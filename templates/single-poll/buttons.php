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

global $poll, $wpp;

?>

<div class="wpp-buttons">

	<?php

	/**
	 * New option button
	 */
    if ( $poll->visitors_can_add_option() ) {
		printf( '<button class="wpp-button wpp-button-orange wpp-button-new-option">%s</button>', $wpp->get_button_text( 'new_option' ) );
	}


	/**
	 * Submit button
	 */
	if( $poll->ready_to_vote() ) {
		printf( '<button class="wpp-button wpp-button-green wpp-submit-poll" data-poll-id="%s">%s</button>', $poll->get_id(), $wpp->get_button_text( 'submit' ) );
	}

	/**
	 * Results button
	 */
	if( ! $poll->hide_results() ) {
		printf( '<button class="wpp-button wpp-button-red wpp-get-poll-results" data-poll-id="%s">%s</button>', $poll->get_id(), $wpp->get_button_text( 'results' ) );
	}
	?>

</div>