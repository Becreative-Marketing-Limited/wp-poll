<?php
/**
 * Template - Single Poll Content
 */


/**
 * Hook: wpp_before_single_poll.
 */
do_action( 'wpp_before_single_poll' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

?>
    <div id="poll-<?php the_ID(); ?>" <?php wpp_single_poll_class(); ?>>
		<?php

        global $poll;

        $poll = wpp_get_poll();

		/**
		 * Hook: wpp_single_poll_main
		 *
		 * @hooked wpp_single_poll_title
		 * @hooked wpp_single_poll_thumb
		 * @hooked wpp_single_poll_content
		 * @hooked wpp_single_poll_options
		 * @hooked wpp_single_poll_notice
		 * @hooked wpp_single_poll_message
		 * @hooked wpp_single_poll_buttons
		 */
		do_action( 'wpp_single_poll_main' );
		?>
    </div>

<?php
/**
 * Hook: wpp_after_single_poll
 */
do_action( 'wpp_after_single_poll' );
?>