<?php
/**
 * Site header and mega menu. Opens the page wrapper that footer.php closes.
 *
 * Links to homepage sections use /#anchor so the menu works from every page.
 * The logo is Appearance > Customize > Site Identity > Logo, the phone number
 * comes from Business details, and each menu photo is the Featured image of
 * the page it links to (the gallery tiles use the first photo in each section
 * of the Gallery page). A card without a photo shows its text only.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div style="display: flex; flex-direction: column; min-width: 0;">

<!-- HEADER + MEGA MENU -->
<header class="site-header">
  <div class="wrap site-header__bar">
    <a class="site-logo" href="<?php ucd_url( '/' ); ?>">
      <?php if ( has_custom_logo() ) : ?>
        <?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'site-logo__img', 'alt' => ucd_detail( 'name' ) . ' logo' ) ); ?>
      <?php else : ?>
        <span class="site-logo__text"><?php echo esc_html( ucd_detail( 'name' ) ); ?></span>
      <?php endif; ?>
    </a>

    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">
      <svg class="nav-toggle__open" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="square"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      <svg class="nav-toggle__close" aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="square"><path d="M5 5l14 14M19 5L5 19"/></svg>
      Menu
    </button>

    <nav id="primary-nav" class="primary-nav" aria-label="Main">
      <ul class="menu">

        <!-- Models -->
        <li class="menu-item menu-item-has-children">
          <button class="menu-trigger" type="button" aria-expanded="false" aria-controls="mega-models">
            Models
            <svg aria-hidden="true" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4l4 4 4-4"/></svg>
          </button>
          <div class="mega-panel" id="mega-models">
            <div class="wrap mega-panel__inner">
              <div class="mega-col">
                <p class="mega-heading">Our homes</p>
                <ul class="sub-menu">
                  <li class="menu-item"><a href="<?php ucd_url( '/models/the-rising-sun/' ); ?>">The Rising Sun <span class="menu-desc">Our flagship. From $85,000, furnished.</span></a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/build/' ); ?>">Custom build <span class="menu-desc">Your layout, our shop.</span></a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/container-homes/#models' ); ?>">Compare models <span class="menu-desc">Size, beds, baths and price side by side.</span></a></li>
                </ul>
              </div>
              <div class="mega-col">
                <p class="mega-heading">Plan it</p>
                <ul class="sub-menu">
                  <li class="menu-item"><a href="<?php ucd_url( '/build/' ); ?>">Build Your Home <span class="menu-desc">Pick upgrades, see the total.</span></a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/models/the-rising-sun/#features' ); ?>">Spec sheets <span class="menu-desc">Insulation, HVAC and wind ratings.</span></a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/gallery/#tour' ); ?>">3D tour <span class="menu-desc">Walk through before you commit.</span></a></li>
                </ul>
              </div>
              <div class="mega-col mega-col--span-6 mega-col--feature">
                <a class="mega-card" href="<?php ucd_url( '/models/the-rising-sun/' ); ?>">
                  <?php $ucd_photo = ucd_page_photo( 'models/the-rising-sun' ); ?>
                  <?php if ( $ucd_photo ) : ?><div class="mega-card__img"><?php echo $ucd_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- core image markup. ?></div><?php endif; ?>
                  <div class="mega-card__body">
                    <span class="mega-card__price">From $85,000</span>
                    <span class="mega-card__title">The Rising Sun</span>
                    <span class="mega-card__text">Custom furniture and the first 1,000 delivery miles included.</span>
                    <span class="mega-card__cta">See the model</span>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </li>

        <!-- How It Works -->
        <li class="menu-item menu-item-has-children">
          <button class="menu-trigger" type="button" aria-expanded="false" aria-controls="mega-how">
            How It Works
            <svg aria-hidden="true" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4l4 4 4-4"/></svg>
          </button>
          <div class="mega-panel" id="mega-how">
            <div class="wrap mega-panel__inner">
              <div class="mega-col mega-col--span-4">
                <p class="mega-heading">Four steps</p>
                <ul class="sub-menu sub-menu--steps">
                  <li class="menu-item"><a href="<?php ucd_url( '/how-it-works/#process' ); ?>"><span>Build it online <span class="menu-desc">Choose your model and finishes.</span></span></a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/financing/' ); ?>"><span>Check financing <span class="menu-desc">A real person reviews every request.</span></span></a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/how-it-works/#process' ); ?>"><span>We build it <span class="menu-desc">Photo updates at every stage.</span></span></a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/how-it-works/#delivery' ); ?>"><span>We deliver it <span class="menu-desc">First 1,000 miles on us.</span></span></a></li>
                </ul>
              </div>
              <div class="mega-col mega-col--span-4 mega-col--feature">
                <a class="mega-card" href="<?php ucd_url( '/how-it-works/' ); ?>">
                  <?php $ucd_photo = ucd_page_photo( 'how-it-works' ); ?>
                  <?php if ( $ucd_photo ) : ?><div class="mega-card__img"><?php echo $ucd_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- core image markup. ?></div><?php endif; ?>
                  <div class="mega-card__body">
                    <span class="mega-card__title">Built in our shop</span>
                    <span class="mega-card__text">Cabinets, tables and framing made by hand for each layout.</span>
                    <span class="mega-card__cta">See the build process</span>
                  </div>
                </a>
              </div>
              <div class="mega-col mega-col--span-4 mega-col--feature">
                <a class="mega-card mega-card--oak" href="<?php ucd_url( '/financing/' ); ?>">
                  <?php $ucd_photo = ucd_page_photo( 'financing' ); ?>
                  <?php if ( $ucd_photo ) : ?><div class="mega-card__img"><?php echo $ucd_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- core image markup. ?></div><?php endif; ?>
                  <div class="mega-card__body">
                    <span class="mega-card__title">See your monthly payment</span>
                    <span class="mega-card__text">Apply online and a financing representative will reach out.</span>
                    <span class="mega-card__cta">Check financing</span>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </li>

        <li class="menu-item"><a href="<?php ucd_url( '/financing/' ); ?>">Financing</a></li>
        <li class="menu-item"><a href="<?php ucd_url( '/plans/' ); ?>">DIY Plans</a></li>

        <!-- Gallery -->
        <li class="menu-item menu-item-has-children">
          <button class="menu-trigger" type="button" aria-expanded="false" aria-controls="mega-gallery">
            Gallery
            <svg aria-hidden="true" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4l4 4 4-4"/></svg>
          </button>
          <div class="mega-panel" id="mega-gallery">
            <div class="wrap mega-panel__inner">
              <div class="mega-col mega-col--full">
                <p class="mega-heading">From real builds</p>
                <ul class="mega-tiles">
                  <li class="mega-tile"><a href="<?php ucd_url( '/gallery/#exteriors' ); ?>"><?php $ucd_photo = ucd_gallery_photo( 'exteriors' ); ?><?php if ( $ucd_photo ) : ?><span class="mega-tile__img"><?php echo $ucd_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- core image markup. ?></span><?php endif; ?>Finished homes</a></li>
                  <li class="mega-tile"><a href="<?php ucd_url( '/gallery/#interiors' ); ?>"><?php $ucd_photo = ucd_gallery_photo( 'interiors' ); ?><?php if ( $ucd_photo ) : ?><span class="mega-tile__img"><?php echo $ucd_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- core image markup. ?></span><?php endif; ?>Interiors</a></li>
                  <li class="mega-tile"><a href="<?php ucd_url( '/gallery/#build' ); ?>"><?php $ucd_photo = ucd_gallery_photo( 'build' ); ?><?php if ( $ucd_photo ) : ?><span class="mega-tile__img"><?php echo $ucd_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- core image markup. ?></span><?php endif; ?>Build process</a></li>
                  <li class="mega-tile"><a href="<?php ucd_url( '/gallery/#delivery' ); ?>"><?php $ucd_photo = ucd_gallery_photo( 'delivery' ); ?><?php if ( $ucd_photo ) : ?><span class="mega-tile__img"><?php echo $ucd_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- core image markup. ?></span><?php endif; ?>Delivery day</a></li>
                </ul>
              </div>
              <div class="mega-footer">
                <span>No renders. Every photo is a home we built.</span>
                <a href="<?php ucd_url( '/gallery/#video' ); ?>">Watch the video walkthrough</a>
              </div>
            </div>
          </div>
        </li>

        <!-- Service Areas -->
        <li class="menu-item menu-item-has-children">
          <button class="menu-trigger" type="button" aria-expanded="false" aria-controls="mega-areas">
            Service Areas
            <svg aria-hidden="true" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 4l4 4 4-4"/></svg>
          </button>
          <div class="mega-panel" id="mega-areas">
            <div class="wrap mega-panel__inner">
              <div class="mega-col mega-col--span-7">
                <p class="mega-heading">Where we deliver</p>
                <ul class="sub-menu sub-menu--cities">
                  <li class="menu-item"><a href="<?php ucd_url( '/#areas' ); ?>">California</a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/#areas' ); ?>">Colorado</a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/#areas' ); ?>">Utah</a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/#areas' ); ?>">Wyoming</a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/#areas' ); ?>">New York</a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/container-homes/texas/' ); ?>">Texas</a></li>
                  <li class="menu-item"><a href="<?php ucd_url( '/service-areas/' ); ?>">All 50 states</a></li>
                </ul>
              </div>
              <div class="mega-col mega-col--span-5 mega-col--feature">
                <a class="mega-card" href="<?php ucd_url( '/service-areas/' ); ?>">
                  <?php $ucd_photo = ucd_page_photo( 'service-areas' ); ?>
                  <?php if ( $ucd_photo ) : ?><div class="mega-card__img"><?php echo $ucd_photo; // phpcs:ignore WordPress.Security.EscapeOutput -- core image markup. ?></div><?php endif; ?>
                  <div class="mega-card__body">
                    <span class="mega-card__title">Built in Texas. Delivered nationwide.</span>
                    <span class="mega-card__text">Shipped to all 50 states. The first 1,000 delivery miles are included in the price.</span>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </li>

        <li class="menu-item"><a href="<?php ucd_url( '/faq/' ); ?>">FAQ</a></li>
      </ul>

      <div class="header-actions">
        <?php if ( ucd_detail( 'phone' ) ) : ?>
        <a class="header-phone" href="<?php echo esc_url( ucd_phone_href(), array( 'tel' ) ); ?>"><?php echo esc_html( ucd_detail( 'phone' ) ); ?></a>
        <?php endif; ?>
        <a class="btn primary" href="<?php ucd_url( '/build/' ); ?>">Build Your Home</a>
      </div>
    </nav>
  </div>
</header>
