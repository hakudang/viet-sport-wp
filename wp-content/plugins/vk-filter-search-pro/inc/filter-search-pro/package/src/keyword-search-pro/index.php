<?php

/**
 * Registers the `vk-filter-search/keyword-search-pro` block.
 */
if ( function_exists( 'register_block_type' ) ) {

	function register_block_vkfs_keyword_search_pro() {
		register_block_type(
			__DIR__,
			array(
				'render_callback' => 'vkfs_keyword_search_pro_render_callback',
			)
		);
	}
	add_action( 'init', 'register_block_vkfs_keyword_search_pro', 9999 );
}

/**
 * Rendering Keyword Search Block
 *
 * @param array $attributes attributes.
 * @param html  $content content.
 */
function vkfs_keyword_search_pro_render_callback( $attributes, $content ) {
	$attributes = wp_parse_args(
		$attributes,
		array(
			'className'              => '',
			'BlockLabel'             => '',
			'Placeholder'            => '',
			'outerColumnXs'          => '12',
			'outerColumnSm'          => '12',
			'outerColumnMd'          => '6',
			'outerColumnLg'          => '6',
			'outerColumnXl'          => '6',
			'outerColumnXxl'         => '6',
			'outerColumnWidthMethod' => 'column',
			'outerColumnWidthMin'    => null,
			'blockId'                => null,
		)
	);

	// オプションを設定
	$options = array(
		'class_name'             => ! empty( $attributes['className'] ) ? $attributes['className'] : '',
		'label'                  => ! empty( $attributes['BlockLabel'] ) ? $attributes['BlockLabel'] : __( 'Keyword', 'vk-filter-search-pro' ),
		'placeholder'            => ! empty( $attributes['Placeholder'] ) ? $attributes['Placeholder'] : __( 'Input Keyword', 'vk-filter-search-pro' ),
		'outer_columns'          => VK_Filter_Search_Pro::get_outer_columns_array( $attributes ),
		'outerColumnWidthMethod' => $attributes['outerColumnWidthMethod'],
		'outerColumnWidthMin'    => $attributes['outerColumnWidthMin'],
		'blockId'                => $attributes['blockId'],
	);

	return VK_Filter_Search::get_keyword_form_html( $options );
}
