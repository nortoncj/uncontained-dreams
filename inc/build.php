<?php
/**
 * Build Your Home: the configurator, review and confirmation pages, the REST
 * endpoints that receive builds, and the Builds screen in wp-admin.
 *
 * Pages (each a Shortcode block under a hero pattern):
 * - /build/               [ucd_build_configurator]
 * - /build/review/        [ucd_build_review]
 * - /build/confirmation/  [ucd_build_receipt] and [ucd_if_financing]...[/ucd_if_financing]
 *
 * Prices are recalculated here from the build's choices, never taken from the
 * browser, and every emailed link is built here too. The calculations mirror
 * assets/js/build-config.js; change both together.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ucd_build_urls() {
	return array(
		'build'        => home_url( '/build/' ),
		'review'       => home_url( '/build/review/' ),
		'confirmation' => home_url( '/build/confirmation/' ),
		'financing'    => home_url( '/financing/' ),
	);
}

// Site-relative links ("/gallery/") become full URLs.
function ucd_build_link( $value ) {
	return 0 === strpos( (string) $value, '/' ) ? home_url( $value ) : $value;
}

// Everything build-config.js needs, handed to the page as window.UCDBuildData.
function ucd_build_page_data() {
	$data = ucd_build_options();
	foreach ( $data['models'] as &$model ) {
		$model['tourUrl']   = ucd_build_link( $model['tourUrl'] );
		$model['photosUrl'] = ucd_build_link( $model['photosUrl'] );
	}
	unset( $model );
	$data['urls']      = ucd_build_urls();
	$data['endpoints'] = array(
		'submitBuild' => rest_url( 'ucd/v1/builds' ),
		'emailBuild'  => rest_url( 'ucd/v1/builds/email' ),
	);
	return $data;
}

function ucd_build_enqueue( $script ) {
	if ( ! wp_script_is( 'ucd-build-config', 'registered' ) ) {
		wp_register_script( 'ucd-build-config', ucd_get_asset( 'js/build-config.js' ), array(), UCD_VERSION, true );
		wp_add_inline_script( 'ucd-build-config', 'window.UCDBuildData = ' . wp_json_encode( ucd_build_page_data() ) . ';', 'before' );
	}
	wp_enqueue_script( 'ucd-' . $script, ucd_get_asset( 'js/' . $script . '.js' ), array( 'ucd-build-config' ), UCD_VERSION, true );
}

/*
 * Prices (mirrors build-config.js)
 */

function ucd_build_find( $items, $id ) {
	foreach ( (array) $items as $item ) {
		if ( $item['id'] === $id ) {
			return $item;
		}
	}
	return null;
}

function ucd_build_available( $item, $model_id ) {
	return ! $model_id || empty( $item['excludeModels'] ) || ! in_array( $model_id, $item['excludeModels'], true );
}

// A build state from the browser, cut down to known ids and plain values, with defaults filled in.
function ucd_build_clean_config( $raw, $data ) {
	$raw   = is_array( $raw ) ? $raw : array();
	$model = ucd_build_find( $data['models'], isset( $raw['m'] ) ? (string) $raw['m'] : '' );
	$cfg   = array(
		'm' => $model ? $model['id'] : '',
		'o' => array(),
		'u' => array(),
	);
	$picks = isset( $raw['o'] ) && is_array( $raw['o'] ) ? $raw['o'] : array();
	foreach ( array_keys( ucd_build_steps() ) as $step ) {
		foreach ( isset( $data['steps'][ $step ] ) ? $data['steps'][ $step ] : array() as $group ) {
			$picked = isset( $picks[ $group['id'] ] ) ? ucd_build_find( $group['choices'], (string) $picks[ $group['id'] ] ) : null;
			if ( $picked && ucd_build_available( $picked, $cfg['m'] ) ) {
				$cfg['o'][ $group['id'] ] = $picked['id'];
			} elseif ( ! empty( $group['default'] ) ) {
				$cfg['o'][ $group['id'] ] = $group['default'];
			}
		}
	}
	foreach ( isset( $raw['u'] ) && is_array( $raw['u'] ) ? $raw['u'] : array() as $id ) {
		$upgrade = ucd_build_find( $data['upgrades'], (string) $id );
		if ( $upgrade && ucd_build_available( $upgrade, $cfg['m'] ) && ! in_array( $upgrade['id'], $cfg['u'], true ) ) {
			$cfg['u'][] = $upgrade['id'];
		}
	}
	if ( isset( $raw['z'] ) && preg_match( '/^\d{5}$/', (string) $raw['z'] ) ) {
		$cfg['z']   = (string) $raw['z'];
		$cfg['loc'] = isset( $raw['loc'] ) ? sanitize_text_field( substr( (string) $raw['loc'], 0, 80 ) ) : '';
		$cfg['mi']  = isset( $raw['mi'] ) && is_numeric( $raw['mi'] ) ? max( 0, (int) $raw['mi'] ) : null;
	}
	if ( ! empty( $raw['intl'] ) ) {
		$cfg['intl'] = true;
	}
	if ( ! empty( $raw['nosite'] ) ) {
		$cfg['nosite'] = true;
	}
	return $cfg;
}

