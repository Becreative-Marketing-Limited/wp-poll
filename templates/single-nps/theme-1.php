<?php
/**
 * Single NPS - Title
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;

global $poll, $liquidpoll;

$nps_commentbox = $poll->get_meta( '_nps_commentbox', 'enabled' );

if ( $poll->has_name() ) {

	printf( '<%1$s itemprop="name" class="liquidpoll-poll-title">%2$s</%1$s>',
		is_singular( 'poll' ) ? 'h1' : 'h2',
		apply_filters( 'the_title', $poll->get_name() )
	);

	// Apply dynamic typography css
	liquidpoll_apply_css( '.liquidpoll-poll-title', $poll->get_css_args( '_typography_title' ) );
}


if ( $poll->has_content() ) {

	printf( '<div class="liquidpoll-content">%s</div>', apply_filters( 'the_content', wp_kses_post( $poll->get_content() ) ) );

	// Apply dynamic typography css
	liquidpoll_apply_css( '.liquidpoll-content', $poll->get_css_args( '_typography_content' ) );
}


if ( ! empty( $poll->get_poll_options() ) && is_array( $poll->get_poll_options() ) ) {

	echo '<ul class="liquidpoll-nps-options">';

	foreach ( $poll->get_poll_options() as $option_id => $option ) :
		printf( '<li><label for="liquidpoll-nps-%1$s">%2$s</label><input required id="liquidpoll-nps-%1$s" type="radio" name="nps_score" value="%1$s"></li>', $option_id, Utils::get_args_option( 'label', $option, '0' ) );
	endforeach;

	echo '</ul>';

	printf( '<div class="liquidpoll-nps-score-labels"><span class="lowest">%s</span><span class="highest">%s</span></div>',
		esc_html( $poll->get_meta( '_nps_lowest_marking_text', Utils::get_option( 'liquidpoll_nps_text_min', esc_html__( 'It was terrible', 'wp-poll' ) ) ) ),
		esc_html( $poll->get_meta( '_nps_highest_marking_text', Utils::get_option( 'liquidpoll_nps_text_max', esc_html__( 'Absolutely love it', 'wp-poll' ) ) ) )
	);

	$marking_text_colors = $poll->get_css_args( '_nps_marking_text_colors' );
	$lowest              = Utils::get_args_option( 'lowest', $marking_text_colors );
	$highest             = Utils::get_args_option( 'highest', $marking_text_colors, '#000000' );

	liquidpoll_apply_css( '.nps-single.theme-1 .liquidpoll-nps-score-labels span.lowest', array( 'color' => $lowest ) );
	liquidpoll_apply_css( '.nps-single.theme-1 .liquidpoll-nps-score-labels span.highest', array( 'color' => $highest ) );


	if ( 'enabled' == $nps_commentbox || 'obvious' == $nps_commentbox ) {
		printf( '<div class="liquidpoll-comment-box %s"><textarea %s placeholder="%s" name="nps_feedback"></textarea></div>',
			esc_attr( $nps_commentbox ),
			( ( 'obvious' == $nps_commentbox ) ? 'required' : '' ),
			esc_html__( Utils::get_option( 'liquidpoll_nps_comment_box_placeholder', 'Tell us a little bit about your feedback' ), 'wp-poll' )
		);

		$comment_box_colors = $poll->get_css_args( '_nps_comment_box_colors' );
		$text_color         = Utils::get_args_option( 'text_color', $comment_box_colors, '#000000' );
		$bg_color           = Utils::get_args_option( 'bg_color', $comment_box_colors, '#F4F4F4' );
		$border_color       = Utils::get_args_option( 'border_color', $comment_box_colors, '#F4F4F4' );

		liquidpoll_apply_css( '.nps-single.theme-1 .liquidpoll-comment-box textarea', array(
			'color'        => $text_color,
			'background'   => $bg_color,
			'border-color' => $border_color,
		) );
	}


	// Normal label color
	liquidpoll_apply_css( '.nps-single.theme-1 ul.liquidpoll-nps-options li label', array(
		'color' => Utils::get_args_option( 'labels_color_normal', $poll->get_css_args( '_nps_labels_color' ) ),
	) );
	// Hover or active label color
	liquidpoll_apply_css( '.nps-single.theme-1 ul.liquidpoll-nps-options li:hover label, .nps-single.theme-1 ul.liquidpoll-nps-options li.active label', array(
		'color' => Utils::get_args_option( 'hover_active', $poll->get_css_args( '_nps_labels_color' ) ),
	) );

	$labels_bg_color = $poll->get_css_args( '_nps_labels_bg_color' );
	$normal_1        = Utils::get_args_option( 'normal_1', $labels_bg_color, 'transparent' );
	$normal_2        = Utils::get_args_option( 'normal_2', $labels_bg_color, 'transparent' );
	$hover_active_1  = Utils::get_args_option( 'hover_active_1', $labels_bg_color );
	$hover_active_2  = Utils::get_args_option( 'hover_active_2', $labels_bg_color );

	liquidpoll_apply_css( '.nps-single.theme-1 ul.liquidpoll-nps-options li', array(
		'background' => "linear-gradient(138.32deg, {$normal_1} 23.82%, {$normal_2} 88.62%)",
	) );

	if ( ! empty( $hover_active_1 ) && ! empty( $hover_active_2 ) ) {
		liquidpoll_apply_css( '.nps-single.theme-1 ul.liquidpoll-nps-options li:hover, .nps-single.theme-1 ul.liquidpoll-nps-options li.active', array(
			'background' => "linear-gradient(138.32deg, {$hover_active_1} 23.82%, {$hover_active_2} 88.62%)",
		) );
	}
}

if ( $poll->ready_to_vote() ) {

	printf( '<p class="liquidpoll-responses"><span class="icon-box"></span><span class="message"></span></p>' );

	printf( '<div class="nps-button-wrap"><button class="liquidpoll-button liquidpoll-submit-poll %s">%s</button></div>', ( 'obvious' == $nps_commentbox ? 'disabled' : '' ), $liquidpoll->get_button_text( 'nps_submit' ) );

	liquidpoll_apply_css( '.liquidpoll-submit-poll', $poll->get_css_args( '_typography_button_submit' ) );

	liquidpoll_apply_css( '.liquidpoll-submit-poll',
		array_merge(
			$poll->get_meta( '_typography_btn_submit', array() ),
			array(
				'background-color' => $poll->get_css_args( '_typography_btn_submit_bg' ),
			)
		)
	);
}
