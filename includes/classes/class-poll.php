<?php
/**
 * Class Poll
 *
 * @author Pluginbazar
 * @package includes/classes/class-poll
 */

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
		 * Return Poll type
		 *
		 * @return string
		 */
		function get_poll_type() {

			$poll_type = 'poll';
			$poll_type = $this->get_meta( 'poll_type', $poll_type );

			if ( $poll_type == 'survey' && ! defined( 'LIQUIDPOLLS_PLUGIN_FILE' ) ) {
				$poll_type = 'poll';
			}

			return apply_filters( 'liquidpoll_filters_poll_type', $poll_type, $this->get_id() );
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
				if ( date( 'U' ) < $poll_deadline ) {
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
			$option_label = isset( $poll_options[ $option_id ]['label'] ) ? $poll_options[ $option_id ]['label'] : '';

			if ( empty( $option_label ) ) {
				return apply_filters( 'liquidpoll_filter_get_option_label', esc_html__( 'N/A', 'wp-poll' ), $option_id, $this->get_id(), $this );
			}

			return apply_filters( 'liquidpoll_filter_get_option_label', $option_label, $option_id, $this->get_id(), $this );
		}


		function get_polled_data() {

			$polled_data = $this->get_meta( 'polled_data', array() );

			return apply_filters( 'liquidpoll_filters_polled_data', $polled_data );
		}


		/**
		 * Return poll results
		 *
		 * @return mixed|void
		 */
		function get_poll_results() {

			$polled_data  = $this->get_polled_data();
			$total_voted  = count( $polled_data );
			$poll_results = array( 'total' => $total_voted, 'singles' => array(), 'percentages' => array() );
			$poll_options = $this->get_meta( 'poll_meta_options', array() );
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
		 * Return poll options as array
		 *
		 * @return mixed|void
		 */
		function get_poll_options() {

			$_poll_options = array();
			$poll_options  = $this->get_meta( 'poll_meta_options', array() );

			foreach ( $poll_options as $option_key => $option ) {
				$_poll_options[ $option_key ] = array(
					'label' => isset( $option['label'] ) ? $option['label'] : '',
					'thumb' => isset( $option['thumb']['url'] ) ? $option['thumb']['url'] : '',
				);
			}

			return apply_filters( 'liquidpoll_filters_poll_options', $_poll_options );
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
			$deadline = empty( $deadline ) ? '' : date( $format, strtotime( $deadline ) );

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