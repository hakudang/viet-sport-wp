<?php
/**
 * Uninstall
 *
 * @package NCSUCP
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove plugin options.
delete_option( 'nifty_cs_options' );
