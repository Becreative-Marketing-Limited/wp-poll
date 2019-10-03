<?php
/**
 * Plugin Name: WP Poll
 *
 * Plugin URI: https://www.pluginbazar.com/plugin/wp-poll/
 * Description: It allows user to poll in your website with many awesome features.
 * Version: 3.1.11
 * Author: Pluginbazar
 * Text Domain: wp-poll
 * Domain Path: /languages/
 * Author URI: https://pluginbazar.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

global $wpdb;

define( 'WPP_TABLE_RESULTS', sprintf( '%spoll_results', $wpdb->prefix ) );
define( 'WPP_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
define( 'WPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPP_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'WPP_FORUM_URL', 'https://pluginbazar.com/forums/forum/wp-poll' );
define( 'WPP_CONTACT_URL', 'https://pluginbazar.com/contact/' );
define( 'WPP_REVIEW_URL', 'https://wordpress.org/support/plugin/wp-poll/reviews/#new-post' );
define( 'WPP_VERSION', '3.1.9' );


/**
 * Class WPPollManager
 */
class WPPollManager {

	/**
	 * WPPollManager constructor.
	 */
	function __construct() {

		$this->load_scripts();
		$this->define_classes_functions();

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
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

//		$settings_path = str_replace( array( 'Pluginbazar/free/', 'Pluginbazar\free/' ), '', ABSPATH );
//		include $settings_path . "PB-Settings/class-pb-settings.php";

		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-pb-settings.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-item-data.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-poll-post-types.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-functions.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-hooks.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-poll-meta.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-shortcodes.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-poll.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-poll-widgets.php' );

		require_once( WPP_PLUGIN_DIR . 'includes/functions.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/functions-settings.php' );

		require_once( WPP_PLUGIN_DIR . 'includes/template-hooks.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/template-hook-functions.php' );
	}


	/**
	 * Retun data that will pass on pluginObject
	 *
	 * @return array
	 */
	function localize_scripts_data() {

		$plugin_obj = array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'copyText' => esc_html__( 'Copied !', 'wp-poll' ),
		);

		return $plugin_obj;
	}


	/**
	 * Loading scripts to backend
	 */
	function admin_scripts() {

		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'jquery-ui', WPP_PLUGIN_URL . 'assets/jquery-ui.css' );
		wp_enqueue_style( 'tooltip', WPP_PLUGIN_URL . 'assets/tool-tip.min.css' );
		wp_enqueue_style( 'icofont', WPP_PLUGIN_URL . 'assets/fonts/icofont.min.css' );
		wp_enqueue_style( 'wpp_admin_style', WPP_PLUGIN_URL . 'assets/admin/css/style.css' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'apexcharts', plugins_url( 'assets/apexcharts.js', __FILE__ ) );
		wp_enqueue_script( 'wpp_admin_js', plugins_url( 'assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'wpp_admin_js', 'wpp_object', $this->localize_scripts_data() );
	}


	/**
	 * Loading scripts to the frontend
	 */
	function front_scripts() {

		wp_enqueue_script( 'wpp_checkbox_js', WPP_PLUGIN_URL . 'assets/front/js/svgcheckbx.js', array( 'jquery' ), false, true );
		wp_enqueue_script( 'wpp_js', plugins_url( 'assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ) );
		wp_localize_script( 'wpp_js', 'wpp_object', $this->localize_scripts_data() );

		wp_enqueue_style( 'tooltip', WPP_PLUGIN_URL . 'assets/tool-tip.min.css' );
		wp_enqueue_style( 'icofont', WPP_PLUGIN_URL . 'assets/fonts/icofont.min.css' );
		wp_enqueue_style( 'wpp_checkbox', WPP_PLUGIN_URL . 'assets/front/css/checkbox.css' );
		wp_enqueue_style( 'wpp_style', WPP_PLUGIN_URL . 'assets/front/css/style.css' );
	}


	/**
	 * Loading scripts
	 */
	function load_scripts() {

		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}
}

new WPPollManager();