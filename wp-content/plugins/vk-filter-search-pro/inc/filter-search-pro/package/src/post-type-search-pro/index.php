<?php

/**
 * Registers the `vk-filter-search-pro/post-type-search-pro` block.
 */
if ( function_exists( 'register_block_type' ) ) {

	function register_block_vkfs_post_type_search_pro() {
		register_block_type(
			__DIR__,
			array(
				'render_callback' => 'vkfs_post_type_search_pro_render_callback',
			)
		);
	}
	add_action( 'init', 'register_block_vkfs_post_type_search_pro', 9999 );
}

/**
 * Rendering Post Type Search Block
 *
 * @param array $attributes attributes.
 * @param html  $content content.
 */
function vkfs_post_type_search_pro_render_callback( $attributes, $content ) {
	$attributes = wp_parse_args(
		$attributes,
		array(
			'isCheckedPostType'      => '["post","page"]',
			'BlockLabel'             => '',
			'PostLabel'              => '',
			'PageLabel'              => '',
			'isSelectedDesign'       => 'select',
			'outerColumnXs'          => '12',
			'outerColumnSm'          => '12',
			'outerColumnMd'          => '6',
			'outerColumnLg'          => '6',
			'outerColumnXl'          => '6',
			'outerColumnXxl'         => '6',
			'innerColumnXs'          => '12',
			'innerColumnSm'          => '12',
			'innerColumnMd'          => '6',
			'innerColumnLg'          => '6',
			'innerColumnXl'          => '6',
			'innerColumnXxl'         => '6',
			'className'              => '',
			'innerColumnWidthMethod' => 'column',
			'innerColumnWidthMin'    => null,
			'outerColumnWidthMethod' => null,
			'outerColumnWidthMin'    => null,
			'blockId'                => null,
		)
	);

	if ( ! empty( $attributes['isCheckedPostType'] ) ) {
		$attributes['isCheckedPostType'] = str_replace( '[', '', $attributes['isCheckedPostType'] );
		$attributes['isCheckedPostType'] = str_replace( ']', '', $attributes['isCheckedPostType'] );
		$attributes['isCheckedPostType'] = str_replace( '"', '', $attributes['isCheckedPostType'] );
	}

	// 投稿タイプを処理
	$post_types = ! empty( $attributes['isCheckedPostType'] ) ? explode( ',', $attributes['isCheckedPostType'] ) : array();

	// インナーのカラム数を処理
	$inner_col_xs  = ! empty( $attributes['innerColumnXs'] ) ? $attributes['innerColumnXs'] : '';
	$inner_col_sm  = ! empty( $attributes['innerColumnSm'] ) ? $attributes['innerColumnSm'] : '';
	$inner_col_md  = ! empty( $attributes['innerColumnMd'] ) ? $attributes['innerColumnMd'] : '';
	$inner_col_lg  = ! empty( $attributes['innerColumnLg'] ) ? $attributes['innerColumnLg'] : '';
	$inner_col_xl  = ! empty( $attributes['innerColumnXl'] ) ? $attributes['innerColumnXl'] : '';
	$inner_col_xxl = ! empty( $attributes['innerColumnXxl'] ) ? $attributes['innerColumnXxl'] : '';

	// オプションを設定
	$options = array(
		'class_name'             => ! empty( $attributes['className'] ) ? $attributes['className'] : '',
		'label'                  => ! empty( $attributes['BlockLabel'] ) ? $attributes['BlockLabel'] : __( 'Post Type', 'vk-filter-search-pro' ),
		'post_label'             => ! empty( $attributes['PostLabel'] ) ? $attributes['PostLabel'] : get_post_type_object( 'post' )->labels->singular_name,
		'page_label'             => ! empty( $attributes['PageLabel'] ) ? $attributes['PageLabel'] : get_post_type_object( 'page' )->labels->singular_name,
		'form_design'            => ! empty( $attributes['isSelectedDesign'] ) ? $attributes['isSelectedDesign'] : 'select',
		'outer_columns'          => VK_Filter_Search_Pro::get_outer_columns_array( $attributes ),
		'inner_columns'          => array(
			'xs'  => $inner_col_xs,
			'sm'  => $inner_col_sm,
			'md'  => $inner_col_md,
			'lg'  => $inner_col_lg,
			'xl'  => $inner_col_xl,
			'xxl' => $inner_col_xxl,
		),
		'innerColumnWidthMethod' => $attributes['innerColumnWidthMethod'],
		'innerColumnWidthMin'    => $attributes['innerColumnWidthMin'],
		'outerColumnWidthMethod' => $attributes['outerColumnWidthMethod'],
		'outerColumnWidthMin'    => $attributes['outerColumnWidthMin'],
		'blockId'                => $attributes['blockId'],
	);

	return VK_Filter_Search::get_post_type_form_html( $post_types, $options );
}
