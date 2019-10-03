<?php
/**
 * Template - Single Poll - Content
 *
 * @package single-poll/social-share
 * @author Pluginbazar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

if( ! is_singular('poll' ) ) {
    return;
}

?>

<div class="wpp-social-share">
    <ul>
        <li>
            <a title="Share on Facebook" href="#">
                <i class="icofont-facebook" aria-hidden="true"></i>
            </a>
        </li>

        <li>
            <a title="Share on Twitter" href="#">
                <i class="icofont-twitter" aria-hidden="true"></i>
            </a>
        </li>

        <li>
            <a title="Share on Pinterest" href="#">
                <i class="icofont-pinterest" aria-hidden="true"></i>
            </a>
        </li>

        <li>
            <a target="_blank" data-social="Digg" title="Share on Digg" href="#">
                <i class="icofont-digg" aria-hidden="true"></i>
            </a>
        </li>

        <li>
            <a href="#" class="wpp-print-page" title="Print">
                <i class="icofont-print" aria-hidden="true"></i>
            </a>
        </li>
    </ul>
</div>