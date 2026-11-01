<?php
/**
 * Title: Gallery: category
 * Slug: uncontained-dreams/gallery-category
 * Categories: uncontained-dreams
 * Description: One empty gallery category: heading, intro and a Gallery block to fill from the Media Library. Insert it inside the gallery sections group; the heading becomes its filter button. While it's empty, filtering to it shows a "photos coming soon" note.
 */
?>
<!-- wp:group {"className":"ucd-gallery-section"} -->
<div class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading -->
<h2 class="wp-block-heading">[CATEGORY]</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>[One line about what these photos show.]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->
