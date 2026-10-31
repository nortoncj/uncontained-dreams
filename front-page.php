<?php
/**
 * Homepage. WordPress uses this for the site front page whatever
 * Settings > Reading is set to.
 */

get_header();
?>

<main>

<!-- HERO -->
<section id="top" style="position: relative; background: #14324A; color: #FFFFFF;">
  <div class="media dark filled" style="position: absolute; inset: 0; border-radius: 0;">
    <img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Front%20Corner.jpg' ); ?>" alt="" fetchpriority="high">
    <video class="hero-video" src="<?php ucd_asset( 'Website/Videos/exterior%20slow%20walk%20around.mp4' ); ?>" poster="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Front%20Corner.jpg' ); ?>" autoplay muted loop playsinline preload="metadata" aria-hidden="true"></video>
  </div>
  <div class="wrap" style="position: relative; padding-top: 120px; padding-bottom: 120px;">
    <div style="background: #14324A; max-width: 620px; padding: 40px; box-sizing: border-box; display: flex; flex-direction: column; gap: 22px; border-radius: 2px;">
      <span class="chip" style="background: #E9B872; color: #14324A; width: max-content;">Built in Texas · Shipped to all 50 states</span>
      <h1 style="font-size: 60px; line-height: 60px; font-weight: 800; letter-spacing: -.015em;">Texas-built container homes, delivered anywhere.</h1>
      <p style="font-size: 19px; line-height: 30px; color: #DCE6EE;">Finished, furnished, and shipped to your lot, from California to New York. The Rising Sun starts at $85,000 with custom furniture and the first 1,000 miles of delivery included.</p>
      <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <a class="btn primary" href="<?php ucd_url( '/#build' ); ?>">Build Your Home</a>
        <a class="btn ghost light" href="<?php ucd_url( '/#tour' ); ?>">Take the 3D Tour</a>
      </div>
      <p style="font-size: 14px; color: #B7C8D6;">Financing available. Apply online and a representative will reach out.</p>
    </div>
  </div>
</section>

<!-- PROOF STRIP -->
<section style="background: #FFFFFF; border-bottom: 1px solid #D5DCDE;">
  <div class="wrap" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; padding-top: 28px; padding-bottom: 28px;">
    <div style="display: flex; flex-direction: column; gap: 4px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 26px; color: #14324A;">$85k to $120k</span><span style="font-size: 15px; color: #5A666C;">Real prices, posted before you call</span></div>
    <div style="display: flex; flex-direction: column; gap: 4px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 26px; color: #14324A;">Fully furnished</span><span style="font-size: 15px; color: #5A666C;">Custom furniture built for the space</span></div>
    <div style="display: flex; flex-direction: column; gap: 4px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 26px; color: #14324A;">All 50 states</span><span style="font-size: 15px; color: #5A666C;">Shipped nationwide, first 1,000 miles included</span></div>
    <div style="display: flex; flex-direction: column; gap: 4px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 26px; color: #14324A;">[★ 4.9 · 00 reviews]</span><span style="font-size: 15px; color: #5A666C;">Google rating, pulled live</span></div>
  </div>
</section>

