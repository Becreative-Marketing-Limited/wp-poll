<?php
/**
 * All Functions
 *
 * @author Liquidpoll
 */

use WPDK\Utils;

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'liquidpoll_export_button' ) ) {
	function liquidpoll_export_button() {

		wp_nonce_field( 'liquidpoll_export_nonce', 'liquidpoll_export_nonce_value' );

		printf( "<input type='hidden' name='action' value='liquidpoll_report_download_csv' />" );
		printf( "<input type='submit' class='button' value='Export Report' />" );
	}
}
add_action( 'pb_settings_before_liquidpoll_export_button', 'liquidpoll_export_button' );


if ( ! function_exists( 'liquidpoll_after_page_export' ) ) {
	function liquidpoll_after_page_export() {

		printf( "</form>" );
	}
}
add_action( 'pb_settings_after_page_liquidpoll-reports', 'liquidpoll_after_page_export' );


if ( ! function_exists( 'liquidpoll_before_page_export' ) ) {
	function liquidpoll_before_page_export() {

		$action_url = admin_url( 'admin-ajax.php' );

		printf( "<form action='%s' method='get'>", $action_url );
	}
}
add_action( 'pb_settings_before_page_liquidpoll-reports', 'liquidpoll_before_page_export' );


if ( ! function_exists( 'liquidpoll_is_page' ) ) {
	/**
	 * Return whether a page is $searched_page or not
	 *
	 * @param string $page_for
	 *
	 * @return bool
	 */
	function liquidpoll_is_page( $page_for = 'archive' ) {

		if ( ! in_array( $page_for, array( 'archive' ) ) ) {
			return false;
		}

		$page_id = liquidpoll()->get_option( 'liquidpoll_page_' . $page_for );

		if ( $page_id == get_the_ID() ) {
			return true;
		}

		return false;
	}
}


if ( ! function_exists( 'liquidpoll_get_poll' ) ) {
	/**
	 * Return Single Poll object
	 *
	 * @param bool $poll_id
	 *
	 * @return bool | LIQUIDPOLL_Poll
	 * @global LIQUIDPOLL_Poll $poll
	 */
	function liquidpoll_get_poll( $poll_id = false, $args = array() ) {

		$poll_id = ! $poll_id || 0 == $poll_id ? get_the_ID() : $poll_id;

		if ( get_post_type( $poll_id ) != 'poll' ) {
			return false;
		}

		return new LIQUIDPOLL_Poll( $poll_id, $args );
	}
}


if ( ! function_exists( 'liquidpoll_the_poll' ) ) {
	/**
	 * Set poll in global variable
	 *
	 * @param bool $poll_id
	 */
	function liquidpoll_the_poll( $poll_id = false ) {

		global $poll;

		if ( get_post_type( $poll_id ) == 'poll' && ! $poll instanceof LIQUIDPOLL_Poll ) {
			$poll = new LIQUIDPOLL_Poll( $poll_id );
		}
	}
}


