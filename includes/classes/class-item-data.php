<?php
/**
 * Class Item Data
 *
 * @author Liquidpoll
 * @package includes/classes/class-item-data
 */

use WPDK\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if ( ! class_exists( 'LIQUIDPOLL_Item_data' ) ) {
	/**
	 * Class LIQUIDPOLLS_Hooks
	 */
	class LIQUIDPOLL_Item_data {


		/**
		 * Item ID
		 *
		 * @var null
		 */
		public $item_id = null;


		/**
		 * Item Post
		 *
		 * @var null
		 */
		public $item_post = null;


		/**
		 * Base theme
		 *
		 * @var null
		 */
		public $theme = null;


		/**
		 * Container classes
		 *
		 * @var null
		 */
		public $class = null;

		/**
		 * timer_type
		 *
		 * @var null
		 */
		public $timer_type = null;


		/**
		 * LIQUIDPOLLS_Poll constructor.
		 *
		 * @param int $item_id
		 * @param array $args
		 */
		function __construct( $item_id = 0, $args = array() ) {

			$this->init( $item_id, $args );
		}


		/**
		 * Return poll options as array
		 *
		 * @return mixed|void
		 */
		function get_poll_options() {

			$_poll_options = array();

			if ( 'nps' == $this->get_type() ) {
				$poll_options = $this->get_meta( 'poll_meta_options_nps', array() );
			} else if ( 'reaction' == $this->get_type() ) {
				$poll_options = $this->get_meta( 'poll_meta_options_reaction', array() );
			} else {
				$poll_options = $this->get_meta( 'poll_meta_options', array() );
			}

			if ( 'reaction' == $this->get_type() ) {
				foreach ( $poll_options as $option_key ) {
					$_poll_options[ $option_key ] = array(
						'label' => ucfirst( $option_key ),
						'thumb' => liquidpoll()->metaboxes->get_reaction_emoji_url( $option_key ),
					);
				}
			} else {
				foreach ( $poll_options as $option_key => $option ) {
					$_poll_options[ $option_key ] = array(
						'label'                 => Utils::get_args_option( 'label', $option ),
						'thumb'                 => Utils::get_args_option( 'url', Utils::get_args_option( 'thumb', $option, array() ) ),
						'fcrm_tags'             => Utils::get_args_option( 'fcrm_tags', $option ),
						'fcrm_nps_tags'         => Utils::get_args_option( 'fcrm_nps_tags', $option ),
						'groundhogg_tags'       => Utils::get_args_option( 'groundhogg_tags', $option ),
						'groundhogg_nps_tags'   => Utils::get_args_option( 'groundhogg_nps_tags', $option ),
						'funnelkit_tags'        => Utils::get_args_option( 'funnelkit_tags', $option ),
						'funnelkit_nps_tags'    => Utils::get_args_option( 'funnelkit_nps_tags', $option ),
						'mailerlite_groups'     => Utils::get_args_option( 'mailerlite_groups', $option ),
						'mailerlite_nps_groups' => Utils::get_args_option( 'mailerlite_nps_groups', $option ),
					);
				}
			}

			return apply_filters( 'liquidpoll_filters_poll_options', $_poll_options );
		}


		/**
		 * Return Poll type
		 *
		 * @return string
		 */
		function get_type() {

			global $liquidpoll_inside_elementor;

			$type = $this->get_meta( '_type', 'poll' );

			if ( $liquidpoll_inside_elementor ) {
				$type = liquidpoll()->get_widget_arg_val( '_type', $type );
			}

			return apply_filters( 'liquidpoll_filters_poll_type', $type, $this->get_id() );
		}


		/**
		 * Return Poll published date
		 *
		 * @param string $format
		 *
		 * @return mixed|void
		 */
		function get_published_date( $format = 'U' ) {

			return apply_filters( 'liquidpoll_filters_published_date', get_the_date( $format, $this->get_id() ) );
		}


		/**
		 * Return Poll author information upon given value
		 *
		 * @param string $info
		 *
		 * @return mixed|string
		 */
		function get_author_info( $info = 'display_name' ) {

			$poll_author = $this->get_author();

			if ( ! $poll_author || empty( $poll_author ) ) {
				return '';
			}

			if ( isset( $poll_author->$info ) ) {
				return $poll_author->$info;
			}

			return '';
		}


		/**
		 * Return Poll post author object
		 *
		 * @return bool|WP_User
		 */
		function get_author() {

			$poll_author_id = isset( $this->item_post->post_author ) ? $this->item_post->post_author : '';

			if ( ! empty( $poll_author_id ) ) {
				return get_user_by( 'ID', $poll_author_id );
			}

			return false;
		}


		/**
		 * Return poll permalink
		 *
		 * @return mixed|void
		 */
		function get_permalink() {

			return apply_filters( 'liquidpoll_filter_permalink', get_the_permalink( $this->get_id() ) );
		}


		/**
		 * Return whether a poll has a thumbnail or not
		 *
		 * @return bool
		 */
		function has_thumbnail() {

			if ( empty( $this->get_thumbnail() ) ) {
				return false;
			} else {
				return true;
			}
		}


		/**
		 * Return Post thumbnail URL
		 *
		 * @param string $size
		 *
		 * @return mixed|void
		 */
		function get_thumbnail( $size = 'full' ) {

			$thumbnail_id = $this->get_meta( '_thumbnail_id' );
			$_thumb_url   = ! empty( $thumbnail_id ) ? wp_get_attachment_image_src( $thumbnail_id, $size ) : array();
			$_thumb_url   = ! empty( $_thumb_url ) ? $_thumb_url : array();
			$thumb_url    = reset( $_thumb_url );

			return apply_filters( 'liquidpoll_filters_thumbnail', $thumb_url, $thumbnail_id, $size );
		}


		/**
		 * Return theme for this poll
		 *
		 * @return mixed|void|null
		 */
		function get_theme() {
			return $this->theme;
		}


		/**
		 * @param $theme
		 *
		 * @return array|int[]
		 */
		function get_theme_args( $theme ) {
			$theme_args = array();

			switch ( $theme ) {
				case '1':
				case '2':
				case '3':
				case '5':
					$theme_args = array(
						'width'  => 75,
						'height' => 45,
					);
					break;
				case '4':
					$theme_args = array(
						'width'  => 125,
						'height' => 75,
					);
					break;
				case '6':
				case '7':
					$theme_args = array(
						'width'  => 267,
						'height' => 258,
					);
					break;
				case '8':
					$theme_args = array(
						'width'  => 555,
						'height' => 180,
					);
					break;
				case '9':
					$theme_args = array(
						'width'  => 555,
						'height' => 180,
					);
					break;
				default:
					$theme_args = array();
			}

			return $theme_args;
		}


		/**
		 * Return CSS Arguments
		 *
		 * @param string $key
		 *
		 * @return array
		 */
		function get_css_args( $key = '' ) {

			if ( empty( $key ) ) {
				return array();
			}

			$css_args_mixed  = array();
			$css_args_option = Utils::get_option( $key, array() );
			$css_args_option = ! is_array( $css_args_option ) ? array() : $css_args_option;
			$css_args_meta   = $this->get_meta( $key, array() );
			$css_args_meta   = ! is_array( $css_args_meta ) ? array() : $css_args_meta;

			foreach ( $css_args_meta as $key => $value ) {
				$css_args_mixed[ $key ] = empty( $value ) ? ( isset( $css_args_option[ $key ] ) ? $css_args_option[ $key ] : '' ) : $value;
			}

			return $css_args_mixed;
		}


		/**
		 * Return Meta Value
		 *
		 * @param string $meta_key
		 * @param string $default
		 *
		 * @return mixed|void
		 */
		function get_meta( $meta_key = '', $default = '' ) {

			global $liquidpoll_inside_elementor;

			$meta_value = Utils::get_meta( $meta_key, $this->get_id(), $default );

			if ( $liquidpoll_inside_elementor ) {
				$meta_value = liquidpoll()->get_widget_arg_val( $meta_key, $meta_value );
			}

			return apply_filters( 'liquidpoll_filters_get_meta', $meta_value, $meta_key, $this );
		}


		/**
		 * Update meta value
		 *
		 * @param string $meta_key
		 * @param string $meta_value
		 *
		 * @return bool|int
		 */
		function update_meta( $meta_key = '', $meta_value = '' ) {

			do_action( 'liquidpoll_before_update_item_meta', $meta_key, $meta_value, $this );

			$ret = update_post_meta( $this->get_id(), $meta_key, $meta_value );

			do_action( 'liquidpoll_after_update_item_meta', $meta_key, $meta_value, $this );

			return $ret;
		}


		/**
		 * Return content
		 *
		 * @param bool $length
		 * @param null $more
		 *
		 * @return mixed|void
		 */
		function get_content( $length = false, $more = null ) {

			global $liquidpoll_inside_elementor;

			$content = $this->get_meta( '_content' );

			if ( $liquidpoll_inside_elementor && ! empty( $el_content = liquidpoll()->get_widget_arg_val( 'poll_content' ) ) ) {
				$content = $el_content;
			}

			if ( $length ) {
				$content = wp_trim_words( $content, $length, $more );
			}

			return apply_filters( 'liquidpoll_filters_poll_content', $content );
		}


		/**
		 * Return if an item has name or not
		 *
		 * @return bool
		 */
		function has_name() {

			if ( empty( $this->get_name() ) ) {
				return false;
			}

			return true;
		}


		/**
		 * Return if an item has content or not
		 *
		 * @return bool
		 */
		function has_content() {

			if ( empty( $this->get_content() ) ) {
				return false;
			}

			return true;
		}


		/**
		 * Return title
		 *
		 * @return mixed|void
		 */
		function get_name() {
			return apply_filters( 'liquidpoll_filters_item_name', $this->item_post->post_title );
		}


		/**
		 * Return ID
		 *
		 * @return bool|null
		 */
		function get_id() {
			return $this->item_id;
		}


		/**
		 * Return post object
		 *
		 * @return null|WP_Post
		 */
		function get_post() {
			return $this->item_post;
		}


		/**
		 * Initialize item
		 *
		 * @param $item_id
		 * @param $args
		 */
		function init( $item_id, $args = array() ) {

			$this->item_id   = ! $item_id ? get_the_ID() : $item_id;
			$this->item_post = get_post( $this->item_id );
			$this->class     = Utils::get_args_option( 'class', $args );

			if ( ! empty( $timer_left = Utils::get_args_option( 'timer_type', $args ) ) ) {
				$this->timer_type = $timer_left;
			}

			if ( isset( $args['theme'] ) && ! empty( $args['theme'] ) ) {
				$this->theme = $args['theme'];
			} else {
				switch ( $this->get_type() ) {
					case 'nps':
						$this->theme = $this->get_meta( '_theme_nps', 1 );
						break;

					case 'reaction':
						$this->theme = $this->get_meta( '_theme_reaction', 1 );
						break;

					case 'reviews':
						$this->theme = $this->get_meta( '_theme_reviews', 1 );
						break;

					default:
						$this->theme = $this->get_meta( '_theme', 1 );
						break;
				}
			}
		}
	}

	new LIQUIDPOLL_Item_data();
}