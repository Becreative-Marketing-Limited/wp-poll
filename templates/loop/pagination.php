<?php
/**
 * Template - Archive - pagination
 *
 * @package loop/pagination
 * @author Liquidpoll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $wp_query;

if( $wp_query->get( 'show_pagination' ) == 'yes' ) : ?>

	<div class="liquidpoll-pagination paginate">
		<?php echo liquidpoll_pagination(); ?>
	</div>

<?php endif; ?>

