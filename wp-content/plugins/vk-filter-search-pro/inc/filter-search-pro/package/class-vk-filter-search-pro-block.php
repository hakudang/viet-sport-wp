<?php
/**
 * VK Fiter Search block
 *
 * @package VK Filter Search
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'VK_Filter_Search_Pro_Block' ) ) {
	/**
	 * VK Filter Search Block
	 */
	class VK_Filter_Search_Pro_Block {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'vkfs_block_data', array( __CLASS__, 'add_block_data' ) );
			add_action( 'init', array( __CLASS__, 'register_block' ), 11 );
			add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'set_block_data' ) );
		}

		/**
		 * ブロックに必要なデータをキャッシュしておく
		 */
		public static function add_block_data( $data ) {

			global $wpdb;
			$field_result = $wpdb->get_results(
				'SELECT DISTINCT meta_key
				FROM ' . $wpdb->prefix . 'postmeta
				ORDER BY meta_key',
				ARRAY_A
			);

			$field_list = array();
			foreach ( $field_result as $field ) {
				if (
					strpos( urlencode( $field['meta_key'] ), '%' ) === false &&
					strpos( urlencode( $field['meta_key'] ), '+' ) === false &&
					strpos( urlencode( $field['meta_key'] ), '.' ) === false
				) {
					$field_list[] = $field['meta_key'];
				}
			}

			$data['custom_field'] = $field_list;

			return $data;
		}

		/**
		 * VK Filter Search Block
		 */
		public static function register_block() {

			$script_dependencies = include plugin_dir_path( __FILE__ ) . '/build/block.asset.php';

			wp_register_style(
				'vk-filter-search-pro-editor',
				VKFS_PRO_MODULE_ROOT_URL . 'build/editor.css',
				array( 'flatpickr' ),
				VKFS_PRO_MODULE_VERSION
			);

			wp_register_style(
				'vk-filter-search-pro-style',
				VKFS_PRO_MODULE_ROOT_URL . 'build/style.css',
				array( 'flatpickr' ),
				VKFS_PRO_MODULE_VERSION
			);

			wp_register_script(
				'vk-filter-search-pro-block',
				VKFS_PRO_MODULE_ROOT_URL . 'build/block.js',
				$script_dependencies['dependencies'],
				VKFS_PRO_MODULE_VERSION,
				true
			);

			$block_array = array(
				'filter-search-pro',
				'keyword-search-pro',
				'post-date-search-pro',
				'post-type-search-pro',
				'taxonomy-search-pro',
				'custom-field-search-pro',
				'search-result-single-order',
			);

			foreach ( $block_array as $block ) {
				require_once plugin_dir_path( __FILE__ ) . 'src/' . $block . '/index.php';
			}
		}

			/**
			 * Set Block Data
			 */
		public static function set_block_data() {
			// ブロックデータを取得
			$block_data = VK_Filter_Search_Block::get_block_data();

			// ブロックに値を渡す
			wp_localize_script(
				'vk-filter-search-pro-block',
				'vk_filter_search_pro_params',
				array(
					'home_url'                   => home_url( '/' ),
					'post_type_checkbox'         => $block_data['post_type_checkbox'],
					'post_type_select'           => $block_data['post_type_select'],
					'post_type_archive_checkbox' => $block_data['post_type_archive_checkbox'],
					'taxonomy_list'              => $block_data['taxonomy_list'],
					'taxonomy_option'            => $block_data['taxonomy_option'],
					'customFieldList'            => $block_data['custom_field'],
					'isBlockTheme'               => VK_Filter_Search_Block::is_block_theme(),
				)
			);
		}
	}
	new VK_Filter_Search_Pro_Block();
}
