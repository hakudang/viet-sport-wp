<?php
/**
 * Options helpers
 *
 * @package NCSUCP
 */

/**
 * Return animation options.
 *
 * @since 1.0.0
 *
 * @return array Options array.
 */
function nifty_cs_get_animation_options() {
	$choices = array(
		'random'      => esc_html__( 'Random', 'nifty-coming-soon-and-under-construction-page' ),
		'fade'        => esc_html__( 'Fade', 'nifty-coming-soon-and-under-construction-page' ),
		'fade2'       => esc_html__( 'Fade 2', 'nifty-coming-soon-and-under-construction-page' ),
		'slideLeft'   => esc_html__( 'Slide Left', 'nifty-coming-soon-and-under-construction-page' ),
		'slideLeft2'  => esc_html__( 'Slide Left 2', 'nifty-coming-soon-and-under-construction-page' ),
		'slideRight'  => esc_html__( 'Slide Right', 'nifty-coming-soon-and-under-construction-page' ),
		'slideRight2' => esc_html__( 'Slide Right 2', 'nifty-coming-soon-and-under-construction-page' ),
		'slideUp'     => esc_html__( 'Slide Up', 'nifty-coming-soon-and-under-construction-page' ),
		'slideUp2'    => esc_html__( 'Slide Up 2', 'nifty-coming-soon-and-under-construction-page' ),
		'slideDown'   => esc_html__( 'Slide Down', 'nifty-coming-soon-and-under-construction-page' ),
		'slideDown2'  => esc_html__( 'Slide Down 2', 'nifty-coming-soon-and-under-construction-page' ),
		'zoomIn'      => esc_html__( 'Zoom In', 'nifty-coming-soon-and-under-construction-page' ),
		'zoomIn2'     => esc_html__( 'Zoom In 2', 'nifty-coming-soon-and-under-construction-page' ),
		'zoomOut'     => esc_html__( 'Zoom Out', 'nifty-coming-soon-and-under-construction-page' ),
		'zoomOut2'    => esc_html__( 'Zoom Out 2', 'nifty-coming-soon-and-under-construction-page' ),
		'swirlLeft'   => esc_html__( 'Swirl Left', 'nifty-coming-soon-and-under-construction-page' ),
		'swirlLeft2'  => esc_html__( 'Swirl Left 2', 'nifty-coming-soon-and-under-construction-page' ),
		'swirlRight'  => esc_html__( 'Swirl Right', 'nifty-coming-soon-and-under-construction-page' ),
		'swirlRight2' => esc_html__( 'Swirl Right 2', 'nifty-coming-soon-and-under-construction-page' ),
		'swirlUp'     => esc_html__( 'Swirl Up', 'nifty-coming-soon-and-under-construction-page' ),
		'swirlUp2'    => esc_html__( 'Swirl Up 2', 'nifty-coming-soon-and-under-construction-page' ),
		'swirlDown'   => esc_html__( 'Swirl Down', 'nifty-coming-soon-and-under-construction-page' ),
		'swirlDown2'  => esc_html__( 'Swirl Down 2', 'nifty-coming-soon-and-under-construction-page' ),
		'burn'        => esc_html__( 'Burn', 'nifty-coming-soon-and-under-construction-page' ),
		'burn2'       => esc_html__( 'Burn 2', 'nifty-coming-soon-and-under-construction-page' ),
		'blur'        => esc_html__( 'Blur', 'nifty-coming-soon-and-under-construction-page' ),
		'blur2'       => esc_html__( 'Blur 2', 'nifty-coming-soon-and-under-construction-page' ),
		'flash'       => esc_html__( 'Flash', 'nifty-coming-soon-and-under-construction-page' ),
		'flash2'      => esc_html__( 'Flash 2', 'nifty-coming-soon-and-under-construction-page' ),
	);

	return $choices;
}

/**
 * Return pattern options.
 *
 * @since 1.0.0
 *
 * @return array Options array.
 */
function nifty_cs_get_pattern_options() {
	$choices = array();

	$list = array( '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17' );

	foreach ( $list as $id ) {
		$choices[ "{$id}.png" ] = NCSUCP_URL . "/assets/images/patterns-preview/{$id}.png";
	}

	return $choices;
}