<!-- FEATURED MODEL -->
<section id="models" style="padding-top: 96px; padding-bottom: 96px;">
  <div class="wrap" style="display: flex; flex-wrap: wrap; gap: 48px; align-items: center;">
    <div style="flex: 1 1 560px; min-width: 0; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px;">
      <div class="media filled" style="grid-column: span 2; aspect-ratio: 16 / 9; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Living%20Room%20in%20View.jpg' ); ?>" alt="The Rising Sun living room with the included sofa, wall-mounted TV, and kitchen beyond" loading="lazy"></div>
      <div class="media filled" style="aspect-ratio: 4 / 3; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Front%20of%20Kitchen.jpg' ); ?>" alt="Kitchen with navy cabinets, marble-look counters, and floating wood shelves" loading="lazy"></div>
      <div class="media filled" style="aspect-ratio: 4 / 3; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Bedroom%20In%20View.jpg' ); ?>" alt="Bedroom with queen bed under a cedar ceiling" loading="lazy"></div>
    </div>
    <div style="flex: 1 1 380px; min-width: 0; display: flex; flex-direction: column; gap: 20px;">
      <span class="eyebrow">Featured model</span>
      <h2 style="font-size: 44px; line-height: 46px; font-weight: 800; color: #14324A;">The Rising Sun</h2>
      <p style="font-size: 17px; line-height: 28px;">Everything a full-size home has, packed into a container footprint. Every piece of furniture you see in the photos comes with it, designed and built for this layout.</p>
      <div style="display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1px; background: #D5DCDE; border: 1px solid #D5DCDE;">
        <div style="background: #FFFFFF; padding: 14px; display: flex; flex-direction: column; gap: 2px;"><span class="eyebrow">Size</span><span style="font-weight: 700; font-size: 18px;">[000 sq ft]</span></div>
        <div style="background: #FFFFFF; padding: 14px; display: flex; flex-direction: column; gap: 2px;"><span class="eyebrow">Beds</span><span style="font-weight: 700; font-size: 18px;">[0]</span></div>
        <div style="background: #FFFFFF; padding: 14px; display: flex; flex-direction: column; gap: 2px;"><span class="eyebrow">Baths</span><span style="font-weight: 700; font-size: 18px;">[0]</span></div>
      </div>
      <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px 20px;">
        <span class="chip" style="background: #14324A; color: #E9B872; font-size: 15px; padding: 8px 16px;">From $85,000</span>
        <span style="font-size: 14px; color: #5A666C;">Up to $120,000 with every upgrade</span>
      </div>
      <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <a class="btn primary" href="<?php ucd_url( '/#build' ); ?>">Customize The Rising Sun</a>
        <a class="btn ghost" href="<?php ucd_url( '/models/the-rising-sun/' ); ?>">See full specs</a>
      </div>
    </div>
  </div>
</section>

