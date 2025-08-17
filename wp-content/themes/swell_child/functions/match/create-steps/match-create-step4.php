<?php
// =============================================
// BƯỚC 4: GOOGLE MAP – chọn vị trí chính xác
// =============================================
defined('ABSPATH') || exit;

if (!session_id()) session_start();
$data = $_SESSION['match'] ?? [];

// ✅ TODO: đổi KEY thật tại đây hoặc lấy từ option/theme_mod
$GOOGLE_MAPS_API_KEY = defined('VSP_GOOGLE_MAPS_KEY') ? VSP_GOOGLE_MAPS_KEY : '';


// Nếu load trang lần đầu & chưa confirm → reset giá trị tọa độ tránh “kẹt toạ độ cũ”
if (empty($_POST) && !isset($_GET['confirmed'])) {
    unset($data['google_map']);
    $_SESSION['match']['google_map'] = '';
}

// Handle submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['tao_san_choi_nonce']) ||
        !wp_verify_nonce($_POST['tao_san_choi_nonce'], 'tao_san_choi_action')
    ) {
        echo '<p>❌ Phiên không hợp lệ.</p>';
        exit;
    }

    $_SESSION['match'] = array_merge($data, $_POST);
    wp_redirect('?step=' . ((int)($_GET['step'] ?? 4) + 1));
    exit;
}

get_header();
?>

<div class="form-create-match match-create-step<?php echo (int)($_GET['step'] ?? 4); ?>">
  <form id="step4-form" method="post">
    <?php wp_nonce_field('tao_san_choi_action', 'tao_san_choi_nonce'); ?>

    <label for="google_address">Địa chỉ cần tìm:</label>
    <input type="text" id="google_address" name="google_address" placeholder="Nhập tên sân, địa chỉ...">

    <div class="form-buttons" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
      <button type="button" class="btn btn-secondary" id="btn-search-map">Tìm</button>
      <button type="button" class="btn btn-primary" id="btn-confirm-location" disabled>Xác nhận vị trí</button>
    </div>

    <!-- Trường ẩn lưu toạ độ -->
    <input type="hidden" id="google_map" name="google_map"
           value="<?php echo esc_attr($data['google_map'] ?? ''); ?>">

    <!-- ✅ PHẢI có chiều cao cho map -->
    <div id="map-canvas"
         style="margin-top:20px;width:100%;height:420px;border:1px solid #ddd;border-radius:6px;"></div>
    <p style="margin-top:10px;">📌 Tọa độ đã chọn: <span id="current-coords">
      <?php echo isset($data['google_map']) && $data['google_map'] ? esc_html($data['google_map']) : 'Chưa xác định'; ?>
    </span></p>

    <div class="form-buttons" style="margin-top:12px;">
      <?php if (!empty($_GET['step']) && (int)$_GET['step'] > 1): ?>
        <button type="button" id="btn-back" class="btn btn-secondary">← Về trước</button>
      <?php endif; ?>
      <button type="submit" class="btn btn-primary">Tiếp tục</button>
    </div>
  </form>
</div>

<?php
// Gợi ý: có thể include content page bên dưới form để giữ style SWELL
include get_theme_file_path('templates/match/create-steps/match-create-content.php');
get_footer();
?>

<!-- ✅ Load Maps JS: thêm region/language cho kết quả ở Nhật -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($GOOGLE_MAPS_API_KEY); ?>&region=JP&language=ja&callback=initMap" async defer></script>

