<?php
/**
 * file name: templates/match/match-search-by-district-form.php
 * Form: Tìm kiếm sân chơi theo tỉnh thành (Prefecture & Region)
 * Description: Hiển thị danh sách các tỉnh Nhật Bản chia theo khu vực, mỗi tỉnh là 1 link tới /pref/{id}
 */
?>

<div class="prefecture-list">
    <h4 class="prefecture-title">Tìm sân chơi theo tỉnh thành</h4>

    <div class="prefecture-columns">
        <!-- Cột trái -->
        <div class="prefecture-column">
            <!-- Khu vực: Hokkaido Tohoku -->
            <div class="region">
                <div class="region-header">北海道 東北</div>
                <div class="prefecture-links">
                    <a class="prefecture-link" href="/pref/0">北海道</a>
                    <a class="prefecture-link" href="/pref/1">青森</a>
                    <a class="prefecture-link" href="/pref/2">岩手</a>
                    <a class="prefecture-link" href="/pref/3">宮城</a>
                    <a class="prefecture-link" href="/pref/4">秋田</a>
                    <a class="prefecture-link" href="/pref/5">山形</a>
                    <a class="prefecture-link" href="/pref/6">福島</a>
                </div>
            </div>

            <!-- Khu vực: Kanto -->
            <div class="region">
                <div class="region-header">関東</div>
                <div class="prefecture-links">
                    <a class="prefecture-link" href="/pref/7">茨城</a>
                    <a class="prefecture-link" href="/pref/8">栃木</a>
                    <a class="prefecture-link" href="/pref/9">群馬</a>
                    <a class="prefecture-link" href="/pref/10">埼玉</a>
                    <a class="prefecture-link" href="/pref/11">千葉</a>
                    <a class="prefecture-link" href="/pref/12">東京</a>
                    <a class="prefecture-link" href="/pref/13">神奈川</a>
                </div>
            </div>

            <!-- Khu vực: Chugoku Shikoku -->
            <div class="region">
                <div class="region-header">中国 四国</div>
                <div class="prefecture-links">
                    <a class="prefecture-link" href="/pref/30">鳥取</a>
                    <a class="prefecture-link" href="/pref/31">島根</a>
                    <a class="prefecture-link" href="/pref/32">岡山</a>
                    <a class="prefecture-link" href="/pref/33">広島</a>
                    <a class="prefecture-link" href="/pref/34">山口</a>
                    <a class="prefecture-link" href="/pref/35">徳島</a>
                    <a class="prefecture-link" href="/pref/36">香川</a>
                    <a class="prefecture-link" href="/pref/37">愛媛</a>
                    <a class="prefecture-link" href="/pref/38">高知</a>
                </div>
            </div>
        </div>

        <!-- Cột phải -->
        <div class="prefecture-column">
            <!-- Khu vực: Chubu -->
            <div class="region">
                <div class="region-header">中部</div>
                <div class="prefecture-links">
                    <a class="prefecture-link" href="/pref/14">新潟</a>
                    <a class="prefecture-link" href="/pref/15">富山</a>
                    <a class="prefecture-link" href="/pref/16">石川</a>
                    <a class="prefecture-link" href="/pref/17">福井</a>
                    <a class="prefecture-link" href="/pref/18">山梨</a>
                    <a class="prefecture-link" href="/pref/19">長野</a>
                    <a class="prefecture-link" href="/pref/20">岐阜</a>
                    <a class="prefecture-link" href="/pref/21">静岡</a>
                    <a class="prefecture-link" href="/pref/22">愛知</a>
                </div>
            </div>

            <!-- Khu vực: Kinki -->
            <div class="region">
                <div class="region-header">近畿</div>
                <div class="prefecture-links">
                    <a class="prefecture-link" href="/pref/23">三重</a>
                    <a class="prefecture-link" href="/pref/24">滋賀</a>
                    <a class="prefecture-link" href="/pref/25">京都</a>
                    <a class="prefecture-link" href="/pref/26">大阪</a>
                    <a class="prefecture-link" href="/pref/27">兵庫</a>
                    <a class="prefecture-link" href="/pref/28">奈良</a>
                    <a class="prefecture-link" href="/pref/29">和歌山</a>
                </div>
            </div>

            <!-- Khu vực: Kyushu -->
            <div class="region">
                <div class="region-header">九州</div>
                <div class="prefecture-links">
                    <a class="prefecture-link" href="/pref/39">福岡</a>
                    <a class="prefecture-link" href="/pref/40">佐賀</a>
                    <a class="prefecture-link" href="/pref/41">長崎</a>
                    <a class="prefecture-link" href="/pref/42">熊本</a>
                    <a class="prefecture-link" href="/pref/43">大分</a>
                    <a class="prefecture-link" href="/pref/44">宮崎</a>
                    <a class="prefecture-link" href="/pref/45">鹿児島</a>
                    <a class="prefecture-link" href="/pref/46">沖縄</a>
                </div>
            </div>
        </div>
    </div>
</div>
