<?php
/**
 * Default page template, also used for the homepage.
 *
 * Pages built from the full-width section patterns (see patterns/) render
 * edge to edge and bring their own hero heading. Plain text pages get the
 * page title and a readable column.
 *
 * The homepage is the page chosen under Settings > Reading > A static page
 * (start it from the "Home page" pattern). If that's set to "Your latest
 * posts" instead, WordPress shows the post list from index.php.
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>

<?php if ( ucd_page_has_sections() ) : ?>
<main id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
	<?php the_content(); ?>
</main>
<?php else : ?>
<main id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content entry-content--plain' ); ?>>
	<h1 class="entry-title"><?php the_title(); ?></h1>
	<?php the_content(); ?>
</main>
<?php endif; ?>

	<?php
endwhile;

get_footer();
