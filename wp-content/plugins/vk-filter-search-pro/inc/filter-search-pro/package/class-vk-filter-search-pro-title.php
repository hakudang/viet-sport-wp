<?php
/**
 * VK Fiter Search Pro Title
 *
 * @package VK Filter Search Title
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'VK_Filter_Search_Pro_Title' ) ) {
	/**
	 * VK Fiter Search Title
	 */
	class VK_Filter_Search_Pro_Title {

		public function __construct() {
			add_filter( 'vkfs_search_title', array( __CLASS__, 'get_search_title' ), 10, 2 );
		}

		/**
		 * 日付の取得
		 *
		 * @param string $date 日付
		 * @return string
		 */
		public static function get_date( $date ) {
			if ( ! empty( $date ) ) {
				return date( get_option( 'date_format' ), strtotime( $date ) );
			} else {
				return '';
			}
		}

		/**
		 * 時間の取得
		 *
		 * @param string $time 時間
		 * @return string
		 */
		public static function get_time( $time ) {
			if ( ! empty( $time ) ) {
				return date( get_option( 'time_format' ), strtotime( $time ) );
			} else {
				return '';
			}
		}

		/**
		 * 日時の取得
		 *
		 * @param string $datetime 日時
		 * @return string
		 */
		public static function get_datetime( $datetime ) {
			if ( ! empty( $datetime ) ) {
				return date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $datetime ) );
			} else {
				return '';
			}
		}

		/**
		 * 日付の検索タイトルを取得
		 *
		 * @param array $search_title_args 区切り文字の配列
		 * @return string
		 */
		public static function get_date_search_title( $search_title_args = array() ) {

			if ( ! $search_title_args ){
				$search_title_args = self::get_search_title_args();
			}

			// 日付のクエリの取得
			$date_array = array(
				'post_date'     => __( 'Post Date', 'vk-filter-search-pro' ),
				'post_modified' => __( 'Post Modified', 'vk-filter-search-pro' ),
			);
			// タイトルを初期化
			$search_title = '';

			foreach ( $date_array as $date => $label ) {

				// 当日の日付の取得
				$date_equal = '';
				if ( ! empty( get_query_var( $date . '_date_equal' ) ) ) {
					$date_equal = get_query_var( $date . '_date_equal' );
				} elseif ( ! empty( get_query_var( $date . '_value' ) ) ) {
					$date_equal = get_query_var( $date . '_value' );
				}
				$date_equal = self::get_date( $date_equal );

				// 開始日
				$date_min = '';
				if ( ! empty( get_query_var( $date . '_date_after' ) ) ) {
					$date_min = get_query_var( $date . '_date_after' );
				} elseif ( ! empty( get_query_var( $date . '_value_after' ) ) ) {
					$date_min = get_query_var( $date . '_value_after' );
				} elseif ( ! empty( get_query_var( $date . '_range_after' ) ) ) {
					$date_min = get_query_var( $date . '_range_after' );
				}
				$date_min = self::get_date( $date_min );

				// 終了日
				$date_max = '';
				if ( ! empty( get_query_var( $date . '_date_before' ) ) ) {
					$date_max = get_query_var( $date . '_date_before' );
				} elseif ( ! empty( get_query_var( $date . '_value_before' ) ) ) {
					$date_max = get_query_var( $date . '_value_before' );
				} elseif ( ! empty( get_query_var( $date . '_range_before' ) ) ) {
					$date_max = get_query_var( $date . '_range_before' );
				}
				$date_max = self::get_date( $date_max );

				// 日付のタイトルを生成
				if ( ! empty( $date_equal ) ){
					$date_equal = $search_title_args['query_element_before'] . $date_equal . $search_title_args['query_element_after'];
				}
				if ( ! empty( $date_min ) ){
					$date_min = $search_title_args['query_element_before'] . $date_min . $search_title_args['query_element_after'];
				}
				if ( ! empty( $date_max ) ){
					$date_max = $search_title_args['query_element_before'] . $date_max . $search_title_args['query_element_after'];
				}
				if ( ! empty( $date_equal ) || ! empty( $date_min ) || ! empty( $date_max ) ) {

					// query_title_display が display の場合はクエリタイトルを表示
					if ( 'display' === $search_title_args['query_title_display'] ) {
						$search_title .= $label . $search_title_args['query_title_after'];
					}
					
					if ( ! empty( $date_equal ) && empty( $date_min ) && empty( $date_max ) ) {
						$search_title .= $date_equal;
					} elseif ( empty( $date_equal ) && ! empty( $date_min ) && empty( $date_max ) ) {
						$search_title .= sprintf( $search_title_args['query_date_min_format'], $date_min );
					} elseif ( empty( $date_equal ) && ! empty( $date_max ) && empty( $date_min ) ) {
						$search_title .= sprintf( $search_title_args['query_date_max_format'], $date_max );
					} elseif ( empty( $date_equal ) && ! empty( $date_min ) && ! empty( $date_max ) ) {
						$search_title .= sprintf( $search_title_args['query_date_range_format'], $date_min, $date_max );
					}

					$search_title .= $search_title_args['query_elements_after'];
				}
			}

			return $search_title;
		}

		/**
		 * 検索タイトルを取得
		 *
		 * @param string $search_title 検索タイトル
		 * @param array  $search_title_args 区切り文字など設定項目の配列
		 * @return string
		 */
		public static function get_search_title( $search_title, $search_title_args ) {

			$search_title .= self::get_date_search_title( $search_title_args );

			return $search_title;
		}
	}
	new VK_Filter_Search_Pro_Title();
}
