<?php
/**
 * Template - Single Poll - Responses
 *
 * @package single-poll/responses
 * @author Liquidpoll
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

global $poll;

?>

<?php if ( ! $poll->ready_to_vote() ) : ?>
    <p class="liquidpoll-responses display liquidpoll-warning">
        <span class="icon-box"></span>
        <span class="message"><?php esc_html_e( Utils::get_option( 'liquidpoll_poll_text_expired', 'This poll has expired.' ), 'wp-poll' ); ?></span>
    </p>
<?php endif; ?>

<p class="liquidpoll-responses">
    <span class="icon-box"></span>
    <span class="message"></span>
    <svg class="close" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M9 3L3 9" stroke="#22D153" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M3 3L9 9" stroke="#22D153" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</p>
