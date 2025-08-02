<?php
/**
 * Register Custom Field Search Pro Block
 *
 * @package VK Filter Search Pro
 */

/**
 * Register the `vk-filter-search--pro/custom-field-search-pro` block.
 */
function vkfs_register_block_search_result_single_order() {
	register_block_type(
		__DIR__,
	);
}
add_action( 'init', 'vkfs_register_block_search_result_single_order', 99 );

/**
 * Custom Field Query Vars
 *
 * @param string[] The array of allowed query variable names.
 */
function vkfs_single_order_query_vars( $public_query_vars ) {
	$public_query_vars[] = 'vkfs_orderby';
	return $public_query_vars;
}
add_filter( 'query_vars', 'vkfs_single_order_query_vars' );

/**
 * Custom Field Query Vars
 *
 * @param WP_Query The WP_Query instance (passed by reference).
 */
function vkfs_single_order_pre_get_posts( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ! isset( $_GET['vkfs_submitted'] ) ) {
		if ( $query->get( 'vkfs_orderby' ) ) {
			$order_array = explode( '.', $query->get( 'vkfs_orderby' ) );
			if ( 'custom-field' === $order_array[0] ) {
				$meta_key  = $order_array[1];
				$order     = $order_array[2];
				$meta_type = $order_array[3];

				if ( 'CHAR' === $meta_type ) {
					$order_by = 'meta_value';
				} elseif ( 'NUMERIC' === $meta_type ) {
					$order_by = 'meta_value_num';
				} elseif ( 'DATE' === $meta_type ) {
					$order_by = 'meta_value_date';
				} elseif ( 'DATETIME' === $meta_type ) {
					$order_by = 'meta_value_datetime';
				} elseif ( 'TIME' === $meta_type ) {
					$order_by = 'meta_value_time';
				}
				$query->set( 'meta_key', $meta_key );
				$query->set( 'meta_type', $meta_type );
				$query->set( 'orderby', $order_by );
				$query->set( 'order', $order );
			} else {
				$order_by = $order_array[0];
				$order    = $order_array[1];
				$query->set( 'orderby', $order_by );
				$query->set( 'order', $order );
			}
		}
	}
}
add_action( 'pre_get_posts', 'vkfs_single_order_pre_get_posts' );
