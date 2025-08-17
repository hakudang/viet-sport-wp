<?php

/**
 * Purpose: Tạo post 'match', lưu ACF & taxonomy terms
 */
defined('ABSPATH') || exit;

if (!session_id()) session_start();

// 1) Lấy dữ liệu từ session
$raw = $_SESSION['match'] ?? [];
if (empty($raw)) {
    wp_safe_redirect('?step=1');
    exit;
}

// 2) Validate tối thiểu
$required = ['title', 'start_date'];
foreach ($required as $f) {
    if (empty($raw[$f])) {
        echo '<p>Thiếu thông tin cần thiết.</p>';
        return;
    }
}

// 3) Sanitize
$clean = [
    'title'       => sanitize_text_field($raw['title']),
    'start_date'  => sanitize_text_field($raw['start_date']),
    'start_time'  => preg_replace('/[^0-9:]/', '', $raw['start_time'] ?? '18:00'),
    'hours'       => max(1, (int)($raw['hours'] ?? 1)),
    'stop_date'   => sanitize_text_field($raw['stop_date'] ?? ''),
    'stop_time'   => preg_replace('/[^0-9:]/', '', $raw['stop_time'] ?? '20:00'),
    'place_name'  => sanitize_text_field($raw['place_name'] ?? ''),
    // district = slug của prefecture (ví dụ "tokyo") lấy từ Step 3
    'district'    => sanitize_text_field($raw['district'] ?? ''),
    // google_map tạm giữ chuỗi "lat,lng" (sẽ convert xuống dưới)
    'google_map'  => trim((string)($raw['google_map'] ?? '')),
    'people'      => max(0, (int)($raw['people'] ?? 0)),
    'stop_method' => sanitize_text_field($raw['stop_method'] ?? ''),
    'court_info'  => sanitize_textarea_field($raw['court_info'] ?? ''),
    'tel_chu_xi'  => preg_replace('/[^0-9+\-\s]/', '', $raw['tel_chu_xi'] ?? ''),
    'details'     => wp_kses_post($raw['details'] ?? ''),
    'ask_participant_tel' => !empty($raw['ask_participant_tel']) ? 1 : 0,
    // (tuỳ chọn) nếu form có gửi sport slug
    'sport'       => sanitize_text_field($raw['sport'] ?? ''),
];

// 4) Tạo post
$post_id = wp_insert_post([
    'post_type'   => 'match',
    'post_status' => 'publish',
    'post_title'  => $clean['title'],
    'post_author' => get_current_user_id(),
]);
if (is_wp_error($post_id)) {
    error_log('[Match] Create failed: ' . $post_id->get_error_message());
    echo '<p>Không thể tạo bài viết. Vui lòng thử lại sau.</p>';
    return;
}

// Helper để cập nhật meta dù ACF có/không
$save_meta = function (string $key, $val) use ($post_id) {
    if (function_exists('update_field')) {
        update_field($key, $val, $post_id);
    } else {
        update_post_meta($post_id, $key, $val);
    }
};

// 5) Chuẩn hoá Google Map (ACF type = google_map → array {address,lat,lng})
$acf_map = null;
if ($clean['google_map'] && preg_match('/^\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)\s*$/', $clean['google_map'], $m)) {
    $lat = (float)$m[1];
    $lng = (float)$m[2];

    // Lấy tên tỉnh theo slug từ DISTRICT_LABELS (nếu có) để dựng address đẹp
    $pref_name = '';
    if (defined('DISTRICT_LABELS')) {
        $arr = json_decode(DISTRICT_LABELS, true);
        if (is_array($arr) && $clean['district'] && isset($arr[$clean['district']])) {
            $pref_name = $arr[$clean['district']];
        }
    }
    $address = trim($clean['place_name'] . ($pref_name ? (($clean['place_name'] ? ', ' : '') . $pref_name) : ''));
    $acf_map = ['address' => $address, 'lat' => $lat, 'lng' => $lng];
}

// 6) Lưu ACF fields (trừ title, district, sport)
$acf_keys = [
    'start_date',
    'start_time',
    'hours',
    'stop_date',
    'stop_time',
    'place_name',
    'people',
    'stop_method',
    'court_info',
    'tel_chu_xi',
    'details',
    'ask_participant_tel'
];
foreach ($acf_keys as $k) {
    $save_meta($k, $clean[$k]);
}
if ($acf_map) {
    $save_meta('google_map', $acf_map);
}

// 7) Set taxonomy terms
// 7.1 match_status = doing (mặc định)
$doing = get_term_by('slug', 'doing', 'match_status');
if ($doing && !is_wp_error($doing)) {
    wp_set_post_terms($post_id, [(int)$doing->term_id], 'match_status', false);
}

// 7.2 match_prefecture theo district (slug)
//     - Nếu chưa có term slug đó, thử tìm theo name (DISTRICT_LABELS)
//     - Nếu vẫn không có → tạo mới với slug + name chuẩn
if ($clean['district']) {
    $pref_term = get_term_by('slug', $clean['district'], 'match_prefecture');

    if (!$pref_term && defined('DISTRICT_LABELS')) {
        $arr  = json_decode(DISTRICT_LABELS, true);
        $name = $arr[$clean['district']] ?? '';
        if ($name) {
            // thử tìm theo name (trường hợp bạn đã seed bằng name mà slug khác)
            $by_name = get_term_by('name', $name, 'match_prefecture');
            if ($by_name) {
                $pref_term = $by_name;
            } else {
                $insert = wp_insert_term($name, 'match_prefecture', ['slug' => $clean['district']]);
                if (!is_wp_error($insert)) {
                    $pref_term = get_term($insert['term_id'], 'match_prefecture');
                }
            }
        }
    }

    if ($pref_term && !is_wp_error($pref_term)) {
        wp_set_post_terms($post_id, [(int)$pref_term->term_id], 'match_prefecture', false);
    }
}

// 7.3 match_sport nếu form có gửi sport slug
if ($clean['sport']) {
    $sport = get_term_by('slug', $clean['sport'], 'match_sport');
    if (!$sport) {
        $ins = wp_insert_term(ucfirst($clean['sport']), 'match_sport', ['slug' => $clean['sport']]);
        if (!is_wp_error($ins)) {
            $sport = get_term($ins['term_id'], 'match_sport');
        }
    }
    if ($sport && !is_wp_error($sport)) {
        wp_set_post_terms($post_id, [(int)$sport->term_id], 'match_sport', false);
    }
}

// 8) Dọn session & chuyển hướng
unset($_SESSION['match']);
session_write_close();

wp_safe_redirect(get_permalink($post_id));
exit;
