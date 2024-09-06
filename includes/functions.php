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
		$ip = '';

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) );
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
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
			/* translators: %s: file path */
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

		$sql_meta_table = "CREATE TABLE IF NOT EXISTS " . LIQUIDPOLL_RESULTS_META_TABLE . " (
			id int(100) NOT NULL AUTO_INCREMENT,
			result_id int(100) NOT NULL,
			meta_key VARCHAR(255) NOT NULL,
			meta_value VARCHAR(1024) NOT NULL,
			datetime DATETIME NOT NULL,
			UNIQUE KEY id (id)
		)";

		maybe_create_table( LIQUIDPOLL_RESULTS_TABLE, $sql_results_table );
		maybe_create_table( LIQUIDPOLL_EMAILS_TABLE, $sql_emails_table );
		maybe_create_table( LIQUIDPOLL_RESULTS_META_TABLE, $sql_meta_table );

		if ( ! function_exists( 'maybe_add_column' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		}

		$sql_add_column = "ALTER TABLE " . LIQUIDPOLL_EMAILS_TABLE . " ADD consent VARCHAR(255) AFTER email_address";
		$sql_add_status_column = "ALTER TABLE " . LIQUIDPOLL_RESULTS_TABLE . " ADD status VARCHAR(255) DEFAULT 'approved' AFTER polled_comments";

		maybe_add_column( LIQUIDPOLL_EMAILS_TABLE, 'consent', $sql_add_column );
		maybe_add_column( LIQUIDPOLL_RESULTS_TABLE, 'status', $sql_add_status_column );
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
			'status' => '',
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

		return $wpdb->insert_id;
	}
}


if ( ! function_exists( 'liquidpoll_update_results_meta' ) ) {
	/**
	 * Insert poll results meta in database
	 *
	 * @param $result_id
	 * @param $meta_key
	 * @param $meta_value
	 *
	 * @return int|WP_Error
	 */
	function liquidpoll_update_results_meta( $result_id, $meta_key, $meta_value ) {
		global $wpdb;

		$meta_value = is_array( $meta_value ) ? serialize( $meta_value ) : $meta_value;
		$args       = array(
			'result_id'  => $result_id,
			'meta_key'   => $meta_key,
			'meta_value' => $meta_value,
			'datetime'   => current_time( 'mysql' ),
		);

		if ( ! empty( liquidpoll_get_results_meta( $result_id, $meta_key ) ) ) {
			$response = $wpdb->update( 
				$wpdb->prepare( '%s', LIQUIDPOLL_RESULTS_META_TABLE ),
				array(
					'meta_value' => $meta_value,
				),
				array(
					'result_id' => $result_id,
					'meta_key'  => $meta_key
				)
			);
		} else {
			$response = $wpdb->insert( $wpdb->prepare( '%s', LIQUIDPOLL_RESULTS_META_TABLE ), $args );
		}

		if ( ! $response ) {
			return new WP_Error( 'database_error', $wpdb->last_error );
		}

		return $wpdb->insert_id;
	}
}


