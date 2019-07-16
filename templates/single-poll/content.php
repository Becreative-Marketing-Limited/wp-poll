<?php
/**
 * Template - Single Poll - Content
 *
 * @author Pluginbazar
 */

if ( ! defined('ABSPATH')) exit;  // if direct access 


global $poll;

echo '<pre>'; print_r( $poll->get_id() ); echo '</pre>';
	

	echo '<div class="wpp_content">';
	the_content();
	echo '</div>';
?>
