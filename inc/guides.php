<?php
/**
 * Guides (the blog): every guide, build update and news post is a WordPress
 * post at /guides/{slug}/, set under Settings > Permalinks > Custom structure
 * as /guides/%postname%/.
 *
 * - /guides/ is a page built from the "Guides page" pattern. Its list comes
 *   from [ucd_guides] and its search box from [ucd_guides_search].
 * - Topics are categories. A topic's address opens the hub filtered to it
 *   (/guides/?topic=permits-zoning), the same as the static site.
 * - "Start here" shows the sticky posts (Post settings > Stick to the top of
 *   the blog), up to three.
 * - The excerpt is the card summary and the article's "short answer" box.
 * - The old /blog/ addresses redirect to /guides/.
 * - [ucd_newsletter_form] adds people to the same mailing list as the plans
 *   form, tagged guides.
 *
 * The topics and the planned articles in assets/data/posts.json are added
 * once, as categories and draft posts, the first time an admin opens
 * wp-admin with this theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ucd_guides_data() {
	static $data = null;
	if ( null === $data ) {
		$data = json_decode( (string) file_get_contents( get_theme_file_path( 'assets/data/posts.json' ) ), true );
		$data = is_array( $data ) ? $data : array();
	}
	return $data;
}

/*
 * Topics, read time and dates
 */

// A post's topic: its first category other than the default one. Null if it has none.
function ucd_guide_topic( $post = null ) {
	foreach ( get_the_category( $post ) as $term ) {
		if ( (int) get_option( 'default_category' ) !== $term->term_id ) {
			return $term;
		}
	}
	return null;
}

// Topics with published guides, in the order posts.json lists them, then any added later.
function ucd_guide_topics() {
	$order = wp_list_pluck( isset( ucd_guides_data()['topics'] ) ? ucd_guides_data()['topics'] : array(), 'id' );
	$terms = get_categories(
		array(
			'hide_empty' => true,
			'exclude'    => array( (int) get_option( 'default_category' ) ),
		)
	);
	usort(
		$terms,
		function ( $a, $b ) use ( $order ) {
			$ia = array_search( $a->slug, $order, true );
			$ib = array_search( $b->slug, $order, true );
			$ia = false === $ia ? PHP_INT_MAX : $ia;
			$ib = false === $ib ? PHP_INT_MAX : $ib;
			return $ia === $ib ? strcmp( $a->name, $b->name ) : $ia - $ib;
		}
	);
	return $terms;
}

function ucd_guide_topic_url( $slug ) {
	return add_query_arg( 'topic', $slug, home_url( '/guides/' ) );
}

function ucd_guide_read_minutes( $post = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post ) ) );
	return max( 1, (int) round( $words / 200 ) );
}

// "Updated May 2026": the last edit. Recheck anything about laws or prices every 6 months and save it again.
function ucd_guide_updated( $post = null ) {
	return 'Updated ' . get_the_modified_date( 'F Y', $post );
}

// Topic links go to the hub, filtered.
function ucd_guide_topic_link( $url, $term, $taxonomy ) {
	return 'category' === $taxonomy ? ucd_guide_topic_url( $term->slug ) : $url;
}
add_filter( 'term_link', 'ucd_guide_topic_link', 10, 3 );

