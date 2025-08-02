<?php
/**
 * Contact section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\Email;
use Nilambar\CustomizerUtils\Control\Text;
use Nilambar\CustomizerUtils\Helper\Sanitize;

$default = nifty_cs_get_default_options();

$wp_customize->add_section(
	'nifty_cs_section_contact',
	array(
		'title'      => esc_html__( 'Contact', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting enter_you_website_or_company_name.
$wp_customize->add_setting(
	'nifty_cs_option[enter_you_website_or_company_name]',
	array(
		'default'           => $default['enter_you_website_or_company_name'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[enter_you_website_or_company_name]',
		array(
			'label'    => esc_html__( 'Company Name', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_contact',
			'settings' => 'nifty_cs_option[enter_you_website_or_company_name]',
			'priority' => 100,
		)
	)
);

// Setting enter_your_address.
$wp_customize->add_setting(
	'nifty_cs_option[enter_your_address]',
	array(
		'default'           => $default['enter_your_address'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[enter_your_address]',
		array(
			'label'    => esc_html__( 'Company Address', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_contact',
			'settings' => 'nifty_cs_option[enter_your_address]',
			'priority' => 100,
		)
	)
);

// Setting enter_your_phone_number.
$wp_customize->add_setting(
	'nifty_cs_option[enter_your_phone_number]',
	array(
		'default'           => $default['enter_your_phone_number'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[enter_your_phone_number]',
		array(
			'label'    => esc_html__( 'Phone Number', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_contact',
			'settings' => 'nifty_cs_option[enter_your_phone_number]',
			'priority' => 100,
		)
	)
);

// Setting enter_your_email_address.
$wp_customize->add_setting(
	'nifty_cs_option[enter_your_email_address]',
	array(
		'default'           => $default['enter_your_email_address'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'email' ),
	)
);
$wp_customize->add_control(
	new Email(
		$wp_customize,
		'nifty_cs_option[enter_your_email_address]',
		array(
			'label'       => esc_html__( 'Contact Email', 'nifty-coming-soon-and-under-construction-page' ),
			'description' => esc_html__( 'This email address is also used for sending subscription request email. If empty, subscription request email will be sent to WordPress admin email.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'     => 'nifty_cs_section_contact',
			'settings'    => 'nifty_cs_option[enter_your_email_address]',
			'priority'    => 100,
		)
	)
);
