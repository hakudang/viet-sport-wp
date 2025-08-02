<?php
/**
 * Register Custom Field Search Pro Block
 *
 * @package VK Filter Search Pro
 */

/**
 * Register the `vk-filter-search--pro/custom-field-search-pro` block.
 */
function register_block_vkfs_cudtom_field_search_pro() {
	register_block_type(
		__DIR__,
		array(
			'render_callback' => 'vkfs_cudtom_field_search_pro_render_callback',
		)
	);
}
add_action( 'init', 'register_block_vkfs_cudtom_field_search_pro', 99 );

/**
 * Rendering Filter Search Block
 *
 * @param array $attributes attributes.
 * @param html  $content content.
 */
function vkfs_cudtom_field_search_pro_render_callback( $attributes, $content ) {
	$attributes = wp_parse_args(
		$attributes,
		array(
			'fieldType'      => 'numeric',
			'fieldCompare'   => 'equal',
			'outerColumnXs'  => '12',
			'outerColumnSm'  => '12',
			'outerColumnMd'  => '6',
			'outerColumnLg'  => '6',
			'outerColumnXl'  => '6',
			'outerColumnXxl' => '6',
		)
	);

	$options = VK_Filter_Search::get_options();
	if ( ! empty( $attributes['fieldName'] ) ) {
		if ( ! empty( $options['custom_fields'] ) && is_array( $options['custom_fields'] ) ) {
			$options['custom_fields'][] = $attributes['fieldName'];
			$options['custom_fields']   = array_unique( $options['custom_fields'] );
			$options['custom_fields']   = array_values( $options['custom_fields'] );
		} else {
			$options['custom_fields'] = array( $attributes['fieldName'] );
		}
	}

	update_option( 'vk_filter_search', $options );
	$content .= VK_Filter_Search::get_column_dynamic_style( $attributes );
	return $content;
}

/**
 * Custom Field Query Vars
 *
 * @param string[] The array of allowed query variable names.
 */
function vkfs_cudtom_field_query_vars( $public_query_vars ) {
	$options = VK_Filter_Search::get_options();
	if ( ! empty( $options['custom_fields'] ) ) {
		$custom_fields = $options['custom_fields'];
		if ( ! empty( $custom_fields ) && is_array( $custom_fields ) ) {
			foreach ( $custom_fields as $custom_field ) {
				$public_query_vars[] = $custom_field . '_numeric_equal';
				$public_query_vars[] = $custom_field . '_numeric_min';
				$public_query_vars[] = $custom_field . '_numeric_max';
				$public_query_vars[] = $custom_field . '_date_equal';
				$public_query_vars[] = $custom_field . '_date_before';
				$public_query_vars[] = $custom_field . '_date_after';
				$public_query_vars[] = $custom_field . '_datetime_equal';
				$public_query_vars[] = $custom_field . '_datetime_before';
				$public_query_vars[] = $custom_field . '_datetime_after';
				$public_query_vars[] = $custom_field . '_time_equal';
				$public_query_vars[] = $custom_field . '_time_before';
				$public_query_vars[] = $custom_field . '_time_after';
				$public_query_vars[] = $custom_field . '_value';
				$public_query_vars[] = $custom_field . '_value_min';
				$public_query_vars[] = $custom_field . '_value_max';
				$public_query_vars[] = $custom_field . '_range_min';
				$public_query_vars[] = $custom_field . '_range_max';
			}
		}
	}
	return $public_query_vars;
}
add_filter( 'query_vars', 'vkfs_cudtom_field_query_vars' );

/**
 * Custom Field Query Vars
 *
 * @param WP_Query The WP_Query instance (passed by reference).
 */
function vkfs_cudtom_field_pre_get_posts( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ! isset( $_GET['vkfs_submitted'] ) ) {
		$options = VK_Filter_Search::get_options();
		if ( ! empty( $options['custom_fields'] ) ) {
			$custom_fields = $options['custom_fields'];
			$meta_query    = array(
				'relation' => 'AND',
			);
			foreach ( $custom_fields as $custom_field ) {
				// 数値
				if ( $value = $query->get( $custom_field . '_numeric_equal' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '=',
						'type'    => 'NUMERIC',
					);
				}
				if ( $value = $query->get( $custom_field . '_numeric_min' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '>=',
						'type'    => 'NUMERIC',
					);
				}
				if ( $value = $query->get( $custom_field . '_numeric_max' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '<=',
						'type'    => 'NUMERIC',
					);
				}
				// 日付
				if ( $value = $query->get( $custom_field . '_date_equal' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '=',
						'type'    => 'DATE',
					);
				}
				if ( $value = $query->get( $custom_field . '_date_before' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '<=',
						'type'    => 'DATE',
					);
				}
				if ( $value = $query->get( $custom_field . '_date_after' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '>=',
						'type'    => 'DATE',
					);
				}
				// 日時
				if ( $value = $query->get( $custom_field . '_datetime_equal' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '=',
						'type'    => 'DATETIME',
					);
				}
				if ( $value = $query->get( $custom_field . '_datetime_before' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '<=',
						'type'    => 'DATETIME',
					);
				}
				if ( $value = $query->get( $custom_field . '_datetime_after' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '>=',
						'type'    => 'DATETIME',
					);
				}
				// 時間
				if ( $value = $query->get( $custom_field . '_time_equal' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '=',
						'type'    => 'TIME',
					);
				}
				if ( $value = $query->get( $custom_field . '_time_before' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '<=',
						'type'    => 'TIME',
					);
				}
				if ( $value = $query->get( $custom_field . '_time_after' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '>=',
						'type'    => 'TIME',
					);
				}
				if ( $value = $query->get( $custom_field . '_value' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '=',
						'type'    => 'NUMERIC',
					);
				}
				if ( $value = $query->get( $custom_field . '_value_min' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '>=',
						'type'    => 'NUMERIC',
					);
				}
				if ( $value = $query->get( $custom_field . '_value_max' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '<=',
						'type'    => 'NUMERIC',
					);
				}
				if ( $value = $query->get( $custom_field . '_range_min' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '>=',
						'type'    => 'NUMERIC',
					);
				}
				if ( $value = $query->get( $custom_field . '_range_max' ) ) {
					$meta_query[] = array(
						'key'     => $custom_field,
						'value'   => $value,
						'compare' => '<=',
						'type'    => 'NUMERIC',
					);
				}
			}

			if ( ! empty( $meta_query ) ) {
				$query->set( 'meta_query', $meta_query );
			}
		}
	}
}
add_action( 'pre_get_posts', 'vkfs_cudtom_field_pre_get_posts' );
