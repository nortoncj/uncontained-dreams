<?php
/**
 * Build Options: the models, option groups, choices, upgrades and delivery
 * settings behind the Build Your Home configurator, edited under Build Options
 * in wp-admin and stored in the ucd_build_options option.
 *
 * Until the screen is first saved, values come from the theme's
 * data/build-options.json and data/models.json. Model photos start empty:
 * choose each one from the Media Library on the Build Options screen.
 *
 * Prices: a number adds that many dollars, 0 shows "Included", and an empty
 * price shows "Quoted" and stays out of the total.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// The configurator's two option steps, in order.
function ucd_build_steps() {
	return array(
		'exterior' => 'Exterior options',
		'interior' => 'Interior options',
	);
}

function ucd_build_options() {
	$options = get_option( 'ucd_build_options' );
	return is_array( $options ) ? $options : ucd_build_default_options();
}

// Today's values from the static site's data files.
function ucd_build_default_options() {
	$read     = function ( $file ) {
		$json = json_decode( (string) file_get_contents( get_template_directory() . '/data/' . $file ), true );
		return is_array( $json ) ? $json : array();
	};
	$options  = $read( 'build-options.json' );
	$models   = $read( 'models.json' );
	// Static paths were relative to the site root; make them work from WordPress.
	$site_url = function ( $path ) {
		return ( $path && ! preg_match( '#^(https?:|\[)#', $path ) ) ? '/' . ltrim( $path, '/' ) : (string) $path;
	};

	$defaults = array(
		'shop'          => isset( $options['shop'] ) ? $options['shop'] : array( 'label' => 'our Texas shop', 'lat' => 0, 'lng' => 0 ),
		'includedMiles' => isset( $options['includedMiles'] ) ? (int) $options['includedMiles'] : 1000,
		'roadFactor'    => isset( $options['roadFactor'] ) ? (float) $options['roadFactor'] : 1.2,
		'models'        => array(),
		'steps'         => isset( $options['steps'] ) ? $options['steps'] : array(),
		'upgrades'      => isset( $options['upgrades'] ) ? $options['upgrades'] : array(),
	);
	foreach ( isset( $models['models'] ) ? $models['models'] : array() as $model ) {
		$defaults['models'][] = array(
			'id'        => $model['id'],
			'name'      => $model['name'],
			'basePrice' => (int) $model['basePrice'],
			'sqft'      => $model['sqft'],
			'beds'      => $model['beds'],
			'baths'     => $model['baths'],
			'line'      => $model['line'],
			'image'     => '',
			'tourUrl'   => $site_url( $model['tourUrl'] ),
			'photosUrl' => $site_url( $model['photosUrl'] ),
		);
	}
	return ucd_build_sanitize_options( $defaults );
}

function ucd_build_model_ids( $options = null ) {
	$options = $options ? $options : ucd_build_options();
	return wp_list_pluck( $options['models'], 'id' );
}

/*
 * Saving
 */

function ucd_build_register_setting() {
	register_setting(
		'ucd_build',
		'ucd_build_options',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'ucd_build_sanitize_options',
		)
	);
}
add_action( 'admin_init', 'ucd_build_register_setting' );

// Price field: empty means quoted (null); otherwise whole dollars.
function ucd_build_sanitize_price( $value ) {
	if ( null === $value || '' === trim( (string) $value ) ) {
		return null;
	}
	return max( 0, (int) round( (float) $value ) );
}

// Site-relative paths ("/gallery/") stay relative; anything else must be a URL.
function ucd_build_sanitize_link( $value ) {
	$value = trim( (string) $value );
	if ( '' === $value || 0 === strpos( $value, '/' ) || 0 === strpos( $value, '[' ) ) {
		return sanitize_text_field( $value );
	}
	return esc_url_raw( $value );
}

// A unique id: the one given (or one made from the label), with -2, -3... added if it's taken.
function ucd_build_unique_id( $id, $label, &$taken ) {
	$base = sanitize_title( '' !== trim( (string) $id ) ? $id : $label );
	$base = '' === $base ? 'item' : $base;
	$id   = $base;
	for ( $n = 2; in_array( $id, $taken, true ); $n++ ) {
		$id = $base . '-' . $n;
	}
	$taken[] = $id;
	return $id;
}

