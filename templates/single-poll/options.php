<?php
/**
 * Single Poll - Options
 */

if ( ! defined('ABSPATH')) exit;  // if direct access 

$options_style = 2;

?>


<?php if( $options_style == 1 ) : ?>
    <div class='wpp-option-list-1'>
        <div class="wpp-option-single">
            <input type="checkbox" name="england" id="england">
            <label for="england">England</label>
        </div>

        <div class="wpp-option-single">
            <input type="checkbox" name="newzealand" id="newzealand">
            <label for="newzealand">New Zealand</label>
        </div>
    </div>
<?php endif; ?>

<?php if( $options_style == 2 ) : ?>
    <div class="ac-custom ac-checkbox ac-checkmark wpp-option-list-2">
        <div class="wpp-option-single">
            <input type="checkbox" name="england" id="england">
            <label for="england">England</label>
        </div>

        <div class="wpp-option-single">
            <input type="checkbox" name="newzealand" id="newzealand">
            <label for="newzealand">New Zealand</label>
        </div>

        <div class="wpp-option-single">
            <input type="checkbox" name="india" id="india">
            <label for="india">India</label>
        </div>

        <div class="wpp-option-single">
            <input type="checkbox" name="pakisthan" id="pakisthan">
            <label for="pakisthan">Pakisthan</label>
        </div>

        <div class="wpp-option-single">
            <input type="checkbox" name="australia" id="australia">
            <label for="australia">Australia</label>
        </div>
    </div>
<?php endif; ?>