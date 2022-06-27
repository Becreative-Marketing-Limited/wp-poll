<?php
/**
 * Single Poll - thumbnail
 */

defined( 'ABSPATH' ) || exit;

global $poll;

$thumb_size = '';

if ( in_array( $poll->get_theme(), array( 6, 7 ) ) ) {
	$thumb_size = 'poll-square';
} elseif ( in_array( $poll->get_theme(), array( 8 ) ) ) {
	$thumb_size = 'poll-long-width';
}

?>

<?php if ( $poll->has_thumbnail() ) : ?>
    <div class="liquidpoll-poll-thumbnail">
        <img src="<?php echo esc_url( $poll->get_thumbnail( $thumb_size ) ); ?>"
             alt="<?php echo esc_attr( $poll->get_name() ); ?>"/>
    </div>
<?php endif; ?>