<?php
/**
 * Class Functions
 *
 * @author Liquidpoll
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'LIQUIDPOLL_Functions' ) ) {
	/**
	 * Class LIQUIDPOLL_Functions
	 */
	class LIQUIDPOLL_Functions {

		/**
		 * @var LIQUIDPOLL_Poll_meta|null
		 */
		public $metaboxes = null;

		/**
		 * @var LIQUIDPOLL_Addons|null
		 */
		public $addons = null;

		/**
		 * @var LIQUIDPOLL_Poll_reports|null
		 */
		public $reports_table = null;


		protected $global_css = array();


		/**
		 * Add css to global scope
		 *
		 * @param $style_rule
		 */
		function add_global_style( $style_rule ) {
			$this->global_css[] = $style_rule;
		}


		/**
		 * Return global css array
		 *
		 * @return array
		 */
		function get_global_css() {
			return $this->global_css;
		}


		/**
		 * Return human-readable user information
		 *
		 * @param bool $user_id
		 * @param string $info_to_get
		 *
		 * @return bool|mixed
		 */
		function get_human_readable_info( $user_id = false, $info_to_get = 'user_email' ) {

			$user_id = ! $user_id ? get_current_user_id() : $user_id;
			$user    = get_user_by( 'ID', $user_id );

			if ( $user instanceof WP_User ) {
				return $user->{$info_to_get};
			}

			return false;
		}


		/**
		 * Print notices
		 *
		 * @param string $message
		 * @param string $type
		 * @param bool $is_dismissible
		 */
		function print_notice( $message = '', $type = 'success', $is_dismissible = true ) {

			$is_dismissible = $is_dismissible ? 'is-dismissible' : '';

			if ( ! empty( $message ) ) {
				printf( '<div class="notice notice-%s %s"><p>%s</p></div>', $type, $is_dismissible, $message );
			}
		}


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

			return apply_filters( 'liquidpoll_filters_site_navs', $site_navs, $args );
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
				$return = apply_filters( 'liquidpoll_filters_get_polls', get_posts( $args ) );
			}

			if ( $return_type == 'wp-query' ) {
				$return = apply_filters( 'liquidpoll_filters_get_polls', new WP_Query( $args ) );
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

			$args             = ! array( $args ) ? array() : $args;
			$args['taxonomy'] = 'poll_cat';

			return apply_filters( 'liquidpoll_filters_poll_categories', get_terms( $args ) );
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

			return apply_filters( 'liquidpoll_filters_poll_types', $poll_types );
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

			$archive_show_hide = $this->get_option( 'liquidpoll_archive_show_hide', array( 'pagination' ) );

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

			$items_per_page = $this->get_option( 'liquidpoll_archive_items_per_page', 10 );

			return apply_filters( 'liquidpoll_filters_polls_per_page', $items_per_page );
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

			if ( ! in_array( $button, array( 'new_option', 'submit','nps_submit', 'results' ) ) || empty( $button ) ) {
				return $button_text;
			}

			if ( $button == 'new_option' ) {
				$button_text = Utils::get_option( 'liquidpoll_btn_text_new_option', esc_html__( 'New option', 'wp-poll' ) );
			} else if ( $button == 'submit' ) {
				$button_text = Utils::get_option( 'liquidpoll_btn_text_submit', esc_html__( 'Submit', 'wp-poll' ) );
			} else if ( $button == 'nps_submit' ) {
				$button_text = Utils::get_option( 'liquidpoll_nps_btn_text_submit', esc_html__( 'Submit', 'wp-poll' ) );
			}
			if ( $button == 'results' ) {
				$button_text = Utils::get_option( 'liquidpoll_btn_text_results', esc_html__( 'Results', 'wp-poll' ) );
			}

			return apply_filters( 'liquidpoll_filters_button_text', $button_text, $button );
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

			return apply_filters( 'liquidpoll_filters_option_' . $option_key, $option_val );
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
		function poll_template_sections() {

			$template_sections = array(
				'liquidpoll_poll_notice'   => array(
					'label'    => __( 'Poll Notice', 'wp-poll' ),
					'callable' => 'notice',
					'priority' => 90,
				),
				'liquidpoll_poll_title'    => array(
					'label'    => __( 'Poll Title', 'wp-poll' ),
					'callable' => 'title',
					'priority' => 70,
				),
				'liquidpoll_poll_message'  => array(
					'label'    => __( 'Message Section', 'wp-poll' ),
					'callable' => 'message',
					'priority' => 30,
				),
				'liquidpoll_poll_thumb'    => array(
					'label'    => __( 'Poll Thumbnail', 'wp-poll' ),
					'callable' => 'thumb',
					'priority' => 50,
				),
				'liquidpoll_poll_content'  => array(
					'label'    => __( 'Content', 'wp-poll' ),
					'callable' => 'content',
					'priority' => 30,
				),
				'liquidpoll_poll_options'  => array(
					'label'    => __( 'Poll Options', 'wp-poll' ),
					'callable' => 'options',
					'priority' => 90,
				),
				'liquidpoll_poll_results'  => array(
					'label'    => __( 'Results', 'wp-poll' ),
					'callable' => 'results',
					'priority' => 80,
				),
				'liquidpoll_poll_buttons'  => array(
					'label'    => __( 'Buttons', 'wp-poll' ),
					'callable' => 'buttons',
					'priority' => 90,
				),
				'liquidpoll_poll_comments' => array(
					'label'    => __( 'Comments', 'wp-poll' ),
					'callable' => 'comments',
					'priority' => 30,
				),

			);

			return apply_filters( 'liquidpoll_filters_poll_template_sections', $template_sections );
		}


		/**
		 * Check if this plugin is pro version or not
		 *
		 * @return bool
		 */
		function is_pro() {
			return apply_filters( 'liquidpoll_filters_is_pro', class_exists( 'LIQUIDPOLL_Pro_Main' ) );
		}


		/**
		 * Return NPS themes
		 *
		 * @return mixed|void
		 */
		function get_reaction_themes() {

			$themes = array(
				999 => array(
					'label'        => esc_html__( '3+ are coming soon', 'wp-poll' ),
					'availability' => 'upcoming',
				),
			);
			$themes = apply_filters( 'LiquidPoll/Filters/reaction_themes', $themes );

			ksort( $themes );

			return $themes;
		}


		/**
		 * Return NPS themes
		 *
		 * @return mixed|void
		 */
		function get_nps_themes() {

			$themes = array(
				1   => array(
					'label' => esc_html__( 'Theme 1', 'wp-poll' ),
				),
				998 => array(
					'label'        => esc_html__( '4+ are in pro', 'wp-poll' ),
					'availability' => 'pro',
				),
				999 => array(
					'label'        => esc_html__( '10+ are coming soon', 'wp-poll' ),
					'availability' => 'upcoming',
				),
			);
			$themes = apply_filters( 'LiquidPoll/Filters/nps_themes', $themes );

			ksort( $themes );

			return $themes;
		}


		/**
		 * Return poll themes
		 *
		 * @return mixed|void
		 */
		function get_poll_themes() {

			$themes = array(
				1   => array(
					'label' => esc_html__( 'Theme 1', 'wp-poll' ),
				),
				2   => array(
					'label' => esc_html__( 'Theme 2', 'wp-poll' ),
				),
				3   => array(
					'label' => esc_html__( 'Theme 3', 'wp-poll' ),
				),
				998 => array(
					'label'        => esc_html__( '10+ are in pro', 'wp-poll' ),
					'availability' => 'pro',
				),
				999 => array(
					'label'        => esc_html__( '20+ are coming soon', 'wp-poll' ),
					'availability' => 'upcoming',
				),
			);
			$themes = apply_filters( 'LiquidPoll/Filters/poll_themes', $themes );

			ksort( $themes );

			return $themes;
		}


		/**
		 * Return elementor widget argument
		 *
		 * @param $key
		 * @param $default
		 *
		 * @return array|bool|mixed|string
		 */
		function get_widget_arg_val( $key = '', $default = '' ) {

			global $liquidpoll_widget_settings;

			return Utils::get_args_option( $key, $liquidpoll_widget_settings, $default );
		}


		/**
		 * Return Arguments Value
		 *
		 * @param string $key
		 * @param string $default
		 * @param array $args
		 *
		 * @return mixed|string
		 */
		function get_args_option( $key = '', $default = '', $args = array() ) {

			global $this_preloader;

			$args    = empty( $args ) ? $this_preloader : $args;
			$default = empty( $default ) ? '' : $default;
			$key     = empty( $key ) ? '' : $key;

			if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
				return $args[ $key ];
			}

			return $default;
		}
	}
}

global $liquidpoll;

$liquidpoll = new LIQUIDPOLL_Functions();