<?php
/**
 * Template - Archive - title
 *
 * @package loop/title
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

?>

<div class="poll-title"><h3><a href="<?php echo esc_attr( $poll->get_permalink() ); ?>"><?php echo apply_filters( 'the_title', $poll->get_name() ); ?></a></h3></div>
