<?php
/**
 * Layout section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\Sortable;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

// Layout Section.
$wp_customize->add_section(
	'nifty_cs_section_layout',
	array(
		'title'      => esc_html__( 'Layout', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting page_blocks.
$wp_customize->add_setting(
	'nifty_cs_option[page_blocks]',
	array(
		'default'           => $default['page_blocks'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'sortable' ),
	)
);
$wp_customize->add_control(
	new Sortable(
		$wp_customize,
		'nifty_cs_option[page_blocks]',
		array(
			'label'       => esc_html__( 'Blocks', 'nifty-coming-soon-and-under-construction-page' ),
			'description' => esc_html__( 'You can reorder and enable/disable blocks here.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'     => 'nifty_cs_section_layout',
			'settings'    => 'nifty_cs_option[page_blocks]',
			'priority'    => 100,
			'choices'     => nifty_cs_page_blocks_options(),
		)
	)
);