/**
 * Cleans what the Build Options form posts (rows keyed by row key) into the
 * stored shape (plain lists). Also accepts the stored shape, since WordPress
 * can run this twice on the first save. options.php has already unslashed the
 * input.
 */
function ucd_build_sanitize_options( $input ) {
	$input = is_array( $input ) ? $input : array();
	$text  = function ( $row, $key ) {
		return isset( $row[ $key ] ) ? sanitize_text_field( $row[ $key ] ) : '';
	};
	$list  = function ( $row, $key, $allowed ) {
		$values = isset( $row[ $key ] ) && is_array( $row[ $key ] ) ? array_map( 'sanitize_title', $row[ $key ] ) : array();
		return array_values( array_intersect( $values, $allowed ) );
	};
	$shop  = isset( $input['shop'] ) && is_array( $input['shop'] ) ? $input['shop'] : array();

	$out = array(
		'shop'          => array(
			'label' => $text( $shop, 'label' ),
			'lat'   => isset( $shop['lat'] ) ? (float) $shop['lat'] : 0,
			'lng'   => isset( $shop['lng'] ) ? (float) $shop['lng'] : 0,
		),
		'includedMiles' => isset( $input['includedMiles'] ) ? absint( $input['includedMiles'] ) : 0,
		'roadFactor'    => isset( $input['roadFactor'] ) ? max( 1, (float) $input['roadFactor'] ) : 1.2,
		'models'        => array(),
		'steps'         => array(),
		'upgrades'      => array(),
	);

	// Models first: choices and upgrades refer to their ids.
	$taken = array();
	foreach ( isset( $input['models'] ) ? (array) $input['models'] : array() as $row ) {
		$name = $text( $row, 'name' );
		if ( '' === $name ) {
			continue;
		}
		$out['models'][] = array(
			'id'        => ucd_build_unique_id( $text( $row, 'id' ), $name, $taken ),
			'name'      => $name,
			'basePrice' => isset( $row['basePrice'] ) ? absint( $row['basePrice'] ) : 0,
			'sqft'      => $text( $row, 'sqft' ),
			'beds'      => $text( $row, 'beds' ),
			'baths'     => $text( $row, 'baths' ),
			'line'      => isset( $row['line'] ) ? sanitize_textarea_field( $row['line'] ) : '',
			'image'     => isset( $row['image'] ) ? esc_url_raw( $row['image'] ) : '',
			'tourUrl'   => ucd_build_sanitize_link( isset( $row['tourUrl'] ) ? $row['tourUrl'] : '' ),
			'photosUrl' => ucd_build_sanitize_link( isset( $row['photosUrl'] ) ? $row['photosUrl'] : '' ),
		);
	}
	$model_ids = wp_list_pluck( $out['models'], 'id' );

	// Group ids share one namespace across steps, since a saved build keys choices by group id.
	$group_ids = array();
	foreach ( array_keys( ucd_build_steps() ) as $step ) {
		$out['steps'][ $step ] = array();
		$groups                = isset( $input['steps'][ $step ] ) ? (array) $input['steps'][ $step ] : array();
		foreach ( $groups as $group ) {
			$label = $text( $group, 'label' );
			if ( '' === $label ) {
				continue;
			}
			$choices    = array();
			$choice_ids = array();
			$default    = isset( $group['default'] ) ? (string) $group['default'] : '';
			$default_id = '';
			foreach ( isset( $group['choices'] ) ? (array) $group['choices'] : array() as $key => $choice ) {
				$choice_label = $text( $choice, 'label' );
				if ( '' === $choice_label ) {
					continue;
				}
				$id = ucd_build_unique_id( $text( $choice, 'id' ), $choice_label, $choice_ids );
				// The form posts the default as the choice's row key; the stored shape uses the id.
				if ( (string) $key === $default || $id === $default ) {
					$default_id = $id;
				}
				$choices[] = array(
					'id'            => $id,
					'label'         => $choice_label,
					'price'         => ucd_build_sanitize_price( isset( $choice['price'] ) ? $choice['price'] : null ),
					'excludeModels' => $list( $choice, 'excludeModels', $model_ids ),
				);
			}
			$clean = array(
				'id'       => ucd_build_unique_id( $text( $group, 'id' ), $label, $group_ids ),
				'label'    => $label,
				'help'     => $text( $group, 'help' ),
				'required' => ! empty( $group['required'] ),
				'choices'  => $choices,
			);
			if ( $default_id ) {
				$clean['default'] = $default_id;
			}
			if ( '' !== $text( $group, 'errorName' ) ) {
				$clean['errorName'] = $text( $group, 'errorName' );
			}
			$out['steps'][ $step ][] = $clean;
		}
	}

	// Two passes over upgrades: "removes" can point at upgrades listed after it.
	$upgrade_ids = array();
	$upgrades    = array();
	foreach ( isset( $input['upgrades'] ) ? (array) $input['upgrades'] : array() as $row ) {
		$label = $text( $row, 'label' );
		if ( '' === $label ) {
			continue;
		}
		$upgrades[] = array( ucd_build_unique_id( $text( $row, 'id' ), $label, $upgrade_ids ), $label, $row );
	}
	foreach ( $upgrades as $upgrade ) {
		list( $id, $label, $row ) = $upgrade;
		$clean                    = array(
			'id'            => $id,
			'label'         => $label,
			'line'          => $text( $row, 'line' ),
			'price'         => ucd_build_sanitize_price( isset( $row['price'] ) ? $row['price'] : null ),
			'excludeModels' => $list( $row, 'excludeModels', $model_ids ),
			'conflicts'     => $list( $row, 'conflicts', array_diff( $upgrade_ids, array( $id ) ) ),
		);
		if ( '' !== $text( $row, 'replaces' ) ) {
			$clean['replaces'] = $text( $row, 'replaces' );
		}
		$out['upgrades'][] = $clean;
	}

	return $out;
}

