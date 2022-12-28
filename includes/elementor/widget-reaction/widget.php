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


class LIQUIDPOLL_Widget_reaction extends Widget_base {

	public function get_name() {
		return 'liquidpoll-widget-reaction';
	}

	public function get_title() {
		return esc_html__( 'Liquidpoll - Reaction', 'wp-poll' );
	}

	public function get_icon() {
		return 'eicon-facebook-like-box';
	}

	public function get_categories() {
		return array( 'liquidpoll_category' );
	}

	public function get_keywords() {
		return array( 'reaction', 'liquidpoll' );
	}

	public function get_style_depends() {
		return array( 'widget-reaction' );
	}

	public function get_script_depends() {
		return array( 'widget-reaction' );
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

		liquidpoll_get_template( 'widget-reaction/views/template-1.php', array(), '', LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/' );
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
			'separator' => 'before',
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
			'separator' => 'before',
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

		$options_reaction = array( '0' => esc_html__( 'Select a Reaction', 'wp-poll' ) );


		foreach ( get_posts( array( 'post_type' => 'poll', 'meta_key' => '_type', 'meta_value' => 'reaction', 'showposts' => - 1 ) ) as $post ) {
			$options_reaction[ $post->ID ] = $post->post_title;
		}

		$this->add_control( 'poll_id_reaction', [
			'label'     => esc_html__( 'Select Reaction', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => $options_reaction,
			'default'   => '0',
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

		$this->add_control( 'settings_reaction_hide_title', [
			'label'     => esc_html__( 'Hide title for this reaction.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
		] );

		$this->add_control( 'settings_reaction_hide_content', [
			'label'     => esc_html__( 'Hide content for this reaction.', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
		] );
	}


	protected function controls_for_style() {

		$this->add_control( '_theme_reaction', [
			'label'     => esc_html__( 'Reaction Theme', 'wp-poll' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => liquidpoll_calculate_themes( liquidpoll()->get_reaction_themes() ),
			'default'   => 1,
		] );
	}
}