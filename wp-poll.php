<?php
/**
 * Plugin Name: LiquidPoll - Polls, Surveys, NPS and Feedback Reviews
 * Plugin URI: https://liquidpoll.com
 * Description: It allows user to poll in your website with many awesome features.
 * Version: 3.3.79
 * Author: LiquidPoll
 * Text Domain: wp-poll
 * Domain Path: /languages/
 * Author URI: https://liquidpoll.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;

defined( 'LIQUIDPOLL_PLUGIN_URL' ) || define( 'LIQUIDPOLL_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
defined( 'LIQUIDPOLL_PLUGIN_DIR' ) || define( 'LIQUIDPOLL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'LIQUIDPOLL_PLUGIN_FILE' ) || define( 'LIQUIDPOLL_PLUGIN_FILE', plugin_basename( __FILE__ ) );
defined( 'LIQUIDPOLL_RESULTS_TABLE' ) || define( 'LIQUIDPOLL_RESULTS_TABLE', sprintf( '%sliquidpoll_results', $wpdb->prefix ) );
defined( 'LIQUIDPOLL_RESULTS_META_TABLE' ) || define( 'LIQUIDPOLL_RESULTS_META_TABLE', sprintf( '%sliquidpoll_results_meta', $wpdb->prefix ) );
defined( 'LIQUIDPOLL_EMAILS_TABLE' ) || define( 'LIQUIDPOLL_EMAILS_TABLE', sprintf( '%sliquidpoll_emails', $wpdb->prefix ) );
defined( 'LIQUIDPOLL_PLUGIN_LINK' ) || define( 'LIQUIDPOLL_PLUGIN_LINK', 'https://www.liquidpoll.com/pro' );
defined( 'LIQUIDPOLL_DOCS_URL' ) || define( 'LIQUIDPOLL_DOCS_URL', 'https://www.liquidpoll.com/docs' );
defined( 'LIQUIDPOLL_ACCOUNT_URL' ) || define( 'LIQUIDPOLL_ACCOUNT_URL', 'https://www.liquidpoll.com/customer-dashboard/' );
defined( 'LIQUIDPOLL_REVIEW_URL' ) || define( 'LIQUIDPOLL_REVIEW_URL', 'https://wordpress.org/support/plugin/wp-poll/reviews/#new-post' );
defined( 'LIQUIDPOLL_TICKET_URL' ) || define( 'LIQUIDPOLL_TICKET_URL', 'support@liquidpoll.com' );
defined( 'LIQUIDPOLL_COMMUNITY_URL' ) || define( 'LIQUIDPOLL_COMMUNITY_URL', 'https://www.facebook.com/groups/liquidpoll/' );
defined( 'LIQUIDPOLL_VERSION' ) || define( 'LIQUIDPOLL_VERSION', '3.3.79' );

if ( ! class_exists( 'LIQUIDPOLL_Main' ) ) {
	/**
	 * Class LIQUIDPOLL_Main
	 */
	class LIQUIDPOLL_Main {

		protected static $_instance = null;

		/**
		 * LIQUIDPOLL_Main constructor.
		 */
		function __construct() {
			$this->load_scripts();
			$this->define_classes_functions();

			add_action( 'widgets_init', array( $this, 'register_widgets' ) );

			load_plugin_textdomain( 'wp-poll', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * @return \LIQUIDPOLL_Main|null
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}


		/**
		 * Register Widgets
		 */
		function register_widgets() {
			register_widget( 'LIQUIDPOLL_Widgets' );
		}


		/**
		 * Loading classes and functions
		 */
		function define_classes_functions() {

			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-pb-settings.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-resizer.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-item-data.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-functions.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/functions.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-hooks.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-plugin-settings.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-poll.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-meta-boxes.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-poll-widgets.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/classes/class-poll-reports.php';

			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/addons/class-addons.php';

			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/template-hooks.php';
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/template-hook-functions.php';

			// Elementor
			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/class-elementor.php';
		}


		/**
		 * Return data that will pass on pluginObject
		 *
		 * @return array
		 */
		function localize_scripts_data() {

			return array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'copyText'           => esc_html__( 'Copied !', 'wp-poll' ),
				'voteText'           => esc_html__( 'votes', 'wp-poll' ),
				'tempProDownload'    => esc_url( 'https://liquidpoll.com/my-account/downloads/' ),
				'tempProDownloadTxt' => esc_html__( 'Download Version 2.0.54', 'wp-poll' ),
			);
		}


		/**
		 * Loading scripts to backend
		 */
		function admin_scripts() {

			$version = defined( 'WP_DEBUG' ) && WP_DEBUG ? current_time( 'U' ) : LIQUIDPOLL_VERSION;

			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'jquery-ui', LIQUIDPOLL_PLUGIN_URL . 'assets/jquery-ui.css' );
			wp_enqueue_style( 'tooltip', LIQUIDPOLL_PLUGIN_URL . 'assets/tool-tip.min.css' );
			wp_enqueue_style( 'roundslider-css', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/roundslider.min.css' );
			wp_enqueue_style( 'liquidpoll-admin', LIQUIDPOLL_PLUGIN_URL . 'assets/admin/css/style.css', array(), $version );

			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'apexcharts', plugins_url( 'assets/apexcharts.js', __FILE__ ) );
			wp_enqueue_script( 'liquidpoll-admin', plugins_url( 'assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ), $version );
			wp_localize_script( 'liquidpoll-admin', 'liquidpoll_object', $this->localize_scripts_data() );
		}


		/**
		 * Loading scripts to the frontend
		 */
		function front_scripts() {

			global $wp_query;

			$version        = defined( 'WP_DEBUG' ) && WP_DEBUG ? current_time( 'U' ) : LIQUIDPOLL_VERSION;
			$load_in_footer = $wp_query->get( 'poll_in_embed' ) ? false : $wp_query->get( 'poll_in_embed' );

			wp_enqueue_script( 'liquidpoll-front-cb', LIQUIDPOLL_PLUGIN_URL . 'assets/front/js/svgcheckbx.js', array( 'jquery' ), $version, $load_in_footer );
			wp_enqueue_script( 'rangeslider', plugins_url( 'assets/front/js/rangeslider.min.js', __FILE__ ), array( 'jquery' ), $version, $load_in_footer );
			wp_enqueue_script( 'roundslider', plugins_url( 'assets/front/js/roundslider.min.js', __FILE__ ), array( 'jquery' ), $version, $load_in_footer );
			wp_enqueue_script( 'datepicker', plugins_url( 'assets/front/js/flatpickr.min.js', __FILE__ ), array( 'jquery' ), $version, $load_in_footer );
			wp_enqueue_script( 'slick-slider', plugins_url( 'assets/front/js/slick.min.js', __FILE__ ), array( 'jquery' ), $version, $load_in_footer );
			wp_enqueue_script( 'masonry', plugins_url( 'assets/front/js/masonry.pkgd.min.js', __FILE__ ), array( 'jquery' ), $version, $load_in_footer );
			wp_enqueue_script( 'apexcharts_front', plugins_url( 'assets/apexcharts.js', __FILE__ ), array( 'jquery' ), $version, $load_in_footer );
			wp_enqueue_script( 'liquidpoll-front', plugins_url( 'assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ), $version, $load_in_footer );
			wp_localize_script( 'liquidpoll-front', 'liquidpoll_object', $this->localize_scripts_data() );

			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'tooltip', LIQUIDPOLL_PLUGIN_URL . 'assets/tool-tip.min.css' );
			wp_enqueue_style( 'rangeslider', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/rangeslider.css', array(), $version );
			wp_enqueue_style( 'roundslider', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/roundslider.min.css', array(), $version );
			wp_enqueue_style( 'datepicker', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/flatpickr.min.css', array(), $version );
			wp_enqueue_style( 'slick', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/slick.css', array(), $version );
			wp_enqueue_style( 'slick-theme', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/slick-theme.css', array(), $version );
			wp_enqueue_style( 'liquidpoll-front-cb', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/checkbox.css', array(), $version );
			wp_enqueue_style( 'liquidpoll-front', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/style.css', array(), $version );
			wp_enqueue_style( 'liquidpoll-front-nps', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/style-nps.css', array(), $version );
		}


		/**
		 * Loading scripts
		 */
		function load_scripts() {
			add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}
	}
}


// Update license server
add_filter( 'WPDK_Settings/Filters/integration_server_wp_poll', function () {
	return esc_url( 'https://www.liquidpoll.com' );
} );

// Update license secret key
add_filter( 'WPDK_Settings/Filters/license_secret_key_wp_poll', function () {
	return '6287d0ca3125a4.96767836';
} );

function wpdk_init_wp_poll() {

	if ( ! function_exists( 'get_plugins' ) ) {
		include_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	if ( ! class_exists( 'WPDK\Client' ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/wpdk/classes/class-client.php' );
	}

	global $liquidpoll_wpdk;

	$liquidpoll_wpdk = new WPDK\Client( esc_html( 'LiquidPoll' ), 'wp-poll', 126, __FILE__ );

	do_action( 'wpdk_init_wp_poll', $liquidpoll_wpdk );
}

/**
 * @global \WPDK\Client $liquidpoll_wpdk
 */
global $liquidpoll_wpdk;

wpdk_init_wp_poll();

add_action( 'plugins_loaded', array( 'LIQUIDPOLL_Main', 'instance' ), 80 );
