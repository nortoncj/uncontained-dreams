<?php
/**
 * Title: Plans thank-you page
 * Slug: uncontained-dreams/page-plans-thank-you
 * Categories: uncontained-dreams
 * Post Types: page
 * Block Types: core/post-content
 * Description: Where the plans list form sends people after they sign up. Publish it as a child of DIY Plans with the slug thank-you, so it lives at /plans/thank-you/. It's kept out of search results automatically. The text inside [ucd_if_course]...[/ucd_if_course] only shows when they also asked about the course.
 */
?>
<!-- wp:group {"anchor":"top","align":"full","className":"ucd-section ucd-section--harbor ucd-success"} -->
<div id="top" class="wp-block-group alignfull ucd-section ucd-section--harbor ucd-success"><!-- wp:group {"className":"wrap ucd-text"} -->
<div class="wp-block-group wrap ucd-text"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">You're on the list.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ucd-lead"} -->
<p class="ucd-lead">We'll email you the day our building plans launch[ucd_if_course], and when the course opens[/ucd_if_course]. Check your inbox for a welcome email from [SENDER NAME].</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","className":"ucd-section ucd-next"} -->
<div class="wp-block-group alignfull ucd-section ucd-next"><!-- wp:group {"className":"wrap ucd-stack ucd-stack--tight"} -->
<div class="wp-block-group wrap ucd-stack ucd-stack--tight"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">While you wait</p>
<!-- /wp:paragraph -->

<!-- wp:group {"className":"ucd-next-cards"} -->
<div class="wp-block-group ucd-next-cards"><!-- wp:group {"className":"ucd-next-card"} -->
<div class="wp-block-group ucd-next-card"><!-- wp:image {"aspectRatio":"16/9","scale":"cover"} -->
<figure class="wp-block-image"><img src="<?php ucd_asset( 'img/gallery/build-interior-framing-800.jpg' ); ?>" alt="" style="aspect-ratio:16/9;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><a href="<?php ucd_url( '/plans/#talk' ); ?>">Book a free discovery call</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-next-card"} -->
<div class="wp-block-group ucd-next-card"><!-- wp:image {"aspectRatio":"16/9","scale":"cover"} -->
<figure class="wp-block-image"><img src="<?php ucd_asset( 'img/gallery/rising-sun-exterior-front-800.jpg' ); ?>" alt="" style="aspect-ratio:16/9;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><a href="<?php ucd_url( '/models/the-rising-sun/' ); ?>">See The Rising Sun</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-next-card"} -->
<div class="wp-block-group ucd-next-card"><!-- wp:image {"aspectRatio":"16/9","scale":"cover"} -->
<figure class="wp-block-image"><img src="<?php ucd_asset( 'img/gallery/build-cabinet-boxes-800.jpg' ); ?>" alt="" style="aspect-ratio:16/9;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph -->
<p><a href="<?php ucd_url( '/faq/#diy' ); ?>">Read the FAQ</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