if ( ! function_exists( 'liquidpoll_add_poll_option' ) ) {
	/**
	 * Return poll option HTML
	 *
	 * @param bool $unique_id
	 * @param array $args
	 */
	function liquidpoll_add_poll_option( $unique_id = false, $args = array() ) {

		global $post;

		if ( ! is_array( $args ) ) {
			$args = array( 'label' => $args );
		}

		$unique_id      = ! $unique_id ? hexdec( uniqid() ) : $unique_id;
		$option_label   = isset( $args['label'] ) ? $args['label'] : '';
		$option_thumb   = isset( $args['thumb'] ) ? $args['thumb'] : '';
		$is_frontend    = isset( $args['frontend'] ) ? $args['frontend'] : false;
		$poll_id        = isset( $args['poll_id'] ) ? $args['poll_id'] : $post->ID;
		$options_fields = array(
			array(
				'id'          => "poll_meta_options[$unique_id][label]",
				'title'       => esc_html__( 'Option label', 'wp-poll' ),
				'placeholder' => esc_html__( 'Option 1', 'wp-poll' ),
				'type'        => 'text',
				'value'       => $option_label,
			),
			array(
				'id'          => "poll_meta_options[$unique_id][thumb]",
				'title'       => esc_html__( 'Image', 'wp-poll' ),
				'placeholder' => esc_html__( 'Day 1', 'wp-poll' ),
				'type'        => 'media',
				'value'       => $option_thumb,
			),
			array(
				'id'      => "poll_meta_options[$unique_id][shortcode]",
				'title'   => esc_html__( 'Shortcode', 'wp-poll' ),
				'details' => sprintf( '<span class="shortcode tt--hint tt--top" aria-label="Click to Copy">[poller_list poll_id="%s" option_id="%s"]</span>', $post->ID, $unique_id ),
			),
		);

		?>

        <li class="poll-option-single">

			<?php liquidpoll()->PB_Settings()->generate_fields( array( array( 'options' => apply_filters( 'liquidpoll_filters_poll_options_fields', $options_fields, $poll_id, $unique_id, $args ) ) ) ); ?>

            <div class="poll-option-controls">
                <span class="option-remove dashicons dashicons-no-alt" data-status=0></span>
                <span class="option-move dashicons dashicons-move"></span>

				<?php if ( $is_frontend ) : ?>
                    <input type="hidden" name="poll_meta_options[<?php echo esc_attr( $unique_id ); ?>][frontend]"
                           value="<?php echo esc_attr( $is_frontend ); ?>">
                    <span class="option-external tt--hint tt--top"
                          aria-label="<?php esc_attr_e( 'Added on frontend', 'wp-poll' ); ?>"><span
                                class="dashicons dashicons-nametag"></span></span>
				<?php endif; ?>
            </div>
        </li>
		<?php
	}
}


if ( ! function_exists( 'liquidpoll' ) ) {
	/**
	 * Return global $liquidpoll
	 *
	 * @return LIQUIDPOLL_Functions
	 */
	function liquidpoll() {
		global $liquidpoll;

		if ( empty( $liquidpoll ) ) {
			$liquidpoll = new LIQUIDPOLL_Functions();
		}

		return $liquidpoll;
	}
}


if ( ! function_exists( 'liquidpoll_get_poller' ) ) {
	/**
	 * Return poller info
	 *
	 * @return int|mixed
	 */
	function liquidpoll_get_poller() {

		if ( is_user_logged_in() ) {
			return get_current_user_id();
		}

		return liquidpoll_get_ip_address();
	}
}


