<?php
/**
 * Title: Service Areas page
 * Slug: uncontained-dreams/page-service-areas
 * Categories: uncontained-dreams
 * Post Types: page
 * Block Types: core/post-content
 * Description: The full Service Areas page (slug service-areas): hero with the ZIP check and map, how delivery works, the state list and a closing call to action. State pages themselves live under Container Homes (/container-homes/texas/).
 */
?>
<!-- wp:pattern {"slug":"uncontained-dreams/service-areas-hero"} /-->

<!-- wp:pattern {"slug":"uncontained-dreams/service-areas-steps"} /-->

<!-- wp:pattern {"slug":"uncontained-dreams/service-areas-states"} /-->

<!-- wp:group {"anchor":"cta","align":"full","className":"ucd-cta"} -->
<div id="cta" class="wp-block-group alignfull ucd-cta"><!-- wp:image -->
<figure class="wp-block-image"><img alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"wrap"} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"ucd-cta__box"} -->
<div class="wp-block-group ucd-cta__box"><!-- wp:heading -->
<h2 class="wp-block-heading">See your price, delivery included.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Pick a model, choose your upgrades, and add your ZIP. You'll see what's included and what gets quoted before you sign.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php ucd_url( '/build/' ); ?>">Build Your Home</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="tel:[PHONE]">Book a call</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
