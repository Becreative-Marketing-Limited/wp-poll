<?php
/**
 * Class Shortcodes
 *
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if ( ! class_exists( 'WPP_Shortcodes' ) ) {
	class WPP_Shortcodes {


		/**
		 * WPP_Shortcodes constructor.
		 */
		public function __construct() {

			add_shortcode( 'poll_list', array( $this, 'display_poll_archive' ) );
			add_shortcode( 'poll', array( $this, 'display_single_poll' ) );

			add_filter( 'the_content', array( $this, 'poll_archive_page_content' ), 99 );
		}


		/**
		 * Update Poll archive page content
		 *
		 * @param $content
		 *
		 * @return string
		 */
		public function poll_archive_page_content( $content ) {

			if ( ! wpp_is_page( 'archive' ) ) {
				return $content;
			}

			if ( in_array( 'yes', wpp()->get_option( 'wpp_poll_page_content_show', 'no' ) ) ) {
				$content .= do_shortcode( '[poll_list]' );
			} else {
				$content = do_shortcode( '[poll_list]' );
			}

			return $content;
		}


		/**
		 * Display Single Poll
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return false|string
		 */
		public function display_single_poll( $atts, $content = null ) {

			$atts = shortcode_atts( array(
				'id' => ''
			), $atts );

			$poll_id = empty( $atts['id'] ) ? '' : $atts['id'];

			global $post;

			$post = get_post( $poll_id );
			setup_postdata( $post );

			ob_start();

			wpp_get_template( 'content-single-poll.php' );

			wp_reset_postdata();

			return ob_get_clean();
		}


		/**
		 * Display poll archive page
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return false|string
		 */
		public function display_poll_archive( $atts, $content = null ) {

			$atts = (array) $atts;
			$atts = array_filter( $atts );

			$defaults = array(
				'post_type'       => 'poll',
				'posts_per_page'  => wpp()->get_polls_per_page(),
				'post_status'     => ( ! empty( $atts['status'] ) ) ? $atts['status'] : 'publish',
				'paged'           => ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1,
				'show_sorting'    => 'yes',
				'show_count'      => 'yes',
				'show_pagination' => 'yes',
			);

			$args = apply_filters( 'wpp_filters_poll_archive_query', array_merge( $defaults, $atts ) );

			ob_start();

			wpp_get_template( 'archive-poll.php', $args );

			return ob_get_clean();
		}
	}

	new WPP_Shortcodes();
}