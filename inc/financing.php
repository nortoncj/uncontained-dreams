<?php
/**
 * Financing request form: the [ucd_financing_form] shortcode and its handler.
 *
 * Requests are emailed to Settings > General > Financing email (the financing
 * agent; the contact form address when that's empty) and saved under Messages.
 * Arriving from the builder with ?config=, the form shows that build and sends
 * it along, priced here from the build's choices.
 *
 * Without JavaScript the form posts and reloads with the result; financing.js
 * sends it in the background and shows the confirmation in place.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ucd_financing_recipient() {
	$email = get_option( 'ucd_financing_email' );
	return $email ? $email : ucd_contact_recipient();
}

// "Which home?": every model in Build Options, then the two open answers.
function ucd_financing_homes() {
	return array_merge( wp_list_pluck( ucd_build_options()['models'], 'name' ), array( 'A custom build', 'Not sure yet' ) );
}

function ucd_financing_error_messages() {
	return array(
		'first_name' => 'Add your name so we know who we\'re talking to.',
		'last_name'  => 'Add your name so we know who we\'re talking to.',
		'email'      => 'That email doesn\'t look right. Check for typos.',
		'phone'      => 'Add a phone number so a financing representative can reach you.',
		'zip'        => 'Add the 5-digit ZIP code where the home will go.',
	);
}

// A build from the ?config= link the builder writes, cleaned and priced. Empty if there's none.
function ucd_financing_build_from_token( $token ) {
	if ( ! is_string( $token ) || '' === $token ) {
		return array();
	}
	$data = ucd_build_options();
	$cfg  = ucd_build_clean_config( json_decode( (string) base64_decode( strtr( $token, '-_', '+/' ) ), true ), $data );
	return $cfg['m'] ? array( 'data' => $data, 'cfg' => $cfg ) : array();
}

/*
 * Form
 */

