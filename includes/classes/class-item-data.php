<?php
/**
 * Class Item Data
 *
 * @author Pluginbazar
 * @package includes/classes/class-item-data
 */

use Pluginbazar\Utils;

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


		/** Base theme
		 *
		 * @var null
		 */
		public $theme = null;


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
			return apply_filters( 'liquidpoll_filters_get_meta', Utils::get_meta( $meta_key, $this->get_id(), $default ), $meta_key, $this );
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

			$content = $this->get_meta( '_content' );

			if ( $length ) {
				$content = wp_trim_words( $content, $length, $more );
			}

			return apply_filters( 'liquidpoll_filters_poll_content', $content );
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
			$this->theme     = isset( $args['theme'] ) && ! empty( $args['theme'] ) ? $args['theme'] : $this->get_meta( '_theme', 1 );
		}
	}

	new LIQUIDPOLL_Item_data();
}