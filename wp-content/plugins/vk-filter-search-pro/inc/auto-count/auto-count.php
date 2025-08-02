<?php
/**
 * Auto Count
 *
 * @package VK Filter Search
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'vkfs_auto_count' ) ) {
	/**
	 * Auto Count
	 *
	 * @param string $taxonomy_name taxonomy_name of the field.
	 * @param string $taxonomy_value value of the field.
	 * @param string $operator 'and' or 'or'.
	 * @return int $count founds post of the query includes the field data.
	 */
	function vkfs_auto_count( $taxonomy_name, $taxonomy_value, $multi_select, $post_type = null,  $operator = null ) {

		// 現在のクエリを取得
		global $wp_query;

		// 新しく作るクエリの受け皿
		$new_query_vars = array();

		// タクソノミー一覧を取得
		$taxonomies = get_taxonomies(
			array( 
				'public' => true
			)
		);

		// タクソノミーのクエリを取得
		$tax_query = array();
		if ( ! empty( $wp_query->tax_query->queries ) ) {
			$tax_query = $wp_query->tax_query->queries;
		}

		// 新しいタクソノミークエリの受け皿
		$new_tax_query = array();

		// クエリの配列を使いやすいように変換
		$query_taxonomies = array();
		
		if ( ! empty( $tax_query ) ) {
			foreach ( $tax_query as $tax ) {
				$query_taxonomies[$tax['taxonomy']][] = $tax;
			}
			$new_tax_query = array( 'relation' => 'AND' );
		}

		// 配列を整理
		foreach ( $query_taxonomies as $tax_key => $tax_value ) {

			// タームの値を統合するための配列
			$tax_terms_array = array();

			// タームの値を統合するためのカウンター
			$tax_merge_count = 0;

			// タームの関係性 (AND か OR か)
			$tax_operator = 'IN';

			if ( count( $tax_value ) > 1 ) {
				foreach ( $tax_value as $key => $value ) {
					// カテゴリ・タグはスラッグとターム ID の２つが tax_query に入るため、スラッグのみ採用
					if ( 'slug' === $value['field'] ) {

						// タームの値を統合							
						$tax_terms_array = array_merge( $tax_terms_array, $value['terms'] );

						// マージが行われた回数をカウント
						$tax_merge_count++;

						// オペレーターを取得
						$tax_operator = $value['operator'];
					}
				}

				// タームの値を統合したものを新しいクエリに追加
				$query_taxonomies[ $tax_key ] = array(
					'taxonomy' => $tax_key,
					'field'    => 'slug',
					'terms'    => $tax_terms_array,
					'operator' => $tax_merge_count > 1 || $tax_operator === 'AND' ? 'AND' : 'IN',
				);
			} else {
				// タームの値を統合しなかったものを新しいクエリに追加
				$query_taxonomies[ $tax_key ] = $tax_value[0];
			}
		}

		// タクソノミー毎にクエリを生成
		foreach ( $taxonomies as $taxonomy ) {
			// 現在処理中のタクソノミーが今回扱うタクソノミーと同じ場合は処理したものを反映
			if ( $taxonomy === $taxonomy_name ) {
				// 現在のクエリ文字列に該当タクソノミーがあればそれに追加、なければ単に追加
				// ただし、チェックボックスとラジオボタンは単数選択なので、既存のタームを削除して新しいタームを追加
				if ( ! empty( $query_taxonomies[ $taxonomy ] ) && $multi_select ) {

					// operator が user かからの場合は $_POST から and, or を取得
					if ( ! empty( $operator ) && 'user' === $operator || empty( $operator ) ) {
						if ( ! empty( $_GET[ $taxonomy_name . '_operator'] ) ) {
							$operator = esc_html( $_GET[ $taxonomy_name . '_operator'] );
						} else {
							$operator = 'and';
						}
					}
						
					// ターム間の関係性 (AND か OR か)
					$relation = $operator === 'and' ? 'AND' : 'OR';
					

					// 既存のタームと新しいタームを AND / OR で結合
					// 既存タームの集合を A, 新たなタームを B とした時
					// A AND B / A OR B という形でクエリを生成
					$new_tax_query[] = array(
						'relation' => $relation,				
						array(
							'taxonomy' => $query_taxonomies[ $taxonomy ]['taxonomy'],
							'field'    => 'slug',
							'terms'    => $query_taxonomies[ $taxonomy ]['terms'],
							'operator' => $query_taxonomies[ $taxonomy ]['operator'],
						),
						array(
							'taxonomy' => $taxonomy_name,
							'field'    => 'slug',
							'terms'    => $taxonomy_value,
							'operator' => 'IN',
						),										
					);

					// $relation が OR の場合は 更に AND をかけることでそのタームが存在するものに限定
					// 既存タームの集合を A, 新たなタームを B とした時
					// ( A OR B ) AND B となり B が存在するものに限定
					// AND の場合は ( A AND B ) AND B となり A AND B と同じすでに限定されているので不要 
					if ( $relation === 'OR' ) {
						$new_tax_query[] = array(
							'taxonomy' => $taxonomy_name,
							'field'    => 'slug',
							'terms'    => $taxonomy_value,
							'operator' => 'IN',
						);
					}
					
					
				} else {
					$new_tax_query[] = array(
						'taxonomy' => $taxonomy_name,
						'field'    => 'slug',
						'terms'    => $taxonomy_value,
						'operator' => 'IN',
					);
				}
			} else {
				if ( ! empty( $query_taxonomies[ $taxonomy ] ) ) {
					$new_tax_query[] = array(
						'taxonomy' => $query_taxonomies[ $taxonomy ]['taxonomy'],
						'field'    => 'slug',
						'terms'    => $query_taxonomies[ $taxonomy ]['terms'],
						'operator' => $query_taxonomies[ $taxonomy ]['operator'],
						
					);
				}
			}

		}	
		
		if ( ! empty( $new_tax_query ) ) {
			$new_query_vars['tax_query'] = $new_tax_query;
		}

		// カスタムフィールドのクエリはそのまま反映
		if ( ! empty( $wp_query->meta_query->queries ) ) {
			$new_query_vars['meta_query'] = $wp_query->meta_query->queries;
		}

		// 日付のクエリはそのまま反映
		if ( ! empty( $wp_query->date_query->queries ) ) {
			$new_query_vars['date_query'] = $wp_query->date_query->queries;
		}

		// 投稿タイプは事前に指定したものを反映
		$new_query_vars['post_type'] = '';
		if ( ! empty( $post_type ) ) {
			$new_query_vars['post_type'] = $post_type;
		} elseif ( ! empty( $wp_query->query_vars['post_type'] ) && 'any' !== $wp_query->query_vars['post_type'] ) {
			$new_query_vars['post_type'] = $wp_query->query_vars['post_type'];
		}

		// キーワードはそのまま反映
		$new_query_vars['s'] = '';
		if ( ! empty( $wp_query->query_vars['s'] ) ) {
			$new_query_vars['s'] = $wp_query->query_vars['s'];
		}

		// 新しいクエリ文字列でクエリを生成
		$new_query = new WP_Query( $new_query_vars );

		// 新しいクエリの投稿数（次に選択したりチェックを入れたりしたらいくつの投稿が見つかるか）を返す
		return $new_query->found_posts;
	}
}