// Old blog addresses and category archives go to the hub.
function ucd_guides_redirects() {
	if ( is_category() ) {
		wp_safe_redirect( ucd_guide_topic_url( get_queried_object()->slug ), 301 );
		exit;
	}
	if ( is_404() ) {
		$path = trim( (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ), '/' );
		$base = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );
		$path = $base && 0 === strpos( $path, $base . '/' ) ? substr( $path, strlen( $base ) + 1 ) : $path;
		if ( 'blog' === $path || 0 === strpos( $path, 'blog/' ) ) {
			wp_safe_redirect( home_url( '/guides/' . ltrim( substr( $path, 4 ), '/' ) ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'ucd_guides_redirects' );

/*
 * Cards and the hub
 */

// What the hub search matches against: title, summary and topic.
function ucd_guide_card_text( $post ) {
	$topic = ucd_guide_topic( $post );
	return strtolower( get_the_title( $post ) . ' ' . get_the_excerpt( $post ) . ' ' . ( $topic ? $topic->name : '' ) );
}

function ucd_guide_card( $post ) {
	$topic = ucd_guide_topic( $post );
	$label = $topic ? $topic->name : '';
	ob_start();
	?>
<article class="ucd-guide-card" data-topic="<?php echo esc_attr( $topic ? $topic->slug : '' ); ?>" data-text="<?php echo esc_attr( ucd_guide_card_text( $post ) ); ?>">
	<?php if ( has_post_thumbnail( $post ) ) : ?>
	<?php echo get_the_post_thumbnail( $post, 'medium_large', array( 'alt' => '', 'loading' => 'lazy' ) ); ?>
	<?php endif; ?>
	<div class="ucd-guide-card__body">
		<?php if ( $label ) : ?>
		<span class="chip"><?php echo esc_html( $label ); ?></span>
		<?php endif; ?>
		<h3><a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a></h3>
		<p><?php echo esc_html( get_the_excerpt( $post ) ); ?></p>
		<p class="ucd-guide-card__meta"><?php echo esc_html( ucd_guide_read_minutes( $post ) . ' min read · ' . ucd_guide_updated( $post ) ); ?></p>
	</div>
</article>
	<?php
	return ob_get_clean();
}

// The search typed on the hub (?q=). guides.js filters as you type; this covers a submitted search without JavaScript.
function ucd_guides_query() {
	return isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
}

function ucd_guides_search_shortcode() {
	ob_start();
	?>
<form class="ucd-guides-search" role="search" id="guides-search" action="<?php echo esc_url( home_url( '/guides/' ) ); ?>#all">
	<label class="screen-reader-text" for="guides-q">Search guides</label>
	<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5A666C" stroke-width="2.2" stroke-linecap="square"><circle cx="10.5" cy="10.5" r="6.5"/><path d="M20 20l-4.8-4.8"/></svg>
	<input id="guides-q" name="q" type="search" placeholder="Search guides, like &quot;permits&quot; or &quot;insulation&quot;" autocomplete="off" value="<?php echo esc_attr( ucd_guides_query() ); ?>">
</form>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_guides_search', 'ucd_guides_search_shortcode' );

function ucd_guides_shortcode() {
	wp_enqueue_script( 'ucd-guides', ucd_get_asset( 'js/guides.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );

	$posts  = get_posts(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'numberposts'         => -1,
			'ignore_sticky_posts' => true,
		)
	);
	$sticky = array_slice( array_values( array_filter( $posts, 'is_sticky' ) ), 0, 3 );
	$topics = ucd_guide_topics();
	$active = isset( $_GET['topic'] ) ? sanitize_title( wp_unslash( $_GET['topic'] ) ) : '';
	$active = in_array( $active, wp_list_pluck( $topics, 'slug' ), true ) ? $active : '';
	$term   = strtolower( ucd_guides_query() );
	$cards  = array();
	$shown  = 0;
	foreach ( $posts as $post ) {
		$topic   = ucd_guide_topic( $post );
		$card    = ucd_guide_card( $post );
		$match   = ( ! $active || ( $topic && $topic->slug === $active ) ) && ( '' === $term || false !== strpos( ucd_guide_card_text( $post ), $term ) );
		$cards[] = $match ? $card : str_replace( '<article class="ucd-guide-card"', '<article hidden class="ucd-guide-card"', $card );
		$shown  += (int) $match;
	}
	$filtered = $active || '' !== $term;

	ob_start();
	if ( $sticky ) :
		?>
<div class="ucd-guides-start" id="start"<?php echo $filtered ? ' hidden' : ''; ?>>
	<div class="ucd-intro">
		<p class="eyebrow">Start here</p>
		<h2>New to container homes? Start with these.</h2>
	</div>
	<div class="ucd-guide-grid">
		<?php foreach ( $sticky as $post ) : ?>
			<?php echo ucd_guide_card( $post ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in ucd_guide_card(). ?>
		<?php endforeach; ?>
	</div>
</div>
	<?php endif; ?>
<div class="ucd-guides-all" id="all">
	<div class="ucd-intro">
		<p class="eyebrow">All guides</p>
		<h2>Every guide and build update.</h2>
	</div>
	<?php if ( $topics ) : ?>
	<div class="ucd-guide-filters" role="group" aria-label="Filter by topic">
		<button class="ucd-filter" type="button" data-topic="" aria-pressed="<?php echo $active ? 'false' : 'true'; ?>">All</button>
		<?php foreach ( $topics as $topic ) : ?>
		<button class="ucd-filter" type="button" data-topic="<?php echo esc_attr( $topic->slug ); ?>" aria-pressed="<?php echo $active === $topic->slug ? 'true' : 'false'; ?>"><?php echo esc_html( $topic->name ); ?></button>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<p class="ucd-guides-count" id="guides-count" aria-live="polite"><?php echo $shown ? esc_html( $shown . ( 1 === $shown ? ' guide' : ' guides' ) ) : ''; ?></p>
	<div class="ucd-guide-grid" id="guides-list">
		<?php echo implode( "\n", $cards ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in ucd_guide_card(). ?>
	</div>
	<div class="ucd-guides-empty" id="guides-empty"<?php echo $shown ? ' hidden' : ''; ?>>
		<p>Nothing here yet. Have a question you want answered? <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Ask us</a> and it might be our next guide.</p>
	</div>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_guides', 'ucd_guides_shortcode' );

/*
 * Newsletter
 */

function ucd_newsletter_form_shortcode() {
	wp_enqueue_script( 'ucd-guides', ucd_get_asset( 'js/guides.js' ), array(), UCD_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	$result = isset( $_GET['newsletter'] ) ? sanitize_key( wp_unslash( $_GET['newsletter'] ) ) : '';
	ob_start();
	?>
<div class="ucd-newsletter" id="newsletter-form">
	<form id="news-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate<?php echo 'sent' === $result ? ' hidden' : ''; ?>>
		<input type="hidden" name="action" value="ucd_newsletter">
		<input type="hidden" name="ucd_started" value="<?php echo esc_attr( time() ); ?>">
		<div class="ucd-contact-form__trap" aria-hidden="true"><label for="n-website">Website</label><input id="n-website" name="website" type="text" tabindex="-1" autocomplete="off"></div>
		<div class="field">
			<label for="n-email">Email</label>
			<input id="n-email" name="email" type="email" autocomplete="email" aria-describedby="n-email-err"<?php echo 'invalid' === $result ? ' aria-invalid="true"' : ''; ?>>
			<span class="err" id="n-email-err"<?php echo 'invalid' === $result ? '' : ' hidden'; ?>>Enter an email like name@example.com.</span>
		</div>
		<button class="btn primary" type="submit">Sign me up</button>
	</form>
	<p class="ucd-newsletter__note" id="news-note"<?php echo 'sent' === $result ? ' hidden' : ''; ?>>No spam. Unsubscribe anytime.</p>
	<p class="ucd-newsletter__done" id="news-done" role="status"<?php echo 'sent' === $result ? '' : ' hidden'; ?>>You're on the list. The next guide comes straight to your inbox.</p>
	<p class="ucd-newsletter__fail" id="news-fail" role="alert"<?php echo 'failed' === $result ? '' : ' hidden'; ?>>That didn't go through. Try again in a minute.</p>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_newsletter_form', 'ucd_newsletter_form_shortcode' );

// Same checks as the plans form: no nonce (cached pages), a hidden field and a minimum fill time.
function ucd_newsletter_submit() {
	$json   = isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) ), 'application/json' );
	$back   = remove_query_arg( 'newsletter', wp_get_referer() ? wp_get_referer() : home_url( '/guides/' ) );
	$email  = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$bot    = ! empty( $_POST['website'] ) || time() - ( isset( $_POST['ucd_started'] ) ? (int) $_POST['ucd_started'] : 0 ) < 3;
	$result = 'sent';
	if ( ! $bot ) {
		$result = ! is_email( $email ) ? 'invalid' : ( ucd_newsletter_deliver( $email ) ? 'sent' : 'failed' );
	}
	if ( $json ) {
		if ( 'sent' === $result ) {
			wp_send_json_success();
		}
		wp_send_json_error( array( 'fields' => 'invalid' === $result ? array( 'email' ) : array() ), 'invalid' === $result ? 400 : 500 );
	}
	wp_safe_redirect( add_query_arg( 'newsletter', $result, $back ) . '#newsletter-form' );
	exit;
}
add_action( 'admin_post_nopriv_ucd_newsletter', 'ucd_newsletter_submit' );
add_action( 'admin_post_ucd_newsletter', 'ucd_newsletter_submit' );

// Passes the signup to the mailing list and saves it under Messages. Succeeds if either works.
function ucd_newsletter_deliver( $email ) {
	$listed  = ucd_mailing_list_add(
		array(
			'email' => $email,
			'tags'  => 'guides',
		)
	);
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'ucd_message',
			'post_status' => 'private',
			'post_title'  => $email . ' · Guides newsletter',
			'meta_input'  => array(
				'_ucd_details' => array(
					'Email'        => $email,
					'Topic'        => 'Guides newsletter',
					'Mailing list' => null === $listed ? 'No list endpoint set' : ucd_mailing_list_note( $listed ),
				),
			),
		)
	);
	return (bool) $listed || (bool) $post_id;
}

/*
 * Articles
 */

// The call to action under an article, matched to its topic.
function ucd_guide_cta( $topic_slug ) {
	switch ( $topic_slug ) {
		case 'costs-financing':
			return array( 'See your number.', 'Build your home online and see the total, or apply and a financing representative will reach out.', array( 'Build Your Home' => home_url( '/build/' ), 'Apply for financing' => home_url( '/financing/' ) ) );
		case 'permits-zoning':
		case 'delivery':
			return array( 'Find out what it takes to get one to you.', 'Get a delivery quote, or see the rules where you\'re building.', array( 'Get a delivery quote' => home_url( '/contact/' ), 'See your state' => home_url( '/service-areas/' ) ) );
		case 'building-design':
		case 'diy':
			return array( 'Building your own?', 'Talk it through on a free call, or join the list for our building plans.', array( 'Book a free discovery call' => home_url( '/plans/#talk' ), 'Join the plans list' => home_url( '/plans/#plans-list' ) ) );
		case 'build-updates':
			return array( 'See the finished home.', 'Walk through The Rising Sun in 3D, or see every spec and piece of furniture.', array( 'Take the 3D tour' => home_url( '/gallery/#tour' ), 'See the model' => home_url( '/models/the-rising-sun/' ) ) );
	}
	return array( 'See what yours would cost.', 'Pick a model, choose your finishes and upgrades, and get your total in about five minutes.', array( 'Build Your Home' => home_url( '/build/' ), 'Book a call' => 'tel:[PHONE]' ) );
}

// Up to three other published guides on the same topic, newest first.
function ucd_guide_related( $post = null ) {
	$post  = get_post( $post );
	$topic = ucd_guide_topic( $post );
	if ( ! $topic ) {
		return array();
	}
	return get_posts(
		array(
			'post_type'           => 'post',
			'numberposts'         => 3,
			'cat'                 => $topic->term_id,
			'post__not_in'        => array( $post->ID ),
			'ignore_sticky_posts' => true,
		)
	);
}

function ucd_article_schema() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$post   = get_queried_object();
	$schema = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title( $post ),
		'description'      => get_the_excerpt( $post ),
		'author'           => array(
			'@type'    => 'Person',
			'name'     => get_the_author_meta( 'display_name', $post->post_author ),
			'worksFor' => array( '@type' => 'Organization', 'name' => 'UnContained Dreams' ),
		),
		'publisher'        => array(
			'@type' => 'Organization',
			'name'  => 'UnContained Dreams',
			'logo'  => array( '@type' => 'ImageObject', 'url' => ucd_get_asset( 'brand/logo.png' ) ),
		),
		'datePublished'    => get_the_date( 'c', $post ),
		'dateModified'     => get_the_modified_date( 'c', $post ),
		'mainEntityOfPage' => get_permalink( $post ),
	);
	if ( has_post_thumbnail( $post ) ) {
		$schema['image'] = array( get_the_post_thumbnail_url( $post, 'full' ) );
	}
	printf( "<script type=\"application/ld+json\">%s</script>\n", wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
}
add_action( 'wp_head', 'ucd_article_schema' );

/*
 * The planned articles, added once
 */

function ucd_guides_seed() {
	if ( get_option( 'ucd_guides_seeded' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	update_option( 'ucd_guides_seeded', 1 );
	$data = ucd_guides_data();

	foreach ( isset( $data['topics'] ) ? $data['topics'] : array() as $topic ) {
		if ( ! term_exists( $topic['id'], 'category' ) ) {
			wp_insert_term( $topic['label'], 'category', array( 'slug' => $topic['id'] ) );
		}
	}

	$pattern = WP_Block_Patterns_Registry::get_instance()->get_registered( 'uncontained-dreams/guide-article' );
	foreach ( isset( $data['posts'] ) ? $data['posts'] : array() as $planned ) {
		if ( get_page_by_path( $planned['slug'], OBJECT, 'post' ) ) {
			continue;
		}
		$notes = array();
		if ( ! empty( $planned['needs'] ) ) {
			$notes[] = 'NEEDS FROM THE OWNER: ' . $planned['needs'];
		}
		if ( ! empty( $planned['hold'] ) ) {
			$notes[] = 'HOLD, DON\'T PUBLISH UNTIL ANSWERED: ' . $planned['hold'];
		}
		if ( ! empty( $planned['image'] ) ) {
			$notes[] = 'FEATURED IMAGE: upload ' . basename( str_replace( '-800', '-1600', $planned['image'] ) ) . ' from assets/img/gallery/.';
		}
		$content = '';
		foreach ( $notes as $note ) {
			$content .= "<!-- wp:paragraph -->\n<p>[" . esc_html( $note ) . "]</p>\n<!-- /wp:paragraph -->\n\n";
		}
		$term = get_term_by( 'slug', $planned['topic'], 'category' );
		wp_insert_post(
			array(
				'post_type'     => 'post',
				'post_status'   => 'draft',
				'post_name'     => $planned['slug'],
				'post_title'    => $planned['title'],
				'post_excerpt'  => $planned['summary'],
				'post_content'  => $content . ( $pattern ? $pattern['content'] : '' ),
				'post_category' => $term ? array( $term->term_id ) : array(),
			)
		);
	}

	foreach ( isset( $data['startHere'] ) ? $data['startHere'] : array() as $slug ) {
		$post = get_page_by_path( $slug, OBJECT, 'post' );
		if ( $post ) {
			stick_post( $post->ID );
		}
	}
}
add_action( 'admin_init', 'ucd_guides_seed' );