<!-- TOUR + VIDEO -->
<section id="tour" style="background: #FFFFFF; padding-top: 96px; padding-bottom: 96px; border-top: 1px solid #D5DCDE; border-bottom: 1px solid #D5DCDE;">
  <div class="wrap" style="display: flex; flex-direction: column; gap: 32px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px;">
      <div style="display: flex; flex-direction: column; gap: 12px; max-width: 620px;">
        <span class="eyebrow">Walk through it</span>
        <h2 style="font-size: 40px; line-height: 44px; font-weight: 800; color: #14324A;">See every inch before you commit.</h2>
        <p style="font-size: 17px; line-height: 28px; color: #5A666C;">No renders standing in for the real thing. Tour the finished home in 3D or watch the walkthrough.</p>
      </div>
      <a class="btn ghost" href="<?php ucd_url( '/#tour' ); ?>">Open 3D tour in new tab</a>
    </div>
    <div style="display: flex; flex-wrap: wrap; gap: 16px;">
      <div class="media dark filled" style="flex: 2 1 520px; min-width: 0; aspect-ratio: 16 / 9; max-width: 100%;"><video src="<?php ucd_asset( 'Website/Videos/Walk%20through%20floating.mp4' ); ?>" poster="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20living%20room%20view.jpg' ); ?>" controls playsinline preload="none" aria-label="Interior walkthrough of The Rising Sun"></video></div>
      <div class="media filled" style="flex: 1 1 300px; min-width: 0; aspect-ratio: 4 / 3; max-width: 100%;"><a href="<?php ucd_url( '/#tour' ); ?>" aria-label="Open the 3D tour"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20hallway%20in%20View.jpg' ); ?>" alt="Hallway looking toward the bedroom" loading="lazy"></a></div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section id="how" style="padding-top: 96px; padding-bottom: 96px;">
  <div class="wrap" style="display: flex; flex-direction: column; gap: 40px;">
    <div style="display: flex; flex-direction: column; gap: 12px; max-width: 620px;">
      <span class="eyebrow">How it works</span>
      <h2 style="font-size: 40px; line-height: 44px; font-weight: 800; color: #14324A;">From your screen to your lot in four steps.</h2>
    </div>
    <ol style="list-style: none; margin: 0; padding: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 16px;">
      <li style="background: #FFFFFF; border: 1px solid #D5DCDE; border-radius: 2px; padding: 24px; display: flex; flex-direction: column; gap: 10px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 36px; color: #B04E25;">1</span><h3 style="font-size: 20px; color: #14324A;">Build it online</h3><p style="font-size: 15px; line-height: 24px; color: #5A666C;">Pick your model, finishes, and upgrades. The price updates as you go.</p></li>
      <li style="background: #FFFFFF; border: 1px solid #D5DCDE; border-radius: 2px; padding: 24px; display: flex; flex-direction: column; gap: 10px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 36px; color: #B04E25;">2</span><h3 style="font-size: 20px; color: #14324A;">Check financing</h3><p style="font-size: 15px; line-height: 24px; color: #5A666C;">Send your info and a financing representative reaches out with options.</p></li>
      <li style="background: #FFFFFF; border: 1px solid #D5DCDE; border-radius: 2px; padding: 24px; display: flex; flex-direction: column; gap: 10px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 36px; color: #B04E25;">3</span><h3 style="font-size: 20px; color: #14324A;">We build it</h3><p style="font-size: 15px; line-height: 24px; color: #5A666C;">[BUILD TIME] weeks in our shop, with photo updates at each stage.</p></li>
      <li style="background: #FFFFFF; border: 1px solid #D5DCDE; border-radius: 2px; padding: 24px; display: flex; flex-direction: column; gap: 10px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 36px; color: #B04E25;">4</span><h3 style="font-size: 20px; color: #14324A;">We deliver it</h3><p style="font-size: 15px; line-height: 24px; color: #5A666C;">Shipped anywhere in the U.S. and set on your site, furnished and ready. First 1,000 miles are on us.</p></li>
    </ol>
  </div>
</section>

<!-- BUILT TO TRAVEL -->
<section style="background: #FFFFFF; padding-top: 96px; padding-bottom: 96px; border-top: 1px solid #D5DCDE; border-bottom: 1px solid #D5DCDE;">
  <div class="wrap" style="display: flex; flex-wrap: wrap-reverse; gap: 48px; align-items: center;">
    <div style="flex: 1 1 420px; min-width: 0; display: flex; flex-direction: column; gap: 20px;">
      <span class="eyebrow">Built to travel</span>
      <h2 style="font-size: 40px; line-height: 44px; font-weight: 800; color: #14324A;">Built for Texas. Ready for anywhere.</h2>
      <p style="font-size: 17px; line-height: 28px;">If a home can handle August in Texas, it's off to a good start. Here's what goes into every build, plus what changes for colder states.</p>
      <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 14px; font-size: 16px; line-height: 24px;">
        <li style="display: flex; gap: 12px;"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2F5D47" stroke-width="2.2" stroke-linecap="square" style="flex: none; margin-top: 1px;"><path d="M4 12l5 5L20 6"></path></svg><span><strong>[INSULATION SPEC]</strong> keeps the steel from turning into an oven in August</span></li>
        <li style="display: flex; gap: 12px;"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2F5D47" stroke-width="2.2" stroke-linecap="square" style="flex: none; margin-top: 1px;"><path d="M4 12l5 5L20 6"></path></svg><span><strong>[HVAC SPEC]</strong> sized for Southeast Texas summers</span></li>
        <li style="display: flex; gap: 12px;"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2F5D47" stroke-width="2.2" stroke-linecap="square" style="flex: none; margin-top: 1px;"><path d="M4 12l5 5L20 6"></path></svg><span><strong>[COATING / RUST SPEC]</strong> for a humid, salty-air climate</span></li>
        <li style="display: flex; gap: 12px;"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2F5D47" stroke-width="2.2" stroke-linecap="square" style="flex: none; margin-top: 1px;"><path d="M4 12l5 5L20 6"></path></svg><span><strong>[TIE-DOWN / WIND SPEC]</strong> for hurricane season</span></li>
        <li style="display: flex; gap: 12px;"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#2F5D47" stroke-width="2.2" stroke-linecap="square" style="flex: none; margin-top: 1px;"><path d="M4 12l5 5L20 6"></path></svg><span><strong>[COLD-CLIMATE PACKAGE]</strong> for snow-country states like Colorado and Wyoming</span></li>
      </ul>
    </div>
    <div class="media filled" style="flex: 1 1 460px; min-width: 0; aspect-ratio: 4 / 5; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Build%20%20plans/DJIvwindow%20cut%20out%20pic%207.JPG' ); ?>" alt="Aerial view of a container mid-build with window openings cut into the steel" loading="lazy"></div>
  </div>
