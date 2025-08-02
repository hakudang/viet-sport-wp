<?php
/**
 * General section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\CodeEditor;
use Nilambar\CustomizerUtils\Control\Image;
use Nilambar\CustomizerUtils\Control\Text;
use Nilambar\CustomizerUtils\Control\Toggle;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

// General Section.
$wp_customize->add_section(
	'nifty_cs_section_general',
	array(
		'title'      => esc_html__( 'General Settings', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting coming_soon_mode_on___off.
$wp_customize->add_setting(
	'nifty_cs_option[coming_soon_mode_on___off]',
	array(
		'default'           => $default['coming_soon_mode_on___off'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'toggle' ),
	)
);
$wp_customize->add_control(
	new Toggle(
		$wp_customize,
		'nifty_cs_option[coming_soon_mode_on___off]',
		array(
			'label'    => esc_html__( 'Enable Coming Soon Mode', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_general',
			'settings' => 'nifty_cs_option[coming_soon_mode_on___off]',
			'priority' => 100,
		)
	)
);

// Setting enable_preloader.
$wp_customize->add_setting(
	'nifty_cs_option[enable_preloader]',
	array(
		'default'           => $default['enable_preloader'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'toggle' ),
	)
);
$wp_customize->add_control(
	new Toggle(
		$wp_customize,
		'nifty_cs_option[enable_preloader]',
		array(
			'label'    => esc_html__( 'Enable Preloader', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_general',
			'settings' => 'nifty_cs_option[enable_preloader]',
			'priority' => 100,
		)
	)
);

// Setting page_title.
$wp_customize->add_setting(
	'nifty_cs_option[page_title]',
	array(
		'default'           => $default['page_title'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[page_title]',
		array(
			'label'       => esc_html__( 'Page Title', 'nifty-coming-soon-and-under-construction-page' ),
			'description' => esc_html__( 'Page title for SEO. Keep it short.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'     => 'nifty_cs_section_general',
			'settings'    => 'nifty_cs_option[page_title]',
			'priority'    => 100,
		)
	)
);

// Setting page_description.
$wp_customize->add_setting(
	'nifty_cs_option[page_description]',
	array(
		'default'           => $default['page_description'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[page_description]',
		array(
			'label'       => esc_html__( 'Page Description', 'nifty-coming-soon-and-under-construction-page' ),
			'description' => esc_html__( 'Page description for SEO. Keep it between 50 and 300 characters.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'     => 'nifty_cs_section_general',
			'settings'    => 'nifty_cs_option[page_description]',
			'priority'    => 100,
		)
	)
);

// Setting opengraph_thumbnail.
$wp_customize->add_setting(
	'nifty_cs_option[opengraph_thumbnail]',
	array(
		'default'           => $default['opengraph_thumbnail'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'url' ),
	)
);
$wp_customize->add_control(
	new Image(
		$wp_customize,
		'nifty_cs_option[opengraph_thumbnail]',
		array(
			'label'       => esc_html__( 'OpenGraph Thumbnail', 'nifty-coming-soon-and-under-construction-page' ),
			'description' => esc_html__( 'Upload at least 600x315px image. Recommended size is 1200x630px.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'     => 'nifty_cs_section_general',
			'settings'    => 'nifty_cs_option[opengraph_thumbnail]',
			'priority'    => 100,
		)
	)
);

// Setting insert_google_analytics_code.
$wp_customize->add_setting(
	'nifty_cs_option[insert_google_analytics_code]',
	array(
		'default'    => $default['insert_google_analytics_code'],
		'capability' => 'manage_options',
		'type'       => 'option',
		'transport'  => 'postMessage',
	)
);
$wp_customize->add_control(
	new CodeEditor(
		$wp_customize,
		'nifty_cs_option[insert_google_analytics_code]',
		array(
			'label'    => esc_html__( 'Google Analytics code', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_general',
			'settings' => 'nifty_cs_option[insert_google_analytics_code]',
			'priority' => 100,
		)
	)
);

// Setting insert_additional_css.
$wp_customize->add_setting(
	'nifty_cs_option[insert_additional_css]',
	array(
		'default'    => $default['insert_additional_css'],
		'capability' => 'manage_options',
		'type'       => 'option',
	)
);
$wp_customize->add_control(
	new CodeEditor(
		$wp_customize,
		'nifty_cs_option[insert_additional_css]',
		array(
			'label'    => esc_html__( 'Additional CSS', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_general',
			'settings' => 'nifty_cs_option[insert_additional_css]',
			'priority' => 100,
		)
	)
);
