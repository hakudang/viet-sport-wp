<?php
/**
 * Subscription section
 *
 * @package NCSUCP
 */

use Nilambar\CustomizerUtils\Control\CodeEditor;
use Nilambar\CustomizerUtils\Control\Color;
use Nilambar\CustomizerUtils\Control\Email;
use Nilambar\CustomizerUtils\Control\Heading;
use Nilambar\CustomizerUtils\Control\Message;
use Nilambar\CustomizerUtils\Control\Text;
use Nilambar\CustomizerUtils\Control\Toggle;
use Nilambar\CustomizerUtils\Helper\Sanitize;
use Nilambar\CustomizerUtils\Helper\Callback;

$default = nifty_cs_get_default_options();

$wp_customize->add_section(
	'nifty_cs_section_subscription',
	array(
		'title'      => esc_html__( 'Subscription', 'nifty-coming-soon-and-under-construction-page' ),
		'priority'   => 100,
		'capability' => 'manage_options',
		'panel'      => 'nifty_cs_panel',
	)
);

// Setting enable_sign_up_form.
$wp_customize->add_setting(
	'nifty_cs_option[enable_sign_up_form]',
	array(
		'default'           => $default['enable_sign_up_form'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'sanitize_callback' => array( Sanitize::class, 'toggle' ),
	)
);
$wp_customize->add_control(
	new Toggle(
		$wp_customize,
		'nifty_cs_option[enable_sign_up_form]',
		array(
			'label'    => esc_html__( 'Enable Built-in Form', 'nifty-coming-soon-and-under-construction-page' ),
			'section'  => 'nifty_cs_section_subscription',
			'settings' => 'nifty_cs_option[enable_sign_up_form]',
			'priority' => 100,
		)
	)
);

// Setting insert_custom_signup_form.
$wp_customize->add_setting(
	'nifty_cs_option[insert_custom_signup_form]',
	array(
		'default'    => $default['insert_custom_signup_form'],
		'capability' => 'manage_options',
		'type'       => 'option',
	)
);
$wp_customize->add_control(
	new CodeEditor(
		$wp_customize,
		'nifty_cs_option[insert_custom_signup_form]',
		array(
			'label'             => esc_html__( 'Custom Subscription Form', 'nifty-coming-soon-and-under-construction-page' ),
			'description'       => esc_html__( 'Enter third party form embed code.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[insert_custom_signup_form]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '!=',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting subscription_email_info.
$wp_customize->add_setting(
	'nifty_cs_option[subscription_email_info]',
	array(
		'capability' => 'manage_options',
		'type'       => 'option',
	)
);
$wp_customize->add_control(
	new Message(
		$wp_customize,
		'nifty_cs_option[subscription_email_info]',
		array(
			'label'             => esc_html__( 'Important', 'nifty-coming-soon-and-under-construction-page' ),
			'description'       => esc_html__( 'Emails are not stored in WordPress nor sent to any 3rd party services like MailChimp. You will only receive them in the email address.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[subscription_email_info]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting sign_up_email_to.
$wp_customize->add_setting(
	'nifty_cs_option[sign_up_email_to]',
	array(
		'default'           => $default['sign_up_email_to'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'email' ),
	)
);
$wp_customize->add_control(
	new Email(
		$wp_customize,
		'nifty_cs_option[sign_up_email_to]',
		array(
			'label'             => esc_html__( 'Send Subscription Email To', 'nifty-coming-soon-and-under-construction-page' ),
			'description'       => esc_html__( 'If empty, subscription request email will be sent to the email address entered in Contact block.', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[sign_up_email_to]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting sign_up_form_intro_text.
$wp_customize->add_setting(
	'nifty_cs_option[sign_up_form_intro_text]',
	array(
		'default'           => $default['sign_up_form_intro_text'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[sign_up_form_intro_text]',
		array(
			'label'             => esc_html__( 'Subscription Form Intro Text', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[sign_up_form_intro_text]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting sign_up_button_text.
$wp_customize->add_setting(
	'nifty_cs_option[sign_up_button_text]',
	array(
		'default'           => $default['sign_up_button_text'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[sign_up_button_text]',
		array(
			'label'             => esc_html__( 'Subscription Form Button Text', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[sign_up_button_text]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting enter_email_text.
$wp_customize->add_setting(
	'nifty_cs_option[enter_email_text]',
	array(
		'default'           => $default['enter_email_text'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[enter_email_text]',
		array(
			'label'             => esc_html__( 'Subscription Form Placeholder Text', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[enter_email_text]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting email_confirmation___error.
$wp_customize->add_setting(
	'nifty_cs_option[email_confirmation___error]',
	array(
		'default'           => $default['email_confirmation___error'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[email_confirmation___error]',
		array(
			'label'             => esc_html__( 'Email Confirmation - Error', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[email_confirmation___error]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting email_confirmation___success.
$wp_customize->add_setting(
	'nifty_cs_option[email_confirmation___success]',
	array(
		'default'           => $default['email_confirmation___success'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'text' ),
	)
);
$wp_customize->add_control(
	new Text(
		$wp_customize,
		'nifty_cs_option[email_confirmation___success]',
		array(
			'label'             => esc_html__( 'Email Confirmation - Success', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[email_confirmation___success]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting signup_style_heading.
$wp_customize->add_setting(
	'nifty_cs_option[signup_style_heading]',
	array(
		'capability' => 'manage_options',
		'type'       => 'option',
	)
);
$wp_customize->add_control(
	new Heading(
		$wp_customize,
		'nifty_cs_option[signup_style_heading]',
		array(
			'label'             => esc_html__( 'Design Options', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[signup_style_heading]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting sign_up_button_color.
$wp_customize->add_setting(
	'nifty_cs_option[sign_up_button_color]',
	array(
		'default'           => $default['sign_up_button_color'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'color' ),
	)
);
$wp_customize->add_control(
	new Color(
		$wp_customize,
		'nifty_cs_option[sign_up_button_color]',
		array(
			'label'             => esc_html__( 'Button Background Color', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[sign_up_button_color]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);

// Setting sign_up_button_color_hover.
$wp_customize->add_setting(
	'nifty_cs_option[sign_up_button_color_hover]',
	array(
		'default'           => $default['sign_up_button_color_hover'],
		'capability'        => 'manage_options',
		'type'              => 'option',
		'transport'         => 'postMessage',
		'sanitize_callback' => array( Sanitize::class, 'color' ),
	)
);
$wp_customize->add_control(
	new Color(
		$wp_customize,
		'nifty_cs_option[sign_up_button_color_hover]',
		array(
			'label'             => esc_html__( 'Button Background Hover Color', 'nifty-coming-soon-and-under-construction-page' ),
			'section'           => 'nifty_cs_section_subscription',
			'settings'          => 'nifty_cs_option[sign_up_button_color_hover]',
			'priority'          => 100,
			'active_callback'   => array( Callback::class, 'active' ),
			'conditional_logic' => array(
				array(
					array(
						'key'     => 'nifty_cs_option[enable_sign_up_form]',
						'compare' => '==',
						'value'   => 'on',
					),
				),
			),
		)
	)
);
