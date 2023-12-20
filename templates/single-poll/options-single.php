<?php
/**
 * Single Poll - Options - Single
 */

defined( 'ABSPATH' ) || exit;

global $poll;

$options_type   = $poll->can_vote_multiple() ? 'checkbox' : 'radio';
$poller_details = isset( $args['poller_details'] ) ? $args['poller_details'] : array();
$poller_count   = isset( $args['poller_count'] ) ? $args['poller_count'] : 0;
$option_id      = isset( $args['option_id'] ) ? $args['option_id'] : '';
$unique_id      = uniqid( 'option-' );
$label          = isset( $args['label'] ) ? $args['label'] : '';
$thumb          = isset( $args['thumb'] ) && ! in_array( $poll->get_theme(), array( 10 ) ) ? $args['thumb'] : '';
$thumb_class    = ! empty( $thumb ) ? ' has-thumb' : '';
$label_class    = ! empty( $label ) ? ' has-label' : '';
$option_name    = 'submit_poll_option';
$option_name    = $poll->get_type() == 'survey' ? sprintf( '%s[%s]', $option_name, $poll->get_id() ) : $option_name;
$option_name    = $poll->get_type() == 'survey' && $options_type == 'checkbox' ? $option_name . "[]" : $option_name;
$theme_args     = $poll->get_theme_args( $poll->get_theme() );
$theme_width    = isset( $theme_args ) && ! empty( $theme_args ) ? $theme_args['width'] : '';
$theme_height   = isset( $theme_args ) && ! empty( $theme_args ) ? $theme_args['height'] : '';
$resized_thumb  = ($thumb && $theme_width) ? liquidpoll_resizer( $thumb, $theme_width, $theme_height, true, true, true ) : '';

?>

<div class="liquidpoll-option-single <?php echo esc_attr( $thumb_class . ' ' . $label_class ); ?>"
     data-option-id="<?php echo esc_attr( $option_id ); ?>">

	<?php if ( 2 != $poll->get_theme() && ! empty( $resized_thumb ) ) : ?>
        <div class="liquidpoll-option-thumb">
            <img src="<?php echo esc_url( $resized_thumb ); ?>" alt="<?php echo esc_attr( $label ); ?>">
        </div>
	<?php endif; ?>

    <div class="liquidpoll-option-input">
        <input type="<?php echo esc_attr( $options_type ); ?>"
               name="<?php echo esc_attr( $option_name ); ?>"
               id="<?php echo esc_attr( $unique_id ); ?>"
               value="<?php echo esc_attr( $option_id ); ?>">

        <label for="<?php echo esc_attr( $unique_id ); ?>">
			<?php if ( ! $poll->get_meta( 'hide_option_labels', false ) ) : ?>
                <span><?php echo esc_html( $label ); ?></span>
			<?php endif; ?>
        </label>

    </div>

	<?php if ( 2 == $poll->get_theme() && ! empty( $resized_thumb ) ) : ?>
        <div class="liquidpoll-option-thumb">
            <img src="<?php echo esc_url( $resized_thumb ); ?>" alt="<?php echo esc_attr( $label ); ?>">
        </div>
	<?php endif; ?>



	<?php if ( ! $poll->hide_results() ) : ?>

		<?php if ( in_array( $poll->get_theme(), array( 1, 11, 12 ) ) ): ?>
            <div class="liquidpoll-option-result"></div>
            <div class="liquidpoll-option-result-bar"></div>
			<?php if ( in_array( $poll->get_theme(), array( 11, 12 ) ) ): ?>
                <div class="liquidpoll-option-result-bar">
                    <span class="liquidpoll-option-result-bar-inner"></span>
                </div>
                <div class="liquidpoll-votes-count"></div>
			<?php endif; ?>

		<?php endif; ?>

		<?php if ( in_array( $poll->get_theme(), array( 2, 3, 5, 8, 9, 10 ) ) ): ?>
            <span class="liquidpoll-votes-count" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></span>
			<?php if ( 10 == $poll->get_theme() ) : ?>
                <span class="percentage-bar" style="width: 0 ;"></span>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( in_array( $poll->get_theme(), array( 4, 6, 7 ) ) ): ?>
            <svg class="liquidpoll-votes-count" viewBox="0 0 80 80">
                <circle class="incomplete" cx="40" cy="40" r="35"></circle>
                <circle class="complete" cx="40" cy="40" r="35" style="stroke-dashoffset: 39.58406743523136;"></circle>
                <text class="percentage" x="50%" y="57%"></text>
            </svg>
		<?php endif; ?>


	<?php endif; ?>


	<?php if ( in_array( $poll->get_theme(), array( 12 ) ) ): ?>
        <div class="liquidpoll-option-submitting-person">
            <ul>
				<?php foreach ( $poller_details as $index => $poller_detail )  :
					if ( $index > 0 ) {
						continue;
					}
					?>
                    <li>
                        <img src="<?php echo get_avatar_url( $poller_detail ); ?>" alt="<?php echo esc_attr( $poller_detail ); ?>">
                    </li>
				<?php endforeach; ?>

				<?php if ( $poller_count > 1 ) : ?>
                    <li>
                        <span>+<?php echo esc_html( $poller_count - 1 ); ?></span>
                    </li>
				<?php endif; ?>
            </ul>
        </div>
	<?php endif; ?>


</div> <!-- .liquidpoll-option-single -->