</section>

<!-- FINANCING -->
<section id="financing" style="background: #14324A; color: #FFFFFF; padding-top: 96px; padding-bottom: 96px;">
  <div class="wrap" style="display: flex; flex-wrap: wrap; gap: 48px; align-items: flex-start;">
    <div style="flex: 1 1 400px; min-width: 0; display: flex; flex-direction: column; gap: 20px;">
      <span class="eyebrow" style="color: #B7C8D6;">Financing</span>
      <h2 style="font-size: 40px; line-height: 44px; font-weight: 800;">See your financing options.</h2>
      <p style="font-size: 17px; line-height: 28px; color: #DCE6EE;">Tell us about yourself and the home you want. A financing representative will review it and contact you within [1–2 business days] to talk through what you qualify for.</p>
      <ul style="list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 12px; font-size: 16px; color: #DCE6EE;">
        <li style="display: flex; gap: 12px;"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7FB89A" stroke-width="2.2" stroke-linecap="square" style="flex: none;"><path d="M4 12l5 5L20 6"></path></svg>A real person reviews every request</li>
        <li style="display: flex; gap: 12px;"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7FB89A" stroke-width="2.2" stroke-linecap="square" style="flex: none;"><path d="M4 12l5 5L20 6"></path></svg>This form never asks for your SSN or bank details</li>
        <li style="display: flex; gap: 12px;"><svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#7FB89A" stroke-width="2.2" stroke-linecap="square" style="flex: none;"><path d="M4 12l5 5L20 6"></path></svg>No obligation, just a conversation about your options</li>
      </ul>
    </div>
    <form style="flex: 1 1 420px; min-width: 0; background: #FFFFFF; color: #1B2429; border-radius: 2px; padding: 32px; box-sizing: border-box; display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px;">
      <label class="field" for="fn">First name<input id="fn" type="text" autocomplete="given-name"></label>
      <label class="field" for="ln">Last name<input id="ln" type="text" autocomplete="family-name"></label>
      <label class="field" for="em" style="grid-column: span 2;">Email<input id="em" type="email" autocomplete="email"></label>
      <label class="field" for="ph">Phone<input id="ph" type="tel" autocomplete="tel"></label>
      <label class="field" for="zp">ZIP code<input id="zp" type="text" inputmode="numeric" autocomplete="postal-code"></label>
      <label class="field" for="md" style="grid-column: span 2;">Which home?<select id="md"><option>The Rising Sun</option><option>A custom build</option><option>Not sure yet</option></select></label>
      <button type="button" class="btn primary" style="grid-column: span 2; border: 0; cursor: pointer; font-family: inherit;">Apply for financing</button>
      <p style="grid-column: span 2; font-size: 13px; line-height: 19px; color: #5A666C;">Submitting doesn't commit you to anything. Financing is arranged through [FINANCING AGENT / LENDER].</p>
    </form>
  </div>
