<?php
/**
 * Template - Single Poll - Content
 *
 * @package single-poll/buttons
 * @author Liquidpoll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll, $liquidpoll;

?>

<div class="liquidpoll-buttons">

	<?php

	/**
	 * New option button
	 */
//	if ( $poll->visitors_can_add_option() ) {
//		printf( '<button class="liquidpoll-button liquidpoll-button-new-option">%s</button>', $liquidpoll->get_button_text( 'new_option' ) );
//	}


	/**
	 * Submit button
	 */
	if ( $poll->ready_to_vote() ) {
		printf( '<button class="liquidpoll-button liquidpoll-submit-poll" data-poll-id="%s">%s</button>', $poll->get_id(), $liquidpoll->get_button_text( 'submit' ) );

		liquidpoll_apply_css( '.liquidpoll-submit-poll', $poll->get_css_args( '_typography_button_submit', array() ) );

		liquidpoll_apply_css( '.liquidpoll-submit-poll',
			array_merge(
				$poll->get_meta( '_typography_btn_submit', array() ),
				array(
					'background-color' => $poll->get_css_args( '_typography_btn_submit_bg' ),
				)
			)
		);
	}

	/**
	 * Results button
	 */
	if ( ! $poll->hide_results() ) {


		$voted_users_only = '1' == ( $poll->get_meta( 'settings_poll_view_results_to_voted_users_only' ) && ! $poll->is_users_voted() ) ? 'voted-users-only' : '';

		printf( '<button class="liquidpoll-button liquidpoll-button-gray liquidpoll-get-poll-results %s" data-poll-id="%s">%s</button>', $voted_users_only, $poll->get_id(), $liquidpoll->get_button_text( 'results' ) );

		liquidpoll_apply_css( '.liquidpoll-get-poll-results',
			array_merge(
				$poll->get_css_args( '_typography_btn_results' ),
				array(
					'background-color' => $poll->get_css_args( '_typography_btn_results_bg' ),
				)
			)
		);
	}
	?>

</div>