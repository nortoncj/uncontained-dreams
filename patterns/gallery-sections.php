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
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"><!-- wp:image {"linkDestination":"media","className":"is-wide"} -->
<figure class="wp-block-image is-wide"><a href="<?php ucd_asset( 'img/gallery/rising-sun-exterior-front-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-exterior-front-800.jpg' ); ?>" alt="Grey shipping container home with wood-trimmed windows and front steps on a grass lot, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Front entry and cedar-trimmed windows · Delivered to [CITY, STATE] · [YEAR]</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-exterior-corner-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-exterior-corner-800.jpg' ); ?>" alt="Grey container home from the corner, three wood-framed windows and a mini-split unit, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Side wall with the mini-split condenser · [CITY, STATE] · [YEAR]</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-exterior-glass-doors-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-exterior-glass-doors-800.jpg' ); ?>" alt="Container home end wall with sliding glass doors, cedar siding, and steel doors swung open, The Rising Sun"/></a><figcaption class="wp-element-caption">The Rising Sun · Glass end wall, original steel doors kept · [CITY, STATE] · [YEAR]</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media","className":"is-wide"} -->
<figure class="wp-block-image is-wide"><a href="<?php ucd_asset( 'img/gallery/teal-home-exterior-aerial-walkway-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/teal-home-exterior-aerial-walkway-800.jpg' ); ?>" alt="Aerial view of a teal container home with glass doors beside a curved concrete walkway"/></a><figcaption class="wp-element-caption">[MODEL] · Set on its lot with a poured walkway · [CITY, STATE] · [YEAR]</figcaption></figure>
<!-- /wp:image --></figure>
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
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"><!-- wp:image {"linkDestination":"media","className":"is-wide"} -->
<figure class="wp-block-image is-wide"><a href="<?php ucd_asset( 'img/gallery/rising-sun-living-room-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-living-room-800.jpg' ); ?>" alt="Living room with sofa, wall-mounted TV, and the galley kitchen beyond, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Living room with the custom sofa, looking toward the kitchen</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-living-room-glass-doors-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-living-room-glass-doors-800.jpg' ); ?>" alt="Living room with sliding glass doors, walnut console table, and wall-mounted TV, The Rising Sun"/></a><figcaption class="wp-element-caption">The Rising Sun · Sliding glass doors at the living room end</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-kitchen-to-bedroom-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-kitchen-to-bedroom-800.jpg' ); ?>" alt="Galley kitchen with floating wood shelves, bedroom visible at the far end, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Kitchen, looking down the full length to the bedroom</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-kitchen-range-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-kitchen-range-800.jpg' ); ?>" alt="Kitchen with navy cabinets, marble-look counters, stainless range, and floating shelves, The Rising Sun"/></a><figcaption class="wp-element-caption">The Rising Sun · Full-size range and floating shelves</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-kitchen-sink-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-kitchen-sink-800.jpg' ); ?>" alt="Kitchen sink with black faucet, marble-look counters, and a full-size fridge, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Sink side of the kitchen, full-size fridge</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-kitchen-coat-hooks-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-kitchen-coat-hooks-800.jpg' ); ?>" alt="Kitchen sink under a window with wood coat hooks beside the side door, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Sink and wood coat hooks by the side door</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-hallway-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-hallway-800.jpg' ); ?>" alt="Hallway past the bathroom toward the bedroom, cedar ceiling overhead, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Hallway from the bath to the bedroom</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-bedroom-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-bedroom-800.jpg' ); ?>" alt="Bedroom with a queen bed, cedar ceiling, and ductless mini-split, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Bedroom at the far end of the home</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-vanity-to-bedroom-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-vanity-to-bedroom-800.jpg' ); ?>" alt="Bathroom vanity with vessel sink and round mirror, bedroom beyond, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Vanity and glass shower outside the bedroom</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-vanity-mirror-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-vanity-mirror-800.jpg' ); ?>" alt="Backlit round mirror over a navy vanity with a vessel sink, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Backlit mirror at the vanity</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/rising-sun-shower-and-vanity-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/rising-sun-shower-and-vanity-800.jpg' ); ?>" alt="Glass walk-in shower beside the vanity, seen from the bedroom, The Rising Sun model"/></a><figcaption class="wp-element-caption">The Rising Sun · Walk-in shower and vanity, seen from the bedroom</figcaption></figure>
<!-- /wp:image --></figure>
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
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"><!-- wp:image {"linkDestination":"media","className":"is-wide"} -->
<figure class="wp-block-image is-wide"><a href="<?php ucd_asset( 'img/gallery/furniture-walnut-dining-table-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/furniture-walnut-dining-table-800.jpg' ); ?>" alt="Walnut dining table set for dinner in front of sliding glass doors, The Rising Sun"/></a><figcaption class="wp-element-caption">Walnut dining table · Set by the glass doors · Comes with The Rising Sun</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/furniture-fold-down-table-hinges-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/furniture-fold-down-table-hinges-800.jpg' ); ?>" alt="Close-up of a walnut fold-down table with brass hinges and bowls of fruit"/></a><figcaption class="wp-element-caption">Fold-down walnut table · Hinged so it drops out of the way</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/furniture-fold-down-table-counter-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/furniture-fold-down-table-counter-800.jpg' ); ?>" alt="Walnut fold-down table extending off the kitchen counter beside a bench seat"/></a><figcaption class="wp-element-caption">Fold-down table · Extends off the counter, folds away to open the room</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/furniture-bench-seat-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/furniture-bench-seat-800.jpg' ); ?>" alt="Wood-framed bench seat with patterned and yellow cushions beside a window"/></a><figcaption class="wp-element-caption">Bench seat · Wood frame built for the space, cushions included</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/furniture-sofa-side-table-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/furniture-sofa-side-table-800.jpg' ); ?>" alt="Walnut side table beside the sofa, holding a bowl of popcorn and a remote"/></a><figcaption class="wp-element-caption">Sofa side table · Sized to fit between the sofa and the glass doors</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/furniture-floating-shelves-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/furniture-floating-shelves-800.jpg' ); ?>" alt="Wood floating shelves with a hanging wine glass rack above the kitchen counter"/></a><figcaption class="wp-element-caption">Floating shelves · Hanging glass rack underneath</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/furniture-walnut-side-table-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/furniture-walnut-side-table-800.jpg' ); ?>" alt="Walnut end table with a maple stripe, fresh off the bench"/></a><figcaption class="wp-element-caption">Walnut end table · Maple inlay stripe · Built in our shop</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/furniture-walnut-tabletop-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/furniture-walnut-tabletop-800.jpg' ); ?>" alt="Top view of a walnut table with a light maple center stripe"/></a><figcaption class="wp-element-caption">Walnut tabletop · Maple center stripe</figcaption></figure>
<!-- /wp:image --></figure>
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
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"><!-- wp:image {"linkDestination":"media","className":"is-wide"} -->
<figure class="wp-block-image is-wide"><a href="<?php ucd_asset( 'img/gallery/build-window-openings-aerial-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-window-openings-aerial-800.jpg' ); ?>" alt="Aerial view of a bare container with window and door openings cut into the steel"/></a><figcaption class="wp-element-caption">Window openings cut · Week [X] of the build · Our Texas shop</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-interior-framing-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-interior-framing-800.jpg' ); ?>" alt="Builder framing a wall section on the shop floor"/></a><figcaption class="wp-element-caption">Framing in progress · Week [X] of the build · Our Texas shop</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-wall-framing-outdoors-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-wall-framing-outdoors-800.jpg' ); ?>" alt="Builder fastening a tall framed wall section in a backyard"/></a><figcaption class="wp-element-caption">Wall framing · Week [X] of the build</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-cabinet-boxes-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-cabinet-boxes-800.jpg' ); ?>" alt="Unfinished birch cabinet boxes stacked in the shop"/></a><figcaption class="wp-element-caption">Cabinet boxes, built to the layout · Our Texas shop</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-cabinet-drawer-slides-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-cabinet-drawer-slides-800.jpg' ); ?>" alt="Unfinished cabinet with drawer slides installed"/></a><figcaption class="wp-element-caption">Drawer slides going in · Our Texas shop</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-cabinet-face-frame-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-cabinet-face-frame-800.jpg' ); ?>" alt="Close-up of an unfinished cabinet with full-extension drawer slides"/></a><figcaption class="wp-element-caption">Cabinet before paint · Our Texas shop</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-tabletop-glue-up-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-tabletop-glue-up-800.jpg' ); ?>" alt="Walnut tabletop clamped during glue-up on the shop bench"/></a><figcaption class="wp-element-caption">Walnut tabletop in clamps · Our Texas shop</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-routing-panel-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-routing-panel-800.jpg' ); ?>" alt="Hands guiding a grooved wood panel across the shop table"/></a><figcaption class="wp-element-caption">Routing a slatted panel · Our Texas shop</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-shower-tile-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-shower-tile-800.jpg' ); ?>" alt="Large marble-look shower tiles set with leveling clips"/></a><figcaption class="wp-element-caption">Shower tile going in · Week [X] of the build</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/build-backsplash-tile-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/build-backsplash-tile-800.jpg' ); ?>" alt="Grey-green subway tile backsplash being installed above a counter"/></a><figcaption class="wp-element-caption">Backsplash tile · Week [X] of the build</figcaption></figure>
<!-- /wp:image --></figure>
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
<figure class="wp-block-gallery has-nested-images columns-default ucd-gallery"><!-- wp:image {"linkDestination":"media","className":"is-wide"} -->
<figure class="wp-block-image is-wide"><a href="<?php ucd_asset( 'img/gallery/delivery-home-on-flatbed-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/delivery-home-on-flatbed-800.jpg' ); ?>" alt="Teal container home on a flatbed trailer pulled by a pickup truck"/></a><figcaption class="wp-element-caption">[MODEL] on the trailer, headed to the lot · [CITY, STATE] · [YEAR]</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/delivery-crane-on-site-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/delivery-crane-on-site-800.jpg' ); ?>" alt="Crane truck beside a teal container home on a trailer at a fenced lot"/></a><figcaption class="wp-element-caption">Crane on site to set [MODEL] · [CITY, STATE] · [YEAR]</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/delivery-site-aerial-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/delivery-site-aerial-800.jpg' ); ?>" alt="Aerial view of a lot with pier footings set beside a poured L-shaped walkway"/></a><figcaption class="wp-element-caption">Site ready: piers set, walkway poured · [CITY, STATE] · [YEAR]</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/delivery-pier-footings-walkway-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/delivery-pier-footings-walkway-800.jpg' ); ?>" alt="Row of concrete pier footings beside a new concrete walkway"/></a><figcaption class="wp-element-caption">Pier footings ready for the home · [CITY, STATE]</figcaption></figure>
<!-- /wp:image -->