</section>

<!-- GALLERY -->
<section id="gallery" style="padding-top: 96px; padding-bottom: 96px;">
  <div class="wrap" style="display: flex; flex-direction: column; gap: 32px;">
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; gap: 16px;">
      <div style="display: flex; flex-direction: column; gap: 12px;">
        <span class="eyebrow">From real builds</span>
        <h2 style="font-size: 40px; line-height: 44px; font-weight: 800; color: #14324A;">No renders. Just the work.</h2>
      </div>
      <a class="btn ghost" href="<?php ucd_url( '/#gallery' ); ?>">View full gallery</a>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px;">
      <div class="media filled" style="aspect-ratio: 4 / 3; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Gallery/DJI_0411.JPG' ); ?>" alt="Finished container home on the trailer, headed to a customer lot" loading="lazy"></div>
      <div class="media filled" style="aspect-ratio: 4 / 3; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Gallery/DJI_0434.JPG' ); ?>" alt="Crane setting a container home on delivery day" loading="lazy"></div>
      <div class="media filled" style="aspect-ratio: 4 / 3; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Gallery/20230601_163705.jpg' ); ?>" alt="Custom walnut dining table set for dinner" loading="lazy"></div>
      <div class="media filled" style="aspect-ratio: 4 / 3; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Gallery/20230601_175058.jpg' ); ?>" alt="Kitchen sink and counter with the hallway beyond" loading="lazy"></div>
      <div class="media filled" style="aspect-ratio: 4 / 3; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Gallery/20230601_165421.jpg' ); ?>" alt="Close-up of the walnut fold-down table and hinges" loading="lazy"></div>
      <div class="media filled" style="aspect-ratio: 4 / 3; max-width: 100%;"><img src="<?php ucd_asset( 'Website/Gallery/20230515_113432.jpg' ); ?>" alt="Built-in bench seat beside the sliding glass door" loading="lazy"></div>
    </div>
  </div>
</section>

<!-- REVIEWS -->
<section style="background: #FFFFFF; padding-top: 96px; padding-bottom: 96px; border-top: 1px solid #D5DCDE; border-bottom: 1px solid #D5DCDE;">
  <div class="wrap" style="display: flex; flex-direction: column; gap: 32px;">
    <div style="display: flex; flex-direction: column; gap: 12px;">
      <span class="eyebrow">Reviews</span>
      <h2 style="font-size: 40px; line-height: 44px; font-weight: 800; color: #14324A;">What owners say.</h2>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px;">
      <figure style="margin: 0; border: 1px solid #D5DCDE; border-radius: 2px; padding: 24px; display: flex; flex-direction: column; gap: 16px;"><blockquote style="margin: 0; font-size: 17px; line-height: 28px;">[REAL GOOGLE REVIEW, verbatim, with permission]</blockquote><figcaption style="font-size: 14px; color: #5A666C;">[First name, last initial] · [City], TX</figcaption></figure>
      <figure style="margin: 0; border: 1px solid #D5DCDE; border-radius: 2px; padding: 24px; display: flex; flex-direction: column; gap: 16px;"><blockquote style="margin: 0; font-size: 17px; line-height: 28px;">[REAL GOOGLE REVIEW, verbatim, with permission]</blockquote><figcaption style="font-size: 14px; color: #5A666C;">[First name, last initial] · [City], TX</figcaption></figure>
      <div class="media dark" style="min-height: 200px;"><span class="mtag">Video testimonial · 30–60s</span><span class="mnote">An owner on camera beats ten written reviews. Shoot it at the next delivery.</span>
      </div>
    </div>
  </div>
</section>

