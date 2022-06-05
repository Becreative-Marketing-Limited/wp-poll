<?php
/**
 * Template - Single Poll - Content
 *
 * @package single-poll/buttons
 * @author Pluginbazar
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
    if ( $poll->visitors_can_add_option() ) {
		printf( '<button class="liquidpoll-button liquidpoll-button-orange liquidpoll-button-new-option">%s</button>', $liquidpoll->get_button_text( 'new_option' ) );
	}


	/**
	 * Submit button
	 */
	if( $poll->ready_to_vote() ) {
		printf( '<button class="liquidpoll-button liquidpoll-button-green liquidpoll-submit-poll" data-poll-id="%s">%s</button>', $poll->get_id(), $liquidpoll->get_button_text( 'submit' ) );
	}

	/**
	 * Results button
	 */
	if( ! $poll->hide_results() ) {
		printf( '<button class="liquidpoll-button liquidpoll-button-red liquidpoll-get-poll-results" data-poll-id="%s">%s</button>', $poll->get_id(), $liquidpoll->get_button_text( 'results' ) );
	}
	?>

</div>