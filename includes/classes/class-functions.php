<?php
/**
 * Class Functions
 *
 * @author Pluginbazar
 */

if ( ! defined('ABSPATH')) exit;  // if direct access 

if( ! class_exists( 'WPP_Functions' ) ) {
	/**
	 * Class WPP_Functions
	 */
	class WPP_Functions {


		/**
		 * Return Post Meta Value
		 * 
		 * @param bool $meta_key
		 * @param bool $post_id
		 * @param string $default
		 *
		 * @return mixed|string|void
		 */
		function get_meta( $meta_key = false, $post_id = false, $default = '' ) {

			if ( ! $meta_key ) {
				return '';
			}

			$post_id    = ! $post_id ? get_the_ID() : $post_id;
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			$meta_value = empty( $meta_value ) ? $default : $meta_value;

			return apply_filters( 'eem_filters_get_meta', $meta_value, $meta_key, $post_id, $default );
		}


		/**
		 * Return option value
		 *
		 * @param string $option_key
		 * @param string $default_val
		 *
		 * @return mixed|string|void
		 */
		function get_option( $option_key = '', $default_val = '' ) {

			if ( empty( $option_key ) ) {
				return '';
			}

			$option_val = get_option( $option_key, $default_val );
			$option_val = empty( $option_val ) ? $default_val : $option_val;

			return apply_filters( 'wpp_filters_option_' . $option_key, $option_val );
		}


		/**
		 * Return PB_Settings class
		 *
		 * @param array $args
		 *
		 * @return PB_Settings
		 */
		function PB_Settings( $args = array() ) {

			return new PB_Settings( $args );
		}

		/**
		 * Single Poll Template Section
		 *
		 * @return mixed|void
		 */
		public function poll_template_sections() {

			$template_sections = array(
				'wpp_poll_notice'   => array(
					'label'    => __( 'Poll Notice', 'wp-poll' ),
					'callable' => 'notice',
					'priority' => 90,
				),
				'wpp_poll_title'    => array(
					'label'    => __( 'Poll Title', 'wp-poll' ),
					'callable' => 'title',
					'priority' => 70,
				),
				'wpp_poll_message'  => array(
					'label'    => __( 'Message Section', 'wp-poll' ),
					'callable' => 'message',
					'priority' => 30,
				),
				'wpp_poll_thumb'    => array(
					'label'    => __( 'Poll Thumbnail', 'wp-poll' ),
					'callable' => 'thumb',
					'priority' => 50,
				),
				'wpp_poll_content'  => array(
					'label'    => __( 'Content', 'wp-poll' ),
					'callable' => 'content',
					'priority' => 30,
				),
				'wpp_poll_options'  => array(
					'label'    => __( 'Poll Options', 'wp-poll' ),
					'callable' => 'options',
					'priority' => 90,
				),
				'wpp_poll_results'  => array(
					'label'    => __( 'Results', 'wp-poll' ),
					'callable' => 'results',
					'priority' => 80,
				),
				'wpp_poll_buttons'  => array(
					'label'    => __( 'Buttons', 'wp-poll' ),
					'callable' => 'buttons',
					'priority' => 90,
				),
				'wpp_poll_comments' => array(
					'label'    => __( 'Comments', 'wp-poll' ),
					'callable' => 'comments',
					'priority' => 30,
				),

			);

			return apply_filters( 'wpp_filters_poll_template_sections', $template_sections );
		}
	}
}

global $wpp;

$wpp = new WPP_Functions();