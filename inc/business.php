<?php
/**
 * Business details: phone, email, address, license, financing partner and the
 * rest, set once under Appearance > Customize > Business details.
 *
 * The header, footer, forms and search-engine data read them with
 * ucd_detail(). Page content can use the same placeholders the patterns ship
 * with: type [PHONE], [EMAIL], [STREET ADDRESS] and so on in any block, and
 * the saved value shows on the site (tel:[PHONE] and mailto:[EMAIL] links
 * too). A placeholder whose detail is still empty stays as typed, so it's
 * easy to spot what's missing.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Key => label, default, help text, and the placeholders it fills in page content.
function ucd_business_fields() {
	return array(
		'name'            => array( 'Business name', 'UnContained Dreams', '', array() ),
		'legal_name'      => array( 'Legal name', '', 'As registered, e.g. UnContained Dreams LLC. Used in the Privacy Policy and Terms.', array( '[LEGAL ENTITY NAME, e.g. UnContained Dreams LLC]', '[LEGAL ENTITY NAME]' ) ),
		'phone'           => array( 'Phone', '', 'As people should read it, e.g. (281) 555-0123.', array( '[PHONE]' ) ),
		'email'           => array( 'Email', '', 'The public email address shown on the site.', array( '[EMAIL]' ) ),
		'street'          => array( 'Street address', '', '', array( '[STREET ADDRESS]' ) ),
		'city'            => array( 'City', 'Sugar Land', '', array() ),
		'region'          => array( 'State', 'TX', 'Two-letter abbreviation.', array() ),
		'zip'             => array( 'ZIP code', '', '', array( '[ZIP]' ) ),
		'hours'           => array( 'Hours', '', 'As people should read them, e.g. Mon–Fri, 8 am to 5 pm.', array() ),
		'opening_hours'   => array( 'Hours for search engines', '', 'The same hours in schema.org format, e.g. Mo-Fr 08:00-17:00.', array() ),
		'license'         => array( 'License or registration', '', 'Shown in the footer, e.g. Texas registration #12345.', array( '[LICENSE / REGISTRATION #]' ) ),
		'insurance'       => array( 'Insurance', '', 'Shown in the footer, e.g. Fully insured, general liability.', array( '[INSURANCE: general liability]' ) ),
		'financing_agent' => array( 'Financing partner', '', 'The agent or lender who handles financing requests.', array( '[FINANCING AGENT / LENDER]' ) ),
		'founder'         => array( 'Founder\'s full name', '', 'Used on the About page and in its search-engine data.', array( '[FOUNDER FULL NAME]' ) ),
		'founded'         => array( 'Year founded', '', '', array() ),
		'tour_url'        => array( '3D tour link', '', 'The Studio VOS tour of The Rising Sun.', array( '[STUDIO VOS TOUR URL]', '[3D TOUR URL]' ) ),
	);
}

function ucd_detail( $key, $fallback = '' ) {
	$fields = ucd_business_fields();
	$value  = trim( (string) get_theme_mod( 'ucd_' . $key, isset( $fields[ $key ] ) ? $fields[ $key ][1] : '' ) );
	return '' === $value ? $fallback : $value;
}

// A tel: link for the phone number, or '' until one is set.
function ucd_phone_href() {
	$digits = preg_replace( '/[^\d+]/', '', ucd_detail( 'phone' ) );
	return $digits ? 'tel:' . $digits : '';
}

// "Street, City, ST ZIP", with whatever parts are filled in.
function ucd_address() {
	$line = trim( ucd_detail( 'region' ) . ' ' . ucd_detail( 'zip' ) );
	return implode( ', ', array_filter( array( ucd_detail( 'street' ), ucd_detail( 'city' ), $line ) ) );
}

function ucd_business_customizer( $wp_customize ) {
	$wp_customize->add_section(
		'ucd_business',
		array(
			'title'       => 'Business details',
			'priority'    => 30,
			'description' => 'Shown in the header, footer, forms and search results. Placeholders like [PHONE] in page content show these values too.',
		)
	);
	foreach ( ucd_business_fields() as $key => $field ) {
		$wp_customize->add_setting(
			'ucd_' . $key,
			array(
				'default'           => $field[1],
				'sanitize_callback' => 'email' === $key ? 'sanitize_email' : ( 'tour_url' === $key ? 'esc_url_raw' : 'sanitize_text_field' ),
			)
		);
		$wp_customize->add_control(
			'ucd_' . $key,
			array(
				'label'       => $field[0],
				'description' => $field[2],
				'section'     => 'ucd_business',
				'type'        => 'email' === $key ? 'email' : ( 'tour_url' === $key ? 'url' : 'text' ),
			)
		);
	}

	// The light logo for the dark footer. The header logo is Site Identity > Logo.
	$wp_customize->add_setting( 'ucd_footer_logo', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'ucd_footer_logo',
			array(
				'label'       => 'Footer logo',
				'description' => 'A light version of the logo for the dark footer. Without one, the footer shows the business name.',
				'section'     => 'title_tagline',
				'mime_type'   => 'image',
			)
		)
	);
}
add_action( 'customize_register', 'ucd_business_customizer' );

/*
 * Placeholders in page content
 */

// Placeholder => value, for every detail that's filled in.
function ucd_business_replacements() {
	$map = array();
	if ( ucd_phone_href() ) {
		$map['tel:[PHONE]'] = ucd_phone_href();
	}
	if ( ucd_detail( 'email' ) ) {
		$map['mailto:[EMAIL]'] = 'mailto:' . ucd_detail( 'email' );
	}
	foreach ( ucd_business_fields() as $key => $field ) {
		$value = ucd_detail( $key );
		if ( '' === $value ) {
			continue;
		}
		foreach ( $field[3] as $placeholder ) {
			$map[ $placeholder ] = 'tour_url' === $key ? esc_url( $value ) : esc_html( $value );
		}
	}
	return $map;
}

function ucd_fill_details( $html ) {
	$map = ucd_business_replacements();
	return $map ? strtr( $html, $map ) : $html;
}
// After blocks and shortcodes have run, so form output is filled in too.
add_filter( 'the_content', 'ucd_fill_details', 20 );
