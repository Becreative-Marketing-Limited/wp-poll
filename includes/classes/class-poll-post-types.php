<?php
/**
 * Class WPP Post Types
 *
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access 


if ( ! class_exists( 'WPP_Post_types' ) ) {
	class WPP_Post_types {


		/**
		 * wpp_Post_types constructor.
		 */
		function __construct() {

//			add_action( 'init', array( $this, 'add_post_types' ) );
		}


		/**
		 * Ad Post types
		 */
		function add_post_types() {

			/**
			 * Register Post Type Poll
			 */
			$this->register_post_type( 'poll', array(
				'singular'          => esc_html__( 'Poll', 'wp-poll' ),
				'plural'            => esc_html__( 'All Polls', 'wp-poll' ),
				'menu_icon'         => 'dashicons-chart-bar',
				'menu_position'     => 15,
				'supports'          => array( 'title' ),
			) );

			/**
			 * Register Post Type Poll
			 */
			$this->register_taxonomy( 'poll_cat', 'poll', array(
				'singular'          => esc_html__( 'Poll Category', 'wp-poll' ),
				'plural'            => esc_html__( 'Poll Categories', 'wp-poll' ),
			) );

			do_action( 'wpp_register_post_types', $this );
		}


		/**
		 * Register Post Types
		 *
		 * @param $post_type
		 * @param $args
		 */
		function register_post_type( $post_type, $args ) {

			if ( post_type_exists( $post_type ) ) {
				return;
			}

			$singular = isset( $args['singular'] ) ? $args['singular'] : '';
			$plural   = isset( $args['plural'] ) ? $args['plural'] : '';

			$args = array_merge( array(
				'labels'              => array(
					'name'               => sprintf( __( '%s', 'wp-poll' ), $plural ),
					'singular_name'      => $singular,
					'menu_name'          => __( $singular, 'wp-poll' ),
					'all_items'          => sprintf( __( '%s', 'wp-poll' ), $plural ),
					'add_new'            => sprintf( __( 'Add %s', 'wp-poll' ), $singular ),
					'add_new_item'       => sprintf( __( 'Add %s', 'wp-poll' ), $singular ),
					'edit'               => __( 'Edit', 'wp-poll' ),
					'edit_item'          => sprintf( __( 'Edit %s', 'wp-poll' ), $singular ),
					'new_item'           => sprintf( __( 'New %s', 'wp-poll' ), $singular ),
					'view'               => sprintf( __( 'View %s', 'wp-poll' ), $singular ),
					'view_item'          => sprintf( __( 'View %s', 'wp-poll' ), $singular ),
					'search_items'       => sprintf( __( 'Search %s', 'wp-poll' ), $plural ),
					'not_found'          => sprintf( __( 'No %s found', 'wp-poll' ), $plural ),
					'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'wp-poll' ), $plural ),
					'parent'             => sprintf( __( 'Parent %s', 'wp-poll' ), $singular )
				),
				'description'         => sprintf( __( 'This is where you can create and manage %s.', 'wp-poll' ), $plural ),
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
			), $args );

			register_post_type( $post_type, apply_filters( "register_post_type_$post_type", $args ) );
		}


		/**
		 * Register Taxonomy
		 *
		 * @param $tax_name
		 * @param $obj_name
		 * @param array $args
		 */
		function register_taxonomy( $tax_name, $obj_name, $args = array() ) {

			if ( taxonomy_exists( $tax_name ) ) {
				return;
			}

			$singular     = isset( $args['singular'] ) ? $args['singular'] : __( 'Singular', 'wp-poll' );
			$plural       = isset( $args['plural'] ) ? $args['plural'] : __( 'Plural', 'wp-poll' );
			$hierarchical = isset( $args['hierarchical'] ) ? $args['hierarchical'] : true;

			register_taxonomy( $tax_name, $obj_name,
				apply_filters( "register_taxonomy_" . $tax_name, array(
					'labels'              => array(
						'name'               => sprintf( __( '%s', 'wp-poll' ), $plural ),
						'singular_name'      => $singular,
						'menu_name'          => __( $singular, 'wp-poll' ),
						'all_items'          => sprintf( __( '%s', 'wp-poll' ), $plural ),
						'add_new'            => sprintf( __( 'Add %s', 'wp-poll' ), $singular ),
						'add_new_item'       => sprintf( __( 'Add %s', 'wp-poll' ), $singular ),
						'edit'               => __( 'Edit', 'wp-poll' ),
						'edit_item'          => sprintf( __( '%s Details', 'wp-poll' ), $singular ),
						'new_item'           => sprintf( __( 'New %s', 'wp-poll' ), $singular ),
						'view'               => sprintf( __( 'View %s', 'wp-poll' ), $singular ),
						'view_item'          => sprintf( __( 'View %s', 'wp-poll' ), $singular ),
						'search_items'       => sprintf( __( 'Search %s', 'wp-poll' ), $plural ),
						'not_found'          => sprintf( __( 'No %s found', 'wp-poll' ), $plural ),
						'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'wp-poll' ), $plural ),
						'parent'             => sprintf( __( 'Parent %s', 'wp-poll' ), $singular )
					),
					'description'         => sprintf( __( 'This is where you can create and manage %s.', 'wp-poll' ), $plural ),
					'public'              => true,
					'show_ui'             => true,
					'capability_type'     => 'post',
					'map_meta_cap'        => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => false,
					'hierarchical'        => $hierarchical,
					'rewrite'             => true,
					'query_var'           => true,
					'show_in_nav_menus'   => true,
					'show_in_menu'        => true,
				) )
			);
		}
	}

	new WPP_Post_types();
}