<?php
/**
 * Template - Archive - meta
 *
 * @package loop/meta
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;


?>

<div class="poll-metas">
	<div class="poll-meta"><?php printf( esc_html__('Created by %s', 'wp-poll'), $poll->get_author_info( 'display_name' ) ); ?></div>
	<div class="poll-meta"><?php printf( esc_html__('Published on %s', 'wp-poll'), $poll->get_published_date( 'jS j Y, g:i a' ) ); ?></div>
</div>
