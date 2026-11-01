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
define( 'UCD_FONTS_URL', 'https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800&family=Public+Sans:wght@400;500;600;700&display=swap' );

require_once get_template_directory() . '/inc/contact-form.php';
require_once get_template_directory() . '/inc/build-options.php';
require_once get_template_directory() . '/inc/build.php';
require_once get_template_directory() . '/inc/financing.php';

function ucd_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	// Show the block editor in the site's fonts and section styles.
	add_theme_support( 'editor-styles' );
	add_editor_style( array( UCD_FONTS_URL, 'style.css' ) );
}
add_action( 'after_setup_theme', 'ucd_setup' );

function ucd_enqueue_assets() {
	// Version must stay null: WordPress would otherwise rebuild the query string and drop the second family= parameter.
	wp_enqueue_style( 'ucd-fonts', UCD_FONTS_URL, array(), null );
	wp_enqueue_style( 'ucd-style', get_stylesheet_uri(), array( 'ucd-fonts' ), UCD_VERSION );
	wp_enqueue_style( 'ucd-mega-menu', ucd_get_asset( 'css/mega-menu.css' ), array( 'ucd-style' ), UCD_VERSION );
	wp_enqueue_script( 'ucd-mega-menu', ucd_get_asset( 'js/mega-menu.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

	// Filters and lightbox, only on pages built with the gallery section patterns.
	if ( is_singular() && false !== strpos( get_post_field( 'post_content', get_queried_object_id() ), 'ucd-gallery-section' ) ) {
		wp_enqueue_script( 'ucd-gallery', ucd_get_asset( 'js/gallery.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_localize_script( 'ucd-gallery', 'ucdGallery', array( 'photoRequestUrl' => 'mailto:[EMAIL]?subject=Photo%20request' ) );
	}

	// Question search, only on pages built with the FAQ patterns.
	if ( is_singular() && false !== strpos( get_post_field( 'post_content', get_queried_object_id() ), 'ucd-faq-layout' ) ) {
		wp_enqueue_script( 'ucd-faq', ucd_get_asset( 'js/faq.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
		wp_localize_script(
			'ucd-faq',
			'ucdFaq',
			array(
				'phone' => '[PHONE]',
				'email' => '[EMAIL]',
			)
		);
	}
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

// Pages for people who just sent something, or are mid-build, stay out of search results.
function ucd_noindex_pages( $robots ) {
	$private = is_page( array( 'thank-you', 'confirmation' ) )
		|| ( is_singular() && has_shortcode( (string) get_post_field( 'post_content', get_queried_object_id() ), 'ucd_build_review' ) );
	if ( $private ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'ucd_noindex_pages' );

// "Chip" style for Button blocks: small rounded links, like the delivery regions on the homepage.
function ucd_register_block_styles() {
	register_block_style(
		'core/button',
		array(
			'name'  => 'chip',
			'label' => 'Chip',
		)
	);
}
add_action( 'init', 'ucd_register_block_styles' );

// Groups the section patterns in patterns/ under one heading in the block inserter.
function ucd_register_pattern_category() {
	register_block_pattern_category( 'uncontained-dreams', array( 'label' => 'UnContained Dreams' ) );
}
add_action( 'init', 'ucd_register_pattern_category' );

/**
 * Whether a page is built from full-width sections. If its first block is
 * aligned full, the page supplies its own hero and heading, so page.php
 * renders it edge to edge without the title.
 */
function ucd_page_has_sections( $post = null ) {
	foreach ( parse_blocks( get_post_field( 'post_content', $post ) ) as $block ) {
		if ( null === $block['blockName'] && '' === trim( $block['innerHTML'] ) ) {
			continue;
		}
		return isset( $block['attrs']['align'] ) && 'full' === $block['attrs']['align'];
	}
	return false;
}

/**
 * Business details for search engines, carried over from the static About and
 * Contact pages. Keep the name, phone and address matching the Google
 * Business Profile exactly.
 */
function ucd_business_schema() {
	if ( ! is_page( array( 'about', 'contact' ) ) ) {
		return;
	}
	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'HomeAndConstructionBusiness',
		'name'       => 'UnContained Dreams',
		'url'        => home_url( '/' ),
		'telephone'  => '[PHONE]',
		'address'    => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => '[STREET ADDRESS]',
			'addressLocality' => 'Sugar Land',
			'addressRegion'   => 'TX',
			'postalCode'      => '[ZIP]',
			'addressCountry'  => 'US',
		),
		'areaServed' => array( array( '@type' => 'Country', 'name' => 'United States' ) ),
	);

	if ( is_page( 'about' ) ) {
		$schema['url']          = get_permalink();
		$schema['foundingDate'] = '[YEAR]';
		$schema['founder']      = array( '@type' => 'Person', 'name' => '[FOUNDER FULL NAME]' );
		foreach ( array( 'California', 'Colorado', 'Utah', 'Wyoming', 'New York' ) as $state ) {
			$schema['areaServed'][] = array( '@type' => 'State', 'name' => $state );
		}
	} else {
		$schema['email']        = '[EMAIL]';
		$schema['openingHours'] = '[Mo-Fr 08:00-17:00]';
	}

	printf( "<script type=\"application/ld+json\">%s</script>\n", wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) );
}
add_action( 'wp_head', 'ucd_business_schema' );

/**
 * Structured data read from the page's own blocks, so it stays in step with
 * what's written in the editor:
 * - step cards (Groups with the ucd-step class) become a HowTo
 * - Details blocks become an FAQPage
 * - gallery sections (ucd-gallery-section) mark the page as an ImageGallery
 * - video items (ucd-video-item) become VideoObjects once their video is in
 *   the Media Library, which supplies the upload date and length
 */
function ucd_block_schema() {
	if ( ! is_singular() ) {
		return;
	}
	$items = array(
		'steps'   => array(),
		'faqs'    => array(),
		'videos'  => array(),
		'gallery' => false,
	);
	ucd_collect_schema_items( parse_blocks( get_post_field( 'post_content' ) ), $items );

	$schemas = array();
	if ( $items['steps'] ) {
		$schemas[] = array(
			'@context' => 'https://schema.org',
			'@type'    => 'HowTo',
			'name'     => 'How to get a finished container home from UnContained Dreams',
			'step'     => $items['steps'],
		);
	}
	if ( $items['faqs'] ) {
		$schemas[] = array(
			'@context'   => 'https://schema.org',
			'@type'      => 'FAQPage',
			'mainEntity' => $items['faqs'],
		);
	}
	if ( $items['gallery'] ) {
		$schemas[] = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'ImageGallery',
			'name'        => 'UnContained Dreams container home gallery',
			'description' => 'Photos, videos, and a 3D tour of real UnContained Dreams container homes: exteriors, interiors, custom furniture, shop builds, and delivery days.',
			'url'         => get_permalink(),
		);
	}
	if ( $items['videos'] ) {
		$schemas[] = $items['videos'];
	}
	foreach ( $schemas as $schema ) {
		printf( "<script type=\"application/ld+json\">%s</script>\n", wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
	}
}
add_action( 'wp_head', 'ucd_block_schema' );

function ucd_collect_schema_items( $blocks, &$items ) {
	foreach ( $blocks as $block ) {
		$classes = explode( ' ', isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '' );
		$group   = 'core/group' === $block['blockName'];

		if ( $group && in_array( 'ucd-step', $classes, true ) ) {
			list( $name, $text ) = ucd_heading_and_text( $block );
			$items['steps'][]    = array(
				'@type'    => 'HowToStep',
				'position' => count( $items['steps'] ) + 1,
				'name'     => $name,
				'text'     => $text,
			);
			continue;
		}

		if ( $group && in_array( 'ucd-video-item', $classes, true ) ) {
			$video = ucd_video_schema( $block );
			if ( $video ) {
				$items['videos'][] = $video;
			}
			continue;
		}

		if ( 'core/details' === $block['blockName'] ) {
			preg_match( '#<summary>(.*?)</summary>#s', $block['innerHTML'], $summary );
			$answer = trim( implode( ' ', array_map( 'ucd_block_text', $block['innerBlocks'] ) ) );
			// Leave out answers that still have [PLACEHOLDER] text so unfinished ones don't reach search results.
			if ( $answer && false === strpos( $answer, '[' ) ) {
				$items['faqs'][] = array(
					'@type'          => 'Question',
					'name'           => $summary ? ucd_plain_text( $summary[1] ) : '',
					'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $answer ),
				);
			}
			continue;
		}

		if ( $group && in_array( 'ucd-gallery-section', $classes, true ) ) {
			$items['gallery'] = true;
		}

		ucd_collect_schema_items( $block['innerBlocks'], $items );
	}
}

// A group's heading text, and the text of everything else in it except media.
function ucd_heading_and_text( $group ) {
	$name = '';
	$text = array();
	foreach ( $group['innerBlocks'] as $inner ) {
		if ( 'core/heading' === $inner['blockName'] ) {
			$name = ucd_block_text( $inner );
		} elseif ( ! in_array( $inner['blockName'], array( 'core/video', 'core/image' ), true ) ) {
			$text[] = ucd_block_text( $inner );
		}
	}
	return array( $name, trim( implode( ' ', $text ) ) );
}

function ucd_video_schema( $group ) {
	$video = null;
	foreach ( $group['innerBlocks'] as $inner ) {
		if ( 'core/video' === $inner['blockName'] ) {
			$video = $inner;
		}
	}
	// Search engines require an upload date and thumbnail, so skip videos not yet in the Media Library or without a poster.
	if ( ! $video || empty( $video['attrs']['id'] ) || ! preg_match( '/\bposter="([^"]+)"/', $video['innerHTML'], $poster ) ) {
		return null;
	}
	list( $name, $text ) = ucd_heading_and_text( $group );
	$id                  = $video['attrs']['id'];
	$schema              = array(
		'@context'     => 'https://schema.org',
		'@type'        => 'VideoObject',
		'name'         => $name,
		// Drop the "1:19 · " running time that starts the description line.
		'description'  => preg_replace( '/^\d+:\d{2}\s*·\s*/u', '', $text ),
		'thumbnailUrl' => html_entity_decode( $poster[1] ),
		'contentUrl'   => wp_get_attachment_url( $id ),
		'uploadDate'   => get_the_date( 'c', $id ),
	);
	$meta = wp_get_attachment_metadata( $id );
	if ( ! empty( $meta['length'] ) ) {
		$schema['duration'] = sprintf( 'PT%dM%dS', floor( $meta['length'] / 60 ), $meta['length'] % 60 );
	}
	return $schema;
}

/**
 * FAQ "Jump to" links. The FAQ layout group (ucd-faq-layout) gets a nav built
 * from its topic groups (ucd-faq-group), so a topic added in the editor shows
 * up there. Topics without an HTML anchor get one from their heading.
 */
function ucd_faq_render( $content, $block ) {
	if ( 'core/group' !== $block['blockName'] ) {
		return $content;
	}
	$classes = explode( ' ', isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '' );

	if ( in_array( 'ucd-faq-group', $classes, true ) && empty( $block['attrs']['anchor'] ) ) {
		$tags = new WP_HTML_Tag_Processor( $content );
		if ( $tags->next_tag() ) {
			$tags->set_attribute( 'id', ucd_faq_topic( $block )['id'] );
			$content = $tags->get_updated_html();
		}
		return $content;
	}

	if ( in_array( 'ucd-faq-layout', $classes, true ) ) {
		$links = '';
		foreach ( ucd_faq_topics( $block['innerBlocks'] ) as $topic ) {
			$links .= sprintf( '<li><a href="#%s">%s</a></li>', esc_attr( $topic['id'] ), esc_html( $topic['label'] ) );
		}
		$nav = '<nav class="ucd-faq-jump" aria-label="FAQ topics"><span class="eyebrow">Jump to</span><ul>' . $links . '</ul></nav>';
		// Put the nav first inside the layout group, so it takes the narrow left column.
		return preg_replace_callback(
			'/^\s*<[^>]+>/',
			function ( $open ) use ( $nav ) {
				return $open[0] . $nav;
			},
			$content,
			1
		);
	}

	return $content;
}
add_filter( 'render_block', 'ucd_faq_render', 10, 2 );

function ucd_faq_topics( $blocks ) {
	$topics = array();
	foreach ( $blocks as $block ) {
		$classes = explode( ' ', isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '' );
		if ( 'core/group' === $block['blockName'] && in_array( 'ucd-faq-group', $classes, true ) ) {
			$topics[] = ucd_faq_topic( $block );
		} else {
			$topics = array_merge( $topics, ucd_faq_topics( $block['innerBlocks'] ) );
		}
	}
	return $topics;
}

function ucd_faq_topic( $group ) {
	$label = '';
	foreach ( $group['innerBlocks'] as $inner ) {
		if ( 'core/heading' === $inner['blockName'] ) {
			$label = ucd_block_text( $inner );
			break;
		}
	}
	$id = empty( $group['attrs']['anchor'] ) ? sanitize_title( $label ) : $group['attrs']['anchor'];
	return array(
		'id'    => $id,
		'label' => $label,
	);
}

function ucd_block_text( $block ) {
	return ucd_plain_text( render_block( $block ) );
}

function ucd_plain_text( $html ) {
	return trim( preg_replace( '/\s+/', ' ', html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES, 'UTF-8' ) ) );
}

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
