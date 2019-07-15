<?php
/**
 * Functions - Settings
 *
 * @author Pluginbazar
 */

$pages = array();

$pages['woc_options'] = array(

	'page_nav'      => sprintf( '<i class="icofont-ui-settings"></i> %s', esc_html__( 'Options', 'wp-poll' ) ),
	'page_settings' => array(

		array(
			'title'   => esc_html__( 'General Settings', 'wp-poll' ),
			'options' => array()
		),

		array(
			'title'   => esc_html__( 'Poll Archive', 'wp-poll' ),
			'options' => array(
				array(
					'id'       => 'wpp_poll_page',
					'title'    => esc_html__( 'Archive Page', 'wp-poll' ),
					'details'  => esc_html__( 'Select a poll archive page', 'wp-poll' ),
					'type'     => 'select',
					'multiple' => true,
					'args'     => 'PAGES',
				),
				array(
					'id'      => 'wpp_poll_page_content_show',
					'title'   => esc_html__( 'Page content', 'wp-poll' ),
					'details' => esc_html__( 'Do you want to show page Content?', 'wp-poll' ),
					'type'    => 'radio',
					'args'    => array(
						'yes' => esc_html__( 'Yes', 'wp-poll' ),
						'no'  => esc_html__( 'No', 'wp-poll' ),
					),
					'default' => array(
						'no'
					),
				),
			)
		),
	),
);

$pages['woc_support'] = array(
	'page_nav'      => '<i class="icofont-live-support"></i> ' . esc_html__( 'Support', 'wp-poll' ),
	'show_submit'   => false,
	'page_settings' => array(

		'sec_options' => array(
			'title'   => esc_html__( 'Emergency support from Pluginbazar.com', 'wp-poll' ),
			'options' => array(
				array(
					'id'      => '__1',
					'title'   => esc_html__( 'Support Forum', 'wp-poll' ),
					'details' => sprintf( '%1$s<br>' . esc_html__( '<a href="%1$s" target="_blank">Ask in Forum</a>', 'wp-poll' ), WPP_FORUM_URL ),
				),

				array(
					'id'      => '__2',
					'title'   => esc_html__( 'Can\'t Login..?', 'wp-poll' ),
					'details' => sprintf( esc_html__( '<span>Unable to login <strong>Pluginbazar.com</strong></span><br><a href="%1$s" target="_blank">Get Immediate Solution</a>', 'wp-poll' ), WPP_CONTACT_URL ),
				),

				array(
					'id'      => '__3',
					'title'   => esc_html__( 'Like this Plugin?', 'wp-poll' ),
					'details' => sprintf( esc_html__( '<span>To share feedback about this plugin Please </span><br><a href="%1$s" target="_blank">Rate now</a>', 'wp-poll' ), WPP_REVIEW_URL ),
				),

			)
		),
	)
);

global $wpp;

$wpp->PB_Settings( array(
	'add_in_menu'     => true,
	'menu_type'       => 'submenu',
	'menu_title'      => esc_html__( 'Settings', 'wp-poll' ),
	'page_title'      => esc_html__( 'Settings', 'wp-poll' ),
	'menu_page_title' => 'WooCommerce Open Close - ' . esc_html__( 'Control Panel', 'wp-poll' ),
	'capability'      => "manage_options",
	'menu_slug'       => "woc-open-close",
	'parent_slug'     => "edit.php?post_type=poll",
	'pages'           => $pages,
) );