<?php
/**
 * Widget Poll Template - 1
 */

global $poll;

$poll_id   = liquidpoll()->get_widget_arg_val( 'poll_id_poll' );
$poll      = liquidpoll_get_poll( $poll_id );

if ( ! $poll instanceof LIQUIDPOLL_Poll ) {
	return;
}

printf( '<div class="liquidpoll-elementor-poll">%s</div>', do_shortcode( '[poll id="' . $poll->get_id() . '"]' ) );