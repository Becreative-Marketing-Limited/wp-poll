<?php
/**
 * Widget - Main
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use WPDK\Utils;


class LIQUIDPOLL_Widget_poll extends LIQUIDPOLL_Widget_base {

	public function get_name() {
		return 'liquidpoll-widget-poll';
	}

	public function get_title() {
		return esc_html__( 'Liquidpoll - Poll', 'wp-poll' );
	}

	public function get_icon() {
		return 'eicon-checkbox';
	}

	public function get_categories() {
		return array( 'liquidpoll_category' );
	}

	public function get_keywords() {
		return array( 'poll', 'liquidpoll' );
	}

	public function get_style_depends() {
		return array( 'widget-poll' );
	}

	public function get_script_depends() {
		return array( 'widget-poll' );
	}


	/**
	 * Render views
	 *
	 * @return void
	 */
	protected function render() {

		global $liquidpoll_widget_settings, $liquidpoll_inside_elementor;

		$liquidpoll_inside_elementor = true;

		$liquidpoll_widget_settings           = $this->get_settings_for_display();
		$liquidpoll_widget_settings['widget'] = $this->get_name();

		liquidpoll_get_template( 'widget-poll/views/template-1.php', array(), '', LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/' );
	}


	/**
	 * Register content related controls
	 */
	protected function register_controls() {

		/**
		 * Main Tab - Content - General Settings
		 */
		$this->start_controls_section( 'section_content_general_settings', array(
			'label' => esc_html__( 'General Settings', 'wp-poll' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );
		$this->controls_for_content_settings();
		$this->end_controls_section();


		/**
		 * Main Tab - Content - Form
		 */
		$this->start_controls_section( 'section_content_form', array(
			'label'     => esc_html__( 'Form', 'wp-poll' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
		) );
		$this->controls_for_content_form();
		$this->end_controls_section();


		/**
		 * Main Tab - Style
		 */
		$this->start_controls_section( 'section_infobox_style', [
			'label' => esc_html__( 'Global Style', 'wp-poll' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );
		$this->controls_for_style();
		$this->end_controls_section();

		/**
		 * Main Tab - Form Style
		 */
		$this->start_controls_section( 'section_form_style', [
			'label'     => esc_html__( 'Form Style', 'wp-poll' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );
		$this->controls_for_style_form();
		$this->end_controls_section();
	}


	protected function controls_for_content_settings() {

		$options_poll     = array( '0' => esc_html__( 'Select a Poll', 'wp-poll' ) );

		foreach ( get_posts( array( 'post_type' => 'poll', 'meta_key' => '_type', 'meta_value' => 'poll', 'showposts' => - 1 ) ) as $post ) {
			$options_poll[ $post->ID ] = $post->post_title;
		}


		$this->add_control( 'poll_id_poll', [
			'label'     => esc_html__( 'Select Poll', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => $options_poll,
			'default'   => '0',
		] );


		$this->add_control( 'poll_content', [
			'label'     => esc_html__( 'Poll Content', 'wp-poll' ),
			'type'      => Controls_Manager::TEXTAREA,
		] );

		$this->add_control( '_deadline', [
			'label'          => esc_html__( 'Deadline', 'wp-poll' ),
			'type'           => Controls_Manager::DATE_TIME,
			'label_block'    => false,
			'picker_options' => [
				'enableTime' => false,
			],
		] );

		$this->add_control( '_countdown_position', [
			'label'     => esc_html__( 'Countdown Position', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'below_options' => esc_html__( 'Below options', 'wp-poll' ),
				'above_options' => esc_html__( 'Above options (Pro)', 'wp-poll' ),
			],
			'default'   => 'below_options',
		] );

		$this->add_control( 'poll_settings', [
			'label'     => esc_html__( 'Settings', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'settings_hide_for_logged_out_users', [
			'label'     => esc_html__( 'Hide for logged out users.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'settings_vote_after_deadline', [
			'label'     => esc_html__( 'Allow users to vote after deadline.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'settings_hide_timer', [
			'label'     => esc_html__( 'Hide countdown timer for this poll.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'settings_poll_view_results_to_voted_users_only', [
			'label'     => esc_html__( 'View results to voted users only.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
		] );
	}


	protected function controls_for_style() {

		$this->add_control( '_theme', [
			'label'     => esc_html__( 'Poll Theme', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => liquidpoll_calculate_themes( liquidpoll()->get_poll_themes() ),
			'default'   => 1,
		] );

		$this->add_control( '_box_overlay_heading', [
			'label'     => esc_html__( 'Box Overlay', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ '_theme' => [ '7', '9' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => '_box_overlay',
			'label'     => esc_html__( 'Overlay', 'wp-poll' ),
			'types'     => [ 'gradient' ],
			'selector'  => '{{WRAPPER}} .liquidpoll-option-thumb::before',
			'condition' => [ '_theme' => [ '7', '9' ] ],
		] );

		$this->add_control( '_box_background_heading', [
			'label'     => esc_html__( 'Box Background or Border', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ '_theme' => [ '6', '7', '8', '9' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => '_box_background',
			'label'     => esc_html__( 'Overlay', 'wp-poll' ),
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .liquidpoll-option-single::before',
			'condition' => [ '_theme' => [ '6', '7', '8', '9' ] ],
		] );

		$this->add_control( '_results_type', [
			'label'     => esc_html__( 'Results Type', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'votes'      => esc_html__( 'Votes Count', 'wp-poll' ),
				'percentage' => esc_html__( 'Percentage', 'wp-poll' ),
			],
			'default'   => 'votes',
			'condition' => [ '_theme' => [ '1', '2', '3', '5', '8', '9', '10' ] ],
		] );

		$this->add_control( '_timer_type', [
			'label'     => esc_html__( 'Countdown Timer Type', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'regular'    => esc_html__( 'Regular', 'wp-poll' ),
				'with_votes' => esc_html__( 'Timer with votes count', 'wp-poll' ),
			],
			'default'   => 'regular',
			'condition' => [ '_theme' => [ '10', '11', '12' ] ],
		] );


		/**
		 * Poll Title
		 */
		$this->add_control( 'typography_poll_title', [
			'label'     => esc_html__( 'Poll Title', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_title_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-poll-title',
		] );

		$this->add_control( 'poll_title_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
		] );


		/**
		 * Poll Content
		 */
		$this->add_control( 'typography_poll_content', [
			'label'     => esc_html__( 'Poll Content', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_content_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-content',
		] );

		$this->add_control( 'poll_content_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-content' => 'color: {{VALUE}}', ],
		] );


		/**
		 * Poll Options
		 */
		$this->add_control( 'typography_poll_options', [
			'label'     => esc_html__( 'Poll Options', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_options_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-option-single label',
		] );

		$this->add_control( 'poll_options_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-option-single label' => 'color: {{VALUE}}', ],
		] );


		/**
		 * Countdown timer
		 */
		$this->add_control( 'typography_poll_countdown', [
			'label'     => esc_html__( 'Poll Count Down', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_countdown_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-countdown-timer > span',
		] );

		$this->add_control( 'poll_countdown_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-countdown-timer > span' => 'color: {{VALUE}}', ],
		] );


		/**
		 * Submit Button
		 */
		$this->add_control( 'typography_poll_btn_submit', [
			'label'     => esc_html__( 'Button  - Submit', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_btn_submit_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-submit-poll',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'poll_btn_submit_bg',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .liquidpoll-submit-poll',
		] );

		$this->add_control( 'poll_btn_submit_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-submit-poll' => 'color: {{VALUE}}', ],
		] );

		$this->add_control(
			'poll_btn_submit_margin',
			[
				'label'      => esc_html__( 'Margin', 'wp-poll' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .liquidpoll-submit-poll' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'poll_btn_submit_padding',
			[
				'label'      => esc_html__( 'Padding', 'wp-poll' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .liquidpoll-submit-poll' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'poll_btn_submit_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wp-poll' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .liquidpoll-submit-poll' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		/**
		 * Results Button
		 */
		$this->add_control( 'typography_poll_btn_results', [
			'label'     => esc_html__( 'Button  - View Results', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_btn_results_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-get-poll-results',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'poll_btn_results_bg',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .liquidpoll-get-poll-results',
		] );

		$this->add_control( 'poll_btn_results_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-get-poll-results' => 'color: {{VALUE}}', ],
		] );

		$this->add_control(
			'poll_btn_results_margin',
			[
				'label'      => esc_html__( 'Margin', 'wp-poll' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .liquidpoll-get-poll-results' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'poll_btn_results_padding',
			[
				'label'      => esc_html__( 'Padding', 'wp-poll' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .liquidpoll-get-poll-results' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'poll_btn_results_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wp-poll' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .liquidpoll-get-poll-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}
}