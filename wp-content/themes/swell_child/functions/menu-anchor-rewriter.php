<?php
/**
 * Menu Anchor Rewriter
 * File name: functions/menu-anchor-rewriter.php
 *
 * Mục đích:
 * - Cho phép nhập anchor trong WP Menus dưới dạng: "#faq", "/#faq" hoặc "home:#faq"
 * - Tự convert thành URL tuyệt đối theo môi trường: home_url('/#faq')
 *   → Local: https://viet-sport.local/#faq
 *   → Dev (staging path /dev): https://viet-sport.com/dev/#faq
 *   → Prod: https://viet-sport.com/#faq
 *
 * Mặc định áp dụng cho 2 location: 'header_menu' và 'match_menu'
 * (đổi trong $only_locations nếu cần).
 */

add_filter( 'wp_nav_menu_objects', function( $items, $args ) {
	// ===== Cấu hình =====
	// Chỉ áp dụng cho các theme location sau. Để [] nếu muốn áp dụng cho tất cả menu.
	// $only_locations = [ 'header_menu', 'match_menu' ];
	$only_locations = [  ];
    
	// Whitelist anchors (tuỳ chọn). Để [] để cho phép mọi anchor.
	$allow_anchors = []; // ví dụ: ['faq', 'pricing', 'contact']

	// Nếu giới hạn theo location → không khớp thì bỏ qua
	if ( ! empty( $only_locations ) ) {
		$loc = isset( $args->theme_location ) ? $args->theme_location : null;
		if ( ! in_array( $loc, $only_locations, true ) ) {
			return $items;
		}
	}

	foreach ( $items as $item ) {
		$url = trim( (string) $item->url );

		$anchor = null;
		// Hỗ trợ '#id' và '/#id'
		if ( preg_match( '~^(?:/)?#([A-Za-z0-9_-]+)$~', $url, $m ) ) {
			$anchor = $m[1];
		}
		// Hỗ trợ 'home:#id'
		elseif ( stripos( $url, 'home:#' ) === 0 ) {
			$anchor = substr( $url, 6 ); // sau 'home:#'
		}

		// Không phải anchor → bỏ qua
		if ( $anchor === null ) {
			continue;
		}

		// Nếu có whitelist anchors → không khớp thì bỏ qua
		if ( ! empty( $allow_anchors ) && ! in_array( $anchor, $allow_anchors, true ) ) {
			continue;
		}

		// Nếu đang ở trang chủ → giữ '#id' để scroll mượt, tránh reload
		$item->url = is_front_page()
			? '#' . $anchor
			: esc_url( home_url( '/#' . $anchor ) );
	}

	return $items;
}, 10, 2 );
