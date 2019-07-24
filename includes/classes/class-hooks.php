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

			add_action( 'wp_ajax_wpp_ajax_add_option', array( $this, 'ajax_new_poll_option' ) );
			add_action( 'wp_ajax_wpp_front_new_option', array( $this, 'wpp_front_new_option' ) );
			add_action( 'wp_ajax_nopriv_wpp_front_new_option', array( $this, 'wpp_front_new_option' ) );

			add_action( 'wp_ajax_wpp_submit_poll', array( $this, 'wpp_submit_poll' ) );
			add_action( 'wp_ajax_nopriv_wpp_submit_poll', array( $this, 'wpp_submit_poll' ) );

			add_action( 'wp_ajax_wpp_get_poll_results', array( $this, 'wpp_get_poll_results' ) );
			add_action( 'wp_ajax_nopriv_wpp_get_poll_results', array( $this, 'wpp_get_poll_results' ) );
		}


		/**
		 * Ajax Get Poll Results
		 */
		function wpp_get_poll_results() {

			$poll_id = isset( $_POST['poll_id'] ) ? sanitize_text_field( $_POST['poll_id'] ) : '';

			if ( empty( $poll_id ) ) {
				wp_send_json_error( esc_html__( 'Invalid data found !', 'wp-poll' ) );
			}

			$poll = wpp_get_poll( $poll_id );

			wp_send_json_success( $poll->get_poll_results() );
		}


		/**
		 * Ajax Submit poll
		 */
		function wpp_submit_poll() {

			$poll_id      = isset( $_POST['poll_id'] ) ? sanitize_text_field( $_POST['poll_id'] ) : '';
			$checked_data = isset( $_POST['checked_data'] ) ? stripslashes_deep( $_POST['checked_data'] ) : array();
			$poll         = wpp_get_poll( $poll_id );

			if ( empty( $poll_id ) || empty( $checked_data ) || ! is_array( $checked_data ) ) {
				wp_send_json_error( esc_html__( 'Invalid data found !', 'wp-poll' ) );
			}

			$polled_data = $poll->get_meta( 'polled_data', array() );
			$poller      = wpp_get_poller();

			/**
			 * Check if already voted
			 */
			if ( array_key_exists( $poller, $polled_data ) ) {
				wp_send_json_error( esc_html__( 'You have already voted on this poll !', 'wp-poll' ) );
			}


			/**
			 * Check ready to vote or not
			 */
			if ( ! $poll->ready_to_vote() ) {
				wp_send_json_error( esc_html__( 'This poll can not be voted any more !', 'wp-poll' ) );
			}


			/**
			 * Add vote into polled data
			 */
			foreach ( $checked_data as $option_id ) {
				$polled_data[ $poller ][] = $option_id;
			}


			/**
			 * Action before saving a vote
			 *
			 * @param $poll_id
			 * @param $polled_data
			 * @param $poller
			 */
			do_action( 'wpp_before_vote', $poll_id, $polled_data, $poller );


			/**
			 * Save polled data
			 *
			 * @filter wpp_filter_before_saving_polled_data
			 */
			$response = update_post_meta( $poll_id, 'polled_data', apply_filters( 'wpp_filter_before_saving_polled_data', $polled_data ) );


			/**
			 * Action after saving a vote
			 *
			 * @param $poll_id
			 * @param $polled_data
			 * @param $poller
			 * @param $response
			 */
			do_action( 'wpp_after_vote', $poll_id, $polled_data, $poller, $response );


			/*
			 * Return if all goes well
			 */
			if ( $response ) {
				wp_send_json_success( esc_html__( 'Congratulations, Successfully voted.', 'wp-poll' ) );
			}

			/**
			 * Something must be wrong
			 */
			wp_send_json_error( esc_html__( 'Something went wrong !', 'wp-poll' ) );
		}


		/**
		 * Add new option
		 *
		 * ajax: wpp_front_new_option
		 */
		function wpp_front_new_option() {

			$poll_id      = isset( $_POST['poll_id'] ) ? sanitize_text_field( $_POST['poll_id'] ) : '';
			$option_value = isset( $_POST['opt_val'] ) ? sanitize_text_field( $_POST['opt_val'] ) : '';

			if ( empty( $option_value ) || empty( $poll_id ) ) {
				wp_send_json_error();
			}

			global $poll;

			$poll   = wpp_get_poll( $poll_id );
			$option = $poll->add_poll_option( $option_value );

			if ( $option ) {

				ob_start();
				wpp_get_template( 'single-poll/options-single.php', $option );
				$options_html = ob_get_clean();

				wp_send_json_success( $options_html );
			}

			wp_send_json_error( esc_html__( 'Something went wrong', 'wp-poll' ) );
		}


		/**
		 * New poll option html
		 *
		 * @throws PB_Error
		 */
		function ajax_new_poll_option() {

			ob_start();

			wpp_add_poll_option();

			wp_send_json_success( ob_get_clean() );
		}


		/**
		 * Filter Single Template for Poll post type
		 *
		 * @param $single_template
		 *
		 * @return string
		 */
		function single_poll_template( $single_template ) {

			if ( is_singular( 'poll' ) ) {
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
				echo sprintf( '<span class="view_report"><a href="%s" rel="permalink">' . __( 'View Reports', 'wp-poll' ) . '</a></span>', "edit.php?post_type=poll&page=wpp-settings&tab=wpp-reports&poll-id=" . $post_id );
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