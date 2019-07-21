<?php
/**
 * Template - Archive - thumb
 *
 * @package loop/thumb
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $poll;

?>

<div class="poll-thumb">
    <a href="<?php echo esc_url( $poll->get_permalink() ); ?>"><img style="width:100px;"
                                                                    src="<?php echo esc_url( $poll->get_thumbnail() ); ?>"
                                                                    alt="<?php echo esc_attr( $poll->get_name() ); ?>"></a>
</div>
