<?php
/**
 * VK Walker Category Input
 *
 * @package VK Input Categories
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'VK_Walker_Category_Input' ) ) {
	/**
	 * VK Walker Category Input
	 */
	class VK_Walker_Category_Input extends Walker {

		/**
		 * What the class handles.
		 *
		 * @since 2.1.0
		 * @var string
		 *
		 * @see Walker::$tree_type
		 */
		public $tree_type = 'category';

		/**
		 * Database fields to use.
		 *
		 * @since 2.1.0
		 * @todo Decouple this
		 * @var array
		 *
		 * @see Walker::$db_fields
		 */
		public $db_fields = array(
			'parent' => 'parent',
			'id'     => 'term_id',
		);

		/**
		 * Starts the list before the elements are added.
		 *
		 * @since 2.1.0
		 *
		 * @see Walker::start_lvl()
		 *
		 * @param string $output Used to append additional content. Passed by reference.
		 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
		 * @param array  $args   Optional. An array of arguments. Will only append content if style argument
		 *                       value is 'list'. See wp_list_categories(). Default empty array.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$ul_class = 'vkfs__children';
			$indent   = str_repeat( "\t", $depth );
			$output  .= "$indent<ul class='$ul_class'>\n";
		}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @since 2.1.0
		 *
		 * @see Walker::end_lvl()
		 *
		 * @param string $output Used to append additional content. Passed by reference.
		 * @param int    $depth  Optional. Depth of category. Used for tab indentation. Default 0.
		 * @param array  $args   Optional. An array of arguments. Will only append content if style argument
		 *                       value is 'list'. See wp_list_categories(). Default empty array.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent  = str_repeat( "\t", $depth );
			$output .= "$indent</ul>\n";
		}

		/**
		 * Starts the element output.
		 *
		 * @since 2.1.0
		 *
		 * @see Walker::start_el()
		 *
		 * @param string  $output   Used to append additional content (passed by reference).
		 * @param WP_Term $data_object Category data object.
		 * @param int     $depth    Depth of category. Used for padding.
		 * @param array   $args     Uses 'checked', 'show_count', and 'value_field' keys, if they exist.
		 *                          See wp_dropdown_categories().
		 * @param int     $current_object_id       Optional. ID of the current category. Default 0 (unused).
		 */
		public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {
			$pad  = str_repeat( '&nbsp;', $depth * 3 );
			$name = esc_attr( $args['name'] );
			$type = $args['type'];

			/** This filter is documented in wp-includes/category-template.php */
			$cat_name = apply_filters( 'list_cats', $data_object->name, $data_object );

			if ( isset( $args['value_field'] ) && isset( $data_object->{$args['value_field']} ) ) {
				$value_field = $args['value_field'];
			} else {
				$value_field = 'term_id';
			}

			$li_class = 'vkfs__level-' . $depth;
			$li_class .= ' termid-' . $data_object->term_id;

			$children = get_terms(
				$data_object->taxonomy,
				array(
					'parent'     => $data_object->term_id,
					'hide_empty' => false,
				)
			);

			if ( ! empty( $children ) ) {
				$li_class .= ' ' . 'vkfs__has-children';
			}

			$output .= "\t<li class=\"$li_class\"><label><input type='$type' name='$name' value=\"" . esc_attr( urldecode( $data_object->{$value_field} ) ) . '"';

			// Type-juggling causes false matches, so we force everything to a string.
			if ( (string) $data_object->{$value_field} === (string) $args['checked'] ) {
				$output .= ' checked="checked"';
			}
			$output .= '>';
			$output .= $cat_name;
			if ( $args['show_count'] ) {
				if ( $args['auto_count'] ) {
					// タクソノミーに応じて適切なクエリのキーに変換
					$count_name = $data_object->taxonomy;
					// 該当フォームの値
					$count_value = esc_attr( urldecode( $data_object->{$value_field} ) );

					$multi_select = $type === 'checkbox' ? true : false;

					$post_type = $args['post_type'];

					// AND 検索か OR 検索か
					$count_operator = 'checkbox' === $args['type'] ? $args['operator'] : null;

					// 上記に基づいた投稿数を取得して表示
					$output .= '&nbsp;&nbsp;(' . number_format_i18n( vkfs_auto_count( $count_name, $count_value, $multi_select, $post_type, $count_operator ) ) . ')';
				} else {
					$output .= '&nbsp;&nbsp;(' . number_format_i18n( $data_object->count ) . ')';
				}
			}
			$output .= "</label>\n";
		}

		/**
		 * Ends the element output, if needed.
		 *
		 * @since 2.1.0
		 *
		 * @see Walker::end_el()
		 *
		 * @param string $output        Used to append additional content (passed by reference).
		 * @param object $data_object   Not used.
		 * @param int    $depth         Optional. Depth of category. Not used.
		 * @param array  $args          Optional. An array of arguments. Only uses 'list' for whether should append
		 *                              to output. See wp_list_categories(). Default empty array.
		 */
		public function end_el( &$output, $data_object, $depth = 0, $args = array() ) {
			$output .= "</li>\n";
		}
	}
}