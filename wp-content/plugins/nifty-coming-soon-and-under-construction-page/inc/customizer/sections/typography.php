<?php
/**
 * Typography section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\DropdownGoogleFonts;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

$wp_customize->add_section(
	'nifty_cs_section_typography',
	array(
		'title'      => esc_html__( 'Typography', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting choose_paragraph_font.
$wp_customize->add_setting(
	'nifty_cs_option[choose_paragraph_font]',
	array(
		'default'           => $default['choose_paragraph_font'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'select' ),
	)
);
$wp_customize->add_control(
	new DropdownGoogleFonts(
		$wp_customize,
		'nifty_cs_option[choose_paragraph_font]',
		array(
			'label'    => esc_html__( 'Body Font', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_typography',
			'settings' => 'nifty_cs_option[choose_paragraph_font]',
			'priority' => 100,
		)
	)
);

// Setting choose_heading_font.
$wp_customize->add_setting(
	'nifty_cs_option[choose_heading_font]',
	array(
		'default'           => $default['choose_heading_font'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'select' ),
	)
);
$wp_customize->add_control(
	new DropdownGoogleFonts(
		$wp_customize,
		'nifty_cs_option[choose_heading_font]',
		array(
			'label'    => esc_html__( 'Heading Font', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_typography',
			'settings' => 'nifty_cs_option[choose_heading_font]',
			'priority' => 100,
		)
	)
);
