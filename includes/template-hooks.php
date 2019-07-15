<?php
/**
 * Template hooks
 */


/**
 * Hooks for Single Poll
 *
 * @see wpp_single_poll_title()
 */
add_action( 'wpp_single_poll_main', 'wpp_single_poll_title', 10 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_thumb', 15 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_content', 20 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_options', 25 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_notice', 30 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_message', 35 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_buttons', 40 );