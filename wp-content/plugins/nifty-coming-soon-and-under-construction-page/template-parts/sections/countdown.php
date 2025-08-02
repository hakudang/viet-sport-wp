<?php
/**
 * Countdown template
 *
 * @package NCSUCP
 */

$coundown_time = nifty_cs_get_option( 'setup_the_count_down_timer' );

if ( empty( $coundown_time ) ) {
	return;
}

$days_translate    = nifty_cs_get_option( 'nifty_days_translate' );
$hours_translate   = nifty_cs_get_option( 'nifty_hours_translate' );
$minutes_translate = nifty_cs_get_option( 'nifty_minutes_translate' );
$seconds_translate = nifty_cs_get_option( 'nifty_seconds_translate' );
?>

<div id="clock" class="nifty-block nifty-timer">
	<div class="nifty-columns nifty-columns-4">
		<div class="timer-item">
			<div class="timer-top"><span id="days"></span></div>
			<div class="timer-bottom timer-days"><?php echo esc_html( $days_translate ); ?></div>
		</div>

		<div class="timer-item">
			<div class="timer-top"><span id="hours"></span></div>
			<div class="timer-bottom timer-hours"><?php echo esc_html( $hours_translate ); ?></div>
		</div>

		<div class="timer-item">
			<div class="timer-top"><span id="minutes"></span></div>
			<div class="timer-bottom timer-minutes"><?php echo esc_html( $minutes_translate ); ?></div>
		</div>

		<div class="timer-item">
			<div class="timer-top"><span id="seconds"></span></div>
			<div class="timer-bottom timer-seconds"><?php echo esc_html( $seconds_translate ); ?></div>
		</div>
	</div>
</div>
