<?php
/**
 * Template hook functions
 */


if( ! function_exists( 'wpp_single_poll_title' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_title() {

		wpp_get_template( 'single-poll/title.php' );
	}
}

if( ! function_exists( 'wpp_single_poll_thumb' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_thumb() {

		wpp_get_template( 'single-poll/thumb.php' );
	}
}

if( ! function_exists( 'wpp_single_poll_content' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_content() {

		wpp_get_template( 'single-poll/content.php' );
	}
}

if( ! function_exists( 'wpp_single_poll_options' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_options() {

		wpp_get_template( 'single-poll/options.php' );
	}
}

if( ! function_exists( 'wpp_single_poll_notice' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_notice() {

		wpp_get_template( 'single-poll/notice.php' );
	}
}

if( ! function_exists( 'wpp_single_poll_message' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_message() {

		wpp_get_template( 'single-poll/message.php' );
	}
}

if( ! function_exists( 'wpp_single_poll_buttons' ) ) {
	/**
	 * Hook: wpp_single_poll_main - 10
	 */
	function wpp_single_poll_buttons() {

		wpp_get_template( 'single-poll/buttons.php' );
	}
}
