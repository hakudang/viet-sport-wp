<?php
/**
 * Animated text section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\Text;
use Nilambar\CustomizerUtils\Control\Toggle;
use Nilambar\CustomizerUtils\Helper\Callback;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

$wp_customize->add_section(
	'nifty_cs_section_animated_text',
	array(
		'title'      => esc_html__( 'Animated Text', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting your_coming_soon_message.
$wp_customize->add_setting(
	'nifty_cs_option[your_coming_soon_message]',
	array(
		'default'           => $default['your_coming_soon_message'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[your_coming_soon_message]',
		array(
			'label'    => esc_html__( 'Coming Soon Message', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_animated_text',
			'settings' => 'nifty_cs_option[your_coming_soon_message]',
			'priority' => 100,
		)
	)
);

// Setting disable_animation.
$wp_customize->add_setting(
	'nifty_cs_option[disable_animation]',
	array(
		'default'           => $default['disable_animation'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'toggle' ),
	)
);
$wp_customize->add_control(
	new Toggle(
		$wp_customize,
		'nifty_cs_option[disable_animation]',
		array(
			'label'    => esc_html__( 'Text Animation', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_animated_text',
			'settings' => 'nifty_cs_option[disable_animation]',
			'priority' => 100,
		)
	)
);

// Setting enter_second_coming_soon_message.
$wp_customize->add_setting(
	'nifty_cs_option[enter_second_coming_soon_message]',
	array(
		'default'           => $default['enter_second_coming_soon_message'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[enter_second_coming_soon_message]',
		array(
			'label'             => esc_html__( 'Second Coming Soon Message', 'nifty-coming-soon-and-under-construction-page' ),
			'description'       => esc_html__( 'This message will be animated over the first message.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_animated_text',
			'settings'          => 'nifty_cs_option[enter_second_coming_soon_message]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_animation]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);
