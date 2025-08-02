<?php
/**
 * Logo template
 *
 * @package NCSUCP
 */

$blog_name          = get_bloginfo( 'name' );
$logo_status        = nifty_cs_get_option( 'disable_logo' );
$display_site_title = nifty_cs_get_option( 'display_site_title' );
$logo_url           = nifty_cs_get_option( 'upload_your_logo' );
?>

<header class="nifty-block nifty-logo">
	<?php

		// Use Logo is true.
	if ( 'off' !== $logo_status ) {
		if ( ! empty( $logo_url ) ) {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '"><img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( $blog_name ) . '" /></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	} elseif ( 'off' !== $display_site_title ) {
		// Show site title text.
		echo '<h1 class="nifty-title">' . $blog_name . '</h1>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	?>
</header>
