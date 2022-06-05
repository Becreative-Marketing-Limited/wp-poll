<?php
/**
 * Template hook functions
 */


/**
 * Frontend Templates Hooks
 */

if ( ! function_exists( 'liquidpoll_single_poll_title' ) ) {
	/**
	 * Hook: liquidpoll_single_poll_main - 10
	 */
	function liquidpoll_single_poll_title() {

		liquidpoll_get_template( 'single-poll/title.php' );
	}
}


if ( ! function_exists( 'liquidpoll_single_poll_thumb' ) ) {
	/**
	 * Hook: liquidpoll_single_poll_main - 10
	 */
	function liquidpoll_single_poll_thumb() {

		liquidpoll_get_template( 'single-poll/thumb.php' );
	}
}


if ( ! function_exists( 'liquidpoll_single_poll_content' ) ) {
	/**
	 * Hook: liquidpoll_single_poll_main - 10
	 */
	function liquidpoll_single_poll_content() {

		liquidpoll_get_template( 'single-poll/content.php' );
	}
}


if ( ! function_exists( 'liquidpoll_single_poll_options' ) ) {
	/**
	 * Hook: liquidpoll_single_poll_main - 10
	 */
	function liquidpoll_single_poll_options() {

		liquidpoll_get_template( 'single-poll/options.php' );
	}
}


if ( ! function_exists( 'liquidpoll_single_poll_notice' ) ) {
	/**
	 * Hook: liquidpoll_single_poll_main - 10
	 */
	function liquidpoll_single_poll_notice() {

		liquidpoll_get_template( 'single-poll/notice.php' );
	}
}


if ( ! function_exists( 'liquidpoll_single_poll_countdown' ) ) {
	/**
	 * Hook: liquidpoll_single_poll_main - 10
	 */
	function liquidpoll_single_poll_countdown() {

		liquidpoll_get_template( 'single-poll/countdown.php' );
	}
}


if ( ! function_exists( 'liquidpoll_single_poll_buttons' ) ) {
	/**
	 * Hook: liquidpoll_single_poll_main - 10
	 */
	function liquidpoll_single_poll_buttons() {

		liquidpoll_get_template( 'single-poll/buttons.php' );
	}
}


if ( ! function_exists( 'liquidpoll_single_poll_responses' ) ) {
	/**
	 * Hook: liquidpoll_single_poll_main - 10
	 */
	function liquidpoll_single_poll_responses() {

		liquidpoll_get_template( 'single-poll/responses.php' );
	}
}


if ( ! function_exists( 'liquidpoll_poll_archive_single_thumb' ) ) {
	/**
	 * Hook: liquidpoll_poll_archive_single_main - 10
	 */
	function liquidpoll_poll_archive_single_thumb() {

		liquidpoll_get_template( 'loop/thumb.php' );
	}
}


if ( ! function_exists( 'liquidpoll_poll_archive_single_summary' ) ) {
	/**
	 * Hook: liquidpoll_poll_archive_single_main - 20
	 */
	function liquidpoll_poll_archive_single_summary() {

		liquidpoll_get_template( 'loop/summary.php' );
	}
}


if ( ! function_exists( 'liquidpoll_poll_archive_single_title' ) ) {
	/**
	 * Hook: liquidpoll_poll_archive_single_summary - 10
	 */
	function liquidpoll_poll_archive_single_title() {

		liquidpoll_get_template( 'loop/title.php' );
	}
}


if ( ! function_exists( 'liquidpoll_poll_archive_single_meta' ) ) {
	/**
	 * Hook: liquidpoll_poll_archive_single_summary - 15
	 */
	function liquidpoll_poll_archive_single_meta() {

		liquidpoll_get_template( 'loop/meta.php' );
	}
}


if ( ! function_exists( 'liquidpoll_poll_archive_single_excerpt' ) ) {
	/**
	 * Hook: liquidpoll_poll_archive_single_summary - 20
	 */
	function liquidpoll_poll_archive_single_excerpt() {

		liquidpoll_get_template( 'loop/excerpt.php' );
	}
}


if ( ! function_exists( 'liquidpoll_poll_archive_single_options' ) ) {
	/**
	 * Hook: liquidpoll_poll_archive_single_summary - 25
	 */
	function liquidpoll_poll_archive_single_options() {

		liquidpoll_get_template( 'loop/options.php' );
	}
}

if ( ! function_exists( 'liquidpoll_poll_archive_pagination' ) ) {
	/**
	 * Hook: liquidpoll_after_poll_archive - 10
	 */
	function liquidpoll_poll_archive_pagination() {

		liquidpoll_get_template( 'loop/pagination.php' );
	}
}


/**
 * Backend Template Hooks
 */

if ( ! function_exists( 'liquidpoll_admin_render_reports' ) ) {
	function liquidpoll_admin_render_reports() {

		require( LIQUIDPOLL_PLUGIN_DIR . 'includes/admin-templates/reports.php' );
	}
}

if ( ! function_exists( 'liquidpoll_poll_submitbox' ) ) {
	function liquidpoll_poll_submitbox() {

		require( LIQUIDPOLL_PLUGIN_DIR . 'includes/admin-templates/poll-submitbox.php' );
	}
}