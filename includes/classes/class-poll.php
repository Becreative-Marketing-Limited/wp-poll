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


		function get_poll_content() {

//			return
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