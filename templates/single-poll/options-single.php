<?php
/**
 * Single Poll - Options - Single
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

$options_type  = $poll->can_vote_multiple() ? 'checkbox' : 'radio';
$option_id     = isset( $args['option_id'] ) ? $args['option_id'] : '';
$label         = isset( $args['label'] ) ? $args['label'] : '';
$thumb         = isset( $args['thumb'] ) ? $args['thumb'] : '';
$thumb_class   = ! empty( $thumb ) ? ' has-thumb' : '';
$label_class   = ! empty( $label ) ? ' has-label' : '';
$options_theme = $poll->get_style( 'options_theme' );
$option_name   = 'submit_poll_option';
$option_name   = $poll->get_poll_type() == 'survey' ? sprintf( '%s[%s]', $option_name, $poll->get_id() ) : $option_name;
$option_name = $poll->get_poll_type() == 'survey' && $options_type == 'checkbox' ? $option_name . "[]" : $option_name;

?>

<?php if ( $options_theme == 9 || $options_theme == 10 || $options_theme == 11 ) : ?>
<div class="wpp-col">
	<?php endif; ?>

    <div class="wpp-option-single <?php echo esc_attr( $thumb_class . ' ' . $label_class ); ?>"
         data-option-id="<?php echo esc_attr( $option_id ); ?>">
        <div class="wpp-option-input">
            <input type="<?php echo esc_attr( $options_type ); ?>"
                   name="<?php echo esc_attr( $option_name ); ?>"
                   id="option-<?php echo esc_attr( $option_id ); ?>"
                   value="<?php echo esc_attr( $option_id ); ?>">
            <label for="option-<?php echo esc_attr( $option_id ); ?>"><?php echo esc_html( $label ); ?></label>
        </div>

		<?php if ( ! empty( $thumb ) ) : ?>
            <div class="wpp-option-thumb">
                <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $label ); ?>">
            </div>
		<?php endif; ?>

        <div class="wpp-option-result"></div>
        <div class="wpp-option-result-bar"></div>

    </div> <!-- .wpp-option-single -->

	<?php if ( $options_theme == 9 || $options_theme == 10 || $options_theme == 11 ) : ?>
</div> <!-- .wwp-col -->
<?php endif; ?>
