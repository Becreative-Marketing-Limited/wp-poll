<?php
/**
 * Class Hooks
 */


use WPDK\Utils;

if ( ! class_exists( 'LIQUIDPOLL_Hooks' ) ) {
	/**
	 * Class LIQUIDPOLL_Hooks
	 */
	class LIQUIDPOLL_Hooks {

		/**
		 * LIQUIDPOLL_Hooks constructor.
		 */
		function __construct() {

			add_action( 'init', array( $this, 'register_everything' ) );

			add_action( 'manage_poll_posts_columns', array( $this, 'add_core_poll_columns' ), 16, 1 );
			add_action( 'manage_poll_posts_custom_column', array( $this, 'custom_columns_content' ), 10, 2 );
			add_action( 'restrict_manage_posts', array( $this, 'add_dropdown_for_poll_type' ), 10, 2 );
			add_filter( 'parse_query', array( $this, 'filter_poll_type' ), 10, 2 );
			add_filter( 'post_row_actions', array( $this, 'remove_row_actions' ), 10, 1 );

			add_filter( 'single_template', array( $this, 'single_poll_template' ) );

			add_action( 'wp_ajax_liquidpoll_ajax_add_option', array( $this, 'ajax_new_poll_option' ) );
			add_action( 'wp_ajax_liquidpoll_front_new_option', array( $this, 'liquidpoll_front_new_option' ) );
			add_action( 'wp_ajax_nopriv_liquidpoll_front_new_option', array( $this, 'liquidpoll_front_new_option' ) );

			add_action( 'wp_ajax_liquidpoll_submit_poll', array( $this, 'liquidpoll_submit_poll' ) );
			add_action( 'wp_ajax_nopriv_liquidpoll_submit_poll', array( $this, 'liquidpoll_submit_poll' ) );

			add_action( 'wp_ajax_liquidpoll_get_poll_results', array( $this, 'liquidpoll_get_poll_results' ) );
			add_action( 'wp_ajax_nopriv_liquidpoll_get_poll_results', array( $this, 'liquidpoll_get_poll_results' ) );

			add_action( 'wp_ajax_liquidpoll_submit_optin_form', array( $this, 'handle_submit_optin_form' ) );
			add_action( 'wp_ajax_nopriv_liquidpoll_submit_optin_form', array( $this, 'handle_submit_optin_form' ) );

			add_filter( 'plugin_row_meta', array( $this, 'add_plugin_meta' ), 10, 2 );
			add_filter( 'plugin_action_links_' . LIQUIDPOLL_PLUGIN_FILE, array( $this, 'add_plugin_actions' ), 10, 2 );

			add_action( 'pb_settings_liquidpoll-extensions', array( $this, 'render_extensions' ) );
			add_action( 'wp_ajax_liquidpoll_report_download_csv', array( $this, 'download_csv_report' ) );

			add_action( 'wpdk_settings_after_meta_navs', array( $this, 'add_plugin_promotional_navs' ) );

			// NPS
			add_action( 'wp_ajax_liquidpoll_submit_nps', array( $this, 'liquidpoll_submit_nps' ) );
			add_action( 'wp_ajax_nopriv_liquidpoll_submit_nps', array( $this, 'liquidpoll_submit_nps' ) );

			add_filter( 'liquidpoll_filters_display_single_poll_main', array( $this, 'control_display_single_poll_main' ), 10, 2 );

			add_action( 'wp_footer', array( $this, 'render_global_css' ) );
			add_action( 'admin_menu', array( $this, 'add_reports_menu' ) );

			add_action( 'wp_ajax_liquidpoll_get_polls', array( $this, 'reports_get_polls' ) );
			add_action( 'wp_ajax_liquidpoll_get_option_values', array( $this, 'reports_get_option_values' ) );
			add_action( 'wp_ajax_liquidpoll-activate-addon', array( $this, 'activate_addon' ) );
		}


		/**
		 * Install and Activate add-on
		 *
		 * @return void
		 */
		function activate_addon() {
			$addon_id         = isset( $_POST['addon_id'] ) ? sanitize_text_field( wp_unslash( $_POST['addon_id'] ) ) : '';
			$addon_nonce      = isset( $_POST['addon_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['addon_nonce'] ) ) : '';
			$addon_nonce_name = isset( $_POST['addon_nonce_name'] ) ? sanitize_text_field( wp_unslash( $_POST['addon_nonce_name'] ) ) : '';

			if ( empty( $addon_id ) || empty( $addon_nonce_name ) ) {
				return;
			}

			if ( current_user_can( 'activate_plugins' ) && wp_verify_nonce( $addon_nonce, $addon_nonce_name ) ) {


				// Include required libs for installation
				require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
				require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
				require_once( ABSPATH . 'wp-admin/includes/class-wp-ajax-upgrader-skin.php' );
				require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php' );

				// Get Plugin Info
				$api      = plugins_api( 'plugin_information',
					array(
						'slug'   => $addon_id,
						'fields' => array(
							'short_description' => false,
							'sections'          => false,
							'requires'          => false,
							'rating'            => false,
							'ratings'           => false,
							'downloaded'        => false,
							'last_updated'      => false,
							'added'             => false,
							'tags'              => false,
							'compatibility'     => false,
							'homepage'          => false,
							'donate_link'       => false,
						),
					)
				);
				$skin     = new WP_Ajax_Upgrader_Skin();
				$upgrader = new Plugin_Upgrader( $skin );
				$upgrader->install( $api->download_link );

				defined( 'WP_ADMIN' ) || define( 'WP_ADMIN', true );
				defined( 'WP_NETWORK_ADMIN' ) || define( 'WP_NETWORK_ADMIN', true ); // Need for Multisite
				defined( 'WP_USER_ADMIN' ) || define( 'WP_USER_ADMIN', true );

				// Include required libs for activation
				require_once( '../wp-load.php' );
				require_once( '../wp-admin/includes/admin.php' );
				require_once( '../wp-admin/includes/plugin.php' );
				$plugin   = "{$api->slug}/{$api->slug}.php";
				$response = activate_plugin( $plugin );

				wp_send_json_success( $response );
			}
		}


		/**
		 * Return values on ajaox for filter in report page
		 */
		function reports_get_option_values() {

			$object_id        = isset( $_POST['object_id'] ) ? sanitize_text_field( wp_unslash( $_POST['object_id'] ) ) : '';
			$poll             = liquidpoll_get_poll( $object_id );
			$select_options[] = sprintf( '<option value="">%s</option>', esc_html__( 'All Values', 'wp-poll' ) );

			foreach ( $poll->get_poll_options() as $option_id => $option ) {
				$select_options[] = sprintf( '<option value="%s">%s</option>', $option_id, Utils::get_args_option( 'label', $option ) );
			}

			wp_send_json_success( implode( '', $select_options ) );
		}


		/**
		 * Return polls on ajaox for filter in report page
		 */
		function reports_get_polls() {

			$poll_type = isset( $_POST['poll_type'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_type'] ) ) : '';
			$all_polls = get_posts( array(
				'post_type'      => 'poll',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'     => '_type',
						'value'   => $poll_type,
						'compare' => '=',
					),
				),
			) );

			if ( empty( $poll_type ) ) {
				$select_options[] = sprintf( '<option value="">%s</option>', esc_html__( 'Select Poll', 'wp-poll' ) );
			} else {
				$select_options[] = sprintf( '<option value="">%s</option>', esc_html__( 'All ' . ucfirst( $poll_type ), 'wp-poll' ) );
			}

			foreach ( $all_polls as $poll_item ) {
				$select_options[] = sprintf( '<option value="%s">%s</option>', $poll_item->ID, $poll_item->post_title );
			}

			wp_send_json_success( implode( '', $select_options ) );
		}


		/**
		 * Display reports menu content
		 */
		function render_complete_report() {

			$report_table = new LIQUIDPOLL_Poll_reports();
			$current_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
			$result_id    = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';

			ob_start();

			if ( ! empty( $result_id ) ) {
				printf( '<h2>%s</h2>', esc_html__( 'LiquidPoll - Results Reply', 'wp-poll' ) );

				include LIQUIDPOLL_PLUGIN_DIR . 'includes/admin-templates/results-edit.php';

			} else {

				printf( '<h2>%s</h2>', esc_html__( 'LiquidPoll - Reports', 'wp-poll' ) );
				printf( '<p>%s</p>', esc_html__( 'Complete poll reports.', 'wp-poll' ) );

				$report_table->prepare_items();

				printf( '<form><input type="hidden" name="page" value="%s"></form>', $current_page );

				$report_table->display();
			}

			printf( '<div class="wrap">%s</div>', ob_get_clean() );
		}

		/**
		 * Add reports submenu
		 */
		function add_reports_menu() {
			add_submenu_page( 'edit.php?post_type=poll', esc_html__( 'Reports', 'wp-poll' ), esc_html__( 'Reports', 'wp-poll' ), 'manage_options', 'reports', array( $this, 'render_complete_report' ), 10 );
		}

		/**
		 * Control whether the poll will show or not
		 *
		 * @param bool $is_display
		 * @param LIQUIDPOLL_Poll $poll
		 *
		 * @return bool
		 */
		function control_display_single_poll_main( $is_display, LIQUIDPOLL_Poll $poll ) {

			if ( '1' == $poll->get_meta( 'settings_hide_for_logged_out_users', '0' ) && ! is_user_logged_in() ) {
				$is_display = false;
			}

			return $is_display;
		}


		/**
		 * Render global CSS
		 */
		function render_global_css() {
			printf( '<style>%s</style>', implode( liquidpoll()->get_global_css() ) );
		}


		/**
		 * Ajax Submit NPS
		 */
		function liquidpoll_submit_nps() {

			$poll_id    = isset( $_POST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) : '';
			$_form_data = isset( $_POST['form_data'] ) ? wp_unslash( $_POST['form_data'] ) : '';

			parse_str( $_form_data, $form_data );

			// Further sanitize individual form fields if necessary
			$form_data = array_map('sanitize_text_field', $form_data);

			$data_args = array(
				'poll_id'         => $poll_id,
				'poll_type'       => 'nps',
				'polled_value'    => Utils::get_args_option( 'nps_score', $form_data ),
				'polled_comments' => wp_kses_post( Utils::get_args_option( 'nps_feedback', $form_data ) ),
			);
			$response  = liquidpoll_insert_results( $data_args );

			if ( is_wp_error( $response ) ) {
				wp_send_json_error( Utils::get_option( 'liquidpoll_nps_text_failed', $response->get_error_message() ) );
			}

			do_action( 'liquidpoll_after_vote_nps', $data_args, $response );

			wp_send_json_success( Utils::get_option( 'liquidpoll_nps_text_success', esc_html__( 'Congratulations, Successfully voted.', 'wp-poll' ) ) );
		}

		/**
		 * Add promotional navigation tabs in meta box
		 */
		function add_plugin_promotional_navs() {

			if ( ! liquidpoll()->is_pro() ) {
				printf( '<li class="wpdk_settings-extra-nav get-pro"><a href="%s">%s</a></li>', LIQUIDPOLL_PLUGIN_LINK, esc_html__( 'Get Pro', 'wp-poll' ) );
			}

			printf( '<li class="wpdk_settings-extra-nav right"><a href="%s">%s</a></li>', LIQUIDPOLL_DOCS_URL, esc_html__( 'Documentation', 'wp-poll' ) );
			printf( '<li class="wpdk_settings-extra-nav right"><a href="%s">%s</a></li>', LIQUIDPOLL_COMMUNITY_URL, esc_html__( 'Community', 'wp-poll' ) );
		}

		/**
		 * Download poll report csv
		 */
		function download_csv_report() {

			$export_nonce = isset( $_REQUEST['liquidpoll_export_nonce_value'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['liquidpoll_export_nonce_value'] ) ) : '';
			if ( ! wp_verify_nonce( $export_nonce, 'liquidpoll_export_nonce' ) ) {
				return;
			}

			$poll_id = isset( $_REQUEST['liquidpoll_reports_poll_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['liquidpoll_reports_poll_id'] ) ) : '';
			$poll    = liquidpoll_get_poll( $poll_id );

			$poll_reports   = array();
			$polled_data    = $poll->get_polled_data();
			$filename       = $poll->get_name() . '_' . gmdate( "Y_m_d_H_i_s" );
			$poll_options   = array_map( function ( $option ) {
				return liquidpoll()->get_args_option( 'label', '', $option );
			}, $poll->get_poll_options() );
			$poll_reports[] = array_merge( array( esc_html__( 'Poller information', 'wp-poll' ) ), array_values( $poll_options ) );

			foreach ( $polled_data as $poller => $polled_options ) {

				if ( empty( $polled_options ) || ! is_array( $polled_options ) ) {
					continue;
				}

				$single_row   = array();
				$single_row[] = is_int( $poller ) ? liquidpoll()->get_human_readable_info( $poller ) : $poller;

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

			global $wp_filesystem;
			WP_Filesystem();
			
			$csv_content = '';
			foreach ( $poll_reports as $poll_report ) {
				$csv_content .= implode( ',', $poll_report ) . "\n";
			}
			$wp_filesystem->put_contents( 'php://output', $csv_content );
			die();
		}


		/**
		 * Render Extensions
		 */
		function render_extensions() {
			require( LIQUIDPOLL_PLUGIN_DIR . 'includes/admin-templates/extensions.php' );
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
				'settings' => sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=poll&page=settings' ), esc_html__( 'Settings', 'wp-poll' ) ),
//				'extensions' => sprintf( '<a href="%s">%s</a>', admin_url( 'edit.php?post_type=poll&page=liquidpoll-settings&tab=liquidpoll-extensions' ), esc_html__( 'Extensions', 'wp-poll' ) ),
			), $links );

			if ( ! liquidpoll()->is_pro() ) {
				$links['go-pro'] = sprintf( '<a href="%s">%s</a>', esc_url( LIQUIDPOLL_PLUGIN_LINK ), esc_html__( 'Go Pro', 'wp-poll' ) );
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

			if ( LIQUIDPOLL_PLUGIN_FILE === $file ) {

				$row_meta = array(
					'docs'    => sprintf( '<a href="%s" class="liquidpoll-doc" target="_blank">%s</a>', esc_url( LIQUIDPOLL_DOCS_URL ), esc_html__( 'Documentation', 'wp-poll' ) ),
					'support' => sprintf( '<a href="%s" class="liquidpoll-support" target="_blank">%s</a>', esc_attr( 'mailto:' . LIQUIDPOLL_TICKET_URL ), esc_html__( 'Support Ticket', 'wp-poll' ) ),
				);

				if ( is_plugin_active( 'wp-poll-pro/wp-poll-pro.php' ) ) {
					$row_meta['account'] = sprintf( '<a href="%s" class="liquidpoll-account" target="_blank">%s</a>', esc_url( LIQUIDPOLL_ACCOUNT_URL ), esc_html__( 'Account', 'wp-poll' ) );
				}

				return array_merge( $links, $row_meta );
			}

			return (array) $links;
		}


		/**
		 * Handle optin form submission
		 */
		function handle_submit_optin_form() {

			$_form_data = Utils::get_args_option( 'form_data', wp_unslash( $_POST ) );

			parse_str( $_form_data, $form_data );

			$response = liquidpoll_insert_email(
				array(
					'poll_id'       => Utils::get_args_option( 'poll_id', $form_data ),
					'first_name'    => Utils::get_args_option( 'first_name', $form_data ),
					'last_name'     => Utils::get_args_option( 'last_name', $form_data ),
					'email_address' => Utils::get_args_option( 'email_address', $form_data ),
					'consent'       => Utils::get_args_option( 'notice', $form_data ),
				)
			);

			if ( $response ) {
				wp_send_json_success( $response );
			}

			wp_send_json_error( esc_html__( 'Something went wrong.', 'wp-poll-pro' ) );
		}


		/**
		 * Ajax Get Poll Results
		 */
		function liquidpoll_get_poll_results() {

			$poll_id = isset( $_POST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) : '';

			if ( empty( $poll_id ) ) {
				wp_send_json_error( esc_html__( 'Invalid data found !', 'wp-poll' ) );
			}

			$poll = liquidpoll_get_poll( $poll_id );

			wp_send_json_success( $poll->get_poll_results() );
		}


		/**
		 * Ajax Submit poll
		 */
		function liquidpoll_submit_poll() {

			$poll_id      = isset( $_POST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) : '';
			$checked_data = isset( $_POST['checked_data'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['checked_data'] ) ) : array();
			$poll         = liquidpoll_get_poll( $poll_id );
			$checked_data = isset( $checked_data[0] ) ? $checked_data[0] : '';

			if ( empty( $poll_id ) ) {
				wp_send_json_error( esc_html__( 'Invalid data found !', 'wp-poll' ) );
			}

			$polled_data = $poll->get_meta( 'polled_data', array() );
			$poller      = liquidpoll_get_poller();

			/**
			 * Check if already voted
			 */
			if ( array_key_exists( $poller, $polled_data ) ) {
				wp_send_json_error( esc_html__( Utils::get_option( 'liquidpoll_poll_text_voted', 'You already voted on this poll.' ), 'wp-poll' ) );
			}

			/**
			 * Check ready to vote or not
			 */
			if ( ! $poll->ready_to_vote() ) {
				wp_send_json_error( esc_html__( Utils::get_option( 'liquidpoll_poll_text_expired', 'This poll has expired.' ), 'wp-poll' ) );
			}

			/**
			 * Add vote into polled data
			 */
			$polled_data[ $poller ][] = $checked_data;

			// insert into result table
			liquidpoll_insert_results(
				array(
					'poll_id'      => $poll_id,
					'polled_value' => $checked_data,
				)
			);

			/**
			 * Action before saving a vote
			 *
			 * @param $poll_id
			 * @param $polled_data
			 * @param $poller
			 */
			do_action( 'liquidpoll_before_vote', $poll_id, $polled_data, $poller );


			/**
			 * Save polled data
			 *
			 * @filter liquidpoll_filter_before_saving_polled_data
			 */
			$response = update_post_meta( $poll_id, 'polled_data', apply_filters( 'liquidpoll_filter_before_saving_polled_data', $polled_data ) );


			/**
			 * Action after saving a vote
			 *
			 * @param $poll_id
			 * @param $polled_data
			 * @param $poller
			 * @param $response
			 */
			do_action( 'liquidpoll_after_vote', $poll_id, $polled_data, $poller, $response );


			/*
			 * Return if all goes well
			 */
			if ( $response ) {
				wp_send_json_success( Utils::get_option( 'liquidpoll_poll_text_success', esc_html__( 'Congratulations, Successfully voted.', 'wp-poll' ) ) );
			}

			/**
			 * Something must be wrong
			 */
			wp_send_json_error( Utils::get_option( 'liquidpoll_poll_text_failed', esc_html__( 'Something went wrong.', 'wp-poll' ) ) );
		}


		/**
		 * Add new option
		 *
		 * ajax: liquidpoll_front_new_option
		 */
		function liquidpoll_front_new_option() {

			$poll_id      = isset( $_POST['poll_id'] ) ? sanitize_text_field( wp_unslash( $_POST['poll_id'] ) ) : '';
			$option_value = isset( $_POST['opt_val'] ) ? sanitize_text_field( wp_unslash( $_POST['opt_val'] ) ) : '';

			if ( empty( $option_value ) || empty( $poll_id ) ) {
				wp_send_json_error();
			}

			global $poll;

			$poll   = liquidpoll_get_poll( $poll_id );
			$option = $poll->add_poll_option( $option_value );

			if ( $option ) {

				ob_start();
				liquidpoll_get_template( 'single-poll/options-single.php', $option );
				$options_html = ob_get_clean();

				wp_send_json_success( $options_html );
			}

			wp_send_json_error( esc_html__( 'Something went wrong', 'wp-poll' ) );
		}


		/**
		 * New poll option html
		 */
		function ajax_new_poll_option() {

			$poll_id = isset( $_GET['poll_id'] ) ? sanitize_text_field( wp_unslash( $_GET['poll_id'] ) ) : '';

			ob_start();

			liquidpoll_add_poll_option( false, array( 'poll_id' => $poll_id ) );

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
				$single_template = LIQUIDPOLL_PLUGIN_DIR . 'templates/single-poll.php';
			}

			return apply_filters( 'liquidpoll_filters_single_poll_template', $single_template, $original_template );
		}


		/**
		 * Filter poll type
		 *
		 * @param $query
		 *
		 * @return void
		 */
		function filter_poll_type( $query ) {

			global $pagenow;

			$post_type   = isset( $_GET['post_type'] ) ? sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) : '';
			$filter_type = isset( $_REQUEST['poll_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['poll_type'] ) ) : '';

			if ( is_admin() && 'poll' == $post_type && 'edit.php' == $pagenow && $filter_type ) {
				$query->query_vars['meta_key']   = '_type';
				$query->query_vars['meta_value'] = $filter_type;
			}
		}


		/**
		 * Add dropdown filter for poll type
		 *
		 * @return void
		 */
		function add_dropdown_for_poll_type() {

			$filter_type = isset( $_REQUEST['poll_type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['poll_type'] ) ) : '';

			?>
            <div class="alignleft ">
                <form action="<?php echo admin_url( 'edit.php?post_type=poll' ); ?>" method="get">
                    <label>
                        <select name="poll_type">
                            <option value=""><?php esc_html_e( 'All Types', 'wp-poll' ); ?></option>
                            <option <?php selected( $filter_type, 'poll' ); ?> value="poll"><?php esc_html_e( 'Poll', 'wp-poll' ); ?></option>
                            <option <?php selected( $filter_type, 'nps' ); ?> value="nps"><?php esc_html_e( 'NPS', 'wp-poll' ); ?></option>
                            <option <?php selected( $filter_type, 'reaction' ); ?> value="reaction"><?php esc_html_e( 'Reaction', 'wp-poll' ); ?></option>
                            <option <?php selected( $filter_type, 'reviews' ); ?> value="reviews"><?php esc_html_e( 'Reviews', 'wp-poll' ); ?></option>
                        </select>
                    </label>
                </form>
            </div>
			<?php
		}


		function add_core_poll_columns( $columns ) {

			$new = array();

			$count = 0;
			foreach ( $columns as $col_id => $col_label ) {
				$count ++;

				if ( $count == 3 ) {
					$new['poll-type']   = esc_html__( 'Poll Type', 'wp-poll' );
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

			global $liquidpoll;

			$poll = liquidpoll_get_poll( $post_id );

			if ( $column == 'poll-type' ):

				echo sprintf( '<span class="poll-type type-%1$s">%1$s</span>', $poll->get_type() );

			endif;

			if ( $column == 'poll-report' ):

				$polled_data = $liquidpoll->get_meta( 'polled_data', $post_id, array() );

				echo sprintf( "<i>%d %s</i>", count( $polled_data ), esc_html__( 'people polled on this', 'wp-poll' ) );
				echo '<div class="row-actions">';
				echo sprintf( '<span class="view_report"><a href="%s" rel="permalink">' . esc_html__( 'View Reports', 'wp-poll' ) . '</a></span>', "admin.php?page=settings&poll-id={$post_id}#tab=reports" );
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

			global $liquidpoll_wpdk;

			// Register post type - Poll
			$liquidpoll_wpdk->utils()->register_post_type( 'poll', apply_filters( 'liquidpoll_filters_post_type_poll', array(
				'singular'      => esc_html__( 'LiquidPoll', 'wp-poll' ),
				'plural'        => esc_html__( 'All Polls', 'wp-poll' ),
				'labels'        => array(
					'add_new'   => esc_html__( 'Add Poll', 'wp-poll' ),
					'edit_item' => esc_html__( 'Edit Poll', 'wp-poll' ),
				),
				'menu_icon'     => 'dashicons-chart-bar',
				'menu_position' => 15,
				'supports'      => array( 'title', 'thumbnail' ),
			) ) );

			// Register Taxonomy - poll_cat
			$liquidpoll_wpdk->utils()->register_taxonomy( 'poll_cat', 'poll', apply_filters( 'liquidpoll_filters_tax_poll_cat', array(
				'singular'     => esc_html__( 'Poll Category', 'wp-poll' ),
				'plural'       => esc_html__( 'Poll Categories', 'wp-poll' ),
				'hierarchical' => true,
			) ) );

			// Add image size
			add_image_size( 'poll-square', 267, 258, true );
			add_image_size( 'poll-long-width', 555, 120, true );

			do_action( 'liquidpoll_after_settings_menu' );

			// Create data table if not exists
			liquidpoll_create_table();

			add_rewrite_endpoint( 'reviews', EP_ROOT | EP_PAGES );
		}
	}

	new LIQUIDPOLL_Hooks();
}