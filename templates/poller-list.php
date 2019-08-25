<?php
/**
 * Template - Poller List
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

<div class="wpp-poller-list-container">

    <h3><?php esc_html_e( sprintf( 'Poll Title: %s', $poll->get_name() ), 'wp-poll' ); ?></h3>


    <table class="wpp-poller-list">
        <tr>
            <th><?php esc_html_e( 'Poller', 'wp-poll' ); ?></th>

			<?php foreach ( $poll->get_poll_options() as $option ) : ?>
                <th><?php echo esc_html( $option['label'] ); ?></th>
			<?php endforeach; ?>
        </tr>

		<?php foreach ( $poll->get_meta( 'polled_data', array() ) as $user => $data ) :

			$poller = get_user_by( 'ID', $user );
			?>
            <tr>
                <td><?php echo empty( $poller ) ? $user : $poller->display_name; ?></td>
				<?php foreach ( $poll->get_poll_options() as $option_id => $option ) : ?>
					<?php printf( '<td>%s</td>', in_array( $option_id, $data ) ? '<i class="icofont-check-alt"></i>' : '' ); ?>
				<?php endforeach; ?>
            </tr>
		<?php endforeach; ?>
    </table>
</div>