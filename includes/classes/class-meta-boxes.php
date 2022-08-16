<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

defined( 'ABSPATH' ) || exit;

class LIQUIDPOLL_Poll_meta {

	public function __construct() {

		$this->generate_poll_meta_box();
	}


	public function generate_poll_meta_box() {

		$prefix = 'liquidpoll_poll_meta';

		PBSettings::createMetabox( $prefix,
			array(
				'title'     => __( 'Slider Options', 'wp-poll' ),
				'post_type' => 'poll',
				'data_type' => 'unserialize',
				'context'   => 'normal',
				'nav'       => 'inline',
				'preview'   => true,
			)
		);

		foreach ( $this->get_meta_field_sections() as $section ) {
			PBSettings::createSection( $prefix, $section );
		}
	}


	public function get_meta_field_sections() {

		$poll_setting_fields = array(
			array(
				'id'    => 'settings_vote_after_deadline',
				'title' => esc_html__( 'Poll settings', 'wp-poll' ),
				'label' => esc_html__( 'Allow users to vote after deadline.', 'wp-poll' ),
				'type'  => 'switcher',
			),
//			array(
//				'id'    => 'settings_multiple_votes',
//				'title' => ' ',
//				'label' => esc_html__( 'Allow users to vote on multiple options in a single poll.', 'wp-poll' ),
//				'type'  => 'switcher',
//				'class' => 'padding-top-none',
//			),
			array(
				'id'    => 'settings_new_options',
				'title' => ' ',
				'label' => esc_html__( 'Allow users to add new option.', 'wp-poll' ),
				'type'  => 'switcher',
				'class' => 'padding-top-none',
			),
			array(
				'id'    => 'settings_hide_timer',
				'title' => ' ',
				'label' => esc_html__( 'Hide countdown timer for this poll.', 'wp-poll' ),
				'type'  => 'switcher',
				'class' => 'padding-top-none',
			),
		);
		$poll_setting_fields = apply_filters( 'LiquidPoll/Filters/poll_setting_fields', $poll_setting_fields );

		$field_sections['general_settings'] = array(
			'title'  => __( 'General Settings', 'wp-poll' ),
			'icon'   => 'fa fa-cog',
			'fields' => array_merge( array(
				array(
					'id'      => '_type',
					'title'   => esc_html__( 'Poll type', 'wp-poll' ),
					'type'    => 'button_set',
					'options' => array(
						'poll'         => array( 'label' => esc_html__( 'Poll', 'wp-poll' ) ),
						'reaction'     => array( 'label' => esc_html__( 'Reaction', 'wp-poll' ), 'availability' => 'upcoming', ),
						'subscription' => array( 'label' => esc_html__( 'Subscription', 'wp-poll' ), 'availability' => 'upcoming', ),
						'feedback'     => array( 'label' => esc_html__( 'Feedback', 'wp-poll' ), 'availability' => 'upcoming', ),
						'nps'          => array( 'label' => esc_html__( 'NPS Score', 'wp-poll' ), 'availability' => 'upcoming', ),
					),
					'default' => 'poll',
				),
				array(
					'id'       => '_content',
					'title'    => esc_html__( 'Poll Content', 'wp-poll' ),
					'subtitle' => esc_html__( 'Description about this poll', 'wp-poll' ),
					'type'     => 'wp_editor',
				),
				array(
					'id'            => '_deadline',
					'title'         => esc_html__( 'Deadline', 'wp-poll' ),
					'subtitle'      => esc_html__( 'Specify a date when this poll will end. Leave empty to ignore this option', 'wp-poll' ),
					'type'          => 'date',
					'autocomplete'  => 'off',
					'placeholder'   => date( 'Y-m-d' ),
					'field_options' => array(
						'dateFormat' => 'yy-mm-dd',
					),
				),
				array(
					'id'      => '_countdown_position',
					'title'   => esc_html__( 'Countdown Position', 'wp-poll' ),
					'type'    => 'select',
					'options' => array(
						'below_options' => array( 'label' => esc_html__( 'Below options', 'wp-poll' ) ),
						'above_options' => array( 'label' => esc_html__( 'Above options (Pro)', 'wp-poll' ), 'availability' => liquidpoll()->is_pro(), ),
					),
					'default' => 'below_options',
				),
			), $poll_setting_fields )
		);
		$field_sections['poll_options']     = array(
			'title'  => __( 'Poll Options', 'wp-poll' ),
			'icon'   => 'fa fa-th-large',
			'fields' => array(
				array(
					'id'              => 'poll_meta_options',
					'title'           => esc_html__( 'Options', 'wp-poll' ),
					'subtitle'        => esc_html__( 'Add poll options here. You can skip using media if you do not need this.', 'wp-poll' ),
					'type'            => 'repeater',
					'button_title'    => esc_html__( 'Add option', 'wp-poll' ),
					'disable_actions' => array( 'clone' ),
					'fields'          => array(
						array(
							'id'    => 'label',
							'title' => esc_html__( 'Label', 'wp-poll' ),
							'type'  => 'text',
						),
						array(
							'id'           => 'thumb',
							'title'        => esc_html__( 'Thumbnail', 'wp-poll' ),
							'type'         => 'media',
							'preview_size' => 'full',
							'library'      => 'image',
							'url'          => false,
						),
//						array(
//							'id'      => 'shortcode',
//							'title'   => esc_html__( 'Shortcode', 'wp-poll' ),
//							'type'    => 'text',
//							'class'   => 'hide-input-field',
//							'default' => '',
//							'desc'    => sprintf( '<span class="shortcode tt--hint tt--top" aria-label="Click to Copy">[poller_list poll_id="%s" option_id="%s"]</span>', '', '' )
//						),
					),
				),
				array(
					'id'    => 'hide_option_labels',
					'title' => esc_html__( 'Hide labels', 'wp-poll' ),
					'label' => esc_html__( 'Hide labels of all options.', 'wp-poll' ),
					'type'  => 'switcher',
				),
			),
		);
		$field_sections['poll_styling']     = array(
			'title'  => __( 'Poll Styling', 'wp-poll' ),
			'icon'   => 'fa fa-bolt',
			'fields' => array(
				array(
					'id'       => '_theme',
					'title'    => esc_html__( 'Theme Style', 'wp-poll' ),
					'subtitle' => esc_html__( 'By default it will apply from global settings.', 'wp-poll' ),
					'type'     => 'select',
					'options'  => $this->get_poll_themes(),
					'default'  => 'default',
				),
				array(
					'id'           => '_results_type',
					'title'        => esc_html__( 'Results Type', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Display percentage or votes count in results screen.', 'wp-poll' ),
					'type'         => 'button_set',
					'options'      => array(
						'votes'      => array( 'label' => esc_html__( 'Votes Count', 'wp-poll' ) ),
						'percentage' => array( 'label' => esc_html__( 'Percentage', 'wp-poll' ) ),
					),
					'text_off'     => esc_html__( 'Percentage', 'wp-poll' ),
					'text_width'   => 150,
					'default'      => 'votes',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme', 'any', '1,2,3,5,8,9,10' ),
				),
//				array(
//					'id'          => '_votes_count_text',
//					'title'       => esc_html__( 'Votes Count Text', 'wp-poll' ),
//					'subtitle'    => esc_html__( 'Change votes count text in results screen.', 'wp-poll' ),
//					'desc'        => esc_html__( 'You can use replacer "{votes_count}" which will replace with actual votes count number.', 'wp-poll' ),
//					'placeholder' => esc_html__( '{votes_count} votes', 'wp-poll' ),
//					'type'        => 'text',
//					'dependency'  => array( '_results_type', '==', 'votes' ),
//				),
				array(
					'id'      => 'subheading_typography',
					'type'    => 'subheading',
					'content' => esc_html__( 'Typography Controls', 'slider-x-woo' ),
				),
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
				array(
					'id'      => 'subheading_typography_button_submit',
					'type'    => 'subheading',
					'content' => esc_html__( 'Typography Controls - Buttons - Submit', 'slider-x-woo' ),
				),
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
					'id'      => 'subheading_typography_button_results',
					'type'    => 'subheading',
					'content' => esc_html__( 'Typography Controls - Buttons - Results', 'slider-x-woo' ),
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
		);

		return apply_filters( 'LiquidPoll/Filters/poll_meta_field_sections', $field_sections );
	}


	/**
	 * Return poll themes
	 *
	 * @return mixed|void
	 */
	function get_poll_themes() {

		$themes = array(
			1   => array(
				'label' => esc_html__( 'Theme 1', 'wp-poll' ),
			),
			2   => array(
				'label' => esc_html__( 'Theme 2', 'wp-poll' ),
			),
			3   => array(
				'label' => esc_html__( 'Theme 3', 'wp-poll' ),
			),
			998 => array(
				'label'        => esc_html__( '10+ are in pro', 'wp-poll' ),
				'availability' => 'pro',
			),
			999 => array(
				'label'        => esc_html__( '20+ are in pro', 'wp-poll' ),
				'availability' => 'upcoming',
			),
		);
		$themes = apply_filters( 'LiquidPoll/Filters/poll_themes', $themes );

		ksort( $themes );

		return $themes;
	}
}

new LIQUIDPOLL_Poll_meta();