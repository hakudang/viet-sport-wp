<?php
/**
 * File: swell_child/functions/match/create-steps/match-create-step1.php
 * Step 1: Chọn bộ môn (sport) + nhập tiêu đề
 */
if (!session_id()) session_start();

// Lấy dữ liệu đã nhập trước đó (nếu có)
$data = $_SESSION['match'] ?? [];

// Xử lý submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        !isset($_POST['tao_san_choi_nonce']) ||
        !wp_verify_nonce($_POST['tao_san_choi_nonce'], 'tao_san_choi_action')
    ) {
        echo '<p>❌ Phiên không hợp lệ.</p>';
        exit;
    }

    // Gom vào session
    $_SESSION['match'] = array_merge($data, [
        'sport' => isset($_POST['sport']) ? sanitize_text_field($_POST['sport']) : '',
        'title' => isset($_POST['title']) ? sanitize_text_field($_POST['title']) : '',
    ]);

    // Sang bước 2
    wp_redirect('?step=' . ($current_step + 1));
    exit;
}

// Chuẩn bị danh sách sport từ taxonomy (nếu có)
$sport_terms   = [];
$use_select    = taxonomy_exists('match_sport');
if ($use_select) {
    $sport_terms = get_terms([
        'taxonomy'   => 'match_sport',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);
    if (is_wp_error($sport_terms) || empty($sport_terms)) $use_select = false;
}

$current_sport = $data['sport'] ?? '';
$current_title = $data['title'] ?? '';

// Header SWELL
get_header();
?>

<div class="form-create-match match-create-step<?php echo (int) $current_step; ?>">
    <form method="post" novalidate>
        <?php wp_nonce_field('tao_san_choi_action', 'tao_san_choi_nonce'); ?>

        <!-- SPORT: ưu tiên chọn từ taxonomy; fallback ô text -->
        <label for="sport" style="display:block;margin-top:12px;">Tên bộ môn (Sport): <span style="color:red">*</span></label>

        <?php if ($use_select) : ?>
            <select id="sport" name="sport" required>
                <option value="">-- Chọn bộ môn --</option>
                <?php foreach ($sport_terms as $t): ?>
                    <option value="<?php echo esc_attr($t->slug); ?>"
                        <?php selected($current_sport, $t->slug); ?>>
                        <?php echo esc_html($t->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <!-- <small>Không thấy bộ môn? Bạn có thể thêm trong WP-Admin → Matches → Sports.</small> -->
        <?php else: ?>
            <input
                type="text"
                id="sport_text"
                name="sport"
                placeholder="Ví dụ: tennis, pickleball..."
                required
                value="<?php echo esc_attr($current_sport); ?>">
            <small>(Hiện chưa có taxonomy <code>match_sport</code> hoặc chưa seed – nhập tên/slug bộ môn)</small>
        <?php endif; ?>

        <!-- TITLE -->
        <label for="title" style="display:block;margin-top:16px;">Tiêu đề: <span style="color:red">*</span></label>
        <input
            type="text"
            id="title"
            name="title"
            required
            value="<?php echo esc_attr($current_title); ?>">

        <!-- ACTIONS -->
        <div class="form-buttons" style="margin-top:16px;">
            <button id="next-btn" type="submit" class="btn btn-primary" disabled>Tiếp tục</button>
        </div>
    </form>
</div>

<?php include SWELL_CHILD_PATH . '/functions/match/create-steps/match-create-content.php'; ?>
<?php get_footer(); ?>

<style>
    #next-btn:disabled { opacity:.5; cursor:not-allowed; }
    .form-create-match input[type="text"],
    .form-create-match select { max-width: 520px; width: 100%; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('title');
    const nextBtn    = document.getElementById('next-btn');
    const sportSel   = document.getElementById('sport');       // nếu dùng select
    const sportTxt   = document.getElementById('sport_text');  // nếu dùng text

    function hasSport() {
        if (sportSel) return sportSel.value.trim() !== '';
        if (sportTxt) return sportTxt.value.trim() !== '';
        return false;
    }

    function toggleButton() {
        const ok = titleInput.value.trim() !== '' && hasSport();
        nextBtn.disabled = !ok;
    }

    ['input','change','blur'].forEach(evt => titleInput.addEventListener(evt, toggleButton));
    if (sportSel) sportSel.addEventListener('change', toggleButton);
    if (sportTxt) ['input','change','blur'].forEach(evt => sportTxt.addEventListener(evt, toggleButton));

    // run once
    toggleButton();
});
</script>
