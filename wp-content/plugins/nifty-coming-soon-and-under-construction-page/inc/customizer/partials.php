<?php
/**
 * Partials
 *
 * @package NCSUCP
 */

// Social.
$wp_customize->selective_refresh->add_partial(
	'nifty_cs_partial_social_links',
	array(
		'selector'            => '.nifty-socials',
		'settings'            => array( 'nifty_cs_option[facebook_page_or_profile_url]', 'nifty_cs_option[twitter_url]', 'nifty_cs_option[youtube_url]', 'nifty_cs_option[linkedin_profile_url]', 'nifty_cs_option[pinterest_url]', 'nifty_cs_option[instagram_url]', 'nifty_cs_option[vimeo_url]' ),
		'container_inclusive' => true,
		'render_callback'     => function() {
			ob_start();
			require NCSUCP_DIR . '/template-parts/sections/social.php';
			return ob_get_clean();
		},
	)
);