<!-- DIY -->
<section id="diy" style="background: #FFFFFF; padding-top: 96px; padding-bottom: 96px; border-bottom: 1px solid #D5DCDE;">
  <div class="wrap" style="display: flex; flex-wrap: wrap; gap: 48px; align-items: center;">
    <div class="media filled" style="flex: 1 1 420px; min-width: 0; aspect-ratio: 3 / 2; max-width: 100%;"><img src="<?php ucd_asset( 'img/gallery/build-interior-framing-1600.jpg' ); ?>" srcset="<?php ucd_asset( 'img/gallery/build-interior-framing-800.jpg' ); ?> 800w, <?php ucd_asset( 'img/gallery/build-interior-framing-1600.jpg' ); ?> 1600w" sizes="(min-width: 1100px) 520px, 100vw" alt="A builder assembling a wood cabinet frame on the workbench in our shop" loading="lazy"></div>
    <div style="flex: 1 1 420px; min-width: 0; display: flex; flex-direction: column; gap: 20px;">
      <span class="eyebrow">Building your own?</span>
      <h2 style="font-size: 40px; line-height: 44px; font-weight: 800; color: #14324A;">Get help from people who've done it.</h2>
      <p style="font-size: 17px; line-height: 28px;">Book a free discovery call to talk through your idea, or a one-hour paid consult when you're stuck mid-build. Our building plans and a step-by-step DIY course are coming soon.</p>
      <div style="display: flex; flex-wrap: wrap; gap: 12px;">
        <a class="btn ghost" href="<?php ucd_url( '/plans/#talk' ); ?>">Book a free discovery call</a>
        <a class="btn ghost" href="<?php ucd_url( '/plans/#plans-list' ); ?>">Get notified when plans launch</a>
      </div>
      <p style="font-size: 14px; line-height: 20px; color: #5A666C;">Plans and course: join the list and you'll hear first. [Later: Buy plans · Enroll in the course]</p>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="faq" style="padding-top: 96px; padding-bottom: 96px;">
  <div class="wrap" style="display: flex; flex-wrap: wrap; gap: 48px;">
    <div style="flex: 1 1 300px; min-width: 0; display: flex; flex-direction: column; gap: 12px;">
      <span class="eyebrow">FAQ</span>
      <h2 style="font-size: 40px; line-height: 44px; font-weight: 800; color: #14324A;">Straight answers.</h2>
    </div>
    <div style="flex: 2 1 560px; min-width: 0; display: flex; flex-direction: column; border-top: 1px solid #D5DCDE;">
      <details open="" style="border-bottom: 1px solid #D5DCDE; padding: 20px 0;"><summary style="font-weight: 700; font-size: 18px; cursor: pointer; color: #14324A;">What does it actually cost?</summary><p style="margin-top: 12px; font-size: 16px; line-height: 26px;">The Rising Sun starts at $85,000 and runs up to $120,000 with every upgrade. That includes the custom furniture and the first 1,000 miles of delivery. Site prep, foundation, and utility hookups are [INCLUDED / QUOTED SEPARATELY].</p></details>
      <details style="border-bottom: 1px solid #D5DCDE; padding: 20px 0;"><summary style="font-weight: 700; font-size: 18px; cursor: pointer; color: #14324A;">Do you deliver to my state?</summary><p style="margin-top: 12px; font-size: 16px; line-height: 26px;">Yes. We ship to all 50 states, and to Canada, the U.S. Virgin Islands, and other international destinations case by case. The first 1,000 miles are included, and longer trips are quoted up front [CONFIRM]. Zoning rules vary by city and county, and we'll help you check yours.</p></details>
      <details style="border-bottom: 1px solid #D5DCDE; padding: 20px 0;"><summary style="font-weight: 700; font-size: 18px; cursor: pointer; color: #14324A;">How long does a build take?</summary><p style="margin-top: 12px; font-size: 16px; line-height: 26px;">[BUILD TIME]</p></details>
      <details style="border-bottom: 1px solid #D5DCDE; padding: 20px 0;"><summary style="font-weight: 700; font-size: 18px; cursor: pointer; color: #14324A;">Can I finance it?</summary><p style="margin-top: 12px; font-size: 16px; line-height: 26px;">Yes. Apply online and a financing representative will contact you to go over your options.</p></details>
      <details style="border-bottom: 1px solid #D5DCDE; padding: 20px 0;"><summary style="font-weight: 700; font-size: 18px; cursor: pointer; color: #14324A;">What's the warranty?</summary><p style="margin-top: 12px; font-size: 16px; line-height: 26px;">[WARRANTY TERMS]</p></details>
    </div>
  </div>
