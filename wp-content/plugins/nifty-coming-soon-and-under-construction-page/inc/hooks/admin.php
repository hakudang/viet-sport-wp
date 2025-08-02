<?php
/**
 * Admin
 *
 * @package NCSUCP
 */

/**
 * Add menu in admin bar.
 *
 * @since 1.0.0
 */
function nifty_cs_custom_menu() {
	global $wp_admin_bar;

	$value = nifty_cs_get_option( 'coming_soon_mode_on___off' );

	if ( 'off' !== $value ) {
		$args = array(
			'id'    => 'niftycs_custom_menu',
			'title' => esc_html__( 'Nifty Coming Soon is Enabled', 'nifty-coming-soon-and-under-construction-page' ),
			'href'  => admin_url( 'admin.php?page=nifty-coming-soon' ),
			'meta'  => array( 'class' => 'red-hot-button' ),

		);

		$wp_admin_bar->add_menu( $args );
	}
}

add_action( 'admin_bar_menu', 'nifty_cs_custom_menu', 1000 );

/**
 * Custom admin styles.
 *
 * @since 1.0.0
 */
function nifty_cs_admin_custom_styles() {
	$custom_css = '
		#wp-admin-bar-niftycs_custom_menu a{
			background:#80002E !important;
			color:#FFFFFF !important;
			transition: all 0.5s ease;
		}
		#wp-admin-bar-niftycs_custom_menu a:active {
			background:#88143E !important;
			color:#F3F3F3 !important;
			transition: all 0.5s ease;
		}
		.wp-not-current-submenu.menu-top.toplevel_page_nifty-coming-soon.menu-top-last:hover {
			background: #80002E !important;
			color: #FFF !important;
		}
	';

	wp_add_inline_style( 'common', $custom_css );
}

add_action( 'admin_enqueue_scripts', 'nifty_cs_admin_custom_styles' );

/**
 * Add admin notice.
 *
 * @since 2.0.2
 */
function nifty_cs_admin_notice() {
	\Nilambar\AdminNotice\Notice::init(
		array(
			'slug' => NCSUCP_SLUG,
			'name' => esc_html__( 'Coming Soon & Maintenance Mode Page', 'nifty-coming-soon-and-under-construction-page' ),
		)
	);
}

add_action( 'admin_init', 'nifty_cs_admin_notice' );

/**
 * Add custom links to plugins page.
 *
 * @since 1.0.0
 *
 * @param array $links Plugin links.
 * @return array Modified plugin links.
 */
function nifty_cs_customize_plugin_action_links( $links ) {
	$new_links = array(
		'<a href="' . esc_url( admin_url( 'admin.php?page=nifty-coming-soon' ) ) . '">' . esc_html__( 'Settings', 'nifty-coming-soon-and-under-construction-page' ) . '</a>',
		'<a href="' . nifty_cs_get_customizer_url() . '">' . esc_html__( 'Customize', 'nifty-coming-soon-and-under-construction-page' ) . '</a>',
	);

	$links = array_merge( $new_links, $links );

	return $links;
}

add_filter( 'plugin_action_links_' . NCSUCP_BASE_FILENAME, 'nifty_cs_customize_plugin_action_links' );
