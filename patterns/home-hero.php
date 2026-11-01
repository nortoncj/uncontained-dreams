<?php
/**
 * Title: Home: hero
 * Slug: uncontained-dreams/home-hero
 * Categories: uncontained-dreams
 * Description: Full-width photo with a background video (hidden on phones), and the headline in a navy box. The image and video in the section are the background: replace the image for the still and phones, the video for the moving version.
 */
?>
<!-- wp:group {"anchor":"top","align":"full","className":"ucd-cta ucd-cta--hero ucd-cta--home"} -->
<div id="top" class="wp-block-group alignfull ucd-cta ucd-cta--hero ucd-cta--home"><!-- wp:image -->
<figure class="wp-block-image"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Front%20Corner.jpg' ); ?>" alt=""/></figure>
<!-- /wp:image -->

<!-- wp:video -->
<figure class="wp-block-video"><video autoplay loop muted poster="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Front%20Corner.jpg' ); ?>" src="<?php ucd_asset( 'Website/Videos/exterior%20slow%20walk%20around.mp4' ); ?>" playsinline></video></figure>
<!-- /wp:video -->

<!-- wp:group {"className":"wrap"} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"ucd-cta__box"} -->
<div class="wp-block-group ucd-cta__box"><!-- wp:paragraph {"className":"chip chip--sand"} -->
<p class="chip chip--sand">Built in Texas · Shipped to all 50 states</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Texas-built container homes, delivered anywhere.</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ucd-lead"} -->
<p class="ucd-lead">Finished, furnished, and shipped to your lot, from California to New York. The Rising Sun starts at $85,000 with custom furniture and the first 1,000 miles of delivery included.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php ucd_url( '/build/' ); ?>">Build Your Home</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#tour">Take the 3D Tour</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"ucd-small"} -->
<p class="ucd-small">Financing available. Apply online and a representative will reach out.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