/**
 * Line items and totals. price is a number, 0 (included), null (quoted) or
 * false (delivery not set yet, the JS version's undefined).
 */
function ucd_build_lines( $data, $cfg ) {
	$model = ucd_build_find( $data['models'], $cfg['m'] );
	$items = array();
	if ( ! $model ) {
		return array(
			'items'  => $items,
			'total'  => 0,
			'quoted' => 0,
			'model'  => null,
		);
	}
	$items[] = array(
		'label' => $model['name'] . ', base',
		'price' => (int) $model['basePrice'],
		'base'  => true,
	);
	foreach ( array_keys( ucd_build_steps() ) as $step ) {
		foreach ( isset( $data['steps'][ $step ] ) ? $data['steps'][ $step ] : array() as $group ) {
			$choice = isset( $cfg['o'][ $group['id'] ] ) ? ucd_build_find( $group['choices'], $cfg['o'][ $group['id'] ] ) : null;
			if ( $choice ) {
				$items[] = array(
					'label' => $group['label'] . ': ' . $choice['label'],
					'price' => $choice['price'],
				);
			}
		}
	}
	foreach ( $cfg['u'] as $id ) {
		$upgrade = ucd_build_find( $data['upgrades'], $id );
		if ( $upgrade ) {
			$items[] = array(
				'label' => $upgrade['label'],
				'price' => $upgrade['price'],
			);
		}
	}
	$items[] = array(
		'label' => 'Custom furniture',
		'price' => 0,
	);

	if ( ! empty( $cfg['intl'] ) ) {
		$delivery = array( 'International delivery', null );
	} elseif ( empty( $cfg['z'] ) || ! isset( $cfg['mi'] ) || null === $cfg['mi'] ) {
		$delivery = array( 'Delivery', false );
	} else {
		$delivery = array( 'Delivery to ' . ( $cfg['loc'] ? $cfg['loc'] : $cfg['z'] ), $cfg['mi'] <= $data['includedMiles'] ? 0 : null );
	}
	$items[] = array(
		'label'    => $delivery[0],
		'price'    => $delivery[1],
		'delivery' => true,
	);

	$total  = 0;
	$quoted = 0;
	foreach ( $items as $item ) {
		if ( is_int( $item['price'] ) ) {
			$total += $item['price'];
		} elseif ( null === $item['price'] ) {
			++$quoted;
		}
	}
	return array(
		'items'  => $items,
		'total'  => $total,
		'quoted' => $quoted,
		'model'  => $model,
	);
}

function ucd_build_money( $amount ) {
	return '$' . number_format( (float) $amount );
}

function ucd_build_price_label( $item ) {
	if ( false === $item['price'] ) {
		return 'Not set';
	}
	if ( null === $item['price'] ) {
		return empty( $item['delivery'] ) ? 'Quoted' : 'Quoted separately';
	}
	if ( 0 === $item['price'] ) {
		return 'Included';
	}
	return ( empty( $item['base'] ) ? '+' : '' ) . ucd_build_money( $item['price'] );
}

// Plain-text summary for emails and the Builds screen. Labels match the on-screen summary and review
// page (base price without a +, "Quoted separately" for delivery), unlike summaryText() in build-config.js.
function ucd_build_summary_text( $data, $cfg ) {
	$res  = ucd_build_lines( $data, $cfg );
	$rows = array();
	foreach ( $res['items'] as $item ) {
		$rows[] = '- ' . $item['label'] . ': ' . ucd_build_price_label( $item );
	}
	$rows[] = 'Estimated total: ' . ucd_build_money( $res['total'] ) . ( $res['quoted'] ? sprintf( ' plus %d quoted item%s', $res['quoted'], 1 === $res['quoted'] ? '' : 's' ) : '' );
	if ( ! empty( $cfg['nosite'] ) ) {
		$rows[] = 'No site yet';
	}
	return implode( "\n", $rows );
}

