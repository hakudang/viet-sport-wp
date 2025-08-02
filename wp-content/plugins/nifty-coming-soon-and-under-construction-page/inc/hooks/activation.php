<?php
/**
 * Activation
 *
 * @package NCSUCP
 */

/**
 * Hook activation.
 *
 * @since 1.0.0
 */
function nifty_cs_plugin_activation() {
	nifty_cs_migrate_plugin_options();
}

register_activation_hook( NCSUCP_BASE_FILEPATH, 'nifty_cs_plugin_activation' );
