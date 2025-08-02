<?php
/**
 * Init
 *
 * @package NCSUCP
 */

// Customizer.
require_once NCSUCP_DIR . '/inc/customizer/init.php';

// Helpers.
require_once NCSUCP_DIR . '/inc/helpers/core.php';
require_once NCSUCP_DIR . '/inc/helpers/utils.php';
require_once NCSUCP_DIR . '/inc/helpers/themes.php';
require_once NCSUCP_DIR . '/inc/helpers/helpers.php';
require_once NCSUCP_DIR . '/inc/helpers/options.php';

// Hooks.
require_once NCSUCP_DIR . '/inc/hooks/hooks.php';
require_once NCSUCP_DIR . '/inc/hooks/admin.php';
require_once NCSUCP_DIR . '/inc/hooks/subscription.php';
require_once NCSUCP_DIR . '/inc/hooks/redirect.php';
require_once NCSUCP_DIR . '/inc/hooks/activation.php';

// Admin page.
require_once NCSUCP_DIR . '/inc/admin-page/admin-page.php';
