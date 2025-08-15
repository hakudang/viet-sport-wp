<?php
// functions/match/match-admin-columns.php
/**
 * File: functions/match/match-admin-columns.php
 * Mục đích: Tùy chỉnh cột hiển thị trong danh sách Match ở khu vực quản trị.
 * - Thêm cột Start Date, People, Prefecture, Status, Slug
 * - Cho phép sort theo Start Date và People
 * - Dropdown filter theo Status và Prefecture
 */

// 1) Thêm cột
add_filter('manage_edit-match_columns', function ($cols) {
    $new = [];
    foreach ($cols as $k => $v) {
        if ($k === 'title') {
            $new['cb']         = $cols['cb'];
            $new['title']      = __('Title');
            $new['start_date'] = __('Start Date');
            $new['people']     = __('People');
            $new['pref']       = __('Prefecture');
            $new['status']     = __('Status');
            $new['slug']     = __('Slug');
        } elseif ($k !== 'cb') {
            // bỏ qua để tự sắp xếp
        }
    }
    $new['date'] = $cols['date'];
    return $new;
});

// 2) Render dữ liệu
// Helper: chuẩn hóa ngày về Y/m/d cho admin list
function vsp_admin_format_date($raw) {
    $raw = trim((string)$raw);
    if ($raw === '') return '—';

    // 8 chữ số: Ymd -> Y/m/d
    if (preg_match('/^\d{8}$/', $raw)) {
        return substr($raw, 0, 4) . '/' . substr($raw, 4, 2) . '/' . substr($raw, 6, 2);
    }

    // Thử parse các kiểu thường gặp (Y-m-d, Y/m/d, ...)
    $ts = strtotime($raw);
    if ($ts) return date('Y/m/d', $ts);

    // Không parse được thì trả nguyên
    return $raw;
}

add_action('manage_match_posts_custom_column', function ($col, $post_id) {
    if ($col === 'start_date') {
        $raw = get_post_meta($post_id, 'start_date', true);
        echo esc_html( vsp_admin_format_date($raw) );

    } elseif ($col === 'people') {
        $n = get_post_meta($post_id, 'people', true);
        echo $n !== '' ? intval($n) : '—';

    } elseif ($col === 'pref') {
        $terms = get_the_terms($post_id, 'match_prefecture');
        echo $terms && !is_wp_error($terms) ? esc_html($terms[0]->name) : '—';

    } elseif ($col === 'status') {
        $terms = get_the_terms($post_id, 'match_status');
        echo $terms && !is_wp_error($terms) ? esc_html($terms[0]->name) : '—';

    } elseif ($col === 'slug') {
        $slug = get_post_field('post_name', $post_id);
        echo $slug ? '<code>'.esc_html($slug).'</code>' : '—';
    }
}, 10, 2);


// 3) Cho phép sort theo Start Date & People, Prefecture, Status, Slug
add_filter('manage_edit-match_sortable_columns', function ($cols) {
    $cols['start_date'] = 'start_date';
    $cols['people']     = 'people';
    $cols['pref']       = 'match_prefecture';
    $cols['status']     = 'match_status';
    $cols['slug']       = 'post_name'; // slug là post_name trong WP
    return $cols;
});

// 4) Xử lý sort meta
add_action('pre_get_posts', function ($q) {
    if (!is_admin() || !$q->is_main_query()) return;
    if ($q->get('post_type') !== 'match') return;

    $orderby = $q->get('orderby');
    if ($orderby === 'start_date') {
        $q->set('meta_key', 'start_date');
        $q->set('meta_type', 'DATE');
        $q->set('orderby', 'meta_value');
    } elseif ($orderby === 'people') {
        $q->set('meta_key', 'people');
        $q->set('meta_type', 'NUMERIC');
        $q->set('orderby', 'meta_value_num');
    }
});

// 5) Dropdown filter theo Status & Prefecture
add_action('restrict_manage_posts', function ($post_type) {
    if ($post_type !== 'match') return;

    // Status
    wp_dropdown_categories([
        'show_option_all' => __('All Statuses'),
        'taxonomy'        => 'match_status',
        'name'            => 'match_status',
        'orderby'         => 'name',
        'selected'        => isset($_GET['match_status']) ? $_GET['match_status'] : '',
        'hierarchical'    => false,
        'show_count'      => false,
        'hide_empty'      => false,
        'value_field'     => 'slug',
    ]);

    // Prefecture
    wp_dropdown_categories([
        'show_option_all' => __('All Prefectures'),
        'taxonomy'        => 'match_prefecture',
        'name'            => 'match_prefecture',
        'orderby'         => 'name',
        'selected'        => isset($_GET['match_prefecture']) ? $_GET['match_prefecture'] : '',
        'hierarchical'    => true,
        'show_count'      => false,
        'hide_empty'      => false,
        'value_field'     => 'slug',
    ]);
});

// 6) Áp filter vào query
add_action('parse_query', function ($q) {
    if (!is_admin() || !$q->is_main_query()) return;
    if ($q->get('post_type') !== 'match') return;

    $tax_query = [];

    if (!empty($_GET['match_status']) && $_GET['match_status'] !== '0') {
        $tax_query[] = [
            'taxonomy' => 'match_status',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['match_status']),
        ];
    }

    if (!empty($_GET['match_prefecture']) && $_GET['match_prefecture'] !== '0') {
        $tax_query[] = [
            'taxonomy' => 'match_prefecture',
            'field'    => 'slug',
            'terms'    => sanitize_text_field($_GET['match_prefecture']),
        ];
    }

    if ($tax_query) {
        $q->set('tax_query', $tax_query);
    }
});

// 7) Thêm CSS để chỉnh cột title
add_action('admin_head-edit.php', function () {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'match') {
        echo '<style>.column-title{width:20%;font-family:sans-serif}</style>';
    }
});


