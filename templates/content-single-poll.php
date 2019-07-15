<?php
/**
 * Template - Single Poll Content
 */

global $poll;

/**
 * Hook: wpp_before_single_poll.
 */
do_action( 'wpp_before_single_poll' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

?>
    <div id="poll-<?php the_ID(); ?>" <?php wpp_single_poll_class( '', $poll ); ?>>

		<?php
		/**
		 * Hook: wpp_single_poll_main
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