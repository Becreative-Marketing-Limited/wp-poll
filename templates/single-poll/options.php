<?php
/**
 * Single Poll - Options
 */

if ( ! defined('ABSPATH')) exit;  // if direct access 

$options_style = 10;
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

<?php if( $options_style == 10 ) : ?>
    <div class="wpp-custom wpp-checkmark wpp-option-list-10">
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

<?php if( $options_style == 10 ) : ?>

    <div class="wpp-custom wpp-radio wpp-fill wpp-option-list-10">
        <div class="wpp-option-single"><input id="r1" name="r1" type="radio"><label for="r1">Seamlessly visualize quality intellectual capital</label></div>
        <div class="wpp-option-single"><input id="r2" name="r1" type="radio"><label for="r2">Collaboratively administrate turnkey channels</label></div>
        <div class="wpp-option-single"><input id="r3" name="r1" type="radio"><label for="r3">Objectively seize scalable metrics</label></div>
        <div class="wpp-option-single"><input id="r4" name="r1" type="radio"><label for="r4">Energistically scale future-proof core competencies</label></div>
    </div>
<?php endif; ?>