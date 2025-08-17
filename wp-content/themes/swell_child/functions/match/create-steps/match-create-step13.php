<?php

/**
 * File: swell_child/functions/match/create-steps/match-create-step13.php
 * Purpose: Xác nhận thông tin sân chơi trước khi tạo mới.
 * - Hiển thị thông tin đã nhập ở các bước trước
 * - Cho phép xác nhận và chuyển sang bước 14 (tạo sân chơi)
 */

// =============================================
// BƯỚC 13: XÁC NHẬN THÔNG TIN SÂN CHƠI
// =============================================

if (!session_id()) session_start();

$data = $_SESSION['match'] ?? [];

// ✅ TODO: đổi KEY thật tại đây hoặc lấy từ option/theme_mod
$GOOGLE_MAPS_API_KEY = defined('VSP_GOOGLE_MAPS_KEY') ? VSP_GOOGLE_MAPS_KEY : '';

$districts = json_decode(DISTRICT_LABELS, true);

if (empty($data)) {
    wp_redirect('?step=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xác nhận & chuyển sang bước 14
    if (
        !isset($_POST['tao_san_choi_nonce']) ||
        !wp_verify_nonce($_POST['tao_san_choi_nonce'], 'tao_san_choi_action')
    ) {
        echo '<p>❌ Phiên không hợp lệ. Vui lòng thử lại.</p>';
        exit;
    }

    wp_redirect('?step=14');
    exit;
}

get_header();
?>

<!-- ==============================
     FORM BƯỚC 13: Xác nhận thông tin
============================== -->
<div class="form-create-match match-create-step13">
    <h2>Xác nhận thông tin sân chơi</h2>

    <ul class="xac-nhan-thong-tin">
        <!-- Tiêu đề -->
        <li>
            <strong>Tiêu đề:</strong>
            <?= esc_html($data['title'] ?? '[Chưa nhập]') ?>
            <a href="?step=1">✏️ Sửa</a>
        </li>

        <!-- Thời gian khai mạc và dừng tuyển -->
        <li>
            <strong>Thời gian:</strong><br>
            • <strong>Khai mạc:</strong>
            <?= esc_html($data['start_date'] ?? '[?]') ?> lúc
            <?= esc_html($data['start_time'] ?? '[?]') ?> –
            <strong>Số giờ:</strong>
            <?= esc_html($data['hours'] ?? '[?]') ?> giờ<br>

            • <strong>Dừng tuyển:</strong>
            <?= esc_html($data['stop_date'] ?? '[?]') ?> lúc
            <?= esc_html($data['stop_time'] ?? '[?]') ?>
            (<em>trước <?= esc_html($data['days'] ?? '?') ?> ngày</em>)
            <a href="?step=2">✏️ Sửa</a>
        </li>

        <!-- Địa điểm -->
        <li>
            <strong>Tên địa điểm:</strong>
            <?= esc_html($data['place_name'] ?? '[Chưa nhập]') ?> –
            <strong>Tỉnh thành:</strong>
            <?= esc_html($districts[$data['district']] ?? '[Chưa chọn]') ?>
            <a href="?step=3">✏️ Sửa</a>
        </li>

        <!-- Google Map tĩnh -->
        <?php if (!empty($data['google_map'])): ?>
            <li>
                <strong>Vị trí bản đồ:</strong>
                <span id="current-coords"><?= esc_html($data['google_map']) ?></span>
                <a href="?step=4">✏️ Sửa</a>
                <div id="map-preview" style="width:100%; height:400px; margin-top:10px; border:1px solid #ccc; border-radius:6px;"></div>
            </li>
        <?php endif; ?>



    </ul>

    <!-- Form xác nhận gửi đi -->
    <form method="post">
        <?php wp_nonce_field('tao_san_choi_action', 'tao_san_choi_nonce'); ?>
        <div class="form-buttons">
            <!-- Nút về trước (dùng JS điều hướng) -->
            <button type="button" id="btn-back" class="btn btn-secondary">← Về trước</button>

            <!-- Nút tạo sân chơi -->
            <button type="submit" class="btn btn-primary">Tạo sân chơi</button>
        </div>
    </form>
</div>

<?php get_footer(); ?>

<?php if (!empty($data['google_map'])): ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($GOOGLE_MAPS_API_KEY); ?>&callback=initPreviewMap" async defer></script>

    <script>
        function initPreviewMap() {
            const coords = "<?php echo esc_js($data['google_map']); ?>".split(',').map(Number);
            const latLng = {
                lat: coords[0],
                lng: coords[1]
            };

            const map = new google.maps.Map(document.getElementById("map-preview"), {
                center: latLng,
                zoom: 16,
                disableDefaultUI: true,
                gestureHandling: "none",
            });

            new google.maps.Marker({
                position: latLng,
                map,
                title: "Vị trí sân chơi"
            });
        }
    </script>
<?php endif; ?>


<!-- ==============================
     JS: Xử lý nút "← Về trước"
============================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnBack = document.getElementById('btn-back');
        if (btnBack) {
            btnBack.addEventListener('click', function() {
                window.location.href = '?step=<?= $current_step - 1 ?>';
            });
        }
    });
</script>