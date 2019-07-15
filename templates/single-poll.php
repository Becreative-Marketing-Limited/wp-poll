<?php
/**
 * Template - Single Poll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


get_header(); ?>

<?php
/**
 * Hook: wpp_before_single_poll_template
 */
do_action( 'wpp_before_single_poll_template' );
?>


<?php while ( have_posts() ) : the_post(); ?>

	<?php global $poll; ?>

    <div id="poll-<?php the_ID(); ?>" <?php wpp_single_poll_class( '', $poll ); ?>>

		<?php wpp_get_template_part( 'content', 'single-poll' ); ?>

    </div>

<?php endwhile; // end of the loop. ?>


<?php
/**
 * Hook: wpp_after_single_poll_template
 */
do_action( 'wpp_after_single_poll_template' );
?>


<?php get_footer();