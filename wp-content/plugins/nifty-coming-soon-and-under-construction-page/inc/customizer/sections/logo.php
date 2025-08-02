<?php
/**
 * Logo section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\DropdownGoogleFonts;
use Nilambar\CustomizerUtils\Control\Image;
use Nilambar\CustomizerUtils\Control\Toggle;
use Nilambar\CustomizerUtils\Helper\Callback;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

$wp_customize->add_section(
	'nifty_cs_section_logo',
	array(
		'title'      => esc_html__( 'Logo', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting disable_logo.
$wp_customize->add_setting(
	'nifty_cs_option[disable_logo]',
	array(
		'default'           => $default['disable_logo'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'toggle' ),
	)
);
$wp_customize->add_control(
	new Toggle(
		$wp_customize,
		'nifty_cs_option[disable_logo]',
		array(
			'label'    => esc_html__( 'Use Image', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_logo',
			'settings' => 'nifty_cs_option[disable_logo]',
			'priority' => 100,
		)
	)
);

// Setting upload_your_logo.
$wp_customize->add_setting(
	'nifty_cs_option[upload_your_logo]',
	array(
		'default'           => $default['upload_your_logo'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'url' ),
	)
);
$wp_customize->add_control(
	new Image(
		$wp_customize,
		'nifty_cs_option[upload_your_logo]',
		array(
			'label'             => esc_html__( 'Upload Logo', 'nifty-coming-soon-and-under-construction-page' ),
			'description'       => esc_html__( 'Note: Please use some png image with 200x90px in size.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_logo',
			'settings'          => 'nifty_cs_option[upload_your_logo]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_logo]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting display_site_title.
$wp_customize->add_setting(
	'nifty_cs_option[display_site_title]',
	array(
		'default'           => $default['display_site_title'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'toggle' ),
	)
);
$wp_customize->add_control(
	new Toggle(
		$wp_customize,
		'nifty_cs_option[display_site_title]',
		array(
			'label'             => esc_html__( 'Display Site Title', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_logo',
			'settings'          => 'nifty_cs_option[display_site_title]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_logo]',
						'compare' => '!=',
						'value'   => 'on',
					),
				),
			),
		)
	)
);


// Setting choose_sitetitle_font.
$wp_customize->add_setting(
	'nifty_cs_option[choose_sitetitle_font]',
	array(
		'default'           => $default['choose_sitetitle_font'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'select' ),
	)
);
$wp_customize->add_control(
	new DropdownGoogleFonts(
		$wp_customize,
		'nifty_cs_option[choose_sitetitle_font]',
		array(
			'label'             => esc_html__( 'Site Title Font', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_logo',
			'settings'          => 'nifty_cs_option[choose_sitetitle_font]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_logo]',
						'compare' => '!=',
						'value'   => 'on',
					),
					array(
						'key'     => 'nifty_cs_option[display_site_title]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);