if ( ! function_exists( 'liquidpoll_get_ip_address' ) ) {
	/**
	 * Return IP Address
	 *
	 * @return mixed
	 */
	function liquidpoll_get_ip_address() {

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}


if ( ! function_exists( 'liquidpoll_poll_archive_class' ) ) {
	/**
	 * Return poll archive class container
	 *
	 * @param string $classes
	 */
	function liquidpoll_poll_archive_class( $classes = '' ) {

		if ( ! is_array( $classes ) ) {
			$classes = explode( "~", str_replace( array( ' ', ',', ', ' ), '~', $classes ) );
		}

		$classes[] = 'archive-poll';

		printf( 'class="%s"', esc_attr( implode( " ", apply_filters( 'liquidpoll_filters_poll_archive_class', $classes ) ) ) );
	}
}


if ( ! function_exists( 'liquidpoll_generate_classes' ) ) {
	/**
	 * Generate and return classes
	 *
	 * @param $classes
	 *
	 * @return string
	 */
	function liquidpoll_generate_classes( $classes ) {

		if ( ! is_array( $classes ) ) {
			$classes = explode( "~", str_replace( array( ' ', ',', ', ' ), '~', $classes ) );
		}

		return implode( " ", apply_filters( 'liquidpoll_generate_classes', array_filter( $classes ) ) );
	}
}


if ( ! function_exists( 'liquidpoll_single_post_class' ) ) {
	/**
	 * Return single post classes
	 *
	 * @param string $classes
	 */
	function liquidpoll_single_post_class( $classes = '' ) {

		global $poll;

		if ( ! is_array( $classes ) ) {
			$classes = explode( "~", str_replace( array( ' ', ',', ', ' ), '~', $classes ) );
		}

		$classes[] = sprintf( '%s-single', $poll->get_type() );
		$classes[] = sprintf( 'theme-%s', $poll->get_theme() );
		$classes[] = sprintf( 'poll-type-%s', $poll->get_type() );
		$classes[] = sprintf( 'results-type-%s', $poll->get_meta( '_results_type', 'votes' ) );

		if ( '1' == $poll->get_meta( 'poll_form_enable', '0' ) || 'yes' == $poll->get_meta( 'poll_form_enable', '0' ) ) {
			$classes[] = 'has-form';
		}

		printf( 'class="%s"', liquidpoll_generate_classes( $classes ) );
	}
}


if ( ! function_exists( 'liquidpoll_options_single_class' ) ) {
	/**
	 * Return options single classes
	 *
	 * @param string $classes
	 * @param LIQUIDPOLL_Poll|null $poll
	 */
	function liquidpoll_options_single_class( $classes = '', \LIQUIDPOLL_Poll $poll = null ) {

		if ( ! is_array( $classes ) ) {
			$classes = explode( "~", str_replace( array( ' ', ',', ', ' ), '~', $classes ) );
		}

		if ( ! $poll ) {
			global $poll;
		}

		$options_theme = $poll->get_style( 'options_theme' );


		// Check multiple or single vote
		$classes[] = $poll->can_vote_multiple() ? 'liquidpoll-checkbox' : 'liquidpoll-radio';


		// Add Theme class
		$classes[] = sprintf( 'liquidpoll-option-list-%s', $options_theme );


		// Add common class excluding for Theme - 1
		if ( $options_theme != 1 && $options_theme != 2 ) {
			$classes[] = 'liquidpoll-custom';
		}


		// Add checkbox animation class excluding for Theme - 1
		if ( $options_theme != 1 && $options_theme != 2 && $poll->can_vote_multiple() ) {
			$classes[] = sprintf( 'liquidpoll-%s', $poll->get_style( 'animation_checkbox' ) );
		}


		// Add radio animation class excluding for Theme - 1
		if ( $options_theme != 1 && $options_theme != 2 && ! $poll->can_vote_multiple() ) {
			$classes[] = sprintf( 'liquidpoll-%s', $poll->get_style( 'animation_radio' ) );
		}

		printf( 'class="%s"', esc_attr( implode( " ", apply_filters( 'liquidpoll_options_single_class', $classes ) ) ) );
	}
}


if ( ! function_exists( 'liquidpoll_get_template_part' ) ) {
	/**
	 * Get Template Part
	 *
	 * @param $slug
	 * @param string $name
	 * @param bool $ext_template When you call a template from extensions you can use this param as true to check from main template only
	 */
	function liquidpoll_get_template_part( $slug, $name = '', $ext_template = false ) {

		$template   = '';
		$plugin_dir = LIQUIDPOLL_PLUGIN_DIR;

		/**
		 * Locate template
		 */
		if ( ! empty( $name ) ) {
			$template = locate_template( array(
				"{$slug}-{$name}.php",
				"liquidpoll/{$slug}-{$name}.php"
			) );
		}

		// Search in external
		if ( $ext_template ) {
			$plugin_dir = LIQUIDPOLL_PRO_PLUGIN_DIR;
		}

		/**
		 * Search for Template in Plugin
		 *
		 * @in Plugin
		 */
		if ( ! $template && $name && file_exists( untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php" ) ) {
			$template = untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php";
		}


		/**
		 * Search for Template in Theme
		 *
		 * @in Theme
		 */
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php", "liquidpoll/{$slug}.php" ) );
		}

		/**
		 * Allow 3rd party plugins to filter template file from their plugin.
		 *
		 * @filter liquidpoll_filters_get_template_part
		 */
		$template = apply_filters( 'liquidpoll_filters_get_template_part', $template, $slug, $name );


		if ( $template ) {
			load_template( $template, false );
		}
	}
}


if ( ! function_exists( 'liquidpoll_get_template' ) ) {
	/**
	 * Get Template
	 *
	 * @param $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return WP_Error
	 */
	function liquidpoll_get_template( $template_name, $args = array(), $template_path = '', $default_path = '', $main_template = false ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		/**
		 * Check directory for templates from Addons
		 */
		$backtrace      = debug_backtrace( 2, true );
		$backtrace      = empty( $backtrace ) ? array() : $backtrace;
		$backtrace      = reset( $backtrace );
		$backtrace_file = isset( $backtrace['file'] ) ? $backtrace['file'] : '';

		$located = liquidpoll_locate_template( $template_name, $template_path, $default_path, $backtrace_file, $main_template );

		if ( ! file_exists( $located ) ) {
			return new WP_Error( 'invalid_data', __( '%s does not exist.', 'wp-poll' ), '<code>' . $located . '</code>' );
		}

		$located = apply_filters( 'liquidpoll_filters_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'liquidpoll_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'liquidpoll_after_template_part', $template_name, $template_path, $located, $args );
	}
}


if ( ! function_exists( 'liquidpoll_locate_template' ) ) {
	/**
	 *  Locate template
	 *
	 * @param $template_name
	 * @param string $template_path
	 * @param string $default_path
	 * @param string $backtrace_file
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return mixed|void
	 */
	function liquidpoll_locate_template( $template_name, $template_path = '', $default_path = '', $backtrace_file = '', $main_template = false ) {

		$plugin_dir = LIQUIDPOLL_PLUGIN_DIR;

		/**
		 * Template path in Theme
		 */
		if ( ! $template_path ) {
			$template_path = 'liquidpoll/';
		}

		// Check for Poll Pro
		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'wp-poll-pro' ) !== false && defined( 'LIQUIDPOLL_PRO_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? LIQUIDPOLL_PLUGIN_DIR : LIQUIDPOLL_PRO_PLUGIN_DIR;
		}


		/**
		 * Template default path from Plugin
		 */
		if ( ! $default_path ) {
			$default_path = untrailingslashit( $plugin_dir ) . '/templates/';
		}

		/**
		 * Look within passed path within the theme - this is priority.
		 */
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		/**
		 * Get default template
		 */
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		/**
		 * Return what we found with allowing 3rd party to override
		 *
		 * @filter liquidpoll_filters_locate_template
		 */
		return apply_filters( 'liquidpoll_filters_locate_template', $template, $template_name, $template_path );
	}
}


