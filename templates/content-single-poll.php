<?php
/**
 * Template - Single Poll Content
 */

global $poll, $wp_query;

if ( ! $poll instanceof LIQUIDPOLL_Poll ) {
	$poll = liquidpoll_get_poll();
}

$embed_class = $wp_query->get( 'poll_in_embed', false ) ? 'inside-embed' : '';

/**
 * Hook: liquidpoll_before_single_poll.
 */
do_action( 'liquidpoll_before_single_poll' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.

	return;
}

?>
    <div id="poll-<?php the_ID(); ?>" <?php liquidpoll_single_post_class( $embed_class ); ?>>
		<?php
		/**
		 * Before Single poll main content
		 */
		do_action( 'liquidpoll_before_single_poll_main' );


		if ( apply_filters( 'liquidpoll_filters_display_single_poll_main', true ) ) {
			/**
			 * Hook: liquidpoll_single_poll_main
			 *
			 * @hooked liquidpoll_single_poll_title
			 * @hooked liquidpoll_single_poll_thumb
			 * @hooked liquidpoll_single_poll_content
			 * @hooked liquidpoll_single_poll_options
			 * @hooked liquidpoll_single_poll_notice
			 * @hooked liquidpoll_single_poll_message
			 * @hooked liquidpoll_single_poll_buttons
			 */
			do_action( 'liquidpoll_single_poll_main' );
		}


		/**
		 * After Single poll main content
		 */
		do_action( 'liquidpoll_after_single_poll_main' );
		?>
    </div>

<?php
/**
 * Hook: liquidpoll_after_single_poll
 */
do_action( 'liquidpoll_after_single_poll' );
?>