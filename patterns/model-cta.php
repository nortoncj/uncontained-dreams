<?php
/**
 * Title: Model: make it yours
 * Slug: uncontained-dreams/model-cta
 * Categories: uncontained-dreams
 * Description: Closing navy box over a full-width photo, with buttons to customize the model and apply for financing. Upload the background photo to the Image block at the top of the section (or replace it with a Video block for a silent looping video); it stays navy until you do.
 */
?>
<!-- wp:group {"anchor":"cta","align":"full","className":"ucd-cta"} -->
<div id="cta" class="wp-block-group alignfull ucd-cta"><!-- wp:image -->
<figure class="wp-block-image"><img alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"wrap"} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"ucd-cta__box"} -->
<div class="wp-block-group ucd-cta__box"><!-- wp:heading -->
<h2 class="wp-block-heading">Make it yours.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Choose your finishes and upgrades and see your exact price in about five minutes. Nothing is charged today.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php ucd_url( '/build/?config=eyJtIjoicmlzaW5nLXN1biIsIm8iOnt9LCJ1IjpbXX0' ); ?>">Customize The Rising Sun</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php ucd_url( '/financing/' ); ?>">Apply for financing</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
