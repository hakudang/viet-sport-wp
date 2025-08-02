<?php
/**
 * Helpers
 *
 * @package NCSUCP
 */

/**
 * Render head.
 *
 * @since 1.0.0
 */
function nifty_cs_head() {
	do_action( 'nifty_cs_head' );
}

/**
 * Render footer.
 *
 * @since 1.0.0
 */
function nifty_cs_footer() {
	do_action( 'nifty_cs_footer' );
}

/**
 * Whether given page block is active.
 *
 * @since 1.0.0
 *
 * @param string $key Block slug.
 * @return bool Whether the block is active or not.
 */
function nifty_cs_is_page_block_active( $key ) {
	$output = false;

	$page_blocks = (array) nifty_cs_get_option( 'page_blocks' );

	if ( in_array( $key, $page_blocks, true ) ) {
		$output = true;
	}

	return $output;
}

/**
 * Display the class names for the body element.
 *
 * @since 1.0.0
 *
 * @param string|string[] $css_class Space-separated string or array of class names to add to the class list.
 */
function nifty_cs_body_class( $css_class = '' ) {
	echo 'class="' . esc_attr( implode( ' ', nifty_cs_get_body_class( $css_class ) ) ) . '"';
}

/**
 * Retrieve an array of the class names for the body element.
 *
 * @since 1.0.0
 *
 * @param string|string[] $css_class Space-separated string or array of class names to add to the class list.
 * @return string[] Array of class names.
 */