if ( ! function_exists( 'liquidpoll_pagination' ) ) {
	/**
	 * Return Pagination HTML Content
	 *
	 * @param bool $query_object
	 * @param array $args
	 *
	 * @return array|string|void
	 */
	function liquidpoll_pagination( $query_object = false, $args = array() ) {

		global $wp_query;

		$previous_query = $wp_query;

		if ( $query_object ) {
			$wp_query = $query_object;
		}

		$paged = max( 1, ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1 );

		$defaults = array(
			'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'    => '?paged=%#%',
			'current'   => $paged,
			'total'     => $wp_query->max_num_pages,
			'prev_text' => esc_html__( 'Previous', 'wp-poll' ),
			'next_text' => esc_html__( 'Next', 'wp-poll' ),
		);

		$args           = apply_filters( 'liquidpoll_filters_pagination', array_merge( $defaults, $args ) );
		$paginate_links = paginate_links( $args );

		$wp_query = $previous_query;

		return $paginate_links;
	}
}


if ( ! function_exists( 'liquidpoll_create_table' ) ) {
	/**
	 * Create table if not exists
	 */
	function liquidpoll_create_table() {

		if ( ! function_exists( 'maybe_create_table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		}

		$sql_results_table = "CREATE TABLE IF NOT EXISTS " . LIQUIDPOLL_RESULTS_TABLE . " (
			id int(100) NOT NULL AUTO_INCREMENT,
			poll_id int(100) NOT NULL,
			poll_type VARCHAR(255) NOT NULL,
			poller_id_ip VARCHAR(255) NOT NULL,
			polled_value VARCHAR(1024) NOT NULL,
			polled_comments VARCHAR(1024),
			datetime DATETIME NOT NULL,
			UNIQUE KEY id (id)
		)";

		$sql_emails_table = "CREATE TABLE IF NOT EXISTS " . LIQUIDPOLL_EMAILS_TABLE . " (
			id int(100) NOT NULL AUTO_INCREMENT,
			poll_id int(100) NOT NULL,
			poller_id_ip VARCHAR(255) NOT NULL,
			first_name VARCHAR(255) NOT NULL,
			last_name VARCHAR(255) NOT NULL,
			email_address VARCHAR(255) NOT NULL,
			consent VARCHAR(255),
			datetime DATETIME NOT NULL,
			UNIQUE KEY id (id)
		)";

		$sql_meta_table = "CREATE TABLE IF NOT EXISTS " . LIQUIDPOLL_META_TABLE . " (
			id int(100) NOT NULL AUTO_INCREMENT,
			result_id int(100) NOT NULL,
			meta_key VARCHAR(255) NOT NULL,
			meta_value VARCHAR(1024) NOT NULL,
			datetime DATETIME NOT NULL,
			UNIQUE KEY id (id)
		)";

		maybe_create_table( LIQUIDPOLL_RESULTS_TABLE, $sql_results_table );
		maybe_create_table( LIQUIDPOLL_EMAILS_TABLE, $sql_emails_table );
		maybe_create_table( LIQUIDPOLL_META_TABLE, $sql_meta_table );

		if ( ! function_exists( 'maybe_add_column' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		}

		$sql_add_column = "ALTER TABLE " . LIQUIDPOLL_EMAILS_TABLE . " ADD consent VARCHAR(255) AFTER email_address";

		maybe_add_column( LIQUIDPOLL_EMAILS_TABLE, 'consent', $sql_add_column );
	}
}


