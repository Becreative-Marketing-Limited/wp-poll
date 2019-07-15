<?php
/*
* @Author 		Jaed Mosharraf
* Copyright: 	2015 Jaed Mosharraf
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


if( ! function_exists( 'wpp_single_poll_class' ) ) {
	function wpp_single_poll_class( $class = '', $product_id = null ) {
//		echo 'class="' . esc_attr( implode( ' ', wc_get_product_class( $class, $product_id ) ) ) . '"';
	}
}

if ( ! function_exists( 'wpp_get_template_part' ) ) {
	function wpp_get_template_part( $slug, $name = '', $args = array() ) {

		$template = '';

		// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php.
		if ( $name ) {
			$template = locate_template( array(
				"{$slug}-{$name}.php",
				 "wpp/{$slug}-{$name}.php"
			) );
		}

		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( untrailingslashit( WPP_PLUGIN_DIR ) . "/templates/{$slug}-{$name}.php" ) ) {
			$template = untrailingslashit( WPP_PLUGIN_DIR ) . "/templates/{$slug}-{$name}.php";
		}

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php.
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php", "wpp/{$slug}.php" ) );
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'wpp_filters_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}
}


if ( ! function_exists( 'wpp_get_template' ) ) {
	function wpp_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		$located = wpp_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			return new WP_Error( 'invalid_data', __( '%s does not exist.', 'woc-open-close' ), '<code>' . $located . '</code>' );
		}

		$located = apply_filters( 'wpp_filters_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'wpp_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'wpp_after_template_part', $template_name, $template_path, $located, $args );
	}
}


if ( ! function_exists( 'wpp_locate_template' ) ) {
	function wpp_locate_template( $template_name, $template_path = '', $default_path = '' ) {


		if ( ! $template_path ) {
			$template_path = 'wpp/';
		}

		if ( ! $default_path ) {
			$default_path = untrailingslashit( WPP_PLUGIN_DIR ) . '/templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template/.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'wpp_filters_locate_template', $template, $template_name, $template_path );
	}
}


//----------------------------------------------------------------------------------------------------



function wpp_ajax_submit_comment() {

	$html        = '';
	$poll_id     = (int) sanitize_text_field( $_POST['poll_id'] );
	$wpp_name    = sanitize_text_field( $_POST['wpp_name'] );
	$wpp_email   = sanitize_email( $_POST['wpp_email'] );
	$wpp_comment = sanitize_text_field( $_POST['wpp_comment'] );

	$user_id = email_exists( $wpp_email );
	if ( ! $user_id ) {

		$arr_user_name   = explode( $wpp_email );
		$user_name       = isset( $arr_user_name[0] ) ? $arr_user_name[0] : $wpp_email;
		$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

		$user_id = wp_create_user( $user_name, $random_password, $wpp_email );
		wp_update_user( array( 'ID' => $user_id, 'display_name' => $wpp_name ) );
	}

	$wpp_comment_data = array(
		'comment_post_ID'      => $poll_id,
		'comment_author'       => $wpp_name,
		'comment_author_email' => $wpp_email,
		'comment_content'      => $wpp_comment,
		'comment_type'         => '',
		'comment_parent'       => 0,
		'user_id'              => $user_id,
		'comment_author_IP'    => wpp_get_ip_address(),
		'comment_date'         => current_time( 'mysql' ),
		'comment_approved'     => 1,
	);

	$wpp_comment_id = wp_insert_comment( $wpp_comment_data );

	$wpp_comment_message_error   = get_option( 'wpp_comment_message_error' );
	$wpp_comment_message_success = get_option( 'wpp_comment_message_success' );
	if ( empty( $wpp_comment_message_error ) ) {
		$wpp_comment_message_error = __( 'Something went wrong, Please try latter', 'wp-poll' );
	}
	if ( empty( $wpp_comment_message_success ) ) {
		$wpp_comment_message_success = __( 'Success, Your Comment may be under review and publish latter', 'wp-poll' );
	}

	if ( ! $wpp_comment_id ) {
		$html .= '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> ' .
		         apply_filters( 'wpp_filters_comment_error', $wpp_comment_message_error );
	} else {
		$html .= '<i class="fa fa-check-circle-o" aria-hidden="true"></i> ' .
		         apply_filters( 'wpp_filters_comment_success', $wpp_comment_message_success );
	}

	echo $html;
	die();
}

add_action( 'wp_ajax_wpp_ajax_submit_comment', 'wpp_ajax_submit_comment' );
add_action( 'wp_ajax_nopriv_wpp_ajax_submit_comment', 'wpp_ajax_submit_comment' );


function wpp_ajax_add_new_option() {

	$response   = array();
	$poll_id    = (int) sanitize_text_field( $_POST['poll_id'] );
	$option_val = sanitize_text_field( $_POST['option_val'] );

	if ( empty( $poll_id ) || empty( $option_val ) ) {
		die();
	}

	$poll_meta_options = get_post_meta( $poll_id, 'poll_meta_options', true );
	if ( empty( $poll_meta_options ) ) {
		$poll_meta_options = array();
	}

	$poll_meta_options[ time() ] = $option_val;


	update_post_meta( $poll_id, 'poll_meta_options', $poll_meta_options );

	echo 'ok';
	die();
}

add_action( 'wp_ajax_wpp_ajax_add_new_option', 'wpp_ajax_add_new_option' );
add_action( 'wp_ajax_nopriv_wpp_ajax_add_new_option', 'wpp_ajax_add_new_option' );


function wpp_ajax_submit_poll() {

	$response     = array();
	$poll_id      = (int) sanitize_text_field( $_POST['poll_id'] );
	$checked_opts = $_POST['checked'];

	$polled_data = get_post_meta( $poll_id, 'polled_data', true );
	$polled_data = empty( $polled_data ) ? array() : $polled_data;
	$poller      = get_poller();

	if ( array_key_exists( $poller, $polled_data ) ) {

		$response['status'] = 0;
		$response['notice'] = '<i class="fa fa-exclamation-triangle"></i> You have reached the Maximum Polling quota !';
	} else {

		foreach ( $checked_opts as $option ) {
			$polled_data[ $poller ][] = $option;
		}
		update_post_meta( $poll_id, 'polled_data', $polled_data );

		$response['status'] = 1;
		$response['notice'] = '<i class="fa fa-check"></i> Successfully Polled on this.';
	}

	echo json_encode( $response );
	die();
}

add_action( 'wp_ajax_wpp_ajax_submit_poll', 'wpp_ajax_submit_poll' );
add_action( 'wp_ajax_nopriv_wpp_ajax_submit_poll', 'wpp_ajax_submit_poll' );


function show_notice( $type = 1 ) {

	if ( $type == 1 ) {
		$notice_type = 'wpp_success';
	}
	if ( $type == 0 ) {
		$notice_type = 'wpp_error';
	}

	return "<div class='wpp_notice $notice_type'>Warning: You have already Polled !</div>";
}

function get_poller() {

	$user = wp_get_current_user();
	if ( $user->ID != 0 ) {
		return $user->ID;
	}

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}


	return $ip;
}

function qa_single_poll_template( $single_template ) {
	global $post;
	if ( $post->post_type == 'poll' ) {
		$single_template = WPP_PLUGIN_DIR . 'templates/single-poll/single-poll.php';
	}

	return $single_template;
}

//add_filter( 'single_template', 'qa_single_poll_template' );



function wpp_dark_color( $rgb, $darker = 2 ) {

	$hash = ( strpos( $rgb, '#' ) !== false ) ? '#' : '';
	$rgb  = ( strlen( $rgb ) == 7 ) ? str_replace( '#', '', $rgb ) : ( ( strlen( $rgb ) == 6 ) ? $rgb : false );
	if ( strlen( $rgb ) != 6 ) {
		return $hash . '000000';
	}
	$darker = ( $darker > 1 ) ? $darker : 1;

	list( $R16, $G16, $B16 ) = str_split( $rgb, 2 );

	$R = sprintf( "%02X", floor( hexdec( $R16 ) / $darker ) );
	$G = sprintf( "%02X", floor( hexdec( $G16 ) / $darker ) );
	$B = sprintf( "%02X", floor( hexdec( $B16 ) / $darker ) );

	return $hash . $R . $G . $B;
}

function wpp_get_ip_address() {
	$ip = $_SERVER['REMOTE_ADDR'];
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	return $ip;
}