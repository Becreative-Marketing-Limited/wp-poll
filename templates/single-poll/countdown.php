<?php
/**
 * Template - Single Poll - Countdown
 *
 * @package single-poll/countdown
 * @author Liquidpoll
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

global $poll;

if ( $poll->hide_countdown_timer() || ! $poll->ready_to_vote() ) {
	return;
}

$unique_id     = uniqid();
$poll_deadline = $poll->get_poll_deadline();
$timer_type    = empty( $poll->timer_type ) ? $poll->get_meta( '_timer_type', 'regular' ) : $poll->timer_type;
$votes_count   = (int) Utils::get_args_option( 'total', $poll->get_poll_results(), 0 );
$total_count   = sprintf( _n( '%s <span>vote</span>', '%s <span>votes</span>', $votes_count, 'wp-poll' ), number_format_i18n( $votes_count ) );

if ( ! in_array( $poll->get_theme(), array( 10, 11, 12 ) ) ) {
	$timer_type = 'regular';
}

$classes[] = 'liquidpoll-countdown-timer';
$classes[] = sprintf( 'liquidpoll-countdown-timer-%s', $poll->get_style( 'countdown' ) );
$classes[] = sprintf( 'timer-type-%s', $timer_type );

if ( 'with_votes' == $timer_type ) {
	$classes[] = 'liquidpoll-vote-survey';
}


?>
    <div id="liquidpoll-<?php echo esc_attr( $unique_id ); ?>" class="<?php echo esc_attr( liquidpoll_generate_classes( $classes ) ); ?>"></div>

    <script>
        (function ($,) {
            "use strict";

            (function updateTime() {

                let countDownDate = new Date(new Date('<?php echo esc_html( $poll_deadline ); ?>').toString()).getTime(),
                    now = new Date().getTime(),
                    seconds = Math.floor((countDownDate - (now)) / 1000),
                    minutes = Math.floor(seconds / 60),
                    hours = Math.floor(minutes / 60),
                    days = Math.floor(hours / 24),
                    timerWrap = $("#liquidpoll-<?php echo esc_attr( $unique_id ); ?>");

                hours = hours - (days * 24);
                minutes = minutes - (days * 24 * 60) - (hours * 60);
                seconds = seconds - (days * 24 * 60 * 60) - (hours * 60 * 60) - (minutes * 60);

                if (timerWrap.hasClass('timer-type-regular')) {
                    timerWrap.html(
                        '<span class="days"><span class="count-number">' + days + '</span><span class="count-text"><?php esc_html_e( 'Days', 'wp-poll' ); ?></span></span>' +
                        '<span class="hours"><span class="count-number">' + hours + '</span><span class="count-text"><?php esc_html_e( 'Hours', 'wp-poll' ); ?></span></span>' +
                        '<span class="minutes"><span class="count-number">' + minutes + '</span><span class="count-text"><?php esc_html_e( 'Minutes', 'wp-poll' ); ?></span></span>' +
                        '<span class="seconds"><span class="count-number">' + seconds + '</span><span class="count-text"><?php esc_html_e( 'Seconds', 'wp-poll' ); ?></span></span>');

                    setTimeout(updateTime, 1000);
                }

                if (timerWrap.hasClass('timer-type-with_votes')) {

                    let left = '';

                    if (days > 0) {
                        if (days === 1) {
                            left = days + ' <span>day left</span>';
                        } else {
                            left = days + ' <span>days left</span>';
                        }
                    } else if (hours > 0) {
                        left = hours + ' <span>hours left</span>';
                    } else if (minutes > 0) {
                        left = minutes + ' <span>minutes left</span>';
                    } else if (seconds > 0) {
                        left = minutes + ' <span>seconds left</span>';
                    }

                    timerWrap.html('<ul>' + '<li><?= $total_count ?></li>' + '<li><span class="dots"></span></li>' + '<li>' + left + '</li>' + '</ul>');
                }
            })();

        })(jQuery);
    </script>


<?php liquidpoll_apply_css( '.liquidpoll-countdown-timer > span', $poll->get_css_args( '_typography_countdown_timer' ) ); ?>