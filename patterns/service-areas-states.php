<?php
/**
 * Title: Service areas: state list
 * Slug: uncontained-dreams/service-areas-states
 * Categories: uncontained-dreams
 * Description: White section with the state cards ([ucd_state_cards]), grouped by region, from assets/data/routes.json. A state links to its page once routes.json marks it live and the page is published; the rest get a delivery-quote button. Fill in homes delivered and transit days in routes.json.
 */
?>
<!-- wp:group {"anchor":"states","align":"full","className":"ucd-section ucd-section--white"} -->
<div id="states" class="wp-block-group alignfull ucd-section ucd-section--white"><!-- wp:group {"className":"wrap ucd-stack ucd-stack--tight"} -->
<div class="wp-block-group wrap ucd-stack ucd-stack--tight"><!-- wp:group {"className":"ucd-intro"} -->
<div class="wp-block-group ucd-intro"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">State list</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Where we deliver most.</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:shortcode -->
[ucd_state_cards]
<!-- /wp:shortcode -->

<!-- wp:paragraph {"className":"ucd-regions__more"} -->
<p class="ucd-regions__more">Don't see your state? We deliver to all 50, plus Canada, the U.S. Virgin Islands, and other international destinations case by case. <a href="#zip">Check delivery to your ZIP</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
