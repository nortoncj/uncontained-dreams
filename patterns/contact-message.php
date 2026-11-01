<?php
/**
 * Title: Contact: details and form
 * Slug: uncontained-dreams/contact-message
 * Categories: uncontained-dreams
 * Description: Phone, email, shop address and visit policy beside the contact form. The form is the [ucd_contact_form] shortcode; messages are emailed to the address under Settings > General and saved under Messages in wp-admin. Only add a Social row for accounts that are active.
 */
?>
<!-- wp:group {"anchor":"message","align":"full","className":"ucd-section ucd-section--white"} -->
<div id="message" class="wp-block-group alignfull ucd-section ucd-section--white"><!-- wp:group {"className":"wrap ucd-split ucd-split--top ucd-contact-split"} -->
<div class="wp-block-group wrap ucd-split ucd-split--top ucd-contact-split"><!-- wp:group {"className":"ucd-contact-details"} -->
<div class="wp-block-group ucd-contact-details"><!-- wp:heading -->
<h2 class="wp-block-heading">Reach us directly</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"ucd-contact-list"} -->
<div class="wp-block-group ucd-contact-list"><!-- wp:group {"className":"ucd-contact-item"} -->
<div class="wp-block-group ucd-contact-item"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Phone</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ucd-contact-phone"} -->
<p class="ucd-contact-phone"><a href="tel:[PHONE]">[PHONE]</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ucd-contact-hours"} -->
<p class="ucd-contact-hours">[Mon–Fri, X am–X pm Central]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-contact-item"} -->
<div class="wp-block-group ucd-contact-item"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Email</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="mailto:[EMAIL]">[EMAIL]</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-contact-item"} -->
<div class="wp-block-group ucd-contact-item"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Shop</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[STREET ADDRESS]<br>Sugar Land, TX [ZIP]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ucd-contact-item"} -->
<div class="wp-block-group ucd-contact-item"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Visits</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[CONFIRM: Tour the shop or a model home by appointment / photo and video tours only.]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:image {"aspectRatio":"4/3","scale":"cover","className":"ucd-shop-photo"} -->
<figure class="wp-block-image ucd-shop-photo"><img src="<?php ucd_asset( 'img/gallery/build-tabletop-glue-up-800.jpg' ); ?>" alt="Walnut tabletop clamped during glue-up on the shop bench" style="aspect-ratio:4/3;object-fit:cover"/><figcaption class="wp-element-caption">In the shop. The person who answers the phone is the one building your home.</figcaption></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:shortcode -->
[ucd_contact_form]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
