<?php
/**
 * Widget Poll Template - 1
 */

global $poll, $liquidpoll_widget_settings;

$poll_type = isset( $liquidpoll_widget_settings['_type'] ) ? $liquidpoll_widget_settings['_type'] : '';
$key       = 'poll_id_' . $poll_type;
$poll_id   = liquidpoll()->get_widget_arg_val( $key );
$poll      = liquidpoll_get_poll( $poll_id );

if ( ! $poll instanceof LIQUIDPOLL_Poll ) {
	return;
}

//wp_enqueue_script( 'rangeslider', LIQUIDPOLL_PLUGIN_URL . 'assets/front/js/rangeslider.min.js', array( 'jquery', 'elementor-frontend' ) );
//wp_enqueue_style( 'rangeslider', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/rangeslider.css' );

printf( '<div class="liquidpoll-elementor-poll">%s</div>', do_shortcode( '[poll id="' . $poll->get_id() . '"]' ) );

?>
<script>

</script>