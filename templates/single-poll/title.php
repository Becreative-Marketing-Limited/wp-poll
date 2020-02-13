<?php
/**
 * Single Poll - Title
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

printf( '<%1$s itemprop="name" class="wpp-poll-title">%2$s</%1$s>',
	is_singular( 'poll' ) ? 'h1' : 'h2',
	apply_filters( 'the_title', $poll->get_name(), $poll->get_id() )
);