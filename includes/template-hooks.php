<?php
/**
 * Template hooks
 */


/**
 * Hooks for Poll Archive Single
 *
 * @on Frontend
 */
add_action( 'liquidpoll_poll_archive_single_main', 'liquidpoll_poll_archive_single_thumb', 10 );
add_action( 'liquidpoll_poll_archive_single_main', 'liquidpoll_poll_archive_single_summary', 20 );

add_action( 'liquidpoll_poll_archive_single_summary', 'liquidpoll_poll_archive_single_title', 10 );
add_action( 'liquidpoll_poll_archive_single_summary', 'liquidpoll_poll_archive_single_meta', 15 );
add_action( 'liquidpoll_poll_archive_single_summary', 'liquidpoll_poll_archive_single_excerpt', 20 );
add_action( 'liquidpoll_poll_archive_single_summary', 'liquidpoll_poll_archive_single_options', 25 );

add_action( 'liquidpoll_after_poll_archive', 'liquidpoll_poll_archive_pagination', 10 );


/**
 * Hooks for Single Poll
 *
 * @see liquidpoll_single_poll_form()
 * @see liquidpoll_single_poll_title()
 * @see liquidpoll_single_poll_thumb()
 * @see liquidpoll_single_poll_content()
 * @see liquidpoll_single_poll_options()
 * @see liquidpoll_single_poll_notice()
 * @see liquidpoll_single_poll_countdown()
 * @see liquidpoll_single_poll_buttons()
 *
 * @on Frontend
 */

add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_form', 5 );
add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_title', 10 );
add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_thumb', 15 );
add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_content', 20 );
add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_options', 25 );
add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_notice', 30 );
add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_countdown', 35 );
add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_responses', 40 );
add_action( 'liquidpoll_single_poll_main', 'liquidpoll_single_poll_buttons', 45 );


add_action( 'liquidpoll_single_poll_main', function () {

	global $poll;

	if ( 'poll' == $poll->get_type() ) {
		echo '<div class="poll-content">';
	}
}, 6 );


add_action( 'liquidpoll_single_poll_main', function () {

	global $poll;

	if ( 'poll' == $poll->get_type() ) {
		echo '</div>'; // .poll-content
	}
}, 100 );




/**
 * Backend Template Hooks
 */

add_action( 'WPDK_Settings/section/primary_reports', 'liquidpoll_admin_render_reports' );

add_action( 'post_submitbox_start', 'liquidpoll_poll_submitbox' );