</section>

<!-- SERVICE AREAS -->
<section id="areas" style="background: #FFFFFF; padding-top: 72px; padding-bottom: 72px; border-top: 1px solid #D5DCDE;">
  <div class="wrap" style="display: flex; flex-direction: column; gap: 20px;">
    <span class="eyebrow">Where we deliver</span>
    <h2 style="font-size: 32px; line-height: 38px; font-weight: 800; color: #14324A;">Built in Texas. Delivered nationwide.</h2>
    <p style="font-size: 17px; line-height: 28px; color: #5A666C; max-width: 620px;">Most of our homes head to the West Coast, the Mountain West, and the Northeast. Canada, the U.S. Virgin Islands, and other international destinations are quoted case by case.</p>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
      <a class="chip" href="<?php ucd_url( '/#areas' ); ?>" style="background: #DCE3E5; color: #14324A; text-decoration: none; min-height: 44px; box-sizing: border-box;">California</a>
      <a class="chip" href="<?php ucd_url( '/#areas' ); ?>" style="background: #DCE3E5; color: #14324A; text-decoration: none; min-height: 44px; box-sizing: border-box;">Colorado</a>
      <a class="chip" href="<?php ucd_url( '/#areas' ); ?>" style="background: #DCE3E5; color: #14324A; text-decoration: none; min-height: 44px; box-sizing: border-box;">Utah</a>
      <a class="chip" href="<?php ucd_url( '/#areas' ); ?>" style="background: #DCE3E5; color: #14324A; text-decoration: none; min-height: 44px; box-sizing: border-box;">Wyoming</a>
      <a class="chip" href="<?php ucd_url( '/#areas' ); ?>" style="background: #DCE3E5; color: #14324A; text-decoration: none; min-height: 44px; box-sizing: border-box;">New York</a>
      <a class="chip" href="<?php ucd_url( '/container-homes/texas/' ); ?>" style="background: #DCE3E5; color: #14324A; text-decoration: none; min-height: 44px; box-sizing: border-box;">Texas</a>
      <a class="chip" href="<?php ucd_url( '/service-areas/' ); ?>" style="background: #DCE3E5; color: #14324A; text-decoration: none; min-height: 44px; box-sizing: border-box;">All 50 states</a>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section id="build" style="position: relative; background: #14324A; color: #FFFFFF;">
  <div class="media dark filled" style="position: absolute; inset: 0; border-radius: 0;"><img src="<?php ucd_asset( 'Website/Main%20Site%20professional%20pics/Copy%20of%20Back%20angle_.jpg' ); ?>" alt="" loading="lazy"></div>
  <div class="wrap" style="position: relative; padding-top: 112px; padding-bottom: 112px;">
    <div style="background: #14324A; max-width: 560px; padding: 40px; box-sizing: border-box; display: flex; flex-direction: column; gap: 20px; border-radius: 2px;">
      <h2 style="font-size: 44px; line-height: 46px; font-weight: 800;">Price your home in about five minutes.</h2>
      <p style="font-size: 17px; line-height: 28px; color: #DCE6EE;">Pick a model, choose your upgrades, and see the total. No sales call required to get a number.</p>
      <div style="display: flex; flex-wrap: wrap; gap: 12px;"><a class="btn primary" href="<?php ucd_url( '/#build' ); ?>">Build Your Home</a><a class="btn ghost light" href="#contact">Talk to us</a></div>
    </div>
  </div>
</section>

</main>

<?php
get_footer();
