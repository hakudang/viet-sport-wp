<?php
/**
 * VK Filter Search Config
 *
 * @package VK Filter Search
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if (
	! class_exists( 'VK_Filter_Search_Pro' ) &&
	! class_exists( 'VK_Filter_Search_Block_Pro' ) &&
	! class_exists( 'VK_Filter_Search_Shortcode_Pro' ) &&
	! class_exists( 'VK_Filter_Search_Pro_Title' )
) {
	// Define Pro Module Root Path
	define( 'VKFS_PRO_MODULE_ROOT_PATH', plugin_dir_path( __FILE__ ) . 'package/' );
	// Define Pro Module Root URL
	define( 'VKFS_PRO_MODULE_ROOT_URL', plugin_dir_url( __FILE__ ) . 'package/' );
	// Define Plugin Version
	define( 'VKFS_PRO_MODULE_VERSION', VKFS_PLUGIN_VERSION );

	// 読み込むファイルを調整.
	require_once __DIR__ . '/package/class-vk-filter-search-pro.php';
	require_once __DIR__ . '/package/class-vk-filter-search-pro-block.php';
	require_once __DIR__ . '/package/class-vk-filter-search-pro-shortcode.php';
	require_once __DIR__ . '/package/class-vk-filter-search-pro-title.php';	
}


if ( ! function_exists( 'vkfs_pro_set_script_translations' ) ) {
	/**
	 * テキストドメインの設定
	 */
	function vkfs_pro_set_script_translations() {
		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'vk-filter-search-pro-block', 'vk-filter-search-pro', VKFS_PLUGIN_ROOT_PATH . '/languages/' );
		}
	}
	add_action( 'init', 'vkfs_pro_set_script_translations', 11 );
}