if ( ! function_exists( 'liquidpoll_get_results_meta' ) ) {
	/**
	 * Return result meta value
	 *
	 * @param $result_id
	 * @param $meta_key
	 * @param $default
	 *
	 * @return false|string
	 */
	function liquidpoll_get_results_meta( $result_id, $meta_key, $default = '' ) {
		global $wpdb;

		$meta_value = $wpdb->get_var( $wpdb->prepare( 
			"SELECT meta_value FROM {$wpdb->prepare( '%s', LIQUIDPOLL_RESULTS_META_TABLE )} WHERE result_id = %s AND meta_key = %s", 
			$result_id, 
			$meta_key 
		) );

		if ( $meta_value ) {
			return is_serialized( $meta_value ) ? unserialize( $meta_value ) : $meta_value;
		}

		return false;
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
		$entry_count = $wpdb->get_var( $wpdb->prepare( 
			"SELECT COUNT(*) FROM {$wpdb->prepare( '%s', LIQUIDPOLL_EMAILS_TABLE )} WHERE poll_id = %s AND email_address = %s",
			$args['poll_id'],
			$args['email_address']
		) );

		if ( $entry_count > 0 ) {
			return new WP_Error( 'duplicate_try', esc_html__( 'Already in the list', 'wp-poll' ) );
		}

		$response = $wpdb->insert( $wpdb->prepare( '%s', LIQUIDPOLL_EMAILS_TABLE ), $args );

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
}


if ( ! function_exists( 'liquidpoll_get_poller_name' ) ) {
	/**
	 * Get poller name from form table
	 *
	 * @param $poll_id
	 * @param $poller_id_ip
	 *
	 * @return string|null
	 */
	function liquidpoll_get_poller_name( $poll_id, $poller_id_ip ) {
		global $wpdb;

		$poller_name = $wpdb->get_var( $wpdb->prepare( 
			"SELECT first_name FROM {$wpdb->prepare( '%s', LIQUIDPOLL_EMAILS_TABLE )} WHERE poll_id = %s AND poller_id_ip = %s", 
			$poll_id, 
			$poller_id_ip 
		) );

		return $poller_name;
	}
}


if ( ! function_exists( 'liquidpoll_get_data_from_email_table' ) ) {
	/**
	 * Get poller info from form table
	 *
	 * @param $poll_id
	 * @param $poller_id_ip
	 *
	 * @return string|null
	 */
	function liquidpoll_get_data_from_email_table( $poll_id, $poller_id_ip ) {
		global $wpdb;

		$poller_info = $wpdb->get_results( $wpdb->prepare( 
			"SELECT first_name,last_name,email_address,consent FROM {$wpdb->prepare( '%s', LIQUIDPOLL_EMAILS_TABLE )} WHERE poll_id = %s AND poller_id_ip = %s", 
			$poll_id, 
			$poller_id_ip 
		), ARRAY_A );

		return end( $poller_info );
	}
}


if ( ! function_exists( 'liquidpoll_get_review_stars' ) ) {
	/**
	 * @param $rating
	 * @param $theme
	 *
	 * @return false|string
	 */
	function liquidpoll_get_review_stars( $rating = 0, $theme = 1 ) {

		$is_checked       = false;
		$rating           = empty( $rating ) ? 0 : $rating;
		$selectable_class = $rating > 0 ? 'not-selectable' : '';

		ob_start();

		echo '<div class="rating-wrap ' . esc_attr( $selectable_class . ' theme-' . $theme ) . '">';

		for ( $index = 5; $index > 0; -- $index ) {

			$unique_id_full  = uniqid();
			$unique_id_half  = uniqid();
			$value_full      = $index;
			$value_half      = ( $index + 0.5 ) - 1;
			$is_checked_full = $index <= $rating ? 'checked' : '';
			$is_checked_half = $index - $rating == 0.5 ? 'checked' : '';
			$active_class    = '';

			if ( $is_checked ) {
				$is_checked_full = $is_checked_half = '';
			}

			if ( ! empty( $is_checked_half ) ) {
				$is_checked   = true;
				$active_class = 'active';
			}

			if ( ! empty( $is_checked_full ) ) {
				$is_checked   = true;
				$active_class = 'active';
			}

			echo '<div class="rating-item ' . esc_attr( $active_class ) . '">';

			echo '<input type="radio" id="' . $unique_id_full . '" class="rating-checkbox" ' . $is_checked_full . ' value="' . $value_full . '" name="rating"/>';
			echo '<label class="full" for="' . $unique_id_full . '"><svg role="img" aria-label="rating"><use xlink:href="#star"></use></svg></label>';

			echo '<input type="radio" id="' . $unique_id_half . '" class="rating-checkbox" ' . $is_checked_half . ' value="' . $value_half . '" name="rating"/>';
			echo '<label class="half" for="' . $unique_id_half . '" name="star"><svg role="img" aria-label="rating"><use xlink:href="#star"></use></svg></label>';

			echo '</div>';
		}

		echo '</div>';

		echo '<svg style="position: absolute; width: 0; height: 0; overflow: hidden" width="42" height="42" viewBox="0 0 42 42" xmlns="http://www.w3.org/2000/svg">';
		echo '<symbol id="star"><path d="M19.554 3.74151C20.0186 2.75426 20.2509 2.26063 20.5662 2.10291C20.8406 1.9657 21.1594 1.9657 21.4338 2.10291C21.7491 2.26063 21.9814 2.75426 22.446 3.74151L26.8538 13.1078C26.991 13.3993 27.0595 13.545 27.1598 13.6582C27.2485 13.7584 27.3549 13.8395 27.4732 13.8972C27.6067 13.9623 27.76 13.9858 28.0666 14.0328L37.9262 15.5444C38.9644 15.7036 39.4835 15.7832 39.7238 16.0492C39.9328 16.2806 40.0311 16.5986 39.9913 16.9146C39.9456 17.2779 39.5698 17.6618 38.8181 18.4298L31.6863 25.7157C31.464 25.9429 31.3529 26.0564 31.2811 26.1916C31.2176 26.3112 31.1769 26.4426 31.1612 26.5786C31.1434 26.7321 31.1696 26.8925 31.2221 27.2134L32.9049 37.5045C33.0824 38.5899 33.1711 39.1326 33.0043 39.4547C32.8592 39.7349 32.6013 39.9314 32.3024 39.9896C31.9588 40.0564 31.4942 39.8001 30.5649 39.2875L21.7506 34.4255C21.4759 34.274 21.3386 34.1982 21.194 34.1685C21.0659 34.1421 20.9341 34.1421 20.806 34.1685C20.6614 34.1982 20.524 34.274 20.2494 34.4255L11.4351 39.2875C10.5058 39.8001 10.0412 40.0564 9.69763 39.9896C9.39872 39.9314 9.14078 39.7349 8.99568 39.4547C8.8289 39.1326 8.91764 38.5899 9.09513 37.5045L10.7779 27.2134C10.8304 26.8925 10.8566 26.7321 10.8388 26.5786C10.8231 26.4426 10.7824 26.3112 10.7189 26.1916C10.6471 26.0564 10.536 25.9429 10.3137 25.7157L3.18191 18.4298C2.43025 17.6618 2.05442 17.2779 2.00868 16.9146C1.96889 16.5986 2.06719 16.2806 2.27622 16.0492C2.51647 15.7832 3.03559 15.7036 4.07385 15.5444L13.9334 14.0328C14.24 13.9858 14.3933 13.9623 14.5268 13.8972C14.6451 13.8395 14.7515 13.7584 14.8402 13.6582C14.9405 13.545 15.009 13.3993 15.1462 13.1078L19.554 3.74151Z" stroke-linecap="round" stroke-linejoin="bevel"/></symbol>';
		echo '</svg>';

		return ob_get_clean();
	}
}


if ( ! function_exists( 'liquidpoll_get_poller_submission_count' ) ) {
	function liquidpoll_get_poller_submission_count( $poller_id_ip = '', $poll_type = 'poll' ) {
		global $wpdb;

		$poller_id_ip = empty( $poller_id_ip ) ? get_current_user_id() : $poller_id_ip;

		return $wpdb->get_var( $wpdb->prepare( 
			"SELECT COUNT(*) FROM {$wpdb->prepare( '%s', LIQUIDPOLL_RESULTS_TABLE )} WHERE poller_id_ip = %s AND poll_type = %s",
			$poller_id_ip,
			$poll_type
		) );
	}
}


if ( ! function_exists( 'liquidpoll_is_current_user_useful_submitted' ) ) {
	/**
	 * @param $result_id
	 * @param $current_user
	 *
	 * @return bool
	 */
	function liquidpoll_is_current_user_useful_submitted( $result_id, $current_user ) {

		$user_useful_submitted = false;
		$useful_data           = liquidpoll_get_results_meta( $result_id, 'results_useful_data' );

		if ( ! $useful_data ) {
			return false;
		}

		if ( ! empty( $useful_data ) && in_array( $current_user, array_column( $useful_data, 'poller_id_ip' ) ) ) {
			$user_useful_submitted = true;
		}

		return $user_useful_submitted;
	}
}


if ( ! function_exists( 'liquidpoll_get_poller_location' ) ) {

	/**
	 * @param $ip_address
	 *
	 * @return string
	 */
	function liquidpoll_get_poller_location( $ip_address ) {
		if ( empty( $ipinfo_token = Utils::get_option( 'liquidpoll_ipinfo_token' ) ) ) {
			return esc_html__( 'Earth', 'wp-poll' );
		}

		if ( ! $ip_address ) {
			return esc_html__( 'Earth', 'wp-poll' );
		}

		if ( is_wp_error( $response = wp_remote_get( 'https://ipinfo.io/' . $ip_address . '/json?token=' . $ipinfo_token ) ) ) {
			return esc_html__( 'Earth', 'wp-poll' );
		}

		$response = wp_remote_retrieve_body( $response );
		$response = json_decode( $response, true );

		if ( isset( $response['city'] ) && isset( $response['country'] ) ) {
			/* translators: %1$s: city name, %2$s: country name */
			return sprintf( esc_html__( '%1$s, %2$s', 'wp-poll' ), $response['city'], $response['country'] );
		}

		return esc_html__( 'Earth', 'wp-poll' );
	}
}