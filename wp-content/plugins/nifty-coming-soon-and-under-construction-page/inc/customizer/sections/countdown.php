<?php
/**
 * Countdown section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\Color;
use Nilambar\CustomizerUtils\Control\Text;
use Nilambar\CustomizerUtils\Control\DateTime;
use Nilambar\CustomizerUtils\Control\DropdownGoogleFonts;
use Nilambar\CustomizerUtils\Control\Heading;
use Nilambar\CustomizerUtils\Helper\Sanitize;


$default = nifty_cs_get_default_options();

$wp_customize->add_section(
	'nifty_cs_section_countdown',
	array(
		'title'      => esc_html__( 'Countdown', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting setup_the_count_down_timer.
$wp_customize->add_setting(
	'nifty_cs_option[setup_the_count_down_timer]',
	array(
		'default'           => $default['setup_the_count_down_timer'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new DateTime(
		$wp_customize,
		'nifty_cs_option[setup_the_count_down_timer]',
		array(
			'label'    => esc_html__( 'Countdown Target Date', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[setup_the_count_down_timer]',
			'priority' => 100,
		)
	)
);

// Setting nifty_days_translate.
$wp_customize->add_setting(
	'nifty_cs_option[nifty_days_translate]',
	array(
		'default'           => $default['nifty_days_translate'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[nifty_days_translate]',
		array(
			'label'    => esc_html__( 'Text for "days"', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[nifty_days_translate]',
			'priority' => 100,
		)
	)
);

// Setting nifty_hours_translate.
$wp_customize->add_setting(
	'nifty_cs_option[nifty_hours_translate]',
	array(
		'default'           => $default['nifty_hours_translate'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[nifty_hours_translate]',
		array(
			'label'    => esc_html__( 'Text for "hours"', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[nifty_hours_translate]',
			'priority' => 100,
		)
	)
);

// Setting nifty_minutes_translate.
$wp_customize->add_setting(
	'nifty_cs_option[nifty_minutes_translate]',
	array(
		'default'           => $default['nifty_minutes_translate'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[nifty_minutes_translate]',
		array(
			'label'    => esc_html__( 'Text for "minutes"', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[nifty_minutes_translate]',
			'priority' => 100,
		)
	)
);

// Setting nifty_seconds_translate.
$wp_customize->add_setting(
	'nifty_cs_option[nifty_seconds_translate]',
	array(
		'default'           => $default['nifty_seconds_translate'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[nifty_seconds_translate]',
		array(
			'label'    => esc_html__( 'Text for "seconds"', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[nifty_seconds_translate]',
			'priority' => 100,
		)
	)
);

// Setting countdown_style_heading.
$wp_customize->add_setting(
	'nifty_cs_option[countdown_style_heading]',
	array(
		'capability' => 'manage_options',
		'type'       => 'option',
	)
);
$wp_customize->add_control(
	new Heading(
		$wp_customize,
		'nifty_cs_option[countdown_style_heading]',
		array(
			'label'    => esc_html__( 'Design Options', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[countdown_style_heading]',
			'priority' => 100,
		)
	)
);

// Setting choose_counter_font.
$wp_customize->add_setting(
	'nifty_cs_option[choose_counter_font]',
	array(
		'default'           => $default['choose_counter_font'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'select' ),
	)
);
$wp_customize->add_control(
	new DropdownGoogleFonts(
		$wp_customize,
		'nifty_cs_option[choose_counter_font]',
		array(
			'label'    => esc_html__( 'Font Family', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[choose_counter_font]',
			'priority' => 100,
		)
	)
);

// Setting countdown_font_color.
$wp_customize->add_setting(
	'nifty_cs_option[countdown_font_color]',
	array(
		'default'           => $default['countdown_font_color'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'color' ),
	)
);
$wp_customize->add_control(
	new Color(
		$wp_customize,
		'nifty_cs_option[countdown_font_color]',
		array(
			'label'    => esc_html__( 'Number Color', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[countdown_font_color]',
			'priority' => 100,
		)
	)
);

// Setting countdown_font_color_bottom.
$wp_customize->add_setting(
	'nifty_cs_option[countdown_font_color_bottom]',
	array(
		'default'           => $default['countdown_font_color_bottom'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'color' ),
	)
);
$wp_customize->add_control(
	new Color(
		$wp_customize,
		'nifty_cs_option[countdown_font_color_bottom]',
		array(
			'label'    => esc_html__( 'Label Color', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_countdown',
			'settings' => 'nifty_cs_option[countdown_font_color_bottom]',
			'priority' => 100,
		)
	)
);
