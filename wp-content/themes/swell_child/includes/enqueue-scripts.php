<?php
// swell_child/includes/enqueue-scripts.php
/**
 * File: includes/enqueue-scripts.php
 * Mục đích: Enqueue CSS/JS cho child theme.
 * Gọi từ includes/load.php
 */

if ( ! function_exists('swell_child_enqueue_scripts') ) {
    function swell_child_enqueue_scripts() {

        // Utility bar: dùng toàn site
        wp_enqueue_style(
            'vs-utility',
            get_stylesheet_directory_uri() . '/assets/css/utility-menu.css',
            [],
            wp_get_theme()->get('Version')
        );

        // --- Chỉ load cho trang/module MATCH ---
        $is_match = function_exists('vsp_is_match_context') && vsp_is_match_context();
        // (hoặc thay bằng: $is_match = is_page_template('page-search-match.php');)

        if ( $is_match ) {

            // helper tránh warning nếu file chưa tồn tại
            $ver = function($relPath) {
                $abs = get_stylesheet_directory() . $relPath;
                return file_exists($abs) ? filemtime($abs) : null;
            };

            // Form tìm theo postcode
            wp_enqueue_style(
                'form-match-postcode-css',
                get_stylesheet_directory_uri() . '/assets/css/match-search-by-postcode-form.css',
                [],
                $ver('/assets/css/match-search-by-postcode-form.css')
            );

            // Form tìm theo tỉnh
            wp_enqueue_style(
                'form-match-district-css',
                get_stylesheet_directory_uri() . '/assets/css/match-search-by-district-form.css',
                [],
                $ver('/assets/css/match-search-by-district-form.css')
            );

            // CSS canh trái & chuẩn SWELL cho trang /match
            // Đặt sau cùng để override 2 file trên
            wp_enqueue_style(
                'vsp-page-search-match',
                get_stylesheet_directory_uri() . '/assets/css/page-search-match.css',
                ['form-match-postcode-css','form-match-district-css'], // đảm bảo load sau
                $ver('/assets/css/page-search-match.css')
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'swell_child_enqueue_scripts', 20);
