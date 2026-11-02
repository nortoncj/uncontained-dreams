<?php
/**
 * DIY Plans & Consulting: the plans list form ([ucd_plans_form]), its handler,
 * the booking links, and the thank-you page helpers.
 *
 * Signups are saved under Messages, emailed to the contact form address, and,
 * when Settings > General > Plans list endpoint is set, passed on to the
 * mailing list service (a form-post URL from Mailchimp, ConvertKit and so on)
 * with the same fields the static form sent. Successful signups go to
 * /plans/thank-you/, with ?course=1 when the course box was ticked.
 *
 * Booking buttons are Button blocks with the class ucd-book-discovery or
 * ucd-book-consult. Once a booking link is set under Settings > General they
 * open it in a new tab; until then they keep the link typed in the editor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ucd_plans_projects() {
	return array(
		'full-time-home'  => 'Full-time home',
		'guest-house-adu' => 'Guest house or ADU',
		'cabin-getaway'   => 'Cabin or getaway',
		'rental'          => 'Rental',
		'researching'     => 'Just researching',
	);
}

function ucd_plans_states() {
	return array( 'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'District of Columbia', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming', 'Outside the U.S.' );
}

function ucd_plans_error_messages() {
	return array(
		'first_name' => 'Enter your first name.',
		'email'      => 'Enter an email like name@example.com.',
		'state'      => 'Choose your state.',
		'project'    => 'Choose what you\'re planning to build.',
	);
}

function ucd_plans_thank_you_url( $course = false ) {
	return add_query_arg( $course ? array( 'course' => '1' ) : array(), home_url( '/plans/thank-you/' ) );
}

/*
 * Form
 */

