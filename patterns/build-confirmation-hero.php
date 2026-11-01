<?php
/**
 * Title: Build: confirmation hero
 * Slug: uncontained-dreams/build-confirmation-hero
 * Categories: uncontained-dreams
 * Description: Confirmation hero after a build is sent. [ucd_build_receipt] shows "Build #UCD-0042. We emailed a copy to ..." for the person who just sent it; leave it as typed.
 */
?>
<!-- wp:group {"anchor":"top","align":"full","className":"ucd-section ucd-section--harbor ucd-success"} -->
<div id="top" class="wp-block-group alignfull ucd-section ucd-section--harbor ucd-success"><!-- wp:group {"className":"wrap ucd-text"} -->
<div class="wp-block-group wrap ucd-text"><!-- wp:heading {"level":1,"className":"ucd-short"} -->
<h1 class="wp-block-heading ucd-short">Your build is in. Here's what happens next.</h1>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[ucd_build_receipt]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
