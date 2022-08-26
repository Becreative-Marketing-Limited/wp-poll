<?php
/**
 * Single NPS - Title
 */

use Pluginbazar\Utils;

defined( 'ABSPATH' ) || exit;


global $poll, $liquidpoll;

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

	echo '<div class="liquidpoll-nps-options">';

//	echo '<input type="range" min="0" max="10" step="1">';
	printf('<input type="range" min="0" max="%s" step="1">',count($poll->get_poll_options()));

	echo '</div>';

	echo '<div class="liquidpoll-nps-score-labels"><span>It was terrible</span><span>Absolutely love it</span></div>';


	echo '<div class="liquidpoll-comment-box"><textarea placeholder="Tell us a little bit about your feedback" name="nps_feedback"></textarea></div>';
}


if ( $poll->ready_to_vote() ) {

	printf( '<div class="nps-button-wrap"><button class="liquidpoll-button liquidpoll-submit-poll" data-poll-id="%s">%s</button> </div>', $poll->get_id(), $liquidpoll->get_button_text( 'submit' ) );

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
