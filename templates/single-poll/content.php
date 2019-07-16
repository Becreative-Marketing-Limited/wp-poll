<?php
/**
 * Template - Single Poll - Content
 *
 * @package single-poll/content
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

?>
<div class="wpp-content">
	<?php echo apply_filters( 'the_content', wp_kses_post( $poll->get_poll_content() ) ); ?>
</div>

