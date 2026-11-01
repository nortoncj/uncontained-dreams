<?php
/**
 * Title: Gallery: hero
 * Slug: uncontained-dreams/gallery-hero
 * Categories: uncontained-dreams
 * Description: Full-width photo with the page heading in a navy box. The first image in the section is the background; replace it to change the photo.
 */
?>
<!-- wp:group {"anchor":"top","align":"full","className":"ucd-cta ucd-cta--hero"} -->
<div id="top" class="wp-block-group alignfull ucd-cta ucd-cta--hero"><!-- wp:image -->
<figure class="wp-block-image"><img src="<?php ucd_asset( 'img/gallery/rising-sun-exterior-front-2400.jpg' ); ?>" alt="Grey shipping container home with wood-trimmed windows and front steps on a grass lot, The Rising Sun model"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"wrap"} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"ucd-cta__box"} -->
<div class="wp-block-group ucd-cta__box"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Gallery</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">No renders. Just the homes we've built.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ucd-lead"} -->
<p class="ucd-lead">Every photo and video here is a real home, built in our Texas shop and delivered to a real lot. If something is a rendering, we label it.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#tour">Take the 3D tour</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php ucd_url( '/build/' ); ?>">Build Your Home</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
