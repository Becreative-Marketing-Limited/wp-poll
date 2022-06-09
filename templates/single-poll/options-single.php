<?php
/**
 * Single Poll - Options - Single
 */

defined( 'ABSPATH' ) || exit;

global $poll;

$options_type = $poll->can_vote_multiple() ? 'checkbox' : 'radio';
$option_id    = isset( $args['option_id'] ) ? $args['option_id'] : '';
$unique_id    = uniqid( 'option-' );
$label        = isset( $args['label'] ) ? $args['label'] : '';
$thumb        = isset( $args['thumb'] ) ? $args['thumb'] : '';
$thumb_class  = ! empty( $thumb ) ? ' has-thumb' : '';
$label_class  = ! empty( $label ) ? ' has-label' : '';
$option_name  = 'submit_poll_option';
$option_name  = $poll->get_poll_type() == 'survey' ? sprintf( '%s[%s]', $option_name, $poll->get_id() ) : $option_name;
$option_name  = $poll->get_poll_type() == 'survey' && $options_type == 'checkbox' ? $option_name . "[]" : $option_name;
$poll_theme   = $poll->get_meta( '_theme', 1 );

?>

<div class="liquidpoll-option-single <?php echo esc_attr( $thumb_class . ' ' . $label_class ); ?>"
     data-option-id="<?php echo esc_attr( $option_id ); ?>">

	<?php if ( 2 != $poll_theme && ! empty( $thumb ) ) : ?>
        <div class="liquidpoll-option-thumb">
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $label ); ?>">
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

	<?php if ( 2 == $poll_theme && ! empty( $thumb ) ) : ?>
        <div class="liquidpoll-option-thumb">
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $label ); ?>">
        </div>
	<?php endif; ?>


	<?php if ( ! $poll->hide_results() ) : ?>
        <div class="liquidpoll-option-result"></div>
        <div class="liquidpoll-option-result-bar"></div>
	<?php endif; ?>

</div> <!-- .liquidpoll-option-single -->
