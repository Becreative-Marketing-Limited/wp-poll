<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

class LIQUIDPOLL_Poll_meta {
	/**
	 * LIQUIDPOLL_Poll_meta constructor.
	 */
	public function __construct() {
		$this->generate_poll_meta_box();
	}


	/**
	 * Generate poll meta box
	 */
	public function generate_poll_meta_box() {

		$prefix = 'liquidpoll_poll_meta';

		WPDK_Settings::createMetabox( $prefix,
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
			WPDK_Settings::createSection( $prefix, $section );
		}
	}


	/**
	 * Return poll meta fields
	 *
	 * @return mixed|void
	 */
	public function get_meta_field_sections() {

		global $wpdb;

		$poll_setting_fields   = array(
			array(
				'id'         => 'settings_hide_for_logged_out_users',
				'title'      => esc_html__( 'Settings', 'wp-poll' ),
				'label'      => esc_html__( 'Hide for logged out users.', 'wp-poll' ),
				'type'       => 'switcher',
				'default'    => false,
				'dependency' => array( '_type', '!=', 'reviews', 'all' ),
			),
			array(
				'id'         => 'settings_vote_after_deadline',
				'title'      => ' ',
				'label'      => esc_html__( 'Allow users to vote after deadline.', 'wp-poll' ),
				'type'       => 'switcher',
				'class'      => 'padding-top-none',
				'dependency' => array( '_type', '==', 'poll', 'all' ),
			),
			array(
				'id'         => 'settings_hide_timer',
				'title'      => ' ',
				'label'      => esc_html__( 'Hide countdown timer for this poll.', 'wp-poll' ),
				'type'       => 'switcher',
				'class'      => 'padding-top-none',
				'dependency' => array( '_type', '==', 'poll', 'all' ),
			),
			array(
				'id'         => 'settings_poll_view_results_to_voted_users_only',
				'title'      => ' ',
				'label'      => esc_html__( 'View results to voted users only.', 'wp-poll' ),
				'type'       => 'switcher',
				'default'    => false,
				'class'      => 'padding-top-none',
				'dependency' => array( '_type', '==', 'poll', 'all' ),
			),
			array(
				'id'         => 'settings_reaction_hide_title',
				'title'      => ' ',
				'label'      => esc_html__( 'Hide title for this reaction.', 'wp-poll' ),
				'type'       => 'switcher',
				'class'      => 'padding-top-none',
				'default'    => true,
				'dependency' => array( '_type', '==', 'reaction', 'all' ),
			),
			array(
				'id'         => 'settings_reaction_hide_content',
				'title'      => ' ',
				'label'      => esc_html__( 'Hide content for this reaction.', 'wp-poll' ),
				'type'       => 'switcher',
				'default'    => true,
				'class'      => 'padding-top-none',
				'dependency' => array( '_type', '==', 'reaction', 'all' ),
			),
			array(
				'id'         => 'settings_allow_guest_review_mode',
				'title'      => esc_html__( 'Settings', 'wp-poll' ),
				'label'      => esc_html__( 'Guest Review mode.', 'wp-poll' ),
				'desc'       => esc_html__( 'Allow anyone to leave a review without having to create an account.', 'wp-poll' ),
				'type'       => 'switcher',
				'default'    => false,
				'dependency' => array( '_type', '==', 'reviews', 'all' ),
			),
		);
		$poll_setting_fields   = apply_filters( 'LiquidPoll/Filters/poll_setting_fields', $poll_setting_fields );
		$post_selection_fields = array();
		$registered_post_types = $wpdb->get_results( "SELECT DISTINCT( post_type ) FROM {$wpdb->posts}", ARRAY_A );

		foreach ( $registered_post_types as $post_type ) {

			$post_type   = Utils::get_args_option( 'post_type', $post_type );
			$all_options = array();
			$skip_types  = array(
				'revision',
				'nav_menu_item',
				'custom_css',
				'customize_changeset',
				'oembed_cache',
				'user_request',
				'wp_block',
				'wp_template',
				'wp_template_part',
				'wp_global_styles',
				'wp_navigation',
			);

			if ( in_array( $post_type, $skip_types ) ) {
				continue;
			}

			foreach ( get_posts( "post_type={$post_type}&posts_per_page=-1" ) as $_post ) {
				$all_options[ $_post->ID ] = $_post->post_title;
			}

			$post_selection_fields[] = array(
				'id'          => '_reaction_post_id_' . $post_type,
				'title'       => sprintf( esc_html__( 'Select %s', 'wp-poll' ), ucfirst( $post_type ) ),
				'type'        => 'select',
				'placeholder' => sprintf( esc_html__( 'Select a %s', 'wp-poll' ), ucfirst( $post_type ) ),
				'options'     => $all_options,
				'ajax'        => true,
				'chosen'      => true,
				'dependency'  => array( '_type|_reaction_position|_reaction_post_type', '==|any|==', 'reaction|below_content,above_content|' . $post_type, 'all' ),
			);
		}

		$field_sections['general_settings'] = array(
			'title'  => __( 'Settings', 'wp-poll' ),
			'icon'   => 'fa fa-cog',
			'fields' => array_merge( array(
				array(
					'id'      => '_type',
					'title'   => esc_html__( 'Poll type', 'wp-poll' ),
					'type'    => 'button_set',
					'options' => array(
						'poll'     => array(
							'label' => esc_html__( 'Poll', 'wp-poll' )
						),
						'nps'      => array(
							'label' => esc_html__( 'NPS Score', 'wp-poll' ),
						),
						'reaction' => array(
							'label'        => esc_html__( 'Reaction', 'wp-poll' ),
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
						'reviews'  => array(
							'label'        => esc_html__( 'Reviews', 'wp-poll' ),
							'availability' => liquidpoll()->is_pro() ? '' : 'pro',
						),
					),
					'default' => 'poll',
				),
				array(
					'id'         => '_content',
					'title'      => esc_html__( 'Poll Content', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Description about this poll', 'wp-poll' ),
					'type'       => 'wp_editor',
					'height'     => '150px',
					'dependency' => array( '_type', 'any', 'poll,nps', 'all' ),
				),
				array(
					'id'            => '_deadline',
					'title'         => esc_html__( 'Deadline', 'wp-poll' ),
					'subtitle'      => esc_html__( 'Specify a date when this poll will end. Leave empty to ignore this option', 'wp-poll' ),
					'type'          => 'date',
					'autocomplete'  => 'off',
					'placeholder'   => gmdate( 'Y-m-d' ),
					'field_options' => array(
						'dateFormat' => 'yy-mm-dd',
					),
					'dependency'    => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'         => '_countdown_position',
					'title'      => esc_html__( 'Countdown Position', 'wp-poll' ),
					'type'       => 'select',
					'options'    => array(
						'below_options' => array( 'label' => esc_html__( 'Below options', 'wp-poll' ) ),
						'above_options' => array( 'label' => esc_html__( 'Above options (Pro)', 'wp-poll' ), 'availability' => liquidpoll()->is_pro(), ),
					),
					'default'    => 'below_options',
					'dependency' => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'         => '_reaction_position',
					'title'      => esc_html__( 'Reaction Position', 'wp-poll' ),
					'type'       => 'select',
					'options'    => array(
						'shortcode'     => array( 'label' => esc_html__( 'Shortcode', 'wp-poll' ), 'availability' => liquidpoll()->is_pro(), ),
						'below_content' => array( 'label' => esc_html__( 'Below Content', 'wp-poll' ) ),
						'above_content' => array( 'label' => esc_html__( 'Above Content', 'wp-poll' ), 'availability' => liquidpoll()->is_pro(), ),
						'floating'      => array( 'label' => esc_html__( 'Floating Position', 'wp-poll' ), 'availability' => liquidpoll()->is_pro(), ),
					),
					'dependency' => array( '_type', '==', 'reaction', 'all' ),
				),
				array(
					'id'          => '_reaction_post_type',
					'title'       => esc_html__( 'Post Type', 'wp-poll' ),
					'type'        => 'select',
					'placeholder' => esc_html__( 'Select post type', 'wp-poll' ),
					'options'     => 'post_types',
					'dependency'  => array(
						'_type|_reaction_position',
						'==|any',
						'reaction|below_content,above_content',
						'all'
					),
				),
				array(
					'id'         => '_reaction_floating_position',
					'title'      => esc_html__( 'Floating Position', 'wp-poll' ),
					'type'       => 'spacing',
					'dependency' => array( '_type|_reaction_position', '==|==', 'reaction|floating', 'all' ),
				),
				array(
					'id'         => 'reviews_service_logo',
					'title'      => esc_html__( 'Service Logo', 'wp-poll' ),
					'type'       => 'media',
					'url'        => false,
					'dependency' => array( '_type', '==', 'reviews', 'all' ),
				),
				array(
					'id'         => 'reviews_service_name',
					'title'      => esc_html__( 'Service Name', 'wp-poll' ),
					'type'       => 'text',
					'dependency' => array( '_type', '==', 'reviews', 'all' ),
				),
				array(
					'id'         => 'reviews_service_url',
					'title'      => esc_html__( 'Service URL', 'wp-poll' ),
					'type'       => 'text',
					'dependency' => array( '_type', '==', 'reviews', 'all' ),
				),
				array(
					'id'         => 'is_consent_required',
					'title'      => esc_html__( 'Require Consent', 'wp-poll' ),
					'type'       => 'switcher',
					'default'    => false,
					'dependency' => array( '_type', '==', 'reviews', 'all' ),
				),
				array(
					'id'         => 'reviews_consent_desc',
					'title'      => esc_html__( 'Consent Description', 'wp-poll' ),
					'type'       => 'textarea',
					'attributes' => array(
						'rows'  => '4',
						'cols'  => '75',
						'style' => 'width:auto;min-height: auto;',
					),
					'dependency' => array( '_type', '==', 'reviews', 'all' ),
				),
				array(
					'id'         => 'reviews_report_read_more_url',
					'title'      => esc_html__( 'Read more URL', 'wp-poll' ),
					'desc'       => esc_html__( 'URL for Read more on the report modal', 'wp-poll' ),
					'type'       => 'text',
					'dependency' => array( '_type', '==', 'reviews', 'all' ),
				),
				array(
					'id'              => 'reviews_report_reason',
					'title'           => esc_html__( 'Report Reason', 'wp-poll' ),
					'desc'            => esc_html__( 'Report reasons for the report modal', 'wp-poll' ),
					'type'            => 'repeater',
					'button_title'    => esc_html__( 'Add option', 'wp-poll' ),
					'disable_actions' => array( 'clone' ),
					'fields'          => array(
						array(
							'id'    => 'reason',
							'title' => esc_html__( 'Reason', 'wp-poll' ),
							'type'  => 'text',
						),
					),
					'dependency'      => array( '_type', '==', 'reviews', 'all' ),
					'default'         => array(
						array(
							'reason' => esc_html__( 'Harmful or illegal', 'wp-poll' ),
						),
						array(
							'reason' => esc_html__( 'Personal information', 'wp-poll' ),
						),
						array(
							'reason' => esc_html__( 'Advertising or promotional', 'wp-poll' ),
						),
						array(
							'reason' => esc_html__( 'Not based on a genuine experience', 'wp-poll' ),
						),

					),
				),
				array(
					'id'           => 'reviews_feature_image',
					'title'        => esc_html__( 'Feature Image for Social Sharing', 'wp-poll' ),
					'subtitle'     => esc_html__( 'This image will apply to all your reviews when they are been shared on social media.', 'wp-poll' ),
					'desc'         => esc_html__( 'Use 1200x630 pixels image size for social sharing', 'wp-poll-pro' ),
					'type'         => 'media',
					'preview_size' => 'full',
					'library'      => 'image',
					'url'          => false,
				),
			), $post_selection_fields, $poll_setting_fields )
		);

		$field_sections['reviews_list'] = array(
			'id'       => 'reviews_approval_page',
			'title'    => __( 'Reviews', 'wp-poll' ),
			'icon'     => 'fas fa-star',
			'external' => true,
		);

		$field_sections['poll_options'] = array(
			'title'  => __( 'Options', 'wp-poll' ),
			'icon'   => 'fa fa-th-large',
			'fields' => array(
				array(
					'id'         => 'notice_form',
					'type'       => 'notice',
					'style'      => 'danger',
					'content'    => esc_html__( 'Options is not available for review type.', 'wp-poll' ),
					'dependency' => array( '_type', 'not-any', 'poll,nps,reaction', 'all' ),
				),
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
					),
					'dependency'      => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'              => 'poll_meta_options_nps',
					'title'           => esc_html__( 'Options (NPS)', 'wp-poll' ),
					'label'           => esc_html__( 'Add nps options here. You can skip using media if you do not need this.', 'wp-poll' ),
					'type'            => 'repeater',
					'button_title'    => esc_html__( 'Add option', 'wp-poll' ),
					'disable_actions' => array( 'clone' ),
					'fields'          => array(
						array(
							'id'    => 'label',
							'title' => esc_html__( 'Label', 'wp-poll' ),
							'type'  => 'text',
						),
					),
					'max'             => 10,
					'default'         => array(
						array(
							'label' => esc_html__( '1', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '2', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '3', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '4', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '5', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '6', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '7', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '8', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '9', 'wp-poll' ),
						),
						array(
							'label' => esc_html__( '10', 'wp-poll' ),
						),
					),
					'dependency'      => array( '_type', '==', 'nps', 'all' ),
				),
				array(
					'id'         => 'poll_meta_options_reaction',
					'title'      => esc_html__( 'Options (Reaction)', 'wp-poll' ),
					'label'      => esc_html__( 'Add reactions here. You can skip using media if you do not need this.', 'wp-poll' ),
					'desc'       => esc_html__( 'Drag items to set the ordering. Items that are not selected are automatically placed at the end.', 'wp-poll' ),
					'type'       => 'image_select_sortable',
					'multiple'   => true,
					'class'      => 'liquidpoll-reaction-options',
					'options'    => $this->get_reaction_emojis(),
					'dependency' => array( '_type', '==', 'reaction', 'all' ),
				),
			),
			array(
				'id'    => 'hide_option_labels',
				'title' => esc_html__( 'Hide labels', 'wp-poll' ),
				'label' => esc_html__( 'Hide labels of all options.', 'wp-poll' ),
				'type'  => 'switcher',
			),
		);

		$field_sections['poll_form'] = array(
			'title'  => __( 'Form', 'wp-poll' ),
			'icon'   => 'fa fa-check-square',
			'fields' => array(
				array(
					'id'         => 'notice_form',
					'type'       => 'notice',
					'style'      => 'danger',
					'content'    => esc_html__( 'Form is not available for review type.', 'wp-poll' ),
					'dependency' => array( '_type', 'not-any', 'poll,nps,reaction', 'all' ),
				),
				array(
					'id'         => 'poll_form_enable',
					'title'      => esc_html__( 'Enable Form', 'wp-poll' ),
					'label'      => esc_html__( 'Enable email collection form.', 'wp-poll' ),
					'type'       => 'switcher',
					'default'    => false,
					'dependency' => array( '_type', 'any', 'poll,nps,reaction', 'all' ),
				),
				array(
					'id'         => 'poll_external_form_enable',
					'title'      => esc_html__( 'External Form', 'wp-poll' ),
					'label'      => esc_html__( 'Enable external email collection form.', 'wp-poll' ),
					'type'       => 'switcher',
					'default'    => false,
					'dependency' => array( '_type|poll_form_enable', 'any|==', 'poll,nps,reaction|true', 'all' ),
				),
				array(
					'id'          => 'external_form_shortcode_field',
					'title'       => ' ',
					'type'        => 'textarea',
					'placeholder' => '[INSERT_FORM_SHORTCODE_HERE]',
					'attributes'  => array(
						'rows'  => '2',
						'cols'  => '50',
						'style' => 'width:35%;min-height: auto;',
					),
					'dependency'  => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|true', 'all' ),
				),
				array(
					'id'         => 'poll_form_fields',
					'title'      => esc_html__( 'Form Fields', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Select form fields', 'wp-poll' ),
					'type'       => 'button_set',
					'multiple'   => true,
					'options'    => array(
						'first_name'    => array(
							'label'        => esc_html__( 'First Name', 'wp-poll' ),
							'availability' => 'fixed',
						),
						'last_name'     => array(
							'label' => esc_html__( 'Last Name', 'wp-poll' ),
						),
						'email_address' => array(
							'label'        => esc_html__( 'Email Address', 'wp-poll' ),
							'availability' => 'fixed',
						),
					),
					'default'    => array( 'first_name', 'email_address' ),
					'dependency' => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'         => 'poll_form_label_first_name',
					'title'      => esc_html__( 'First Name Label', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Label for first name in the form', 'wp-poll' ),
					'type'       => 'text',
					'dependency' => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'         => 'poll_form_label_last_name',
					'title'      => esc_html__( 'Last Name Label', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Label for last name in the form', 'wp-poll' ),
					'type'       => 'text',
					'dependency' => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'         => 'poll_form_label_email',
					'title'      => esc_html__( 'Email Address Label', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Label for email address in the form', 'wp-poll' ),
					'type'       => 'text',
					'dependency' => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'         => 'poll_form_label_button',
					'title'      => esc_html__( 'Submit Button Label', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Label for submit button in the form.', 'wp-poll' ),
					'type'       => 'text',
					'dependency' => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'            => 'poll_form_content',
					'title'         => esc_html__( 'Form Content', 'wp-poll' ),
					'subtitle'      => esc_html__( 'Add some contents before the form fields.', 'wp-poll' ),
					'type'          => 'wp_editor',
					'media_buttons' => false,
					'height'        => '100px',
					'dependency'    => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'            => 'poll_form_notice',
					'title'         => esc_html__( 'Form Notice', 'wp-poll' ),
					'subtitle'      => esc_html__( 'Let the users know about your policy', 'wp-poll' ),
					'type'          => 'wp_editor',
					'media_buttons' => false,
					'height'        => '100px',
					'dependency'    => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'         => 'poll_form_require_notice_consent',
					'title'      => esc_html__( 'Require consent', 'wp-poll' ),
					'label'      => esc_html__( 'Require notice consent.', 'wp-poll' ),
					'type'       => 'switcher',
					'default'    => false,
					'dependency' => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'type'       => 'subheading',
					'content'    => esc_html__( 'Form Styling', 'wp-poll' ),
					'dependency' => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'         => 'poll_form_style_colors',
					'title'      => esc_html__( 'Colors', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Update form colors.', 'wp-poll' ),
					'type'       => 'color_group',
					'options'    => array(
						'form_bg'         => esc_html__( 'Form Bg', 'wp-poll' ),
						'content'         => esc_html__( 'Content', 'wp-poll' ),
						'button_normal'   => esc_html__( 'Button', 'wp-poll' ),
						'button_bg'       => esc_html__( 'Button Bg', 'wp-poll' ),
						'button_bg_hover' => esc_html__( 'Button Bg (Hover))', 'wp-poll' ),
					),
					'dependency' => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'             => 'poll_form_style_typography_content',
					'title'          => esc_html__( 'Content Typography', 'wp-poll' ),
					'subtitle'       => esc_html__( 'Typography for the content before form fields.', 'wp-poll' ),
					'type'           => 'typography',
					'color'          => false,
					'preview'        => false,
					'text_transform' => false,
					'text_align'     => false,
					'letter_spacing' => false,
					'dependency'     => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'             => 'poll_form_style_typography_gdpr',
					'title'          => esc_html__( 'GDPR Notice Typography', 'wp-poll' ),
					'subtitle'       => esc_html__( 'Typography for the GDPR notice.', 'wp-poll' ),
					'type'           => 'typography',
					'color'          => false,
					'preview'        => false,
					'text_transform' => false,
					'text_align'     => false,
					'letter_spacing' => false,
					'dependency'     => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
				array(
					'id'             => 'poll_form_style_typography_button',
					'title'          => esc_html__( 'Submit Button Typography', 'wp-poll' ),
					'subtitle'       => esc_html__( 'Typography for the Submit button.', 'wp-poll' ),
					'type'           => 'typography',
					'color'          => false,
					'preview'        => false,
					'text_transform' => false,
					'text_align'     => false,
					'letter_spacing' => false,
					'dependency'     => array( '_type|poll_form_enable|poll_external_form_enable', 'any|==|==', 'poll,nps,reaction|true|false', 'all' ),
				),
			),
		);

		$field_sections['poll_styling'] = array(
			'title'  => __( 'Styling', 'wp-poll' ),
			'icon'   => 'fa fa-bolt',
			'fields' => array(
				array(
					'id'         => '_theme',
					'title'      => esc_html__( 'Poll Theme', 'wp-poll' ),
					'subtitle'   => esc_html__( 'By default it will apply from global settings.', 'wp-poll' ),
					'type'       => 'select',
					'options'    => $this->get_poll_themes(),
					'default'    => 'default',
					'dependency' => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'         => '_theme_nps',
					'title'      => esc_html__( 'NPS Theme', 'wp-poll' ),
					'subtitle'   => esc_html__( 'By default it will apply from global settings.', 'wp-poll' ),
					'type'       => 'select',
					'options'    => $this->get_nps_themes(),
					'dependency' => array( '_type', '==', 'nps', 'all' ),
				),
				array(
					'id'         => '_theme_reaction',
					'title'      => esc_html__( 'Reaction Theme', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Update your preference on the reaction.', 'wp-poll' ),
					'type'       => 'select',
					'options'    => $this->get_reaction_themes(),
					'dependency' => array( '_type', '==', 'reaction', 'all' ),
				),
				array(
					'id'         => '_theme_reviews',
					'title'      => esc_html__( 'Reviews Theme', 'wp-poll' ),
					'subtitle'   => esc_html__( 'By default it will apply from global settings.', 'wp-poll' ),
					'type'       => 'select',
					'options'    => $this->get_reviews_themes(),
					'dependency' => array( '_type', '==', 'reviews', 'all' ),
				),
				array(
					'id'          => '_nps_lowest_marking_text',
					'title'       => esc_html__( 'Lowest Marking Text', 'wp-poll' ),
					'subtitle'    => esc_html__( 'Edit the text for indicating lowest value.', 'wp-poll' ),
					'placeholder' => esc_html__( 'It was terrible', 'wp-poll' ),
					'type'        => 'text',
					'dependency'  => array( '_type', '==', 'nps', 'all' ),
				),
				array(
					'id'          => '_nps_highest_marking_text',
					'title'       => esc_html__( 'Highest Marking Text', 'wp-poll' ),
					'subtitle'    => esc_html__( 'Edit the text for indicating highest value.', 'wp-poll' ),
					'placeholder' => esc_html__( 'Absolutely love it', 'wp-poll' ),
					'type'        => 'text',
					'dependency'  => array( '_type', '==', 'nps', 'all' ),
				),
				array(
					'id'         => '_nps_commentbox',
					'title'      => esc_html__( 'Comment Box', 'wp-poll' ),
					'subtitle'   => esc_html__( 'Receive feedback from users.', 'wp-poll' ),
					'type'       => 'button_set',
					'options'    => array(
						'disabled' => array( 'label' => esc_html__( 'Disable', 'wp-poll' ) ),
						'enabled'  => array( 'label' => esc_html__( 'Enable, Not Mandatory', 'wp-poll' ) ),
						'obvious'  => array( 'label' => esc_html__( 'Enable, Mandatory', 'wp-poll' ) ),
					),
					'default'    => 'enabled',
					'dependency' => array( '_type', '==', 'nps', 'all' ),
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
					'dependency'   => array( '_theme|_type', 'any|==', '1,2,3,5,8,9,10|poll', 'all' ),
				),
				array(
					'id'           => '_two_columns_for_options_on_mobile',
					'title'        => esc_html__( 'Options Column on Mobile', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Display two columns for options on mobile, default is one.', 'wp-poll' ),
					'type'         => 'switcher',
					'default'      => false,
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme|_type', 'any|==', '6,7|poll', 'all' ),
				),
				array(
					'id'           => '_timer_type',
					'title'        => esc_html__( 'Countdown Timer Type', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Select countdown timer type..', 'wp-poll' ),
					'type'         => 'button_set',
					'options'      => array(
						'regular'    => array( 'label' => esc_html__( 'Regular', 'wp-poll' ) ),
						'with_votes' => array( 'label' => esc_html__( 'Timer with votes count', 'wp-poll' ) ),
					),
					'text_off'     => esc_html__( 'Percentage', 'wp-poll' ),
					'text_width'   => 150,
					'default'      => 'regular',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme|_type', 'any|==', '10,11,12|poll', 'all' ),
				),
				array(
					'id'           => '_nps_labels_color',
					'title'        => esc_html__( 'Labels Color', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Add custom NPS labels color.', 'wp-poll' ),
					'type'         => 'color_group',
					'options'      => array(
						'labels_color_normal' => esc_html__( 'Normal Color', 'wp-poll' ),
						'hover_active'        => esc_html__( 'Hover/Active Color', 'wp-poll' ),
					),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme_nps|_type', 'any|==', '1,2|nps', 'all' ),
				),
				array(
					'id'           => '_nps_labels_colors',
					'title'        => esc_html__( 'Labels Color', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Add custom NPS labels color.', 'wp-poll' ),
					'type'         => 'color_group',
					'options'      => array(
						'labels_colors_normal' => esc_html__( 'Label color', 'wp-poll' ),
						'active'               => esc_html__( 'Hover/Active color', 'wp-poll' ),
						'selected_bg'          => esc_html__( 'Selected background color', 'wp-poll' ),
						'border'               => esc_html__( 'Wrapper border', 'wp-poll' ),
						'wrapper_bg'           => esc_html__( 'Wrapper background', 'wp-poll' ),
					),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme_nps|_type', 'any|==', '5|nps', 'all' ),
				),
				array(
					'id'           => '_nps_slider_color',
					'title'        => esc_html__( 'Slider Color', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Add custom NPS slider color.', 'wp-poll' ),
					'type'         => 'color_group',
					'options'      => array(
						'bar'              => esc_html__( 'Bar Color', 'wp-poll' ),
						'circle_indicator' => esc_html__( 'Circle Indicator Color', 'wp-poll' ),
					),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme_nps|_type', 'any|==', '3|nps', 'all' ),
				),
				array(
					'id'           => '_nps_progress_bar_colors',
					'title'        => esc_html__( 'Progress Bar Color', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Add progress bar color.', 'wp-poll' ),
					'type'         => 'color_group',
					'options'      => array(
						'background' => esc_html__( 'Bar Color', 'wp-poll' ),
						'indicator'  => esc_html__( 'Circle Indicator Color', 'wp-poll' ),
						'bar_fill_1' => esc_html__( 'Bar Active Color 1', 'wp-poll' ),
						'bar_fill_2' => esc_html__( 'Bar Active Color 2', 'wp-poll' ),
						'bar_fill_3' => esc_html__( 'Bar Active Color 3', 'wp-poll' ),
					),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme_nps|_type', 'any|==', '4|nps', 'all' ),
				),

				array(
					'id'           => '_nps_labels_bg_color',
					'title'        => esc_html__( 'Labels Background Color', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Add background colors for labels. ', 'wp-poll' ),
					'type'         => 'color_group',
					'options'      => array(
						'normal_1'       => esc_html__( 'Normal Color 1', 'wp-poll' ),
						'normal_2'       => esc_html__( 'Normal Color 2', 'wp-poll' ),
						'hover_active_1' => esc_html__( 'Hover/Active Color 1', 'wp-poll' ),
						'hover_active_2' => esc_html__( 'Hover/Active 2', 'wp-poll' ),
					),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme_nps|_type', 'any|==', '1,2|nps', 'all' ),
				),
				array(
					'id'           => '_nps_marking_text_colors',
					'title'        => esc_html__( 'Marking Texts', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Add marking texts colors, "Not Happy" or "Fully Satisfied"', 'wp-poll' ),
					'type'         => 'color_group',
					'options'      => array(
						'lowest'  => esc_html__( 'Lowest text color', 'wp-poll' ),
						'highest' => esc_html__( 'Highest text color', 'wp-poll' ),
					),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme_nps|_type', 'any|==', '1,2,3,4,5|nps', 'all' ),
				),
				array(
					'id'           => '_nps_comment_box_colors',
					'title'        => esc_html__( 'Comment / Feedback Box', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Add colors for comment box. ', 'wp-poll' ),
					'type'         => 'color_group',
					'options'      => array(
						'text_color'   => esc_html__( 'Text color', 'wp-poll' ),
						'bg_color'     => esc_html__( 'Background color', 'wp-poll' ),
						'border_color' => esc_html__( 'Border color', 'wp-poll' ),
					),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_theme_nps|_type', 'any|==', '1,2,3,4,5|nps', 'all' ),
				),

				array(
					'id'         => 'subheading_typography',
					'type'       => 'subheading',
					'content'    => esc_html__( 'Typography Controls', 'wp-poll' ),
					'dependency' => array( '_type', '==', 'poll,nps', 'all' ),
				),
				array(
					'id'           => '_typography_title',
					'title'        => esc_html__( 'Poll Title', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Control typography settings for poll title.', 'wp-poll' ),
					'type'         => 'typography',
					'dependency'   => array( '_type', 'not-any', 'reaction,reviews', 'all' ),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
				),
				array(
					'id'           => '_typography_content',
					'title'        => esc_html__( 'Poll Content', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Control typography settings for poll content.', 'wp-poll' ),
					'type'         => 'typography',
					'dependency'   => array( '_type', 'not-any', 'reaction,reviews', 'all' ),
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
				),
				array(
					'id'           => '_typography_options',
					'title'        => esc_html__( 'Poll Options', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Control typography settings for poll options.', 'wp-poll' ),
					'type'         => 'typography',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'           => '_typography_countdown_timer',
					'title'        => esc_html__( 'Countdown Timer', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Control typography settings for poll count down timer.', 'wp-poll' ),
					'type'         => 'typography',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'         => 'subheading_typography_button_submit',
					'type'       => 'subheading',
					'content'    => esc_html__( 'Typography Controls - Buttons - Submit', 'wp-poll' ),
					'dependency' => array( '_type', '==', 'poll,nps', 'all' ),
				),
				array(
					'id'           => '_typography_btn_submit',
					'title'        => esc_html__( 'Submit Button', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Control typography settings for poll submit button.', 'wp-poll' ),
					'type'         => 'typography',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_type', '==', 'poll,nps', 'all' ),
				),
				array(
					'id'           => '_typography_btn_submit_bg',
					'title'        => esc_html__( 'Background color', 'wp-poll' ),
					'type'         => 'color',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_type', '==', 'poll,nps', 'all' ),
				),
				array(
					'id'         => 'subheading_typography_button_results',
					'type'       => 'subheading',
					'content'    => esc_html__( 'Typography Controls - Buttons - Results', 'wp-poll' ),
					'dependency' => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'           => '_typography_btn_results',
					'title'        => esc_html__( 'Results Button', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Control typography settings for poll results button.', 'wp-poll' ),
					'type'         => 'typography',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'           => '_typography_btn_results_bg',
					'title'        => esc_html__( 'Background color', 'wp-poll' ),
					'type'         => 'color',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_type', '==', 'poll', 'all' ),
				),
				array(
					'id'           => '_emoji_size',
					'title'        => esc_html__( 'Emoji size', 'wp-poll' ),
					'subtitle'     => esc_html__( 'Control your preferred emoji size.', 'wp-poll' ),
					'type'         => 'button_set',
					'options'      => array(
						'34'  => array( 'label' => esc_html__( 'Small', 'wp-poll' ) ),
						'68'  => array( 'label' => esc_html__( 'Medium', 'wp-poll' ) ),
						'102' => array( 'label' => esc_html__( 'Large', 'wp-poll' ) ),
					),
					'default'      => '34',
					'availability' => liquidpoll()->is_pro() ? '' : 'pro',
					'dependency'   => array( '_type', '==', 'reaction', 'all' ),
				),
			),
		);

		return apply_filters( 'LiquidPoll/Filters/poll_meta_field_sections', $field_sections );
	}


	/**
	 * Return Reviews themes
	 *
	 * @return mixed|void
	 */
	function get_reviews_themes() {

		$themes = array(
			999 => array(
				'label'        => esc_html__( '10+ are coming soon', 'wp-poll' ),
				'availability' => 'upcoming',
			),
		);
		$themes = apply_filters( 'LiquidPoll/Filters/reviews_themes', $themes );

		ksort( $themes );

		return $themes;
	}

	/**
	 * Return NPS themes
	 *
	 * @return mixed|void
	 */
	function get_reaction_themes() {

		$themes = array(
			999 => array(
				'label'        => esc_html__( '3+ are coming soon', 'wp-poll' ),
				'availability' => 'upcoming',
			),
		);
		$themes = apply_filters( 'LiquidPoll/Filters/reaction_themes', $themes );

		ksort( $themes );

		return $themes;
	}


	/**
	 * Return NPS themes
	 *
	 * @return mixed|void
	 */
	function get_nps_themes() {

		$themes = array(
			1   => array(
				'label' => esc_html__( 'Theme 1', 'wp-poll' ),
			),
			998 => array(
				'label'        => esc_html__( '4+ are in pro', 'wp-poll' ),
				'availability' => 'pro',
			),
			999 => array(
				'label'        => esc_html__( '10+ are coming soon', 'wp-poll' ),
				'availability' => 'upcoming',
			),
		);
		$themes = apply_filters( 'LiquidPoll/Filters/nps_themes', $themes );

		ksort( $themes );

		return $themes;
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
				'label'        => esc_html__( '20+ are coming soon', 'wp-poll' ),
				'availability' => 'upcoming',
			),
		);
		$themes = apply_filters( 'LiquidPoll/Filters/poll_themes', $themes );

		ksort( $themes );

		return $themes;
	}


	/**
	 * Return reaction emojis
	 *
	 * @return array
	 */
	function get_reaction_emojis() {

		$_emojis = array( 'angel', 'angry', 'anger', 'blushing', 'cry', 'crying', 'cussing', 'dislike', 'flushed', 'halo', 'happy', 'heart-eyes', 'cool', 'kiss', 'meh', 'nose-stem', 'laugh', 'laughing-tears', 'like', 'love', 'party', 'sad', 'sad-tear', 'shocked', 'silly', 'sleeping', 'vomiting', 'smile', 'thinking', 'zany', );

		foreach ( $_emojis as $emoji ) {
			$emojis[ $emoji ] = $this->get_reaction_emoji_url( $emoji );
		}

		return $emojis;
	}


	/**
	 * @param string $emoji
	 *
	 * @return string
	 */
	function get_reaction_emoji_url( $emoji = '' ) {
		return apply_filters( 'LiquidPoll/Filters/get_reaction_emoji_url', LIQUIDPOLL_PLUGIN_URL . 'assets/images/emojis/' . $emoji . '.svg' );
	}
}

global $liquidpoll;

$liquidpoll->metaboxes = new LIQUIDPOLL_Poll_meta();