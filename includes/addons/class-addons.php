<?php
/**
 * Class Addons
 */


use Pluginbazar\Utils;

if ( ! class_exists( 'LIQUIDPOLL_Addons' ) ) {
	/**
	 * Class LIQUIDPOLL_Addons
	 */
	class LIQUIDPOLL_Addons {

		/**
		 * LIQUIDPOLL_Addons constructor.
		 */
		function __construct() {

			add_action( 'admin_menu', array( $this, 'add_addons_menu' ), 99 );

			add_action( 'admin_enqueue_scripts', array( $this, 'addon_scripts' ) );
		}


		/**
		 * Add styles and scripts for the addons page.
		 */
		function addon_scripts() {
			wp_enqueue_style( 'liquidpoll-addon', LIQUIDPOLL_PLUGIN_URL . 'includes/addons/css/addons-style.css', array(), LIQUIDPOLL_VERSION );
		}


		/**
		 * Return array of addons
		 *
		 * @return mixed|void
		 */
		function get_addons() {

			$addons = array(
				array(
					'id'      => 'liquidpoll-fluent-crm',
					'title'   => esc_html__( 'FluentCRM', 'wp-poll' ),
					'details' => esc_html__( 'Integration with Fluent CRM to Liquidpoll plugin.', 'wp-poll' ),
					'thumb'   => '',
					'status'  => 'available',
					'is_pro'  => false,
					'link'    => '',
					'class'   => '',
				),
				array(
					'id'      => '',
					'title'   => esc_html__( 'GroundHogg - CRM & Email Automation', 'wp-poll' ),
					'details' => esc_html__( 'Integration of Liquidpoll for GroundHogg plugin.', 'wp-poll' ),
					'thumb'   => '',
					'status'  => 'comingsoon',
					'is_pro'  => false,
					'link'    => '',
					'class'   => '',
				),
				array(
					'id'      => '',
					'title'   => esc_html__( 'Autonami - Automation & CRM for WooFunnels', 'wp-poll' ),
					'details' => esc_html__( 'Integration of Liquidpoll and Autonami plugin.', 'wp-poll' ),
					'thumb'   => '',
					'status'  => 'comingsoon',
					'is_pro'  => false,
					'link'    => '',
					'class'   => '',
				),
				array(
					'id'      => '',
					'title'   => esc_html__( 'ConvertKit - Email marketing tool', 'wp-poll' ),
					'details' => esc_html__( 'Integration of Liquidpoll for ConvertKit.', 'wp-poll' ),
					'thumb'   => '',
					'status'  => 'comingsoon',
					'is_pro'  => false,
					'link'    => '',
					'class'   => '',
				),
				array(
					'id'      => '',
					'title'   => esc_html__( 'ActiveCampaign - Emai & CRM', 'wp-poll' ),
					'details' => esc_html__( 'Integration of Liquidpoll for ActiveCampaign.', 'wp-poll' ),
					'thumb'   => '',
					'status'  => 'comingsoon',
					'is_pro'  => false,
					'link'    => '',
					'class'   => '',
				),
				array(
					'id'      => '',
					'title'   => esc_html__( 'MailerLite - Email Marketing', 'wp-poll' ),
					'details' => esc_html__( 'Integration of Liquidpoll for MailerLite.', 'wp-poll' ),
					'thumb'   => '',
					'status'  => 'comingsoon',
					'is_pro'  => false,
					'link'    => '',
					'class'   => '',
				),
			);

			return apply_filters( 'LiquidPoll/Filters/get_addons', $addons );
		}


		/**
		 * Render addons page
		 */
		function render_addons() {

			ob_start();


			printf( '<h2>%s</h2>', esc_html__( 'LiquidPoll - Recommended Plugins and Addons', 'wp-poll' ) );

			$addons = array_map( function ( $addon ) {
				ob_start();

				$addon_id     = Utils::get_args_option( 'id', $addon );
				$addon_title  = Utils::get_args_option( 'title', $addon );
				$addon_link   = Utils::get_args_option( 'link', $addon );
				$addon_status = Utils::get_args_option( 'status', $addon );
				$classes      = Utils::get_args_option( 'class', $addon );
				$classes      = explode( ' ', $classes );
				$classes[]    = $addon_status;

				if ( Utils::get_args_option( 'is_pro', $addon, false ) ) {
					$classes[] = 'addon-pro';
				}

				printf( '<h3>%s</h3>', $addon_title );
				printf( '<p>%s</p>', Utils::get_args_option( 'details', $addon ) );

				if ( is_plugin_active( "{$addon_id}/{$addon_id}.php" ) ) {
					printf( '<a class="link" href="%s">%s</a>', $addon_link, esc_html__( 'Learn More', 'wp-poll' ) );
				} else if ( 'comingsoon' == $addon_status ) {
					printf( '<a class="link" disabled>%s</a>', esc_html__( 'Coming soon', 'wp-poll' ) );
				} else {
					printf( '<a class="link" href="%s">%s</a>', $addon_link, sprintf( esc_html__( 'Try %s', 'wp-poll' ), $addon_title ) );
				}

				return sprintf( '<div class="addon %s">%s</div>', implode( ' ', $classes ), ob_get_clean() );
			}, $this->get_addons() );

			printf( '<div class="all-addons">%s</div>', implode( ' ', $addons ) );

			printf( '<div class="wrap liquidpoll-addons">%s</div>', ob_get_clean() );
		}


		/**
		 * Add addons menu
		 */
		function add_addons_menu() {
			add_submenu_page( 'edit.php?post_type=poll', esc_html__( 'Addons', 'wp-poll' ), esc_html__( 'Addons', 'wp-poll' ), 'manage_options', 'addons', array( $this, 'render_addons' ) );
		}
	}
}

liquidpoll()->addons = new LIQUIDPOLL_Addons();