// A link that reopens the build, in the same ?config= format build-config.js writes.
function ucd_build_config_url( $cfg ) {
	$state = array(
		'm' => $cfg['m'],
		'o' => (object) $cfg['o'],
		'u' => $cfg['u'],
	);
	foreach ( array( 'z', 'loc', 'mi', 'intl', 'nosite' ) as $key ) {
		if ( isset( $cfg[ $key ] ) ) {
			$state[ $key ] = $cfg[ $key ];
		}
	}
	$token = rtrim( strtr( base64_encode( wp_json_encode( $state ) ), '+/', '-_' ), '=' );
	return add_query_arg( 'config', $token, ucd_build_urls()['build'] );
}

function ucd_build_number( $post_id ) {
	return sprintf( 'UCD-%04d', $post_id );
}

/*
 * Pages
 */

function ucd_build_configurator_shortcode() {
	ucd_build_enqueue( 'build' );
	ob_start();
	?>
<div class="ucd-build alignfull">
	<nav class="stepbar" aria-label="Build steps">
		<div class="wrap">
			<ol>
				<li><button type="button" data-goto="model"><span class="num">1</span>Model</button></li>
				<li><button type="button" data-goto="exterior"><span class="num">2</span>Exterior</button></li>
				<li><button type="button" data-goto="interior"><span class="num">3</span>Interior</button></li>
				<li><button type="button" data-goto="upgrades"><span class="num">4</span>Upgrades</button></li>
				<li><button type="button" data-goto="delivery"><span class="num">5</span>Delivery</button></li>
				<li><button type="button" data-goto="review"><span class="num">6</span>Review</button></li>
			</ol>
		</div>
	</nav>

	<div class="wrap">
		<div class="result result--error" id="load-error" role="alert" hidden>We couldn't load prices right now. Refresh the page, or call <a href="tel:[PHONE]">[PHONE]</a> for a quote.</div>
		<noscript><div class="result result--error">The builder needs JavaScript. Call <a href="tel:[PHONE]">[PHONE]</a> and we'll price your home with you.</div></noscript>

		<div class="cfg-layout" id="cfg" hidden>
			<div>
				<section class="cfg-step" data-step="model" aria-labelledby="h-model">
					<h2 id="h-model" tabindex="-1">Start with a model.</h2>
					<p class="cfg-helper">Every model comes fully furnished with custom furniture built for the space.</p>
					<p class="cfg-err" id="err-model" role="alert" hidden>Pick a model to start building.</p>
					<div class="model-list" id="model-list"></div>
				</section>

				<section class="cfg-step" data-step="exterior" aria-labelledby="h-exterior" hidden>
					<h2 id="h-exterior" tabindex="-1">How it looks from the street.</h2>
					<div class="notice" id="notice-exterior" role="status" hidden></div>
					<div class="cfg-groups" id="groups-exterior"></div>
				</section>

				<section class="cfg-step" data-step="interior" aria-labelledby="h-interior" hidden>
					<h2 id="h-interior" tabindex="-1">The inside, your way.</h2>
					<div class="notice" id="notice-interior" role="status" hidden></div>
					<div class="cfg-groups" id="groups-interior"></div>
				</section>

				<section class="cfg-step" data-step="upgrades" aria-labelledby="h-upgrades" hidden>
					<h2 id="h-upgrades" tabindex="-1">Add what you need.</h2>
					<p class="cfg-helper">Every upgrade shows its price before you add it. Skip any of them, the home is complete without them.</p>
					<div class="notice" id="notice-upgrades" role="status" hidden></div>
					<div class="upg-grid" id="upgrade-list"></div>
				</section>

				<section class="cfg-step" data-step="delivery" aria-labelledby="h-delivery" hidden>
					<h2 id="h-delivery" tabindex="-1">Where is your home going?</h2>
					<fieldset class="opt-group">
						<legend class="screen-reader-text">Delivery destination</legend>
						<div class="opt-grid opt-grid--wide">
							<label class="opt"><input type="radio" name="dest" value="us" checked><span class="opt__name">In the U.S.</span><span class="opt__price">All 50 states</span></label>
							<label class="opt"><input type="radio" name="dest" value="intl"><span class="opt__name">Outside the U.S.</span><span class="opt__price">Canada, USVI, and more</span></label>
						</div>
					</fieldset>
					<div class="zip-block" id="zip-block">
						<div class="zip-row">
							<label class="field" for="zip">Delivery ZIP code
								<input id="zip" type="text" inputmode="numeric" autocomplete="postal-code" maxlength="5" pattern="[0-9]{5}" aria-describedby="zip-help zip-err">
							</label>
							<button class="btn ghost" type="button" id="zip-check">Check delivery</button>
						</div>
						<span class="sum-note" id="zip-help">We use this to estimate delivery. You can change it later.</span>
						<p class="cfg-err" id="zip-err" role="alert" hidden></p>
					</div>
					<div class="result" id="delivery-result" role="status" hidden></div>
					<label class="check"><input type="checkbox" id="nosite"><span>I don't have a site yet.<small>That's fine. We'll talk through site options on our call.</small></span></label>
				</section>

				<div class="cfg-foot">
					<button class="btn ghost" type="button" id="back-btn">Back</button>
					<button class="btn primary" type="button" id="next-btn">Next step</button>
				</div>
			</div>

			<aside class="summary" id="summary" aria-labelledby="h-summary">
				<button class="btn ghost sum-close" type="button" id="sum-close">Close</button>
				<h2 id="h-summary">Your build</h2>
				<p class="sum-note sum-empty" id="sum-empty">Pick a model to see your price.</p>
				<ul class="sum-lines" id="sum-lines"></ul>
				<div class="sum-total"><span>Estimated total</span><strong id="sum-total">$0</strong></div>
				<p class="sum-note" id="sum-quoted" hidden></p>
				<p class="sum-note">Final price is confirmed in your written quote. Site prep, foundation, and permits aren't included [CONFIRM].</p>
				<p class="sum-finance">Want to finance it? <a href="<?php echo esc_url( ucd_build_urls()['financing'] ); ?>" id="fin-link">Apply for financing</a></p>
				<button class="btn primary" type="button" id="sum-next">Next step</button>
				<button class="link-btn" type="button" id="share-toggle" aria-expanded="false" aria-controls="share-pop">Save or share this build</button>
				<div class="share-pop" id="share-pop" hidden>
					<p>Copy this link to come back to your build or send it to someone.</p>
					<div class="share-row">
						<input id="share-link" type="text" readonly aria-label="Link to this build">
						<button class="btn primary" type="button" id="share-copy">Copy link</button>
					</div>
					<p class="sum-note">Anyone with the link sees your choices, not your contact info.</p>
					<form class="share-email" id="share-email" novalidate>
						<label for="share-email-input">Email me this build</label>
						<div class="ucd-contact-form__trap" aria-hidden="true"><label for="share-website">Website</label><input id="share-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
						<div class="share-row">
							<input id="share-email-input" type="email" autocomplete="email" placeholder="Email">
							<button class="btn ghost" type="submit">Send it</button>
						</div>
						<p class="sum-note" id="share-email-msg" role="status"></p>
					</form>
				</div>
			</aside>
		</div>
	</div>

	<div class="sum-bar" id="sum-bar" hidden>
		<span><strong id="bar-total">$0</strong> estimated</span>
		<button type="button" id="bar-open" aria-controls="summary" aria-expanded="false">See details</button>
	</div>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_build_configurator', 'ucd_build_configurator_shortcode' );

function ucd_build_move_in_options() {
	return array( 'Within 6 months', '6 to 12 months', 'More than a year', 'Just exploring' );
}

function ucd_build_land_options() {
	return array( 'Yes', 'Buying soon', 'Not yet' );
}

function ucd_build_review_shortcode() {
	ucd_build_enqueue( 'build-review' );
	ob_start();
	?>
<div class="ucd-build ucd-build-review alignfull">
	<div class="wrap">
		<div class="form-alert" id="load-error" role="alert" hidden>We couldn't load prices right now. Refresh the page, or call <a href="tel:[PHONE]">[PHONE]</a> for a quote.</div>
		<div class="form-alert form-alert--info" id="no-build" hidden>There's no build to review yet. <a href="<?php echo esc_url( ucd_build_urls()['build'] ); ?>">Start your build</a> and it takes about five minutes.</div>

		<div class="review-layout" id="review" hidden>
			<section class="rv" aria-labelledby="h-summary">
				<h2 id="h-summary">Your build</h2>
				<div id="rv-blocks"></div>
				<div class="rv-total"><span>Estimated total</span><strong id="rv-total">$0</strong></div>
				<p class="note" id="rv-quoted" hidden></p>
				<p class="note rv-fine">Final price is confirmed in your written quote. Site prep, foundation, and permits aren't included [CONFIRM].</p>
			</section>

			<form id="quote-form" class="quote-form" novalidate>
				<input type="hidden" name="ucd_started" value="<?php echo esc_attr( time() ); ?>">
				<div class="ucd-contact-form__trap" aria-hidden="true"><label for="q-website">Website</label><input id="q-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
				<h2 class="full">Where should we send your quote?</h2>
				<p class="full note">Fields marked <span class="req">*</span> are required.</p>
				<div class="form-alert full" id="send-error" role="alert" hidden>Your build didn't go through. Try again, or call us at <a href="tel:[PHONE]">[PHONE]</a>.</div>

				<div class="field">
					<label for="q-first">First name <span class="req" aria-hidden="true">*</span></label>
					<input id="q-first" name="first_name" type="text" autocomplete="given-name" required aria-describedby="q-first-err">
					<span class="err" id="q-first-err" hidden>Add your name so we know who we're talking to.</span>
				</div>
				<div class="field">
					<label for="q-last">Last name <span class="req" aria-hidden="true">*</span></label>
					<input id="q-last" name="last_name" type="text" autocomplete="family-name" required aria-describedby="q-last-err">
					<span class="err" id="q-last-err" hidden>Add your name so we know who we're talking to.</span>
				</div>
				<div class="field">
					<label for="q-email">Email <span class="req" aria-hidden="true">*</span></label>
					<input id="q-email" name="email" type="email" autocomplete="email" required aria-describedby="q-email-err">
					<span class="err" id="q-email-err" hidden>That email doesn't look right. Check for typos.</span>
				</div>
				<div class="field">
					<label for="q-phone">Phone <span class="req" aria-hidden="true">*</span></label>
					<input id="q-phone" name="phone" type="tel" autocomplete="tel" required aria-describedby="q-phone-err">
					<span class="err" id="q-phone-err" hidden>Add a phone number so we can call you about your build.</span>
				</div>
				<div class="field full">
					<label for="q-city">Delivery city and state <span class="req" aria-hidden="true">*</span></label>
					<input id="q-city" name="delivery_city_state" type="text" placeholder="City, state" autocomplete="address-level2" required aria-describedby="q-city-err">
					<span class="err" id="q-city-err" hidden>Add the city and state where the home will go.</span>
				</div>
				<div class="field full">
					<label for="q-when">When do you want to move in?</label>
					<select id="q-when" name="move_in">
						<option value="">Choose one</option>
						<?php foreach ( ucd_build_move_in_options() as $option ) : ?>
						<option><?php echo esc_html( $option ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<fieldset class="field full">
					<legend>Do you have land for the home?</legend>
					<div class="choice-row">
						<?php foreach ( ucd_build_land_options() as $option ) : ?>
						<label class="choice"><input type="radio" name="land" value="<?php echo esc_attr( $option ); ?>"><span><?php echo esc_html( $option ); ?></span></label>
						<?php endforeach; ?>
					</div>
				</fieldset>
				<label class="check full"><input type="checkbox" name="financing" value="yes" id="q-financing"><span>I'm interested in financing.<small>A financing representative will reach out separately.</small></span></label>
				<div class="field full">
					<label for="q-notes">Anything else we should know?</label>
					<textarea id="q-notes" name="notes" placeholder="HOA rules, site details, questions."></textarea>
				</div>
				<div class="full quote-consent">
					<label class="check"><input type="checkbox" name="contact_consent" value="yes" id="q-consent" required aria-describedby="q-consent-err"><span>You can call or text me about my build. <span class="req" aria-hidden="true">*</span><small>Message and data rates may apply. Reply STOP to opt out of texts.</small></span></label>
					<span class="err" id="q-consent-err" hidden>Check this box so we can call or text you about your build.</span>
				</div>
				<div class="full quote-submit">
					<button type="submit" class="btn primary">Send my build</button>
					<p class="note">No payment today. Your price is confirmed in a written quote before you sign anything. We only ask for contact info. Never your SSN or bank details.</p>
				</div>
			</form>
		</div>
	</div>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_build_review', 'ucd_build_review_shortcode' );

// Confirmation page: filled in by build-confirmation.js from what the review page saved in this browser tab.
function ucd_build_receipt_shortcode() {
	wp_enqueue_script( 'ucd-build-confirmation', ucd_get_asset( 'js/build-confirmation.js' ), array(), UCD_VERSION, true );
	return '<p class="ucd-build-receipt" hidden><span class="chip">Build #<span data-build-number></span></span> We emailed a copy to <strong data-build-email></strong>.</p>';
}
add_shortcode( 'ucd_build_receipt', 'ucd_build_receipt_shortcode' );

// [ucd_if_financing]Text[/ucd_if_financing]: shown only if the buyer ticked "I'm interested in financing".
function ucd_if_financing_shortcode( $atts, $content = '' ) {
	wp_enqueue_script( 'ucd-build-confirmation', ucd_get_asset( 'js/build-confirmation.js' ), array(), UCD_VERSION, true );
	return '<p class="ucd-financing-note" hidden>' . wp_kses_post( trim( $content ) ) . '</p>';
}
add_shortcode( 'ucd_if_financing', 'ucd_if_financing_shortcode' );

/*
 * Receiving builds
 */

function ucd_build_rest_routes() {
	register_rest_route(
		'ucd/v1',
		'/builds',
		array(
			'methods'             => 'POST',
			'callback'            => 'ucd_build_rest_submit',
			'permission_callback' => '__return_true',
		)
	);
	register_rest_route(
		'ucd/v1',
		'/builds/email',
		array(
			'methods'             => 'POST',
			'callback'            => 'ucd_build_rest_email',
			'permission_callback' => '__return_true',
		)
	);
}
add_action( 'rest_api_init', 'ucd_build_rest_routes' );

// Allows $limit requests an hour per visitor (by IP address) for $action.
function ucd_build_rate_ok( $action, $limit ) {
	$ip    = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key   = 'ucd_rate_' . $action . '_' . md5( $ip );
	$count = (int) get_transient( $key );
	if ( $count >= $limit ) {
		return false;
	}
	set_transient( $key, $count + 1, HOUR_IN_SECONDS );
	return true;
}

function ucd_build_rest_submit( WP_REST_Request $request ) {
	$param = function ( $name ) use ( $request ) {
		$value = $request->get_param( $name );
		return is_string( $value ) ? $value : '';
	};

	// Bots fill the hidden website field or post within seconds. Let them think it worked.
	if ( '' !== $param( 'website' ) || time() - (int) $param( 'ucd_started' ) < 3 ) {
		return array( 'buildNumber' => ucd_build_number( wp_rand( 1000, 9999 ) ) );
	}
	if ( ! ucd_build_rate_ok( 'submit', 10 ) ) {
		return new WP_Error( 'ucd_rate_limited', 'Too many builds from here. Call us instead.', array( 'status' => 429 ) );
	}

	$data    = ucd_build_options();
	$cfg     = ucd_build_clean_config( json_decode( $param( 'build_config' ), true ), $data );
	$move_in = sanitize_text_field( $param( 'move_in' ) );
	$land    = sanitize_text_field( $param( 'land' ) );
	$contact = array(
		'first_name' => sanitize_text_field( $param( 'first_name' ) ),
		'last_name'  => sanitize_text_field( $param( 'last_name' ) ),
		'email'      => sanitize_email( $param( 'email' ) ),
		'phone'      => sanitize_text_field( $param( 'phone' ) ),
		'city'       => sanitize_text_field( $param( 'delivery_city_state' ) ),
		'move_in'    => in_array( $move_in, ucd_build_move_in_options(), true ) ? $move_in : '',
		'land'       => in_array( $land, ucd_build_land_options(), true ) ? $land : '',
		'financing'  => 'yes' === $param( 'financing' ),
		'consent'    => 'yes' === $param( 'contact_consent' ),
		'notes'      => sanitize_textarea_field( $param( 'notes' ) ),
	);

	$errors = array();
	foreach ( array( 'first_name', 'last_name', 'city' ) as $field ) {
		if ( '' === $contact[ $field ] ) {
			$errors[] = $field;
		}
	}
	if ( ! is_email( $contact['email'] ) ) {
		$errors[] = 'email';
	}
	if ( strlen( preg_replace( '/\D/', '', $contact['phone'] ) ) < 10 ) {
		$errors[] = 'phone';
	}
	if ( ! $contact['consent'] ) {
		$errors[] = 'contact_consent';
	}
	if ( ! $cfg['m'] ) {
		$errors[] = 'build_config';
	}
	if ( $errors ) {
		return new WP_Error( 'ucd_invalid', 'Some fields need fixing.', array( 'status' => 400, 'fields' => $errors ) );
	}

	$number = ucd_build_save_and_send( $data, $cfg, $contact );
	if ( ! $number ) {
		return new WP_Error( 'ucd_not_sent', 'The build could not be saved or sent.', array( 'status' => 500 ) );
	}
	return array( 'buildNumber' => $number );
}

// Saves the build and emails it to the owner and the buyer. Returns the build number, or '' if neither saving nor the owner email worked.
function ucd_build_save_and_send( $data, $cfg, $contact ) {
	$res     = ucd_build_lines( $data, $cfg );
	$name    = $contact['first_name'] . ' ' . $contact['last_name'];
	$summary = ucd_build_summary_text( $data, $cfg );
	$link    = ucd_build_config_url( $cfg );
	$details = array(
		'Email'                   => $contact['email'],
		'Phone'                   => $contact['phone'],
		'Delivery city and state' => $contact['city'],
		'Move in'                 => $contact['move_in'],
		'Has land'                => $contact['land'],
		'Interested in financing' => $contact['financing'] ? 'Yes' : 'No',
		'OK to call or text'      => 'Yes',
	);

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'ucd_build',
			'post_status'  => 'private',
			'post_title'   => $name . ' · ' . $res['model']['name'],
			'post_content' => $contact['notes'],
			'meta_input'   => array(
				'_ucd_build' => array(
					'details' => $details,
					'summary' => $summary,
					'model'   => $res['model']['name'],
					'total'   => $res['total'],
					'quoted'  => $res['quoted'],
					'link'    => $link,
					'config'  => $cfg,
				),
			),
		)
	);
	$number = $post_id ? ucd_build_number( $post_id ) : 'UCD-' . strtoupper( base_convert( (string) time(), 10, 36 ) );

	$body = "Build {$number}\n\n{$summary}\n\n";
	foreach ( $details as $label => $value ) {
		$body .= $label . ': ' . ( '' === $value ? '-' : $value ) . "\n";
	}
	$body .= "\nNotes:\n" . ( '' === $contact['notes'] ? '-' : $contact['notes'] ) . "\n\nOpen the build: {$link}\n";
	if ( $post_id ) {
		$body .= 'Saved in WordPress: ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";
	}
	$reply = str_replace( array( '"', '<', '>', ',', ';' ), '', $name );
	$sent  = wp_mail(
		ucd_contact_recipient(),
		sprintf( 'New build %s: %s, %s (%s)', $number, $name, $res['model']['name'], ucd_build_money( $res['total'] ) ),
		$body,
		array( 'Reply-To: ' . $reply . ' <' . $contact['email'] . '>' )
	);

	// The buyer's copy, promised on the confirmation page.
	wp_mail(
		$contact['email'],
		sprintf( 'Your UnContained Dreams build (%s)', $number ),
		sprintf( "Thanks, %s. Here's the build you sent us.\n\n%s\n\nOpen it again: %s\n\nWhat happens next: we call you within [1 business day] to go over it, then send a written quote with every cost. Nothing is charged until you sign.\n\nQuestions? Reply to this email or call [PHONE].\n", $contact['first_name'], $summary, $link ),
		array( 'Reply-To: ' . ucd_contact_recipient() )
	);

	return ( $post_id || $sent ) ? $number : '';
}

// "Email me this build": sends the buyer a summary and a link back to their build.
function ucd_build_rest_email( WP_REST_Request $request ) {
	$website = $request->get_param( 'website' );
	if ( is_string( $website ) && '' !== $website ) {
		return array( 'sent' => true );
	}
	$email = sanitize_email( (string) $request->get_param( 'email' ) );
	if ( ! is_email( $email ) ) {
		return new WP_Error( 'ucd_invalid', 'That email doesn\'t look right.', array( 'status' => 400 ) );
	}
	$data = ucd_build_options();
	$cfg  = ucd_build_clean_config( json_decode( (string) $request->get_param( 'config' ), true ), $data );
	if ( ! $cfg['m'] ) {
		return new WP_Error( 'ucd_invalid', 'There\'s no build to send.', array( 'status' => 400 ) );
	}
	// The email can go to any address, so cap how often one visitor can send it.
	if ( ! ucd_build_rate_ok( 'email', 5 ) ) {
		return new WP_Error( 'ucd_rate_limited', 'Too many emails from here.', array( 'status' => 429 ) );
	}
	$sent = wp_mail(
		$email,
		'Your UnContained Dreams build',
		sprintf( "Here's the container home build you saved on our site.\n\n%s\n\nOpen it again or keep changing it: %s\n\nQuestions? Reply to this email or call [PHONE].\n", ucd_build_summary_text( $data, $cfg ), ucd_build_config_url( $cfg ) ),
		array( 'Reply-To: ' . ucd_contact_recipient() )
	);
	return $sent ? array( 'sent' => true ) : new WP_Error( 'ucd_not_sent', 'The email didn\'t send.', array( 'status' => 500 ) );
}

/*
 * Builds screen in wp-admin
 */

function ucd_register_builds() {
	register_post_type(
		'ucd_build',
		array(
			'labels'          => array(
				'name'               => 'Builds',
				'singular_name'      => 'Build',
				'all_items'          => 'All builds',
				'edit_item'          => 'Build',
				'search_items'       => 'Search builds',
				'not_found'          => 'No builds yet.',
				'not_found_in_trash' => 'No builds in the trash.',
			),
			'public'          => false,
			'show_ui'         => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-clipboard',
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			// Editors and admins only; builds only come in through the review page.
			'capabilities'    => array(
				'create_posts' => 'do_not_allow',
				'edit_posts'   => 'edit_others_posts',
			),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'ucd_register_builds' );

function ucd_build_meta_box() {
	add_meta_box( 'ucd-build', 'Build', 'ucd_render_build_box', 'ucd_build', 'normal', 'high' );
}
add_action( 'add_meta_boxes_ucd_build', 'ucd_build_meta_box' );

function ucd_render_build_box( $post ) {
	$build = get_post_meta( $post->ID, '_ucd_build', true );
	$build = is_array( $build ) ? $build : array();
	echo '<table class="form-table" role="presentation"><tbody>';
	printf( '<tr><th scope="row">Build number</th><td><strong>%s</strong></td></tr>', esc_html( ucd_build_number( $post->ID ) ) );
	if ( ! empty( $build['summary'] ) ) {
		printf( '<tr><th scope="row">Build</th><td><pre style="white-space: pre-wrap; margin: 0; font: inherit;">%s</pre></td></tr>', esc_html( $build['summary'] ) );
	}
	if ( ! empty( $build['link'] ) ) {
		printf( '<tr><th scope="row">Open it</th><td><a href="%s" target="_blank" rel="noopener">See this build in the builder</a></td></tr>', esc_url( $build['link'] ) );
	}
	foreach ( isset( $build['details'] ) ? $build['details'] : array() as $label => $value ) {
		printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $label ), ucd_message_value( $label, $value ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in ucd_message_value().
	}
	printf( '<tr><th scope="row">Notes</th><td>%s</td></tr>', $post->post_content ? wpautop( esc_html( $post->post_content ) ) : '-' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped before wpautop.
	echo '</tbody></table>';
}

function ucd_build_columns( $columns ) {
	return array(
		'cb'           => $columns['cb'],
		'ucd_number'   => 'Build',
		'title'        => 'From',
		'ucd_estimate' => 'Estimate',
		'ucd_phone'    => 'Phone',
		'date'         => 'Received',
	);
}
add_filter( 'manage_ucd_build_posts_columns', 'ucd_build_columns' );

function ucd_build_column( $column, $post_id ) {
	$build = get_post_meta( $post_id, '_ucd_build', true );
	$build = is_array( $build ) ? $build : array();
	if ( 'ucd_number' === $column ) {
		echo esc_html( ucd_build_number( $post_id ) );
	} elseif ( 'ucd_estimate' === $column && isset( $build['total'] ) ) {
		echo esc_html( ucd_build_money( $build['total'] ) . ( empty( $build['quoted'] ) ? '' : ' + ' . $build['quoted'] . ' quoted' ) );
	} elseif ( 'ucd_phone' === $column && isset( $build['details']['Phone'] ) ) {
		echo ucd_message_value( 'Phone', $build['details']['Phone'] ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in ucd_message_value().
	}
}
add_action( 'manage_ucd_build_posts_custom_column', 'ucd_build_column', 10, 2 );
