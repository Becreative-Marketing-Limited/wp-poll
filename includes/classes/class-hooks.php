<?php
/**
 * Class Hooks
 */


if ( ! class_exists( 'WPP_Hooks' ) ) {
	/**
	 * Class WPP_Hooks
	 */
	class WPP_Hooks {

		/**
		 * WPP_Hooks constructor.
		 */
		function __construct() {

			add_action( 'manage_poll_posts_columns', array( $this, 'add_core_poll_columns' ), 16, 1 );
			add_action( 'manage_poll_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );

			add_filter( 'single_template', array( $this, 'single_poll_template' ) );
		}


		/**
		 * Filter Single Template for Poll post type
		 *
		 * @param $single_template
		 *
		 * @return string
		 */
		function single_poll_template( $single_template ) {

			if( is_singular( 'poll' ) ) {
				return WPP_PLUGIN_DIR . 'templates/single-poll.php';
			}

			return $single_template;
		}



		function add_core_poll_columns( $columns ) {

			$new = array();

			$count = 0;
			foreach ( $columns as $col_id => $col_label ) {
				$count ++;

				if ( $count == 3 ) {
					$new['poll-report'] = __( 'Poll Report', 'wp-poll' );
				}

				if ( 'title' === $col_id ) {
					$new[ $col_id ] = __( 'Poll title', 'wp-poll' );
				} else {
					$new[ $col_id ] = $col_label;
				}

				unset( $new['date'] );
			}

			$new['poll-date'] = __( 'Published at', 'wp-poll' );

			return $new;
		}


		function custom_columns_content( $column, $post_id ) {

			global $wpp;

			if ( $column == 'poll-report' ):

				$polled_data = $wpp->get_meta( 'polled_data', $post_id, array() );

				echo sprintf( "<i>%d %s</i>", count( $polled_data ), __( 'people polled on this', 'wp-poll' ) );
				echo '<div class="row-actions">';
				echo sprintf( '<span class="view_report"><a href="%s" rel="permalink">' . __( 'View Reports', 'wp-poll' ) . '</a></span>', "edit.php?post_type=poll&page=wpp_reports&id=" . $post_id );
				echo '</div>';

			endif;

			if ( $column == 'poll-date' ):

				$time_ago = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
				echo "<i>$time_ago " . __( 'ago', 'wp-poll' ) . "</i>";

			endif;
		}
	}

	new WPP_Hooks();
}