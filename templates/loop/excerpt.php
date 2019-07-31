<?php
/**
 * Template - Archive - excerpt
 *
 * @package loop/excerpt
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

?>

<?php if ( ! empty( $poll->get_content() ) ) : ?>
    <div class="poll-excerpt">
		<?php echo apply_filters( 'the_excerpt', $poll->get_content( 15 ) ); ?>
    </div>
<?php endif; ?>
