<?php
/**
 * Core functions
 *
 * @package NCSUCP
 */

/**
 * Return default plugin options.
 *
 * @since 3.0.0
 *
 * @return array Default options.
 */
function nifty_cs_get_default_options() {
	$defaults = array();

	$site_slug = nifty_cs_get_site_slug( home_url() );

	// General.
	$defaults['coming_soon_mode_on___off']    = 'on';
	$defaults['enable_preloader']             = 'on';
	$defaults['page_title']                   = get_bloginfo( 'name' ) . ' is coming soon';
	$defaults['page_description']             = 'We are doing some work on our site. Please be patient. Thank you.';
	$defaults['opengraph_thumbnail']          = '';
	$defaults['insert_google_analytics_code'] = '';
	$defaults['insert_additional_css']        = '';

	// Layout.
	$defaults['page_blocks'] = array( 'logo', 'animated-text', 'countdown', 'slider' );

	// Typography.
	$defaults['choose_paragraph_font'] = 'Open Sans';
	$defaults['choose_heading_font']   = 'Lato';

	// Background.
	$defaults['background_color']                 = '';
	$defaults['disable_background_image_slider']  = 'on';
	$defaults['background_slider_time']           = '10000';
	$defaults['background_slider_animation_time'] = '2000';
	$defaults['upload_slider_images']             = NCSUCP_URL . '/assets/images/slideshow/1.jpg';
	$defaults['upload_slider_images_2']           = NCSUCP_URL . '/assets/images/slideshow/2.jpg';
	$defaults['upload_slider_images_3']           = NCSUCP_URL . '/assets/images/slideshow/3.jpg';
	$defaults['upload_slider_images_4']           = NCSUCP_URL . '/assets/images/slideshow/4.jpg';
	$defaults['background_slider_animation']      = 'random';
	$defaults['select_pattern_overlay']           = '16.png';
	$defaults['pattern_overlay_opacity']          = '0.5';

	// Logo.
	$defaults['disable_logo']          = 'on';
	$defaults['upload_your_logo']      = NCSUCP_URL . '/assets/images/logo.png';
	$defaults['display_site_title']    = 'off';
	$defaults['choose_sitetitle_font'] = 'Lato';

	// Animated Text.
	$defaults['your_coming_soon_message']         = 'Our website is coming very soon';
	$defaults['enter_second_coming_soon_message'] = 'Feel free to drop-by any time soon';
	$defaults['disable_animation']                = 'on';

	// Countdown.
	$defaults['setup_the_count_down_timer']  = gmdate( 'Y-m-d H:i', strtotime( '+7 days' ) );
	$defaults['nifty_days_translate']        = 'days';
	$defaults['nifty_hours_translate']       = 'hours';
	$defaults['nifty_minutes_translate']     = 'minutes';
	$defaults['nifty_seconds_translate']     = 'seconds';
	$defaults['choose_counter_font']         = 'Raleway';
	$defaults['countdown_font_color']        = '#ffffff';
	$defaults['countdown_font_color_bottom'] = '#ffffff';

	// Slider.
	$defaults['slider_blocks']      = array( 'subscription', 'contact', 'social' );
	$defaults['disable_navigation'] = 'on';

	// Subscription.
	$defaults['enable_sign_up_form']          = 'on';
	$defaults['insert_custom_signup_form']    = '';
	$defaults['sign_up_email_to']             = '';
	$defaults['sign_up_form_intro_text']      = 'Sign up to find out when we launch';
	$defaults['sign_up_button_text']          = 'Sign Up';
	$defaults['enter_email_text']             = 'Enter Email...';
	$defaults['email_confirmation___error']   = 'Please, enter valid email address.';
	$defaults['email_confirmation___success'] = 'You will be notified, thanks.';
	$defaults['sign_up_button_color']         = '#9e0039';
	$defaults['sign_up_button_color_hover']   = '#9e0039';

	// Contact.
	$defaults['enter_you_website_or_company_name'] = 'ACME COMPANY';
	$defaults['enter_your_address']                = '230 New Found lane, 8900 New City';
	$defaults['enter_your_phone_number']           = '+555 53211 777';
	$defaults['enter_your_email_address']          = 'someone@example.com';

	// Social.
	$defaults['social_links_intro_text']      = 'Are you social? We are, find us below ;)';
	$defaults['facebook_page_or_profile_url'] = "https://www.facebook.com/{$site_slug}";
	$defaults['twitter_url']                  = "https://twitter.com/{$site_slug}";
	$defaults['youtube_url']                  = '';
	$defaults['linkedin_profile_url']         = '';
	$defaults['pinterest_url']                = '';
	$defaults['instagram_url']                = '';
	$defaults['vimeo_url']                    = "https://vimeo.com/{$site_slug}";

	// Pass through filter.
	return apply_filters( 'nifty_cs_filter_default_options', $defaults );
}

/**
 * Return plugin option.
 *
 * @since 1.0.0
 *
 * @param string $key Option key.
 * @return mixed Option value.
 */
function nifty_cs_get_option( $key ) {
	$default_options = nifty_cs_get_default_options();

	if ( empty( $key ) ) {
		return;
	}

	$wp_options = (array) get_option( 'nifty_cs_option' );
	$wp_options = wp_parse_args( $wp_options, $default_options );

	$value = null;

	if ( isset( $wp_options[ $key ] ) ) {
		$value = $wp_options[ $key ];
	}

	return $value;
}
