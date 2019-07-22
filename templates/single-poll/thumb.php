<?php
/**
 * Single Poll - thumbnail
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

?>

<?php if ( $poll->has_thumbnail() ) : ?>
    <div class="wpp-poll-thumbnail">
        <img src="<?php echo esc_url( $poll->get_thumbnail() ); ?>"
             alt="<?php echo esc_attr( $poll->get_name() ); ?>"/>
    </div>
<?php endif; ?>