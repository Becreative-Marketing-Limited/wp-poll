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

			add_action( 'init', array( $this, 'register_everything' ) );

			add_action( 'manage_poll_posts_columns', array( $this, 'add_core_poll_columns' ), 16, 1 );
			add_action( 'manage_poll_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
			add_filter( 'post_row_actions', array( $this, 'remove_row_actions' ), 10, 1 );

			add_filter( 'single_template', array( $this, 'single_poll_template' ) );

			add_action( 'wp_ajax_wpp_ajax_add_option', array( $this, 'ajax_new_poll_option' ) );
			add_action( 'wp_ajax_wpp_front_new_option', array( $this, 'wpp_front_new_option' ) );
			add_action( 'wp_ajax_nopriv_wpp_front_new_option', array( $this, 'wpp_front_new_option' ) );

			add_action( 'wp_ajax_wpp_submit_poll', array( $this, 'wpp_submit_poll' ) );
			add_action( 'wp_ajax_nopriv_wpp_submit_poll', array( $this, 'wpp_submit_poll' ) );

			add_action( 'wp_ajax_wpp_get_poll_results', array( $this, 'wpp_get_poll_results' ) );
			add_action( 'wp_ajax_nopriv_wpp_get_poll_results', array( $this, 'wpp_get_poll_results' ) );

			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta' ), 10, 2 );
			add_filter( 'plugin_action_links_' . WPP_PLUGIN_FILE, array( $this, 'add_plugin_actions' ), 10, 2 );

			add_action( 'pb_settings_wpp-extensions', array( $this, 'render_extensions' ) );

			add_action( 'wp_ajax_wpp_report_download_csv', array( $this, 'wpp_report_download_csv' ) );
		}


		function wpp_report_download_csv() {

			$export_nonce = isset( $_REQUEST['wpp_export_nonce_value'] ) ? $_REQUEST['wpp_export_nonce_value'] : '';
			if ( ! wp_verify_nonce( $export_nonce, 'wpp_export_nonce' ) ) {
				return;
			}

			$poll_id = isset( $_REQUEST['wpp_reports_poll_id'] ) ? sanitize_text_field( $_REQUEST['wpp_reports_poll_id'] ) : '';
			$poll    = wpp_get_poll( $poll_id );

			$poll_reports   = array();
			$polled_data    = $poll->get_polled_data();
			$filename       = $poll->get_name() . '_' . date( "Y_m_d_H_i_s" );
			$poll_options   = array_map( function ( $option ) {
				return wpp()->get_args_option( 'label', '', $option );
			}, $poll->get_poll_options() );
			$poll_reports[] = array_merge( array( esc_html__( 'Poller information', 'wp-poll' ) ), array_values( $poll_options ) );

			foreach ( $polled_data as $poller => $polled_options ) {

				if ( empty( $polled_options ) || ! is_array( $polled_options ) ) {
					continue;
				}

				$single_row   = array();
				$single_row[] = is_int( $poller ) ? wpp()->get_human_readable_info( $poller ) : $poller;;

				foreach ( $poll_options as $option_id => $option_label ) {
					$single_row[] = in_array( $option_id, $polled_options ) ? 'yes' : '';
				}

				$poll_reports[] = $single_row;
			}


			header( "Content-Type: text/csv" );
			header( "Content-Disposition: attachment; filename=$filename.csv" );
			header( "Cache-Control: no-cache, no-store, must-revalidate" );
			header( "Pragma: no-cache" );
			header( "Expires: 0" );

			$out = fopen( 'php://output', 'w' );

			foreach ( $poll_reports as $poll_report ) {
				fputcsv( $out, $poll_report );
			}

			fclose( $out );
			die();
		}


		/**
		 * Render Extensions
		 */
		function render_extensions() {
			require( WPP_PLUGIN_DIR . 'includes/admin-templates/extensions.php' );
		}


		/**
		 * Add custom links to Plugin actions
		 *
		 * @param $links
		 *
		 * @return array
		 */
		function add_plugin_actions( $links ) {

			$links = array_merge( array(
				'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=poll&page=wpp-settings' ), esc_html__( 'Settings', 'wp-poll' ) ),
//				'extensions' => sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=poll&page=wpp-settings&tab=wpp-extensions' ), esc_html__( 'Extensions', 'wp-poll' ) ),
			), $links );

			if ( ! wpp()->is_pro() ) {
				$links['go-pro'] = sprintf( '<a href="%s">%s</a>', esc_url( WPP_PLUGIN_LINK ), esc_html__( 'Go Pro', 'wp-poll' ) );
			}

			return $links;
		}


		/**
		 * Add custom links to plugin meta
		 *
		 * @param $links
		 * @param $file
		 *
		 * @return array
		 */
		function add_plugin_meta( $links, $file ) {

			if ( WPP_PLUGIN_FILE === $file ) {

				$row_meta = array(
					'docs'    => sprintf( '<a href="%s" class="wpp-doc" target="_blank">%s</a>', esc_url( WPP_DOCS_URL ), esc_html__( 'Documentation', 'wp-poll' ) ),
					'support' => sprintf( '<a href="%s" class="wpp-support" target="_blank">%s</a>', esc_url( PB_TICKET_URL ), esc_html__( 'Support Ticket', 'wp-poll' ) ),
				);

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
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
		 */
		function ajax_new_poll_option() {

			$poll_id = isset( $_GET['poll_id'] ) ? sanitize_text_field( $_GET['poll_id'] ) : '';

			ob_start();

			wpp_add_poll_option( false, array( 'poll_id' => $poll_id ) );

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

			$original_template = $single_template;

			if ( is_singular( 'poll' ) ) {
				$single_template = WPP_PLUGIN_DIR . 'templates/single-poll.php';
			}

			return apply_filters( 'wpp_filters_single_poll_template', $single_template, $original_template );
		}


		function add_core_poll_columns( $columns ) {

			$new = array();

			$count = 0;
			foreach ( $columns as $col_id => $col_label ) {
				$count ++;

				if ( $count == 3 ) {
					$new['poll-report'] = esc_html__( 'Poll Report', 'wp-poll' );
				}

				if ( 'title' === $col_id ) {
					$new[ $col_id ] = esc_html__( 'Poll title', 'wp-poll' );
				} else {
					$new[ $col_id ] = $col_label;
				}

				unset( $new['date'] );
			}

			$new['poll-date'] = esc_html__( 'Published at', 'wp-poll' );

			return $new;
		}


		function custom_columns_content( $column, $post_id ) {

			global $wpp;

			if ( $column == 'poll-report' ):

				$polled_data = $wpp->get_meta( 'polled_data', $post_id, array() );

				echo sprintf( "<i>%d %s</i>", count( $polled_data ), esc_html__( 'people polled on this', 'wp-poll' ) );
				echo '<div class="row-actions">';
				echo sprintf( '<span class="view_report"><a href="%s" rel="permalink">' . esc_html__( 'View Reports', 'wp-poll' ) . '</a></span>', "edit.php?post_type=poll&page=wpp-settings&tab=wpp-reports&poll-id=" . $post_id );
				echo '</div>';

			endif;

			if ( $column == 'poll-date' ):

				$time_ago = human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) );
				echo "<i>$time_ago " . esc_html__( 'ago', 'wp-poll' ) . "</i>";

			endif;
		}

		/**
		 * Remove Post row actions
		 *
		 * @param $actions
		 *
		 * @return mixed
		 */
		public function remove_row_actions( $actions ) {
			global $post;

			if ( $post->post_type === 'poll' ) {
				unset( $actions['inline hide-if-no-js'] );
			}

			return $actions;
		}


		/**
		 * Register Post types, Taxes, Pages and Shortcodes
		 */
		function register_everything() {

			// Register post type - Poll
			wpp()->PB_Settings()->register_post_type( 'poll', apply_filters( 'wpp_filters_post_type_poll', array(
				'singular'      => esc_html__( 'WP Poll', 'wp-poll' ),
				'plural'        => esc_html__( 'All Polls', 'wp-poll' ),
				'labels'        => array(
					'add_new' => esc_html__( 'Add New', 'wp-poll' ),
				),
				'menu_icon'     => 'dashicons-chart-bar',
				'menu_position' => 15,
				'supports'      => array( 'title' ),
			) ) );

			do_action( 'wpp_register_post_types', wpp()->PB_Settings() );


			// Register Taxonomy - poll_cat
			wpp()->PB_Settings()->register_taxonomy( 'poll_cat', 'poll', apply_filters( 'wpp_filters_tax_poll_cat', array(
				'singular'     => esc_html__( 'Poll Category', 'wp-poll' ),
				'plural'       => esc_html__( 'Poll Categories', 'wp-poll' ),
				'hierarchical' => true,
			) ) );


			// Add Settings Menu
			wpp()->PB_Settings( array(
				'add_in_menu'     => true,
				'menu_type'       => 'submenu',
				'menu_title'      => esc_html__( 'Settings', 'wp-poll' ),
				'page_title'      => esc_html__( 'Settings', 'wp-poll' ),
				'menu_page_title' => esc_html__( 'WP Poll - Control Panel', 'wp-poll' ),
				'capability'      => "manage_options",
				'menu_slug'       => 'wpp-settings',
				'parent_slug'     => "edit.php?post_type=poll",
				'pages'           => wpp()->get_plugin_settings(),
				'disabled_notice' => sprintf( '%s <a href="%s?ref=%s" target="_blank">%s</a>',
					esc_html__( 'This feature is locked.', 'wp-poll' ), WPP_PLUGIN_LINK, get_site_url(),
					esc_html__( 'Get pro', 'wp-poll' )
				),
			) );

			do_action( 'wpp_after_settings_menu' );
		}
	}

	new WPP_Hooks();
}