function ucd_financing_form_shortcode() {
	wp_enqueue_script( 'ucd-financing', ucd_get_asset( 'js/financing.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

	// Back from a no-JavaScript submission: a success flag, or a token for what was typed and what went wrong.
	$sent  = isset( $_GET['financing'] ) && 'sent' === $_GET['financing'];
	$state = array();
	if ( isset( $_GET['ucd_financing'] ) ) {
		$state = get_transient( 'ucd_financing_' . sanitize_key( wp_unslash( $_GET['ucd_financing'] ) ) );
		$state = is_array( $state ) ? $state : array();
	}
	$token  = isset( $state['values']['build_config'] ) ? $state['values']['build_config'] : ( isset( $_GET['config'] ) ? sanitize_text_field( wp_unslash( $_GET['config'] ) ) : '' );
	$build  = ucd_financing_build_from_token( $token );
	$values = wp_parse_args(
		isset( $state['values'] ) ? $state['values'] : array(),
		array(
			'first_name' => '',
			'last_name'  => '',
			'email'      => '',
			'phone'      => '',
			'zip'        => $build && isset( $build['cfg']['z'] ) ? $build['cfg']['z'] : '',
			'home'       => '',
		)
	);
	if ( '' === $values['home'] && $build ) {
		$values['home'] = ucd_build_find( $build['data']['models'], $build['cfg']['m'] )['name'];
	}
	$errors   = isset( $state['errors'] ) ? $state['errors'] : array();
	$messages = ucd_financing_error_messages();
	$invalid  = function ( $field ) use ( $errors ) {
		return in_array( $field, $errors, true ) ? ' aria-invalid="true"' : '';
	};
	$hidden   = function ( $field ) use ( $errors ) {
		return in_array( $field, $errors, true ) ? '' : ' hidden';
	};

	ob_start();
	?>
<div class="ucd-financing" id="financing-form">
	<form id="fin-form" class="ucd-contact-form ucd-contact-form--white" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate<?php echo $sent ? ' hidden' : ''; ?>>
		<input type="hidden" name="action" value="ucd_financing">
		<input type="hidden" name="ucd_started" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="build_config" value="<?php echo esc_attr( $build ? $token : '' ); ?>">
		<div class="ucd-contact-form__trap" aria-hidden="true"><label for="f-website">Website</label><input id="f-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>

		<h2>Apply for financing</h2>
		<p class="ucd-contact-form__note">Fields marked <span class="req">*</span> are required.</p>
		<?php
		if ( $build ) :
			$res = ucd_build_lines( $build['data'], $build['cfg'] );
			?>
		<p class="ucd-build-chip">Your build: <?php echo esc_html( $res['model']['name'] ); ?>, estimated <?php echo esc_html( ucd_build_money( $res['total'] ) ); ?><?php echo $res['quoted'] ? esc_html( sprintf( ' plus %d quoted item%s', $res['quoted'], 1 === $res['quoted'] ? '' : 's' ) ) : ''; ?>. We'll send it with your request.</p>
		<?php endif; ?>
		<div class="form-alert" id="send-error" role="alert"<?php echo $hidden( 'send' ); ?>>Your request didn't go through. Try again, or call us at <a href="tel:[PHONE]">[PHONE]</a>.</div>

		<div class="field">
			<label for="f-first">First name <span class="req" aria-hidden="true">*</span></label>
			<input id="f-first" name="first_name" type="text" autocomplete="given-name" required aria-describedby="f-first-err" value="<?php echo esc_attr( $values['first_name'] ); ?>"<?php echo $invalid( 'first_name' ); ?>>
			<span class="err" id="f-first-err"<?php echo $hidden( 'first_name' ); ?>><?php echo esc_html( $messages['first_name'] ); ?></span>
		</div>
		<div class="field">
			<label for="f-last">Last name <span class="req" aria-hidden="true">*</span></label>
			<input id="f-last" name="last_name" type="text" autocomplete="family-name" required aria-describedby="f-last-err" value="<?php echo esc_attr( $values['last_name'] ); ?>"<?php echo $invalid( 'last_name' ); ?>>
			<span class="err" id="f-last-err"<?php echo $hidden( 'last_name' ); ?>><?php echo esc_html( $messages['last_name'] ); ?></span>
		</div>
		<div class="field ucd-contact-form__full">
			<label for="f-email">Email <span class="req" aria-hidden="true">*</span></label>
			<input id="f-email" name="email" type="email" autocomplete="email" required aria-describedby="f-email-err" value="<?php echo esc_attr( $values['email'] ); ?>"<?php echo $invalid( 'email' ); ?>>
			<span class="err" id="f-email-err"<?php echo $hidden( 'email' ); ?>><?php echo esc_html( $messages['email'] ); ?></span>
		</div>
		<div class="field">
			<label for="f-phone">Phone <span class="req" aria-hidden="true">*</span></label>
			<input id="f-phone" name="phone" type="tel" autocomplete="tel" required aria-describedby="f-phone-err" value="<?php echo esc_attr( $values['phone'] ); ?>"<?php echo $invalid( 'phone' ); ?>>
			<span class="err" id="f-phone-err"<?php echo $hidden( 'phone' ); ?>><?php echo esc_html( $messages['phone'] ); ?></span>
		</div>
		<div class="field">
			<label for="f-zip">ZIP code <span class="req" aria-hidden="true">*</span></label>
			<input id="f-zip" name="zip" type="text" inputmode="numeric" autocomplete="postal-code" maxlength="5" required aria-describedby="f-zip-err" value="<?php echo esc_attr( $values['zip'] ); ?>"<?php echo $invalid( 'zip' ); ?>>
			<span class="err" id="f-zip-err"<?php echo $hidden( 'zip' ); ?>><?php echo esc_html( $messages['zip'] ); ?></span>
		</div>
		<div class="field ucd-contact-form__full">
			<label for="f-home">Which home?</label>
			<select id="f-home" name="home">
				<?php foreach ( ucd_financing_homes() as $home ) : ?>
				<option<?php selected( $values['home'], $home ); ?>><?php echo esc_html( $home ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="ucd-contact-form__submit">
			<button type="submit" class="btn primary">Apply for financing</button>
			<p>Submitting doesn't commit you to anything. Financing is arranged through [FINANCING AGENT / LENDER]. UnContained Dreams does not make credit decisions.</p>
		</div>
	</form>

	<div class="ucd-fin-success" id="fin-success" role="status" tabindex="-1"<?php echo $sent ? '' : ' hidden'; ?>>
		<h2>Got it.</h2>
		<p>A financing representative will call or email you within [1–2 business days].</p>
		<p class="ucd-fin-success__next">While you wait: <a href="<?php echo esc_url( home_url( '/build/' ) ); ?>">build your home</a>, <a href="<?php echo esc_url( home_url( '/how-it-works/' ) ); ?>">see how it works</a>, or <a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">read the FAQ</a>.</p>
	</div>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_financing_form', 'ucd_financing_form_shortcode' );

/*
 * Submission
 *
 * No nonce, for the same reason as the contact form: nothing to forge, and a
 * cached page would block real requests. A hidden field and a minimum fill
 * time catch most bots.
 */

function ucd_financing_submit() {
	// financing.js asks for JSON; a plain form post gets a redirect.
	$json  = isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) ), 'application/json' );
	$back  = remove_query_arg( array( 'ucd_financing', 'financing' ), wp_get_referer() ? wp_get_referer() : home_url( '/financing/' ) );
	$field = function ( $name ) {
		return isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';
	};

	$started = (int) $field( 'ucd_started' );
	if ( '' !== $field( 'website' ) || time() - $started < 3 ) {
		if ( $json ) {
			wp_send_json_success();
		}
		wp_safe_redirect( add_query_arg( 'financing', 'sent', $back ) . '#financing-form' );
		exit;
	}

	$home   = sanitize_text_field( $field( 'home' ) );
	$values = array(
		'first_name'   => sanitize_text_field( $field( 'first_name' ) ),
		'last_name'    => sanitize_text_field( $field( 'last_name' ) ),
		'email'        => sanitize_email( $field( 'email' ) ),
		'phone'        => sanitize_text_field( $field( 'phone' ) ),
		'zip'          => sanitize_text_field( $field( 'zip' ) ),
		'home'         => in_array( $home, ucd_financing_homes(), true ) ? $home : 'Not sure yet',
		'build_config' => sanitize_text_field( $field( 'build_config' ) ),
	);

	$errors = array();
	foreach ( array( 'first_name', 'last_name' ) as $name ) {
		if ( '' === $values[ $name ] ) {
			$errors[] = $name;
		}
	}
	if ( ! is_email( $values['email'] ) ) {
		$errors[] = 'email';
	}
	if ( strlen( preg_replace( '/\D/', '', $values['phone'] ) ) < 10 ) {
		$errors[] = 'phone';
	}
	if ( ! preg_match( '/^\d{5}$/', $values['zip'] ) ) {
		$errors[] = 'zip';
	}
	if ( ! $errors && ! ucd_financing_deliver( $values ) ) {
		$errors[] = 'send';
	}

	if ( $errors ) {
		if ( $json ) {
			wp_send_json_error( array( 'fields' => $errors ), in_array( 'send', $errors, true ) ? 500 : 400 );
		}
		$token = strtolower( wp_generate_password( 16, false ) );
		set_transient( 'ucd_financing_' . $token, compact( 'values', 'errors' ), 10 * MINUTE_IN_SECONDS );
		wp_safe_redirect( add_query_arg( 'ucd_financing', $token, $back ) . '#financing-form' );
		exit;
	}

	if ( $json ) {
		wp_send_json_success();
	}
	wp_safe_redirect( add_query_arg( 'financing', 'sent', $back ) . '#financing-form' );
	exit;
}
add_action( 'admin_post_nopriv_ucd_financing', 'ucd_financing_submit' );
add_action( 'admin_post_ucd_financing', 'ucd_financing_submit' );

// Saves the request under Messages and emails it to the financing agent. Succeeds if either works.
function ucd_financing_deliver( $values ) {
	$name    = $values['first_name'] . ' ' . $values['last_name'];
	$build   = ucd_financing_build_from_token( $values['build_config'] );
	$details = array(
		'Email'      => $values['email'],
		'Phone'      => $values['phone'],
		'ZIP code'   => $values['zip'],
		'Which home' => $values['home'],
		'Topic'      => 'Financing request',
	);
	$summary = '';
	if ( $build ) {
		$details['Build from the builder'] = ucd_build_config_url( $build['cfg'] );
		$summary                           = ucd_build_summary_text( $build['data'], $build['cfg'] );
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'ucd_message',
			'post_status'  => 'private',
			'post_title'   => $name . ' · Financing request',
			'post_content' => $summary,
			'meta_input'   => array( '_ucd_details' => $details ),
		)
	);

	$body = '';
	foreach ( $details as $label => $value ) {
		$body .= $label . ': ' . $value . "\n";
	}
	if ( $summary ) {
		$body .= "\nTheir build:\n{$summary}\n";
	}
	if ( $post_id ) {
		$body .= "\nSaved in WordPress: " . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";
	}
	$reply = str_replace( array( '"', '<', '>', ',', ';' ), '', $name );
	$sent  = wp_mail(
		ucd_financing_recipient(),
		sprintf( 'Financing request: %s (%s)', $name, $values['home'] ),
		$body,
		array( 'Reply-To: ' . $reply . ' <' . $values['email'] . '>' )
	);

	return (bool) $post_id || $sent;
}

function ucd_financing_settings() {
	register_setting(
		'general',
		'ucd_financing_email',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_email',
			'default'           => '',
		)
	);
	add_settings_field( 'ucd_financing_email', '<label for="ucd_financing_email">Financing email</label>', 'ucd_financing_email_field', 'general' );
}
add_action( 'admin_init', 'ucd_financing_settings' );

function ucd_financing_email_field() {
	printf(
		'<input name="ucd_financing_email" type="email" id="ucd_financing_email" value="%1$s" class="regular-text" placeholder="%2$s"><p class="description">Where financing requests are sent, usually the financing agent. Leave empty to use the contact form email. Every request is also saved under Messages.</p>',
		esc_attr( get_option( 'ucd_financing_email' ) ),
		esc_attr( ucd_contact_recipient() )
	);
}
