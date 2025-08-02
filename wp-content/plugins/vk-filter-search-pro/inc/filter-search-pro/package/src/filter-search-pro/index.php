<?php

/**
 * Registers the `vk-filter-search-pro/filter-search-pro` block.
 */
if ( function_exists( 'register_block_type' ) ) {

	function register_block_vkfs_filter_search_pro() {
		register_block_type(
			__DIR__,
			array(
				'render_callback' => 'vkfs_filter_search_pro_render_callback',
			)
		);
	}
	add_action( 'init', 'register_block_vkfs_filter_search_pro', 9999 );
}

/**
 * Rendering Filter Search Block
 *
 * @param array $attributes attributes.
 * @param html  $content content.
 */
function vkfs_filter_search_pro_render_callback( $attributes, $content ) {
	$attributes = wp_parse_args(
		$attributes,
		array(
			'TargetPostType'           => '',
			'DisplayOnResult'          => false,
			'DisplayOnPosttypeArchive' => '[]',
			'SubmitText'               => '',
			'SubmitLetterSpacing'      => '',
			'SubmitPadding'            => array(
				'top'    => null,
				'right'  => null,
				'bottom' => null,
				'left'   => null,
			),
			'FormID'                   => null,
			'PostID'                   => null,
		)
	);

	// 多言語プラグイン等で検索結果の基準となる URL が変わる場合があるため action の URL を変更
	$content = str_replace( 'action="' . home_url( '/' ) . '"', 'action="' . VK_Filter_Search::get_search_root_url() . '"', $content );

	if ( false === strpos( $content, 'vkfs__keyword' ) ) {
		$content = str_replace( '[no_keyword_hidden_input]', '<input type="hidden" name="s" value="" />', $content );
	} else {
		$content = str_replace( '[no_keyword_hidden_input]', '', $content );
	}

	// 検索結果の移動先を設置
	if ( true === $attributes['DisplayOnResult'] ) {
		$content = str_replace( '[filter_search_result_input]', '<input type="hidden" name="vkfs_form_id" value="' . $attributes['FormID'] . '" />', $content );
	} else {
		$content = str_replace( '[filter_search_result_input]', '', $content );
	}

	if ( ! empty( $attributes['DisplayOnPosttypeArchive'] ) ) {
		$attributes['DisplayOnPosttypeArchive'] = str_replace( '[', '', $attributes['DisplayOnPosttypeArchive'] );
		$attributes['DisplayOnPosttypeArchive'] = str_replace( ']', '', $attributes['DisplayOnPosttypeArchive'] );
		$attributes['DisplayOnPosttypeArchive'] = str_replace( '"', '', $attributes['DisplayOnPosttypeArchive'] );
	}

	$post_types = ! empty( $attributes['DisplayOnPosttypeArchive'] ) ? explode( ',', $attributes['DisplayOnPosttypeArchive'] ) : array();

	$options = VK_Filter_Search::get_options();

	$target_post = get_post( $attributes['PostID'] );
	// 該当の投稿の投稿タイプが 'filter-search' の場合は post_meta に情報を保存
	if ( ! empty( $target_post ) && 'filter-search' === $target_post->post_type ) {
		// POST された値を取得後処理
		$display_result  = ! empty( $attributes['DisplayOnResult'] ) ? true : false;
		$display_archive = ! empty( $attributes['DisplayOnPosttypeArchive'] ) ? $attributes['DisplayOnPosttypeArchive'] : '';

		// 値を保存
		update_post_meta( $target_post->ID, 'vkfs_display_result', $display_result );
		update_post_meta( $target_post->ID, 'vkfs_display_archive', $display_archive );
	} elseif ( ! empty( $target_post ) ) {
		// 公開済み or 非公開の場合はオプションを処理、それ以外の場合は除去
		if ( 'publish' === $target_post->post_status || 'private' === $target_post->post_status ) {
			// 検索結果ページにフォームを表示する場合
			// フォームのデータを option 値に保存しおき、それを検索結果ページで読み込むようにしている
			if ( ! empty( $attributes['DisplayOnResult'] ) ) {
				// フォームが設置してある投稿IDとコンテンツの情報を option に追加
				$options['display_on_result'][ $attributes['FormID'] ] = array(
					'form_post_id' => $attributes['PostID'],
					'form_content' => $content,
				);
			} else {
				// フォームを検索結果に表示しない場合は opton からフォームの情報を削除
				if ( isset( $options['display_on_result'][ $attributes['FormID'] ] ) ) {
					unset( $options['display_on_result'][ $attributes['FormID'] ] );
				}
			}

			// 投稿タイプアーカイブにフォームを表示する場合
			if ( ! empty( $post_types ) ) {
				// 表示するフォームの情報を option に追加
				$options['display_on_post_type_archive'][ $attributes['FormID'] ] = array(
					'display_post_type' => $post_types,
					'form_post_id'      => $attributes['PostID'],
					'form_content'      => $content,
				);
			} else {
				// フォームを投稿タイプアーカイブに表示しない場合は opton からフォームの情報を削除
				if ( isset( $options['display_on_post_type_archive'][ $attributes['FormID'] ] ) ) {
					unset( $options['display_on_post_type_archive'][ $attributes['FormID'] ] );
				}
			}
		} else {
			if ( isset( $options['display_on_result'][ $attributes['FormID'] ] ) ) {
				unset( $options['display_on_result'][ $attributes['FormID'] ] );
			}
			if ( isset( $options['display_on_post_type_archive'][ $attributes['FormID'] ] ) ) {
				unset( $options['display_on_post_type_archive'][ $attributes['FormID'] ] );
			}
		}

		// オプション値を更新
		update_option( 'vk_filter_search', $options );
	}

	return $content;
}
