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

	echo '<ul class="liquidpoll-nps-options">';

	foreach ( $poll->get_poll_options() as $option_id => $option ) :
		printf( '<li><label for="liquidpoll-nps-%1$s">%2$s</label><input required id="liquidpoll-nps-%1$s" type="radio" name="nps_score" value="%1$s"></li>', $option_id, Utils::get_args_option( 'label', $option, '0' ) );
	endforeach;

	echo '</ul>';

	printf( '<div class="liquidpoll-nps-score-labels"><span>%s</span><span>%s</span></div>',
		esc_html( $poll->get_meta( '_nps_lowest_marking_text', esc_html__( 'It was terrible', 'wp-poll' ) ) ),
		esc_html( $poll->get_meta( '_nps_highest_marking_text', esc_html__( 'Absolutely love it', 'wp-poll' ) ) )
	);


	$nps_commentbox = $poll->get_meta( '_nps_commentbox', 'enabled' );

	if ( 'enabled' == $nps_commentbox || 'obvious' == $nps_commentbox ) {
		printf( '<div class="liquidpoll-comment-box %s"><textarea %s placeholder="%s" name="nps_feedback"></textarea></div>',
			esc_attr( $nps_commentbox ),
			( ( 'obvious' == $nps_commentbox ) ? 'required' : '' ),
			esc_html__( 'Tell us a little bit about your feedback', 'wp-poll' )
		);
	}
}


if ( $poll->ready_to_vote() ) {

	printf( '<p class="liquidpoll-responses"><span class="icon-box"></span><span class="message"></span></p>' );

	printf( '<div class="nps-button-wrap"><button type="submit" class="liquidpoll-button liquidpoll-submit-poll">%s</button></div>', $liquidpoll->get_button_text( 'submit' ) );

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
