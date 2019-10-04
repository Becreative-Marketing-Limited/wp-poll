<?php
/**
 * Template hooks
 */


/**
 * Hooks for Poll Archive Single
 *
 * @on Frontend
 */
add_action( 'wpp_poll_archive_single_main', 'wpp_poll_archive_single_thumb', 10 );
add_action( 'wpp_poll_archive_single_main', 'wpp_poll_archive_single_summary', 20 );

add_action( 'wpp_poll_archive_single_summary', 'wpp_poll_archive_single_title', 10 );
add_action( 'wpp_poll_archive_single_summary', 'wpp_poll_archive_single_meta', 15 );
add_action( 'wpp_poll_archive_single_summary', 'wpp_poll_archive_single_excerpt', 20 );
add_action( 'wpp_poll_archive_single_summary', 'wpp_poll_archive_single_options', 25 );

add_action( 'wpp_after_poll_archive', 'wpp_poll_archive_pagination', 10 );


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
 *
 * @on Frontend
 */
add_action( 'wpp_single_poll_main', 'wpp_single_poll_title', 10 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_thumb', 15 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_content', 20 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_options', 25 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_notice', 30 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_countdown', 35 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_responses', 40 );
add_action( 'wpp_single_poll_main', 'wpp_single_poll_buttons', 45 );


/**
 * Backend Template Hooks
 */

add_action( 'wpp-reports', 'wpp_admin_render_reports' );

add_action( 'post_submitbox_start', 'wpp_poll_submitbox' );