function ucd_plans_form_shortcode() {
	wp_enqueue_script( 'ucd-plans', ucd_get_asset( 'js/plans.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

	// After a failed no-JavaScript submission: a token for what was typed and what went wrong.
	$state = array();
	if ( isset( $_GET['ucd_plans'] ) ) {
		$state = get_transient( 'ucd_plans_' . sanitize_key( wp_unslash( $_GET['ucd_plans'] ) ) );
		$state = is_array( $state ) ? $state : array();
	}
	$values   = wp_parse_args(
		isset( $state['values'] ) ? $state['values'] : array(),
		array(
			'first_name' => '',
			'email'      => '',
			'phone'      => '',
			'state'      => '',
			'project'    => '',
			'course'     => false,
		)
	);
	$errors   = isset( $state['errors'] ) ? $state['errors'] : array();
	$messages = ucd_plans_error_messages();
	$invalid  = function ( $field ) use ( $errors ) {
		return in_array( $field, $errors, true ) ? ' aria-invalid="true"' : '';
	};
	$hidden   = function ( $field ) use ( $errors ) {
		return in_array( $field, $errors, true ) ? '' : ' hidden';
	};

	ob_start();
	?>
<form id="plans-form" class="ucd-contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate>
	<input type="hidden" name="action" value="ucd_plans">
	<input type="hidden" name="ucd_started" value="<?php echo esc_attr( time() ); ?>">
	<div class="ucd-contact-form__trap" aria-hidden="true"><label for="p-website">Website</label><input id="p-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>

	<div class="form-alert" id="plans-alert" role="alert"<?php echo $hidden( 'send' ); ?>>That didn't go through. Try again, or email us at [EMAIL].</div>

	<div class="field">
		<label for="p-first">First name <span class="req" aria-hidden="true">*</span></label>
		<input id="p-first" name="first_name" type="text" autocomplete="given-name" required aria-describedby="p-first-err" value="<?php echo esc_attr( $values['first_name'] ); ?>"<?php echo $invalid( 'first_name' ); ?>>
		<span class="err" id="p-first-err"<?php echo $hidden( 'first_name' ); ?>><?php echo esc_html( $messages['first_name'] ); ?></span>
	</div>
	<div class="field">
		<label for="p-email">Email <span class="req" aria-hidden="true">*</span></label>
		<input id="p-email" name="email" type="email" autocomplete="email" required aria-describedby="p-email-err" value="<?php echo esc_attr( $values['email'] ); ?>"<?php echo $invalid( 'email' ); ?>>
		<span class="err" id="p-email-err"<?php echo $hidden( 'email' ); ?>><?php echo esc_html( $messages['email'] ); ?></span>
	</div>
	<div class="field">
		<label for="p-phone">Phone <span class="help">(optional)</span></label>
		<input id="p-phone" name="phone" type="tel" autocomplete="tel" value="<?php echo esc_attr( $values['phone'] ); ?>">
	</div>
	<div class="field">
		<label for="p-state">State <span class="req" aria-hidden="true">*</span></label>
		<select id="p-state" name="state" required aria-describedby="p-state-err"<?php echo $invalid( 'state' ); ?>>
			<option value="">Choose one</option>
			<?php foreach ( ucd_plans_states() as $name ) : ?>
			<option<?php selected( $values['state'], $name ); ?>><?php echo esc_html( $name ); ?></option>
			<?php endforeach; ?>
		</select>
		<span class="err" id="p-state-err"<?php echo $hidden( 'state' ); ?>><?php echo esc_html( $messages['state'] ); ?></span>
	</div>
	<div class="field ucd-contact-form__full">
		<label for="p-project">What are you planning to build? <span class="req" aria-hidden="true">*</span></label>
		<select id="p-project" name="project" required aria-describedby="p-project-err"<?php echo $invalid( 'project' ); ?>>
			<option value="">Choose one</option>
			<?php foreach ( ucd_plans_projects() as $key => $label ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $values['project'], $key ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<span class="err" id="p-project-err"<?php echo $hidden( 'project' ); ?>><?php echo esc_html( $messages['project'] ); ?></span>
	</div>
	<label class="ucd-check ucd-contact-form__full" for="p-course"><input id="p-course" name="course" type="checkbox" value="yes"<?php checked( $values['course'] ); ?>>Also tell me when the DIY course opens</label>
	<div class="ucd-contact-form__submit">
		<button type="submit" class="btn primary">Notify me when plans launch</button>
		<p>We'll email you when plans and the course launch. No spam, unsubscribe anytime.</p>
	</div>
</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_plans_form', 'ucd_plans_form_shortcode' );

/*
 * Submission
 *
 * No nonce, for the same reason as the contact form: nothing to forge, and a
 * cached page would block real signups. A hidden field and a minimum fill
 * time catch most bots.
 */

function ucd_plans_submit() {
	// plans.js asks for JSON; a plain form post gets a redirect.
	$json  = isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) ), 'application/json' );
	$back  = remove_query_arg( 'ucd_plans', wp_get_referer() ? wp_get_referer() : home_url( '/plans/' ) );
	$field = function ( $name ) {
		return isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';
	};

	$values = array(
		'first_name' => sanitize_text_field( $field( 'first_name' ) ),
		'email'      => sanitize_email( $field( 'email' ) ),
		'phone'      => sanitize_text_field( $field( 'phone' ) ),
		'state'      => sanitize_text_field( $field( 'state' ) ),
		'project'    => sanitize_key( $field( 'project' ) ),
		'course'     => 'yes' === $field( 'course' ),
	);
	$done   = ucd_plans_thank_you_url( $values['course'] );

	$started = (int) $field( 'ucd_started' );
	if ( '' !== $field( 'website' ) || time() - $started < 3 ) {
		if ( $json ) {
			wp_send_json_success( array( 'redirect' => $done ) );
		}
		wp_safe_redirect( $done );
		exit;
	}

	$errors = array();
	if ( '' === $values['first_name'] ) {
		$errors[] = 'first_name';
	}
	if ( ! is_email( $values['email'] ) ) {
		$errors[] = 'email';
	}
	if ( ! in_array( $values['state'], ucd_plans_states(), true ) ) {
		$errors[] = 'state';
	}
	if ( ! isset( ucd_plans_projects()[ $values['project'] ] ) ) {
		$errors[] = 'project';
	}
	if ( ! $errors && ! ucd_plans_deliver( $values ) ) {
		$errors[] = 'send';
	}

	if ( $errors ) {
		if ( $json ) {
			wp_send_json_error( array( 'fields' => $errors ), in_array( 'send', $errors, true ) ? 500 : 400 );
		}
		$token = strtolower( wp_generate_password( 16, false ) );
		set_transient( 'ucd_plans_' . $token, compact( 'values', 'errors' ), 10 * MINUTE_IN_SECONDS );
		wp_safe_redirect( add_query_arg( 'ucd_plans', $token, $back ) . '#plans-list' );
		exit;
	}

	if ( $json ) {
		wp_send_json_success( array( 'redirect' => $done ) );
	}
	wp_safe_redirect( $done );
	exit;
}
add_action( 'admin_post_nopriv_ucd_plans', 'ucd_plans_submit' );
add_action( 'admin_post_ucd_plans', 'ucd_plans_submit' );

// Saves the signup under Messages, emails it, and passes it to the mailing list. Succeeds if any of them works.
function ucd_plans_deliver( $values ) {
	$project = ucd_plans_projects()[ $values['project'] ];
	$wants   = $values['course'] ? 'Plans and the DIY course' : 'Plans';
	$details = array(
		'Email'             => $values['email'],
		'Phone'             => $values['phone'],
		'State'             => $values['state'],
		'Planning to build' => $project,
		'Topic'             => 'Plans list: ' . $wants,
	);

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'ucd_message',
			'post_status' => 'private',
			'post_title'  => $values['first_name'] . ' · Plans list',
			'meta_input'  => array( '_ucd_details' => $details ),
		)
	);

	// One list with tags, the same fields the static form sent.
	$listed = ucd_mailing_list_add(
		array(
			'first_name' => $values['first_name'],
			'email'      => $values['email'],
			'phone'      => $values['phone'],
			'state'      => $values['state'],
			'project'    => $values['project'],
			'course'     => $values['course'] ? 'yes' : '',
			'tags'       => implode( ',', array_filter( array( 'plans', $values['course'] ? 'course' : '', 'project:' . $values['project'], 'state:' . $values['state'] ) ) ),
		)
	);

	$body = '';
	foreach ( $details as $label => $value ) {
		$body .= $label . ': ' . ( '' === $value ? '-' : $value ) . "\n";
	}
	if ( null !== $listed ) {
		$body .= "\n" . ucd_mailing_list_note( $listed ) . "\n";
	}
	if ( $post_id ) {
		$body .= "\nSaved in WordPress: " . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";
	}
	$reply = str_replace( array( '"', '<', '>', ',', ';' ), '', $values['first_name'] );
	$sent  = wp_mail(
		ucd_contact_recipient(),
		sprintf( 'Plans list signup: %s (%s)', $values['first_name'], $wants ),
		$body,
		array( 'Reply-To: ' . $reply . ' <' . $values['email'] . '>' )
	);

	return (bool) $post_id || $sent || $listed;
}

