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

		return apply_filters( 'wpp_filters_poll_meta_options_fields', array( array( 'options' => wpp()->get_poll_meta_fields() ) ) );
	}
}

new WPP_Poll_meta();