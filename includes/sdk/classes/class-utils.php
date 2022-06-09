<?php
/**
 * Pluginbazar SDK - Utils class
 */

namespace Pluginbazar;

/**
 * Class Utils
 *
 * @package Pluginbazar
 */
class Utils {

	/**
	 * @var Client null
	 */
	private $client = null;


	/**
	 * Notifications constructor.
	 */
	function __construct( Client $client ) {

		$this->client = $client;
	}


	/**
	 * Register Shortcode
	 *
	 * @param string $shortcode
	 * @param string $callable_func
	 */
	function register_shortcode( $shortcode = '', $callable_func = '' ) {

		if ( empty( $shortcode ) || empty( $callable_func ) ) {
			return;
		}

		add_shortcode( $shortcode, $callable_func );
	}


	/**
	 * Register Taxonomy
	 *
	 * @param $tax_name
	 * @param $obj_type
	 * @param array $args
	 */
	function register_taxonomy( $tax_name, $obj_type, $args = array() ) {

		if ( taxonomy_exists( $tax_name ) ) {
			return;
		}

		$singular = Utils::get_args_option( 'singular', $args, '' );
		$plural   = Utils::get_args_option( 'plural', $args, '' );
		$labels   = Utils::get_args_option( 'labels', $args, array() );

		$args = wp_parse_args( $args,
			array(
				'description'         => sprintf( $this->client->__trans( 'This is where you can create and manage %s.' ), $plural ),
				'public'              => true,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'rewrite'             => true,
				'query_var'           => true,
				'show_in_nav_menus'   => true,
				'show_in_menu'        => true,
			)
		);

		$args['labels'] = wp_parse_args( $labels,
			array(
				'name'               => sprintf( $this->client->__trans( '%s' ), $plural ),
				'singular_name'      => $singular,
				'menu_name'          => $this->client->__trans( $singular ),
				'all_items'          => sprintf( $this->client->__trans( '%s' ), $plural ),
				'add_new'            => sprintf( $this->client->__trans( 'Add %s' ), $singular ),
				'add_new_item'       => sprintf( $this->client->__trans( 'Add %s' ), $singular ),
				'edit'               => $this->client->__trans( 'Edit' ),
				'edit_item'          => sprintf( $this->client->__trans( '%s Details' ), $singular ),
				'new_item'           => sprintf( $this->client->__trans( 'New %s' ), $singular ),
				'view'               => sprintf( $this->client->__trans( 'View %s' ), $singular ),
				'view_item'          => sprintf( $this->client->__trans( 'View %s' ), $singular ),
				'search_items'       => sprintf( $this->client->__trans( 'Search %s' ), $plural ),
				'not_found'          => sprintf( $this->client->__trans( 'No %s found' ), $plural ),
				'not_found_in_trash' => sprintf( $this->client->__trans( 'No %s found in trash' ), $plural ),
				'parent'             => sprintf( $this->client->__trans( 'Parent %s' ), $singular ),
			)
		);

		register_taxonomy( $tax_name, $obj_type, apply_filters( "Pluginbazar/Utils/register_taxonomy_$tax_name", $args, $obj_type ) );
	}


	/**
	 * Register Post Type
	 *
	 * @param $post_type
	 * @param array $args
	 */
	public function register_post_type( $post_type, $args = array() ) {

		if ( post_type_exists( $post_type ) ) {
			return;
		}

		$singular = Utils::get_args_option( 'singular', $args, '' );
		$plural   = Utils::get_args_option( 'plural', $args, '' );
		$labels   = Utils::get_args_option( 'labels', $args, array() );

		$args = wp_parse_args( $args,
			array(
				'description'         => sprintf( $this->client->__trans( 'This is where you can create and manage %s.' ), $plural ),
				'public'              => true,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'rewrite'             => true,
				'query_var'           => true,
				'supports'            => array( 'title', 'thumbnail', 'editor', 'author' ),
				'show_in_nav_menus'   => true,
				'show_in_menu'        => true,
				'menu_icon'           => '',
			)
		);

		$args['labels'] = wp_parse_args( $labels,
			array(
				'name'               => sprintf( $this->client->__trans( '%s' ), $plural ),
				'singular_name'      => $singular,
				'menu_name'          => $this->client->__trans( $singular ),
				'all_items'          => sprintf( $this->client->__trans( '%s' ), $plural ),
				'add_new'            => sprintf( $this->client->__trans( 'Add %s' ), $singular ),
				'add_new_item'       => sprintf( $this->client->__trans( 'Add %s' ), $singular ),
				'edit'               => $this->client->__trans( 'Edit' ),
				'edit_item'          => sprintf( $this->client->__trans( 'Edit %s' ), $singular ),
				'new_item'           => sprintf( $this->client->__trans( 'New %s' ), $singular ),
				'view'               => sprintf( $this->client->__trans( 'View %s' ), $singular ),
				'view_item'          => sprintf( $this->client->__trans( 'View %s' ), $singular ),
				'search_items'       => sprintf( $this->client->__trans( 'Search %s' ), $plural ),
				'not_found'          => sprintf( $this->client->__trans( 'No %s found' ), $plural ),
				'not_found_in_trash' => sprintf( $this->client->__trans( 'No %s found in trash' ), $plural ),
				'parent'             => sprintf( $this->client->__trans( 'Parent %s' ), $singular ),
			)
		);

		register_post_type( $post_type, apply_filters( "Pluginbazar/Utils/register_post_type$post_type", $args ) );
	}


	/**
	 * Return Arguments Value
	 *
	 * @param string $key
	 * @param string $default
	 * @param array $args
	 *
	 * @return mixed|string
	 */
	public static function get_args_option( $key = '', $args = array(), $default = '' ) {

		$default = is_array( $default ) && empty( $default ) ? array() : $default;
		$default = ! is_array( $default ) && empty( $default ) ? '' : $default;
		$key     = empty( $key ) ? '' : $key;

		if ( isset( $args[ $key ] ) && ! empty( $args[ $key ] ) ) {
			return $args[ $key ];
		}

		return $default;
	}


	/**
	 * Return Post Meta Value
	 *
	 * @param bool $meta_key
	 * @param bool $post_id
	 * @param string $default
	 *
	 * @return mixed|string|void
	 */
	public static function get_meta( $meta_key = false, $post_id = false, $default = '' ) {

		if ( ! $meta_key ) {
			return '';
		}

		$post_id    = ! $post_id ? get_the_ID() : $post_id;
		$meta_value = get_post_meta( $post_id, $meta_key, true );
		$meta_value = empty( $meta_value ) ? $default : $meta_value;

		return apply_filters( 'Pluginbazar/Utils/get_meta', $meta_value, $meta_key, $post_id, $default );
	}
}