/**
 * Adds someone to the mailing list (Settings > General > Plans list endpoint),
 * shared by the plans form and the guides newsletter. Returns null when no
 * endpoint is set, otherwise whether the list service accepted it.
 */
function ucd_mailing_list_add( $fields ) {
	$endpoint = get_option( 'ucd_plans_endpoint' );
	if ( ! $endpoint ) {
		return null;
	}
	$response = wp_remote_post(
		$endpoint,
		array(
			'timeout' => 10,
			'headers' => array( 'Accept' => 'application/json' ),
			'body'    => $fields,
		)
	);
	$code = wp_remote_retrieve_response_code( $response );
	return $code >= 200 && $code < 400;
}

function ucd_mailing_list_note( $listed ) {
	return $listed ? 'Added to the mailing list.' : 'Not added to the mailing list (the list service didn\'t accept it). Add them by hand.';
}

/*
 * Booking links
 */

// Points Button blocks with the ucd-book-discovery or ucd-book-consult class at the booking link, once there is one.
function ucd_plans_booking_buttons( $content, $block ) {
	$classes = isset( $block['attrs']['className'] ) ? explode( ' ', $block['attrs']['className'] ) : array();
	foreach ( array( 'discovery', 'consult' ) as $type ) {
		$url = get_option( 'ucd_book_' . $type );
		if ( $url && in_array( 'ucd-book-' . $type, $classes, true ) ) {
			$tags = new WP_HTML_Tag_Processor( $content );
			if ( $tags->next_tag( 'a' ) ) {
				$tags->set_attribute( 'href', esc_url( $url ) );
				$tags->set_attribute( 'target', '_blank' );
				$tags->set_attribute( 'rel', 'noopener' );
			}
			return $tags->get_updated_html();
		}
	}
	return $content;
}
add_filter( 'render_block_core/button', 'ucd_plans_booking_buttons', 10, 2 );

