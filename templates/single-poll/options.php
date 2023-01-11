<?php
/**
 * Single Poll - Options
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

global $poll;

$poller_details = $poll->get_poller_details();

?>
<div <?php liquidpoll_options_single_class( 'liquidpoll-options' ); ?>>

	<?php
	foreach ( $poll->get_poll_options() as $option_id => $option ) :

		$pollers = $poller_details[ $option_id ] ?? array();
		$args    = array_merge(
			array(
				'option_id'      => $option_id,
				'poller_details' => $pollers,
				'poller_count'   => count( $pollers ),
			), $option
		);

		liquidpoll_get_template( 'single-poll/options-single.php', $args );

	endforeach;
	?>

	<?php
	$typography_options = $poll->get_css_args( '_typography_options' );
	$selector           = '.poll-single.theme-' . $poll->get_theme() . ' .liquidpoll-option-list-1 .liquidpoll-option-single input + label';

	liquidpoll_apply_css( $selector, $typography_options );

	liquidpoll_apply_css( '.poll-single.theme-4 .liquidpoll-option-single.has-result svg.liquidpoll-votes-count circle', array(
		'stroke' => Utils::get_args_option( 'color', $typography_options ),
	) );
	?>

</div>

<?php if ( $poll->get_type() === 'poll' ) : ?>

    <div class="liquidpoll-popup-container">
        <div class="liquidpoll-popup-box">
            <span class="box-close dashicons dashicons-no-alt"></span>
            <div class="liquidpoll-new-option">
                <input type="text" placeholder="<?php esc_attr_e( 'Your option', 'wp-poll' ); ?>">
                <span class="liquidpoll-notice-warning"
                      style="display: none;"><?php esc_html_e( 'Please write some text !', 'wp-poll' ) ?></span>
                <button class="liquidpoll-button liquidpoll-button-blue"
                        data-pollid="<?php echo esc_attr( $poll->get_id() ); ?>"><?php esc_html_e( 'Add Option', 'wp-poll' ); ?></button>
            </div>
        </div>
    </div>

<?php endif; ?>


