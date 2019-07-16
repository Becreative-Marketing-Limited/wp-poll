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

			if( in_array( $field_id, array( 'post_title', 'post_content' ) ) || empty( $field_id ) ) {
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
				'id'      => 'post_title',
				'title'   => esc_html__( 'Poll title', 'wp-poll' ),
				'details' => esc_html__( 'Write a suitable title for this poll', 'wp-poll' ),
				'type'    => 'text',
			),

			array(
				'id'    => 'poll_meta_options',
				'title' => esc_html__( 'Options', 'wp-poll' ),
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
				'placeholder'   => date( 'Y-m-d' ),
				'field_options' => array(
					'dateFormat' => 'yy-mm-dd',
				),
			),

			array(
				'id'      => 'poll_meta_multiple',
				'title'   => esc_html__( 'Settings', 'wp-poll' ),
				'details' => esc_html__( 'Allow multiple vote | Default: No', 'wp-poll' ),
				'type'    => 'select',
				'args'    => array(
					'yes' => esc_html__( 'Yes', 'wp-poll' ),
					'no'  => esc_html__( 'No', 'wp-poll' ),
				),
				'default' => array( 'no' ),
			),

			array(
				'id'      => 'poll_meta_new_option',
				'details' => esc_html__( 'Allow Visitors to add New Option | Default: No', 'wp-poll' ),
				'type'    => 'select',
				'args'    => array(
					'yes' => esc_html__( 'Yes', 'wp-poll' ),
					'no'  => esc_html__( 'No', 'wp-poll' ),
				),
				'default' => array( 'no' ),
			),

			array(
				'id'      => 'poll_style_countdown',
				'title' => esc_html__( 'Styles', 'wp-poll' ),
				'details' => esc_html__( 'Select countdown timer style | Default: 1', 'wp-poll' ),
				'type'    => 'select',
				'args'    => array(
					'1' => esc_html__( 'Style - 1', 'wp-poll' ),
					'2'  => esc_html__( 'Style - 2', 'wp-poll' ),
					'3'  => esc_html__( 'Style - 3', 'wp-poll' ),
					'4'  => esc_html__( 'Style - 4', 'wp-poll' ),
					'5'  => esc_html__( 'Style - 5', 'wp-poll' ),
				),
				'default' => array( '1' ),
			),


		);
	}
}

new WPP_Post_meta_Poll();