<script>
(function() {
  let map, geocoder, marker;

  // Helper
  function setCoords(lat, lng) {
    const val = lat.toFixed(6) + ',' + lng.toFixed(6);
    document.getElementById('google_map').value = val;
    document.getElementById('current-coords').textContent = val;
  }

  function toggleConfirmByZoom() {
    const btnConfirm = document.getElementById('btn-confirm-location');
    if (!btnConfirm || !map) return;
    const ok = map.getZoom() >= 16;     // yêu cầu zoom gần để xác nhận
    btnConfirm.disabled = !ok;
    btnConfirm.title = ok ? '' : 'Phóng to bản đồ (≥16) để xác nhận vị trí';
  }

  // ✅ Callback cho Google Maps (khai báo global)
  window.initMap = function() {
    const mapEl = document.getElementById('map-canvas');
    if (!mapEl) return;

    const defaultLatLng = { lat: 35.681236, lng: 139.767125 }; // 🗼 Tokyo Station
    geocoder = new google.maps.Geocoder();

    map = new google.maps.Map(mapEl, {
      center: defaultLatLng,
      zoom: 14,
      mapTypeControl: false,
      streetViewControl: false
    });

    // Marker
    marker = new google.maps.Marker({
      position: defaultLatLng,
      map,
      draggable: true
    });

    // Khôi phục toạ độ đã chọn trước đó
    const saved = document.getElementById('google_map').value;
    if (saved && saved !== "0,0") {
      const parts = saved.split(',').map(Number);
      if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1])) {
        const savedPos = { lat: parts[0], lng: parts[1] };
        map.setCenter(savedPos);
        map.setZoom(17);
        marker.setPosition(savedPos);
        setCoords(savedPos.lat, savedPos.lng);
        document.getElementById('btn-confirm-location').disabled = false;
      }
    } else {
      setCoords(defaultLatLng.lat, defaultLatLng.lng);
    }

    // Drag marker → cập nhật toạ độ, yêu cầu re-confirm
    marker.addListener('dragend', () => {
      const pos = marker.getPosition();
      setCoords(pos.lat(), pos.lng());
      document.getElementById('btn-confirm-location').disabled = map.getZoom() < 16;
      document.getElementById('btn-confirm-location').textContent = 'Xác nhận vị trí';
    });

    // Click map → đặt marker
    map.addListener('click', (e) => {
      marker.setPosition(e.latLng);
      if (map.getZoom() < 16) map.setZoom(17);
      setCoords(e.latLng.lat(), e.latLng.lng());
      document.getElementById('btn-confirm-location').disabled = false;
      document.getElementById('btn-confirm-location').textContent = 'Xác nhận vị trí';
    });

    map.addListener('zoom_changed', toggleConfirmByZoom);
    toggleConfirmByZoom();

    // Nút Tìm
    document.getElementById('btn-search-map').addEventListener('click', function() {
      const address = document.getElementById('google_address').value.trim();
      if (!address) { alert('⛔ Nhập địa chỉ trước khi tìm!'); return; }

      geocoder.geocode(
        { address, componentRestrictions: { country: 'JP' }}, // bias về JP
        function(results, status) {
          if (status === 'OK' && results && results[0]) {
            const loc = results[0].geometry.location;
            map.setCenter(loc);
            map.setZoom(17);
            marker.setPosition(loc);
            setCoords(loc.lat(), loc.lng());
            document.getElementById('btn-confirm-location').disabled = false;
            document.getElementById('btn-confirm-location').textContent = 'Xác nhận vị trí';
          } else {
            alert('⚠️ Không tìm được địa điểm: ' + status);
            console.warn('[Geocode]', status, results);
          }
        }
      );
    });

    // Nút Xác nhận → “chốt” lại và bật nút Tiếp tục
    document.getElementById('btn-confirm-location').addEventListener('click', function() {
      const pos = marker.getPosition();
      setCoords(pos.lat(), pos.lng());
      this.disabled = true;
      this.textContent = '✅ Đã xác nhận';

      const submitBtn = document.querySelector('#step4-form button[type="submit"]');
      if (submitBtn) { submitBtn.disabled = false; submitBtn.title = ''; }

      // Xoá nội dung ô địa chỉ cho gọn
      const addr = document.getElementById('google_address'); if (addr) addr.value = '';
    });

    // Back
    const backBtn = document.getElementById('btn-back');
    if (backBtn) {
      backBtn.addEventListener('click', () => {
        window.location.href = '?step=' + (<?php echo (int)($_GET['step'] ?? 4); ?> - 1);
      });
    }

    // Lúc đầu: nếu chưa có toạ độ → cấm submit
    const submitBtn = document.querySelector('#step4-form button[type="submit"]');
    if (submitBtn && !document.getElementById('google_map').value) {
      submitBtn.disabled = true;
      submitBtn.title = '⚠️ Vui lòng chọn & xác nhận vị trí';
    }
  };
})();
</script>
