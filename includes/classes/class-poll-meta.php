<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class WPP_Poll_meta {

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_data' ) );
		add_action( 'pb_settings_poll_meta_options', array( $this, 'display_poll_options' ) );

		add_action( 'pb_settings_poll_style_countdown', array( $this, 'add_preview_html' ) );
		add_action( 'pb_settings_poll_options_theme', array( $this, 'add_preview_html' ) );
		add_action( 'pb_settings_poll_animation_checkbox', array( $this, 'add_preview_html' ) );
		add_action( 'pb_settings_poll_animation_radio', array( $this, 'add_preview_html' ) );
	}


	/**
	 * Add live preview link to the themes/animations
	 *
	 * @param $option
	 */
	function add_preview_html( $option ) {

		$option_id = isset( $option['id'] ) ? $option['id'] : '';

		if ( empty( $option_id ) ) {
			return;
		}

		$tt_text          = esc_html__( 'See live preview for this selection', 'wp-poll' );
		$option_value     = wpp()->get_meta( $option_id );
		$option_value     = is_array( $option_value ) ? reset( $option_value ) : $option_value;
		$data_demo_server = '//demo.pluginbazar.com/wp-poll/poll';

		if ( $option_id == 'poll_style_countdown' ) {
			$tt_text     = esc_html__( 'See live preview for this countdown style', 'wp-poll' );
			$data_target = 'countdown-timer-style';
		} else if ( $option_id == 'poll_options_theme' ) {
			$tt_text     = esc_html__( 'See live preview for this theme of options', 'wp-poll' );
			$data_target = 'poll-theme-variation';
		} else if ( $option_id == 'poll_animation_checkbox' ) {
			$tt_text     = esc_html__( 'See live preview for this checkbox animation', 'wp-poll' );
			$data_target = 'input-type-checkbox-animation-effect';
		} else if ( $option_id == 'poll_animation_radio' ) {
			$tt_text     = esc_html__( 'See live preview for this radio animation', 'wp-poll' );
			$data_target = 'input-type-radio-animation-effect';
		}

		printf( '<a data-demo-server="%s" data-target="%s" class="wpp-preview-link tt--hint tt--top" href="%s" aria-label="%s" target="_blank">%s</a>',
			$data_demo_server, $data_target,
			esc_url( sprintf( '%s/%s-%s', $data_demo_server, $data_target, $option_value ) ),
			$tt_text, esc_html__( 'Live Preview', 'wp-poll' )
		);
	}


	/**
	 * Display poll option with repeater field
	 */
	function display_poll_options() {

		global $post;

		ob_start();

		foreach ( wpp()->get_meta( 'poll_meta_options', false, array() ) as $unique_id => $args ) {
			wpp_add_poll_option( $unique_id, $args );
		}

		$poll_options = ob_get_clean();

		printf( '<div class="button wpp-add-poll-option" data-poll-id="%s">%s</div>', $post->ID, esc_html__( 'Add Option', 'wp-poll' ) );
		printf( '<ul class="poll-options">%s</ul>', $poll_options );
	}


	/**
	 * Save meta box
	 *
	 * @param $post_id
	 *
	 * @return mixed
	 */
	public function save_meta_data( $post_id ) {

		$nonce = isset( $_POST['poll_nonce_value'] ) ? $_POST['poll_nonce_value'] : '';

		if ( ! wp_verify_nonce( $nonce, 'poll_nonce' ) ) {
			return $post_id;
		}

		foreach ( $this->__get_meta_fields() as $field ) {

			$field_id = isset( $field['id'] ) ? $field['id'] : '';

			if ( in_array( $field_id, array( 'post_title', 'post_content' ) ) || empty( $field_id ) ) {
				continue;
			}

			$field_value = isset( $_POST[ $field_id ] ) ? stripslashes_deep( $_POST[ $field_id ] ) : '';

			update_post_meta( $post_id, $field_id, $field_value );
		}
	}


	/**
	 * Meta box output
	 *
	 * @param $post
	 *
	 * @throws PB_Error
	 */
	public function poll_meta_box_function( $post ) {

		wp_nonce_field( 'poll_nonce', 'poll_nonce_value' );

		wpp()->PB_Settings()->generate_fields( $this->get_meta_fields(), $post->ID );
	}


	/**
	 * Add meta boxes
	 *
	 * @param $post_type
	 */
	public function add_meta_boxes( $post_type ) {

		if ( in_array( $post_type, array( 'poll' ) ) ) {

			add_meta_box( 'poll_metabox', __( 'Poll data box', 'wp-poll' ), array(
				$this,
				'poll_meta_box_function'
			), $post_type, 'normal', 'high' );
		}
	}


	/**
	 * Return meta fields for direct use to PB_Settings
	 *
	 * @return mixed|void
	 */
	function get_meta_fields() {

		return apply_filters( 'wpp_filters_poll_meta_options_fields', array( array( 'options' => $this->__get_meta_fields() ) ) );
	}


	/**
	 * Return raw meta fields
	 *
	 * @return array
	 */
	private function __get_meta_fields() {

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
				'args'  => array(
					'vote_after_deadline' => esc_html__( 'Allow users to vote after poll meets deadline', 'wp-poll' ),
					'multiple_votes'      => esc_html__( 'Allow Multiple votes', 'wp-poll' ),
					'new_options'         => esc_html__( 'Allow Visitors to add new options', 'wp-poll' ),
					'hide_timer'          => esc_html__( 'Hide countdown timer for this poll ', 'wp-poll' ),
				),
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

		return apply_filters( 'wpp_filters_poll_meta_fields', $meta_fields );
	}
}

new WPP_Poll_meta();