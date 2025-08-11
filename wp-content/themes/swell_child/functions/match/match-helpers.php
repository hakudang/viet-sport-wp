<?php
/**
 * File name: functions/match/match-helpers.php
 * Vị trí: /wp-content/themes/swell_child/functions/match/match-helpers.php
 * Xác định ngữ cảnh “Match”
 * - URL bắt đầu bằng /match
 * - hoặc dùng template page-search-match.php
 * - hoặc CPT match (để mở rộng về sau)
 */

// Xác định ngữ cảnh “Match” - trang nằm trong module match - tìm và tạo sân chơi
function vsp_is_match_context() {
    $req = isset($_SERVER['REQUEST_URI']) ? trim($_SERVER['REQUEST_URI'], '/') : '';

    // Nếu URL chính là /match hoặc bắt đầu bằng /match/
    if ($req === 'match' || strpos($req, 'match/') === 0) {
        return true;
    }

    // Nếu là template trang tìm kiếm match
    if (function_exists('is_page_template') && is_page_template('page-search-match.php')) {
        return true;
    }

    // Nếu là archive hoặc single của CPT "match"
    if (function_exists('is_post_type_archive') && is_post_type_archive('match')) {
        return true;
    }
    if (function_exists('is_singular') && is_singular('match')) {
        return true;
    }

    return false;
}


/**
 * Ẩn widget chứa Blog Parts 504 ở frontend khi đang ở khu Match.
 * - Dùng 'widget_display_callback' để chặn output của widget.
 * - Áp dụng cho widget 'Custom HTML' (id dạng 'custom_html-3') hoặc nội dung có chứa partsid=504.
 */
add_filter('widget_display_callback', function ($instance, $widget, $args) {

    // Chỉ làm ở frontend & đúng ngữ cảnh match
    if ( is_admin() ) return $instance;
    if ( ! function_exists('vsp_is_match_context') || ! vsp_is_match_context() ) return $instance;

    // 1) Nếu biết chính xác widget id (ví dụ 'custom_html-3') thì chặn thẳng tay:
    if ( isset($args['widget_id']) && $args['widget_id'] === 'custom_html-3' ) {
        return false; // ⛔ Ẩn toàn bộ widget này ở /match
    }

    // 2) Hoặc nếu không chắc id, kiểm tra nội dung có parts 504 không (áp dụng cho Text/Custom HTML widget)
    if ( isset($instance['content']) && strpos($instance['content'], 'data-partsid="504"') !== false ) {
        return false; // ⛔ Ẩn widget có chứa Blog Parts 504
    }

    return $instance;
}, 10, 3);
/* ============ Ẩn đúng khối Blog Parts ============ */


/**
 * Thêm body class 'is-match' khi đang ở ngữ cảnh Match (để CSS/JS có thể target)
 * Thêm 1 class tên 'is-match' cho 'body_class' của trang nếu nó thuộc ngữ cảnh Match
 */

add_filter('body_class', function ($classes) {
    if ( function_exists('vsp_is_match_context') && vsp_is_match_context() ) {
        $classes[] = 'is-match';
    }
    return $classes;
});
/* ============ Thêm body class 'is-match' ============ */