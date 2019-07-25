<?php
/**
 * Template - Archive - pagination
 *
 * @package loop/pagination
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $wp_query;

if( $wp_query->get( 'show_pagination' ) == 'yes' ) : ?>

	<div class="wpp-pagination paginate">
		<?php echo wpp_pagination(); ?>
	</div>

<?php endif; ?>

