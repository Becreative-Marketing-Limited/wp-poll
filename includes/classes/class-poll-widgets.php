<?php
/**
 * Class LIQUIDPOLL_Widgets
 *
 * @author Liquidpoll
 * @package includes/classes/class-poll
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if ( ! class_exists( 'LIQUIDPOLL_Widgets' ) ) {
	class LIQUIDPOLL_Widgets extends WP_Widget {

		/**
		 * LIQUIDPOLL_Widgets constructor.
		 */
		function __construct() {
			parent::__construct( 'liquidpoll_widget_poll', esc_html__( 'WP Poll', 'wp-poll' ), array( 'description' => esc_html__( 'Display single poll', 'wp-poll' ), ) );
		}


		function widget( $args, $instance ) {

			$widget_title = apply_filters( 'widget_title', $instance['title'] );
			$poll_id      = isset( $instance['poll_id'] ) ? $instance['poll_id'] : '';

			/**
			 * Before Widget
			 */
			echo wp_kses_post( $args['before_widget'] );


			/**
			 * Widget Title
			 */
			printf( '%s%s%s', $args['before_title'], $widget_title, $args['before_title'] );


			global $post;

			$post = get_post( $poll_id );
			setup_postdata( $post );
			liquidpoll_get_template( 'content-single-poll.php' );
			wp_reset_postdata();


			/**
			 * After Widget
			 */
			echo wp_kses_post( $args['after_widget'] );
		}


		/**
		 * Widget form fields
		 *
		 * @param array $instance
		 *
		 * @return string|void
		 * @throws PB_Error
		 */
		function form( $instance ) {

			$widget_title = isset( $instance['title'] ) ? $instance['title'] : '';
			$poll_id      = isset( $instance['poll_id'] ) ? $instance['poll_id'] : '';

			$fields = array(
				array(
					'options' => array(
						array(
							'id'      => $this->get_field_name( 'title' ),
							'title'   => esc_html__( 'Widget title', 'wp-poll' ),
							'details' => esc_html__( 'Write a title of this widget', 'wp-poll' ),
							'type'    => 'text',
							'value'   => $widget_title,
						),
						array(
							'id'      => $this->get_field_name( 'poll_id' ),
							'title'   => esc_html__( 'Select Poll', 'wp-poll' ),
							'details' => esc_html__( 'Select a poll you want to display', 'wp-poll' ),
							'type'    => 'select',
							'args'    => 'POSTS_%poll%',
							'value'   => $poll_id,
						),
					)
				)
			);

			liquidpoll()->PB_Settings()->generate_fields( $fields );
		}
	}
}