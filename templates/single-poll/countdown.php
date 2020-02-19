<?php
/**
 * Template - Single Poll - Countdown
 *
 * @package single-poll/countdown
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;


if( $poll->hide_countdown_timer() || empty( $poll_deadline = $poll->get_poll_deadline() ) ) {
    return;
}

$unique_id = uniqid();

?>

<div id="wpp-countdown-timer-<?php echo esc_attr( $unique_id ); ?>"
     class="wpp-countdown-timer-<?php echo esc_attr( $poll->get_style( 'countdown' ) ); ?>"></div>

<script>
    (function ($, window, document) {
        "use strict";

        (function updateTime() {

            var countDownDate = new Date(new Date('<?php echo esc_html( $poll_deadline ); ?>').toString()).getTime(),
                now = new Date().getTime(),
                distance = countDownDate - now,
                days = 0, hours = 0, minutes = 0, seconds = 0;

            if (distance > 0) {
                days = Math.floor(distance / (1000 * 60 * 60 * 24));
                hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60) + days * 24);
                minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                seconds = Math.floor((distance % (1000 * 60)) / 1000);
            }

            $("#wpp-countdown-timer-<?php echo esc_attr( $unique_id ); ?>").html(
                '<span class="hours"><span class="count-number">' + hours + '</span><span class="count-text"><?php esc_html_e( 'Hours', 'wp-poll' ); ?></span></span>' +
                '<span class="minutes"><span class="count-number">' + minutes + '</span><span class="count-text"><?php esc_html_e( 'Minutes', 'wp-poll'); ?></span></span>' +
                '<span class="seconds"><span class="count-number">' + seconds + '</span><span class="count-text"><?php esc_html_e( 'Seconds', 'wp-poll' ); ?></span></span>');

            setTimeout(updateTime, 1000);
        })();

    })(jQuery, window, document);
</script>