<?php
/**
 * Background section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\Color;
use Nilambar\CustomizerUtils\Control\Message;
use Nilambar\CustomizerUtils\Control\Image;
use Nilambar\CustomizerUtils\Control\RadioImage;
use Nilambar\CustomizerUtils\Control\Range;
use Nilambar\CustomizerUtils\Control\Select;
use Nilambar\CustomizerUtils\Control\Text;
use Nilambar\CustomizerUtils\Control\Toggle;
use Nilambar\CustomizerUtils\Helper\Callback;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

$wp_customize->add_section(
	'nifty_cs_section_background',
	array(
		'title'      => esc_html__( 'Background', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting disable_background_image_slider.
$wp_customize->add_setting(
	'nifty_cs_option[disable_background_image_slider]',
	array(
		'default'           => $default['disable_background_image_slider'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'toggle' ),
	)
);
$wp_customize->add_control(
	new Toggle(
		$wp_customize,
		'nifty_cs_option[disable_background_image_slider]',
		array(
			'label'    => esc_html__( 'Background Image Slider', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_background',
			'settings' => 'nifty_cs_option[disable_background_image_slider]',
			'priority' => 100,
		)
	)
);

// Setting background_images_info.
$wp_customize->add_setting(
	'nifty_cs_option[background_images_info]',
	array(
		'capability' => 'manage_options',
		'type'       => 'option',
	)
);
$wp_customize->add_control(
	new Message(
		$wp_customize,
		'nifty_cs_option[background_images_info]',
		array(
			'label'             => esc_html__( 'Note', 'nifty-coming-soon-and-under-construction-page' ),
			'description'       => esc_html__( 'Recommended image dimension: 1920x1080', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[background_images_info]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting upload_slider_images.
$wp_customize->add_setting(
	'nifty_cs_option[upload_slider_images]',
	array(
		'default'           => $default['upload_slider_images'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'url' ),
	)
);
$wp_customize->add_control(
	new Image(
		$wp_customize,
		'nifty_cs_option[upload_slider_images]',
		array(
			'label'             => esc_html__( 'First Background Slider Image', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[upload_slider_images]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting upload_slider_images_2.
$wp_customize->add_setting(
	'nifty_cs_option[upload_slider_images_2]',
	array(
		'default'           => $default['upload_slider_images_2'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'url' ),
	)
);
$wp_customize->add_control(
	new Image(
		$wp_customize,
		'nifty_cs_option[upload_slider_images_2]',
		array(
			'label'             => esc_html__( 'Second Background Slider Image', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[upload_slider_images_2]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting upload_slider_images_3.
$wp_customize->add_setting(
	'nifty_cs_option[upload_slider_images_3]',
	array(
		'default'           => $default['upload_slider_images_3'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'url' ),
	)
);
$wp_customize->add_control(
	new Image(
		$wp_customize,
		'nifty_cs_option[upload_slider_images_3]',
		array(
			'label'             => esc_html__( 'Third Background Slider Image', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[upload_slider_images_3]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting upload_slider_images_4.
$wp_customize->add_setting(
	'nifty_cs_option[upload_slider_images_4]',
	array(
		'default'           => $default['upload_slider_images_4'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'url' ),
	)
);
$wp_customize->add_control(
	new Image(
		$wp_customize,
		'nifty_cs_option[upload_slider_images_4]',
		array(
			'label'             => esc_html__( 'Fourth Background Slider Image', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[upload_slider_images_4]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting background_slider_animation.
$wp_customize->add_setting(
	'nifty_cs_option[background_slider_animation]',
	array(
		'default'           => $default['background_slider_animation'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'select' ),
	)
);
$wp_customize->add_control(
	new Select(
		$wp_customize,
		'nifty_cs_option[background_slider_animation]',
		array(
			'label'             => esc_html__( 'Select Animation', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[background_slider_animation]',
			'priority'          => 100,
			'choices'           => nifty_cs_get_animation_options(),
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting background_slider_time.
$wp_customize->add_setting(
	'nifty_cs_option[background_slider_time]',
	array(
		'default'           => $default['background_slider_time'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'number' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[background_slider_time]',
		array(
			'label'             => esc_html__( 'Slider Rotation Time', 'nifty-coming-soon-and-under-construction-page' ),
			'description'       => esc_html__( 'In miliseconds. Eg, 3000 equals to 3 seconds.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[background_slider_time]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting background_slider_animation_time.
$wp_customize->add_setting(
	'nifty_cs_option[background_slider_animation_time]',
	array(
		'default'           => $default['background_slider_animation_time'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'number' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[background_slider_animation_time]',
		array(
			'label'             => esc_html__( 'Transition Duration Time', 'nifty-coming-soon-and-under-construction-page' ),
			'description'       => esc_html__( 'In miliseconds. Eg, 3000 equals to 3 seconds.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[background_slider_animation_time]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting select_pattern_overlay.
$wp_customize->add_setting(
	'nifty_cs_option[select_pattern_overlay]',
	array(
		'default'           => $default['select_pattern_overlay'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'select' ),
	)
);
$wp_customize->add_control(
	new RadioImage(
		$wp_customize,
		'nifty_cs_option[select_pattern_overlay]',
		array(
			'label'             => esc_html__( 'Select Pattern Overlay', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[select_pattern_overlay]',
			'priority'          => 100,
			'choices'           => nifty_cs_get_pattern_options(),
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting pattern_overlay_opacity.
$wp_customize->add_setting(
	'nifty_cs_option[pattern_overlay_opacity]',
	array(
		'default'           => $default['pattern_overlay_opacity'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'range' ),
	)
);
$wp_customize->add_control(
	new Range(
		$wp_customize,
		'nifty_cs_option[pattern_overlay_opacity]',
		array(
			'label'             => esc_html__( 'Pattern Overlay Opacity', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[pattern_overlay_opacity]',
			'priority'          => 100,
			'input_attrs'       => array(
				'min'  => 0,
				'max'  => 1,
				'step' => 0.1,
			),
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting background_color.
$wp_customize->add_setting(
	'nifty_cs_option[background_color]',
	array(
		'default'           => $default['background_color'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'color' ),
	)
);
$wp_customize->add_control(
	new Color(
		$wp_customize,
		'nifty_cs_option[background_color]',
		array(
			'label'             => esc_html__( 'Background Color', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_background',
			'settings'          => 'nifty_cs_option[background_color]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[disable_background_image_slider]',
						'compare' => '!=',
						'value'   => 'on',
					),
				),
			),
		)
	)
);
