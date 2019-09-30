<?php
/**
 * Template - Single Poll - Content
 *
 * @package single-poll/cf7-popup
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

?>

<div class="wpp-cf7-popup-wrap wpp-popup-container">

    <div class="wpp-popup-box">
        <span class="box-close"><i class="icofont-close"></i></span>
	    <?php echo do_shortcode( '[contact-form-7 id="232" title="Contact form 1"]' ); ?>
    </div>

</div>