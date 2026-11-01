<?php
/**
 * Title: Team card
 * Slug: uncontained-dreams/team-card
 * Categories: uncontained-dreams
 * Description: One person: headshot, name, role and two sentences. Add one per team member.
 */
?>
<!-- wp:group {"className":"team-card"} -->
<div class="wp-block-group team-card"><!-- wp:image {"aspectRatio":"1","scale":"cover"} -->
<figure class="wp-block-image"><img alt="Headshot of [FULL NAME]" style="aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group -->
<div class="wp-block-group"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">[FULL NAME]</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"team-card__role"} -->
<p class="team-card__role">[ROLE]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[2 sentences: background, why they build, one human detail.]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
