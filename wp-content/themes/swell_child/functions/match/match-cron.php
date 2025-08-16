<?php
// functions/match/match-cron.php
/**
 * File: functions/match/match-cron.php
 * Mục đích: Cron job để tự động cập nhật trạng thái match.
 * Gọi từ includes/load.php
 */

// Tạo 3 lịch chạy hằng ngày (tự reschedule)
add_action('after_setup_theme', function () {
    vsp_schedule_daily_event('vsp_match_cron_1100', '11:00');
    vsp_schedule_daily_event('vsp_match_cron_1600', '16:00');
    vsp_schedule_daily_event('vsp_match_cron_2100', '21:00');
});

function vsp_schedule_daily_event($hook, $time_str) {
    if (!wp_next_scheduled($hook)) {
        $ts = vsp_next_timestamp_today($time_str);
        wp_schedule_event($ts, 'daily', $hook);
    }
}

function vsp_next_timestamp_today($time_str) {
    $tz  = wp_timezone();
    $now = new DateTime('now', $tz);
    [$H,$i] = array_map('intval', explode(':',$time_str));
    $run = (new DateTime('today', $tz))->setTime($H,$i,0);
    if ($run <= $now) $run->modify('+1 day');
    return $run->getTimestamp();
}

// Gắn 3 hooks vào cùng 1 handler
add_action('vsp_match_cron_1100', 'vsp_match_cron_runner');
add_action('vsp_match_cron_1600', 'vsp_match_cron_runner');
add_action('vsp_match_cron_2100', 'vsp_match_cron_runner');

function vsp_match_cron_runner() {
    // 1) doing -> closed nếu quá deadline và stop_method = by_deadline
    vsp_close_by_deadline();

    // 2) closed -> done nếu quá giờ kết thúc + 4h
    vsp_close_to_done_after_4h();
}

function vsp_close_by_deadline() {
    $q = new WP_Query([
        'post_type'      => 'match',
        'posts_per_page' => -1,
        'tax_query'      => [[
            'taxonomy' => 'match_status',
            'field'    => 'slug',
            'terms'    => ['doing'],
        ]],
        'meta_query'     => [[ 'key'=>'stop_method','value'=>'by_deadline' ]],
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]);

    $tz = wp_timezone();
    $now = new DateTime('now', $tz);

    foreach ($q->posts as $post_id) {
        $sd = get_field('stop_date', $post_id);
        $st = get_field('stop_time', $post_id) ?: '23:59';
        if (!$sd) continue;
        try {
            $deadline = new DateTime($sd.' '.$st, $tz);
            if ($now > $deadline) {
                vsp_set_status($post_id, 'closed');
            }
        } catch (Exception $e) {}
    }
}

function vsp_close_to_done_after_4h() {
    $q = new WP_Query([
        'post_type'      => 'match',
        'posts_per_page' => -1,
        'tax_query'      => [[
            'taxonomy' => 'match_status',
            'field'    => 'slug',
            'terms'    => ['closed'],
        ]],
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]);

    $tz = wp_timezone();
    $now = new DateTime('now', $tz);

    foreach ($q->posts as $post_id) {
        $sd = get_field('start_date', $post_id);
        $st = get_field('start_time', $post_id) ?: '00:00';
        $hrs = floatval(get_field('hours', $post_id));
        if (!$sd || $hrs <= 0) continue;

        try {
            $start = new DateTime($sd.' '.$st, $tz);
            $end   = clone $start;
            $mins  = (int) round($hrs * 60);
            $end->modify("+{$mins} minutes")->modify('+4 hours'); // buffer 4h
            if ($now > $end) {
                vsp_set_status($post_id, 'done');
            }
        } catch (Exception $e) {}
    }
}

function vsp_set_status($post_id, $slug) {
    $term = get_term_by('slug', $slug, 'match_status');
    if ($term) wp_set_post_terms($post_id, [$term->term_id], 'match_status', false);
}

// (Tuỳ chọn) doing -> closed khi đủ người đăng ký nếu stop_method = auto_when_full
// Bạn có thể gọi vsp_set_status($post_id,'closed') khi vsp_get_signups_count($post_id) >= people
