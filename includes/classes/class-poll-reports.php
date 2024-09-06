<?php
/**
 * Class Poll Reports
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


if ( ! class_exists( 'LIQUIDPOLL_Poll_reports' ) ) {
	class LIQUIDPOLL_Poll_reports extends WP_List_Table {


		/**
		 * @var array
		 */
		private $data = array();

		/**
		 * Add filter form
		 *
		 * @param string $which
		 */
		function extra_tablenav( $which ) {

			if ( $which == "top" ) {

				// Add nonce verification
				if ( ! isset( $_REQUEST['liquidpoll_reports_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['liquidpoll_reports_nonce'] ) ), 'liquidpoll_reports_action' ) ) {
					wp_die( 'Invalid nonce specified', 'Error', array( 'response' => 403 ) );
				}

				$current_page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
				$filter_type  = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';
				$object_id    = isset( $_REQUEST['object'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['object'] ) ) : '';
				$value_id     = isset( $_REQUEST['value'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['value'] ) ) : '';
				$date         = isset( $_REQUEST['date'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date'] ) ) : '';
				$date_1       = isset( $_REQUEST['date_1'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date_1'] ) ) : '';
				$date_2       = isset( $_REQUEST['date_2'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date_2'] ) ) : '';
				$date_1       = 'custom' == $date ? $date_1 : '';
				$date_2       = 'custom' == $date ? $date_2 : '';
				$all_objects  = array();
				$all_values   = array();

				if ( in_array( $filter_type, array( 'poll', 'nps', 'reaction' ) ) ) {
					$all_objects = get_posts( array(
						'post_type'      => 'poll',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'meta_query'     => array(
							array(
								'key'     => '_type',
								'value'   => $filter_type,
								'compare' => '=',
							),
						),
					) );

					if ( ! empty( $object_id ) ) {
						$poll_object = liquidpoll_get_poll( $object_id );
						$all_values  = $poll_object->get_poll_options();
					}
				}

				?>
                <div class="alignleft">
                    <form action="<?php echo admin_url( 'edit.php?post_type=poll&page=reports' ); ?>" method="get" class="liquidpoll-sort-form">
						<?php wp_nonce_field( 'liquidpoll_reports_action', 'liquidpoll_reports_nonce' ); ?>
                        <label>
                            <select name="type">
                                <option value=""><?php esc_html_e( 'All Types', 'wp-poll' ); ?></option>
                                <option <?php selected( $filter_type, 'poll' ); ?> value="poll"><?php esc_html_e( 'Poll', 'wp-poll' ); ?></option>
                                <option <?php selected( $filter_type, 'nps' ); ?> value="nps"><?php esc_html_e( 'NPS', 'wp-poll' ); ?></option>
                                <option <?php selected( $filter_type, 'reaction' ); ?> value="reaction"><?php esc_html_e( 'Reaction', 'wp-poll' ); ?></option>
                            </select>
                        </label>
                        <label>
                            <select name="object">
								<?php
								if ( empty( $all_objects ) ) {
									printf( '<option value="">%s</option>', esc_html__( 'Select Poll', 'wp-poll' ) );
								} else {
									printf( '<option value="">%s</option>', esc_html__( 'All Polls', 'wp-poll' ) );
								}

								foreach ( $all_objects as $object ) {
									printf( '<option %s value="%s">%s</option>', selected( $object_id, $object->ID, false ), $object->ID, $object->post_title );
								}
								?>
                            </select>
                        </label>
                        <label>
                            <select name="value">
								<?php
								if ( empty( $all_values ) ) {
									printf( '<option value="">%s</option>', esc_html__( 'Select Option', 'wp-poll' ) );
								} else {
									printf( '<option value="">%s</option>', esc_html__( 'All Options', 'wp-poll' ) );
								}

								foreach ( $all_values as $option_id => $option ) {
									printf( '<option %s value="%s">%s</option>', selected( $value_id, $option_id, false ), $option_id, Utils::get_args_option( 'label', $option ) );
								}
								?>
                            </select>
                        </label>

                        <label>
                            <select name="date">
                                <option value=""><?php esc_html_e( 'All time', 'wp-poll' ); ?></option>
                                <option <?php selected( $date, 'last_7' ); ?> value="last_7"><?php esc_html_e( 'Last 7 days', 'wp-poll' ); ?></option>
                                <option <?php selected( $date, 'last_15' ); ?> value="last_15"><?php esc_html_e( 'Last 15 days', 'wp-poll' ); ?></option>
                                <option <?php selected( $date, 'last_30' ); ?> value="last_30"><?php esc_html_e( 'Last 30 days', 'wp-poll' ); ?></option>
                                <option <?php selected( $date, 'custom' ); ?> value="custom"><?php esc_html_e( 'Custom', 'wp-poll' ); ?></option>
                            </select>
                        </label>

                        <label>
                            <input value="<?php echo esc_attr( $date_1 ); ?>" class="<?php echo ! empty( $date_1 ) ? '' : esc_attr( 'no-display' ); ?>" type="text" name="date_1" placeholder="YYYY-MM-DD">
                            <input value="<?php echo esc_attr( $date_2 ); ?>" class="<?php echo ! empty( $date_2 ) ? '' : esc_attr( 'no-display' ); ?>" type="text" name="date_2" placeholder="YYYY-MM-DD">
                        </label>

                        <input type="hidden" name="page" value="<?php echo esc_attr( $current_page ); ?>">
                        <input type="hidden" name="post_type" value="<?php echo esc_attr( 'poll' ); ?>">
                        <button class="button" type="submit"><?php echo esc_html__( 'Filter', 'wp-poll' ); ?></button>
                    </form>
                </div>
                <div class="alignleft">
                    <form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="post" class="liquidpoll-export-form <?php echo liquidpoll()->is_pro() ? '' : 'disabled'; ?>">
                        <input type="hidden" name="action" value="liquidpoll_download_reports">
                        <input type="hidden" name="type" value="<?php echo esc_attr( $filter_type ); ?>">
                        <input type="hidden" name="object" value="<?php echo esc_attr( $object_id ); ?>">
                        <input type="hidden" name="value" value="<?php echo esc_attr( $value_id ); ?>">
                        <input type="hidden" name="date" value="<?php echo esc_attr( $date ); ?>">
                        <input type="hidden" name="date_1" value="<?php echo esc_attr( $date_1 ); ?>">
                        <input type="hidden" name="date_2" value="<?php echo esc_attr( $date_2 ); ?>">
						<?php wp_nonce_field( 'liquidpoll_export_nonce', 'liquidpoll_export_nonce' ); ?>
                        <button type="submit" class="primary button liquidpoll-report-export"><span class="dashicons dashicons-download"></span><?php esc_html_e( 'Export', 'wp-poll' ); ?></button>
                    </form>
                </div>
				<?php
			}
		}


		/**
		 * Column object_name
		 *
		 * @param $item
		 *
		 * @return void
		 */
		function column_polled_value( $item ) {

			$poll_id      = Utils::get_args_option( 'poll_id', $item );
			$poll         = liquidpoll_get_poll( $poll_id );
			$polled_value = Utils::get_args_option( 'polled_value', $item );

			if ( 'reaction' == $poll->get_type() ) {
				printf( '<img alt="%s" src="%s">', $polled_value, liquidpoll()->metaboxes->get_reaction_emoji_url( $polled_value ) );
			} else {
				printf( '<span>%s</span>', $poll->get_option_label( $polled_value ) );
			}
		}


		/**
		 * Column consent
		 *
		 * @param $item
		 *
		 * @return void
		 */
		function column_consent( $item ) {

			$poll_id     = Utils::get_args_option( 'poll_id', $item );
			$poller      = Utils::get_args_option( 'poller_id_ip', $item );
			$poller_info = liquidpoll_get_data_from_email_table( $poll_id, $poller );
			$consent     = ! empty( Utils::get_args_option( 'consent', $poller_info ) ) ? Utils::get_args_option( 'consent', $poller_info ) : 'no';

			printf( '<span>%s</span>', ucwords( $consent ) );
		}


		/**
		 * Column poller_email
		 *
		 * @param $item
		 *
		 * @return void
		 */
		function column_poller_email( $item ) {

			$poll_id      = Utils::get_args_option( 'poll_id', $item );
			$poller       = Utils::get_args_option( 'poller_id_ip', $item );
			$poller_info  = liquidpoll_get_data_from_email_table( $poll_id, $poller );
			$poller_email = Utils::get_args_option( 'email_address', $poller_info );

			if ( ! filter_var( $poller, FILTER_VALIDATE_IP ) !== false ) {
				$poller_user = get_user_by( 'ID', $poller );
				printf( '<a href="%s">%s</a>', admin_url( 'user-edit.php?user_id=' . $poller ), $poller_user->user_email );
			} else {
				printf( '<span>%s</span>', $poller_email );
			}
		}


		/**
		 * Column poller_name
		 *
		 * @param $item
		 *
		 * @return void
		 */
		function column_poller_name( $item ) {

			$poll_id     = Utils::get_args_option( 'poll_id', $item );
			$poller      = Utils::get_args_option( 'poller_id_ip', $item );
			$poller_info = liquidpoll_get_data_from_email_table( $poll_id, $poller );
			$first_name  = Utils::get_args_option( 'first_name', $poller_info );
			$last_name   = Utils::get_args_option( 'last_name', $poller_info );

			if ( ! filter_var( $poller, FILTER_VALIDATE_IP ) !== false ) {
				$poller_user = get_user_by( 'ID', $poller );
				printf( '<a href="%s">%s</a>', admin_url( 'user-edit.php?user_id=' . $poller ), $poller_user->display_name );
			} else {
				printf( '<span>%s %s</span>', $first_name, $last_name );
			}
		}


		/**
		 * Column object_name
		 *
		 * @param $item
		 *
		 * @return void
		 */
		function column_polled_by( $item ) {

			$poller   = Utils::get_args_option( 'poller_id_ip', $item );
			$datetime = strtotime( Utils::get_args_option( 'datetime', $item ) );
			$timeago  = human_time_diff( $datetime, time() ) . ' ' . esc_html__( 'ago', 'wp-poll' );

			if ( ! filter_var( $poller, FILTER_VALIDATE_IP ) !== false ) {
				$poller_user = get_user_by( 'ID', $poller );
				printf( '<a href="%s">%s</a>', admin_url( 'user-edit.php?user_id=' . $poller ), $poller_user->display_name );
			} else {
				printf( '<span>%s</span>', $this->get_human_readable_ip_info( $poller ) );
			}

			$row_actions[] = sprintf( '<span class="timeago">%s</span>', $timeago );

			printf( '<div class="row-actions visible">%s</div>', implode( ' | ', $row_actions ) );
		}


		/**
		 * Column object_name
		 *
		 * @param $item
		 *
		 * @return void
		 */
		function column_poll( $item ) {

			$poll_id       = Utils::get_args_option( 'poll_id', $item );
			$result_id     = Utils::get_args_option( 'id', $item );
			$poll          = liquidpoll_get_poll( $poll_id );
			$row_actions[] = sprintf( '<span class="type %1$s">%1$s</span>', $poll->get_type() );
			$title_link    = $poll->get_type() == 'reviews' ? admin_url( 'edit.php?post_type=poll&page=reports&id=' . $result_id ) : $poll->get_permalink();

			printf( '<strong><a href="%s" class="row-title">%s</a></strong>', $title_link, $poll->get_name() );
			printf( '<div class="row-actions visible">%s</div>', implode( ' | ', $row_actions ) );
		}


		/**
		 * @param $item
		 * @param $column_name
		 *
		 * @return bool|mixed|string|void
		 */
		function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'id':
				case 'poll':
				case 'polled_by':
				case 'polled_value':
				case 'polled_comments':
				case 'poller_name':
				case 'poller_email':
				case 'consent':
				default:
					return Utils::get_args_option( $column_name, $item );
			}
		}


		/**
		 * @return array
		 */
		function get_columns() {
			return apply_filters( 'LiquidPoll/Filters/get_report_columns',
				array(
					'id'              => esc_html__( 'ID', 'wp-poll' ),
					'poll'            => esc_html__( 'Poll', 'wp-poll' ),
					'polled_by'       => esc_html__( 'Polled By', 'wp-poll' ),
					'polled_value'    => esc_html__( 'Polled Value', 'wp-poll' ),
					'polled_comments' => esc_html__( 'Comments', 'wp-poll' ),
					'poller_name'     => esc_html__( 'Name', 'wp-poll' ),
					'poller_email'    => esc_html__( 'Email', 'wp-poll' ),
					'consent'         => esc_html__( 'Consent', 'wp-poll' ),
				)
			);
		}


		/**
		 * Return downloaded data
		 *
		 * @return array|object|stdClass[]|null
		 */
		public static function get_data() {

			global $wpdb;

			// Add nonce verification
			if ( ! isset( $_REQUEST['liquidpoll_reports_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['liquidpoll_reports_nonce'] ) ), 'liquidpoll_reports_action' ) ) {
				wp_die( 'Invalid nonce specified', 'Error', array( 'response' => 403 ) );
			}

			$poll_type       = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';
			$object_id       = isset( $_REQUEST['object'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['object'] ) ) : '';
			$value_id        = isset( $_REQUEST['value'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['value'] ) ) : '';
			$date            = isset( $_REQUEST['date'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date'] ) ) : '';
			$date_1          = isset( $_REQUEST['date_1'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date_1'] ) ) : '';
			$date_2          = isset( $_REQUEST['date_2'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['date_2'] ) ) : '';
			$where_clauses[] = '1=1';

			if ( in_array( $poll_type, array( 'poll', 'nps', 'reaction' ) ) ) {
				$where_clauses[] = $wpdb->prepare( "poll_type = %s", $poll_type );
			}

			if ( ! empty( $object_id ) ) {
				$where_clauses[] = $wpdb->prepare( "poll_id = %s", $object_id );
			}

			if ( ! empty( $value_id ) ) {
				$where_clauses[] = $wpdb->prepare( "polled_value = %s", $value_id );
			}

			if ( 'last_30' == $date ) {
				$date_1 = gmdate( 'Y-m-d', strtotime( '-30 days' ) );
				$date_2 = gmdate( 'Y-m-d' );
			} else if ( 'last_15' == $date ) {
				$date_1 = gmdate( 'Y-m-d', strtotime( '-15 days' ) );
				$date_2 = gmdate( 'Y-m-d' );
			} else if ( 'last_7' == $date ) {
				$date_1 = gmdate( 'Y-m-d', strtotime( '-7 days' ) );
				$date_2 = gmdate( 'Y-m-d' );
			}

			if ( ! empty( $date_1 ) && ! empty( $date_2 ) ) {
				$where_clauses[] = $wpdb->prepare( "datetime BETWEEN %s AND %s", $date_1, $date_2 );
			}

			$where_conditions = implode( ' AND ', $where_clauses );
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$query = $wpdb->prepare(
				"SELECT * FROM {$wpdb->prefix}liquidpoll_results WHERE %1s ORDER BY datetime DESC",
				$where_conditions
			);
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$all_poll_data = $wpdb->get_results( $query, ARRAY_A );

			return apply_filters( 'LiquidPoll/Filters/get_data', $all_poll_data );
		}


		/**
		 * @return void
		 */
		function prepare_items() {

			$downloaded_data = $this->get_data();
			$columns         = $this->get_columns();
			$per_page        = 20;
			$current_page    = $this->get_pagenum();
			$count           = count( $downloaded_data );
			$this->data      = array_slice( $downloaded_data, ( ( $current_page - 1 ) * $per_page ), $per_page );

			$this->set_pagination_args( array(
				'total_items' => $count,
				'per_page'    => $per_page,
			) );

			$this->_column_headers = array( $columns );
			$this->items           = $this->data;
		}


		/**
		 * Return human-readable ip information
		 *
		 * @param $ip_address
		 *
		 * @return string
		 */
		public static function get_human_readable_ip_info( $ip_address ) {

			if ( empty( $ipinfo_token = Utils::get_option( 'liquidpoll_ipinfo_token' ) ) ) {
				return esc_html__( 'Someone from the Earth', 'wp-poll' );
			}

			if ( is_wp_error( $response = wp_remote_get( 'https://ipinfo.io/' . $ip_address . '/json?token=' . $ipinfo_token ) ) ) {
				return esc_html__( 'Someone from the Earth', 'wp-poll' );
			}

			$response = wp_remote_retrieve_body( $response );
			$response = json_decode( $response, true );

			if ( isset( $response['city'] ) && isset( $response['country'] ) ) {
				return sprintf( esc_html__( 'Someone from %s, %s', 'wp-poll' ), $response['city'], $response['country'] );
			}

			return esc_html__( 'Someone from the Earth', 'wp-poll' );
		}
	}
}