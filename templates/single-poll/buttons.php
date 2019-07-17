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
	printf( '<button class="wpp-button wpp-button-green">%s</button>', $wpp->get_button_text( 'submit' ) );


	/**
	 * Results button
	 */
	printf( '<button class="wpp-button wpp-button-red">%s</button>', $wpp->get_button_text( 'results' ) );

	?>

</div>

