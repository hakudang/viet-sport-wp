<?php
// includes/acf/match_acf.php

// --- QUICK DIAG: xác nhận file đã load ---
// add_action('plugins_loaded', function () {
//     if ( defined('WP_DEBUG') && WP_DEBUG ) {
//         error_log('[VSP] match_acf.php loaded');
//     }
// });

// --- ADMIN NOTICE nếu ACF chưa active ---
add_action('admin_notices', function () {
    if ( ! function_exists('acf_add_local_field_group') ) {
        echo '<div class="notice notice-error"><p><strong>ACF not active</strong>: Advanced Custom Fields chưa được kích hoạt nên Match Fields không thể đăng ký.</p></div>';
    }
});

// --- Đăng ký field group bằng hook ổn định ---
add_action('acf/include_fields', function () {
    if ( ! function_exists('acf_add_local_field_group') ) return;

    // // (LOG) xác nhận acf/include_fields đã chạy
    // if ( defined('WP_DEBUG') && WP_DEBUG ) {
    //     error_log('[VSP] acf/include_fields fired');
    // }

    acf_add_local_field_group([
        'key'                   => 'group_match_acf',     // nếu nghi ngờ xung đột, đổi sang group_match_acf_v1
        'title'                 => 'Match Fields',
        'location'              => [[[
            'param'     => 'post_type',
            'operator'  => '==',
            'value'     => 'match',
        ]]],
        'position'              => 'acf_after_title',
        'style'                 => 'seamless',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'menu_order'            => 0,
        'active'                => true,

        'fields' => [
            // --- Time & schedule ---
            [ 'key'=>'field_start_date','label'=>'Ngày bắt đầu','name'=>'start_date','type'=>'date_picker','return_format'=>'Y-m-d','display_format'=>'Y-m-d','first_day'=>1 ],
            [ 'key'=>'field_start_time','label'=>'Giờ bắt đầu','name'=>'start_time','type'=>'time_picker','display_format'=>'H:i','return_format'=>'H:i','default_value'=>'18:00' ],
            [ 'key'=>'field_hours','label'=>'Số giờ','name'=>'hours','type'=>'number','min'=>1,'step'=>0.5 ],
            [ 'key'=>'field_stop_date','label'=>'Ngày dừng tuyển','name'=>'stop_date','type'=>'date_picker','return_format'=>'Y-m-d','display_format'=>'Y-m-d','first_day'=>1 ],
            [ 'key'=>'field_stop_time','label'=>'Giờ dừng tuyển','name'=>'stop_time','type'=>'time_picker','display_format'=>'H:i','return_format'=>'H:i','default_value'=>'20:00' ],

            // --- Location ---
            [ 'key'=>'field_place_name','label'=>'Tên địa điểm','name'=>'place_name','type'=>'text' ],
            [ 'key'=>'field_google_map','label'=>'Google map','name'=>'google_map','type'=>'google_map','center'=>['lat'=>35.681236,'lng'=>139.767125],'zoom'=>12 ],

            // --- People & rules ---
            [ 'key'=>'field_people','label'=>'Số người','name'=>'people','type'=>'number','min'=>1,'step'=>1 ],
            [
                'key'=>'field_stop_method','label'=>'Phương thức chốt dừng tuyển','name'=>'stop_method','type'=>'radio',
                'choices'=>[
                    'by_deadline'=>'Theo hạn (stop_date/time)',
                    'auto_when_full'=>'Đủ người tự đóng',
                    'manual'=>'Thủ công',
                ],
                'layout'=>'horizontal'
            ],

            // --- Contact & details ---
            [ 'key'=>'field_tel_chu_xi','label'=>'SĐT chủ xị','name'=>'tel_chu_xi','type'=>'text','wrapper'=>['width'=>50] ],
            [ 'key'=>'field_ask_part_tel','label'=>'Yêu cầu người tham gia nhập SDT','name'=>'ask_participant_tel','type'=>'true_false','ui'=>1,'wrapper'=>['width'=>50] ],
            [ 'key'=>'field_court_info','label'=>'Thông tin sân','name'=>'court_info','type'=>'text' ],
            [ 'key'=>'field_details','label'=>'Thông tin chi tiết','name'=>'details','type'=>'wysiwyg' ],
        ],
    ]);

    // // (LOG) xác nhận group đã add
    // if ( defined('WP_DEBUG') && WP_DEBUG ) {
    //     if ( function_exists('acf_get_field_group') ) {
    //         $g = acf_get_field_group('group_match_acf');
    //         error_log('[VSP] ACF group registered: '. ( $g ? 'YES' : 'NO' ));
    //     }
    // }
});

// --- Defaults & sanitation ---
add_filter('acf/update_value', function ($value, $post_id, $field) {
    if ( get_post_type($post_id) !== 'match' ) return $value;
    if ($field['name'] === 'start_time' && empty($value)) return '18:00';
    if ($field['name'] === 'stop_time'  && empty($value)) return '20:00';
    if ($field['name'] === 'stop_date'  && empty($value)) {
        $start_date = get_field('start_date', $post_id);
        if ($start_date) return $start_date;
    }
    return $value;
}, 10, 3);

add_filter('acf/update_value/name=tel_chu_xi', function ($value) {
    if (!is_string($value)) return $value;
    return trim(preg_replace('/[^0-9+\-\s]/', '', $value));
}, 10, 1);
