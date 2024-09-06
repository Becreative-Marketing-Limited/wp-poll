<?php
/**
 * Results Edit Template
 */

use WPDK\Utils;

global $wpdb;

// Sanitize and validate the 'id' parameter
$result_id = isset($_GET['id']) ? absint(wp_unslash($_GET['id'])) : 0;

$result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}liquidpoll_results WHERE id = %d", $result_id));

if (empty($result)) {
    return;
}

$poll = liquidpoll_get_poll($result->poll_id);

?>

<div class="liquidpoll-result-edit">
    <div class="liquidpoll-result-details">

        <div class="liquidpoll-edit-item">
            <div class="item-title">Poll</div>
            <div class="item-value"><?php echo esc_html($poll->get_name()); ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Poll Type</div>
            <div class="item-value"><?php echo esc_html($poll->get_type()); ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Polled By</div>
            <div class="item-value"><?php echo esc_html(get_user_by('id', $result->user_id)->display_name); ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Polled Value</div>
            <div class="item-value"><?php echo esc_html($result->polled_value); ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Title</div>
            <div class="item-value"><?php echo esc_html(liquidpoll_get_results_meta($result_id, 'review_title')); ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Comments</div>
            <div class="item-value"><?php echo wpautop($result->polled_comments); ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Date Time</div>
            <div class="item-value"><?php echo esc_html(gmdate("F j, Y", strtotime($result->created_at))); ?></div>
        </div>

        <div class="liquidpoll-edit-item">
            <div class="item-title">Experienced Time</div>
            <div class="item-value"><?php echo esc_html(gmdate("F j, Y", strtotime(liquidpoll_get_results_meta($result_id, 'experience_time')))); ?></div>
        </div>

    </div>

    <div class="liquidpoll-result-replies">

        <div class="liquidpoll-replies">

			<?php foreach ( liquidpoll_get_results_meta($result_id, 'result_replies', array()) as $reply ) : $reply_author = get_user_by('id', $reply['user_id']); ?>

                <div class="liquidpoll-reply">
                    <div class="reply-author">
                        <div class="author-image"><img src="<?php echo esc_url(get_avatar_url($reply_author->ID)); ?>" alt="<?php echo esc_attr($reply_author->display_name); ?>"></div>
                        <div class="author-details">
                            <p class="author-name"><?php echo esc_html($reply_author->display_name); ?></p>
                            <p class="reply-time"><?php echo esc_html(gmdate("jS M Y, g:i a", strtotime($reply['datetime']))); ?></p>
                        </div>
                    </div>
                    <div class="reply-content">
						<?php echo wpautop($reply['reply_content']); ?>
                    </div>
                </div>

			<?php endforeach; ?>
        </div>

        <form class="liquidpoll-reply-box" action="" method="post">
            <div class="textarea-wrap">
                <label for="liquidpoll-result-reply">Submit Reply</label>
                <textarea required name="result_reply" id="liquidpoll-result-reply" rows="5" placeholder="Start typing reply..."></textarea>
            </div>
            <input type="hidden" name="result_id" value="<?php echo esc_attr($result_id); ?>">
            <button type="submit" class="liquidpoll-button">Send Reply</button>
        </form>
    </div>

    <div class="liquidpoll-result-reports">
        <h2> Review reports</h2>
        <div class="liquidpoll-review-report">

			<?php foreach ( liquidpoll_get_results_meta($result_id, 'results_report_data', array()) as $report ) : $report_author = get_user_by('id', $report['poller_id_ip']); ?>

                <div class="reply-author">
                    <div class="author-image"><img src="<?php echo esc_url(get_avatar_url($report_author->ID)); ?>" alt="<?php echo esc_attr($report_author->display_name); ?>"></div>
                    <div class="author-details">
                        <p class="author-name"><?php echo esc_html($report_author->display_name); ?></p>
                        <p class="reply-time"><?php echo esc_html(gmdate("jS M Y, g:i a", strtotime($report['datetime']))); ?></p>
                    </div>
                </div>

                <div class="liquidpoll-edit-item">
                    <div class="item-title">Report Reason</div>
                    <div class="item-value"><?php echo esc_html($report['report_reason']); ?></div>
                </div>

                <div class="liquidpoll-edit-item">
                    <div class="item-title">Report E-mail</div>
                    <div class="item-value"><?php echo esc_html($report['report_email']); ?></div>
                </div>

                <div class="liquidpoll-edit-item">
                    <div class="item-title">Purchase Consent</div>
                    <div class="item-value"><?php echo esc_html($report['purchase_consent'] ?? 'No'); ?></div>
                </div>

			<?php endforeach; ?>

        </div>
    </div>
</div>
