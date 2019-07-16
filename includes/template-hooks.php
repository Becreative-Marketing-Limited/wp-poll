<?php
/**
 * Template hooks
 */


/**
 * Hooks for Single Poll
 *
 * @see wpp_single_poll_title()
 * @see wpp_single_poll_thumb()
 * @see wpp_single_poll_content()
 * @see wpp_single_poll_options()
 * @see wpp_single_poll_notice()
 * @see wpp_single_poll_countdown()
 * @see wpp_single_poll_buttons()
 */
add_action( 'wpp_single_poll_main', 'wpp_single_poll_title', 10 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_thumb', 15 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_content', 20 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_options', 25 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_notice', 30 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_countdown', 35 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_buttons', 40 );