<?php
/**
 * File name: functions/urm-menu-hooks.php
 * 🎯 Thêm 4 menu URM vào location 'utility_menu' (menu nhỏ phía trên header).
 * - Dùng walker của WP/SWELL → giữ nguyên CSS/markup
 * - Chỉ set description trên object → SWELL tự render .desc (nếu bật)
 * - Tránh trùng nếu admin đã thêm thủ công
 */

/** Tạo nav_menu_item ảo kèm description để walker render chuẩn */
function vs_make_menu_item( string $title, string $url, array $extra_classes = [], string $desc = '' ) : WP_Post {
    $item = new stdClass();
    $item->ID = 0; $item->db_id = 0; $item->menu_item_parent = 0;
    $item->object_id = 0; $item->object = 'custom'; $item->type = 'custom'; $item->type_label = __( 'Custom Link' );
    $item->title = $title; $item->url = $url; $item->target = ''; $item->attr_title = ''; $item->xfn = '';
    $item->current = false; $item->current_item_ancestor = false; $item->current_item_parent = false;

    // Class nhận diện (nếu cần style riêng)
    $item->classes = array_filter(array_merge([
        'menu-item','menu-item-type-custom','menu-item-object-custom','menu-item-urm','menu-urm',
    ], $extra_classes));

    // Description: SWELL sẽ tự in nếu bạn bật hiển thị mô tả cho menu này
    $item->description = $desc;

    return new WP_Post($item);
}

/** Chèn URM items vào 'utility_menu' trước khi walker render */
add_filter('wp_nav_menu_objects', function ($items, $args) {
    if ( empty($args->theme_location) || $args->theme_location !== 'utility_menu' ) {
        return $items;
    }

    // 🔒 ÉP utility_menu CHỈ hiển thị URM: khởi tạo mảng rỗng, bỏ qua mọi item thủ công
    $items = [];

    $base             = site_url('/match');
    $url_account      = esc_url("$base/my-account");
    $url_registration = esc_url("$base/registration");
    $url_login        = esc_url("$base/login");
    $url_logout       = esc_url( wp_logout_url($base) );

    if ( is_user_logged_in() ) {
        $items[] = vs_make_menu_item('Tài khoản', $url_account, ['menu-urm-account'], 'Quản lý thông tin');
        $items[] = vs_make_menu_item('Đăng xuất',  $url_logout,  ['menu-urm-logout'],  'Thoát khỏi tài khoản');
    } else {
        $items[] = vs_make_menu_item('Đăng ký',    $url_registration, ['menu-urm-registration'], 'Tạo tài khoản');
        $items[] = vs_make_menu_item('Đăng nhập',  $url_login,        ['menu-urm-login'],        'Truy cập tài khoản');
    }

    return $items;
}, 10, 2);

// ❌ KHÔNG thêm hook walker_nav_menu_start_el để chèn HTML thủ công — để SWELL tự render title/desc
