<?php
/**
 * Settings class
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

class LIQUIDPOLL_Settings {

	/**
	 * LIQUIDPOLL_Settings constructor.
	 */
	public function __construct() {

		global $liquidpoll_wpdk;

		// Generate settings page
		$settings_args = array(
			'framework_title'     => esc_html__( 'LiquidPoll - Advanced Polls for Creators and Brands', 'wp-poll' ),
			'menu_title'          => esc_html__( 'Settings', 'wp-poll' ),
			'menu_slug'           => 'settings',
			'menu_type'           => 'submenu',
			'menu_parent'         => 'edit.php?post_type=poll',
			'database'            => 'option',
			'theme'               => 'light',
			'show_search'         => false,
			'product_url'         => LIQUIDPOLL_PLUGIN_LINK,
			'product_version'     => $liquidpoll_wpdk->plugin_version,
			'product_version_pro' => liquidpoll()->is_pro() ? $liquidpoll_wpdk->license()->plugin_version : '',
			'quick_links'         => array(
				'supports' => array(
					'label' => esc_html__( 'Support', 'wp-poll' ),
					'url'   => LIQUIDPOLL_TICKET_URL,
				),
				'docs'     => array(
					'label' => esc_html__( 'Documentations', 'wp-poll' ),
					'url'   => LIQUIDPOLL_DOCS_URL,
				),
			),
			'pro_url'             => LIQUIDPOLL_PLUGIN_LINK,
		);

		WPDK_Settings::createSettingsPage( $liquidpoll_wpdk->plugin_unique_id, $settings_args, $this->get_settings_pages() );
	}


	/**
	 * Return settings pages
	 *
	 * @return mixed|void
	 */
	function get_settings_pages() {

		$field_sections['options'] = array(
			'title'    => esc_html__( 'Options', 'wp-poll' ),
			'sections' => array(
				array(
					'title'  => esc_html__( 'General Settings', 'wp-poll' ),
					'fields' => array(
						array(
							'id'         => 'liquidpoll_pro_popup_poll',
							'title'      => esc_html__( 'Popup Poll on Scroll', 'wp-poll' ),
							'subtitle'   => esc_html__( 'Set a poll that will popup when users / visitors scroll and come to end on your website.', 'wp-poll' ),
							'desc'       => esc_html__( 'Leave empty to disable this option.', 'wp-poll' ),
							'type'       => 'select',
							'chosen'     => true,
							'multiple'   => true,
							'settings'   => array(
								'width' => '50%',
							),
							'options'    => 'posts',
							'query_args' => array(
								'post_type' => 'poll',
							),
						),
						array(
							'id'          => 'liquidpoll_btn_text_submit',
							'title'       => esc_html__( 'Button Text - Submit Vote', 'wp-poll' ),
							'subtitle'    => esc_html__( 'Customize "Submit" button text.', 'wp-poll' ),
							'placeholder' => esc_html__( 'Vote Now', 'wp-poll' ),
							'type'        => 'text',
						),
						array(
							'id'          => 'liquidpoll_btn_text_results',
							'title'       => esc_html__( 'Button Text - Results', 'wp-poll' ),
							'subtitle'    => esc_html__( 'Customize "Results" button text.', 'wp-poll' ),
							'placeholder' => esc_html__( 'View Results', 'wp-poll' ),
							'type'        => 'text',
						),
						array(
							'id'          => 'liquidpoll_btn_text_new_option',
							'title'       => esc_html__( 'Button Text - New Option', 'wp-poll' ),
							'subtitle'    => esc_html__( 'Customize "New Option" button text.', 'wp-poll' ),
							'placeholder' => esc_html__( 'Add new option', 'wp-poll' ),
							'type'        => 'text',
						),
						array(
							'id'      => 'liquidpoll_heading_ipinfo',
							'content' => esc_html__( 'IPinfo Settings', 'wp-poll' ),
							'type'    => 'subheading',
						),
						array(
							'id'          => 'liquidpoll_ipinfo_token',
							'title'       => esc_html__( 'API Token', 'wp-poll' ),
							'subtitle'    => esc_html__( 'IPinfor api access token.', 'wp-poll' ),
							'desc'        => esc_html__( 'Get your token from', 'wp-poll' ) . ' ' . sprintf( '<a href="%s" target="_blank">%s</a>', esc_url( 'https://ipinfo.io/' ), 'ipinfo.io' ),
							'placeholder' => '93u867df431io7',
							'type'        => 'text',
						),
					),
				),
				array(
					'title'  => esc_html__( 'Poll Archive', 'wp-poll' ),
					'fields' => array(
						array(
							'id'       => 'liquidpoll_page_archive',
							'title'    => esc_html__( 'Archive Page', 'wp-poll' ),
							'subtitle' => esc_html__( 'Select a page where all the polls will be listed.', 'wp-poll' ),
							'type'     => 'select',
							'chosen'   => true,
							'multiple' => true,
							'settings' => array(
								'width' => '50%',
							),
							'options'  => 'pages',
						),
						array(
							'id'       => 'liquidpoll_archive_items_per_page',
							'title'    => esc_html__( 'Polls Per Page', 'wp-poll' ),
							'subtitle' => esc_html__( 'How many poll do you want to show per page.', 'wp-poll' ),
							'desc'     => esc_html__( 'Default: 10', 'wp-poll' ),
							'type'     => 'number',
							'default'  => 10,
						),
						array(
							'id'      => 'liquidpoll_archive_thumb',
							'title'   => esc_html__( 'Poll Thumbnail', 'wp-poll' ),
							'label'   => esc_html__( 'Display poll thumbnail in archive page.', 'wp-poll' ),
							'type'    => 'switcher',
							'default' => false,
						),
						array(
							'id'      => 'liquidpoll_archive_results',
							'title'   => esc_html__( 'Poll Results', 'wp-poll' ),
							'label'   => esc_html__( 'Display poll results in archive page.', 'wp-poll' ),
							'type'    => 'switcher',
							'default' => false,
						),
						array(
							'id'      => 'liquidpoll_archive_pagination',
							'title'   => esc_html__( 'Pagination', 'wp-poll' ),
							'label'   => esc_html__( 'Display pagination in archive page.', 'wp-poll' ),
							'type'    => 'switcher',
							'default' => false,
						),
						array(
							'id'      => 'liquidpoll_archive_page-content',
							'title'   => esc_html__( 'Page Content', 'wp-poll' ),
							'label'   => esc_html__( 'Display page content along with the archive.', 'wp-poll' ),
							'type'    => 'switcher',
							'default' => false,
						),
					),
				),
				array(
					'title'  => esc_html__( 'SMS Settings', 'wp-poll' ),
					'desc'   => esc_html__( 'You need to sign up on Twilio and get necessary information below to make the SMS functions enable. Click here to Start now', 'wp-poll' ),
					'fields' => array(
						array(
							'id'           => '_twilio_sid',
							'title'        => esc_html__( 'Account SID', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Add account subscription ID.', 'wp-poll' ),
							'desc'         => sprintf( '<a href="%s">%s</a>', esc_url( 'www.twilio.com' ), esc_html__( 'Login to get this information', 'wp-poll-pro' ) ),
							'type'         => 'text',
							'placeholder'  => 'AC67jfk762a8a84f0fjwtd779c57572eb8',
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
						array(
							'id'           => '_twilio_token',
							'title'        => esc_html__( 'Auth Token', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Add authentication token.', 'wp-poll' ),
							'desc'         => sprintf( '<a href="%s">%s</a>', esc_url( 'www.twilio.com' ), esc_html__( 'Login to get this information', 'wp-poll-pro' ) ),
							'type'         => 'text',
							'placeholder'  => 'cd1232661c62b3f95190kod16739b6e1',
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
						array(
							'id'           => '_twilio_sms_from',
							'title'        => esc_html__( 'From Phone', 'wp-poll' ),
							'subtitle'     => esc_html__( 'From which number the SMS will be sent.', 'wp-poll' ),
							'type'         => 'text',
							'placeholder'  => '+12015883105',
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
					),
				),
			),
		);

		$field_sections['styling'] = array(
			'title'    => esc_html__( 'Styling', 'wp-poll' ),
			'sections' => array(
				array(
					'title'  => esc_html__( 'Poll Elements', 'wp-poll' ),
					'fields' => array(
						array(
							'id'           => '_typography_title',
							'title'        => esc_html__( 'Poll Title', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Control typography settings for poll title.', 'wp-poll' ),
							'type'         => 'typography',
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
						array(
							'id'           => '_typography_content',
							'title'        => esc_html__( 'Poll Content', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Control typography settings for poll content.', 'wp-poll' ),
							'type'         => 'typography',
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
						array(
							'id'           => '_typography_options',
							'title'        => esc_html__( 'Poll Options', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Control typography settings for poll options.', 'wp-poll' ),
							'type'         => 'typography',
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
						array(
							'id'           => '_typography_countdown_timer',
							'title'        => esc_html__( 'Countdown Timer', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Control typography settings for poll count down timer.', 'wp-poll' ),
							'type'         => 'typography',
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
					),
				),
				array(
					'title'  => esc_html__( 'Poll Buttons', 'wp-poll' ),
					'fields' => array(
						array(
							'id'           => '_typography_btn_submit',
							'title'        => esc_html__( 'Submit Button', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Control typography settings for poll submit button.', 'wp-poll' ),
							'type'         => 'typography',
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
						array(
							'id'           => '_typography_btn_submit_bg',
							'title'        => esc_html__( 'Background color', 'wp-poll' ),
							'type'         => 'color',
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
						array(
							'id'           => '_typography_btn_results',
							'title'        => esc_html__( 'Results Button', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Control typography settings for poll results button.', 'wp-poll' ),
							'type'         => 'typography',
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
						array(
							'id'           => '_typography_btn_results_bg',
							'title'        => esc_html__( 'Background color', 'wp-poll' ),
							'type'         => 'color',
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
					),
				),
			),
		);

		$field_sections['languages'] = array(
			'title'    => esc_html__( 'Language', 'wp-poll' ),
			'sections' => array(
				array(
					'title'  => esc_html__( 'Poll', 'wp-poll' ),
					'fields' => array(
						array(
							'id'           => 'liquidpoll_poll_text_success',
							'title'        => esc_html__( 'Poll Success Text', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Display once submission is successfull.', 'wp-poll' ),
							'type'         => 'text',
							'placeholder'  => esc_html__( 'Congratulations, Successfully voted.', 'wp-poll' ),
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
						array(
							'id'           => 'liquidpoll_poll_text_failed',
							'title'        => esc_html__( 'Poll Failed Text', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Display once submission is failed.', 'wp-poll' ),
							'type'         => 'text',
							'placeholder'  => esc_html__( 'Something went wrong!', 'wp-poll' ),
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
					),
				),
				array(
					'title'  => esc_html__( 'NPS', 'wp-poll' ),
					'fields' => array(
						array(
							'id'           => 'liquidpoll_nps_text_min',
							'title'        => esc_html__( 'Minimum Identifier Text', 'wp-poll' ),
							'subtitle'     => esc_html__( 'This will be replacer for identifying the min value.', 'wp-poll' ),
							'type'         => 'text',
							'placeholder'  => esc_html__( 'It was terrible!', 'wp-poll' ),
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
						array(
							'id'           => 'liquidpoll_nps_text_max',
							'title'        => esc_html__( 'Maximum Identifier Text', 'wp-poll' ),
							'subtitle'     => esc_html__( 'This will be replacer for identifying the max value.', 'wp-poll' ),
							'type'         => 'text',
							'placeholder'  => esc_html__( 'Absolutely love it!', 'wp-poll' ),
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
						array(
							'id'           => 'liquidpoll_nps_text_success',
							'title'        => esc_html__( 'Success Text', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Display once submission is successfull.', 'wp-poll' ),
							'type'         => 'text',
							'placeholder'  => esc_html__( 'Congratulations, Successfully voted.', 'wp-poll' ),
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
						array(
							'id'           => 'liquidpoll_nps_text_failed',
							'title'        => esc_html__( 'Failed Text', 'wp-poll' ),
							'subtitle'     => esc_html__( 'Display once submission is failed.', 'wp-poll' ),
							'type'         => 'text',
							'placeholder'  => esc_html__( 'Something went wrong!', 'wp-poll' ),
							'availability' => ! liquidpoll()->is_pro() ? 'pro' : '',
						),
					),
				),
			),
		);

		$field_sections['reports'] = array(
			'external' => true,
			'title'    => esc_html__( 'Reports', 'wp-poll' ),
		);

//		if ( isset( $_GET['poll-id'] ) && ! empty( sanitize_text_field( $_GET['poll-id'] ) ) ) {
//		}

		return apply_filters( 'woc_filters_settings_pages', $field_sections );
	}
}

new LIQUIDPOLL_Settings();

