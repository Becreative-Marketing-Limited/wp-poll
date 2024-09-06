<?php
/**
 * Class Poll
 *
 * @author Liquidpoll
 * @package includes/classes/class-poll
 */

use WPDK\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if ( ! class_exists( 'LIQUIDPOLL_Poll' ) ) {
	/**
	 * Class LIQUIDPOLL_Hooks
	 */
	class LIQUIDPOLL_Poll extends LIQUIDPOLL_Item_data {

		/**
		 * LIQUIDPOLL_Poll constructor.
		 *
		 * @param int $poll_id
		 * @param array $args
		 */
		function __construct( $poll_id = 0, $args = array() ) {
			parent::__construct( $poll_id, $args );
		}


		/**
		 * Return whether a poll is ready to vote or not checking deadline
		 *
		 * @return mixed|void
		 */
		function ready_to_vote() {

			$can_vote      = true;
			$poll_deadline = $this->get_poll_deadline( 'U' );

			if ( ! empty( $poll_deadline ) && $poll_deadline !== 0 ) {

				// Check allow/disallow
				if ( ! $this->get_meta( 'settings_vote_after_deadline' ) ) {
					$can_vote = false;
				}

				// Check deadline
				if ( gmdate( 'U' ) < $poll_deadline ) {
					$can_vote = true;
				}
			}

			return apply_filters( 'liquidpoll_filters_ready_to_vote', $can_vote, $this->get_id() );
		}


		/**
		 * @param bool $reports_for false | labels | percentages | counts | total_votes
		 *
		 * @return mixed|void
		 */
		function get_poll_reports( $reports_for = false ) {

			$poll_reports = array();
			$poll_options = $this->get_poll_options();
			$poll_results = $this->get_poll_results();


			foreach ( $poll_options as $option_id => $option ) {

				$poll_reports[ $option_id ] = array(
					'label'      => isset( $option['label'] ) ? $option['label'] : '',
					'percentage' => isset( $poll_results['percentages'][ $option_id ] ) ? $poll_results['percentages'][ $option_id ] : 0,
					'count'      => isset( $poll_results['singles'][ $option_id ] ) ? $poll_results['singles'][ $option_id ] : 0,
				);
			}


			/**
			 * Reports for : labels
			 */
			if ( $reports_for == 'labels' ) {
				$poll_reports = array_map( function ( $report ) {
					return isset( $report['label'] ) ? $report['label'] : '';
				}, $poll_reports );
			}


			/**
			 * Report for : percentages
			 */
			if ( $reports_for == 'percentages' ) {
				$poll_reports = array_map( function ( $report ) {
					return isset( $report['percentage'] ) ? $report['percentage'] : 0;
				}, $poll_reports );
			}


			/**
			 * Report for : counts
			 */
			if ( $reports_for == 'counts' ) {
				$poll_reports = array_map( function ( $report ) {
					return isset( $report['count'] ) ? $report['count'] : 0;
				}, $poll_reports );
			}


			/**
			 * Report for : total_votes
			 */
			if ( $reports_for == 'total_votes' ) {
				$poll_reports = isset( $poll_results['total'] ) ? $poll_results['total'] : 0;
			}

			return apply_filters( 'liquidpoll_poll_reports', $poll_reports, $reports_for, $this->get_id(), $this );
		}


		/**
		 * Return Option Label upon giving Option ID
		 *
		 * @param bool $option_id
		 *
		 * @return mixed|void
		 */
		function get_option_label( $option_id = false ) {

			if ( ! $option_id ) {
				return apply_filters( 'liquidpoll_filter_get_option_label', esc_html__( 'N/A', 'wp-poll' ), $option_id, $this->get_id(), $this );
			}

			$poll_options = $this->get_poll_options();

			if ( 'reaction' == $this->get_type() || 'reviews' == $this->get_type() ) {
				$option_label = $option_id;
			} else {
				$option_label = $poll_options[ $option_id ]['label'] ?? '';
			}

			if ( empty( $option_label ) ) {
				return apply_filters( 'liquidpoll_filter_get_option_label', esc_html__( 'N/A', 'wp-poll' ), $option_id, $this->get_id(), $this );
			}

			return apply_filters( 'liquidpoll_filter_get_option_label', $option_label, $option_id, $this->get_id(), $this );
		}


		function get_poller_details() {

			$poller_details = array();

			foreach ( $this->get_polled_data() as $poller_id_ip => $polled_option ) {
				$polled_option = reset( $polled_option );
//				$poller_id_ip  = is_string( $poller_id_ip ) ? $poller_id_ip : get_user_by( 'ID', $poller_id_ip )->user_email;

				$poller_details[ $polled_option ][] = $poller_id_ip;
			}

			return $poller_details;
		}


		function get_polled_data() {

			$polled_data = $this->get_meta( 'polled_data', array() );

			return apply_filters( 'liquidpoll_filters_polled_data', $polled_data );
		}


		/**
		 * If current user voted this poll
		 *
		 * @return bool
		 */
		function is_users_voted() {

			$all_pollers    = array_keys( $this->get_polled_data() );
			$current_poller = liquidpoll_get_poller();

			return in_array( $current_poller, $all_pollers );
		}


		/**
		 * Return poll results
		 *
		 * @return mixed|void
		 */
		function get_poll_results( $args = array() ) {

			global $wpdb;

			if ( 'nps' == $this->get_type() ) {
				$polled_data   = array();
				$poll_options  = $this->get_meta( 'poll_meta_options_nps', array() );
				$query_results = $wpdb->get_results( $wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}liquidpoll_results WHERE poll_id = %d AND poll_type = %s",
					$this->get_id(),
					'nps'
				), ARRAY_A );

				foreach ( $query_results as $query_result ) {
					if ( ! empty( $poller_id_ip = $query_result['poller_id_ip'] ) ) {
						$polled_data[ $poller_id_ip ][] = $query_result['polled_value'];
					}
				}
			} else if ( 'reaction' == $this->get_type() ) {
				$polled_data   = array();
				$poll_options  = $this->get_meta( 'poll_meta_options_reaction', array() );
				$query_results = $wpdb->get_results( $wpdb->prepare(
					"SELECT * FROM {$wpdb->prefix}liquidpoll_results WHERE poll_id = %d AND poll_type = %s",
					$this->get_id(),
					'reaction'
				), ARRAY_A );

				foreach ( $query_results as $query_result ) {
					if ( ! empty( $poller_id_ip = $query_result['poller_id_ip'] ) ) {
						$polled_data[ $poller_id_ip ][] = $query_result['polled_value'];
					}
				}
			} else if ( 'reviews' == $this->get_type() ) {

				$where_clause      = $wpdb->prepare("WHERE poll_id = %d AND poll_type = %s", $this->get_id(), 'reviews');
				$orderby_clause    = "ORDER BY datetime DESC";
				$rating            = Utils::get_args_option( 'rating', $args );
				$relevant_orderby  = Utils::get_args_option( 'relevant', $args );
				$filter_date       = Utils::get_args_option( 'filter_date', $args );
				$filter_by_ratings = Utils::get_args_option( 'filter_rating', $args );
				$status            = Utils::get_args_option( 'status', $args );

				if ( ! empty( $rating ) && $rating > 1 && $rating <= 5 ) {
					$where_clause .= $wpdb->prepare(" AND polled_value = %d", $rating);
				}

				if ( ! empty( $status ) ) {
					$where_clause .= $wpdb->prepare(" AND status = %s", $status);
				}

				if ( ! empty( $relevant_orderby ) && ( $relevant_orderby === 'DESC' || $relevant_orderby === 'ASC' ) ) {
					$orderby_clause = $wpdb->prepare("ORDER BY datetime %s", $relevant_orderby);
				}

				if ( ! empty( $filter_date ) ) {

					$date_1 = '';
					$date_2 = '';

					if ( 'last_30' == $filter_date ) {
						$date_1 = gmdate( 'Y-m-d', strtotime( '-30 days' ) );
						$date_2 = gmdate( 'Y-m-d' );
					} else if ( 'last_3' == $filter_date ) {
						$date_1 = gmdate( 'Y-m-d', strtotime( '-3 month' ) );
						$date_2 = gmdate( 'Y-m-d' );
					} else if ( 'last_6' == $filter_date ) {
						$date_1 = gmdate( 'Y-m-d', strtotime( '-6 month' ) );
						$date_2 = gmdate( 'Y-m-d' );
					} else if ( 'last_12' == $filter_date ) {
						$date_1 = gmdate( 'Y-m-d', strtotime( '-1 year' ) );
						$date_2 = gmdate( 'Y-m-d' );
					}

					if ( ! empty( $date_1 ) && ! empty( $date_2 ) ) {
						$where_clause .= $wpdb->prepare(" AND datetime BETWEEN %s AND %s", $date_1, $date_2);
					}

				}

				if ( ! empty( $filter_by_ratings ) && is_array( $filter_by_ratings ) ) {

					$filter_by_ratings = array_map( function ( $rating_value ) use ($wpdb) {
						return $wpdb->prepare("polled_value = %d", $rating_value);
					}, $filter_by_ratings );
					$filter_by_ratings = implode( ' OR ', $filter_by_ratings );
					$where_clause      .= " AND ($filter_by_ratings)";
				}

				return $wpdb->get_results( 
					$wpdb->prepare(
						"SELECT * FROM {$wpdb->prefix}liquidpoll_results %s %s",
						$where_clause,
						$orderby_clause
					),
					ARRAY_A
				);
			} else {
				$polled_data  = $this->get_polled_data();
				$poll_options = $this->get_meta( 'poll_meta_options', array() );
			}

			$total_voted  = count( $polled_data );
			$poll_results = array( 'total' => $total_voted, 'singles' => array(), 'percentages' => array() );
			$option_ids   = array_keys( $poll_options );

			/**
			 * Calculate vote count per single option
			 */
			foreach ( $polled_data as $poller => $polled_options ) {

				if ( empty( $polled_options ) || ! is_array( $polled_options ) ) {
					continue;
				}

				foreach ( $polled_options as $option_id ) {
					if ( ! isset( $poll_results['singles'][ $option_id ] ) ) {
						$poll_results['singles'][ $option_id ] = 0;
					}

					$poll_results['singles'][ $option_id ] ++;
				}
			}

			/**
			 * Calculate vote percentage per single option
			 */
			$singles = isset( $poll_results['singles'] ) ? $poll_results['singles'] : array();
			$singles = ! empty( $singles ) ? $singles : array();

			foreach ( $singles as $option_id => $single_count ) {
				$poll_results['percentages'][ $option_id ] = round( ( $single_count * 100 ) / $total_voted );
			}

			foreach ( $option_ids as $_option_id ) {

				if ( is_array( $poll_results['singles'] ) && ! in_array( $_option_id, array_keys( $poll_results['singles'] ) ) ) {
					$poll_results['singles'][ $_option_id ] = 0;
				}

				if ( is_array( $poll_results['percentages'] ) && ! in_array( $_option_id, array_keys( $poll_results['percentages'] ) ) ) {
					$poll_results['percentages'][ $_option_id ] = 0;
				}
			}

			return apply_filters( 'liquidpoll_filters_poll_results', $poll_results, $this->get_id(), $this );
		}


		/**
		 * Add new poll option
		 *
		 * @param string $option_label
		 * @param bool $from_frontend
		 *
		 * @return array|bool
		 */
		function add_poll_option( $option_label = '', $from_frontend = true ) {

			if ( empty( $option_label ) ) {
				return false;
			}

			$poll_options  = $this->get_meta( 'poll_meta_options', array() );
			$option_id     = hexdec( uniqid() );
			$option_to_add = array(
				'label'    => $option_label,
				'frontend' => true,
			);

			$poll_options[ $option_id ] = $option_to_add;

			if ( $this->update_meta( 'poll_meta_options', $poll_options ) ) {
				return array_merge( array( 'option_id' => $option_id ), $option_to_add );
			} else {
				return false;
			}
		}


		/**
		 * Check whether users/visitors can vote multiple to a single poll or not
		 *
		 * @return bool
		 */
		function can_vote_multiple() {
			return $this->get_meta( '_settings_multiple_votes', false );
		}


		/**
		 * Return style of some element of a Poll
		 *
		 * @param string $style_of
		 *
		 * @return mixed|void
		 */
		function get_style( $style_of = '' ) {

			$style = 1;

			if ( ! in_array( $style_of, array(
				'countdown',
				'options_theme',
				'animation_checkbox',
				'animation_radio'
			) ) ) {
				return apply_filters( 'liquidpoll_filters_get_style', $style, $style_of );
			}

			if ( $style_of == 'countdown' ) {
				$style = $this->get_meta( 'poll_style_countdown', 1 );
			}

			if ( $style_of == 'options_theme' ) {
				$style = $this->get_meta( 'poll_options_theme', 1 );
			}

			if ( $style_of == 'animation_checkbox' ) {
				$style = $this->get_meta( 'poll_animation_checkbox', 'checkmark' );
			}

			if ( $style_of == 'animation_radio' ) {
				$style = $this->get_meta( 'poll_animation_radio', 'checkmark' );
			}

			$style = is_array( $style ) ? reset( $style ) : $style;

			return apply_filters( 'liquidpoll_filters_get_style', $style, $style_of );
		}


		/**
		 * Return Poll Deadline
		 *
		 * @param string $format
		 *
		 * @return mixed|void
		 */
		function get_poll_deadline( $format = 'M j Y G:i:s' ) {

			$deadline = $this->get_meta( '_deadline' );
			$deadline = empty( $deadline ) ? '' : gmdate( $format, strtotime( $deadline ) );

			return apply_filters( 'liquidpoll_filters_poll_deadline', $deadline );
		}


		/**
		 * Return bool whether visitors can add new option to a poll or not
		 *
		 * @return bool
		 */
		function visitors_can_add_option() {
			return apply_filters( 'liquidpoll_filters_visitors_can_add_option', $this->get_meta( 'settings_new_options', false ), $this->get_id() );
		}


		/**
		 * Return true or false about displaying countdown timer
		 *
		 * @return bool
		 */
		function hide_countdown_timer() {
			return $this->get_meta( 'settings_hide_timer', false );
		}


		/**
		 * Return true or false about displaying poll results
		 *
		 * @return bool
		 */
		function hide_results() {
			return $this->get_meta( 'hide_results', false );
		}
	}

	new LIQUIDPOLL_Poll();
}