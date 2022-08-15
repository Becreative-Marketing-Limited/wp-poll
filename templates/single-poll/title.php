<?php
/**
 * Single Poll - Title
 */

defined( 'ABSPATH' ) || exit;

global $poll;

printf( '<%1$s itemprop="name" class="liquidpoll-poll-title">%2$s</%1$s>',
	is_singular( 'poll' ) ? 'h1' : 'h2',
	apply_filters( 'the_title', $poll->get_name() )
);

/**
 * Apply dynamic typography css
 */
liquidpoll_apply_css( '.liquidpoll-poll-title', $poll->get_css_args( '_typography_title' ) );