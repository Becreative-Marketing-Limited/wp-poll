<?php
/**
 * Template - Archive - options
 *
 * @package loop/options
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll, $wp_query;

$show_results = $wp_query->get( 'show_results', 'no' ) == 'yes' ? true : false;
$poll_results = $poll->get_poll_results();
$singles      = isset( $poll_results['singles'] ) ? $poll_results['singles'] : array();

?>
<ul class="poll-options">

	<?php
	foreach ( $poll->get_poll_options() as $option_id => $option ) :

		$option_html   = array();
		$option_html[] = isset( $option['label'] ) ? $option['label'] : '';
		$thumb_url     = isset( $option['thumb'] ) ? $option['thumb'] : '';
		$votes_count = isset( $singles[$option_id] ) ? $singles[$option_id] : 0;

		if ( apply_filters( 'wpp_filter_show_results_in_archive', $show_results ) ) {
			$option_html[] = sprintf( '<span class="area-right">%s %s</span>', $votes_count, esc_html__( 'Vote(s)', 'wp-poll') );
		}

		if ( ! empty( $thumb_url ) ) {
			$option_html[] = sprintf( '<span class="area-right"><img src="%s" alt="%s"></span>', $thumb_url, isset( $option['label'] ) ? $option['label'] : '' );
		}

		printf( '<li>%s</li>', implode( ' ', array_filter( $option_html ) ) );

	endforeach;
	?>

</ul>