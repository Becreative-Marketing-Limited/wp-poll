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

		foreach ( wpp()->get_poll_meta_fields() as $field ) {

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

			add_meta_box( 'poll_metabox', esc_html__('Poll data box', 'wp-poll' ), array(
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

		return apply_filters( 'wpp_filters_poll_meta_options_fields', array( array( 'options' => wpp()->get_poll_meta_fields() ) ) );
	}
}

new WPP_Poll_meta();