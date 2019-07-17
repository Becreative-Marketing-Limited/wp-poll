<?php
/**
 * Single Poll - Title
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

?>

<h1 itemprop="name"
    class="wpp-poll-title"><?php echo apply_filters( 'the_title', $poll->get_name(), $poll->get_id() ); ?></h1>
