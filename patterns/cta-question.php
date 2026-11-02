<?php
/**
 * Title: Call to action: still have a question?
 * Slug: uncontained-dreams/cta-question
 * Categories: uncontained-dreams
 * Description: Closing navy box over a full-width photo, inviting a call or message. Upload the background photo to the Image block at the top of the section (or replace it with a Video block for a silent looping video); it stays navy until you do.
 */
?>
<!-- wp:group {"align":"full","className":"ucd-cta"} -->
<div class="wp-block-group alignfull ucd-cta"><!-- wp:image -->
<figure class="wp-block-image"><img alt=""/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"wrap"} -->
<div class="wp-block-group wrap"><!-- wp:group {"className":"ucd-cta__box"} -->
<div class="wp-block-group ucd-cta__box"><!-- wp:heading -->
<h2 class="wp-block-heading">Still have a question?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Call <a href="tel:[PHONE]">[PHONE]</a> or send us a message. You'll hear back from the person building your home, usually within [1 business day].</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="mailto:[EMAIL]">Ask a question</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php ucd_url( '/build/' ); ?>">Build Your Home</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
