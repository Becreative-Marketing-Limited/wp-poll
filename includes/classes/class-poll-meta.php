<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

class WPP_Post_meta_Poll {

	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_data' ) );
		add_action( 'pb_settings_poll_meta_options', array( $this, 'display_poll_options' ) );
	}


	/**
	 * Display poll option with repeater field
	 *
	 * @throws PB_Error
	 */
	function display_poll_options() {

		ob_start();

		foreach ( wpp()->get_meta( 'poll_meta_options', false, array() ) as $unique_id => $args ) {
			wpp_add_poll_option( $unique_id, $args );
		}

		$poll_options = ob_get_clean();

		printf( '<div class="button wpp-add-poll-option">%s</div>', esc_html__( 'Add Option', 'wp-poll' ) );
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

		return apply_filters( 'wpp_filters_poll_meta_fields', array( array( 'options' => $this->__get_meta_fields() ) ) );
	}


	/**
	 * Return raw meta fields
	 *
	 * @return array
	 */
	private function __get_meta_fields() {

		return array(

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
				'title'         => esc_html__( 'Settings', 'wp-poll' ),
				'details'       => esc_html__( 'Specify a date when this poll will end. Leave empty to ignore this option', 'wp-poll' ),
				'type'          => 'datepicker',
				'autocomplete'  => 'off',
				'placeholder'   => date( 'Y-m-d' ),
				'field_options' => array(
					'dateFormat' => 'yy-mm-dd',
				),
			),

			array(
				'id'            => 'poll_allow_disallow',
				'type'          => 'checkbox',
				'args' => array(
					'vote_after_deadline' => esc_html__( 'Allow users to vote after poll meets deadline', 'wp-poll' ),
					'multiple_votes' => esc_html__( 'Allow Multiple votes', 'wp-poll' ),
					'new_options' => esc_html__( 'Allow Visitors to add new options', 'wp-poll' ),
				),
			),

			array(
				'id'      => 'poll_style_countdown',
				'title'   => esc_html__( 'Timer Styles', 'wp-poll' ),
				'details' => esc_html__( 'Countdown timer style | Default: 1', 'wp-poll' ),
				'type'    => 'select',
				'args'    => array(
					'1' => esc_html__( 'Style - 1', 'wp-poll' ),
					'2' => esc_html__( 'Style - 2', 'wp-poll' ),
					'3' => esc_html__( 'Style - 3', 'wp-poll' ),
					'4' => esc_html__( 'Style - 4', 'wp-poll' ),
					'5' => esc_html__( 'Style - 5', 'wp-poll' ),
				),
				'default' => array( '1' ),
			),

			array(
				'id'      => 'poll_options_theme',
				'title'   => esc_html__( 'Options Styles', 'wp-poll' ),
				'details' => esc_html__( 'Options theme style | Default: 1', 'wp-poll' ),
				'type'    => 'select',
				'args'    => array(
					'1'  => esc_html__( 'Theme - 1', 'wp-poll' ),
					'2'  => esc_html__( 'Theme - 2', 'wp-poll' ),
					'3'  => esc_html__( 'Theme - 3', 'wp-poll' ),
					'4'  => esc_html__( 'Theme - 4', 'wp-poll' ),
					'5'  => esc_html__( 'Theme - 5', 'wp-poll' ),
					'6'  => esc_html__( 'Theme - 6', 'wp-poll' ),
					'7'  => esc_html__( 'Theme - 7', 'wp-poll' ),
					'8'  => esc_html__( 'Theme - 8', 'wp-poll' ),
					'9'  => esc_html__( 'Theme - 9', 'wp-poll' ),
					'10' => esc_html__( 'Theme - 10', 'wp-poll' ),
				),
				'default' => array( '1' ),
			),

			array(
				'id'      => 'poll_animation_checkbox',
				'details' => esc_html__( 'Animations for Multiple selections (Input type - Checkbox) | Default: Checkmark', 'wp-poll' ),
				'type'    => 'select',
				'args'    => array(
					'cross'     => esc_html__( 'Cross', 'wp-poll' ),
					'boxfill'   => esc_html__( 'Box Fill', 'wp-poll' ),
					'checkmark' => esc_html__( 'Checkmark', 'wp-poll' ),
					'diagonal'  => esc_html__( 'Diagonal', 'wp-poll' ),
				),
				'default' => array( 'checkmark' ),
			),

			array(
				'id'      => 'poll_animation_radio',
				'details' => esc_html__( 'Animations for Single selection (Input type - Radio) | Default: Fill', 'wp-poll' ),
				'type'    => 'select',
				'args'    => array(
					'fill'   => esc_html__( 'Fill', 'wp-poll' ),
					'circle' => esc_html__( 'Circle', 'wp-poll' ),
					'swirl'  => esc_html__( 'Swirl', 'wp-poll' ),
				),
				'default' => array( 'fill' ),
			),
		);
	}
}

new WPP_Post_meta_Poll();