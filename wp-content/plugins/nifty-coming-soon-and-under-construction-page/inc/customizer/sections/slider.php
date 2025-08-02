<?php
/**
 * Slider section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\Sortable;
use Nilambar\CustomizerUtils\Control\Toggle;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

$wp_customize->add_section(
	'nifty_cs_section_slider',
	array(
		'title'      => esc_html__( 'Slider', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);


// Setting slider_blocks.
$wp_customize->add_setting(
	'nifty_cs_option[slider_blocks]',
	array(
		'default'           => $default['slider_blocks'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'sortable' ),
	)
);
$wp_customize->add_control(
	new Sortable(
		$wp_customize,
		'nifty_cs_option[slider_blocks]',
		array(
			'label'       => esc_html__( 'Slider Blocks', 'nifty-coming-soon-and-under-construction-page' ),
			'description' => esc_html__( 'You can reorder and enable/disable slider blocks here.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'     => 'nifty_cs_section_slider',
			'settings'    => 'nifty_cs_option[slider_blocks]',
			'priority'    => 100,
			'choices'     => nifty_cs_slider_blocks_options(),
		)
	)
);

// Setting disable_navigation.
$wp_customize->add_setting(
	'nifty_cs_option[disable_navigation]',
	array(
		'default'           => $default['disable_navigation'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'toggle' ),
	)
);
$wp_customize->add_control(
	new Toggle(
		$wp_customize,
		'nifty_cs_option[disable_navigation]',
		array(
			'label'    => esc_html__( 'Navigation', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_slider',
			'settings' => 'nifty_cs_option[disable_navigation]',
			'priority' => 100,
		)
	)
);
