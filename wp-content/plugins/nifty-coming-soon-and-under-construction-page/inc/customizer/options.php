<?php
/**
 * Options
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Section\Header;

$default = nifty_cs_get_default_options();

// Add Panel.
$wp_customize->add_panel(
	'nifty_cs_panel',
	array(
		'title'      => esc_html__( 'Nifty Options', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 1,
		'capability' => 'manage_options',
	)
);

// Load options.
require_once NCSUCP_DIR . '/inc/customizer/sections/general.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/layout.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/typography.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/background.php';

$wp_customize->add_section(
	new Header(
		$wp_customize,
		'blocks_header',
		array(
			'title'    => esc_html__( 'Blocks', 'nifty-coming-soon-and-under-construction-page' ),
			'priority' => 100,
			'panel'    => 'nifty_cs_panel',
		)
	)
);

require_once NCSUCP_DIR . '/inc/customizer/sections/logo.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/animated-text.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/countdown.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/slider.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/subscription.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/contact.php';
require_once NCSUCP_DIR . '/inc/customizer/sections/social.php';
