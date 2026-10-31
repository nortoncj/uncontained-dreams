<?php
/**
 * Site footer. Closes the page wrapper opened in header.php.
 */
?>
<!-- FOOTER -->
<footer id="contact" style="background: #0F1B24; color: #B7C8D6; padding-top: 56px; padding-bottom: 40px;">
  <div class="wrap" style="display: flex; flex-direction: column; gap: 32px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 32px; font-size: 15px; line-height: 26px;">
      <div style="display: flex; flex-direction: column; gap: 8px;"><span style="font-family: Archivo, sans-serif; font-weight: 800; font-size: 20px; color: #FFFFFF;">
        <img src="<?php ucd_asset( 'brand/logo-dark.png' ); ?>" alt="UnContained Dreams logo" style="width: 240px; height: auto; margin-top: 8px;">
      </span><span>Texas-built container homes, delivered anywhere.</span></div>
      <div style="display: flex; flex-direction: column; gap: 4px;"><span style="color: #FFFFFF; font-weight: 600;">Contact</span><span>[PHONE]</span><span>[EMAIL]</span><span>[STREET ADDRESS], Sugar Land, TX [ZIP]</span></div>
      <div style="display: flex; flex-direction: column; gap: 4px;"><span style="color: #FFFFFF; font-weight: 600;">Explore</span><a href="<?php ucd_url( '/#models' ); ?>" style="color: #B7C8D6;">Models</a><a href="<?php ucd_url( '/#build' ); ?>" style="color: #B7C8D6;">Build Your Home</a><a href="<?php ucd_url( '/#financing' ); ?>" style="color: #B7C8D6;">Financing</a><a href="<?php ucd_url( '/plans/' ); ?>" style="color: #B7C8D6;">DIY Plans &amp; Consulting</a><a href="<?php ucd_url( '/#gallery' ); ?>" style="color: #B7C8D6;">Gallery</a></div>
      <div style="display: flex; flex-direction: column; gap: 4px;"><span style="color: #FFFFFF; font-weight: 600;">Trust</span><span>[LICENSE / REGISTRATION #]</span><span>[INSURANCE: general liability]</span><a href="<?php ucd_url( '/#faq' ); ?>" style="color: #B7C8D6;">Warranty terms</a></div>
    </div>
    <p style="font-size: 13px; color: #8FA3B3; border-top: 1px solid #2A3A44; padding-top: 20px;">© <?php echo esc_html( wp_date( 'Y' ) ); ?> UnContained Dreams. Financing arranged through [FINANCING AGENT / LENDER]; UnContained Dreams does not make credit decisions. <a href="<?php ucd_url( '/privacy-policy/' ); ?>" style="color: #B7C8D6;">Privacy</a> · <a href="<?php ucd_url( '/terms/' ); ?>" style="color: #B7C8D6;">Terms</a></p>
  </div>
</footer>

</div>

<?php wp_footer(); ?>
</body>
</html>