/**
 * Return page blocks options.
 *
 * @since 1.0.0
 *
 * @return array Options array.
 */
function nifty_cs_page_blocks_options() {
	$choices = array();

	$all_blocks = nifty_cs_all_page_blocks();

	if ( empty( $all_blocks ) ) {
		return $choices;
	}

	foreach ( $all_blocks as $key => $block ) {
		$choices[ $key ] = $block['label'];
	}

	return $choices;
}

/**
 * Return slider blocks options.
 *
 * @since 1.0.0
 *
 * @return array Options array.
 */
function nifty_cs_slider_blocks_options() {
	$choices = array();

	$all_blocks = nifty_cs_all_page_blocks();

	if ( empty( $all_blocks ) ) {
		return $choices;
	}

	$allowed = array( 'subscription', 'contact', 'social' );

	foreach ( $all_blocks as $key => $block ) {
		if ( in_array( $key, $allowed, true ) ) {
			$choices[ $key ] = $block['label'];
		}
	}

	return $choices;
}

/**
 * Return plugin features.
 *
 * @since 1.0.0
 *
 * @return array Features array.
 */
function nifty_cs_get_plugin_features() {
	$output = array(
		array(
			'label' => 'Preloader',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'SEO (Page Title, Meta Description, OpenGraph Thumbnail)',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Google Analytics',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Additional CSS',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Blocks (Sections) Reordering',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => '300+ Google Fonts',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Background Mode ( Slider, Solid Color)',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Logo Block',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Countdown Block',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Animated Text Block',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Built-in Subscription Form',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Social Links',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => 'Live Preview',
			'free'  => true,
			'pro'   => true,
		),
		array(
			'label' => '10+ Premade themes',
			'pro'   => true,
		),
		array(
			'label' => 'Content Options ( Width, Alignment)',
			'pro'   => true,
		),
		array(
			'label' => 'Color Options',
			'pro'   => true,
		),
		array(
			'label' => 'Secret Access (Sharable link with secret key)',
			'pro'   => true,
		),
		array(
			'label' => 'Additional Background Mode (Static, Video, Gradient)',
			'pro'   => true,
		),
		array(
			'label' => 'Background Overlay',
			'pro'   => true,
		),
		array(
			'label' => 'GDPR checkbox in Subscription',
			'pro'   => true,
		),
		array(
			'label' => 'Heading Block',
			'pro'   => true,
		),
		array(
			'label' => 'Content Block',
			'pro'   => true,
		),
		array(
			'label' => 'Two Columns Content Block',
			'pro'   => true,
		),
		array(
			'label' => 'Map Block',
			'pro'   => true,
		),
		array(
			'label' => 'Progress Bar Block',
			'pro'   => true,
		),
		array(
			'label' => 'Video Block',
			'pro'   => true,
		),
		array(
			'label' => 'Divider Block',
			'pro'   => true,
		),
		array(
			'label' => 'Reset Plugin Options',
			'pro'   => true,
		),
		array(
			'label' => 'Import / Export Plugin Options',
			'pro'   => true,
		),
		array(
			'label' => 'Premium Support',
			'pro'   => true,
		),
	);

	return $output;
}

/**
 * Return comparison items for welcome page.
 *
 * @since 1.0.0
 *
 * @return array Comparison items.
 */
function nifty_cs_get_comparison_items() {
	$output = array();

	$features = nifty_cs_get_plugin_features();

	if ( empty( $features ) ) {
		return $output;
	}

	foreach ( $features as $feature ) {
		$item = array();

		if ( ! empty( $feature['label'] ) ) {
			$item['title'] = $feature['label'];

			$free_status  = ( isset( $feature['free'] ) ) ? (bool) $feature['free'] : false;
			$item['free'] = ( $free_status ) ? 'yes' : 'no';

			$pro_status  = ( isset( $feature['pro'] ) ) ? (bool) $feature['pro'] : false;
			$item['pro'] = ( $pro_status ) ? 'yes' : 'no';
		}

		$output[] = $item;
	}

	return $output;
}
