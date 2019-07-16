<?php
/**
 * Class Poll
 */


if ( ! class_exists( 'WPP_Poll' ) ) {
	/**
	 * Class WPP_Hooks
	 */
	class WPP_Poll {


		/**
		 * Poll ID
		 *
		 * @var null
		 */
		public $poll_id = null;


		/**
		 * Poll Post
		 *
		 * @var null
		 */
		public $poll_post = null;


		/**
		 * WPP_Poll constructor.
		 *
		 * @param bool $poll_id
		 */
		function __construct( $poll_id = false ) {

			$this->init( $poll_id );
		}


		/**
		 * Check whether users/visitors can vote multiple to a single poll or not
		 *
		 * @return bool
		 */
		function can_vote_multiple() {

			$can_multiple = $this->get_meta( 'poll_meta_multiple', 'no' );

			if( $can_multiple == 'yes' ) {
				return true;
			} else {
				return false;
			}
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

			if( ! in_array( $style_of, array( 'countdown', 'options' ) ) ) {
				return apply_filters( 'wpp_filters_get_style', $style, $style_of );
			}

			if( $style_of == 'countdown' ) {
				$style = $this->get_meta( 'poll_style_countdown', 1 );
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

			$is_new_option = $this->get_meta( 'poll_meta_new_option', 'no' );

			if( $is_new_option == 'yes' ) {
				return true;
			} else {
				return false;
			}
		}


		/**
		 * Return Poll content
		 *
		 * @return mixed|void
		 */
		function get_poll_content() {

			return apply_filters( 'wpp_filters_poll_content', $this->get_poll_post()->post_content );
		}


		/**
		 * Return Meta Value
		 *
		 * @param string $meta_key
		 * @param string $default
		 *
		 * @return mixed|void
		 */
		function get_meta( $meta_key = '', $default = '' ) {

			global $wpp;

			$meta_value = $wpp->get_meta( $meta_key, $this->get_id(), $default );

			return apply_filters( 'wpp_filters_get_poll_meta', $meta_value, $meta_key, $this );
		}


		/**
		 * Return Poll title
		 *
		 * @return mixed|void
		 */
		function get_name() {

			return apply_filters( 'wpp_filters_poll_name', \get_the_title( $this->get_id() ) );
		}


		/**
		 * Return poll ID
		 *
		 * @return bool|null
		 */
		function get_id() {
			return $this->poll_id;
		}


		/**
		 * Return Poll post object
		 *
		 * @return null
		 */
		function get_poll_post() {

			return $this->poll_post;
		}


		/**
		 * Initialize poll
		 *
		 * @param $poll_id
		 */
		function init( $poll_id ) {

			$this->poll_id   = ! $poll_id ? get_the_ID() : $poll_id;
			$this->poll_post = get_post( $this->poll_id );
		}
	}

	new WPP_Poll();
}