<?php
/**
 * Widget - Main
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Widget_Base;


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

		global $liquidpoll_widget_settings;

		$liquidpoll_widget_settings           = $this->get_settings_for_display();
		$liquidpoll_widget_settings['widget'] = $this->get_name();

		liquidpoll_get_template( 'widget-poll/views/template-1.php', array(), '', LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/' );
	}


	/**
	 * Register content related controls
	 */
	protected function register_controls() {

		/**
		 * Main Tab - Content
		 */
		$this->start_controls_section( 'section_content', array(
			'label' => esc_html__( 'Settings', 'wp-poll' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		) );
		$this->controls_for_content();
		$this->end_controls_section();


		/**
		 * Main Tab - Style
		 */
		$this->start_controls_section(
			'section_infobox_style',
			[
				'label' => esc_html__( 'Global Style', 'wp-poll' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->controls_for_style();
		$this->end_controls_section();
	}


	protected function controls_for_content() {

		$options = array();

		foreach ( get_posts( array( 'post_type' => 'poll', 'showposts' => - 1 ) ) as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}

		$this->add_control( 'poll_id', array(
			'label'   => esc_html__( 'Select Poll', 'wp-poll' ),
			'type'    => Controls_Manager::SELECT,
			'options' => $options,
		) );
	}


	protected function controls_for_style() {

		$this->add_control(
			'primary_color',
			[
				'label'  => esc_html__( 'Primary Color', 'element-path' ),
				'type'   => Controls_Manager::COLOR,
				'scheme' => [
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				],
			]
		);
	}
}