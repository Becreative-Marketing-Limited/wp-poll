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
			add_shortcode( 'poller_list', array( $this, 'display_poller_list' ) );
			add_shortcode( 'force_user_poll', array( $this, 'display_force_user_poll' ) );

			add_filter( 'the_content', array( $this, 'poll_archive_page_content' ), 99 );
		}


		/**
		 * Display Single Poll
		 *
		 * @param $atts
		 * @param null $content
		 *
		 * @return false|string
		 */
		public function display_force_user_poll( $atts, $content = null ) {

			$atts = shortcode_atts( array(
				'id'         => '',
				'popup_show' => ''
			), $atts );

			$poll_id = empty( $atts['id'] ) ? '' : $atts['id'];

			$popup_show = ! empty( $atts['popup_show'] ) && $atts['popup_show'] == 'scroll' ? 'wpp-show-popup-on-scroll' : 'wpp-show-popup-on-load';

			global $post;

			$post = get_post( $poll_id );
			setup_postdata( $post );

			ob_start();

			?>

            <div class="wpp-froce-user-poll-wrap wpp-popup-container <?php echo esc_attr( $popup_show ); ?>">
                <div class="wpp-popup-box">

					<?php if ( $atts['popup_show'] == 'scroll') : ?>
                        <span class="box-close"><i class="icofont-close"></i></span>
					<?php endif; ?>

					<?php wpp_get_template( 'content-single-poll.php' ); ?>

                </div>

            </div>


			<?php

			wp_reset_postdata();

			return ob_get_clean();
		}


		/**
		 * Update Poll archive page content
		 *
		 * @param $atts
		 * @param $content
		 *
		 * @return string
		 */
		public function display_poller_list( $atts = array(), $content = null ) {

			ob_start();

			if ( isset( $atts['option_id'] ) && ! empty( $atts['option_id'] ) ) {
				wpp_get_template( 'poller-list-single.php', $atts );
			} else {
				wpp_get_template( 'poller-list.php', $atts );
			}

			return ob_get_clean();
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

			$show_results    = wpp()->display_on_archive( 'results' ) ? 'yes' : 'no';
			$show_pagination = wpp()->display_on_archive( 'pagination' ) ? 'yes' : 'no';
			$shortcode       = sprintf( '[poll_list show_results="%s" show_pagination="%s"]', $show_results, $show_pagination );

			if ( wpp()->display_on_archive( 'page-content' ) ) {
				$content .= do_shortcode( $shortcode );
			} else {
				$content = do_shortcode( $shortcode );
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
				'show_results'    => 'no',
			);

			$args = apply_filters( 'wpp_filters_poll_archive_query', array_merge( $defaults, $atts ) );

			ob_start();

			wpp_get_template( 'archive-poll.php', $args );

			return ob_get_clean();
		}
	}

	new WPP_Shortcodes();
}