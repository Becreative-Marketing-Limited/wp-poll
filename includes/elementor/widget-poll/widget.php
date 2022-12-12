<?php
/**
 * Widget - Main
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use WPDK\Utils;


class LIQUIDPOLL_Widget_poll extends Widget_base {

	public function get_name() {
		return 'liquidpoll-widget-poll';
	}

	public function get_title() {
		return esc_html__( 'Liquidpoll - Widget Poll', 'wp-poll' );
	}

	public function get_icon() {
		return 'fa fa-heart';
	}

	public function get_categories() {
		return array( 'liquidpoll_category' );
	}

	public function get_keywords() {
		return array( 'poll', 'liquidpoll' );
	}

	public function get_style_depends() {
		return array( 'widget-poll', 'rangeslider', 'roundslider' );
	}

	public function get_script_depends() {
		return array( 'widget-poll', 'rangeslider', 'roundslider' );
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
			'condition' => [ '_type' => [ 'poll' ] ],
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
	}


	protected function controls_for_content_form() {

		$this->add_control( 'poll_form_enable', [
			'label' => esc_html__( 'Enable Form', 'wp-poll' ),
			'type'  => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'poll_form_fields', [
			'label'     => esc_html__( 'Form Fields', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'poll_form_enable' => 'yes', ],
			'separator' => 'after',
		] );

		$this->add_control( 'poll_form_label_first_name', [
			'label'     => esc_html__( 'First Name Label', 'wp-poll' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_label_email', [
			'label'     => esc_html__( 'Email Address Label', 'wp-poll' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'enable_last_name', [
			'label'     => esc_html__( 'Enable Last Name', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_label_last_name', [
			'label'     => esc_html__( 'Last Name Label', 'wp-poll' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [ 'poll_form_enable' => 'yes', 'enable_last_name' => 'yes' ],
		] );

		$this->add_control( 'poll_form_label_button', [
			'label'     => esc_html__( 'Submit Button Label', 'wp-poll' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_content', [
			'label'     => esc_html__( 'Form Content', 'wp-poll' ),
			'type'      => Controls_Manager::TEXTAREA,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_notice', [
			'label'     => esc_html__( 'Form Notice', 'wp-poll' ),
			'type'      => Controls_Manager::TEXTAREA,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'form_styling', [
			'label'     => esc_html__( 'Form Styling', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [ 'poll_form_enable' => 'yes', ],
			'separator' => 'after',
		] );

		$this->add_control( 'poll_form_style_colors', [
			'label'     => esc_html__( 'Colors', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'form_bg', [
			'label'     => esc_html__( 'Form Bg', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'poll_form_enable' => 'yes', 'poll_form_style_colors' => 'yes' ],
		] );

		$this->add_control( 'content', [
			'label'     => esc_html__( 'Content', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'poll_form_enable' => 'yes', 'poll_form_style_colors' => 'yes' ],
		] );

		$this->add_control( 'button_normal', [
			'label'     => esc_html__( 'Button', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'poll_form_enable' => 'yes', 'poll_form_style_colors' => 'yes' ],
		] );

		$this->add_control( 'button_bg', [
			'label'     => esc_html__( 'Button Bg', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'poll_form_enable' => 'yes', 'poll_form_style_colors' => 'yes' ],
		] );

		$this->add_control( 'button_bg_hover', [
			'label'     => esc_html__( 'Button Bg (Hover))', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'condition' => [ 'poll_form_enable' => 'yes', 'poll_form_style_colors' => 'yes' ],
		] );
	}


	protected function controls_for_content_settings() {

		$options    = array();
		$poll_types = array(
			'poll'     => esc_html__( 'Poll', 'wp-poll' ),
			'reaction' => esc_html__( 'Reaction', 'wp-poll' ),
			'nps'      => esc_html__( 'NPS', 'wp-poll' ),
		);

		foreach ( get_posts( array( 'post_type' => 'poll', 'showposts' => - 1 ) ) as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		if ( ! liquidpoll()->is_pro() ) {
			unset( $poll_types['reaction'] );
		}

		$this->add_control( 'poll_id', [
			'label'   => esc_html__( 'Select Poll', 'wp-poll' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $options,
		] );

		$this->add_control( '_type', [
			'label'   => esc_html__( 'Poll Type', 'wp-poll' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'poll',
			'options' => $poll_types,
		] );

		$this->add_control( 'poll_content', [
			'label'     => esc_html__( 'Poll Content', 'wp-poll' ),
			'type'      => Controls_Manager::TEXTAREA,
			'condition' => [ '_type' => [ 'poll', 'nps' ] ],
		] );

		$this->add_control( '_deadline', [
			'label'          => esc_html__( 'Deadline', 'wp-poll' ),
			'type'           => Controls_Manager::DATE_TIME,
			'label_block'    => false,
			'picker_options' => [
				'enableTime' => false,
			],
			'condition'      => [ '_type' => 'poll', ],
		] );

		$this->add_control( '_countdown_position', [
			'label'     => esc_html__( 'Countdown Position', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'below_options' => esc_html__( 'Below options', 'wp-poll' ),
				'above_options' => esc_html__( 'Above options (Pro)', 'wp-poll' ),
			],
			'default'   => 'below_options',
			'condition' => [ '_type' => 'poll', ],
		] );

		$this->add_control( 'poll_settings', [
			'label'     => esc_html__( 'Settings', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
		] );

		$this->add_control( 'settings_hide_for_logged_out_users', [
			'label'     => esc_html__( 'Hide for logged out users.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ '_type' => [ 'poll', 'reaction', 'nps' ] ],
		] );

		$this->add_control( 'settings_vote_after_deadline', [
			'label'     => esc_html__( 'Allow users to vote after deadline.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_control( 'settings_hide_timer', [
			'label'     => esc_html__( 'Hide countdown timer for this poll.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_control( 'settings_poll_view_results_to_voted_users_only', [
			'label'     => esc_html__( 'View results to voted users only.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_control( 'settings_reaction_hide_title', [
			'label'     => esc_html__( 'Hide title for this reaction.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ '_type' => [ 'reaction' ] ],
		] );

		$this->add_control( 'settings_reaction_hide_content', [
			'label'     => esc_html__( 'Hide content for this reaction.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ '_type' => [ 'reaction' ] ],
		] );
	}


	protected function calculate_themes( $themes ) {

		$calculated_themes = array();

		foreach ( $themes as $theme_id => $theme ) {

			$availability = Utils::get_args_option( 'availability', $theme );
			$theme_label  = Utils::get_args_option( 'label', $theme );

			if ( 'pro' == $availability && ! liquidpoll()->is_pro() ) {
				$calculated_themes[998] = esc_html__( '7+ are in pro', 'wp-poll' );
				continue;
			}

			$calculated_themes[ $theme_id ] = $theme_label;
		}

		return $calculated_themes;
	}


	protected function controls_for_style() {

		$this->add_control( '_theme', [
			'label'     => esc_html__( 'Poll Theme', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => $this->calculate_themes( liquidpoll()->get_poll_themes() ),
			'default'   => 1,
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_control( '_theme_nps', [
			'label'     => esc_html__( 'NPS Theme', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => $this->calculate_themes( liquidpoll()->get_nps_themes() ),
			'default'   => 1,
			'condition' => [ '_type' => [ 'nps' ] ],
		] );

		$this->add_control( '_theme_reaction', [
			'label'     => esc_html__( 'Reaction Theme', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => $this->calculate_themes( liquidpoll()->get_reaction_themes() ),
			'default'   => 1,
			'condition' => [ '_type' => [ 'reaction' ] ],
		] );

		$this->add_control( '_results_type', [
			'label'     => esc_html__( 'Results Type', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'votes'      => esc_html__( 'Votes Count', 'wp-poll' ),
				'percentage' => esc_html__( 'Percentage', 'wp-poll' ),
			],
			'default'   => 'votes',
			'condition' => [ '_theme' => [ '1', '2', '3', '5', '8', '9', '10' ], '_type' => 'poll' ],
		] );

		$this->add_control( '_timer_type', [
			'label'     => esc_html__( 'Countdown Timer Type', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'regular'    => esc_html__( 'Regular', 'wp-poll' ),
				'with_votes' => esc_html__( 'Timer with votes count', 'wp-poll' ),
			],
			'default'   => 'regular',
			'condition' => [ '_theme' => [ '10', '11', '12' ], '_type' => 'poll' ],
		] );

		$this->add_control( '_nps_lowest_marking_text', [
			'label'     => esc_html__( 'Lowest Marking Text', 'wp-poll' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [ '_type' => 'nps' ],
		] );

		$this->add_control( '_nps_highest_marking_text', [
			'label'     => esc_html__( 'Highest Marking Text', 'wp-poll' ),
			'type'      => Controls_Manager::TEXT,
			'condition' => [ '_type' => 'nps' ],
		] );

		$this->add_control( '_nps_commentbox', [
			'label'     => esc_html__( 'Comment Box', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'disabled' => esc_html__( 'Disable', 'wp-poll' ),
				'enabled'  => esc_html__( 'Enable, Not Mandatory', 'wp-poll' ),
				'obvious'  => esc_html__( 'Enable, Mandatory', 'wp-poll' ),
			],
			'default'   => 'enabled',
			'condition' => [ '_type' => 'nps' ],
		] );

		$this->add_control( '_nps_labels_color', [
			'label'     => esc_html__( 'Labels Color', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_theme_nps' => [ '1', '2' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'labels_color_normal', [
			'label'     => esc_html__( 'Normal Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} ul.liquidpoll-nps-options li label' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '1', '2' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'hover_active', [
			'label'     => esc_html__( 'Hover/Active Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .liquidpoll-nps-options li.active label' => 'color: {{VALUE}}',
				'{{WRAPPER}} .liquidpoll-nps-options li:hover label'  => 'color: {{VALUE}}',
			],
			'condition' => [ '_theme_nps' => [ '1', '2' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( '_nps_labels_colors', [
			'label'     => esc_html__( 'Labels Color', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_theme_nps' => [ '5' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'labels_colors_normal', [
			'label'     => esc_html__( 'Label color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li > label > span:first-child' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '5' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'active', [
			'label'     => esc_html__( 'Hover/Active color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li span.liquidpoll-nps-tooltip' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '5' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'selected_bg', [
			'label'     => esc_html__( 'Selected background color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li:hover, ul.liquidpoll-nps-options li.active, ul.liquidpoll-nps-options li span.liquidpoll-nps-tooltip::before' => 'background: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '5' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'border', [
			'label'     => esc_html__( 'Wrapper border', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '5' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'wrapper_bg', [
			'label'     => esc_html__( 'Wrapper background', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '5' ], '_type' => [ 'nps' ] ],
		] );


		$this->add_control( '_nps_labels_bg_color', [
			'label'     => esc_html__( 'Labels Background Color', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_theme_nps' => [ '1', '2' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'normal_1', [
			'label'     => esc_html__( 'Normal Color 1', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li' => 'background: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '1', '2' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'normal_2', [
			'label'     => esc_html__( 'Normal Color 2', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-options li' => 'background: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '1', '2' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'hover_active_1', [
			'label'     => esc_html__( 'Hover/Active Color 1', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '1', '2' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'hover_active_2', [
			'label'     => esc_html__( 'Hover/Active 2', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '1', '2' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( '_nps_slider_color', [
			'label'     => esc_html__( 'Slider Color', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_theme_nps' => [ '3' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'bar', [
			'label'     => esc_html__( 'Bar Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .rangeslider__fill' => 'background: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '3' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'circle_indicator', [
			'label'     => esc_html__( 'Circle Indicator Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .rangeslider__handle' => 'background: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '3' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( '_nps_progress_bar_colors', [
			'label'     => esc_html__( 'Progress Bar Color', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_theme_nps' => [ '4' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'background', [
			'label'     => esc_html__( 'Bar Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .rs-path-color' => 'border-color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '4' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'indicator', [
			'label'     => esc_html__( 'Circle Indicator Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} #nps_score .rs-handle' => 'border-right-color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '4' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'bar_fill_1', [
			'label'     => esc_html__( 'Bar Active Color 1', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '4' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'bar_fill_2', [
			'label'     => esc_html__( 'Bar Active Color 2', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '4' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'bar_fill_3', [
			'label'     => esc_html__( 'Bar Active Color 3', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
			'condition' => [ '_theme_nps' => [ '4' ], '_type' => [ 'nps' ] ],
		] );

		$this->add_control( '_nps_marking_text_colors', [
			'label'     => esc_html__( 'Marking Texts', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'lowest', [
			'label'     => esc_html__( 'Lowest text color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-score-labels span.lowest' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'highest', [
			'label'     => esc_html__( 'Highest text color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-nps-score-labels span.highest' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'nps' ] ],
		] );

		$this->add_control( '_nps_comment_box_colors', [
			'label'     => esc_html__( 'Comment / Feedback Box', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'text_color', [
			'label'     => esc_html__( 'Text color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-comment-box textarea' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'bg_color', [
			'label'     => esc_html__( 'Background color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-comment-box textarea' => 'background: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'nps' ] ],
		] );

		$this->add_control( 'border_color', [
			'label'     => esc_html__( 'Border color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-comment-box textarea' => 'border-color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'nps' ] ],
		] );


		/**
		 * Poll Title
		 */
		$this->add_control( 'typography_poll_title', [
			'label'     => esc_html__( 'Poll Title', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_type' => [ 'poll', 'nps' ] ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_title_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-poll-title',
			'condition' => [ '_type' => [ 'poll', 'nps' ] ],
		] );

		$this->add_control( 'poll_title_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-poll-title' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'poll', 'nps' ] ],
		] );


		/**
		 * Poll Content
		 */
		$this->add_control( 'typography_poll_content', [
			'label'     => esc_html__( 'Poll Content', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_type' => [ 'poll', 'nps' ] ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_content_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-content',
			'condition' => [ '_type' => [ 'poll', 'nps' ] ],
		] );

		$this->add_control( 'poll_content_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-content' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'poll', 'nps' ] ],
		] );


		/**
		 * Poll Options
		 */
		$this->add_control( 'typography_poll_options', [
			'label'     => esc_html__( 'Poll Options', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_options_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-option-single label',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_control( 'poll_options_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-option-single label' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'poll' ] ],
		] );


		/**
		 * Countdown timer
		 */
		$this->add_control( 'typography_poll_countdown', [
			'label'     => esc_html__( 'Poll Count Down', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_countdown_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-countdown-timer > span',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_control( 'poll_countdown_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-countdown-timer > span' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'poll' ] ],
		] );


		/**
		 * Submit Button
		 */
		$this->add_control( 'typography_poll_btn_submit', [
			'label'     => esc_html__( 'Button  - Submit', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_btn_submit_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-submit-poll',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'poll_btn_submit_bg',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .liquidpoll-get-poll-results',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_control( 'poll_btn_submit_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-submit-poll' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'poll' ] ],
		] );


		/**
		 * Results Button
		 */
		$this->add_control( 'typography_poll_btn_results', [
			'label'     => esc_html__( 'Button  - View Results', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'after',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_btn_results_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-get-poll-results',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'poll_btn_results_bg',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .liquidpoll-get-poll-results',
			'condition' => [ '_type' => [ 'poll' ] ],
		] );

		$this->add_control( 'poll_btn_results_color', [
			'label'     => esc_html__( 'Text Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-get-poll-results' => 'color: {{VALUE}}', ],
			'condition' => [ '_type' => [ 'poll' ] ],
		] );
	}
}