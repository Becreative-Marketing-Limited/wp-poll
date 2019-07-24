<?php
/**
 * Template hook functions
 */


/**
 * Frontend Templates Hooks
 */

if ( ! function_exists( 'wpp_single_poll_title' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_title() {

		wpp_get_template( 'single-poll/title.php' );
	}
}

if ( ! function_exists( 'wpp_single_poll_thumb' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_thumb() {

		wpp_get_template( 'single-poll/thumb.php' );
	}
}

if ( ! function_exists( 'wpp_single_poll_content' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_content() {

		wpp_get_template( 'single-poll/content.php' );
	}
}

if ( ! function_exists( 'wpp_single_poll_options' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_options() {

		wpp_get_template( 'single-poll/options.php' );
	}
}

if ( ! function_exists( 'wpp_single_poll_notice' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_notice() {

		wpp_get_template( 'single-poll/notice.php' );
	}
}

if ( ! function_exists( 'wpp_single_poll_countdown' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_countdown() {

		wpp_get_template( 'single-poll/countdown.php' );
	}
}

if ( ! function_exists( 'wpp_single_poll_buttons' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_buttons() {

		wpp_get_template( 'single-poll/buttons.php' );
	}
}


if ( ! function_exists( 'wpp_single_poll_responses' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_responses() {

		wpp_get_template( 'single-poll/responses.php' );
	}
}


if ( ! function_exists( 'wpp_poll_archive_single_thumb' ) ) {
	/**
	 * Hook: wpp_poll_archive_single_main - 10
	 */
	function wpp_poll_archive_single_thumb() {

		wpp_get_template( 'loop/thumb.php' );
	}
}


if ( ! function_exists( 'wpp_poll_archive_single_summary' ) ) {
	/**
	 * Hook: wpp_poll_archive_single_main - 20
	 */
	function wpp_poll_archive_single_summary() {

		wpp_get_template( 'loop/summary.php' );
	}
}


if ( ! function_exists( 'wpp_poll_archive_single_title' ) ) {
	/**
	 * Hook: wpp_poll_archive_single_summary - 10
	 */
	function wpp_poll_archive_single_title() {

		wpp_get_template( 'loop/title.php' );
	}
}


if ( ! function_exists( 'wpp_poll_archive_single_meta' ) ) {
	/**
	 * Hook: wpp_poll_archive_single_summary - 15
	 */
	function wpp_poll_archive_single_meta() {

		wpp_get_template( 'loop/meta.php' );
	}
}


if ( ! function_exists( 'wpp_poll_archive_single_excerpt' ) ) {
	/**
	 * Hook: wpp_poll_archive_single_summary - 20
	 */
	function wpp_poll_archive_single_excerpt() {

		wpp_get_template( 'loop/excerpt.php' );
	}
}


if ( ! function_exists( 'wpp_poll_archive_single_options' ) ) {
	/**
	 * Hook: wpp_poll_archive_single_summary - 25
	 */
	function wpp_poll_archive_single_options() {

		wpp_get_template( 'loop/options.php' );
	}
}

if ( ! function_exists( 'wpp_poll_archive_pagination' ) ) {
	/**
	 * Hook: wpp_after_poll_archive - 10
	 */
	function wpp_poll_archive_pagination() {

		wpp_get_template( 'loop/pagination.php' );
	}
}





/**
 * Backend Template Hooks
 */

if( ! function_exists( 'wpp_admin_render_reports' ) ) {
	function wpp_admin_render_reports() {

		require( WPP_PLUGIN_DIR . 'includes/admin-templates/reports.php' );
	}
}

if( ! function_exists( 'wpp_poll_submitbox' ) ) {
	function wpp_poll_submitbox() {

		require( WPP_PLUGIN_DIR . 'includes/admin-templates/poll-submitbox.php' );
	}
}