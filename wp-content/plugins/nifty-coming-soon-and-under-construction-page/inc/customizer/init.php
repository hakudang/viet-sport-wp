<?php
/**
 * Plugin Customizer
 *
 * @package NCSUCP
 */

/**
 * Customizer setup.
 *
 * @since 1.0.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function nift_cs_customize_register( $wp_customize ) {
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Color::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\DateTime::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Dimension::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\DropdownGoogleFonts::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Email::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Heading::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Image::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Message::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\RadioImage::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Range::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Select::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Sortable::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Text::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\Toggle::class );
	$wp_customize->register_control_type( Nilambar\CustomizerUtils\Control\URL::class );

	$wp_customize->register_section_type( Nilambar\CustomizerUtils\Section\Header::class );

	require_once NCSUCP_DIR . '/inc/customizer/options.php';

	require_once NCSUCP_DIR . '/inc/customizer/partials.php';
}

add_action( 'customize_register', 'nift_cs_customize_register' );

/**
 * Register customizer controls scripts.
 *
 * @since 1.0.0
 */
function nifty_cs_customize_controls_register_scripts() {
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'nifty-cs-customize-controls', NCSUCP_URL . '/assets/js/customize-controls' . $min . '.js', array( 'jquery', 'customize-controls' ), NCSUCP_VERSION, true );

	$localize = array(
		'page' => home_url( '/?get_preview=true' ),
	);

	wp_localize_script( 'nifty-cs-customize-controls', 'niftyCsCustomizer', $localize );
}

add_action( 'customize_controls_enqueue_scripts', 'nifty_cs_customize_controls_register_scripts', 0 );

/**
 * Register customizer preview scripts.
 *
 * @since 1.0.0
 */
function nifty_cs_customize_preview_scripts() {
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'nifty-cs-customize-preview', NCSUCP_URL . '/assets/js/customize-preview' . $min . '.js', array( 'jquery', 'customize-preview' ), NCSUCP_VERSION, true );

	$localize = array(
		'page'                              => home_url( '/?get_preview=true' ),
		'background_slider_pattern'         => nifty_cs_get_option( 'select_pattern_overlay' ),
		'pattern_folder_url'                => NCSUCP_URL . '/assets/images/patterns/',
		'background_slider_pattern_opacity' => nifty_cs_get_option( 'pattern_overlay_opacity' ),
	);

	wp_localize_script( 'nifty-cs-customize-preview', 'niftyCsPreview', $localize );
}

add_action( 'customize_preview_init', 'nifty_cs_customize_preview_scripts' );
