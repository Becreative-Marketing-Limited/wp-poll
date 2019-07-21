<?php
/**
 * Template - Archive - summary
 *
 * @package loop/summary
 * @author Pluginbazar
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
	 * @hooked wpp_poll_archive_single_main
	 *
	 * @see wpp_poll_archive_single_title()
	 * @see wpp_poll_archive_single_meta()
     * @see wpp_poll_archive_single_excerpt()
	 * @see wpp_poll_archive_single_options()
	 */

	do_action( 'wpp_poll_archive_single_summary' );
	?>

</div>
