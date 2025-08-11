<?php
/**
 * File: functions/render-utility-menu.php
 * In utility bar ngay sau <body>.
 * - Không phụ thuộc has_nav_menu: nếu menu rỗng sẽ in HTML comment để debug.
 */
add_action('wp_body_open', function () {
    // Lấy HTML menu (không echo) để biết có gì không
    $html = wp_nav_menu([
        'theme_location' => 'utility_menu',
        'container'      => false,
        'menu_class'     => 'vs-utility-menu',
        'fallback_cb'    => '__return_false', // không in danh sách trang mặc định
        'echo'           => false,
        'depth'          => 1,
    ]);

    if ( ! $html ) {
        echo "\n<!-- Utility menu empty: hãy tạo & assign menu vào location 'utility_menu' -->\n";
        return;
    }

    // aria-label để screen reader hiểu đây là menu tiện ích
    echo '<nav class="vs-utility" role="navigation" aria-label="Utility Menu"><div class="vs-wrap">', $html, '</div></nav>';
});
