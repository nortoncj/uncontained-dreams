<?php
/**
 * Site header and mega menu. Opens the page wrapper that footer.php closes.
 *
 * Links to homepage sections use /#anchor so the menu works from every page.
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
      <img class="site-logo__img" src="<?php ucd_asset( 'brand/logo.png' ); ?>" alt="UnContained Dreams logo" width="230" height="80"/>
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
                  <div class="mega-card__img"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Front%20View.jpg' ); ?>" alt="" loading="lazy"></div>
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
                  <div class="mega-card__img"><img src="<?php ucd_asset( 'Website/Build%20%20plans/File%20Name%20Lost%20(491).jpg' ); ?>" alt="" loading="lazy"></div>
                  <div class="mega-card__body">
                    <span class="mega-card__title">Built in our shop</span>
                    <span class="mega-card__text">Cabinets, tables and framing made by hand for each layout.</span>
                    <span class="mega-card__cta">See the build process</span>
                  </div>
                </a>
              </div>
              <div class="mega-col mega-col--span-4 mega-col--feature">
                <a class="mega-card mega-card--oak" href="<?php ucd_url( '/financing/' ); ?>">
                  <div class="mega-card__img"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Living%20room%20_%20Kitchen%20view.jpg' ); ?>" alt="" loading="lazy"></div>
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
                  <li class="mega-tile"><a href="<?php ucd_url( '/gallery/#exteriors' ); ?>"><span class="mega-tile__img"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Front%20Corner.jpg' ); ?>" alt="" loading="lazy"></span>Finished homes</a></li>
                  <li class="mega-tile"><a href="<?php ucd_url( '/gallery/#interiors' ); ?>"><span class="mega-tile__img"><img src="<?php ucd_asset( 'Website/Gallery/20230601_163705.jpg' ); ?>" alt="" loading="lazy"></span>Interiors</a></li>
                  <li class="mega-tile"><a href="<?php ucd_url( '/gallery/#build' ); ?>"><span class="mega-tile__img"><img src="<?php ucd_asset( 'Website/Build%20%20plans/Cabinet%20Stacked.JPG' ); ?>" alt="" loading="lazy"></span>Build process</a></li>
                  <li class="mega-tile"><a href="<?php ucd_url( '/gallery/#delivery' ); ?>"><span class="mega-tile__img"><img src="<?php ucd_asset( 'Website/Gallery/DJI_0434.JPG' ); ?>" alt="" loading="lazy"></span>Delivery day</a></li>
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
                  <div class="mega-card__img"><img src="<?php ucd_asset( 'Website/Gallery/DJI_0411.JPG' ); ?>" alt="" loading="lazy"></div>
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
        <a class="header-phone" href="#contact">[PHONE]</a>
        <a class="btn primary" href="<?php ucd_url( '/build/' ); ?>">Build Your Home</a>
      </div>
    </nav>
  </div>
</header>
