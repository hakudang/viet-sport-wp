<!-- /**
 * File name :header-match.php
 * Description: Header riêng cho trang tạo và tìm kiếm sân chơi
 */ -->
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>

  <!-- ✅ Toggle Checkbox cho menu mobile -->
  <input type="checkbox" id="mobile-menu-toggle" class="mobile-menu-toggle" hidden>

  <header class="match-header">
    <div class="container match-header__inner">

      <!-- ✅ LOGO cho PC -->
      <div class="match-header__logo match-header__logo--desktop">
        <a href="<?php echo esc_url(home_url('/match')); ?>">
          <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-vietsport.png" alt="VietSport Logo">
        </a>
      </div>

      <!-- ✅ HEADER MOBILE -->
      <div class="match-mobile-bar">
        <!-- ☰ Hamburger icon -->
        <label for="mobile-menu-toggle" class="mobile-menu-icon">&#9776;</label>

        <!-- Logo mobile (giữa) -->
        <div class="match-header__logo match-header__logo--mobile">
          <a href="<?php echo esc_url(home_url('/match')); ?>">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo-vietsport.png" alt="VietSport Logo">
          </a>
        </div>

        <!-- 🔍 Search icon -->
        <div class="match-header__search-icon">
          <a href="/match/search" title="Tìm kiếm">🔍</a>
        </div>
      </div>

      <!-- ✅ NAV PC -->
      <nav class="match-header__nav">
        <ul class="match-nav">
          <li><a href="/match/create">Tạo sân chơi</a></li>
          <li><a href="/match/messages">Tin nhắn</a></li>
          <li><a href="/match/joined">Tham gia</a></li>
          <li><a href="/match/hosted">Chủ xị</a></li>
          <li><a href="/match/viewing">Đang xem</a></li>
          <li><a href="/match/following">Theo dõi</a></li>
          <li><a href="/match/notifications">Thông báo</a></li>
          <li><a href="/match/alerts">Cảnh báo</a></li>
          <li><a href="/match/more">Khác</a></li>
          <li><a href="/my-account">My Account</a></li>
          <li><a href="<?php echo esc_url(wp_logout_url()); ?>">Logout</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- ✅ MENU MOBILE SLIDE -->
  <div class="match-mobile-menu">
    <div class="match-mobile-menu__header">
      <label for="mobile-menu-toggle" class="close-menu-icon">✖</label>
      <strong>MENU</strong>
    </div>
    <nav>
      <ul class="match-nav">
        <li><a href="/match/create">Tạo sân chơi</a></li>
        <li><a href="/match/messages">Tin nhắn</a></li>
        <li><a href="/match/joined">Tham gia</a></li>
        <li><a href="/match/hosted">Chủ xị</a></li>
        <li><a href="/match/viewing">Đang xem</a></li>
        <li><a href="/match/following">Theo dõi</a></li>
        <li><a href="/match/notifications">Thông báo</a></li>
        <li><a href="/match/alerts">Cảnh báo</a></li>
        <li><a href="/match/more">Khác</a></li>
        <li><a href="/my-account">My Account</a></li>
        <li><a href="<?php echo esc_url(wp_logout_url()); ?>">Logout</a></li>
      </ul>
    </nav>
  </div>

</body>
</html>
