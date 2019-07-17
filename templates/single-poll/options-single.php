<?php
/**
 * Single Poll - Options - Single
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

$options_name = sprintf( 'poll_%s_options%s', $poll->get_id(), $poll->can_vote_multiple() ? '[]' : '' );
$options_type = $poll->can_vote_multiple() ? 'checkbox' : 'radio';

$option_id    = isset( $args['option_id'] ) ? $args['option_id'] : '';
$label        = isset( $args['label'] ) ? $args['label'] : '';
$thumb        = isset( $args['thumb'] ) ? $args['thumb'] : '';
$thumb_class  = ! empty( $thumb ) ? ' has-thumb' : '';
$label_class  = ! empty( $label ) ? ' has-label' : '';

?>
<div class="wpp-option-single <?php echo esc_attr( $thumb_class . ' ' . $label_class ); ?>">

    <div class="wpp-option-input">
        <input type="<?php echo esc_attr( $options_type ); ?>"
               name="<?php echo esc_attr( $options_name ); ?>"
               id="option-<?php echo esc_attr( $option_id ); ?>"
               value="<?php esc_attr( $option_id ); ?>">
        <label for="option-<?php echo esc_attr( $option_id ); ?>"><?php echo esc_html( $label ); ?></label>
    </div>

	<?php if ( ! empty( $thumb ) ) : ?>
        <div class="wpp-option-thumb">
            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $label ); ?>">
        </div>
	<?php endif; ?>

</div>
