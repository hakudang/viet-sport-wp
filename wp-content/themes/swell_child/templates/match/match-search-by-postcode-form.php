<?php
/**
 * file name: templates/match/match-search-by-postcode-form.php
 * Form tìm kiếm sân chơi theo mã bưu điện (postcode)
 * Hiển thị tại trang /match
 * Thuộc module "match" trong theme swell_child của dự án VietSport
 */
?>

<div class="searchOnTop-form-container">
    <h4 class="form-title">Tìm sân chơi gần bạn</h4>

    <form class="search-form" method="POST" action="/postalCode/searchOnTop">
        <input type="hidden" name="_csrf" value="dummy-csrf">

        <div class="form-group">
            <label for="postalCode" class="input-label">Mã bưu điện</label>
            <div class="input-fields">
                <input class="postal-code-input" placeholder="2430432" type="text" inputmode="numeric" required name="postalCode">
            </div>

            <label for="radiusKilo" class="input-label">Bán kính</label>
            <div class="radius-select">
                <select name="radiusKilo" id="radiusKilo">
                    <option value="K3">3Km</option>
                    <option value="K5">5Km</option>
                    <option value="K10">10Km</option>
                    <option value="K15" selected>15Km</option>
                    <option value="K20">20Km</option>
                    <option value="K30">30Km</option>
                </select>
            </div>

            <button class="submit-button" type="submit">
                <span class="submit-icon">🔍</span>
                Tìm kiếm
            </button>
        </div>
    </form>

    <div class="select-container">
        <select class="saved-condition-select">
            <option value="none">Bộ lọc đã lưu</option>
            <option value="163188571025152868">15km</option>
        </select>
    </div>
</div>
