<?php

/**
 * @see \WPStaging\Backup\Ajax\Listing::render
 */

?>

<div class="wpstg--full-screen-overlay" id="wpstg-restore-wait">
    <div class="wpstg-logo">
        <img class="wpstg-logo-light" src="<?php echo esc_url(WPSTG_PLUGIN_URL . "assets/img/logo.svg"); ?>">
        <img class="wpstg-logo-dark" src="<?php echo esc_url(WPSTG_PLUGIN_URL . "assets/img/dark-logo.svg"); ?>">
    </div>
    <div class="wpstg-title"><?php esc_html_e('Backup Restore Successful!', 'wp-staging') ?></div>
    <div class="wpstg-text"><?php esc_html_e('You are being redirected to the login page...', 'wp-staging') ?></div>
</div>