function ucd_plans_settings() {
	$fields = array(
		'ucd_plans_endpoint' => array( 'Plans list endpoint', 'The mailing list service\'s form-post URL, used by the plans form and the guides newsletter. Signups are sent there with first_name, email, phone, state, project, course and tags (plans signups are tagged plans, newsletter signups guides). Leave empty to only save them under Messages and email them to you.' ),
		'ucd_book_discovery' => array( 'Discovery call booking link', 'Your Calendly or Cal.com link for the free call. Leave empty to keep each button\'s own link.' ),
		'ucd_book_consult'   => array( 'Paid consult booking link', 'Your Calendly or Cal.com link for the paid consult. Take payment there, so the site never handles card details. Leave empty to keep each button\'s own link.' ),
	);
	foreach ( $fields as $name => $field ) {
		register_setting(
			'general',
			$name,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'esc_url_raw',
				'default'           => '',
			)
		);
		add_settings_field(
			$name,
			'<label for="' . esc_attr( $name ) . '">' . esc_html( $field[0] ) . '</label>',
			function () use ( $name, $field ) {
				printf( '<input name="%1$s" type="url" id="%1$s" value="%2$s" class="regular-text"><p class="description">%3$s</p>', esc_attr( $name ), esc_attr( get_option( $name ) ), esc_html( $field[1] ) );
			},
			'general'
		);
	}
}
add_action( 'admin_init', 'ucd_plans_settings' );

/*
 * Thank-you page (/plans/thank-you/)
 */

function ucd_is_plans_thank_you() {
	return is_page() && 'plans/thank-you' === get_page_uri( get_queried_object() );
}

function ucd_plans_course_wanted() {
	return isset( $_GET['course'] ) && '1' === $_GET['course'];
}

// [ucd_if_course]...[/ucd_if_course]: shown only when the visitor also asked about the course.
function ucd_if_course_shortcode( $atts, $content = '' ) {
	return ucd_plans_course_wanted() ? do_shortcode( $content ) : '';
}
add_shortcode( 'ucd_if_course', 'ucd_if_course_shortcode' );

// Conversion event. Swap for the site's analytics call once it's chosen.
function ucd_plans_conversion_event() {
	if ( ! ucd_is_plans_thank_you() ) {
		return;
	}
	printf(
		"<script>window.dataLayer = window.dataLayer || []; window.dataLayer.push(%s);</script>\n",
		wp_json_encode(
			array(
				'event' => 'plans_list_signup',
				'tag'   => ucd_plans_course_wanted() ? 'plans + course' : 'plans',
			)
		)
	);
}
add_action( 'wp_footer', 'ucd_plans_conversion_event' );
