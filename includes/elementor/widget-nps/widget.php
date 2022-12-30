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


class LIQUIDPOLL_Widget_nps extends LIQUIDPOLL_Widget_base {

	public function get_name() {
		return 'liquidpoll-widget-nps';
	}

	public function get_title() {
		return esc_html__( 'Liquidpoll - NPS', 'wp-poll' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return array( 'liquidpoll_category' );
	}

	public function get_keywords() {
		return array( 'nps', 'liquidpoll' );
	}

	public function get_style_depends() {
		return array( 'widget-nps' );
	}

	public function get_script_depends() {
		return array( 'widget-nps' );
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

		liquidpoll_get_template( 'widget-nps/views/template-1.php', array(), '', LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/' );
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
			'label' => esc_html__( 'Form', 'wp-poll' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
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

		$options_nps = array( '0' => esc_html__( 'Select a NPS', 'wp-poll' ) );

		foreach ( get_posts( array( 'post_type' => 'poll', 'meta_key' => '_type', 'meta_value' => 'nps', 'showposts' => - 1 ) ) as $post ) {
			$options_nps[ $post->ID ] = $post->post_title;
		}

		$this->add_control( 'poll_id_nps', [
			'label'   => esc_html__( 'Select NPS', 'wp-poll' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $options_nps,
			'default' => '0',
		] );

		$this->add_control( 'poll_content', [
			'label' => esc_html__( 'NPS Content', 'wp-poll' ),
			'type'  => Controls_Manager::TEXTAREA,
		] );

		$this->add_control( 'poll_settings', [
			'label'     => esc_html__( 'Settings', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'settings_hide_for_logged_out_users', [
			'label' => esc_html__( 'Hide for logged out users.', 'wp-poll' ),
			'type'  => Controls_Manager::SWITCHER,
		] );
	}


	protected function controls_for_style() {

		$this->add_control( '_theme_nps', [
			'label'   => esc_html__( 'NPS Theme', 'wp-poll' ),
			'type'    => Controls_Manager::SELECT,
			'options' => liquidpoll_calculate_themes( liquidpoll()->get_nps_themes() ),
			'default' => 1,
		] );

		$this->add_control( '_nps_lowest_marking_text', [
			'label' => esc_html__( 'Lowest Marking Text', 'wp-poll' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$this->add_control( '_nps_highest_marking_text', [
			'label' => esc_html__( 'Highest Marking Text', 'wp-poll' ),
			'type'  => Controls_Manager::TEXT,
		] );

		$this->add_control( '_nps_commentbox', [
			'label'   => esc_html__( 'Comment Box', 'wp-poll' ),
			'type'    => Controls_Manager::SELECT,
			'options' => [
				'disabled' => esc_html__( 'Disable', 'wp-poll' ),
				'enabled'  => esc_html__( 'Enable, Not Mandatory', 'wp-poll' ),
				'obvious'  => esc_html__( 'Enable, Mandatory', 'wp-poll' ),
			],
			'default' => 'enabled',
		] );


		$this->add_control( '_nps_label_styles', [
			'label'     => esc_html__( 'Label Styles', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );


		$this->start_controls_tabs( '_nps_label_styles_tabs', [ 'condition' => [ '_theme_nps' => [ '1', '2' ] ] ] );

		// Tab - normal
		$this->start_controls_tab( '_nps_label_styles_tabs_normal', [ 'label' => esc_html__( 'Normal', 'wp-poll' ), ] );

		$this->add_control( '_nps_label_styles_tabs_normal_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} ul.liquidpoll-nps-options li label' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => '_nps_label_styles_tabs_normal_bg',
			'types'     => [ 'gradient' ],
			'selector'  => '{{WRAPPER}} ul.liquidpoll-nps-options > li',
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => '_nps_label_styles_tabs_normal_border',
			'selector'  => '{{WRAPPER}} ul.liquidpoll-nps-options > li:hover',
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->end_controls_tab(); // tabs_normal


		// Tab - hover
		$this->start_controls_tab( '_nps_label_styles_tabs_hover', [ 'label' => esc_html__( 'Hover', 'wp-poll' ), ] );

		$this->add_control( '_nps_label_styles_tabs_hover_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li:hover label' => 'color: {{VALUE}}' ],
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => '_nps_label_styles_tabs_hover_bg',
			'types'     => [ 'gradient' ],
			'selector'  => '{{WRAPPER}} ul.liquidpoll-nps-options > li:hover',
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => '_nps_label_styles_tabs_hover_border',
			'selector'  => '{{WRAPPER}} ul.liquidpoll-nps-options > li:hover',
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->end_controls_tab(); // tabs_hover


		// Tab - active
		$this->start_controls_tab( '_nps_label_styles_tabs_active', [ 'label' => esc_html__( 'Active', 'wp-poll' ), ] );

		$this->add_control( '_nps_label_styles_tabs_active_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li.active label' => 'color: {{VALUE}}' ],
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => '_nps_label_styles_tabs_active_bg',
			'types'     => [ 'gradient' ],
			'selector'  => '{{WRAPPER}} ul.liquidpoll-nps-options > li.active',
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'      => '_nps_label_styles_tabs_active_border',
			'selector'  => '{{WRAPPER}} ul.liquidpoll-nps-options > li:hover',
			'condition' => [ '_theme_nps' => [ '1', '2' ] ],
		] );

		$this->end_controls_tab(); // tabs_active


		$this->end_controls_tabs();


		$this->add_control( '_nps_slider_color', [
			'label'     => esc_html__( 'Slider Color', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ '_theme_nps' => [ '3' ] ],
		] );

		$this->add_control( 'bar', [
			'label'     => esc_html__( 'Bar Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .rangeslider__fill' => 'background: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '3' ] ],
		] );

		$this->add_control( 'circle_indicator', [
			'label'     => esc_html__( 'Circle Indicator Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .rangeslider__handle' => 'background: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '3' ] ],
		] );

		$this->add_control( '_nps_progress_bar_colors', [
			'label'     => esc_html__( 'Progress Bar Color', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ '_theme_nps' => [ '4' ] ],
		] );

		$this->add_control( 'background', [
			'label'     => esc_html__( 'Bar Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} #nps_score .rs-path-color'    => 'border-color: {{VALUE}}',
				'{{WRAPPER}} #nps_score .rs-handle:before' => 'outline-color: {{VALUE}}',
			],
			'condition' => [ '_theme_nps' => [ '4' ] ],
		] );

		$this->add_control( 'indicator', [
			'label'     => esc_html__( 'Circle Indicator Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} #nps_score .rs-handle'        => 'border-right-color: {{VALUE}}',
				'{{WRAPPER}} #nps_score .rs-handle:before' => 'border-color: {{VALUE}}',
			],
			'condition' => [ '_theme_nps' => [ '4' ] ],
		] );


		$this->add_control( 'progress_bar_background_heading', [
			'label'     => esc_html__( 'Progress Bar Background', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ '_theme_nps' => [ '4' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'progress_bar_fill_color',
			'types'     => [ 'gradient' ],
			'selector'  => '{{WRAPPER}} #nps_score .rs-range-color',
			'condition' => [ '_theme_nps' => [ '4' ] ],
		] );

		$this->add_control( 'progress_ball_background_heading', [
			'label'     => esc_html__( 'Progress Ball Background', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ '_theme_nps' => [ '4' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'progress_ball_fill_color',
			'types'     => [ 'gradient' ],
			'selector'  => '{{WRAPPER}} #nps_score .rs-handle:after',
			'condition' => [ '_theme_nps' => [ '4' ] ],
		] );

		$this->add_control( 'labels_colors_normal', [
			'label'     => esc_html__( 'Label color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li > label > span:first-child' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '5' ] ],
		] );

		$this->add_control( 'active', [
			'label'     => esc_html__( 'Hover/Active Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .liquidpoll-nps-options li span.liquidpoll-nps-tooltip' => 'color: {{VALUE}}',
				'{{WRAPPER}} .liquidpoll-nps-options li::before'                     => 'background: {{VALUE}}',
			],
			'condition' => [ '_theme_nps' => [ '5' ] ],
		] );

		$this->add_control( 'selected_bg', [
			'label'     => esc_html__( 'Hover/Active Background', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .liquidpoll-nps-options li:hover > label > span:first-child'    => 'color: {{VALUE}}',
				'{{WRAPPER}} .liquidpoll-nps-options li.active > label > span:first-child'   => 'color: {{VALUE}}',
				'{{WRAPPER}} .liquidpoll-nps-options li:hover'                               => 'background: {{VALUE}}',
				'{{WRAPPER}} .liquidpoll-nps-options li.active'                              => 'background: {{VALUE}}',
				'{{WRAPPER}} .liquidpoll-nps-options li span.liquidpoll-nps-tooltip::before' => 'background: {{VALUE}}',
			],
			'condition' => [ '_theme_nps' => [ '5' ] ],
		] );

		$this->add_control( 'border', [
			'label'     => esc_html__( 'Wrapper Border', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .liquidpoll-nps-options li'               => 'border-color: {{VALUE}}',
				'{{WRAPPER}} ul.liquidpoll-nps-options li:first-child' => 'border-left-color: {{VALUE}}',
				'{{WRAPPER}} .liquidpoll-nps-options li:last-child'    => 'border-right-color: {{VALUE}}',
			],
			'condition' => [ '_theme_nps' => [ '5' ] ],
		] );

		$this->add_control( 'wrapper_bg', [
			'label'     => esc_html__( 'Wrapper Background', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li' => 'background: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '5' ] ],
		] );

		$this->add_control( '_nps_marking_text_colors', [
			'label'     => esc_html__( 'Marking Texts', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => '_nps_highest_marking_typography',
			'selector' => '{{WRAPPER}} .liquidpoll-nps-score-labels span',
		] );

		$this->add_control( 'lowest', [
			'label'     => esc_html__( 'Lowest text color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-score-labels span.lowest' => 'color: {{VALUE}}', ],
		] );

		$this->add_control( 'highest', [
			'label'     => esc_html__( 'Highest text color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-score-labels span.highest' => 'color: {{VALUE}}', ],
		] );

		$this->add_control( '_nps_comment_box_colors', [
			'label'     => esc_html__( 'Comment / Feedback Box', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Text color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-comment-box textarea' => 'color: {{VALUE}}', ],
		] );

		$this->add_control( 'bg_color', [
			'label'     => esc_html__( 'Background color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-comment-box textarea' => 'background: {{VALUE}}', ],
		] );

		$this->add_control( 'border_color', [
			'label'     => esc_html__( 'Border color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-comment-box textarea' => 'border-color: {{VALUE}}', ],
		] );


		/**
		 * Poll Title
		 */
		$this->add_control( 'typography_poll_title', [
			'label'     => esc_html__( 'NPS Title', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'poll_title_typography',
			'selector' => '{{WRAPPER}} .liquidpoll-poll-title',
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
			'label'     => esc_html__( 'NPS Content', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'poll_content_typography',
			'selector' => '{{WRAPPER}} .liquidpoll-content',

		] );

		$this->add_control( 'poll_content_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-content' => 'color: {{VALUE}}', ],

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
			'name'     => 'poll_btn_submit_typography',
			'selector' => '{{WRAPPER}} .liquidpoll-submit-poll',
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'     => 'poll_btn_submit_bg',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .liquidpoll-submit-poll',
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
	}
}