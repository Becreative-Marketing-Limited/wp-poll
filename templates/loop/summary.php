<?php
/**
 * Template - Archive - summary
 *
 * @package loop/summary
 * @author Liquidpoll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

?>
<div class="poll-summary">

	<?php
	/**
	 * Poll Archive single summary
	 *
	 * @hooked liquidpoll_poll_archive_single_main
	 *
	 * @see liquidpoll_poll_archive_single_title()
	 * @see liquidpoll_poll_archive_single_meta()
     * @see liquidpoll_poll_archive_single_excerpt()
	 * @see liquidpoll_poll_archive_single_options()
	 */

	do_action( 'liquidpoll_poll_archive_single_summary' );
	?>

</div>
