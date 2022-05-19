<?php
/**
 * Class Functions
 *
 * @author Pluginbazar
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPP_Functions' ) ) {
	/**
	 * Class WPP_Functions
	 */
	class WPP_Functions {


		/**
		 * Return human readable user information
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

			$args             = ! array( $args ) ? array() : $args;
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
		function poll_template_sections() {

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


		/**
		 * Return raw meta fields
		 *
		 * @param string $fields_for
		 *
		 * @return array
		 */
		function get_poll_meta_fields( $fields_for = 'general' ) {

//			$meta_fields['general']  = array(
//				array(
//					'id'          => 'post_title',
//					'title'       => esc_html__( 'Poll Title', 'wp-poll' ),
//					'placeholder' => esc_html__( 'Poll title here', 'wp-poll' ),
//					'details'     => esc_html__( 'Write a suitable title for this poll', 'wp-poll' ),
//					'type'        => 'text',
//				),
//				array(
//					'id'          => 'post_name',
//					'title'       => esc_html__( 'Poll Permalink', 'wp-poll' ),
//					'placeholder' => esc_html__( 'poll-slug-here', 'wp-poll' ),
//					'details'     => esc_html__( 'Leave blank to fill automatically from poll title. You just need to put the slug.', 'wp-poll' ),
//					'type'        => 'text',
//				),
//				array(
//					'id'            => 'content',
//					'title'         => esc_html__( 'Poll Content', 'wp-poll' ),
//					'details'       => esc_html__( 'Write some details about this poll', 'wp-poll' ),
//					'type'          => 'wp_editor',
//					'field_options' => array(
//						'media_buttons'    => false,
//						'editor_height'    => '120px',
//						'drag_drop_upload' => true,
//					),
//				),
//				array(
//					'id'    => '_thumbnail_id',
//					'title' => esc_html__( 'Featured Image', 'wp-poll' ),
//					'type'  => 'media',
//				),
//			);
//			$meta_fields['options']  = array(
//				array(
//					'id'    => 'poll_meta_options',
//					'title' => esc_html__( 'Options', 'wp-poll' ),
//				),
//			);
//			$meta_fields['settings'] = array(
//				array(
//					'id'      => 'poll_type',
//					'title'   => esc_html__( 'Poll Type', 'wp-poll' ),
//					'details' => esc_html__( 'Select type for this poll. Default: General Poll', 'wp-poll' ),
//					'type'    => 'radio',
//					'args'    => wpp()->get_poll_types(),
//					'default' => array( 'poll' ),
//				),
//				array(
//					'id'            => 'poll_deadline',
//					'title'         => esc_html__( 'Deadline', 'wp-poll' ),
//					'details'       => esc_html__( 'Specify a date when this poll will end. Leave empty to ignore this option', 'wp-poll' ),
//					'type'          => 'datepicker',
//					'autocomplete'  => 'off',
//					'placeholder'   => date( 'Y-m-d' ),
//					'field_options' => array(
//						'dateFormat' => 'yy-mm-dd',
//					),
//				),
//				array(
//					'id'    => 'poll_allow_disallow',
//					'title' => esc_html__( 'Settings', 'wp-poll' ),
//					'type'  => 'checkbox',
//					'args'  => apply_filters( 'wpp_filters_allow_disallow_options', array(
//						'vote_after_deadline' => esc_html__( 'Allow users to vote after poll meets deadline', 'wp-poll' ),
//						'multiple_votes'      => esc_html__( 'Allow Multiple votes', 'wp-poll' ),
//						'new_options'         => esc_html__( 'Allow Visitors to add new options', 'wp-poll' ),
//						'hide_timer'          => esc_html__( 'Hide countdown timer for this poll', 'wp-poll' ),
//					) ),
//				),
//			);
//			$meta_fields['designs']  = array(
//				array(
//					'id'      => 'poll_style_countdown',
//					'title'   => esc_html__( 'Timer Styles', 'wp-poll' ),
//					'details' => esc_html__( 'Countdown timer style | Default: 1', 'wp-poll' ),
//					'type'    => 'image_select',
//					'class'   => 'count-down-selection',
//					'args'    => array(
//						'1' => sprintf( '%sassets/images/timer/style-1.jpg', WPP_PLUGIN_URL ),
//						'2' => sprintf( '%sassets/images/timer/style-2.jpg', WPP_PLUGIN_URL ),
//						'3' => sprintf( '%sassets/images/timer/style-3.jpg', WPP_PLUGIN_URL ),
//						'4' => sprintf( '%sassets/images/timer/style-4.jpg', WPP_PLUGIN_URL ),
//						'5' => sprintf( '%sassets/images/timer/style-5.jpg', WPP_PLUGIN_URL ),
//					),
//					'default' => array( '1' ),
//				),
//				array(
//					'id'      => 'poll_options_theme',
//					'title'   => esc_html__( 'Options Styles', 'wp-poll' ),
//					'details' => esc_html__( 'Options theme style | Default: 1', 'wp-poll' ),
//					'type'    => 'image_select',
//					'args'    => array(
//						'1'  => sprintf( '%sassets/images/themes/theme-1.jpg', WPP_PLUGIN_URL ),
//						'2'  => sprintf( '%sassets/images/themes/theme-2.jpg', WPP_PLUGIN_URL ),
//						'3'  => sprintf( '%sassets/images/themes/theme-3.jpg', WPP_PLUGIN_URL ),
//						'4'  => sprintf( '%sassets/images/themes/theme-4.jpg', WPP_PLUGIN_URL ),
//						'5'  => sprintf( '%sassets/images/themes/theme-5.jpg', WPP_PLUGIN_URL ),
//						'6'  => sprintf( '%sassets/images/themes/theme-6.jpg', WPP_PLUGIN_URL ),
//						'7'  => sprintf( '%sassets/images/themes/theme-7.jpg', WPP_PLUGIN_URL ),
//						'8'  => sprintf( '%sassets/images/themes/theme-8.jpg', WPP_PLUGIN_URL ),
//						'9'  => sprintf( '%sassets/images/themes/theme-9.jpg', WPP_PLUGIN_URL ),
//						'10' => sprintf( '%sassets/images/themes/theme-10.jpg', WPP_PLUGIN_URL ),
//						'11' => sprintf( '%sassets/images/themes/theme-11.jpg', WPP_PLUGIN_URL ),
//					),
//					'default' => array( '1' ),
//				),
//				array(
//					'id'      => 'poll_animation_checkbox',
//					'details' => esc_html__( 'Animations for Multiple selections (Input type - Checkbox) | Default: Checkmark', 'wp-poll' ),
//					'type'    => 'image_select',
//					'args'    => array(
//						'cross'     => sprintf( '%sassets/images/animations/cross.jpg', WPP_PLUGIN_URL ),
//						'boxfill'   => sprintf( '%sassets/images/animations/boxfill.jpg', WPP_PLUGIN_URL ),
//						'checkmark' => sprintf( '%sassets/images/animations/checkmark.jpg', WPP_PLUGIN_URL ),
//						'diagonal'  => sprintf( '%sassets/images/animations/diagonal.jpg', WPP_PLUGIN_URL ),
//					),
//					'default' => array( 'checkmark' ),
//				),
//				array(
//					'id'      => 'poll_animation_radio',
//					'details' => esc_html__( 'Animations for Single selection (Input type - Radio) | Default: Fill', 'wp-poll' ),
//					'type'    => 'image_select',
//					'args'    => array(
//						'fill'   => sprintf( '%sassets/images/animations/fill.jpg', WPP_PLUGIN_URL ),
//						'circle' => sprintf( '%sassets/images/animations/circle.jpg', WPP_PLUGIN_URL ),
//						'swirl'  => sprintf( '%sassets/images/animations/swirl.jpg', WPP_PLUGIN_URL ),
//					),
//					'default' => array( 'fill' ),
//				),
//			);
//
//			if ( isset( $meta_fields[ $fields_for ] ) && is_array( $meta_fields[ $fields_for ] ) ) {
//				return apply_filters( 'wpp_filters_poll_meta_fields_' . $fields_for, $meta_fields[ $fields_for ] );
//			}

			$meta_fields = array(

				array(
					'id'      => 'poll_type',
					'title'   => esc_html__( 'Poll Type', 'wp-poll' ),
					'details' => esc_html__( 'Select type for this poll. Default: General Poll', 'wp-poll' ),
					'type'    => 'select',
					'args'    => wpp()->get_poll_types(),
				),

				array(
					'id'    => 'poll_meta_options',
					'title' => esc_html__( 'Options', 'wp-poll' ),
				),

				array(
					'id'    => '_thumbnail_id',
					'title' => esc_html__( 'Featured Image', 'wp-poll' ),
					'type'  => 'media',
				),

				array(
					'id'            => 'content',
					'title'         => esc_html__( 'Poll Content', 'wp-poll' ),
					'details'       => esc_html__( 'Write some details about this poll', 'wp-poll' ),
					'type'          => 'wp_editor',
					'field_options' => array(
						'media_buttons'    => false,
						'editor_height'    => '120px',
						'drag_drop_upload' => true,
					),
				),

				array(
					'id'            => 'poll_deadline',
					'title'         => esc_html__( 'Deadline', 'wp-poll' ),
					'details'       => esc_html__( 'Specify a date when this poll will end. Leave empty to ignore this option', 'wp-poll' ),
					'type'          => 'datepicker',
					'autocomplete'  => 'off',
					'placeholder'   => date( 'Y-m-d' ),
					'field_options' => array(
						'dateFormat' => 'yy-mm-dd',
					),
				),

				array(
					'id'    => 'poll_allow_disallow',
					'title' => esc_html__( 'Settings', 'wp-poll' ),
					'type'  => 'checkbox',
					'args'  => apply_filters( 'wpp_filters_allow_disallow_options', array(
						'vote_after_deadline' => esc_html__( 'Allow users to vote after poll meets deadline', 'wp-poll' ),
						'multiple_votes'      => esc_html__( 'Allow Multiple votes', 'wp-poll' ),
						'new_options'         => esc_html__( 'Allow Visitors to add new options', 'wp-poll' ),
						'hide_timer'          => esc_html__( 'Hide countdown timer for this poll', 'wp-poll' ),
					) ),
				),

				array(
					'id'      => 'poll_style_countdown',
					'title'   => esc_html__( 'Timer Styles', 'wp-poll' ),
					'details' => esc_html__( 'Countdown timer style | Default: 1', 'wp-poll' ),
					'type'    => 'image_select',
					'class'   => 'count-down-selection',
					'args'    => array(
						'1' => sprintf( '%sassets/images/timer/style-1.jpg', WPP_PLUGIN_URL ),
						'2' => sprintf( '%sassets/images/timer/style-2.jpg', WPP_PLUGIN_URL ),
						'3' => sprintf( '%sassets/images/timer/style-3.jpg', WPP_PLUGIN_URL ),
						'4' => sprintf( '%sassets/images/timer/style-4.jpg', WPP_PLUGIN_URL ),
						'5' => sprintf( '%sassets/images/timer/style-5.jpg', WPP_PLUGIN_URL ),
					),
					'default' => array( '1' ),
				),

				array(
					'id'      => 'poll_options_theme',
					'title'   => esc_html__( 'Options Styles', 'wp-poll' ),
					'details' => esc_html__( 'Options theme style | Default: 1', 'wp-poll' ),
					'type'    => 'image_select',
					'args'    => array(
						'1'  => sprintf( '%sassets/images/themes/theme-1.jpg', WPP_PLUGIN_URL ),
						'2'  => sprintf( '%sassets/images/themes/theme-2.jpg', WPP_PLUGIN_URL ),
						'3'  => sprintf( '%sassets/images/themes/theme-3.jpg', WPP_PLUGIN_URL ),
						'4'  => sprintf( '%sassets/images/themes/theme-4.jpg', WPP_PLUGIN_URL ),
						'5'  => sprintf( '%sassets/images/themes/theme-5.jpg', WPP_PLUGIN_URL ),
						'6'  => sprintf( '%sassets/images/themes/theme-6.jpg', WPP_PLUGIN_URL ),
						'7'  => sprintf( '%sassets/images/themes/theme-7.jpg', WPP_PLUGIN_URL ),
						'8'  => sprintf( '%sassets/images/themes/theme-8.jpg', WPP_PLUGIN_URL ),
						'9'  => sprintf( '%sassets/images/themes/theme-9.jpg', WPP_PLUGIN_URL ),
						'10' => sprintf( '%sassets/images/themes/theme-10.jpg', WPP_PLUGIN_URL ),
						'11' => sprintf( '%sassets/images/themes/theme-11.jpg', WPP_PLUGIN_URL ),
					),
					'default' => array( '1' ),
				),

				array(
					'id'      => 'poll_animation_checkbox',
					'details' => esc_html__( 'Animations for Multiple selections (Input type - Checkbox) | Default: Checkmark', 'wp-poll' ),
					'type'    => 'image_select',
					'args'    => array(
						'cross'     => sprintf( '%sassets/images/animations/cross.jpg', WPP_PLUGIN_URL ),
						'boxfill'   => sprintf( '%sassets/images/animations/boxfill.jpg', WPP_PLUGIN_URL ),
						'checkmark' => sprintf( '%sassets/images/animations/checkmark.jpg', WPP_PLUGIN_URL ),
						'diagonal'  => sprintf( '%sassets/images/animations/diagonal.jpg', WPP_PLUGIN_URL ),
					),
					'default' => array( 'checkmark' ),
				),

				array(
					'id'      => 'poll_animation_radio',
					'details' => esc_html__( 'Animations for Single selection (Input type - Radio) | Default: Fill', 'wp-poll' ),
					'type'    => 'image_select',
					'args'    => array(
						'fill'   => sprintf( '%sassets/images/animations/fill.jpg', WPP_PLUGIN_URL ),
						'circle' => sprintf( '%sassets/images/animations/circle.jpg', WPP_PLUGIN_URL ),
						'swirl'  => sprintf( '%sassets/images/animations/swirl.jpg', WPP_PLUGIN_URL ),
					),
					'default' => array( 'fill' ),
				),
			);

			return $meta_fields;
		}


		/**
		 * Return plugin settings fields
		 *
		 * @return mixed|void
		 */
		function get_plugin_settings() {

			$pages['wpp-options'] = array(
				'page_nav'      => esc_html__( 'Options', 'wp-poll' ),
				'page_settings' => apply_filters( 'wpp_filters_settings_page_options', array(
					array(
						'title'   => esc_html__( 'General Settings', 'wp-poll' ),
						'options' => array(
							array(
								'id'          => 'wpp_btn_text_new_option',
								'title'       => esc_html__( 'Buttons Text', 'wp-poll' ),
								'details'     => esc_html__( 'New option button', 'wp-poll' ),
								'placeholder' => esc_html__( 'New Option', 'wp-poll' ),
								'type'        => 'text',
							),
							array(
								'id'          => 'wpp_btn_text_submit',
								'details'     => esc_html__( 'Submit button', 'wp-poll' ),
								'placeholder' => esc_html__( 'Submit now', 'wp-poll' ),
								'type'        => 'text',
							),
							array(
								'id'          => 'wpp_btn_text_results',
								'details'     => esc_html__( 'Results button', 'wp-poll' ),
								'placeholder' => esc_html__( 'Results', 'wp-poll' ),
								'type'        => 'text',
							),
						)
					),
					array(
						'title'   => esc_html__( 'Poll Archive', 'wp-poll' ),
						'options' => array(
							array(
								'id'      => 'wpp_page_archive',
								'title'   => esc_html__( 'Archive Page', 'wp-poll' ),
								'details' => esc_html__( 'Select a poll archive page', 'wp-poll' ),
								'type'    => 'select',
								'args'    => 'PAGES',
							),
							array(
								'id'          => 'wpp_archive_items_per_page',
								'title'       => esc_html__( 'Items per page', 'wp-poll' ),
								'details'     => esc_html__( 'How many poll do you want to show per page | Default: 10', 'wp-poll' ),
								'placeholder' => esc_html__( '10', 'wp-poll' ),
								'type'        => 'number',
							),
							array(
								'id'      => 'wpp_archive_show_hide',
								'title'   => esc_html__( 'Show / Hide', 'wp-poll' ),
								'details' => esc_html__( 'Choose what you want to display on archive page.', 'wp-poll' ),
								'type'    => 'checkbox',
								'args'    => array(
									'thumb'        => esc_html__( 'Display poll thumbnail', 'wp-poll' ),
									'results'      => esc_html__( 'Display poll results', 'wp-poll' ),
									'pagination'   => esc_html__( 'Display pagination', 'wp-poll' ),
									'page-content' => esc_html__( 'Display archive page content', 'wp-poll' ),
								),
								'default' => array( 'pagination' ),
							),
						)
					),
				) ),
			);
			$pages['wpp-pro']     = array(
				'page_nav'      => esc_html__( 'Pro Options', 'wp-poll-pro' ),
				'page_settings' => apply_filters( 'wpp_filters_settings_page_pro', array(
					array(
						'title'   => esc_html__( 'Premium Settings', 'wp-poll-pro' ),
						'options' => array(
							array(
								'id'       => 'wpp_disabled_options',
								'title'    => esc_html__( 'Options to Disable', 'wp-poll-pro' ),
								'type'     => 'checkbox',
								'args'     => array(
									'social_share' => esc_html__( 'Disable social share on single poll page', 'wp-poll-pro' ),
								),
								'disabled' => ! wpp()->is_pro(),
							),
							array(
								'id'       => 'wppp_popup_poll',
								'title'    => esc_html__( 'Popup Poll on Scroll', 'wp-poll-pro' ),
								'details'  => esc_html__( 'Set a poll that will popup when users / visitors scroll and come to end on your website. Leave empty to disable this option.', 'wp-poll-pro' ),
								'type'     => 'select2',
								'multiple' => false,
								'args'     => 'POSTS_%poll%',
								'disabled' => ! wpp()->is_pro(),
							),
						),
					),
					array(
						'title'       => esc_html__( 'SMS Settings - Twilio', 'wp-poll-pro' ),
						'description' => sprintf( '%s <a href="%s">%s</a>',
							esc_html__( 'You need to sign up on Twilio and get necessary information below to make the SMS functions enable.', 'wp-poll-pro' ),
							esc_url( 'www.twilio.com/referral/0ZT7C7' ),
							esc_html__( 'Click here to Start now', 'wp-poll-pro' )
						),
						'options'     => array(
							array(
								'id'          => '_twilio_sid',
								'title'       => esc_html__( 'Account SID', 'wp-poll-pro' ),
								'details'     => sprintf( '<a href="%s">%s</a>', esc_url( 'www.twilio.com/referral/0ZT7C7' ), esc_html__( 'Login to get this information', 'wp-poll-pro' ) ),
								'type'        => 'text',
								'placeholder' => 'AC67jfk762a8a84f0fjwtd779c57572eb8',
								'disabled'    => ! wpp()->is_pro(),
							),
							array(
								'id'          => '_twilio_token',
								'title'       => esc_html__( 'Auth Token', 'wp-poll-pro' ),
								'details'     => sprintf( '<a href="%s">%s</a>', esc_url( 'www.twilio.com/referral/0ZT7C7' ), esc_html__( 'Login to get this information', 'wp-poll-pro' ) ),
								'type'        => 'text',
								'placeholder' => 'cd1232661c62b3f95190kod16739b6e1',
								'disabled'    => ! wpp()->is_pro(),
							),
							array(
								'id'          => '_twilio_sms_from',
								'title'       => esc_html__( 'From Phone', 'wp-poll-pro' ),
								'details'     => esc_html__( 'Set a number from where the SMS will be sent', 'wp-poll-pro' ),
								'type'        => 'text',
								'placeholder' => '+12015883105',
								'disabled'    => ! wpp()->is_pro(),
							),
						),
					),
				) ),
			);
			$pages['wpp-reports'] = array(
				'page_nav'      => esc_html__( 'Poll Reports', 'wp-poll' ),
				'show_submit'   => false,
				'page_settings' => apply_filters( 'wpp_filters_settings_page_reports', array(
					array(
						'title'       => esc_html__( 'Poll Reports', 'wp-poll' ),
						'description' => esc_html__( 'View reports for specific poll item', 'wp-poll' ),
						'options'     => array(
							array(
								'id'       => 'wpp_reports_poll_id',
								'title'    => esc_html__( 'Select Poll', 'wp-poll' ),
								'details'  => esc_html__( 'Select a poll you want to see report. Reports will generate automatically', 'wp-poll' ),
								'type'     => 'select',
								'value'    => isset( $_GET['poll-id'] ) ? sanitize_text_field( $_GET['poll-id'] ) : '',
								'args'     => 'POSTS_%poll%',
								'required' => true,
							),
							array(
								'id'    => 'wpp_reports_style',
								'title' => esc_html__( 'Report Style', 'wp-poll' ),
								'type'  => 'select',
								'value' => isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : 'pie',
								'args'  => array(
									'pie' => esc_html__( 'Pie Charts', 'wp-poll' ),
									'bar' => esc_html__( 'Bar Charts', 'wp-poll' ),
								),
							),

							array(
								'id' => 'wpp_export_button',
							),

						),
					),
				) ),
			);
			$pages['wpp-support'] = array(
				'page_nav'      => esc_html__( 'Support', 'wp-poll' ),
				'show_submit'   => false,
				'page_settings' => apply_filters( 'wpp_filters_settings_page_support', array(

					'sec_options' => array(
						'title'   => esc_html__( 'Support from Pluginbazar', 'wp-poll' ),
						'options' => array(

							array(
								'id'      => 'wpp_chat',
								'title'   => esc_html__( 'Live Chat', 'wp-poll' ),
								'details' => sprintf( '<a style="text-decoration: none;" href="%s" target="_blank"><span class="dashicons dashicons-format-chat"></span></a>', esc_url( 'https://m.me/pluginbazar' ) ),
							),
							array(
								'id'      => 'wpp_ticket',
								'title'   => esc_html__( 'Support Ticket', 'wp-poll' ),
								'details' => sprintf( '<a href="%s" target="_blank">%s</a>', PB_TICKET_URL, esc_html__( 'Create Ticket', 'wp-poll' ) ),
							),
							array(
								'id'      => 'wpp_review',
								'title'   => esc_html__( 'Like this Plugin?', 'wp-poll' ),
								'details' => sprintf( '<p style="color: #ff9800;"><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></p><p>%s</p><p>%s</p><p><a href="%s" target="_blank">%s</a></p>',
									esc_html__( 'Do you like this plugin and our hard works into it?', 'wp-poll' ),
									esc_html__( 'Simply rate it and let the worlds know about it. We dont know any language to Thank you', 'wp-poll' ),
									WPP_REVIEW_URL,
									esc_html__( 'Rate Now', 'wp-poll' )
								),
							),

						)
					),
				) ),
			);

			return apply_filters( 'wpp_filters_settings_pages', $pages );
		}


		/**
		 * Check if this plugin is pro version or not
		 *
		 * @return bool
		 */
		function is_pro() {
			return apply_filters( 'wpp_filters_is_pro', class_exists( 'WP_Poll_pro' ) );
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

global $wpp;

$wpp = new WPP_Functions();