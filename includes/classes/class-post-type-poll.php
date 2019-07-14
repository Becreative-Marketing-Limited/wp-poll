<?php

/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 

class WPP_Post_type_Poll{
	
	public function __construct(){
		add_action( 'init', array( $this, 'wpp_post_type_poll' ), 0 );
		// add_action( 'init', array( $this, 'wpp_post_type_wpp_by_post' ), 0 );
	}
	
	public function wpp_post_type_poll() {
		if ( post_type_exists( "poll" ) )
		return;

		$singular  = __( 'Poll', 'wp-poll' );
		$plural    = __( 'Polls', 'wp-poll' );
	 
	 
		register_post_type( "poll",
			apply_filters( "register_post_type_poll", array(
				'labels' => array(
					'name' 					=> $plural,
					'singular_name' 		=> $singular,
					'menu_name'             => $singular,
					'all_items'             => __( 'All', 'wp-poll' ) 	." $plural",
					'add_new' 				=> __( 'Add', 'wp-poll' ) 	." $singular",
					'add_new_item' 			=> __( 'Add', 'wp-poll' ) 	." $singular",
					'edit' 					=> __( 'Edit', 'wp-poll' ),
					'edit_item' 			=> __( 'Edit', 'wp-poll' ) 	." $singular",
					'new_item' 				=> __( 'New', 'wp-poll' ) 	." $singular",
					'view' 					=> __( 'View', 'wp-poll' ) 	." $singular",
					'view_item' 			=> __( 'View', 'wp-poll' ) 	." $singular",
					'search_items' 			=> __( 'Search', 'wp-poll' ) 	." $singular",
					'not_found' 			=> sprintf( __( 'No %s found', 'wp-poll' ), $singular ),
					'not_found_in_trash' 	=> sprintf( __( 'No %s found in trash', 'wp-poll' ), $plural ),
					'parent' 				=> __( 'Parent', 'wp-poll' ) 	." $singular",
				),
				'description' => __( 'This is where you can create and manage', 'wp-poll' ) ." $plural",
				'public' 				=> true,
				'show_ui' 				=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'          => true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> true,
				'query_var' 			=> true,
				'supports' 				=> array('comments','thumbnail'),
				'show_in_nav_menus' 	=> false,
				'menu_icon' => 'dashicons-chart-bar',
			) )
		); 
		
		$singular  = __( 'Poll Category', 'wp-poll' );
		$plural    = __( 'Poll Categories', 'wp-poll' );
	 
		register_taxonomy( "poll_cat",
			apply_filters( 'register_taxonomy_poll_cat_object_type', array( 'poll' ) ),
	       	apply_filters( 'register_taxonomy_poll_cat_args', array(
				'hierarchical' 			=> true,
		        'show_admin_column' 	=> true,					
		        'update_count_callback' => '_update_post_term_count',
		        'label' 				=> $plural,
		        'labels' => array(
					'name'              => $plural,
					'singular_name'     => $singular,
					'menu_name'         => ucwords( $plural ),
					'search_items'      => __( 'Search', 'wp-poll' ) 	." $singular",
					'all_items'         => __( 'All', 'wp-poll' ) 	." $plural",
					'parent_item'       => __( 'Parent', 'wp-poll' ) 	." $singular",
					'parent_item_colon' => __( 'Parent', 'wp-poll' ) 	." $singular",
					'edit_item'         => __( 'Edit', 'wp-poll' ) 	." $singular",
					'update_item'       => __( 'Update', 'wp-poll' ) 	." $singular",
					'add_new_item'      => __( 'Add', 'wp-poll' ) 	." $singular",
					'new_item_name'     => sprintf( __( 'New %s Name', 'wp-poll' ),  $singular )
	            ),
		        'show_ui' 				=> true,
		        'public' 	     		=> true,
				'rewrite' => array(
					'slug' => 'poll_cat',
					'with_front' => false,
					'hierarchical' => true
				),
			) )
		);
			
			
			
	}
	
} new WPP_Post_type_Poll();