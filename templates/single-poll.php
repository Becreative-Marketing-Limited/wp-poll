<?php
/**
 * Template - Single Poll
 */

defined( 'ABSPATH' ) || exit;

global $liquidpoll;

/**
 * Get WP Header
 */
get_header();
?>

<?php
/**
 * Hook: liquidpoll_before_single_poll_template
 */
do_action( 'liquidpoll_before_single_poll_template' );
?>


<?php while ( have_posts() ) : the_post(); ?>

	<?php liquidpoll_get_template_part( 'content', 'single-poll' ); ?>

<?php endwhile; // end of the loop. ?>


<?php
/**
 * Hook: liquidpoll_after_single_poll_template
 */
do_action( 'liquidpoll_after_single_poll_template' );
?>


<?php

/**
 * Get WP Footer
 */

get_footer();