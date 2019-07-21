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

global $poll;


?>
<ul class="poll-options">

	<?php
	foreach ( $poll->get_poll_options() as $option_id => $option ) :

		$option_html   = array();
		$option_html[] = isset( $option['label'] ) ? $option['label'] : '';
		$thumb_url     = isset( $option['thumb'] ) ? $option['thumb'] : '';

		if ( ! empty( $thumb_url ) ) {
			$option_html[] = sprintf( '<img src="%s" alt="%s">', $thumb_url, $label );
		}

		printf( '<li>%s</li>', implode( ' - ', array_filter( $option_html ) ) );

	endforeach;
	?>

</ul>