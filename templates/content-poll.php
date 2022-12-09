<?php
/**
 * Template - Content Poll
 *
 * @package content-poll.php
 * @author Liquidpoll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


/**
* Hook: liquidpoll_before_poll_archive_single.
*/

do_action( 'liquidpoll_before_poll_archive_single' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

global $poll;

$poll = liquidpoll_get_poll();

$has_thumb = $poll->has_thumbnail() && liquidpoll()->display_on_archive( 'thumb' ) ? 'has-thumbnail' : '';

?>

<div id="poll-archive-single-<?php the_ID(); ?>" class="poll-archive-single <?php echo esc_attr( $has_thumb ); ?>">

	<?php

	/**
	 * Hook: liquidpoll_single_poll_main
	 *
	 * @see liquidpoll_poll_archive_single_thumb()
	 * @see liquidpoll_poll_archive_single_summary()
	 */
	do_action( 'liquidpoll_poll_archive_single_main' );
	?>

</div>


<?php

/**
 * Hook: liquidpoll_after_poll_archive_single
 */

do_action( 'liquidpoll_after_poll_archive_single' );