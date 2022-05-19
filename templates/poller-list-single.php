<?php
/**
 * Template - Poller List Single Option
 *
 * @shortcode poller_list
 *
 * @args poll_id
 * @args option_id
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$poll_id   = isset( $args['poll_id'] ) ? $args['poll_id'] : '';
$option_id = isset( $args['option_id'] ) ? $args['option_id'] : '';
$poll      = wpp_get_poll( $poll_id );

if ( ! $poll || empty( $poll ) ) {
	exit;
}

?>

<div class="wpp-poller-list-single-container">

	<?php if ( ! empty( $option_id ) ) : ?>
        <p><?php esc_html_e( sprintf( 'You are seeing results for the option \'%s\' only', $poll->get_option_label( $option_id ) ), 'wp-poll' ); ?></p>
	<?php endif; ?>

    <table class="wpp-poller-list-single">
        <tr>
            <th><?php esc_html_e( sprintf( '%s - %s', $poll->get_name(), $poll->get_option_label( $option_id ) ), 'wp-poll' ); ?></th>
        </tr>

		<?php foreach ( $poll->get_meta( 'polled_data', array() ) as $user => $data ) :
			$poller = get_user_by( 'ID', $user );
			?>
            <tr>
                <td><?php echo empty( $poller ) ? $user : $poller->display_name; ?></td>
            </tr>
		<?php endforeach; ?>
    </table>
</div>