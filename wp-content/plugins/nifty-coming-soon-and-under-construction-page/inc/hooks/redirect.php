<?php
/**
 * Redirect
 *
 * @package NCSUCP
 */

/**
 * Setup redirect.
 *
 * @since 1.0.0
 */
function nifty_cs_redirect() {
	// phpcs:disable
	$request_uri = trailingslashit( strtolower( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ) );
	// phpcs:enable

	// Some URLs have to be accessible at all times.
	$white_list = array( '/wp-admin/', '/feed/', '/feed/rss/', '/feed/rss2/', '/feed/rdf/', '/feed/atom/', '/admin/', '/login/', '/wp-login.php' );

	if ( in_array( $request_uri, $white_list, true ) || false !== strpos( $request_uri, '/wp-login.php' ) ) {
		return;
	}

	// Check if the coming soon mode is enabled.
	$value = nifty_cs_get_option( 'coming_soon_mode_on___off' );

	if ( 'off' !== $value ) {
		if ( ! is_feed() ) {
			// Guests are redirected to the coming soon page.
			if ( ! is_user_logged_in() || ( isset( $_GET['get_preview'] ) && 'true' === $_GET['get_preview'] ) ) {
				// Path to custom coming soon page.
				$template_path = NCSUCP_DIR . '/template-parts/coming-soon.php';
				include $template_path;
				exit();
			}
		}

		// Check user assigned role.
		if ( is_user_logged_in() ) {
			// Get logined in user role.
			global $current_user;

			$loggedin_user_id = $current_user->ID;
			$user_data        = get_userdata( $loggedin_user_id );

			// If user is not having administrator, editor, author or contributor role he will be server the coming soon page too.
			if ( 'subscriber' === $user_data->roles[0] || ( isset( $_GET['get_preview'] ) && 'true' === $_GET['get_preview'] ) ) {
				if ( ! is_feed() ) {
					$template_path = NCSUCP_DIR . '/template-parts/coming-soon.php';
					include $template_path;
					exit();
				}
			}
		}
	}
}

/**
 * Preview coming soon page.
 *
 * @since 1.0.0
 */
function nifty_cs_get_preview() {
	if ( ( isset( $_GET['get_preview'] ) && 'true' === $_GET['get_preview'] ) ) {
		$template_path = NCSUCP_DIR . '/template-parts/coming-soon.php';
		include $template_path;
		exit();
	}
}

add_action( 'template_redirect', 'nifty_cs_get_preview' );

/**
 * Hook redirect.
 *
 * @since 1.0.0
 */
function nifty_cs_skip_redirect_on_login() {
	global $currentpage;

	if ( 'wp-login.php' !== $currentpage ) {
		add_action( 'template_redirect', 'nifty_cs_redirect' );
	}
}

add_action( 'init', 'nifty_cs_skip_redirect_on_login' );
