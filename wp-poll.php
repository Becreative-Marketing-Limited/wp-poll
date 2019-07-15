<?php
/*
    Plugin Name: WP Poll
    Plugin URI: https://www.pluginbazar.net/product/wp-poll/
    Description: It allows user to poll in your website with many awesome feature.
    Version: 3.1.0
    Author: Pluginbazar
	Text Domain: wp-poll
	Domain Path: /languages/
    Author URI: https://pluginbazar.net/
    License: GPLv2 or later
    License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access


/**
 * Class WPPollManager
 */
class WPPollManager {

	/**
	 * WPPollManager constructor.
	 */
	public function __construct() {

		$this->define_constants();
		$this->load_scripts();
		$this->define_classes_functions();

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	}


	/**
	 * Loading TextDomain
	 */
	public function load_textdomain() {

		load_plugin_textdomain( 'wp-poll', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * Loading classes and functions
	 */
	public function define_classes_functions() {

		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-pb-settings.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-post-types.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-functions.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-hooks.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-post-meta.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/classes/class-shortcodes.php' );

		require_once( WPP_PLUGIN_DIR . 'includes/functions.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/functions-settings.php' );

		require_once( WPP_PLUGIN_DIR . 'includes/template-hooks.php' );
		require_once( WPP_PLUGIN_DIR . 'includes/template-hook-functions.php' );
	}


	/**
	 * Loading scripts to backend
	 */
	public function admin_scripts() {

		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'jquery-ui', WPP_PLUGIN_URL . 'assets/jquery-ui.css' );
		wp_enqueue_style( 'icofont', WPP_PLUGIN_URL . 'assets/fonts/icofont.min.css' );
		wp_enqueue_style( 'wpp_admin_style', WPP_PLUGIN_URL . 'assets/admin/css/style.css' );

		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'wpp_admin_js', plugins_url( 'assets/admin/js/scripts.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'apexcharts', plugins_url( 'assets/apexcharts.js', __FILE__ ) );
	}


	/**
	 * Loading scripts to the frontend
	 */
	public function front_scripts() {

        wp_enqueue_script( 'wpp_checkbox_js', WPP_PLUGIN_URL . 'assets/front/js/svgcheckbx.js', array('jquery'), false, true );
        wp_enqueue_script( 'wpp_js', plugins_url( 'assets/front/js/scripts.js', __FILE__ ), array( 'jquery' ) );
        wp_localize_script( 'wpp_js', 'wpp_ajax', array( 'wpp_ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		wp_enqueue_style( 'icofont', WPP_PLUGIN_URL . 'assets/fonts/icofont.min.css' );
		wp_enqueue_style( 'wpp_checkbox', WPP_PLUGIN_URL . 'assets/front/css/checkbox.css' );
		wp_enqueue_style( 'wpp_style', WPP_PLUGIN_URL . 'assets/front/css/style.css' );
	}


	/**
	 * Loading scripts
	 */
	public function load_scripts() {

		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}


	/**
	 * Define Constants
	 */
	public function define_constants() {

		define( 'WPP_PLUGIN_URL', WP_PLUGIN_URL . '/' . plugin_basename( dirname( __FILE__ ) ) . '/' );
		define( 'WPP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'WPP_PLUGIN_FILE', plugin_basename( __FILE__ ) );
		define( 'WPP_FORUM_URL', 'https://pluginbazar.com/forums/forum/wp-poll' );
		define( 'WPP_CONTACT_URL', 'https://pluginbazar.com/contact/' );
		define( 'WPP_REVIEW_URL', 'https://wordpress.org/support/plugin/wp-poll/reviews/' );
	}
}

new WPPollManager();