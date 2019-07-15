<?php
/**
 * Template hook functions
 */


if( ! function_exists( 'wpp_single_poll_title' ) ) {
	function wpp_single_poll_title() {

		wpp_get_template( 'single-poll/title.php' );
	}
}