if ( ! function_exists( 'liquidpoll_insert_results' ) ) {
	/**
	 * Insert poll results in database
	 *
	 * @param array $args
	 * @param bool $allow_multi_submission
	 *
	 * @return WP_Error|bool
	 */
	function liquidpoll_insert_results( $args = array(), $allow_multi_submission = false ) {

		global $wpdb, $poll;

		$defaults = array(
			'poll_id'         => $poll instanceof LIQUIDPOLL_Poll ? $poll->get_id() : '',
			'poll_type'       => 'poll',
			'poller_id_ip'    => liquidpoll_get_poller(),
			'polled_value'    => '',
			'polled_comments' => '',
			'datetime'        => current_time( 'mysql' ),
		);
		$args     = wp_parse_args( $args, $defaults );
//		$entry_count = $wpdb->get_var( "SELECT COUNT(*) FROM " . LIQUIDPOLL_RESULTS_TABLE . " WHERE poll_type = '{$args['poll_type']}' AND poller_id_ip = '{$args['poller_id_ip']}'" );
//		if ( $entry_count > 0 ) {
//			return new WP_Error( 'duplicate_try', esc_html__( 'Already voted', 'wp-poll' ) );
//		}

		$response = $wpdb->insert( LIQUIDPOLL_RESULTS_TABLE, $args );

		if ( ! $response ) {
			return new WP_Error( 'database_error', $wpdb->last_error );
		}

		return true;
	}
}


