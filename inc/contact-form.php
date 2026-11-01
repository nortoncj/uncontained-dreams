<?php
/**
 * Contact form: the [ucd_contact_form] shortcode, its submission handler, the
 * Messages screen in wp-admin, and the thank-you page helpers.
 *
 * Each message is saved as a private ucd_message post, so a lead isn't lost if
 * the email doesn't arrive, and emailed to the address under Settings >
 * General > Contact form email (the site admin email when that's empty).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ucd_contact_topics() {
	return array(
		'buying'           => 'Buying a finished home',
		'financing'        => 'Financing',
		'diy-consultation' => 'DIY consultation',
		'plans-course'     => 'Building plans or course',
		'other'            => 'Something else',
	);
}

function ucd_contact_reach_options() {
	return array(
		'call'  => 'Call',
		'text'  => 'Text',
		'email' => 'Email',
	);
}

// Shown under a field that fails validation, by the server or by contact-form.js.
function ucd_contact_error_messages() {
	return array(
		'first_name' => 'Add your name so we know who we\'re talking to.',
		'last_name'  => 'Add your name so we know who we\'re talking to.',
		'email'      => 'That email doesn\'t look right. Check for typos.',
		'topic'      => 'Pick what you need help with so the right person calls you back.',
	);
}

function ucd_contact_validate( $values ) {
	$errors = array();
	foreach ( array( 'first_name', 'last_name' ) as $field ) {
		if ( '' === $values[ $field ] ) {
			$errors[] = $field;
		}
	}
	if ( ! is_email( $values['email'] ) ) {
		$errors[] = 'email';
	}
	if ( ! isset( ucd_contact_topics()[ $values['topic'] ] ) ) {
		$errors[] = 'topic';
	}
	return $errors;
}

function ucd_contact_recipient() {
	$email = get_option( 'ucd_contact_email' );
	return $email ? $email : get_option( 'admin_email' );
}

function ucd_contact_thank_you_url( $topic = '', $reach = '' ) {
	return add_query_arg( array_filter( compact( 'topic', 'reach' ) ), home_url( '/contact/thank-you/' ) );
}

/*
 * Form
 */

