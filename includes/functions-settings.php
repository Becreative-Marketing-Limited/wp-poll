<?php
/**
 * Functions - Settings
 *
 * @author Pluginbazar
 */

$pages = array();

$pages['wpp-options'] = array(

	'page_nav'      => sprintf( '<i class="icofont-ui-settings"></i> %s', esc_html__( 'Options', 'wp-poll' ) ),
	'page_settings' => array(

		array(
			'title'   => esc_html__( 'General Settings', 'wp-poll' ),
			'options' => array(
				array(
					'id'          => 'wpp_btn_text_new_option',
					'title'       => esc_html__( 'Buttons Text', 'wp-poll' ),
					'details'     => esc_html__( 'New option button', 'wp-poll' ),
					'placeholder' => esc_html__( 'New Option', 'wp-poll' ),
					'type'        => 'text',
				),
				array(
					'id'          => 'wpp_btn_text_submit',
					'details'     => esc_html__( 'Submit button', 'wp-poll' ),
					'placeholder' => esc_html__( 'Submit now', 'wp-poll' ),
					'type'        => 'text',
				),
				array(
					'id'          => 'wpp_btn_text_results',
					'details'     => esc_html__( 'Results button', 'wp-poll' ),
					'placeholder' => esc_html__( 'Results', 'wp-poll' ),
					'type'        => 'text',
				),
			)
		),


		array(
			'title'   => esc_html__( 'Poll Archive', 'wp-poll' ),
			'options' => array(
				array(
					'id'      => 'wpp_page_archive',
					'title'   => esc_html__( 'Archive Page', 'wp-poll' ),
					'details' => esc_html__( 'Select a poll archive page', 'wp-poll' ),
					'type'    => 'select',
					'args'    => 'PAGES',
				),
				array(
					'id'          => 'wpp_archive_items_per_page',
					'title'       => esc_html__( 'Items per page', 'wp-poll' ),
					'details'     => esc_html__( 'How many poll do you want to show per page | Default: 10', 'wp-poll' ),
					'placeholder' => esc_html__( '10', 'wp-poll' ),
					'type'        => 'number',
				),
				array(
					'id'      => 'wpp_archive_show_hide',
					'title'   => esc_html__( 'Show / Hide', 'wp-poll' ),
					'details' => esc_html__( 'Choose what you want to display on archive page.', 'wp-poll' ),
					'type'    => 'checkbox',
					'args'    => array(
						'thumb'        => esc_html__( 'Display poll thumbnail', 'wp-poll' ),
						'results'      => esc_html__( 'Display poll results', 'wp-poll' ),
						'pagination'   => esc_html__( 'Display pagination', 'wp-poll' ),
						'page-content' => esc_html__( 'Display archive page content', 'wp-poll' ),
					),
					'default' => array( 'pagination' ),
				),
			)
		),


	),
);

$pages['wpp-reports'] = array(
	'page_nav'      => sprintf( '<i class="icofont-chart-histogram-alt"></i> %s', esc_html__( 'Reports', 'wp-poll' ) ),
	'show_submit'   => false,
	'page_settings' => array(
		array(
			'title'       => esc_html__( 'Poll Reports', 'wp-poll' ),
			'description' => esc_html__( 'View reports for specific poll item', 'wp-poll' ),
			'options'     => array(
				array(
					'id'      => 'wpp_reports_poll_id',
					'title'   => esc_html__( 'Select Poll', 'wp-poll' ),
					'details' => esc_html__( 'Select a poll you want to see report. Reports will generate automatically', 'wp-poll' ),
					'type'    => 'select',
					'value'   => isset( $_GET['poll-id'] ) ? sanitize_text_field( $_GET['poll-id'] ) : '',
					'args'    => 'POSTS_%poll%',
				),
				array(
					'id'    => 'wpp_reports_style',
					'title' => esc_html__( 'Report Style', 'wp-poll' ),
					'type'  => 'select',
					'value' => isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : 'pie',
					'args'  => array(
						'pie' => esc_html__( 'Pie Charts', 'wp-poll' ),
						'bar' => esc_html__( 'Bar Charts', 'wp-poll' ),
					),
				),

			),
		),
	)
);

$pages['wpp-support'] = array(
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


wpp()->PB_Settings( array(
	'add_in_menu'     => true,
	'menu_type'       => 'submenu',
	'menu_title'      => esc_html__( 'Settings', 'wp-poll' ),
	'page_title'      => esc_html__( 'Settings', 'wp-poll' ),
	'menu_page_title' => esc_html__( 'Poll - Control Panel', 'wp-poll' ),
	'capability'      => "manage_options",
	'menu_slug'       => 'wpp-settings',
	'parent_slug'     => "edit.php?post_type=poll",
	'pages'           => $pages,
) );