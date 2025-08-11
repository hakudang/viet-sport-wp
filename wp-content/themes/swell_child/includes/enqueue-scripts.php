<?php
/**
 * File: swell_child/includes/enqueue-scripts.php
 * Mục đích: Tải CSS cho các form Match và utility bar
 */

if ( ! function_exists('swell_child_enqueue_scripts') ) {
    function swell_child_enqueue_scripts() {
        // 🎯 CSS cho form tìm kiếm theo postcode
        wp_enqueue_style(
            'form-match-postcode-css',
            get_stylesheet_directory_uri() . '/assets/css/match-search-by-postcode-form.css',
            [],
            filemtime( get_stylesheet_directory() . '/assets/css/match-search-by-postcode-form.css' )
        );

        // 🎯 CSS cho form tìm kiếm theo quận/huyện
        wp_enqueue_style(
            'form-match-district-css',
            get_stylesheet_directory_uri() . '/assets/css/match-search-by-district-form.css',
            [],
            filemtime( get_stylesheet_directory() . '/assets/css/match-search-by-district-form.css' )
        );

        // 🎯 CSS cho utility bar (menu URM phía trên header)
        wp_enqueue_style(
            'vs-utility',
            get_stylesheet_directory_uri() . '/assets/css/utility-menu.css',
            [],
            wp_get_theme()->get('Version')
        );
    }
}
add_action('wp_enqueue_scripts', 'swell_child_enqueue_scripts', 20);
