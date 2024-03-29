<?php
/**
 * Template - Archive Poll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $args['paged'] ) ) {
	$args['paged'] = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
}

$args['items_per_row'] = isset( $args['items_per_row'] ) ? $args['items_per_row'] : '';

$poll_archive = new WP_Query( $args );

global $wp_query;


$previous_wp_query = $wp_query;
$wp_query          = $poll_archive;

/**
 * Before Poll Archive
 *
 */
do_action( 'liquidpoll_before_poll_archive' );

if ( $poll_archive->have_posts() ) :

	liquidpoll_get_template( 'loop/start.php' );

	while ( $poll_archive->have_posts() ) : $poll_archive->the_post();

		liquidpoll_get_template_part( 'content', 'poll' );

	endwhile;

	liquidpoll_get_template( 'loop/end.php' );

else :

	liquidpoll_get_template( 'loop/no-item.php' );

endif;

/**
 * After Poll Archive
 *
 * @see liquidpoll_poll_archive_pagination() - 10
 */
do_action( 'liquidpoll_after_poll_archive' );


$wp_query = $previous_wp_query;
wp_reset_query();