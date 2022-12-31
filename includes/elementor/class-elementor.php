<?php
/**
 * Elementor Support
 */


use Elementor\Plugin;

if ( ! class_exists( 'LIQUIDPOLL_Elementor' ) ) {
	/**
	 * LIQUIDPOLL_Elementor class
	 */
	class LIQUIDPOLL_Elementor {

		private $version;

		/**
		 * LIQUIDPOLL_Elementor Constructor
		 */
		function __construct() {

			$this->version = defined( 'WP_DEBUG' ) && WP_DEBUG ? current_time( 'U' ) : LIQUIDPOLL_VERSION;

			add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'register_styles' ] );
			add_action( 'elementor/frontend/after_register_scripts', [ $this, 'register_scripts' ] );

			add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		}


		/**
		 * Register all widgets
		 */
		function register_widgets() {

			require_once LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/class-widget-base.php';
			include_once LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/widget-nps/widget.php';
			include_once LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/widget-poll/widget.php';
			include_once LIQUIDPOLL_PLUGIN_DIR . 'includes/elementor/widget-reaction/widget.php';

			Plugin::instance()->widgets_manager->register( new LIQUIDPOLL_Widget_nps );
			Plugin::instance()->widgets_manager->register( new LIQUIDPOLL_Widget_poll );
			Plugin::instance()->widgets_manager->register( new LIQUIDPOLL_Widget_reaction );
		}


		/**
		 * Register elementor scripts
		 *
		 * @return void
		 */
		public function register_scripts() {
			wp_enqueue_script( 'rangeslider-el', LIQUIDPOLL_PLUGIN_URL . 'assets/front/js/rangeslider.min.js', array( 'jquery' ) );
			wp_register_script( 'widget-nps', LIQUIDPOLL_PLUGIN_URL . 'includes/elementor/widget-nps/js/scripts.js', array( 'jquery' ), $this->version );
			wp_register_script( 'widget-poll', LIQUIDPOLL_PLUGIN_URL . 'includes/elementor/widget-poll/js/scripts.js', array( 'jquery' ), $this->version );
		}


		/**
		 * Register elementor styles
		 *
		 * @return void
		 */
		public function register_styles() {
			wp_enqueue_style( 'rangeslider', LIQUIDPOLL_PLUGIN_URL . 'assets/front/css/rangeslider.css' );
			wp_register_style( 'widget-poll', LIQUIDPOLL_PLUGIN_URL . 'includes/elementor/widget-poll/css/style.css', array(), $this->version );
		}
	}
}

new LIQUIDPOLL_Elementor;