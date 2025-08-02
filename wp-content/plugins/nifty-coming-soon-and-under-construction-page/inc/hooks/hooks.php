<?php
/**
 * Hooks
 *
 * @package NCSUCP
 */

// Hook function is defined in helpers.php file.
add_action( 'wp_loaded', 'nifty_cs_migrate_plugin_options' );

/**
 * Load plugin textdomain.
 *
 * @since 2.0.2
 */
function nifty_cs_load_textdomain() {
	load_plugin_textdomain( 'nifty-coming-soon-and-under-construction-page' );
}

add_action( 'plugins_loaded', 'nifty_cs_load_textdomain' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function nifty_cs_body_classes( $classes ) {
	$bg_slider_status = nifty_cs_get_option( 'disable_background_image_slider' );

	if ( 'on' === $bg_slider_status ) {
		$classes[] = 'background-slider-enabled';
	}

	$intro_animation = nifty_cs_get_option( 'disable_animation' );

	if ( 'on' === $intro_animation ) {
		$classes[] = 'intro-animation-enabled';
	}

	$countdown_status = nifty_cs_is_page_block_active( 'countdown' );
	$coundown_time    = nifty_cs_get_option( 'setup_the_count_down_timer' );

	if ( true === $countdown_status && ! empty( $coundown_time ) ) {
		$classes[] = 'countdown-timer-enabled';
	}

	return $classes;
}

add_filter( 'nifty_cs_body_class', 'nifty_cs_body_classes' );

/**
 * Render header meta.
 *
 * @since 3.0.0
 */
function nifty_cs_add_header_meta() {
	$page_title = nifty_cs_get_option( 'page_title' );

	if ( ! empty( $page_title ) ) {
		echo '<title>' . esc_html( $page_title ) . '</title>';
	}

	$page_description = nifty_cs_get_option( 'page_description' );

	if ( ! empty( $page_description ) ) {
		echo '<meta name="description" content="' . esc_html( $page_description ) . '">';
	}
}

add_action( 'nifty_cs_head', 'nifty_cs_add_header_meta', 2 );

/**
 * Add OpenGraph tags.
 *
 * @since 3.0.0
 */
function nifty_cs_add_og_tags() {
	$opengraph_thumbnail = nifty_cs_get_option( 'opengraph_thumbnail' );

	// Bail if no image.
	if ( empty( $opengraph_thumbnail ) ) {
		return;
	}

	$attachment_id = attachment_url_to_postid( $opengraph_thumbnail );

	// Bail if no valid attachment ID.
	if ( ! $attachment_id ) {
		return;
	}

	$metadata = wp_get_attachment_metadata( $attachment_id );

	$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

	$tags = array(
		'image'            => $opengraph_thumbnail,
		'image:secure_url' => $opengraph_thumbnail,
		'og:image:width'   => $metadata['width'],
		'og:image:height'  => $metadata['height'],
		'og:image:alt'     => $alt,
		'og:image:type'    => $metadata['sizes']['thumbnail']['mime-type'],
	);

	foreach ( $tags as $key => $val ) {
		$attrs = array(
			'property' => $key,
			'content'  => $val,
		);

		echo '<meta' . nifty_cs_render_attr( $attrs, false ) . '/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'nifty_cs_head', 'nifty_cs_add_og_tags', 3 );

// Add favicon if uploaded.
add_action( 'nifty_cs_head', 'wp_site_icon', 5 );

/**
 * Load fonts.
 *
 * @since 3.0.0
 */
function nifty_cs_load_fonts() {
	$countdown_status  = nifty_cs_is_page_block_active( 'countdown' );
	$site_title_status = ( 'on' !== nifty_cs_get_option( 'disable_logo' ) ) && ( 'on' === nifty_cs_get_option( 'display_site_title' ) );

	$sitetitle_font = nifty_cs_get_option( 'choose_sitetitle_font' );
	$heading_font   = nifty_cs_get_option( 'choose_heading_font' );
	$paragraph_font = nifty_cs_get_option( 'choose_paragraph_font' );
	$counter_font   = nifty_cs_get_option( 'choose_counter_font' );

	$all_fonts = array( $heading_font, $paragraph_font );

	if ( true === $countdown_status ) {
		$all_fonts[] = $counter_font;
	}

	if ( true === $site_title_status ) {
		$all_fonts[] = $sitetitle_font;
	}

	$font_families = array_unique( $all_fonts );

	if ( ! empty( $font_families ) ) {
		$fonts_url = add_query_arg(
			array(
				'family'  => implode( '&family=', $font_families ),
				'display' => 'swap',
			),
			'https://fonts.googleapis.com/css2'
		);

		echo "<link href='" . esc_url_raw( wptt_get_webfont_url( esc_url_raw( $fonts_url ) ) ) . "' rel='stylesheet' type='text/css'>"; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
	}
}

add_action( 'nifty_cs_head', 'nifty_cs_load_fonts', 5 );

/**
 * Localize data required in JS.
 *
 * @since 3.0.0
 */
function nifty_cs_add_localized() {
	$countdown_time = nifty_cs_get_option( 'setup_the_count_down_timer' );

	$countdown_time_formatted = date( 'Y/m/d H:i', strtotime( $countdown_time ) ); // phpcs:ignore WordPress.DateTime.RestrictedFunctions.date_date

	$slider_images = array(
		nifty_cs_get_option( 'upload_slider_images' ),
		nifty_cs_get_option( 'upload_slider_images_2' ),
		nifty_cs_get_option( 'upload_slider_images_3' ),
		nifty_cs_get_option( 'upload_slider_images_4' ),
	);

	$slider_images = array_values( array_filter( $slider_images ) );

	$data = array(
		'ajax_url'                          => admin_url( 'admin-ajax.php' ),
		'background_slides'                 => $slider_images,
		'background_slider_time'            => nifty_cs_get_option( 'background_slider_time' ),
		'background_slider_animation'       => nifty_cs_get_option( 'background_slider_animation' ),
		'background_slider_animation_time'  => nifty_cs_get_option( 'background_slider_animation_time' ),
		'background_slider_pattern'         => nifty_cs_get_option( 'select_pattern_overlay' ),
		'background_slider_pattern_opacity' => nifty_cs_get_option( 'pattern_overlay_opacity' ),
		'pattern_folder_url'                => NCSUCP_URL . '/assets/images/patterns/',
		'countdown_time_formatted'          => $countdown_time_formatted,
		'slider_blocks'                     => nifty_cs_get_option( 'slider_blocks' ),
		'blocks'                            => nifty_cs_all_page_blocks(),
		'subscription_success_message'      => nifty_cs_get_option( 'email_confirmation___success' ),
		'subscription_error_message'        => nifty_cs_get_option( 'email_confirmation___error' ),
	);

	nifty_localize( 'niftyCsObject', $data );
}

add_action( 'nifty_cs_head', 'nifty_cs_add_localized', 5 );

/**
 * Load assets.
 *
 * @since 3.0.0
 */
function nifty_cs_load_assets() {
	// phpcs:disable
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	?>
	<link rel="stylesheet" href="<?php echo esc_url( NCSUCP_URL . '/third-party/icomoon/icomoon.css' ); ?>">
	<link rel="stylesheet" href="<?php echo esc_url( NCSUCP_URL . '/assets/css/frontend' . $min . '.css' ); ?>">

	<script src="<?php echo esc_url( includes_url( 'js/jquery/jquery.js' ) ); ?>"></script>
	<script src="<?php echo esc_url( includes_url( 'js/jquery/jquery-migrate.js' ) ); ?>"></script>
	<?php
	// phpcs:enable
}

add_action( 'nifty_cs_head', 'nifty_cs_load_assets', 10 );

/**
 * Add custom styles.
 *
 * @since 3.0.0
 */
function nifty_cs_load_custom_styles() {
	$custom_css = '';

	$custom_css .= nifty_cs_generate_style(
		'choose_paragraph_font',
		array(
			array(
				'selector' => 'body',
				'property' => 'font-family',
			),
		)
	);

	$custom_css .= nifty_cs_generate_style(
		'choose_sitetitle_font',
		array(
			array(
				'selector' => '.nifty-title',
				'property' => 'font-family',
			),
		)
	);

	$custom_css .= nifty_cs_generate_style(
		'choose_heading_font',
		array(
			array(
				'selector' => '.nifty-coming-soon-message',
				'property' => 'font-family',
			),
		)
	);

	$custom_css .= nifty_cs_generate_style(
		'choose_counter_font',
		array(
			array(
				'selector' => '.timer-item',
				'property' => 'font-family',
			),
		)
	);

	if ( 'on' !== nifty_cs_get_option( 'disable_background_image_slider' ) ) {
		$custom_css .= nifty_cs_generate_style(
			'background_color',
			array(
				array(
					'selector' => 'body',
					'property' => 'background',
				),
			)
		);
	}

	$custom_css .= nifty_cs_generate_style(
		'countdown_font_color',
		array(
			array(
				'selector' => '#days, #hours, #minutes, #seconds',
				'property' => 'color',
			),
		)
	);

	$custom_css .= nifty_cs_generate_style(
		'countdown_font_color_bottom',
		array(
			array(
				'selector' => '.timer-bottom',
				'property' => 'color',
			),
		)
	);

	$custom_css .= nifty_cs_generate_style(
		'pattern_overlay_opacity',
		array(
			array(
				'selector' => '.vegas-overlay',
				'property' => 'opacity',
			),
		)
	);

	$custom_css .= nifty_cs_generate_style(
		'sign_up_button_color',
		array(
			array(
				'selector' => '.nifty-subscription .button',
				'property' => 'background',
			),
		)
	);

	$custom_css .= nifty_cs_generate_style(
		'sign_up_button_color_hover',
		array(
			array(
				'selector' => '.nifty-subscription .button:hover',
				'property' => 'background',
			),
		)
	);

	if ( ! empty( $custom_css ) ) {
		echo '<style>' . $custom_css . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'nifty_cs_head', 'nifty_cs_load_custom_styles', 10 );

/**
 * Load scripts.
 *
 * @since 3.0.0
 */
function nifty_cs_load_plugin_scripts() {
	// phpcs:disable
	?>
	<script src="<?php echo esc_url( NCSUCP_URL . '/third-party/countdown/jquery.countdown.js' ); ?>"></script>
	<script src="<?php echo esc_url( NCSUCP_URL . '/third-party/swiper/swiper-bundle.js' ); ?>"></script>
	<script src="<?php echo esc_url( NCSUCP_URL . '/third-party/vegas/jquery.vegas.js' ); ?>"></script>
	<script src="<?php echo esc_url( NCSUCP_URL . '/third-party/lettering/jquery.lettering.js' ); ?>"></script>
	<script src="<?php echo esc_url( NCSUCP_URL . '/third-party/textillate/jquery.textillate.js' ); ?>"></script>
	<script src="<?php echo esc_url( NCSUCP_URL . '/assets/js/frontend.js' ); ?>"></script>
	<?php
	// phpcs:enable
}

add_action( 'nifty_cs_footer', 'nifty_cs_load_plugin_scripts', 10 );

/**
 * Load additional CSS.
 *
 * @since 3.0.0
 */
function nifty_cs_load_additional_css() {
	$additional_css_code = nifty_cs_get_option( 'insert_additional_css' );

	echo '<style>' . $additional_css_code . '</style>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_action( 'nifty_cs_footer', 'nifty_cs_load_additional_css', 10 );

/**
 * Render analytics code.
 *
 * @since 3.0.0
 */
function nifty_cs_add_google_analytics() {
	echo nifty_cs_get_option( 'insert_google_analytics_code' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

add_action( 'nifty_cs_footer', 'nifty_cs_add_google_analytics', 10 );

/**
 * Customize assets.
 *
 * @since 3.0.0
 */
function nifty_cs_customize_default_assets() {
	if ( ! is_customize_preview() ) {
		return;
	}

	$is_custom_preview = false;

	if ( isset( $_REQUEST['get_preview'] ) && 'true' === $_REQUEST['get_preview'] ) {
		$is_custom_preview = true;
	}

	if ( false === $is_custom_preview ) {
		return;
	}

	$styles = wp_styles();

	$queue = $styles->queue;

	$allowed = array( 'customize-preview', 'global-styles', 'wp-mediaelement' );

	if ( ! empty( $queue ) && is_array( $queue ) ) {
		foreach ( $queue as $item ) {
			if ( in_array( $item, $allowed, true ) ) {
				continue;
			}

			wp_dequeue_style( $item );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'nifty_cs_customize_default_assets', 9999 );

