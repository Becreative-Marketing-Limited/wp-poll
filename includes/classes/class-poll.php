<?php
/**
 * Class Poll
 */


if ( ! class_exists( 'WPP_Poll' ) ) {
	/**
	 * Class WPP_Hooks
	 */
	class WPP_Poll {


		/**
		 * Poll ID
		 *
		 * @var null
		 */
		public $poll_id = null;


		/**
		 * Poll Post
		 *
		 * @var null
		 */
		public $poll_post = null;


		/**
		 * WPP_Poll constructor.
		 *
		 * @param bool $poll_id
		 */
		function __construct( $poll_id = false ) {

			$this->init( $poll_id );
		}


		/**
		 * Return Poll published date
		 *
		 * @param string $format
		 *
		 * @return mixed|void
		 */
		function get_published_date( $format = 'U' ) {

			return apply_filters( 'wpp_filters_poll_published_date', get_the_date( $format, $this->get_id() ) );
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

			$poll_author_id = isset( $this->poll_post->post_author ) ? $this->poll_post->post_author : '';

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

			return apply_filters( 'wpp_filter_poll_permalink', get_the_permalink( $this->get_id() ) );
		}


		/**
		 * Add new poll option
		 *
		 * @param string $option_label
		 * @param bool $from_frontend
		 *
		 * @return array|bool
		 */
		function add_poll_option( $option_label = '', $from_frontend = true ) {

			if ( empty( $option_label ) ) {
				return false;
			}

			$poll_options  = $this->get_meta( 'poll_meta_options', array() );
			$option_id     = hexdec( uniqid() );
			$option_to_add = array(
				'label'    => $option_label,
				'frontend' => true,
			);

			$poll_options[ $option_id ] = $option_to_add;

			if ( $this->update_meta( 'poll_meta_options', $poll_options ) ) {
				return array_merge( array( 'option_id' => $option_id ), $option_to_add );
			} else {
				return false;
			}
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

			return apply_filters( 'wpp_filters_poll_thumbnail', $thumb_url, $thumbnail_id, $size );
		}


		/**
		 * Return poll options as array
		 *
		 * @return mixed|void
		 */
		function get_poll_options() {

			$_poll_options = array();
			$poll_options  = $this->get_meta( 'poll_meta_options', array() );

			foreach ( $poll_options as $option_id => $option ) {

				$label     = isset( $option['label'] ) ? $option['label'] : '';
				$thumb_id  = isset( $option['thumb'] ) ? $option['thumb'] : '';
				$thumb_url = array();

				if ( ! empty( $thumb_id ) ) {
					$thumb_url = wp_get_attachment_image_src( $thumb_id );
				}

				$_poll_options[ $option_id ] = array(
					'label' => $label,
					'thumb' => reset( $thumb_url ),
				);
			}

			return apply_filters( 'wpp_filters_poll_options', $_poll_options );
		}


		/**
		 * Check whether users/visitors can vote multiple to a single poll or not
		 *
		 * @return bool
		 */
		function can_vote_multiple() {

			$can_multiple = $this->get_meta( 'poll_meta_multiple', 'no' );

			if ( $can_multiple == 'yes' ) {
				return true;
			} else {
				return false;
			}
		}


		/**
		 * Return style of some element of a Poll
		 *
		 * @param string $style_of
		 *
		 * @return mixed|void
		 */
		function get_style( $style_of = '' ) {

			$style = 1;

			if ( ! in_array( $style_of, array(
				'countdown',
				'options_theme',
				'animation_checkbox',
				'animation_radio'
			) ) ) {
				return apply_filters( 'wpp_filters_get_style', $style, $style_of );
			}

			if ( $style_of == 'countdown' ) {
				$style = $this->get_meta( 'poll_style_countdown', 1 );
			}

			if ( $style_of == 'options_theme' ) {
				$style = $this->get_meta( 'poll_options_theme', 1 );
			}

			if ( $style_of == 'animation_checkbox' ) {
				$style = $this->get_meta( 'poll_animation_checkbox', 'checkmark' );
			}

			if ( $style_of == 'animation_radio' ) {
				$style = $this->get_meta( 'poll_animation_radio', 'checkmark' );
			}

			return apply_filters( 'wpp_filters_get_style', $style, $style_of );
		}


		/**
		 * Return Poll Deadline
		 *
		 * @param string $format
		 *
		 * @return mixed|void
		 */
		function get_poll_deadline( $format = 'M j Y G:i:s' ) {

			$deadline = $this->get_meta( 'poll_deadline' );

			return apply_filters( 'wpp_filters_poll_deadline', date( $format, strtotime( $deadline ) ) );
		}


		/**
		 * Return bool whether visitors can add new option to a poll or not
		 *
		 * @return bool
		 */
		function visitors_can_add_option() {

			$is_new_option = $this->get_meta( 'poll_meta_new_option', 'no' );

			if ( $is_new_option == 'yes' ) {
				return true;
			} else {
				return false;
			}
		}


		/**
		 * Return Poll content
		 *
		 * @param bool $length
		 * @param null $more
		 *
		 * @return mixed|void
		 */
		function get_poll_content( $length = false, $more = null ) {

			$content = $this->get_poll_post()->post_content;

			if ( $length ) {
				$content = wp_trim_words( $content, $length, $more );
			}

			return apply_filters( 'wpp_filters_poll_content', $content );
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

			global $wpp;

			$meta_value = $wpp->get_meta( $meta_key, $this->get_id(), $default );

			return apply_filters( 'wpp_filters_get_poll_meta', $meta_value, $meta_key, $this );
		}


		/**
		 * Update Poll meta value
		 *
		 * @param string $meta_key
		 * @param string $meta_value
		 *
		 * @return bool|int
		 */
		function update_meta( $meta_key = '', $meta_value = '' ) {

			do_action( 'wpp_before_update_poll_meta', $meta_key, $meta_value, $this );

			$ret = update_post_meta( $this->get_id(), $meta_key, $meta_value );

			do_action( 'wpp_after_update_poll_meta', $meta_key, $meta_value, $this );

			return $ret;
		}


		/**
		 * Return Poll title
		 *
		 * @return mixed|void
		 */
		function get_name() {

			return apply_filters( 'wpp_filters_poll_name', \get_the_title( $this->get_id() ) );
		}


		/**
		 * Return poll ID
		 *
		 * @return bool|null
		 */
		function get_id() {
			return $this->poll_id;
		}


		/**
		 * Return Poll post object
		 *
		 * @return null|WP_Post
		 */
		function get_poll_post() {

			return $this->poll_post;
		}


		/**
		 * Initialize poll
		 *
		 * @param $poll_id
		 */
		function init( $poll_id ) {

			$this->poll_id   = ! $poll_id ? get_the_ID() : $poll_id;
			$this->poll_post = get_post( $this->poll_id );
		}
	}

	new WPP_Poll();
}