<?php
/**
 * Template - Content Poll
 *
 * @package content-poll.php
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


/**
* Hook: wpp_before_poll_archive_single.
*/

do_action( 'wpp_before_poll_archive_single' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

?>

<div id="poll-archive-single-<?php the_ID(); ?>" class="poll-archive-single">

	<?php

	global $poll;

	$poll = wpp_get_poll();

	/**
	 * Hook: wpp_single_poll_main
	 *
	 * @see wpp_poll_archive_single_thumb()
	 * @see wpp_poll_archive_single_summary()
	 */
	do_action( 'wpp_poll_archive_single_main' );
	?>

</div>


<?php

/**
 * Hook: wpp_after_poll_archive_single
 */

do_action( 'wpp_after_poll_archive_single' );