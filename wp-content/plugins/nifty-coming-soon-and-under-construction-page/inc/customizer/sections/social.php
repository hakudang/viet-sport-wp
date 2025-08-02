<?php
/**
 * Social section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\Message;
use Nilambar\CustomizerUtils\Control\Text;
use Nilambar\CustomizerUtils\Control\URL;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

// Social Section.
$wp_customize->add_section(
	'nifty_cs_section_social',
	array(
		'title'      => esc_html__( 'Social Links', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting social_links_intro_text.
$wp_customize->add_setting(
	'nifty_cs_option[social_links_intro_text]',
	array(
		'default'           => $default['social_links_intro_text'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[social_links_intro_text]',
		array(
			'label'    => esc_html__( 'Social Intro Text', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_social',
			'settings' => 'nifty_cs_option[social_links_intro_text]',
			'priority' => 100,
		)
	)
);

// Setting social_url_info.
$wp_customize->add_setting(
	'nifty_cs_option[social_url_info]',
	array(
		'capability' => 'manage_options',
		'type'       => 'option',
	)
);
$wp_customize->add_control(
	new Message(
		$wp_customize,
		'nifty_cs_option[social_url_info]',
		array(
			'label'       => esc_html__( 'Note', 'nifty-coming-soon-and-under-construction-page' ),
			'description' => esc_html__( 'Please enter full URL including https://', 'nifty-coming-soon-and-under-construction-page' ),
			'section'     => 'nifty_cs_section_social',
			'settings'    => 'nifty_cs_option[social_url_info]',
			'priority'    => 100,
		)
	)
);

$ncs_socials = array(
	'facebook_page_or_profile_url' => array(
		'label' => esc_html__( 'Facebook URL', 'nifty-coming-soon-and-under-construction-page' ),
	),
	'twitter_url'                  => array(
		'label' => esc_html__( 'Twitter URL', 'nifty-coming-soon-and-under-construction-page' ),
	),
	'youtube_url'                  => array(
		'label' => esc_html__( 'Youtube URL', 'nifty-coming-soon-and-under-construction-page' ),
	),
	'linkedin_profile_url'         => array(
		'label' => esc_html__( 'LinkedIn URL', 'nifty-coming-soon-and-under-construction-page' ),
	),
	'pinterest_url'                => array(
		'label' => esc_html__( 'Pinterest URL', 'nifty-coming-soon-and-under-construction-page' ),
	),
	'instagram_url'                => array(
		'label' => esc_html__( 'Instagram URL', 'nifty-coming-soon-and-under-construction-page' ),
	),
	'vimeo_url'                    => array(
		'label' => esc_html__( 'Vimeo URL', 'nifty-coming-soon-and-under-construction-page' ),
	),
);

foreach ( $ncs_socials as $key => $social ) {
	$wp_customize->add_setting(
		"nifty_cs_option[${key}]",
		array(
			'default'           => $default[ $key ],
			'capability'        => 'manage_options',
			'type'              => 'option',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( Sanitize::class, 'url' ),
		)
	);
	$wp_customize->add_control(
		new URL(
			$wp_customize,
			"nifty_cs_option[${key}]",
			array(
				'label'    => $social['label'],
				'section'  => 'nifty_cs_section_social',
				'settings' => "nifty_cs_option[${key}]",
				'priority' => 100,
			)
		)
	);
}
