<?php
/**
 * UnContained Dreams theme setup.
 *
 * The theme lives at the repo root so it shares assets/ with the static pages
 * that haven't been converted yet.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'UCD_VERSION', '1.0.0' );

function ucd_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'ucd_setup' );

function ucd_enqueue_assets() {
	// Version must stay null: WordPress would otherwise rebuild the query string and drop the second family= parameter.
	wp_enqueue_style( 'ucd-fonts', 'https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=Public+Sans:wght@400;500;600;700&display=swap', array(), null );
	wp_enqueue_style( 'ucd-style', get_stylesheet_uri(), array( 'ucd-fonts' ), UCD_VERSION );
	wp_enqueue_style( 'ucd-mega-menu', ucd_get_asset( 'css/mega-menu.css' ), array( 'ucd-style' ), UCD_VERSION );
	wp_enqueue_script( 'ucd-mega-menu', ucd_get_asset( 'js/mega-menu.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'ucd_enqueue_assets' );

function ucd_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'ucd_resource_hints', 10, 2 );

// Use the brand icon until a Site Icon is set under Appearance > Customize.
function ucd_fallback_icon() {
	if ( ! has_site_icon() ) {
		printf( "<link rel=\"icon\" href=\"%s\" sizes=\"any\">\n", esc_url( ucd_get_asset( 'brand/icon.png' ) ) );
	}
}
add_action( 'wp_head', 'ucd_fallback_icon' );

/**
 * URL of a file in assets/. Pass the path URL-encoded (spaces as %20), as it
 * appears in the static HTML.
 */
function ucd_get_asset( $path ) {
	return get_theme_file_uri( 'assets/' . ltrim( $path, '/' ) );
}

function ucd_asset( $path ) {
	echo esc_url( ucd_get_asset( $path ) );
}

// Echoes a site-root URL, e.g. ucd_url( '/plans/' ) or ucd_url( '/#faq' ).
function ucd_url( $path = '/' ) {
	echo esc_url( home_url( $path ) );
}
