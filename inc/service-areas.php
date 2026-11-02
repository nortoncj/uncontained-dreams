<?php
/**
 * Service Areas: the ZIP delivery check ([ucd_zip_check]) and the state list
 * ([ucd_state_cards]).
 *
 * Both read assets/data/routes.json, the same file the static site uses. A
 * state links to its page only when routes.json marks it live and the page is
 * published in WordPress, so a held state never shows up as a dead link; the
 * rest get a "Get a delivery quote" button to the contact form.
 *
 * The ZIP check uses the same lookup and distance math as the builder's
 * delivery step (build-config.js and Build Options).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ucd_service_area_states() {
	static $states = null;
	if ( null === $states ) {
		$json   = json_decode( (string) file_get_contents( get_theme_file_path( 'assets/data/routes.json' ) ), true );
		$states = array();
		foreach ( isset( $json['states'] ) ? $json['states'] : array() as $state ) {
			$page          = get_page_by_path( trim( $state['url'], '/' ) );
			$state['live'] = ! empty( $state['live'] ) && $page && 'publish' === $page->post_status;
			$state['link'] = home_url( '/' . ltrim( $state['url'], '/' ) );
			$states[]      = $state;
		}
	}
	return $states;
}

function ucd_zip_check_shortcode() {
	ucd_build_enqueue( 'service-areas' );
	$live = array();
	foreach ( ucd_service_area_states() as $state ) {
		if ( $state['live'] ) {
			$live[ $state['abbr'] ] = array(
				'name' => $state['name'],
				'url'  => $state['link'],
			);
		}
	}
	ob_start();
	?>
<div class="ucd-zip-check">
	<form class="ucd-zip" id="zip-form" novalidate data-states="<?php echo esc_attr( wp_json_encode( (object) $live ) ); ?>">
		<label class="field" for="zip">Enter your ZIP to estimate delivery
			<input id="zip" name="zip" type="text" inputmode="numeric" autocomplete="postal-code" maxlength="5" aria-describedby="zip-err">
		</label>
		<button class="btn primary" type="submit">Check delivery</button>
		<span class="ucd-zip__err" id="zip-err" hidden></span>
	</form>
	<div class="ucd-zip-result" id="zip-result" aria-live="polite" hidden></div>
	<noscript><p>Call <a href="tel:[PHONE]">[PHONE]</a> or <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">send us your address</a> for a delivery quote.</p></noscript>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_zip_check', 'ucd_zip_check_shortcode' );

function ucd_state_cards_shortcode() {
	$regions = array();
	foreach ( ucd_service_area_states() as $state ) {
		$regions[ $state['region'] ][] = $state;
	}
	ob_start();
	?>
<div class="ucd-regions">
	<?php foreach ( $regions as $region => $states ) : ?>
	<div class="ucd-region">
		<h3><?php echo esc_html( $region ); ?></h3>
		<div class="ucd-state-cards">
			<?php foreach ( $states as $state ) : ?>
			<article class="ucd-state-card<?php echo $state['live'] ? ' is-live' : ''; ?>">
				<h4><?php echo esc_html( $state['name'] ); ?></h4>
				<p><?php echo esc_html( $state['homesDelivered'] ); ?> homes delivered · About <?php echo esc_html( $state['transitDays'] ); ?> days from our shop</p>
				<?php if ( $state['live'] ) : ?>
				<a class="ucd-state-card__go" href="<?php echo esc_url( $state['link'] ); ?>">See <?php echo esc_html( $state['name'] ); ?> details</a>
				<?php else : ?>
				<a class="btn ghost" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Get a delivery quote</a>
				<?php endif; ?>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'ucd_state_cards', 'ucd_state_cards_shortcode' );
