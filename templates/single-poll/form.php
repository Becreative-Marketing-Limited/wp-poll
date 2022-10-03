<?php
/**
 * Single Poll - Form
 */

use FluentCrm\App\Http\Controllers\SubscriberController;
use Pluginbazar\Utils;

defined( 'ABSPATH' ) || exit;

global $poll;

$poll_form_fields           = $poll->get_meta( 'poll_form_fields', array( 'first_name', 'email_address' ) );
$poll_form_label_first_name = $poll->get_meta( 'poll_form_label_first_name', esc_html__( 'First Name', 'wp-poll' ) );
$poll_form_label_last_name  = $poll->get_meta( 'poll_form_label_last_name', esc_html__( 'Last Name', 'wp-poll' ) );
$poll_form_label_email      = $poll->get_meta( 'poll_form_label_email', esc_html__( 'Email Address', 'wp-poll' ) );
$poll_form_label_button     = $poll->get_meta( 'poll_form_label_button', esc_html__( 'View Results', 'wp-poll' ) );
$poll_form_content          = $poll->get_meta( 'poll_form_content' );
$poll_form_notice           = $poll->get_meta( 'poll_form_notice', esc_html__( 'I have accept the terms and condition', 'wp-poll' ) );
$poll_form_style_colors     = $poll->get_meta( 'poll_form_style_colors' );

?>
    <form class="liquidpoll-form" action="" enctype="multipart/form-data" method="get">

		<?php if ( ! empty( $poll_form_content ) ) : ?>
            <div class="liquidpoll-form-field liquidpoll-form-content">
				<?php echo apply_filters( 'the_content', $poll_form_content ); ?>
            </div>
		<?php endif; ?>

		<?php if ( in_array( 'first_name', $poll_form_fields ) ) : ?>
            <div class="liquidpoll-form-field">
                <label for="liquidpoll_first_name"><?php echo $poll_form_label_first_name; ?></label>
                <input id="liquidpoll_first_name" type="text" placeholder="<?php echo $poll_form_label_first_name; ?>" name="first_name" required>
            </div>
		<?php endif; ?>

		<?php if ( in_array( 'last_name', $poll_form_fields ) ) : ?>
            <div class="liquidpoll-form-field">
                <label for="liquidpoll_last_name"><?php echo $poll_form_label_last_name; ?></label>
                <input id="liquidpoll_last_name" type="text" placeholder="<?php echo $poll_form_label_last_name; ?>" name="last_name">
            </div>
		<?php endif; ?>

		<?php if ( in_array( 'email_address', $poll_form_fields ) ) : ?>
            <div class="liquidpoll-form-field">
                <label for="liquidpoll_email"><?php echo $poll_form_label_email; ?></label>
                <input id="liquidpoll_email" type="email" placeholder="<?php echo $poll_form_label_email; ?>" name="email_address" required>
            </div>
		<?php endif; ?>

		<?php if ( ! empty( $poll_form_notice ) ) : ?>
            <div class="liquidpoll-form-field liquidpoll-form-notice">
                <input type="checkbox" id="liquidpoll-form-notice" name="notice">
                <label for="liquidpoll-form-notice" class="notice"><?php echo wp_kses_data( $poll_form_notice ); ?></label>
            </div>
		<?php endif; ?>

        <div class="liquidpoll-form-field">
            <input type="hidden" name="poll_id" value="<?php echo esc_attr( $poll->get_id() ); ?>">
            <input id="liquidpoll_submit" type="submit" value="<?php echo esc_attr__( 'View Results', 'wp-poll' ); ?>">
        </div>

    </form>
<?php

liquidpoll_apply_css( '.liquidpoll-form', array( 'background' => Utils::get_args_option( 'form_bg', $poll_form_style_colors ) ) );
liquidpoll_apply_css( '.liquidpoll-form-content', array( 'color' => Utils::get_args_option( 'content', $poll_form_style_colors ) ) );
liquidpoll_apply_css( '.liquidpoll-form-field input[type="submit"]',
	array(
		'color'      => Utils::get_args_option( 'button_normal', $poll_form_style_colors ),
		'background' => Utils::get_args_option( 'button_bg', $poll_form_style_colors ),
	)
);
liquidpoll_apply_css( '.liquidpoll-form-field input[type="submit"]:hover', array( 'background' => Utils::get_args_option( 'button_bg_hover', $poll_form_style_colors ), ) );

liquidpoll_apply_css( '.liquidpoll-form-content', $poll->get_css_args( 'poll_form_style_typography_content' ) );
liquidpoll_apply_css( '.liquidpoll-form-notice .notice', $poll->get_css_args( 'poll_form_style_typography_gdpr' ) );
liquidpoll_apply_css( '.liquidpoll-form-field input[type="submit"]', $poll->get_css_args( 'poll_form_style_typography_button' ) );