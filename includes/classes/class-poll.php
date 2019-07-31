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


if ( ! class_exists( 'WPP_Poll' ) ) {
	/**
	 * Class WPP_Hooks
	 */
	class WPP_Poll extends WPP_Item_data {


		/**
		 * WPP_Poll constructor.
		 *
		 * @param bool $poll_id
		 */
		function __construct( $poll_id = false ) {
			parent::__construct( $poll_id );
		}


		/**
		 * Return whether a poll is ready to vote or not
		 *
		 * @return mixed|void
		 */
		function ready_to_vote() {

			$can_vote      = true;
			$poll_deadline = $this->get_poll_deadline( 'U' );

			if ( ! empty( $poll_deadline ) && $poll_deadline !== 0 ) {

				// Check allow/disallow
				if ( ! $this->get_allows_disallows( 'vote_after_deadline' ) ) {
					$can_vote = false;
				}

				// Check deadline
				if ( date( 'U' ) < $poll_deadline ) {
					$can_vote = true;
				}
			}

			return apply_filters( 'wpp_filters_ready_to_vote', $can_vote, $this->get_id() );
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


			return apply_filters( 'wpp_poll_reports', $poll_reports, $reports_for, $this->get_id(), $this );
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
				return apply_filters( 'wpp_filter_get_option_label', esc_html__( 'N/A', 'wp-poll' ), $option_id, $this->get_id(), $this );
			}

			$poll_options = $this->get_poll_options();
			$option_label = isset( $poll_options[ $option_id ]['label'] ) ? $poll_options[ $option_id ]['label'] : '';

			if ( empty( $option_label ) ) {
				return apply_filters( 'wpp_filter_get_option_label', esc_html__( 'N/A', 'wp-poll' ), $option_id, $this->get_id(), $this );
			}

			return apply_filters( 'wpp_filter_get_option_label', $option_label, $option_id, $this->get_id(), $this );
		}


		/**
		 * Return poll results
		 *
		 * @return mixed|void
		 */
		function get_poll_results() {

			$polled_data  = $this->get_meta( 'polled_data', array() );
			$total_voted  = count( $polled_data );
			$poll_results = array( 'total' => $total_voted, 'singles' => array(), 'percentages' => array() );

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
				$poll_results['percentages'][ $option_id ] = floor( ( $single_count * 100 ) / $total_voted );
			}

			return apply_filters( 'wpp_filters_poll_results', $poll_results, $this->get_id(), $this );
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

			foreach ( $poll_options as $option_id => $option ) {

				$label     = isset( $option['label'] ) ? $option['label'] : '';
				$thumb_id  = isset( $option['thumb'] ) ? $option['thumb'] : '';
				$thumb_url = array();

				if ( ! empty( $thumb_id ) ) {
					$thumb_url = wp_get_attachment_image_src( $thumb_id );
				}

				$_poll_options[ $option_id ] = array(
					'label' => $label,
					'thumb' => reset( $thumb_url ),
				);
			}

			return apply_filters( 'wpp_filters_poll_options', $_poll_options );
		}


		/**
		 * Check whether users/visitors can vote multiple to a single poll or not
		 *
		 * @return bool
		 */
		function can_vote_multiple() {

			return $this->get_allows_disallows( 'multiple_votes' );
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
				return apply_filters( 'wpp_filters_get_style', $style, $style_of );
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

			return apply_filters( 'wpp_filters_get_style', $style, $style_of );
		}


		/**
		 * Return Poll Deadline
		 *
		 * @param string $format
		 *
		 * @return mixed|void
		 */
		function get_poll_deadline( $format = 'M j Y G:i:s' ) {

			$deadline = $this->get_meta( 'poll_deadline' );

			return apply_filters( 'wpp_filters_poll_deadline', date( $format, strtotime( $deadline ) ) );
		}


		/**
		 * Return bool whether visitors can add new option to a poll or not
		 *
		 * @return bool
		 */
		function visitors_can_add_option() {

			return $this->get_allows_disallows( 'new_options' );
		}


		/**
		 * Return allows disallows for a poll
		 *
		 * @param bool $thing_to_check vote_after_deadline | multiple_votes | new_options
		 *
		 * @return bool
		 */
		function get_allows_disallows( $thing_to_check = false ) {

			if ( ! $thing_to_check || empty( $thing_to_check ) ) {
				return false;
			}

			$allow_disallow = $this->get_meta( 'poll_allow_disallow', array() );

			if ( is_array( $allow_disallow ) && in_array( $thing_to_check, $allow_disallow ) ) {
				return true;
			}

			return false;
		}
	}

	new WPP_Poll();
}