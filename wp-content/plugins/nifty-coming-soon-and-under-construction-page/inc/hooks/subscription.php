<?php
/**
 * Subscription
 *
 * @package NCSUCP
 */

/**
 * Send subscription email.
 *
 * @since 1.0.0
 */
function nifty_cs_subscription() {
	$data = array();

	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
	if ( ! isset( $_POST['email'] ) || ! filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) || ! sanitize_email( $_POST['email'] ) ) {
		$error = new WP_Error( 'invalid-email', 'Email is invalid' );
		wp_send_json_error( $error, '500' );
	}

	$signup_email = sanitize_email( wp_unslash( $_POST['email'] ) );

	$subject = 'New subscriber from coming soon page';

	$body = 'Hello, we have a new subscription from the Coming soon page.';

	if ( ! empty( $signup_email ) ) {
		$body .= "\r\nEmail address: {$signup_email }";
	}

	$to_email = '';

	$contact_email = sanitize_email( nifty_cs_get_option( 'enter_your_email_address' ) );

	if ( ! empty( $contact_email ) ) {
		$to_email = $contact_email;
	}

	$signup_to_email = sanitize_email( nifty_cs_get_option( 'sign_up_email_to' ) );

	if ( ! empty( $signup_to_email ) ) {
		$to_email = $signup_to_email;
	}

	if ( empty( $to_email ) ) {
		$to_email = sanitize_email( get_option( 'admin_email' ) );
	}

	if ( ! $to_email ) {
		$error = new WP_Error( 'invalid-setting', 'Setting is invalid' );
		wp_send_json_error( $error, '500' );
	}

	$mail_status = wp_mail( $to_email, $subject, $body, 'From:' . $signup_email );

	if ( true === $mail_status ) {
		$data['message'] = 'Email sent successfully.';
		wp_send_json_success( $data );
	}

	$error = new WP_Error( 'error-sending-email', 'Error sending email' );
	wp_send_json_error( $error, '500' );
}

add_action( 'wp_ajax_nifty_cs_subscribe', 'nifty_cs_subscription' );
add_action( 'wp_ajax_nopriv_nifty_cs_subscribe', 'nifty_cs_subscription' );
