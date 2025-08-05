<?php
// Tải các style của child theme (match module)
function swell_child_enqueue_scripts()
{
    // 🎯 CSS cho form tìm kiếm theo postcode
    wp_enqueue_style(
        'form-match-postcode-css',
        get_stylesheet_directory_uri() . '/assets/css/match-search-by-postcode-form.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/css/match-search-by-postcode-form.css')
    );

    // 🎯 CSS cho form tìm kiếm theo quận/huyện
    wp_enqueue_style(
        'form-match-district-css',
        get_stylesheet_directory_uri() . '/assets/css/match-search-by-district-form.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/css/match-search-by-district-form.css')
    );

    // ✅ CSS riêng cho header của trang match (chỉ load nếu đúng template)
    if (is_page_template('page-search-match.php')) {
        wp_enqueue_style(
            'match-header-style',
            get_stylesheet_directory_uri() . '/assets/css/match-header.css',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/css/match-header.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'swell_child_enqueue_scripts');