/*
 * Screen
 */

function ucd_build_admin_menu() {
	add_menu_page( 'Build Options', 'Build Options', 'manage_options', 'ucd-build-options', 'ucd_build_render_admin', 'dashicons-hammer', 27 );
}
add_action( 'admin_menu', 'ucd_build_admin_menu' );

function ucd_build_admin_assets( $hook ) {
	if ( 'toplevel_page_ucd-build-options' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'ucd-build-options', ucd_get_asset( 'css/build-options-admin.css' ), array(), UCD_VERSION );
	wp_enqueue_script( 'ucd-build-options', ucd_get_asset( 'js/build-options-admin.js' ), array(), UCD_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'ucd_build_admin_assets' );

function ucd_build_name( $path ) {
	return 'ucd_build_options[' . implode( '][', $path ) . ']';
}

// Row controls shared by every repeatable item.
function ucd_build_row_actions( $what ) {
	printf(
		'<span class="ucd-row__actions"><button type="button" class="button-link" data-move="-1">Move up</button> <button type="button" class="button-link" data-move="1">Move down</button> <button type="button" class="button-link button-link-delete" data-remove>Remove %s</button></span>',
		esc_html( $what )
	);
}

// An id field: editable on a new row, locked once saved because shared build links use it.
function ucd_build_id_field( $path, $value, $help ) {
	$locked = '' !== $value;
	printf(
		'<label class="ucd-field ucd-field--id"><span>ID</span><input type="text" name="%1$s" value="%2$s" class="code" data-id-field%3$s><small>%4$s</small></label>',
		esc_attr( ucd_build_name( $path ) ),
		esc_attr( $value ),
		$locked ? ' readonly' : '',
		esc_html( $locked ? 'Locked: saved builds use this ID.' : $help )
	);
}

function ucd_build_text_field( $path, $label, $value, $attrs = '' ) {
	printf(
		'<label class="ucd-field"><span>%1$s</span><input type="text" name="%2$s" value="%3$s"%4$s></label>',
		esc_html( $label ),
		esc_attr( ucd_build_name( $path ) ),
		esc_attr( $value ),
		$attrs // phpcs:ignore WordPress.Security.EscapeOutput -- fixed attribute strings from this file.
	);
}

function ucd_build_price_field( $path, $value ) {
	printf(
		'<label class="ucd-field ucd-field--price"><span>Price ($)</span><input type="number" min="0" step="1" name="%1$s" value="%2$s" placeholder="Quoted"></label>',
		esc_attr( ucd_build_name( $path ) ),
		esc_attr( null === $value ? '' : $value )
	);
}

// Checkboxes for a list of ids, like the models a choice isn't available on.
function ucd_build_checkboxes( $path, $label, $options, $checked, $empty ) {
	echo '<fieldset class="ucd-field ucd-field--checks"><legend>' . esc_html( $label ) . '</legend>';
	if ( ! $options ) {
		echo '<small>' . esc_html( $empty ) . '</small>';
	}
	foreach ( $options as $id => $name ) {
		printf(
			'<label><input type="checkbox" name="%1$s[]" value="%2$s"%3$s> %4$s</label>',
			esc_attr( ucd_build_name( $path ) ),
			esc_attr( $id ),
			checked( in_array( $id, (array) $checked, true ), true, false ),
			esc_html( $name )
		);
	}
	echo '</fieldset>';
}

function ucd_build_model_row( $key, $model ) {
	$model = wp_parse_args( $model, array_fill_keys( array( 'id', 'name', 'basePrice', 'sqft', 'beds', 'baths', 'line', 'image', 'tourUrl', 'photosUrl' ), '' ) );
	$path  = array( 'models', $key );
	?>
	<details class="ucd-row" data-row<?php echo '' === $model['id'] ? ' open' : ''; ?>>
		<summary><span data-row-title><?php echo esc_html( $model['name'] ? $model['name'] : 'New model' ); ?></span></summary>
		<div class="ucd-row__body">
			<div class="ucd-grid">
				<?php
				ucd_build_text_field( array_merge( $path, array( 'name' ) ), 'Name', $model['name'], ' data-title-field' );
				ucd_build_id_field( array_merge( $path, array( 'id' ) ), $model['id'], 'Leave empty to make one from the name.' );
				printf(
					'<label class="ucd-field ucd-field--price"><span>Base price ($)</span><input type="number" min="0" step="1" name="%1$s" value="%2$s"></label>',
					esc_attr( ucd_build_name( array_merge( $path, array( 'basePrice' ) ) ) ),
					esc_attr( $model['basePrice'] )
				);
				ucd_build_text_field( array_merge( $path, array( 'sqft' ) ), 'Size', $model['sqft'] );
				ucd_build_text_field( array_merge( $path, array( 'beds' ) ), 'Beds', $model['beds'] );
				ucd_build_text_field( array_merge( $path, array( 'baths' ) ), 'Baths', $model['baths'] );
				ucd_build_text_field( array_merge( $path, array( 'tourUrl' ) ), '3D tour link', $model['tourUrl'], ' placeholder="/gallery/#tour"' );
				ucd_build_text_field( array_merge( $path, array( 'photosUrl' ) ), 'Photos link', $model['photosUrl'], ' placeholder="/gallery/"' );
				?>
			</div>
			<label class="ucd-field"><span>One-line description</span><textarea name="<?php echo esc_attr( ucd_build_name( array_merge( $path, array( 'line' ) ) ) ); ?>" rows="2"><?php echo esc_textarea( $model['line'] ); ?></textarea></label>
			<div class="ucd-field ucd-image">
				<span>Card photo</span>
				<img src="<?php echo esc_url( $model['image'] ); ?>" alt="" data-image-preview<?php echo $model['image'] ? '' : ' hidden'; ?>>
				<input type="url" name="<?php echo esc_attr( ucd_build_name( array_merge( $path, array( 'image' ) ) ) ); ?>" value="<?php echo esc_attr( $model['image'] ); ?>" data-image-url>
				<button type="button" class="button" data-choose-image>Choose photo</button>
			</div>
			<?php ucd_build_row_actions( 'model' ); ?>
		</div>
	</details>
	<?php
}

function ucd_build_choice_row( $step, $gkey, $ckey, $choice, $default, $models ) {
	$choice = wp_parse_args(
		$choice,
		array(
			'id'            => '',
			'label'         => '',
			'price'         => null,
			'excludeModels' => array(),
		)
	);
	$path   = array( 'steps', $step, $gkey, 'choices', $ckey );
	?>
	<div class="ucd-choice" data-row>
		<label class="ucd-field ucd-field--default"><input type="radio" name="<?php echo esc_attr( ucd_build_name( array( 'steps', $step, $gkey, 'default' ) ) ); ?>" value="<?php echo esc_attr( $ckey ); ?>"<?php checked( '' !== $choice['id'] && $choice['id'] === $default ); ?>> Starts selected</label>
		<?php
		ucd_build_text_field( array_merge( $path, array( 'label' ) ), 'Choice', $choice['label'], ' data-title-field' );
		ucd_build_id_field( array_merge( $path, array( 'id' ) ), $choice['id'], 'Made from the choice name if empty.' );
		ucd_build_price_field( array_merge( $path, array( 'price' ) ), $choice['price'] );
		ucd_build_checkboxes( array_merge( $path, array( 'excludeModels' ) ), 'Not available on', $models, $choice['excludeModels'], 'Save a model first.' );
		ucd_build_row_actions( 'choice' );
		?>
	</div>
	<?php
}

function ucd_build_group_row( $step, $gkey, $group, $models ) {
	$group = wp_parse_args(
		$group,
		array(
			'id'        => '',
			'label'     => '',
			'help'      => '',
			'required'  => false,
			'default'   => '',
			'errorName' => '',
			'choices'   => array(),
		)
	);
	$path  = array( 'steps', $step, $gkey );
	?>
	<details class="ucd-row" data-row<?php echo '' === $group['id'] ? ' open' : ''; ?>>
		<summary><span data-row-title><?php echo esc_html( $group['label'] ? $group['label'] : 'New option group' ); ?></span><?php echo $group['required'] ? ' <em>Required</em>' : ''; ?></summary>
		<div class="ucd-row__body">
			<div class="ucd-grid">
				<?php
				ucd_build_text_field( array_merge( $path, array( 'label' ) ), 'Group name', $group['label'], ' data-title-field' );
				ucd_build_id_field( array_merge( $path, array( 'id' ) ), $group['id'], 'Leave empty to make one from the name.' );
				ucd_build_text_field( array_merge( $path, array( 'help' ) ), 'Help text', $group['help'] );
				ucd_build_text_field( array_merge( $path, array( 'errorName' ) ), 'Error wording', $group['errorName'], ' placeholder="Choose a ___ to keep going."' );
				?>
				<label class="ucd-field ucd-field--check"><input type="checkbox" name="<?php echo esc_attr( ucd_build_name( array_merge( $path, array( 'required' ) ) ) ); ?>" value="1"<?php checked( $group['required'] ); ?>> Buyer must pick one</label>
			</div>
			<div class="ucd-choices" data-repeater="choice" data-step="<?php echo esc_attr( $step ); ?>" data-group="<?php echo esc_attr( $gkey ); ?>">
				<?php
				foreach ( $group['choices'] as $ckey => $choice ) {
					ucd_build_choice_row( $step, $gkey, $ckey, $choice, $group['default'], $models );
				}
				?>
			</div>
			<p><button type="button" class="button" data-add="choice">Add choice</button> <label class="ucd-clear-default"><input type="radio" name="<?php echo esc_attr( ucd_build_name( array_merge( $path, array( 'default' ) ) ) ); ?>" value=""<?php checked( '' === $group['default'] ); ?>> Nothing starts selected</label></p>
			<?php ucd_build_row_actions( 'group' ); ?>
		</div>
	</details>
	<?php
}

function ucd_build_upgrade_row( $key, $upgrade, $models, $upgrades ) {
	$upgrade = wp_parse_args(
		$upgrade,
		array(
			'id'            => '',
			'label'         => '',
			'line'          => '',
			'price'         => null,
			'replaces'      => '',
			'conflicts'     => array(),
			'excludeModels' => array(),
		)
	);
	$path    = array( 'upgrades', $key );
	unset( $upgrades[ $upgrade['id'] ] );
	?>
	<details class="ucd-row" data-row<?php echo '' === $upgrade['id'] ? ' open' : ''; ?>>
		<summary><span data-row-title><?php echo esc_html( $upgrade['label'] ? $upgrade['label'] : 'New upgrade' ); ?></span></summary>
		<div class="ucd-row__body">
			<div class="ucd-grid">
				<?php
				ucd_build_text_field( array_merge( $path, array( 'label' ) ), 'Upgrade', $upgrade['label'], ' data-title-field' );
				ucd_build_id_field( array_merge( $path, array( 'id' ) ), $upgrade['id'], 'Leave empty to make one from the name.' );
				ucd_build_price_field( array_merge( $path, array( 'price' ) ), $upgrade['price'] );
				ucd_build_text_field( array_merge( $path, array( 'line' ) ), 'One-line description', $upgrade['line'] );
				ucd_build_text_field( array_merge( $path, array( 'replaces' ) ), 'Replaces (shown when added)', $upgrade['replaces'], ' placeholder="e.g. Standard electrical"' );
				?>
			</div>
			<div class="ucd-grid">
				<?php
				ucd_build_checkboxes( array_merge( $path, array( 'conflicts' ) ), 'Removes these when added', $upgrades, $upgrade['conflicts'], 'Save another upgrade first.' );
				ucd_build_checkboxes( array_merge( $path, array( 'excludeModels' ) ), 'Not available on', $models, $upgrade['excludeModels'], 'Save a model first.' );
				?>
			</div>
			<?php ucd_build_row_actions( 'upgrade' ); ?>
		</div>
	</details>
	<?php
}

function ucd_build_render_admin() {
	$options  = ucd_build_options();
	$models   = wp_list_pluck( $options['models'], 'name', 'id' );
	$upgrades = wp_list_pluck( $options['upgrades'], 'label', 'id' );
	?>
	<div class="wrap ucd-build-admin">
		<h1>Build Options</h1>
		<p class="ucd-intro">Models, options and prices for the Build Your Home page. Only put a price on something the written quote will honor: a number adds that many dollars, <strong>0</strong> shows "Included", and an empty price shows "Quoted" and stays out of the total.</p>
		<?php if ( false === get_option( 'ucd_build_options' ) ) : ?>
		<div class="notice notice-info inline"><p>These are the values from the static site. Nothing is stored in WordPress until you save.</p></div>
		<?php endif; ?>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php settings_fields( 'ucd_build' ); ?>

			<h2>Delivery</h2>
			<div class="ucd-grid ucd-panel">
				<?php
				ucd_build_text_field( array( 'shop', 'label' ), 'Shop, as it reads in "miles from ___"', $options['shop']['label'] );
				ucd_build_text_field( array( 'shop', 'lat' ), 'Shop latitude', $options['shop']['lat'], ' inputmode="decimal"' );
				ucd_build_text_field( array( 'shop', 'lng' ), 'Shop longitude', $options['shop']['lng'], ' inputmode="decimal"' );
				ucd_build_text_field( array( 'includedMiles' ), 'Delivery miles included', $options['includedMiles'], ' inputmode="numeric"' );
				ucd_build_text_field( array( 'roadFactor' ), 'Road factor (straight-line miles × this)', $options['roadFactor'], ' inputmode="decimal"' );
				?>
			</div>

			<h2>Models</h2>
			<div class="ucd-rows" data-repeater="model">
				<?php
				foreach ( $options['models'] as $key => $model ) {
					ucd_build_model_row( $key, $model );
				}
				?>
			</div>
			<p><button type="button" class="button" data-add="model">Add model</button></p>

			<?php foreach ( ucd_build_steps() as $step => $title ) : ?>
			<h2><?php echo esc_html( $title ); ?></h2>
			<div class="ucd-rows" data-repeater="group" data-step="<?php echo esc_attr( $step ); ?>">
				<?php
				foreach ( isset( $options['steps'][ $step ] ) ? $options['steps'][ $step ] : array() as $gkey => $group ) {
					ucd_build_group_row( $step, $gkey, $group, $models );
				}
				?>
			</div>
			<p><button type="button" class="button" data-add="group" data-step="<?php echo esc_attr( $step ); ?>">Add option group</button></p>
			<?php endforeach; ?>

			<h2>Upgrades</h2>
			<div class="ucd-rows" data-repeater="upgrade">
				<?php
				foreach ( $options['upgrades'] as $key => $upgrade ) {
					ucd_build_upgrade_row( $key, $upgrade, $models, $upgrades );
				}
				?>
			</div>
			<p><button type="button" class="button" data-add="upgrade">Add upgrade</button></p>

			<?php submit_button( 'Save build options' ); ?>
		</form>

		<?php // Blank rows for the Add buttons. __step__, __group__ and __key__ are filled in by build-options-admin.js. ?>
		<template id="ucd-tpl-model"><?php ucd_build_model_row( '__key__', array() ); ?></template>
		<template id="ucd-tpl-group"><?php ucd_build_group_row( '__step__', '__group__', array(), $models ); ?></template>
		<template id="ucd-tpl-choice"><?php ucd_build_choice_row( '__step__', '__group__', '__key__', array(), '', $models ); ?></template>
		<template id="ucd-tpl-upgrade"><?php ucd_build_upgrade_row( '__key__', array(), $models, $upgrades ); ?></template>
	</div>
	<?php
}
