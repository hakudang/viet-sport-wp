<?php

/**
 * Registers the `vk-filter-search-pro/taxonomy-search-pro` block.
 */
if ( function_exists( 'register_block_type' ) ) {

	function register_block_vkfs_taxonomy_search_pro() {
		register_block_type(
			__DIR__,
			array(
				'render_callback' => 'vkfs_taxonomy_search_pro_render_callback',
			)
		);
	}
	add_action( 'init', 'register_block_vkfs_taxonomy_search_pro', 9999 );
}


/**
 * Rendering Taxonomy Search Block
 *
 * @param array $attributes attributes.
 * @param html  $content content.
 */
function vkfs_taxonomy_search_pro_render_callback( $attributes, $content ) {

	// 設定項目を処理
	$attributes = wp_parse_args(
		$attributes,
		array(
			'TargetPostType'         => '',
			'isSelectedTaxonomy'     => 'category',
			'BlockLabel'             => '',
			'isSelectedDesign'       => 'select',
			'nonSelectedLabel'       => '',
			'isSelectedOperator'     => 'or',
			'accordionType'          => 'none',
			'enableChildDropdwon'    => false,
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
			'showCount'              => false,
			'autoCount'              => false,
			'hideEmpty'              => true,
			'className'              => '',
			'innerColumnWidthMethod' => 'column',
			'innerColumnWidthMin'    => null,
			'outerColumnWidthMethod' => null,
			'outerColumnWidthMin'    => null,
			'blockId'                => null,
		)
	);

	// コンテンツは初期化
	$content = '';

	// タクソノミーを処理
	$taxonomy = ! empty( $attributes['isSelectedTaxonomy'] ) ? $attributes['isSelectedTaxonomy'] : '';

	// タクソノミーの構造体が存在している場合はそのタクソノミーのフォームをコンテンツに反映
	if ( ! empty( get_taxonomy( $taxonomy ) ) ) {

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
			'label'                  => ! empty( $attributes['BlockLabel'] ) ? $attributes['BlockLabel'] : get_taxonomy( $taxonomy )->labels->singular_name,
			'form_design'            => ! empty( $attributes['isSelectedDesign'] ) ? $attributes['isSelectedDesign'] : 'select',
			'non_selected_label'     => ! empty( $attributes['nonSelectedLabel'] ) ? $attributes['nonSelectedLabel'] : '',
			'post_type'              => ! empty( $attributes['TargetPostType'] ) ? $attributes['TargetPostType'] : '',
			'operator'               => ! empty( $attributes['isSelectedOperator'] ) ? $attributes['isSelectedOperator'] : 'or',
			'enable_child_dropdown'  => ! empty( $attributes['enableChildDropdwon'] ) ? $attributes['enableChildDropdwon'] : false,
			'show_count'             => ! empty( $attributes['showCount'] ) ? $attributes['showCount'] : false,
			'auto_count'             => ! empty( $attributes['autoCount'] ) ? $attributes['autoCount'] : false,
			'hide_empty'             => ! isset( $attributes['hideEmpty'] ) ? true : $attributes['hideEmpty'],
			'accordion_type'         => ! empty( $attributes['accordionType'] ) ? $attributes['accordionType'] : 'none',
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

		$content .= VK_Filter_Search::get_taxonomy_form_html( $taxonomy, $options );
	}

	return $content;
}

function vkfs_ajax_get_child_categories() {
	check_ajax_referer( 'category_nonce', 'nonce' );
	$patrnt_name  = $_POST['parent_name'];
	$parent_value = $_POST['parent_value'];
	$parent_class = $_POST['parent_class'];
	$option_first = $_POST['option_first'];
	$settings     = json_decode( str_replace( '\\', '', $_POST['container_settings'] ), true );
	$dropdown = '';

	if ( ! empty( $parent_value ) ) {		
		$child_of = 0;
		if ( 'vkfs_category[]' === $patrnt_name ) {
			$taxonomy = 'category';
		} elseif ( 'vkfs_post_tag[]' === $patrnt_name ) {
			$taxonomy = 'post_tag';
		} else {
			$taxonomy = str_replace( 'vkfs_', '', $patrnt_name );
			$taxonomy = str_replace( '[]', '', $taxonomy );
		}

		$child_of = get_term_by( 'slug', urlencode( $parent_value ), $taxonomy )->term_id;
		
		$terms    = get_terms(
			array(
				'taxonomy' => $taxonomy,
				'child_of' => $child_of,
			)
		);

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$dropdown_args = array(
				'show_option_none'  => $option_first,
				'option_none_value' => '',
				'class'             => $parent_class,
				'child_of'          => $child_of,
				'post_type'         => $settings['post_type'],
				'taxonomy'          => $taxonomy,
				'operator'          => $settings['operator'],
				'show_count'        => $settings['show_count'],
				'auto_count'        => $settings['auto_count'],
				'hide_empty'        => $settings['hide_empty'],
				'echo'              => false,
				'name'              => $patrnt_name, // 継承される名前属性
				'value_field'       => 'slug',
				'hierarchical'      => true,
				'depth'             => 1,
			);
			$dropdown      = vk_dropdown_categories( $dropdown_args );
		}
	}
	if ( ! empty( $dropdown ) ) {
		wp_send_json_success( $dropdown );
	} else {
		wp_send_json_error( 'No children found' );
	}
}
add_action( 'wp_ajax_get_child_categories', 'vkfs_ajax_get_child_categories' );
add_action( 'wp_ajax_nopriv_get_child_categories', 'vkfs_ajax_get_child_categories' );

function vkfs_taxonomy_enqueue_block_editor_assets() {
	wp_localize_script(
		'vk-filter-search-pro-block',
		'ajax_object',
		array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'category_nonce' ),
		)
	);
}
add_action( 'enqueue_block_editor_assets', 'vkfs_taxonomy_enqueue_block_editor_assets' );