function ucd_contact_form_shortcode() {
	wp_enqueue_script( 'ucd-contact-form', ucd_get_asset( 'js/contact-form.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

	// After a failed submission the handler sends the visitor back with a token for what they typed and what went wrong.
	$state = array();
	if ( isset( $_GET['ucd_contact'] ) ) {
		$state = get_transient( 'ucd_contact_' . sanitize_key( wp_unslash( $_GET['ucd_contact'] ) ) );
		$state = is_array( $state ) ? $state : array();
	}
	$values = wp_parse_args(
		isset( $state['values'] ) ? $state['values'] : array(),
		array(
			'first_name' => '',
			'last_name'  => '',
			'email'      => '',
			'phone'      => '',
			'location'   => '',
			'topic'      => '',
			'message'    => '',
			'reach'      => 'call',
		)
	);
	$errors   = isset( $state['errors'] ) ? $state['errors'] : array();
	$messages = ucd_contact_error_messages();
	$invalid  = function ( $field ) use ( $errors ) {
		return in_array( $field, $errors, true ) ? ' aria-invalid="true"' : '';
	};
	$hidden   = function ( $field ) use ( $errors ) {
		return in_array( $field, $errors, true ) ? '' : ' hidden';
	};

	ob_start();
	?>
<form id="contact-form" class="ucd-contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate>
	<input type="hidden" name="action" value="ucd_contact">
	<input type="hidden" name="ucd_started" value="<?php echo esc_attr( time() ); ?>">
	<div class="ucd-contact-form__trap" aria-hidden="true"><label for="c-website">Website</label><input id="c-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>

	<h2>Send us a message</h2>
	<p class="ucd-contact-form__note">Fields marked <span class="req">*</span> are required.</p>

	<div class="form-alert" id="form-alert" role="alert"<?php echo $hidden( 'send' ); ?>>Your message didn't go through. Try again, or call us at <a href="tel:[PHONE]">[PHONE]</a>.</div>

	<div class="field">
		<label for="c-first">First name <span class="req" aria-hidden="true">*</span></label>
		<input id="c-first" name="first_name" type="text" autocomplete="given-name" required aria-describedby="c-first-err" value="<?php echo esc_attr( $values['first_name'] ); ?>"<?php echo $invalid( 'first_name' ); ?>>
		<span class="err" id="c-first-err"<?php echo $hidden( 'first_name' ); ?>><?php echo esc_html( $messages['first_name'] ); ?></span>
	</div>
	<div class="field">
		<label for="c-last">Last name <span class="req" aria-hidden="true">*</span></label>
		<input id="c-last" name="last_name" type="text" autocomplete="family-name" required aria-describedby="c-last-err" value="<?php echo esc_attr( $values['last_name'] ); ?>"<?php echo $invalid( 'last_name' ); ?>>
		<span class="err" id="c-last-err"<?php echo $hidden( 'last_name' ); ?>><?php echo esc_html( $messages['last_name'] ); ?></span>
	</div>
	<div class="field">
		<label for="c-email">Email <span class="req" aria-hidden="true">*</span></label>
		<input id="c-email" name="email" type="email" autocomplete="email" required aria-describedby="c-email-err" value="<?php echo esc_attr( $values['email'] ); ?>"<?php echo $invalid( 'email' ); ?>>
		<span class="err" id="c-email-err"<?php echo $hidden( 'email' ); ?>><?php echo esc_html( $messages['email'] ); ?></span>
	</div>
	<div class="field">
		<label for="c-phone">Phone</label>
		<input id="c-phone" name="phone" type="tel" autocomplete="tel" value="<?php echo esc_attr( $values['phone'] ); ?>">
	</div>
	<div class="field ucd-contact-form__full">
		<label for="c-where">Where will the home go?</label>
		<input id="c-where" name="location" type="text" placeholder="City, state" autocomplete="address-level2" aria-describedby="c-where-help" value="<?php echo esc_attr( $values['location'] ); ?>">
		<span class="help" id="c-where-help">Helps us check delivery and zoning before we call.</span>
	</div>
	<div class="field ucd-contact-form__full">
		<label for="c-topic">What can we help with? <span class="req" aria-hidden="true">*</span></label>
		<select id="c-topic" name="topic" required aria-describedby="c-topic-err"<?php echo $invalid( 'topic' ); ?>>
			<option value="">Choose one</option>
			<?php foreach ( ucd_contact_topics() as $key => $label ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>"<?php selected( $values['topic'], $key ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
		<span class="err" id="c-topic-err"<?php echo $hidden( 'topic' ); ?>><?php echo esc_html( $messages['topic'] ); ?></span>
	</div>
	<div class="field ucd-contact-form__full">
		<label for="c-message">Message</label>
		<textarea id="c-message" name="message" placeholder="Tell us about your project, your timeline, or any questions."><?php echo esc_textarea( $values['message'] ); ?></textarea>
	</div>
	<fieldset class="field ucd-contact-form__full">
		<legend>Best way to reach you</legend>
		<div class="choice-row">
			<?php foreach ( ucd_contact_reach_options() as $key => $label ) : ?>
			<label class="choice"><input type="radio" name="reach" value="<?php echo esc_attr( $key ); ?>"<?php checked( $values['reach'], $key ); ?>><span><?php echo esc_html( $label ); ?></span></label>
			<?php endforeach; ?>
		</div>
	</fieldset>
	<div class="ucd-contact-form__submit">
		<button type="submit" class="btn primary">Send message</button>
		<p>We only ask for contact info. Never your SSN or bank details.</p>
	</div>
</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_contact_form', 'ucd_contact_form_shortcode' );

/*
 * Submission
 *
 * There's no nonce: this is a public form with nothing to forge, and a cached
 * page would serve an expired nonce and block real messages. A hidden field
 * and a minimum fill time catch most bots instead.
 */

function ucd_contact_submit() {
	// Bots fill the hidden website field or post within seconds of loading the page. Let them think it worked.
	$started = isset( $_POST['ucd_started'] ) ? (int) $_POST['ucd_started'] : 0;
	if ( ! empty( $_POST['website'] ) || time() - $started < 3 ) {
		wp_safe_redirect( ucd_contact_thank_you_url() );
		exit;
	}

	$field  = function ( $name ) {
		return isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : '';
	};
	$reach  = sanitize_key( $field( 'reach' ) );
	$values = array(
		'first_name' => sanitize_text_field( $field( 'first_name' ) ),
		'last_name'  => sanitize_text_field( $field( 'last_name' ) ),
		'email'      => sanitize_email( $field( 'email' ) ),
		'phone'      => sanitize_text_field( $field( 'phone' ) ),
		'location'   => sanitize_text_field( $field( 'location' ) ),
		'topic'      => sanitize_key( $field( 'topic' ) ),
		'message'    => sanitize_textarea_field( $field( 'message' ) ),
		'reach'      => isset( ucd_contact_reach_options()[ $reach ] ) ? $reach : 'call',
	);

	$errors = ucd_contact_validate( $values );
	if ( ! $errors && ! ucd_contact_deliver( $values ) ) {
		$errors[] = 'send';
	}

	if ( $errors ) {
		$token = strtolower( wp_generate_password( 16, false ) );
		set_transient( 'ucd_contact_' . $token, compact( 'values', 'errors' ), 10 * MINUTE_IN_SECONDS );
		$back = remove_query_arg( 'ucd_contact', wp_get_referer() ? wp_get_referer() : home_url( '/contact/' ) );
		wp_safe_redirect( add_query_arg( 'ucd_contact', $token, $back ) . '#contact-form' );
		exit;
	}

	wp_safe_redirect( ucd_contact_thank_you_url( $values['topic'], $values['reach'] ) );
	exit;
}
add_action( 'admin_post_nopriv_ucd_contact', 'ucd_contact_submit' );
add_action( 'admin_post_ucd_contact', 'ucd_contact_submit' );

// Saves the message and emails it. Succeeds if either works, so the visitor isn't told to retry a message we already have.
function ucd_contact_deliver( $values ) {
	$topic   = ucd_contact_topics()[ $values['topic'] ];
	$name    = $values['first_name'] . ' ' . $values['last_name'];
	$details = array(
		'Email'             => $values['email'],
		'Phone'             => $values['phone'],
		'Home goes to'      => $values['location'],
		'Topic'             => $topic,
		'Best way to reach' => ucd_contact_reach_options()[ $values['reach'] ],
	);

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'ucd_message',
			'post_status'  => 'private',
			'post_title'   => $name . ' · ' . $topic,
			'post_content' => $values['message'],
			'meta_input'   => array( '_ucd_details' => $details ),
		)
	);

	$body = '';
	foreach ( $details as $label => $value ) {
		$body .= $label . ': ' . ( '' === $value ? '-' : $value ) . "\n";
	}
	$body .= "\nMessage:\n" . ( '' === $values['message'] ? '-' : $values['message'] ) . "\n";
	if ( $post_id ) {
		$body .= "\nSaved in WordPress: " . admin_url( 'post.php?post=' . $post_id . '&action=edit' ) . "\n";
	}

	// Strip characters that would break the Reply-To header.
	$reply_name = str_replace( array( '"', '<', '>', ',', ';' ), '', $name );
	$sent       = wp_mail(
		ucd_contact_recipient(),
		'New message from ' . $name . ' (' . $topic . ')',
		$body,
		array( 'Reply-To: ' . $reply_name . ' <' . $values['email'] . '>' )
	);

	return (bool) $post_id || $sent;
}

/*
 * Messages screen in wp-admin
 */

function ucd_register_messages() {
	register_post_type(
		'ucd_message',
		array(
			'labels'          => array(
				'name'               => 'Messages',
				'singular_name'      => 'Message',
				'all_items'          => 'All messages',
				'edit_item'          => 'Message',
				'search_items'       => 'Search messages',
				'not_found'          => 'No messages yet.',
				'not_found_in_trash' => 'No messages in the trash.',
			),
			'public'          => false,
			'show_ui'         => true,
			'menu_position'   => 26,
			'menu_icon'       => 'dashicons-email-alt',
			'supports'        => array( 'title' ),
			'capability_type' => 'post',
			// Editors and admins only; messages only come in through the form.
			'capabilities'    => array(
				'create_posts' => 'do_not_allow',
				'edit_posts'   => 'edit_others_posts',
			),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'ucd_register_messages' );

function ucd_message_meta_box() {
	add_meta_box( 'ucd-message', 'Message', 'ucd_render_message_box', 'ucd_message', 'normal', 'high' );
}
add_action( 'add_meta_boxes_ucd_message', 'ucd_message_meta_box' );

function ucd_render_message_box( $post ) {
	$details = get_post_meta( $post->ID, '_ucd_details', true );
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( is_array( $details ) ? $details : array() as $label => $value ) {
		printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $label ), ucd_message_value( $label, $value ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in ucd_message_value().
	}
	printf( '<tr><th scope="row">Message</th><td>%s</td></tr>', $post->post_content ? wpautop( esc_html( $post->post_content ) ) : '-' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped before wpautop.
	echo '</tbody></table>';
}

// Email and phone become links, so a reply is one click away.
function ucd_message_value( $label, $value ) {
	if ( '' === $value ) {
		return '-';
	}
	if ( 'Email' === $label ) {
		return sprintf( '<a href="mailto:%1$s">%2$s</a>', esc_attr( $value ), esc_html( $value ) );
	}
	if ( 'Phone' === $label ) {
		return sprintf( '<a href="tel:%1$s">%2$s</a>', esc_attr( preg_replace( '/[^0-9+]/', '', $value ) ), esc_html( $value ) );
	}
	return esc_html( $value );
}

function ucd_message_columns( $columns ) {
	return array(
		'cb'        => $columns['cb'],
		'title'     => 'From',
		'ucd_email' => 'Email',
		'ucd_phone' => 'Phone',
		'ucd_reach' => 'Reach by',
		'date'      => 'Received',
	);
}
add_filter( 'manage_ucd_message_posts_columns', 'ucd_message_columns' );

function ucd_message_column( $column, $post_id ) {
	$labels  = array(
		'ucd_email' => 'Email',
		'ucd_phone' => 'Phone',
		'ucd_reach' => 'Best way to reach',
	);
	$details = get_post_meta( $post_id, '_ucd_details', true );
	if ( isset( $labels[ $column ] ) ) {
		$label = $labels[ $column ];
		echo ucd_message_value( $label, isset( $details[ $label ] ) ? $details[ $label ] : '' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in ucd_message_value().
	}
}
add_action( 'manage_ucd_message_posts_custom_column', 'ucd_message_column', 10, 2 );

function ucd_contact_settings() {
	register_setting(
		'general',
		'ucd_contact_email',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_email',
			'default'           => '',
		)
	);
	add_settings_field( 'ucd_contact_email', '<label for="ucd_contact_email">Contact form email</label>', 'ucd_contact_email_field', 'general' );
}
add_action( 'admin_init', 'ucd_contact_settings' );

function ucd_contact_email_field() {
	printf(
		'<input name="ucd_contact_email" type="email" id="ucd_contact_email" value="%1$s" class="regular-text" placeholder="%2$s"><p class="description">Where contact form messages are sent. Leave empty to use the administration email address. Every message is also saved under Messages.</p>',
		esc_attr( get_option( 'ucd_contact_email' ) ),
		esc_attr( get_option( 'admin_email' ) )
	);
}

/*
 * Thank-you page (/contact/thank-you/)
 */

// [ucd_reply_method]: "a call", "a text" or "an email", whichever the visitor picked on the form.
function ucd_reply_method_shortcode() {
	$labels = array(
		'call'  => 'a call',
		'text'  => 'a text',
		'email' => 'an email',
	);
	$reach  = isset( $_GET['reach'] ) ? sanitize_key( wp_unslash( $_GET['reach'] ) ) : '';
	return esc_html( isset( $labels[ $reach ] ) ? $labels[ $reach ] : 'a reply' );
}
add_shortcode( 'ucd_reply_method', 'ucd_reply_method_shortcode' );

function ucd_is_contact_thank_you() {
	return is_page() && 'contact/thank-you' === get_page_uri( get_queried_object() );
}

// Thank-you pages are for people who just sent something, not for search results.
function ucd_noindex_thank_you( $robots ) {
	if ( is_page( 'thank-you' ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'ucd_noindex_thank_you' );

// Conversion event, tagged with the topic. Swap for the site's analytics call once it's chosen.
function ucd_contact_conversion_event() {
	if ( ! ucd_is_contact_thank_you() ) {
		return;
	}
	$topic = isset( $_GET['topic'] ) ? sanitize_key( wp_unslash( $_GET['topic'] ) ) : '';
	printf(
		"<script>window.dataLayer = window.dataLayer || []; window.dataLayer.push(%s);</script>\n",
		wp_json_encode(
			array(
				'event' => 'contact_form_submitted',
				'topic' => $topic ? $topic : 'unknown',
			)
		)
	);
}
add_action( 'wp_footer', 'ucd_contact_conversion_event' );
