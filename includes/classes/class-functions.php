<?php
/**
 * Class Functions
 *
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

if ( ! class_exists( 'WPP_Functions' ) ) {
	/**
	 * Class WPP_Functions
	 */
	class WPP_Functions {


		/**
		 * Return site navigation menu list
		 *
		 * @param array $args
		 *
		 * @return mixed|void
		 */
		function get_site_navs( $args = array() ) {

			$site_navs = array();

			foreach ( wp_get_nav_menus( $args ) as $nav_menu ) {
				if ( ! $nav_menu instanceof WP_Term ) {
					continue;
				}
				$site_navs[ $nav_menu->term_id ] = $nav_menu->name;
			}

			return apply_filters( 'wpp_filters_site_navs', $site_navs, $args );
		}


		/**
		 * Return Polls
		 *
		 * @param array $args
		 * @param string $return_type array | wp_query
		 *
		 * @return array|mixed|void
		 */
		function get_polls( $args = array(), $return_type = 'array' ) {

			$return = array();

			/**
			 * set post type
			 */
			$args['post_type'] = 'poll';


			if ( $return_type == 'array' ) {
				$return = apply_filters( 'wpp_filters_get_polls', get_posts( $args ) );
			}

			if ( $return_type == 'wp-query' ) {
				$return = apply_filters( 'wpp_filters_get_polls', new WP_Query( $args ) );
			}

			return $return;
		}


		/**
		 * Return poll categories
		 *
		 * @param array $args
		 *
		 * @return mixed|void
		 */
		function get_poll_categories( $args = array() ) {

			$args['taxonomy'] = 'poll_cat';

			return apply_filters( 'wpp_filters_poll_categories', get_terms( $args ) );
		}


		/**
		 * Return Poll types
		 *
		 * @return mixed|void
		 */
		function get_poll_types() {

			$poll_types = array(
				'poll' => esc_html__( 'General Poll', 'wp-poll' ),
			);

			return apply_filters( 'wpp_filters_poll_types', $poll_types );
		}


		/**
		 * Display Items or Not in Archive page
		 *
		 * @param bool $thing_to_check page-content | results | thumb | pagination
		 *
		 * @return bool
		 */
		function display_on_archive( $thing_to_check = false ) {

			if ( ! $thing_to_check || empty( $thing_to_check ) ) {
				return false;
			}

			$archive_show_hide = $this->get_option( 'wpp_archive_show_hide', array( 'pagination' ) );

			if ( in_array( $thing_to_check, $archive_show_hide ) ) {
				return true;
			}

			return false;
		}


		/**
		 * Return the number of items per page
		 *
		 * @return mixed|void
		 */
		function get_polls_per_page() {

			$items_per_page = $this->get_option( 'wpp_archive_items_per_page', 10 );

			return apply_filters( 'wpp_filters_polls_per_page', $items_per_page );
		}


		/**
		 * Return button text for button
		 *
		 * @param string $button
		 *
		 * @return mixed|string|void
		 */
		function get_button_text( $button = '' ) {

			$button_text = esc_html__( 'Submit', 'wp-poll' );

			if ( ! in_array( $button, array( 'new_option', 'submit', 'results' ) ) || empty( $button ) ) {
				return $button_text;
			}

			if ( $button == 'new_option' ) {
				$button_text = $this->get_option( 'wpp_btn_text_new_option', esc_html__( 'New option', 'wp-poll' ) );
			} else if ( $button == 'submit' ) {
				$button_text = $this->get_option( 'wpp_btn_text_submit', esc_html__( 'Submit', 'wp-poll' ) );
			}
			if ( $button == 'results' ) {
				$button_text = $this->get_option( 'wpp_btn_text_results', esc_html__( 'Results', 'wp-poll' ) );
			}

			return apply_filters( 'wpp_filters_button_text', $button_text, $button );
		}


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