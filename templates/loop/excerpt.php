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
<div class="poll-excerpt">
	<?php echo apply_filters( 'the_excerpt', $poll->get_poll_content( 15 ) ); ?>
</div>
