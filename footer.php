<?php
/**
 * Site footer. Closes the page wrapper opened in header.php.
 *
 * Contact and trust details come from Appearance > Customize > Business
 * details; a line stays hidden until its detail is filled in. The logo is the
 * Footer logo under Site Identity.
 */
$ucd_name = ucd_detail( 'name' );
?>
<!-- FOOTER -->
<footer id="contact" style="background: #0F1B24; color: #B7C8D6; padding-top: 56px; padding-bottom: 40px;">
  <div class="wrap" style="display: flex; flex-direction: column; gap: 32px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 32px; font-size: 15px; line-height: 26px;">
      <div style="display: flex; flex-direction: column; gap: 8px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 20px; color: #FFFFFF;">
        <?php if ( get_theme_mod( 'ucd_footer_logo' ) ) : ?>
          <?php echo wp_get_attachment_image( get_theme_mod( 'ucd_footer_logo' ), 'medium', false, array( 'alt' => $ucd_name . ' logo', 'style' => 'width: 240px; height: auto; margin-top: 8px;' ) ); ?>
        <?php else : ?>
          <?php echo esc_html( $ucd_name ); ?>
        <?php endif; ?>
      </span><span><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : 'Texas-built container homes, delivered anywhere.' ); ?></span></div>
      <div style="display: flex; flex-direction: column; gap: 4px;"><span style="color: #FFFFFF; font-weight: 600;">Contact</span>
        <?php if ( ucd_detail( 'phone' ) ) : ?><a href="<?php echo esc_url( ucd_phone_href(), array( 'tel' ) ); ?>" style="color: #B7C8D6;"><?php echo esc_html( ucd_detail( 'phone' ) ); ?></a><?php endif; ?>
        <?php if ( ucd_detail( 'email' ) ) : ?><a href="<?php echo esc_url( 'mailto:' . ucd_detail( 'email' ), array( 'mailto' ) ); ?>" style="color: #B7C8D6;"><?php echo esc_html( ucd_detail( 'email' ) ); ?></a><?php endif; ?>
        <?php if ( ucd_address() ) : ?><span><?php echo esc_html( ucd_address() ); ?></span><?php endif; ?>
        <?php if ( ucd_detail( 'hours' ) ) : ?><span><?php echo esc_html( ucd_detail( 'hours' ) ); ?></span><?php endif; ?>
        <a href="<?php ucd_url( '/contact/' ); ?>" style="color: #B7C8D6;">Send us a message</a>
      </div>
      <div style="display: flex; flex-direction: column; gap: 4px;"><span style="color: #FFFFFF; font-weight: 600;">Explore</span><a href="<?php ucd_url( '/container-homes/' ); ?>" style="color: #B7C8D6;">Container Homes</a><a href="<?php ucd_url( '/build/' ); ?>" style="color: #B7C8D6;">Build Your Home</a><a href="<?php ucd_url( '/how-it-works/' ); ?>" style="color: #B7C8D6;">How It Works</a><a href="<?php ucd_url( '/financing/' ); ?>" style="color: #B7C8D6;">Financing</a><a href="<?php ucd_url( '/plans/' ); ?>" style="color: #B7C8D6;">DIY Plans &amp; Consulting</a><a href="<?php ucd_url( '/gallery/' ); ?>" style="color: #B7C8D6;">Gallery</a><a href="<?php ucd_url( '/guides/' ); ?>" style="color: #B7C8D6;">Guides</a></div>
      <div style="display: flex; flex-direction: column; gap: 4px;"><span style="color: #FFFFFF; font-weight: 600;">Company</span><a href="<?php ucd_url( '/about/' ); ?>" style="color: #B7C8D6;">About</a><a href="<?php ucd_url( '/faq/' ); ?>" style="color: #B7C8D6;">FAQ</a><a href="<?php ucd_url( '/contact/' ); ?>" style="color: #B7C8D6;">Contact</a></div>
      <div style="display: flex; flex-direction: column; gap: 4px;"><span style="color: #FFFFFF; font-weight: 600;">Trust</span><?php if ( ucd_detail( 'license' ) ) : ?><span><?php echo esc_html( ucd_detail( 'license' ) ); ?></span><?php endif; ?><?php if ( ucd_detail( 'insurance' ) ) : ?><span><?php echo esc_html( ucd_detail( 'insurance' ) ); ?></span><?php endif; ?><a href="<?php ucd_url( '/how-it-works/#warranty' ); ?>" style="color: #B7C8D6;">Warranty terms</a></div>
    </div>
    <p style="font-size: 13px; color: #8FA3B3; border-top: 1px solid #2A3A44; padding-top: 20px;">© <?php echo esc_html( wp_date( 'Y' ) . ' ' . $ucd_name ); ?>. <?php echo esc_html( ( ucd_detail( 'financing_agent' ) ? 'Financing arranged through ' . ucd_detail( 'financing_agent' ) . '; ' : '' ) . $ucd_name . ' does not make credit decisions.' ); ?> <a href="<?php ucd_url( '/privacy-policy/' ); ?>" style="color: #B7C8D6;">Privacy</a> · <a href="<?php ucd_url( '/terms/' ); ?>" style="color: #B7C8D6;">Terms</a></p>
  </div>
</footer>

</div>

<?php wp_footer(); ?>
</body>
</html>
