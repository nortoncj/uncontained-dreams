<?php
/**
 * Title: Gallery: photo and video sections
 * Slug: uncontained-dreams/gallery-sections
 * Categories: uncontained-dreams
 * Description: The gallery body: one section per category (heading, intro, Gallery block) plus a Video section. Each section gets a filter button named after its heading. Add photos to a Gallery block from the Media Library; give a photo the is-wide class (Advanced > Additional CSS class) to span two columns. Insert the "Gallery: category" pattern inside this group to add a category.
 */
?>
<!-- wp:group {"align":"full","className":"ucd-gallery-body"} -->
<div class="wp-block-group alignfull ucd-gallery-body"><!-- wp:group {"className":"wrap"} -->
<div class="wp-block-group wrap"><!-- wp:group {"anchor":"exteriors","className":"ucd-gallery-section"} -->
<div id="exteriors" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Exteriors</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Steel outside, finished like a real home. These are homes on their actual lots.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"interiors","className":"ucd-gallery-section"} -->
<div id="interiors" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Interiors</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Full kitchens, full baths, and room to live. Shot as delivered, furniture included.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"furniture","className":"ucd-gallery-section"} -->
<div id="furniture" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Custom furniture</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Every piece is designed and built for the space, and it all comes with the home.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"build","className":"ucd-gallery-section"} -->
<div id="build" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Build process</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>From bare container to finished home in our Texas shop. This is the part most builders won't show you.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"delivery","className":"ucd-gallery-section"} -->
<div id="delivery" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Delivery day</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Trucked to the site and set by crane. [X] homes delivered to [X] states so far.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"media","imageCrop":false,"className":"ucd-gallery"} -->
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"></figure>
<!-- /wp:gallery --></div>
<!-- /wp:group -->

<!-- wp:group {"anchor":"video","className":"ucd-gallery-section"} -->
<div id="video" class="wp-block-group ucd-gallery-section"><!-- wp:group {"className":"ucd-gallery-head"} -->
<div class="wp-block-group ucd-gallery-head"><!-- wp:heading -->
<h2 class="wp-block-heading">Video</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Walkthroughs, build time-lapses, and delivery days. Hit play, sound on.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-video-list"} -->
<div class="wp-block-group ucd-video-list"><!-- wp:group {"className":"ucd-video-item ucd-video-item--feature"} -->
<div class="wp-block-group ucd-video-item ucd-video-item--feature"><!-- wp:video -->
<figure class="wp-block-video"></figure>
<!-- /wp:video -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">The full walkthrough</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>3-minute tour of The Rising Sun, start to finish.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-videos"} -->
<div class="wp-block-group ucd-videos"><!-- wp:group {"className":"ucd-video-item"} -->
<div class="wp-block-group ucd-video-item"><!-- wp:video -->
<figure class="wp-block-video"></figure>
<!-- /wp:video -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Delivery day in [CITY, STATE]</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>1:19 · Drone fly-in to [MODEL] on its lot</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-video-item"} -->
<div class="wp-block-group ucd-video-item"><!-- wp:video -->
<figure class="wp-block-video"></figure>
<!-- /wp:video -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Exterior walk-around</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>2:51 · A slow lap around The Rising Sun</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-video-item"} -->
<div class="wp-block-group ucd-video-item"><!-- wp:video -->
<figure class="wp-block-video"></figure>
<!-- /wp:video -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Walking up to the finished home</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>2:03 · Up the walkway and through the door of [MODEL]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-video-item"} -->
<div class="wp-block-group ucd-video-item"><!-- wp:video -->
<figure class="wp-block-video"></figure>
<!-- /wp:video -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Glass end wall, up close</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>1:07 · Cedar cladding and the glass doors on The Rising Sun</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
