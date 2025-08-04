<?php
/**
 * Plugin Name: VK Filter Search Pro
 * Plugin URI: https://github.com/vektor-inc/vk-filter-search/
 * Description: This is a plugin that you can add a filter search function with blocks.
 * Version: 2.17.0.0
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Author:  Vektor,Inc.
 * Author URI: https://vektor-inc.co.jp
 * Text Domain: vk-filter-search-pro
 * Domain Path: /languages
 * License: GPL 2.0 or Later
 *
 * @package VK Filter Search Pro
 */

/*
Copyright 2020-2025 Vektor,Inc. ( email : vk-develop@vektor-inc.co.jp )
*/

defined( 'ABSPATH' ) || exit;
require_once ABSPATH . 'wp-admin/includes/plugin.php';

/**
 * Deactive VK Filter Search
 */
// Deactive Plugin VK Filter Search ( free )
if ( is_plugin_active( 'vk-filter-search/vk-filter-search.php' ) ) {
	deactivate_plugins( 'vk-filter-search/vk-filter-search.php' );
	return;
}
if ( is_plugin_active( 'vk-filter-search-pro-global-edition/vk-filter-search-pro-global-edition.php' ) ) {
	deactivate_plugins( 'vk-filter-search-pro/vk-filter-search-pro.php' );
	return;
}

if ( 'vk-filter-search-pro/vk-filter-search-pro.php' === plugin_basename( __FILE__ ) ) {
	/**
	 * Composer Autoload
	 */
	$autoload_path = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
	// vendor ディレクトリがない状態で誤配信された場合に Fatal Error にならないようにファイルの存在確認.
	if ( file_exists( $autoload_path ) ) {
		// Composer のファイルを読み込み ( composer install --no-dev )
		require_once $autoload_path;
	}

	// Define Plugin  Root Path
	define( 'VKFS_PLUGIN_ROOT_PATH', plugin_dir_path( __FILE__ ) );
	// Define Plugin Root URL
	define( 'VKFS_PLUGIN_ROOT_URL', plugin_dir_url( __FILE__ ) );
	// Define Plugin Version
	$plugin_data = get_file_data( __FILE__, array( 'version' => 'Version' ) );
	define( 'VKFS_PLUGIN_VERSION', $plugin_data['version'] );

	/**
	 * Load Text Domain
	 */
	function vkfs_pro_load_textdomain() {
		load_plugin_textdomain( 'vk-filter-search-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	add_action( 'plugins_loaded', 'vkfs_pro_load_textdomain' );


	// Update Checker
	if ( class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
		$my_update_checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
			'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=vk-filter-search-pro',
			__FILE__,
			'vk-filter-search-pro'
		);
	}

	// Load Modules
	require_once plugin_dir_path( __FILE__ ) . 'inc/patches/config.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/auto-count/auto-count.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/dropdown-categories/dropdown-categories.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/input-categories/input-categories.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/filter-search/config.php';
	require_once plugin_dir_path( __FILE__ ) . 'inc/filter-search-pro/config.php';
}
