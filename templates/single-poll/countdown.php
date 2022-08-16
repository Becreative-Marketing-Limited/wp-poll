<?php
/**
 * Template - Single Poll - Countdown
 *
 * @package single-poll/countdown
 * @author Pluginbazar
 */

defined( 'ABSPATH' ) || exit;

global $poll;

if ( $poll->hide_countdown_timer() || ! $poll->ready_to_vote() ) {
	return;
}

$poll_deadline = $poll->get_poll_deadline();
$unique_id     = uniqid();

?>

    <div id="liquidpoll-countdown-timer-<?php echo esc_attr( $unique_id ); ?>"
         class="liquidpoll-countdown-timer liquidpoll-countdown-timer-<?php echo esc_attr( $poll->get_style( 'countdown' ) ); ?>"></div>

    <script>
        (function ($,) {
            "use strict";

            (function updateTime() {

                let countDownDate = new Date(new Date('<?php echo esc_html( $poll_deadline ); ?>').toString()).getTime(),
                    now = new Date().getTime(),
                    seconds = Math.floor((countDownDate - (now)) / 1000),
                    minutes = Math.floor(seconds / 60),
                    hours = Math.floor(minutes / 60),
                    days = Math.floor(hours / 24);

                hours = hours - (days * 24);
                minutes = minutes - (days * 24 * 60) - (hours * 60);
                seconds = seconds - (days * 24 * 60 * 60) - (hours * 60 * 60) - (minutes * 60);

                $("#liquidpoll-countdown-timer-<?php echo esc_attr( $unique_id ); ?>").html(
                    '<span class="days"><span class="count-number">' + days + '</span><span class="count-text"><?php esc_html_e( 'Days', 'wp-poll' ); ?></span></span>' +
                    '<span class="hours"><span class="count-number">' + hours + '</span><span class="count-text"><?php esc_html_e( 'Hours', 'wp-poll' ); ?></span></span>' +
                    '<span class="minutes"><span class="count-number">' + minutes + '</span><span class="count-text"><?php esc_html_e( 'Minutes', 'wp-poll' ); ?></span></span>' +
                    '<span class="seconds"><span class="count-number">' + seconds + '</span><span class="count-text"><?php esc_html_e( 'Seconds', 'wp-poll' ); ?></span></span>');

                setTimeout(updateTime, 1000);
            })();

        })(jQuery);
    </script>


<?php liquidpoll_apply_css( '.liquidpoll-countdown-timer > span', $poll->get_css_args( '_typography_countdown_timer' ) ); ?>