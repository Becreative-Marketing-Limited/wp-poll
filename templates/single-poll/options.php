<?php
/**
 * Single Poll - Options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;


?>
    <div <?php wpp_options_single_class( 'wpp-options' ); ?>>

		<?php
		foreach ( $poll->get_poll_options() as $option_id => $option ) :

			$args = array_merge( array( 'option_id' => $option_id ), $option );

			wpp_get_template( 'single-poll/options-single.php', $args );

		endforeach;
		?>

    </div>

<?php if ( $poll->get_poll_type() === 'poll' ) : ?>

    <div class="wpp-popup-container">
        <div class="wpp-popup-box">
            <span class="box-close dashicons dashicons-no-alt"></span>
            <div class="wpp-new-option">
                <input type="text" placeholder="<?php esc_attr_e( 'Your option', 'wp-poll' ); ?>">
                <span class="wpp-notice-warning"
                      style="display: none;"><?php esc_html_e( 'Please write some text !', 'wp-poll' ) ?></span>
                <button class="wpp-button wpp-button-blue"
                        data-pollid="<?php echo esc_attr( $poll->get_id() ); ?>"><?php esc_html_e( 'Add Option', 'wp-poll' ); ?></button>
            </div>
        </div>
    </div>

<?php endif; ?>