function nifty_cs_get_body_class( $css_class = '' ) {
	$classes = array();

	$classes[] = 'nifty-cs';

	if ( ! empty( $css_class ) ) {
		if ( ! is_array( $css_class ) ) {
			$css_class = preg_split( '#\s+#', $css_class );
		}
		$classes = array_merge( $classes, $css_class );
	} else {
		$css_class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	$classes = apply_filters( 'nifty_cs_body_class', $classes, $css_class );

	return array_unique( $classes );
}

/**
 * Render localized variable.
 *
 * @since 1.0.0
 *
 * @param string $object_name Object name.
 * @param array  $data Data to be localized.
 */
function nifty_localize( $object_name, $data ) {
	if ( empty( $data ) || ! is_array( $data ) ) {
		return;
	}

	foreach ( (array) $data as $key => $value ) {
		if ( ! is_scalar( $value ) ) {
				continue;
		}

		$data[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
	}

	echo '<script>';
	echo "var $object_name = " . wp_json_encode( $data ) . ';'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '</script>';
}

/**
 * Render preloader.
 *
 * @since 1.0.0
 */
function nifty_cs_render_preloader() {
	$preloader = nifty_cs_get_option( 'enable_preloader' );

	if ( 'off' !== $preloader ) {
		echo '<div id="preloader"></div>';
	}
}

/**
 * Return Customizer URL.
 *
 * @since 1.0.0
 *
 * @return string Customizer URL.
 */
function nifty_cs_get_customizer_url() {
	$url = add_query_arg(
		array(
			'autofocus[panel]' => 'nifty_cs_panel',
		),
		admin_url( 'customize.php' )
	);

	return $url;
}

/**
 * Generate styles.
 *
 * @since 1.0.0
 *
 * @param string $option_key Option key to fetch value from.
 * @param array  $args CSS rules arguments.
 * @return string CSS rules string.
 */
function nifty_cs_generate_style( $option_key, $args ) {
	$value = nifty_cs_get_option( $option_key );

	if ( ! $value ) {
		return;
	}

	$defaults = nifty_cs_get_default_options();

	if ( isset( $defaults[ $option_key ] ) ) {
		if ( $defaults[ $option_key ] === $value ) {
			return;
		}
	}

	return nifty_cs_generate_css( $value, $args );
}

/**
 * Return list of page blocks.
 *
 * @since 1.0.0
 *
 * @return array Array of page blocks.
 */
function nifty_cs_all_page_blocks() {
	$output = array(
		'logo'          => array(
			'label'    => esc_html__( 'Logo', 'nifty-coming-soon-and-under-construction-page' ),
			'template' => 'template-parts/sections/logo.php',
		),
		'animated-text' => array(
			'label'    => esc_html__( 'Animated Text', 'nifty-coming-soon-and-under-construction-page' ),
			'template' => 'template-parts/sections/animated-text.php',
		),
		'countdown'     => array(
			'label'    => esc_html__( 'Countdown', 'nifty-coming-soon-and-under-construction-page' ),
			'template' => 'template-parts/sections/countdown.php',
		),
		'slider'        => array(
			'label'    => esc_html__( 'Slider', 'nifty-coming-soon-and-under-construction-page' ),
			'template' => 'template-parts/sections/slider.php',
		),
		'contact'       => array(
			'label'    => esc_html__( 'Contact', 'nifty-coming-soon-and-under-construction-page' ),
			'template' => 'template-parts/sections/contact.php',
			'icon'     => 'icon-map-marker',
		),
		'social'        => array(
			'label'    => esc_html__( 'Social', 'nifty-coming-soon-and-under-construction-page' ),
			'template' => 'template-parts/sections/social.php',
			'icon'     => 'icon-thumbs-up',
		),
		'subscription'  => array(
			'label'    => esc_html__( 'Subscription', 'nifty-coming-soon-and-under-construction-page' ),
			'template' => 'template-parts/sections/subscription.php',
			'icon'     => 'icon-paper-plane',
		),
	);

	return $output;
}

/**
 * Import options from old plugin.
 *
 * @since 1.0.0
 */
function nifty_cs_migrate_plugin_options() {
	$is_migrated = get_option( 'nifty_cs_migrated' );

	if ( 'on' === $is_migrated ) {
		return;
	}

	$old_options = (array) get_option( 'option_tree' );

	if ( empty( $old_options ) ) {
		return;
	}

	$current_options = (array) get_option( 'nifty_cs_option' );

	// Merge old options.
	$current_options = array_merge( $current_options, $old_options );

	// Fix slider URL.
	$slider_keys = array( 'upload_slider_images', 'upload_slider_images_2', 'upload_slider_images_3', 'upload_slider_images_4' );

	foreach ( $slider_keys as $key ) {
		if ( isset( $current_options[ $key ] ) && ! empty( $current_options[ $key ] ) ) {
			$current_options[ $key ] = str_replace( 'admin/assets/slideshow', 'assets/images/slideshow', $current_options[ $key ] );
		}
	}

	// Fix logo URL.
	if ( ! empty( $current_options['upload_your_logo'] ) ) {
		$current_options['upload_your_logo'] = str_replace( 'admin//assets/images/logo.png', 'assets/images/logo.png', $current_options['upload_your_logo'] );
	}

	// Fix countdown switch.
	$countdown_status = isset( $current_options['display_count_down_timer'] ) ? $current_options['display_count_down_timer'] : 'on';

	if ( 'off' === $countdown_status ) {
		$page_blocks = nifty_cs_get_option( 'page_blocks' );

		if ( in_array( 'countdown', $page_blocks, true ) ) {
			$new_page_blocks                = array_diff( (array) $page_blocks, array( 'countdown' ) );
			$current_options['page_blocks'] = array_values( $new_page_blocks );
		}
	}

	// Fix contact and social switch.
	$contact_status = isset( $current_options['enable_contact_details'] ) ? $current_options['enable_contact_details'] : 'on';
	$social_status  = isset( $current_options['enable_social_links'] ) ? $current_options['enable_social_links'] : 'on';

	$slider_blocks = nifty_cs_get_option( 'slider_blocks' );

	$exclude_blocks = array();

	if ( 'off' === $contact_status && in_array( 'contact', $slider_blocks, true ) ) {
		$exclude_blocks[] = 'contact';
	}

	if ( 'off' === $social_status && in_array( 'social', $slider_blocks, true ) ) {
		$exclude_blocks[] = 'social';
	}

	if ( ! empty( $exclude_blocks ) ) {
		$new_slider_blocks                = array_diff( (array) $slider_blocks, $exclude_blocks );
		$current_options['slider_blocks'] = array_values( $new_slider_blocks );
	}

	// Save new options.
	update_option( 'nifty_cs_option', $current_options );
	update_option( 'nifty_cs_migrated', 'on' );
}
