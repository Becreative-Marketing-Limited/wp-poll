<?php
/**
 * Template - Single Poll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $wpp;

/**
 * Get WP Header
 */
get_header();
?>

<?php
/**
 * Hook: wpp_before_single_poll_template
 */
do_action( 'wpp_before_single_poll_template' );
?>


<?php while ( have_posts() ) : the_post(); ?>

	<?php wpp_get_template_part( 'content', 'single-poll' ); ?>

<?php endwhile; // end of the loop. ?>


<?php
/**
 * Hook: wpp_after_single_poll_template
 */
do_action( 'wpp_after_single_poll_template' );
?>


<?php

/**
 * Get WP Footer
 */

get_footer();
?>