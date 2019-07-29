<?php
/**
 * Class: WPP_Data_update
 */


class WPP_Data_update {

	public $data_updated;

	function __construct() {

		$this->init();

		add_action( 'admin_notices', array( $this, 'show_data_update_notice' ) );
		add_action( 'admin_menu', array( $this, 'add_data_update_menu' ) );
	}


	function display_data_update() {

		$all_polls = get_posts( 'post_type=poll&posts_per_page=-1&fields=ids' );

		foreach ( $all_polls as $poll_id ) :

			$polled_data = get_post_meta( $poll_id, 'polled_data', true );
			$polled_data = empty( $polled_data ) ? array() : $polled_data;

			foreach ( $polled_data as $poller => $single_poll_data ) {

				$user_id = ( strpos( $poller, '.' ) !== false ) ? '' : $poller;
				$user_ip = ( strpos( $poller, '.' ) !== false ) ? $poller : '';

				foreach ( $single_poll_data as $option_id ) {
					$response = wpp_insert_poll_submission( $option_id, $poll_id, $user_id, $user_ip );
					if ( is_wp_error( $response ) ) {
						printf( '<div class="notice notice-error"><p><strong>%s: %s</strong> %s</p></div>',
							esc_html__( 'Poll ID', 'wp-poll' ),
							esc_html( $poll_id ),
							$response->get_error_message()
						);
					} else {
						printf( '<div class="notice notice-success"><p><strong>%s: %s</strong> %s</p></div>',
							esc_html__( 'Poll ID', 'wp-poll' ),
							esc_html( $poll_id ),
							esc_html__( 'Success', 'wp-poll' )
						);
					}
				}
			}

		endforeach;
	}

	function add_data_update_menu() {

		if ( $this->data_updated === 'yes' ) {
			return;
		}

		add_submenu_page( 'edit.php?post_type=poll', esc_html__( 'Data update', 'wp-poll' ), esc_html__( 'Data update', 'wp-poll' ),
			'manage_options', 'data-update', array( $this, 'display_data_update' ) );
	}

	function show_data_update_notice() {

		if ( $this->data_updated === 'yes' ) {
			return;
		}

		printf( '<div class="notice notice-warning is-dismissible"><p>%s<a href="%s">%s</a></p></div>',
			esc_html__( 'WP Poll needs your action to update data. ', 'wp-poll' ),
			esc_url( admin_url( 'edit.php?post_type=poll&page=data-update' ) ),
			esc_html__( 'Click here to update now', 'wp-poll' )
		);
	}

	function init() {

		$this->data_updated = wpp()->get_option( 'data_updated', 'no' );
	}
}

new WPP_Data_update();