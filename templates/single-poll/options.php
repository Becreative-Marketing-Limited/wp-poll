<?php
/**
 * Single Poll - Options
 */

if ( ! defined('ABSPATH')) exit;  // if direct access 

global $poll;


//echo '<pre>'; print_r( $poll->get_poll_options() ); echo '</pre>';


?>


<div <?php wpp_options_single_class(); ?>>


    <div class="wpp-option-single">
        <input type="checkbox" name="england" id="england">
        <label for="england">England</label>
    </div>

    <div class="wpp-option-single">
        <input type="checkbox" name="newzealand" id="newzealand">
        <label for="newzealand">New Zealand</label>
    </div>
</div>