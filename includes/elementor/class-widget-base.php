<?php
/**
 * Elementor Support
 */


use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;


class LIQUIDPOLL_Widget_base extends Widget_Base {

	public function get_name() {
		// TODO: Implement get_name() method.
	}


	public function controls_for_content_form() {

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
	}


	public function controls_for_style_form() {

		$this->add_control( 'poll_form_preview', [
			'label'     => esc_html__( 'Enable Preview', 'wp-poll' ),
			'type'      => Controls_Manager::SWITCHER,
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'poll_form_background',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .liquidpoll-form',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_content_heading', [
			'label'     => esc_html__( 'Form Content', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_form_content_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-form-content',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_content_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-form-content' => 'color: {{VALUE}}', ],
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_field_heading', [
			'label'     => esc_html__( 'Form Fields', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_form_field_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-form-field input',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_field_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-form-field input' => 'color: {{VALUE}}', ],
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_notice_heading', [
			'label'     => esc_html__( 'Form Notice', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_form_notice_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-form-notice .notice',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_notice_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} ..liquidpoll-form-notice .notice' => 'color: {{VALUE}}', ],
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_submit_heading', [
			'label'     => esc_html__( 'Submit Button', 'wp-poll' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'      => 'poll_form_submit_typography',
			'selector'  => '{{WRAPPER}} .liquidpoll-form-field .liquidpoll-button',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_group_control( Group_Control_Background::get_type(), [
			'name'      => 'poll_form_submit_background',
			'types'     => [ 'classic', 'gradient' ],
			'selector'  => '{{WRAPPER}} .liquidpoll-form-field .liquidpoll-button',
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_submit_color', [
			'label'     => esc_html__( 'Color', 'wp-poll' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}} .liquidpoll-form-field .liquidpoll-button' => 'color: {{VALUE}}', ],
			'condition' => [ 'poll_form_enable' => 'yes', ],
		] );


		$this->add_control( 'poll_form_submit_margin', [
			'label'      => esc_html__( 'Margin', 'wp-poll' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .liquidpoll-form-field .liquidpoll-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', ],
			'condition'  => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_submit_padding', [
			'label'      => esc_html__( 'Padding', 'wp-poll' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .liquidpoll-form-field .liquidpoll-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', ],
			'condition'  => [ 'poll_form_enable' => 'yes', ],
		] );

		$this->add_control( 'poll_form_submit_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'wp-poll' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors'  => [ '{{WRAPPER}} .liquidpoll-form-field .liquidpoll-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};', ],
			'condition'  => [ 'poll_form_enable' => 'yes', ],
		] );
	}
}