if ( ! function_exists( 'liquidpoll_insert_email' ) ) {
	/**
	 * Insert poll email in database
	 *
	 * @param array $args
	 *
	 * @return WP_Error|bool
	 */
	function liquidpoll_insert_email( $args = array() ) {

		global $wpdb;

		$defaults    = array(
			'poll_id'       => '',
			'poller_id_ip'  => liquidpoll_get_poller(),
			'first_name'    => '',
			'last_name'     => '',
			'email_address' => '',
			'consent'       => '',
			'datetime'      => current_time( 'mysql' ),
		);
		$args        = wp_parse_args( $args, $defaults );
		$entry_count = $wpdb->get_var( "SELECT COUNT(*) FROM " . LIQUIDPOLL_EMAILS_TABLE . " WHERE poll_id = '{$args['poll_id']}' AND email_address = '{$args['email_address']}'" );

		if ( $entry_count > 0 ) {
			return new WP_Error( 'duplicate_try', esc_html__( 'Already in the list', 'wp-poll' ) );
		}

		$response = $wpdb->insert( LIQUIDPOLL_EMAILS_TABLE, $args );

		if ( ! $response ) {
			return new WP_Error( 'database_error', $wpdb->last_error );
		}

		do_action( 'liquidpoll_email_added_local', $args, $response );

		return true;
	}
}


if ( ! function_exists( 'liquidpoll_apply_css' ) ) {
	/**
	 * Appdly dynamic CSS
	 *
	 * @param string $selector
	 * @param array $css_arr
	 */
	function liquidpoll_apply_css( $selector = '', $css_arr = array() ) {

		global $poll;

		if ( empty( $selector ) || empty( $css_arr ) || ! is_array( $css_arr ) ) {
			return;
		}

		ob_start();

		foreach ( $css_arr as $property => $value ) {

			if ( in_array( $property, array( 'type', 'unit' ) ) ) {
				continue;
			}

			if ( in_array( $property, array( 'font-size', 'line-height', 'letter-spacing' ) ) ) {
				$value = $value . Utils::get_args_option( 'unit', $css_arr );
			}

			if ( ! empty( $value ) ) {
				printf( '%s: %s;', $property, $value );
			}
		}

		liquidpoll()->add_global_style( sprintf( '%s {%s}', $selector, ob_get_clean() ) );
	}
}


if ( ! function_exists( 'liquidpoll_resizer' ) ) {
	/**
	 * Resize images
	 *
	 * @param $url
	 * @param null $width
	 * @param null $height
	 * @param null $crop
	 * @param bool $single
	 * @param false $upscale
	 *
	 * @return array|false|mixed|string
	 */
	function liquidpoll_resizer( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			global $sitepress;
			$url = $sitepress->convert_url( $url, $sitepress->get_default_language() );
		}

		$liquidpoll_resize = LIQUIDPOLL_Resizer::getInstance();

		try {
			return $liquidpoll_resize->process( $url, $width, $height, $crop, $single, $upscale );
		} catch ( LIQUIDPOLL_Exception $e ) {
			return false;
		}
	}
}


if ( ! function_exists( 'liquidpoll_get_feedback_form' ) ) {
	/**
	 * Return html content for feedback form
	 *
	 * @return false|string
	 */
	function liquidpoll_get_feedback_form() {

		ob_start();
		liquidpoll_single_poll_form();

		return ob_get_clean();

	}
}


