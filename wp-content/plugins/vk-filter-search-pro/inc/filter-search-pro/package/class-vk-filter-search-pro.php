<?php
/**
 * VK Fiter Search Pro
 *
 * @package VK Filter Search Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'VK_Filter_Search_Pro' ) ) {
	/**
	 * VK Filter Search
	 */
	class VK_Filter_Search_Pro {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'vk_filter_search_form_style', array( __CLASS__, 'form_style_option' ) );
			add_filter( 'vk_search_filter_post_type_design_html', array( __CLASS__, 'post_type_design_html' ), 10, 3 );
			add_filter( 'vk_search_filter_taxonomy_design_html', array( __CLASS__, 'taxonomy_design_html' ), 10, 3 );
			add_filter( 'vkfs_header_scripts', array( __CLASS__, 'header_scripts' ) );
			add_action( 'vkfs_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

			// Fallback for theme editor
			add_action( 'admin_init', array( __CLASS__, 'enqueue_style_on_theme_editor' ) );
		}

		/**
		 * Fallback for theme editor
		 *
		 * @return void
		 */
		public static function enqueue_style_on_theme_editor() {
			wp_enqueue_style(
				'vk-filter-search-theme-pro-editor-style',
				VKFS_PRO_MODULE_ROOT_URL . 'build/style.css',
				array(),
				VKFS_PRO_MODULE_VERSION
			);
			wp_enqueue_style(
				'vk-filter-search-theme-pro-editor-editor-style',
				VKFS_PRO_MODULE_ROOT_URL . 'build/editor.css',
				array(),
				VKFS_PRO_MODULE_VERSION
			);
		}

		/**
		 * Form Stryle Option
		 *
		 * @param array $form_style_option Available Form Style.
		 */
		public static function form_style_option() {
			$form_style_option = array(
				'select',
				'checkbox',
				'radio',
			);
			return $form_style_option;
		}

		/**
		 * Get Outer Columns array
		 *
		 * @param array $attributes attributes.
		 */
		public static function get_outer_columns_array( $attributes = array() ) {
			$outer_columns_option = array(
				'xs'  => ! empty( $attributes['outerColumnXs'] ) ? $attributes['outerColumnXs'] : '',
				'sm'  => ! empty( $attributes['outerColumnSm'] ) ? $attributes['outerColumnSm'] : '',
				'md'  => ! empty( $attributes['outerColumnMd'] ) ? $attributes['outerColumnMd'] : '',
				'lg'  => ! empty( $attributes['outerColumnLg'] ) ? $attributes['outerColumnLg'] : '',
				'xl'  => ! empty( $attributes['outerColumnXl'] ) ? $attributes['outerColumnXl'] : '',
				'xxl' => ! empty( $attributes['outerColumnXxl'] ) ? $attributes['outerColumnXxl'] : '',
			);
			return $outer_columns_option;
		}

		public static function get_inner_column_width_method( $options = array() ) {
			if ( empty( $options['innerColumnWidthMethod'] ) ) {
				return 'column';
			} elseif ( 'minimum' === $options['innerColumnWidthMethod'] ) {
				return 'minimum';
			} else {
				return 'column';
			}
		}

		/**
		 * カラム制御用のクラス及びスタイル制御用のブロックidを取得
		 *
		 * @param array $options options.
		 * @return string $outer_classes
		 */
		public static function get_inner_column_class( $options = array() ) {
			$inner_classes = '';
			if ( 'column' === self::get_inner_column_width_method( $options ) ) {
				if ( ! empty( $options['inner_columns'] ) ) {
					foreach ( $options['inner_columns'] as $key => $value ) {
						if ( $value ) {
							$inner_classes .= ' vkfs__input-wrap--col-' . $key . '-' . $value;
						}
					}
				}
			} else {
				$inner_classes .= ' vkfs__input-wrap--layout-min';
			}
			return $inner_classes;
		}

		/**
		 * Post Type Design HTML
		 *
		 * @param string $post_type_design_html design HTML of post type filter.
		 * @param array  $post_types  filtering post types.
		 * @param array  $options options.
		 */
		public static function post_type_design_html( $post_type_design_html, $post_types, $options = array() ) {
			$li_class = 'vkfs_prefix__level-0';

			// 投稿タイプの調整.
			$post_types = ! empty( $post_types ) ? $post_types : array( 'post', 'page' );

			// オプションの値を調整
			$default = array(
				'class_name'    => '',
				'label'         => __( 'Post Type', 'vk-filter-search-pro' ),
				'post_label'    => get_post_type_object( 'post' )->labels->singular_name,
				'page_label'    => get_post_type_object( 'page' )->labels->singular_name,
				'form_design'   => 'select',
				'outer_columns' => array(),
				'inner_columns' => array(),
			);
			$options = wp_parse_args( $options, $default );

			// デザインの調整.
			$form_style_option = self::form_style_option();
			$form_design       = ! empty( $options['form_design'] ) && in_array( $options['form_design'], $form_style_option, true ) ? $options['form_design'] : 'select';

			$inner_classes = self::get_inner_column_class( $options );

			// 変数の初期化.
			$post_type_design_html  = '';
			$post_type_option_array = array();

			$post_type_name = 'vkfs_post_type[]';

			// 共通オプション.
			$default_option_array = array(
				array(
					'label' => __( 'Any', 'vk-filter-search-pro' ),
					'value' => 'any',
				),
			);

			foreach ( $post_types as $post_type ) {
				if ( ! empty( get_post_type_object( $post_type ) ) ) {
					if ( 'post' === $post_type ) {
						$post_type_option_array[] = array(
							'label' => $options['post_label'],
							'value' => $post_type,
						);
					} elseif ( 'page' === $post_type ) {
						$post_type_option_array[] = array(
							'label' => $options['page_label'],
							'value' => $post_type,
						);
					} else {
						$post_type_option_array[] = array(
							'label' => get_post_type_object( $post_type )->labels->singular_name,
							'value' => $post_type,
						);
					}
				}
			}

			// デザインに応じて切り替え開始.
			if ( 'select' === $form_design ) {

				// 配列を統合.
				$post_type_option_array = array_merge( $default_option_array, $post_type_option_array );

				// 描画開始.
				$post_type_design_html .= '<select class="vkfs__input-wrap vkfs__input-wrap--select vkfs__input-wrap--post_type" name="' . $post_type_name . '" id="post_type">';

				// 項目のループ.
				foreach ( $post_type_option_array as $post_type_option ) {
					$post_type_design_html .= '<option value="' . $post_type_option['value'] . '">' . $post_type_option['label'] . '</option>';
				}

				$post_type_design_html .= '</select>';
			} elseif ( 'checkbox' === $form_design ) {

				// 描画開始.
				$post_type_design_html .= '<ul class="vkfs__input-wrap vkfs__input-wrap--checkbox vkfs__input-wrap--post_type' . $inner_classes . '">';

				// 項目のループ.
				foreach ( $post_type_option_array as $post_type_option ) {
					$post_type_design_html .= '<li class="' . $li_class . '"><label>';
					$post_type_design_html .= '<input type="checkbox" name="' . $post_type_name . '" value="' . $post_type_option['value'] . '">';
					$post_type_design_html .= $post_type_option['label'];
					$post_type_design_html .= '</label></li>';
				}

				$post_type_design_html .= '</ul>';
			} elseif ( 'radio' === $form_design ) {

				// 配列を統合.
				$post_type_option_array = array_merge( $default_option_array, $post_type_option_array );

				// 描画開始.
				$post_type_design_html .= '<ul class="vkfs__input-wrap vkfs__input-wrap--radio vkfs__input-wrap--post_type' . $inner_classes . '">';

				// 項目のループ.
				foreach ( $post_type_option_array as $post_type_option ) {
					$post_type_design_html .= '<li class="' . $li_class . '"><label>';
					$post_type_design_html .= '<input type="radio" name="' . $post_type_name . '" value="' . $post_type_option['value'] . '">';
					$post_type_design_html .= $post_type_option['label'];
					$post_type_design_html .= '</label></li>';
				}

				$post_type_design_html .= '</ul>';
			}

			return $post_type_design_html;
		}

		/**
		 * Get Taxonomy Filter Design HTML
		 *
		 * @param string $taxonomy_design_html design of html.
		 * @param string $taxonomy             name of taxonomy.
		 * @param array  $options options.
		 */
		public static function taxonomy_design_html( $taxonomy_design_html, $taxonomy, $options = array() ) {

			// タクソノミーの調整.
			$taxonomy        = ! empty( $taxonomy ) ? $taxonomy : 'category';
			$taxonomy_object = get_taxonomy( $taxonomy );
			$taxonomy_terms  = get_terms( $taxonomy );

			// オプションの値を調整
			$default = array(
				'class_name'            => '',
				'label'                 => $taxonomy_object->labels->singular_name,
				'form_design'           => 'select',
				'non_selected_label'    => '',
				'post_type'             => '',
				'operator'              => 'or',
				'enable_child_dropdown' => false,
				'show_count'            => false,
				'auto_count'            => false,
				'hide_empty'            => true,
				'outer_columns'         => array(),
				'inner_columns'         => array(),
			);
			$options = wp_parse_args( $options, $default );

			// デザインの調整.
			$form_style_option = self::form_style_option();
			$form_design       = ! empty( $options['form_design'] ) && in_array( $options['form_design'], $form_style_option, true ) ? $options['form_design'] : 'select';

			$inner_classes = self::get_inner_column_class( $options );

			// 変数を初期化.
			$taxonomy_design_html = '';

			// デザインに応じて HTML を描画.
			// 共通の設定項目.
			$common_args = array(
				'show_option_none'  => ! empty( $options['non_selected_label'] ) ? $options['non_selected_label'] : __( 'Any', 'vk-filter-search-pro' ),
				'option_none_value' => '',
				'post_type'         => $options['post_type'],
				'operator'          => $options['operator'],
				'show_count'        => $options['show_count'],
				'auto_count'        => $options['auto_count'],
				'hide_empty'        => $options['hide_empty'],
				'echo'              => false,
				'taxonomy'          => $taxonomy,
				'value_field'       => 'slug',
			);

			// 共通かつカスタマイズの余地がある設定項目.
			$custom_args = array(
				'orderby'      => 'NAME',
				'order'        => 'ASC',
				'hierarchical' => true,
			);
			$custom_args = apply_filters( 'vkfs_taxonomy_custom_setting', $custom_args );

			// デザインに応じて HTML を描画.
			if ( 'select' === $form_design ) {
				if ( ! empty( $options['enable_child_dropdown'] ) ) {
					if ( 'category' === $taxonomy ) {
						$taxonomy_design_html = vk_dropdown_categories(
							array_merge(
								$common_args,
								$custom_args,
								array(
									'name'  => 'vkfs_category[]',
									'id'    => 'vkfs_category',
									'class' => 'vkfs__input-wrap vkfs__input-wrap--select vkfs__input-wrap--category_name vkfs__input-wrap--child-dropdown vkfs__depth-0',
									'depth' => 1,
								)
							)
						);
					} elseif ( 'post_tag' === $taxonomy ) {
						$taxonomy_design_html = vk_dropdown_categories(
							array_merge(
								$common_args,
								$custom_args,
								array(
									'name'  => 'vkfs_post_tag[]',
									'id'    => 'vkfs_post_tag',
									'class' => 'vkfs__input-wrap vkfs__input-wrap--select vkfs__input-wrap--tag vkfs__input-wrap--child-dropdown vkfs__depth-0',
									'depth' => 1,
								)
							)
						);
					} else {
						$taxonomy_design_html = vk_dropdown_categories(
							array_merge(
								$common_args,
								$custom_args,
								array(
									'name'  => 'vkfs_' . $taxonomy_object->name . '[]',
									'id'    => 'vkfs_' . $taxonomy_object->name,
									'class' => 'vkfs__input-wrap vkfs__input-wrap--select vkfs__input-wrap--' . $taxonomy_object->name . ' vkfs__input-wrap--child-dropdown vkfs__depth-0',
									'depth' => 1,
								)
							)
						);
					}
				} elseif ( 'category' === $taxonomy ) {
						$taxonomy_design_html = vk_dropdown_categories(
							array_merge(
								$common_args,
								$custom_args,
								array(
									'name'  => 'vkfs_category[]',
									'id'    => 'vkfs_category',
									'class' => 'vkfs__input-wrap vkfs__input-wrap--select vkfs__input-wrap--category_name',
								)
							)
						);
				} elseif ( 'post_tag' === $taxonomy ) {
					$taxonomy_design_html = vk_dropdown_categories(
						array_merge(
							$common_args,
							$custom_args,
							array(
								'name'  => 'vkfs_post_tag[]',
								'id'    => 'vkfs_post_tag',
								'class' => 'vkfs__input-wrap vkfs__input-wrap--select vkfs__input-wrap--tag',
							)
						)
					);
				} else {
					$taxonomy_design_html = vk_dropdown_categories(
						array_merge(
							$common_args,
							$custom_args,
							array(
								'name'  => 'vkfs_' . $taxonomy_object->name . '[]',
								'id'    => 'vkfs_' . $taxonomy_object->name,
								'class' => 'vkfs__input-wrap vkfs__input-wrap--select vkfs__input-wrap--' . $taxonomy_object->name,
							)
						)
					);
				}
			} elseif ( 'checkbox' === $form_design ) {
				if ( 'category' === $taxonomy ) {
					$taxonomy_design_html = vk_input_categories(
						array_merge(
							$common_args,
							$custom_args,
							array(
								'name'  => 'vkfs_category[]',
								'id'    => 'vkfs_category',
								'class' => 'vkfs__input-wrap vkfs__input-wrap--checkbox vkfs__input-wrap--category_name' . $inner_classes,
								'type'  => 'checkbox',
							)
						)
					);
				} elseif ( 'post_tag' === $taxonomy ) {
					$taxonomy_design_html = vk_input_categories(
						array_merge(
							$common_args,
							$custom_args,
							array(
								'name'  => 'vkfs_post_tag[]',
								'id'    => 'vkfs_post_tag',
								'class' => 'vkfs__input-wrap vkfs__input-wrap--checkbox vkfs__input-wrap--tag' . $inner_classes,
								'type'  => 'checkbox',
							)
						)
					);
				} else {
					$taxonomy_design_html = vk_input_categories(
						array_merge(
							$common_args,
							$custom_args,
							array(
								'name'  => 'vkfs_' . $taxonomy_object->name . '[]',
								'id'    => 'vkfs_' . $taxonomy_object->name,
								'class' => 'vkfs__input-wrap vkfs__input-wrap--checkbox vkfs__input-wrap--' . $taxonomy_object->name . $inner_classes,
								'type'  => 'checkbox',
							)
						)
					);
				}
			} elseif ( 'radio' === $form_design ) {
				if ( 'category' === $taxonomy ) {
					$taxonomy_design_html = vk_input_categories(
						array_merge(
							$common_args,
							$custom_args,
							array(
								'name'  => 'vkfs_category[]',
								'id'    => 'vkfs_category',
								'class' => 'vkfs__input-wrap vkfs__input-wrap--radio vkfs__input-wrap--category_name' . $inner_classes,
								'type'  => 'radio',
							)
						)
					);
				} elseif ( 'post_tag' === $taxonomy ) {
					$taxonomy_design_html = vk_input_categories(
						array_merge(
							$common_args,
							$custom_args,
							array(
								'name'  => 'vkfs_post_tag[]',
								'id'    => 'vkfs_post_tag',
								'class' => 'vkfs__input-wrap vkfs__input-wrap--radio vkfs__input-wrap--tag' . $inner_classes,
								'type'  => 'radio',
							)
						)
					);
				} else {
					$taxonomy_design_html = vk_input_categories(
						array_merge(
							$common_args,
							$custom_args,
							array(
								'name'  => 'vkfs_' . $taxonomy_object->name . '[]',
								'id'    => 'vkfs_' . $taxonomy_object->name,
								'class' => 'vkfs__input-wrap vkfs__input-wrap--radio vkfs__input-wrap--' . $taxonomy_object->name . $inner_classes,
								'type'  => 'radio',
							)
						)
					);
				}
			}
			return $taxonomy_design_html;
		}

		/**
		 * Header Scripts
		 */
		public static function header_scripts() {
			if ( isset( $_GET['vkfs_submitted'] ) ) {
				$header_script_params = array(
					'home_url' => VK_Filter_Search::get_search_root_url(),
				);

				$header_scripts  = '<script type="text/javascript" id="vk-filter-search-pro-redirct-js-extra">/* <![CDATA[ */ var vk_filter_search_params = ' . json_encode( $header_script_params ) . '; /* ]]> */</script>';
				$header_scripts .= '<script type="text/javascript" id="vk-filter-search-pro-redirct-js" src="' . VKFS_PRO_MODULE_ROOT_URL . 'build/vk-filter-search-pro-redirect.min.js?ver=' . VKFS_PRO_MODULE_VERSION . '"></script>';
				return $header_scripts;
			}
		}

		/**
		 * Enqueue Scripts
		 */
		public static function enqueue_scripts() {
			wp_deregister_script( 'vk-filter-search-result' );
			wp_enqueue_style(
				'vk-filter-search-pro-style',
				VKFS_PRO_MODULE_ROOT_URL . 'build/style.css',
				array( 'flatpickr' ),
				VKFS_PRO_MODULE_VERSION
			);
			wp_enqueue_script(
				'vk-filter-search-pro-taxonomy-dropdown',
				VKFS_PRO_MODULE_ROOT_URL . 'build/vk-filter-search-pro-taxonomy-dropdown.min.js',
				array(),
				VKFS_PRO_MODULE_VERSION,
				true
			);
			wp_localize_script(
				'vk-filter-search-pro-taxonomy-dropdown',
				'ajax_object',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'category_nonce' ),
				)
			);
			wp_enqueue_script(
				'vk-filter-search-pro-submit',
				VKFS_PRO_MODULE_ROOT_URL . 'build/vk-filter-search-pro-submit.min.js',
				array(),
				VKFS_PRO_MODULE_VERSION,
				true
			);

			wp_enqueue_script(
				'vk-filter-search-pro-taxonomy-accordion',
				VKFS_PRO_MODULE_ROOT_URL . 'build/vk-filter-search-pro-taxonomy-accordion.min.js',
				array(),
				VKFS_PRO_MODULE_VERSION,
				true
			);
			if ( ! isset( $_GET['vkfs_submitted'] ) ) {
				wp_enqueue_script(
					'vk-filter-search-pro-result',
					VKFS_PRO_MODULE_ROOT_URL . 'build/vk-filter-search-pro-result.min.js',
					array(),
					VKFS_PRO_MODULE_VERSION,
					true
				);
			}
		}
	}
	new VK_Filter_Search_Pro();
}
