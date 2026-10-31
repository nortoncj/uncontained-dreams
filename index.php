<?php
/**
 * Fallback template, required by WordPress. Renders any view without a more
 * specific template until the static pages get their own.
 */

get_header();
?>

<main class="wrap" style="padding-top: 72px; padding-bottom: 96px; display: flex; flex-direction: column; gap: 48px;">
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="display: flex; flex-direction: column; gap: 20px; font-size: 17px; line-height: 28px;">
    <?php if ( is_singular() ) : ?>
    <h1 style="font-size: 44px; line-height: 46px; font-weight: 800; color: #14324A;"><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <?php else : ?>
    <h2 style="font-size: 28px; line-height: 34px; font-weight: 800;"><a href="<?php the_permalink(); ?>" style="text-decoration: none;"><?php the_title(); ?></a></h2>
    <?php the_excerpt(); ?>
    <?php endif; ?>
  </article>
  <?php endwhile; ?>
  <?php the_posts_pagination(); ?>
<?php else : ?>
  <p style="font-size: 17px; line-height: 28px;">Nothing here yet.</p>
<?php endif; ?>
</main>

<?php
get_footer();