<!-- wp:image {"linkDestination":"media"} -->
<figure class="wp-block-image"><a href="<?php ucd_asset( 'img/gallery/delivery-pier-footings-1600.jpg' ); ?>"><img src="<?php ucd_asset( 'img/gallery/delivery-pier-footings-800.jpg' ); ?>" alt="Close-up of a round concrete pier footing on a gravel pad"/></a><figcaption class="wp-element-caption">Concrete pier, set before delivery day · [CITY, STATE]</figcaption></figure>
<!-- /wp:image --></figure>
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
<figure class="wp-block-video"><video controls poster="<?php ucd_asset( 'img/gallery/video-poster-rising-sun-walkthrough.jpg' ); ?>" preload="none" src="<?php ucd_asset( 'Website/Videos/Walk%20through%20floating.mp4' ); ?>" playsinline></video></figure>
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
<figure class="wp-block-video"><video controls poster="<?php ucd_asset( 'img/gallery/video-poster-teal-home-fly-in.jpg' ); ?>" preload="none" src="<?php ucd_asset( 'Website/Videos/DJI%20finished%20container%20fly%20in.MP4' ); ?>" playsinline></video></figure>
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
<figure class="wp-block-video"><video controls poster="<?php ucd_asset( 'img/gallery/video-poster-rising-sun-exterior.jpg' ); ?>" preload="none" src="<?php ucd_asset( 'Website/Videos/exterior%20slow%20walk%20around.mp4' ); ?>" playsinline></video></figure>
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
<figure class="wp-block-video"><video controls poster="<?php ucd_asset( 'img/gallery/video-poster-teal-home-arrival.jpg' ); ?>" preload="none" src="<?php ucd_asset( 'Website/Videos/finished%20container%20opening%20door%20entering.MP4' ); ?>" playsinline></video></figure>
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
<figure class="wp-block-video"><video controls poster="<?php ucd_asset( 'img/gallery/video-poster-rising-sun-glass-end.jpg' ); ?>" preload="none" src="<?php ucd_asset( 'Website/Videos/VID_20230517_112601.mp4' ); ?>" playsinline></video></figure>
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
