<?php
// functions/match/gmw-sync.php
/**
 * File: functions/match/gmw-sync.php
 * Mục đích: Đồng bộ hoá dữ liệu vị trí từ ACF Google Map với GEO my WP.
 * Gọi từ includes/load.php
 */
add_action('acf/save_post', function ($post_id) {
    if (get_post_type($post_id) !== 'match') return;
    if (!function_exists('gmw_update_post_location')) return; // GEO my WP chưa bật

    $loc = get_field('google_map', $post_id);
    if (!empty($loc['lat']) && !empty($loc['lng'])) {
        gmw_update_post_location($post_id, [
            'lat'     => $loc['lat'],
            'lng'     => $loc['lng'],
            'address' => $loc['address'] ?? '',
        ]);
    }
});
