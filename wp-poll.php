<?php
/**
 * Plugin Name: WP Poll - Best Polling Solution in WordPress
 * Plugin URI: https://www.pluginbazar.com/plugin/wp-poll/
 * Description: It allows user to poll in your website with many awesome features.
 * Version: 3.3.11
 * Author: Pluginbazar
 * Text Domain: wp-poll
 * Domain Path: /languages/
 * Author URI: https://pluginbazar.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) || exit;

global $wpdb;

defined( 'WPP_TABLE_RESULTS' ) || define( 'WPP_TABLE_RESULTS', sprintf( '%spoll_results', $wpdb->prefix ) );
defined( 'WPP_PLUGIN_URL' ) || define( 'WPP_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
defined( 'WPP_PLUGIN_DIR' ) || define( 'WPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
defined( 'WPP_PLUGIN_FILE' ) || define( 'WPP_PLUGIN_FILE', plugin_basename( __FILE__ ) );
defined( 'WPP_PLUGIN_LINK' ) || define( 'WPP_PLUGIN_LINK', 'https://pluginbazar.com/plugin/wp-poll/' );
defined( 'WPP_DOCS_URL' ) || define( 'WPP_DOCS_URL', 'https://pluginbazar.com/d/wp-poll/' );
defined( 'WPP_REVIEW_URL' ) || define( 'WPP_REVIEW_URL', 'https://wordpress.org/support/plugin/wp-poll/reviews/#new-post' );
defined( 'PB_TICKET_URL' ) || define( 'PB_TICKET_URL', 'https://pluginbazar.com/my-account/tickets/?action=new' );

if ( ! class_exists( 'WP_Poll_main' ) ) {
	/**
	 * Class WP_Poll_main
	 */
	class WP_Poll_main {

		protected static $_instance = null;

		/**
		 * WP_Poll_main constructor.
		 */
		function __construct() {

			$this->load_scripts();
			$this->define_classes_functions();

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		}


		/**
		 * @return \WP_Poll_main|null
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
			register_widget( 'WPP_Widgets' );
		}


		/**
		 * Loading TextDomain
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'wp-poll', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Loading classes and functions
		 */
		function define_classes_functions() {

			require_once WPP_PLUGIN_DIR . 'includes/classes/class-pb-settings.php';
			require_once WPP_PLUGIN_DIR . 'includes/classes/class-item-data.php';
			require_once WPP_PLUGIN_DIR . 'includes/classes/class-functions.php';
			require_once WPP_PLUGIN_DIR . 'includes/functions.php';
			require_once WPP_PLUGIN_DIR . 'includes/classes/class-hooks.php';
			require_once WPP_PLUGIN_DIR . 'includes/classes/class-meta-boxes.php';
			require_once WPP_PLUGIN_DIR . 'includes/classes/class-shortcodes.php';
			require_once WPP_PLUGIN_DIR . 'includes/classes/class-poll.php';
			require_once WPP_PLUGIN_DIR . 'includes/classes/class-poll-widgets.php';


			require_once WPP_PLUGIN_DIR . 'includes/template-hooks.php';
			require_once WPP_PLUGIN_DIR . 'includes/template-hook-functions.php';
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
				'voteText'           => esc_html__( 'Vote(s)', 'wp-poll' ),
				'tempProDownload'    => esc_url( 'https://pluginbazar.com/my-account/downloads/' ),
				'tempProDownloadTxt' => esc_html__( 'Download Version 1.1.0', 'wp-poll' ),
			);
		}


		/**
		 * Loading scripts to backend
		 */
		function admin_scripts() {

			wp_enqueue_style( 'jquery-ui' );
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'jquery-ui', WPP_PLUGIN_URL . 'assets/jquery-ui.css' );
			wp_enqueue_style( 'tooltip', WPP_PLUGIN_URL . 'assets/tool-tip.min.css' );
			wp_enqueue_style( 'wpp-admin', WPP_PLUGIN_URL . 'assets/admin/css/style.css' );

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'apexcharts', plugins_url( 'assets/apexcharts.js', __FILE__ ) );
			wp_enqueue_script( 'wpp-admin', plugins_url( 'assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ) );
			wp_localize_script( 'wpp-admin', 'wpp_object', $this->localize_scripts_data() );
		}


		/**
		 * Loading scripts to the frontend
		 */
		function front_scripts() {

			global $wp_query, $wp_version;

			$load_in_footer = $wp_query->get( 'poll_in_embed' ) ? false : $wp_query->get( 'poll_in_embed' );

			wp_enqueue_script( 'wpp-front-cb', WPP_PLUGIN_URL . 'assets/front/js/svgcheckbx.js', array( 'jquery' ), $wp_version, $load_in_footer );
			wp_enqueue_script( 'wpp-front', plugins_url( 'assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ), $wp_version, $load_in_footer );
			wp_localize_script( 'wpp-front', 'wpp_object', $this->localize_scripts_data() );

			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'tooltip', WPP_PLUGIN_URL . 'assets/tool-tip.min.css' );
			wp_enqueue_style( 'wpp-front-cb', WPP_PLUGIN_URL . 'assets/front/css/checkbox.css', array(), $wp_version );
			wp_enqueue_style( 'wpp-front', WPP_PLUGIN_URL . 'assets/front/css/style.css', array(), $wp_version );
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

WP_Poll_main::instance();


function pb_sdk_init_wp_poll() {

	if ( ! class_exists( 'Pluginbazar\Client' ) ) {
		require_once( plugin_dir_path( __FILE__ ) . 'includes/sdk/class-client.php' );
	}

	if ( ! function_exists( 'get_plugins' ) ) {
		include_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	global $wppoll_sdk;

	$wppoll_sdk = new Pluginbazar\Client( esc_html( 'WP Poll Pro' ), 'wp-poll', 34, __FILE__ );
	$wppoll_sdk->license()->add_settings_page( array( 'parent_slug' => 'edit.php?post_type=poll' ) );;
	$wppoll_sdk->notifications();
	$wppoll_sdk->updater();
}

/**
 * @global \Pluginbazar\Client $wppoll_sdk
 */
global $wppoll_sdk;

pb_sdk_init_wp_poll();

