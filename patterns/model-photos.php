<?php
/**
 * Title: Model: photos
 * Slug: uncontained-dreams/model-photos
 * Categories: uncontained-dreams
 * Description: White section with one photo category per room, each with its own Gallery block, and a filter menu like the Gallery page's: each category gets a button named after its heading, and photos open in the lightbox. Captions follow "Model · What you're looking at · Where · When". To add a room, copy a category and give it a new heading and HTML anchor (Advanced > HTML anchor, e.g. photos-loft).
 */
?>
<!-- wp:group {"anchor":"photos","align":"full","className":"ucd-section ucd-section--white"} -->
<div id="photos" class="wp-block-group alignfull ucd-section ucd-section--white"><!-- wp:group {"className":"wrap ucd-stack"} -->
<div class="wp-block-group wrap ucd-stack"><!-- wp:group {"className":"ucd-head-row"} -->
<div class="wp-block-group ucd-head-row"><!-- wp:group {"className":"ucd-intro"} -->
<div class="wp-block-group ucd-intro"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Photos</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">See it inside and out.</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"ucd-link"} -->
<p class="ucd-link"><a href="<?php ucd_url( '/gallery/' ); ?>">Full gallery</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-gallery-body ucd-gallery-body--inline"} -->
<div class="wp-block-group ucd-gallery-body ucd-gallery-body--inline"><!-- wp:group {"anchor":"photos-exterior","className":"ucd-gallery-section"} -->
<div id="photos-exterior" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Exterior</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"photos-living","className":"ucd-gallery-section"} -->
<div id="photos-living" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Living</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"photos-kitchen","className":"ucd-gallery-section"} -->
<div id="photos-kitchen" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Kitchen</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"photos-bedroom","className":"ucd-gallery-section"} -->
<div id="photos-bedroom" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Bedroom</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"photos-bath","className":"ucd-gallery-section"} -->
<div id="photos-bath" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Bath</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"photos-furniture","className":"ucd-gallery-section"} -->
<div id="photos-furniture" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Furniture</h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
