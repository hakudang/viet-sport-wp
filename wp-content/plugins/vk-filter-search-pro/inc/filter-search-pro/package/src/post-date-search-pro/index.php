<?php
/**
 * Register Custom Field Search Pro Block
 *
 * @package VK Filter Search Pro
 */

/**
 * Register the `vk-filter-search-pro/post-date-search-pro` block.
 */
function vkfs_register_block_post_date_search_pro() {
	$options        = VK_Filter_Search::get_options();
	$future_disable = ! empty( $options['future_disable'] ) ? json_encode( $options['future_disable'] ) : '[]';

	register_block_type(
		__DIR__,
		array(
			'render_callback' => 'vkfs_post_date_search_pro_render_callback',
		)
	);

	// ブロックに値を渡す
	wp_localize_script(
		'vk-filter-search-pro-block',
		'vkPostDateSearchPro',
		array(
			'futurePostDisable' => $future_disable,
		)
	);
}
add_action( 'init', 'vkfs_register_block_post_date_search_pro', 9999 );

/**
 * Rendering Filter Search Block
 *
 * @param array $attributes attributes.
 * @param html  $content content.
 */
function vkfs_post_date_search_pro_render_callback( $attributes, $content ) {
	$attributes = wp_parse_args(
		$attributes,
		array(
			'dateName'              => 'post_date',
			'dateCompare'           => 'equal',
			'blockLabel'            => __( 'Post Date', 'vk-filter-search-pro' ),
			'rangeBetween'          => '～',
			'outerColumnXs'         => '12',
			'outerColumnSm'         => '12',
			'outerColumnMd'         => '6',
			'outerColumnLg'         => '6',
			'outerColumnXl'         => '6',
			'outerColumnXxl'        => '6',
			'disableFuturePostType' => '[]',
		)
	);
	$post_types = json_decode( $attributes['disableFuturePostType'], true );

	$options = VK_Filter_Search::get_options();
	if ( ! empty( $post_types ) ) {
		$options['future_disable'] = $post_types;
	} else {
		unset( $options['future_disable'] );
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
function vkfs_date_query_vars( $public_query_vars ) {

	$date_array = array(
		'post_date',
		'post_modified',
	);
	foreach ( $date_array as $date ) {
		$public_query_vars[] = $date . '_date_equal';
		$public_query_vars[] = $date . '_date_before';
		$public_query_vars[] = $date . '_date_after';
		$public_query_vars[] = $date . '_value';
		$public_query_vars[] = $date . '_value_before';
		$public_query_vars[] = $date . '_value_after';
		$public_query_vars[] = $date . '_range_before';
		$public_query_vars[] = $date . '_range_after';
	}
	return $public_query_vars;
}
add_filter( 'query_vars', 'vkfs_date_query_vars' );

/**
 * Custom Field Query Vars
 *
 * @param WP_Query The WP_Query instance (passed by reference).
 */
function vkfs_date_pre_get_posts( $query ) {
	if ( ! is_admin() && $query->is_main_query() && ! isset( $_GET['vkfs_submitted'] ) ) {
		$date_array = array(
			'post_date',
			'post_modified',
		);
		$date_query = array(
			'relation' => 'AND',
		);
		foreach ( $date_array as $date ) {
			if ( $value = $query->get( $date . '_date_equal' ) ) {
				$date_query[] = array(
					'column'    => $date,
					'before'    => $value,
					'after'     => $value,
					'inclusive' => true,
				);
			}
			if ( $value = $query->get( $date . '_date_before' ) ) {
				$date_query[] = array(
					'column'    => $date,
					'before'    => $value,
					'inclusive' => true,
				);
			}
			if ( $value = $query->get( $date . '_date_after' ) ) {
				$date_query[] = array(
					'column'    => $date,
					'after'     => $value,
					'inclusive' => true,
				);
			}
			if ( $value = $query->get( $date . '_value' ) ) {
				$date_query[] = array(
					'column'    => $date,
					'before'    => $value,
					'after'     => $value,
					'inclusive' => true,
				);
			}
			if ( $value = $query->get( $date . '_value_before' ) ) {
				$date_query[] = array(
					'column'    => $date,
					'before'    => $value,
					'inclusive' => true,
				);
			}
			if ( $value = $query->get( $date . '_value_after' ) ) {
				$date_query[] = array(
					'column'    => $date,
					'after'     => $value,
					'inclusive' => true,
				);
			}
			if ( $value = $query->get( $date . '_range_before' ) ) {
				$date_query[] = array(
					'column'    => $date,
					'before'    => $value,
					'inclusive' => true,
				);
			}
			if ( $value = $query->get( $date . '_range_after' ) ) {
				$date_query[] = array(
					'column'    => $date,
					'after'     => $value,
					'inclusive' => true,
				);
			}
		}

		if ( ! empty( $date_query ) ) {
			$query->set( 'date_query', $date_query );
		}
	}
}
add_action( 'pre_get_posts', 'vkfs_date_pre_get_posts' );

function vkfs_disable_future_posts( $data ) {
	$options = VK_Filter_Search::get_options();
	if ( ! empty( $options['future_disable'] ) && is_array( $options['future_disable'] ) ) {
		foreach ( $options['future_disable'] as $post_type ) {
			if ( $post_type === $data['post_type'] && 'future' === $data['post_status'] ) {
				$data['post_status'] = 'publish';
			}
		}
	}
	return $data;
}
add_filter( 'wp_insert_post_data', 'vkfs_disable_future_posts' );

/**
 * Enqueue Video Unit Script
 */
function vkfs_enqueue_date_script() {

	$flatpickr_url     = VKFS_PLUGIN_ROOT_URL . 'library/flatpickr/';
	$flatpickr_path    = VKFS_PLUGIN_ROOT_PATH . 'library/flatpickr/';
	$flatpickr_version = '4.6.9';

	$locale = get_locale();
	wp_enqueue_style(
		'flatpickr',
		$flatpickr_url . 'flatpickr.min.css',
		array(),
		$flatpickr_version
	);
	wp_enqueue_script(
		'flatpickr',
		$flatpickr_url . 'flatpickr.min.js',
		array(),
		$flatpickr_version,
		true
	);
	if ( file_exists( $flatpickr_path . 'l10n/' . $locale . '.js' ) ) {
		wp_enqueue_script(
			'flatpickr-' . $locale,
			$flatpickr_url . 'l10n/' . $locale . '.js',
			array( 'flatpickr' ),
			$flatpickr_version,
			true
		);
	} else {
		wp_enqueue_script(
			'flatpickr-' . $locale,
			$flatpickr_url . 'l10n/default.js',
			array( 'flatpickr' ),
			$flatpickr_version,
			true
		);
		$locale = 'default';
	}
	wp_enqueue_script(
		'vk-filter-search-date',
		VKFS_PRO_MODULE_ROOT_URL . 'build/vk-filter-search-pro-date.min.js',
		array( 'flatpickr' ),
		VKFS_PRO_MODULE_VERSION,
		true
	);
	// ブロックに値を渡す
	wp_localize_script(
		'vk-filter-search-date',
		'vkfsDateParams',
		array(
			'flatpickrLocaleUrl' => $flatpickr_url . 'l10n/',
			'locale'             => $locale,
		)
	);
}
add_action( 'enqueue_block_assets', 'vkfs_enqueue_date_script' );
add_action( 'enqueue_block_editor_assets', 'vkfs_enqueue_date_script' );
