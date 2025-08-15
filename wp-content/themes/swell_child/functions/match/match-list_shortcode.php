<?php
/**
 * Shortcode: [match-list]
 * File: functions/match/match-list_shortcode.php
 * Mục đích: Hiển thị danh sách sân chơi (CPT: match)
 * Hiển thị bảng danh sách sân chơi (CPT: match)
 */
add_shortcode('match-list', 'vsp_render_match_list');

function vsp_render_match_list($atts = []) {
    $a = shortcode_atts([
        'limit'    => 50,      // số dòng
        'upcoming' => 0,       // 1 = chỉ các trận sắp tới (start_date >= hôm nay)
        'status'   => '',      // slug match_status để lọc (optional)
        'pref'     => '',      // slug match_prefecture để lọc (optional)
        'sport'    => '',      // slug match_sport để lọc (optional)
    ], $atts, 'match-list');

    // Tax filters (nếu cần)
    $tax_query = [];
    foreach (['match_status' => $a['status'], 'match_prefecture' => $a['pref'], 'match_sport' => $a['sport']] as $tax => $slug) {
        if ($slug !== '') {
            $tax_query[] = ['taxonomy' => $tax, 'field' => 'slug', 'terms' => sanitize_title($slug)];
        }
    }

    // Meta filter: chỉ trận sắp tới
    $meta_query = [];
    if (intval($a['upcoming']) === 1) {
        $today = wp_date('Y-m-d');
        $meta_query[] = ['key' => 'start_date', 'value' => $today, 'compare' => '>=', 'type' => 'DATE'];
    }

    $q = new WP_Query([
        'post_type'              => 'match',
        'posts_per_page'         => intval($a['limit']),
        'post_status'            => 'publish',
        'orderby'                => 'meta_value',
        'meta_key'               => 'start_date',
        'meta_type'              => 'DATE',
        'order'                  => 'ASC',
        'ignore_sticky_posts'    => true,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => true,
        'tax_query'              => $tax_query ?: null,
        'meta_query'             => $meta_query ?: null,
    ]);

    ob_start();

    if ($q->have_posts()) {
        echo '<table class="match-table"><thead><tr>
                <th>Tỉnh</th>
                <th>Ngày &amp; giờ</th>
                <th>Tiêu đề</th>
                <th>Số giờ</th>
                <th>Trạng thái</th>
              </tr></thead><tbody>';

        while ($q->have_posts()) { $q->the_post();
            $post_id = get_the_ID();

            // Taxonomy names
            $pref   = vsp_first_term_name($post_id, 'match_prefecture');
            $status = vsp_first_term_name($post_id, 'match_status');

            // ACF fields
            $start_date = get_field('start_date') ?: '';
            $start_time = get_field('start_time') ?: '';
            $hours      = get_field('hours');
            $title      = get_the_title();
            $slug       = get_post_field('post_name', $post_id);

            // Format ngày: 2025-08-31 -> 31/08/2025 (CN) - 12:00
            $start_full = '—';
            if ($start_date) {
                $ts  = strtotime($start_date);
                if ($ts) {
                    $dow = [
                        'Mon'=>'Th2','Tue'=>'Th3','Wed'=>'Th4','Thu'=>'Th5','Fri'=>'Th6','Sat'=>'Th7','Sun'=>'CN'
                    ][date('D', $ts)] ?? '';
                    $start_full = date('d/m/Y', $ts) . ($dow ? " ($dow)" : '');
                    if ($start_time) $start_full .= ' - ' . date('H:i', strtotime($start_time));
                }
            }
            $hours_label = ($hours !== '' && $hours !== null) ? esc_html($hours) . ' giờ' : '—';

            printf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td><a href="%s">%s <span class="ma-san">(#%s)</span></a></td>
                    <td>%s</td>
                    <td>%s</td>
                 </tr>',
                esc_html($pref ?: '—'),
                esc_html($start_full),
                esc_url(get_permalink()),
                esc_html($title),
                esc_html($slug),
                $hours_label,
                esc_html($status ?: '—')
            );
        }

        echo '</tbody></table>';
    } else {
        echo '<p>Không có sân chơi nào.</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}

/** Lấy tên term đầu tiên của taxonomy (hoặc null) */
function vsp_first_term_name($post_id, $taxonomy) {
    $terms = get_the_terms($post_id, $taxonomy);
    if (!$terms || is_wp_error($terms)) return null;
    $first = array_shift($terms);
    return $first ? $first->name : null;
}