if ( ! function_exists( 'liquidpoll_calculate_themes' ) ) {
	/**
	 * Calculate themes for each type
	 *
	 * @param $themes
	 *
	 * @return array
	 */
	function liquidpoll_calculate_themes( $themes ) {

		$calculated_themes = array();

		foreach ( $themes as $theme_id => $theme ) {

			$availability = Utils::get_args_option( 'availability', $theme );
			$theme_label  = Utils::get_args_option( 'label', $theme );

			if ( 'pro' == $availability && ! liquidpoll()->is_pro() ) {
				$calculated_themes[998] = esc_html__( '7+ are in pro', 'wp-poll' );
				continue;
			}

			$calculated_themes[ $theme_id ] = $theme_label;
		}

		return $calculated_themes;
	}


	/**
	 * Get poller name from form table
	 *
	 * @param $poll_id
	 * @param $poller_id_ip
	 *
	 * @return string|null
	 */
	if ( ! function_exists( 'liquidpoll_get_poller_name' ) ) {
		function liquidpoll_get_poller_name( $poll_id, $poller_id_ip ) {
			global $wpdb;

			$poller_name = $wpdb->get_var( $wpdb->prepare( "SELECT first_name FROM " . LIQUIDPOLL_EMAILS_TABLE . " WHERE poll_id = %s AND poller_id_ip = %s", $poll_id, $poller_id_ip ) );

			return $poller_name;
		}
	}


	/**
	 * Get poller info from form table
	 *
	 * @param $poll_id
	 * @param $poller_id_ip
	 *
	 * @return string|null
	 */
	if ( ! function_exists( 'liquidpoll_get_data_from_email_table' ) ) {
		function liquidpoll_get_data_from_email_table( $poll_id, $poller_id_ip ) {
			global $wpdb;

			$poller_info = $wpdb->get_results( $wpdb->prepare( "SELECT first_name,last_name,email_address,consent FROM " . LIQUIDPOLL_EMAILS_TABLE . " WHERE poll_id = %s AND poller_id_ip = %s", $poll_id, $poller_id_ip ), ARRAY_A );

			return end( $poller_info );
		}
	}



	/**
	 * Render review star
	 */
	if ( ! function_exists( 'render_review_star' ) ) {
		function render_review_star() {
			return '<svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g filter="url(#filter0_d_1537_1548)">
                    <path d="M22.554 5.74151C23.0186 4.75426 23.2509 4.26063 23.5662 4.10291C23.8406 3.9657 24.1594 3.9657 24.4338 4.10291C24.7491 4.26063 24.9814 4.75426 25.446 5.74151L29.8538 15.1078C29.991 15.3993 30.0595 15.545 30.1598 15.6582C30.2485 15.7584 30.3549 15.8395 30.4732 15.8972C30.6067 15.9623 30.76 15.9858 31.0666 16.0328L40.9262 17.5444C41.9644 17.7036 42.4835 17.7832 42.7238 18.0492C42.9328 18.2806 43.0311 18.5986 42.9913 18.9146C42.9456 19.2779 42.5698 19.6618 41.8181 20.4298L34.6863 27.7157C34.464 27.9429 34.3529 28.0564 34.2811 28.1916C34.2176 28.3112 34.1769 28.4426 34.1612 28.5786C34.1434 28.7321 34.1696 28.8925 34.2221 29.2134L35.9049 39.5045C36.0824 40.5899 36.1711 41.1326 36.0043 41.4547C35.8592 41.7349 35.6013 41.9314 35.3024 41.9896C34.9588 42.0564 34.4942 41.8001 33.5649 41.2875L24.7506 36.4255C24.476 36.274 24.3386 36.1982 24.194 36.1685C24.0659 36.1421 23.9341 36.1421 23.806 36.1685C23.6614 36.1982 23.524 36.274 23.2494 36.4255L14.4351 41.2875C13.5058 41.8001 13.0412 42.0564 12.6976 41.9896C12.3987 41.9314 12.1408 41.7349 11.9957 41.4547C11.8289 41.1326 11.9176 40.5899 12.0951 39.5045L13.7779 29.2134C13.8304 28.8925 13.8566 28.7321 13.8388 28.5786C13.8231 28.4426 13.7824 28.3112 13.7189 28.1916C13.6471 28.0564 13.536 27.9429 13.3137 27.7157L6.18191 20.4298C5.43025 19.6618 5.05442 19.2779 5.00868 18.9146C4.96889 18.5986 5.06719 18.2806 5.27622 18.0492C5.51647 17.7832 6.03559 17.7036 7.07385 17.5444L16.9334 16.0328C17.24 15.9858 17.3933 15.9623 17.5268 15.8972C17.6451 15.8395 17.7515 15.7584 17.8402 15.6582C17.9404 15.545 18.009 15.3993 18.1462 15.1078L22.554 5.74151Z" fill="url(#paint0_linear_1537_1548)"/>
                    <path d="M22.554 5.74151C23.0186 4.75426 23.2509 4.26063 23.5662 4.10291C23.8406 3.9657 24.1594 3.9657 24.4338 4.10291C24.7491 4.26063 24.9814 4.75426 25.446 5.74151L29.8538 15.1078C29.991 15.3993 30.0595 15.545 30.1598 15.6582C30.2485 15.7584 30.3549 15.8395 30.4732 15.8972C30.6067 15.9623 30.76 15.9858 31.0666 16.0328L40.9262 17.5444C41.9644 17.7036 42.4835 17.7832 42.7238 18.0492C42.9328 18.2806 43.0311 18.5986 42.9913 18.9146C42.9456 19.2779 42.5698 19.6618 41.8181 20.4298L34.6863 27.7157C34.464 27.9429 34.3529 28.0564 34.2811 28.1916C34.2176 28.3112 34.1769 28.4426 34.1612 28.5786C34.1434 28.7321 34.1696 28.8925 34.2221 29.2134L35.9049 39.5045C36.0824 40.5899 36.1711 41.1326 36.0043 41.4547C35.8592 41.7349 35.6013 41.9314 35.3024 41.9896C34.9588 42.0564 34.4942 41.8001 33.5649 41.2875L24.7506 36.4255C24.476 36.274 24.3386 36.1982 24.194 36.1685C24.0659 36.1421 23.9341 36.1421 23.806 36.1685C23.6614 36.1982 23.524 36.274 23.2494 36.4255L14.4351 41.2875C13.5058 41.8001 13.0412 42.0564 12.6976 41.9896C12.3987 41.9314 12.1408 41.7349 11.9957 41.4547C11.8289 41.1326 11.9176 40.5899 12.0951 39.5045L13.7779 29.2134C13.8304 28.8925 13.8566 28.7321 13.8388 28.5786C13.8231 28.4426 13.7824 28.3112 13.7189 28.1916C13.6471 28.0564 13.536 27.9429 13.3137 27.7157L6.18191 20.4298C5.43025 19.6618 5.05442 19.2779 5.00868 18.9146C4.96889 18.5986 5.06719 18.2806 5.27622 18.0492C5.51647 17.7832 6.03559 17.7036 7.07385 17.5444L16.9334 16.0328C17.24 15.9858 17.3933 15.9623 17.5268 15.8972C17.6451 15.8395 17.7515 15.7584 17.8402 15.6582C17.9404 15.545 18.009 15.3993 18.1462 15.1078L22.554 5.74151Z" stroke="#FFF0EA" stroke-width="3" stroke-linecap="round" stroke-linejoin="bevel"/>
                    </g>
                    <defs>
                    <filter id="filter0_d_1537_1548" x="0.5" y="0.5" width="51" height="50.9999" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                    <feOffset dx="2" dy="3"/>
                    <feGaussianBlur stdDeviation="2.5"/>
                    <feComposite in2="hardAlpha" operator="out"/>
                    <feColorMatrix type="matrix" values="0 0 0 0 0.24974 0 0 0 0 0.281849 0 0 0 0 0.570833 0 0 0 0.41 0"/>
                    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_1537_1548"/>
                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_1537_1548" result="shape"/>
                    </filter>
                    <linearGradient id="paint0_linear_1537_1548" x1="24" y1="4" x2="24" y2="42" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#5F64EB"/>
                    <stop offset="1" stop-color="#B9BCFF"/>
                    </linearGradient>
                    </defs>
                    </svg>';
		}
	}
}