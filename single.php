<?php
/**
 * A guide, build update or news post (/guides/{slug}/).
 *
 * Top to bottom: breadcrumb, title, byline, featured image, the short answer
 * (the post's excerpt), the article, a call to action matched to the topic,
 * and up to three related guides. The byline's role comes from the author's
 * Biographical Info under Users > Profile, e.g. "founder of UnContained Dreams".
 * Start a new post from the "Guide article" pattern.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$topic   = ucd_guide_topic();
	$role    = get_the_author_meta( 'description' );
	$cta     = ucd_guide_cta( $topic ? $topic->slug : '' );
	$related = ucd_guide_related();
	?>
<main id="post-<?php the_ID(); ?>" <?php post_class( 'ucd-article-page' ); ?>>
	<div class="wrap">
		<article class="ucd-article">
			<nav aria-label="Breadcrumb">
				<ol class="ucd-crumbs">
					<li><a href="<?php ucd_url( '/' ); ?>">Home</a></li>
					<li><a href="<?php ucd_url( '/guides/' ); ?>">Guides</a></li>
					<?php if ( $topic ) : ?>
					<li><a href="<?php echo esc_url( ucd_guide_topic_url( $topic->slug ) ); ?>"><?php echo esc_html( $topic->name ); ?></a></li>
					<?php endif; ?>
				</ol>
			</nav>

			<header class="ucd-article__head">
				<h1><?php the_title(); ?></h1>
				<p class="ucd-byline">By <strong><?php the_author(); ?></strong><?php echo $role ? ', ' . esc_html( $role ) : ''; ?> · <time datetime="<?php echo esc_attr( get_the_modified_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( ucd_guide_updated() ); ?></time> · <?php echo esc_html( ucd_guide_read_minutes() ); ?> min read</p>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
			<figure class="ucd-article__photo">
				<?php the_post_thumbnail( 'large', array( 'fetchpriority' => 'high' ) ); ?>
				<?php if ( get_the_post_thumbnail_caption() ) : ?>
				<figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
				<?php endif; ?>
			</figure>
			<?php endif; ?>

			<?php if ( has_excerpt() ) : ?>
			<section class="ucd-short" aria-labelledby="short-h">
				<span class="eyebrow" id="short-h">The short answer</span>
				<p><?php echo esc_html( get_the_excerpt() ); ?></p>
			</section>
			<?php endif; ?>

			<div class="ucd-prose">
				<?php the_content(); ?>
			</div>

			<aside class="ucd-article-cta">
				<h2><?php echo esc_html( $cta[0] ); ?></h2>
				<p><?php echo esc_html( $cta[1] ); ?></p>
				<div class="ucd-article-cta__btns">
					<?php
					$first = true;
					foreach ( $cta[2] as $label => $url ) :
						?>
					<a class="btn <?php echo $first ? 'primary' : 'ghost'; ?>" href="<?php echo esc_url( $url, array( 'http', 'https', 'tel' ) ); ?>"><?php echo esc_html( $label ); ?></a>
						<?php
						$first = false;
					endforeach;
					?>
				</div>
			</aside>
		</article>
	</div>

	<?php if ( $related ) : ?>
	<section class="ucd-related">
		<div class="wrap">
			<h2>Related guides</h2>
			<div class="ucd-guide-grid">
				<?php foreach ( $related as $post_item ) : ?>
					<?php echo ucd_guide_card( $post_item ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in ucd_guide_card(). ?>
				<?php endforeach; ?>
			</div>
			<p class="ucd-link"><a href="<?php ucd_url( '/guides/' ); ?>">All guides</a></p>
		</div>
	</section>
	<?php endif; ?>
</main>
	<?php
endwhile;

get_footer();
