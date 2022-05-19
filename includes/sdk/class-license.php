<?php
/**
 * Pluginbazar SDK Client
 */

namespace Pluginbazar;

/**
 * Class License
 *
 * @package Pluginbazar
 */
class License {

	protected $cache_key;
	protected $data;
	protected $option_key = null;
	protected $menu_args = null;
	protected $license_server = 'https://pluginbazar.com';
	protected $secret_key = '5beed4ad27fd52.16817105';

	/**
	 * @var Client null
	 */
	private $client = null;

	/**
	 * License constructor.
	 */
	function __construct( Client $client ) {

		$this->client = $client;

		$this->option_key = sprintf( 'pb_%s_license_data', md5( $this->client->text_domain ) );
		$this->cache_key  = sprintf( 'pb_%s_version_info', md5( $this->client->text_domain ) );
		$this->data       = get_option( $this->option_key, array() );

		add_action( 'admin_notices', array( $this, 'license_activation_notices' ) );
	}


	/**
	 * Send message if License is not valid
	 */
	function license_activation_notices() {

		if ( ! $this->is_activate_pro() || $this->is_valid() || ( isset( $_GET['page'] ) && sanitize_text_field( $_GET['page'] == $this->menu_args['menu_slug'] ) ) ) {
			return;
		}

		$license_message = sprintf( $this->client->__trans( '<p>You must activate <strong>%s</strong> to unlock the premium features, enable single-click download, and etc. Dont have your key? <a href="%s" target="_blank">Your license keys</a></p><p><a class="button-primary" href="%s">Activate License</a></p>' ),
			$this->client->plugin_name, sprintf( '%s/my-account/license-keys/', $this->license_server ), menu_page_url( $this->menu_args['menu_slug'], false )
		);

		$this->client->print_notice( $license_message, 'warning' );
	}


	/**
	 * Add menu page for license page
	 *
	 * @param array $args
	 */
	public function add_settings_page( $args = array() ) {

		$defaults = array(
			'type'        => 'submenu', // Can be: menu, options, submenu
			'page_title'  => sprintf( $this->client->__trans( 'Manage License - %s' ), $this->client->plugin_name ),
			'menu_title'  => $this->client->__trans( 'Manage License' ),
			'capability'  => 'manage_options',
			'menu_slug'   => $this->client->text_domain . '-manage-license',
			'icon_url'    => '',
			'position'    => null,
			'parent_slug' => '',
		);

		$this->menu_args = wp_parse_args( $args, $defaults );

		// if pro version activated then add the license page menu
		if ( $this->is_activate_pro() ) {
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 999 );
		}
	}


	/**
	 * Return is pro version activated
	 *
	 * @return bool
	 */
	public function is_activate_pro() {

		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		return is_plugin_active( sprintf( '%1$s-pro/%1$s-pro.php', $this->client->text_domain ) );
	}


	/**
	 * Add admin Menu
	 */
	public function admin_menu() {
		switch ( $type = $this->client->get_args_option( 'type', $this->menu_args ) ) {
			case 'menu':
				call_user_func( 'add_' . $type . '_page',
					$this->menu_args['page_title'],
					$this->menu_args['menu_title'],
					$this->menu_args['capability'],
					$this->menu_args['menu_slug'],
					array( $this, 'render_license_page' ),
					$this->menu_args['icon_url'],
					$this->menu_args['position']
				);
				break;

			case 'submenu':
				call_user_func( 'add_' . $type . '_page',
					$this->menu_args['parent_slug'],
					$this->menu_args['page_title'],
					$this->menu_args['menu_title'],
					$this->menu_args['capability'],
					$this->menu_args['menu_slug'],
					array( $this, 'render_license_page' ),
					$this->menu_args['position']
				);
				break;

			case 'options':
				call_user_func( 'add_' . $type . '_page',
					$this->menu_args['page_title'],
					$this->menu_args['menu_title'],
					$this->menu_args['capability'],
					$this->menu_args['menu_slug'],
					array( $this, 'render_license_page' ),
					$this->menu_args['position']
				);
				break;
		}
	}


	/**
	 * Process license form submission
	 */
	function process_form_submission() {

		if ( ! wp_verify_nonce( isset( $_POST['_wpnonce'] ) ? $_POST['_wpnonce'] : '', $this->nonce() ) ) {
			return;
		}

		$license_key    = isset( $_POST['license_key'] ) ? trim( sanitize_text_field( $_POST['license_key'] ) ) : '';
		$license_key    = str_replace( ' ', '', $license_key );
		$license_action = isset( $_POST['license_action'] ) ? sanitize_text_field( $_POST['license_action'] ) : '';

		if ( empty( $license_key ) || empty( $license_action ) ) {
			$this->client->print_notice( sprintf( '<p>%s</p>', $this->client->__trans( 'Invalid license key' ) ), 'error' );

			return;
		}

		// Sending request to server
		$api_params = array(
			'slm_action'        => $license_action,
			'registered_domain' => $this->client->get_website_url(),
		);

		if ( $license_action == 'slm_activate' ) {

			// add license key to the api param
			$api_params['license_key'] = $license_key;

			// Settings license to the data
			$this->data = array( 'license_key' => $license_key );
		}

		$api_response = $this->license_api_request( $api_params );
		$message      = $this->client->get_args_option( 'message', $api_response );

		if ( $this->is_error( $api_response ) ) {

			$this->client->print_notice( sprintf( '<p>%s</p>', $message ), 'error' );

			if ( $this->client->get_args_option( 'error_code', $api_response ) == 80 ) {
				$this->flush_license_data();
			}

			return;
		}

		$this->client->print_notice( sprintf( '<p>%s</p>', $api_response['message'] ) );

		$this->data = $api_response;
		update_option( $this->option_key, $api_response );
	}


	/**
	 * Send request to license server
	 *
	 * @param array $api_params
	 *
	 * @return array|mixed
	 */
	private function license_api_request( $api_params = array() ) {

		$defaults = array(
			'slm_action'     => 'slm_check',
			'secret_key'     => $this->secret_key,
			'license_key'    => $this->get_license_data( 'license_key' ),
			'item_reference' => $this->client->plugin_reference,
		);

		$api_params = wp_parse_args( $api_params, $defaults );
		$api_query  = esc_url_raw( add_query_arg( $api_params, $this->license_server ) );
		$response   = wp_remote_post( $api_query, array( 'timeout' => 20, 'sslverify' => false ) );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return json_decode( wp_remote_retrieve_body( $response ), true );
	}


	/**
	 * Return bool if response is error or not
	 *
	 * @param array $response
	 *
	 * @return bool
	 */
	private function is_error( $response = array() ) {
		if ( is_array( $response ) && isset( $response['result'] ) && $response['result'] !== 'success' ) {
			return true;
		}

		return false;
	}


	/**
	 * Render License page
	 */
	public function render_license_page() {

		if ( isset( $_POST['submit'] ) ) {
			$this->process_form_submission();
		}

		$this->render_licenses_style();

		$license_action = $this->is_valid() ? 'slm_deactivate' : 'slm_activate';

		?>
        <div class="wrap pb-license-settings-wrapper">
            <h1>
				<?php printf( $this->client->__trans( 'License settings for <strong>%s</strong>' ), $this->client->plugin_name ); ?>
				<?php printf( $this->client->__trans( '<sub style="font-size: 12px; vertical-align: middle;">%s</sub>' ), $this->client->plugin_version ); ?>
            </h1>

            <div class="pb-license-settings pb-license-section action-<?php echo esc_attr( $license_action ); ?>">

				<?php $this->show_license_page_card_header(); ?>

                <div class="pb-license-details">
                    <p>
						<?php printf( $this->client->__trans( 'Activate or Deactivate <strong>%s</strong> by your license key to get support and automatic update from your WordPress dashboard.' ), $this->client->plugin_name ); ?>
                    </p>
                    <form method="post" action="<?php echo esc_url_raw( $this->form_url() ); ?>" novalidate="novalidate" spellcheck="false">
                        <input type="hidden" name="license_action" value="<?php echo esc_attr( $license_action ); ?>">
						<?php wp_nonce_field( $this->nonce() ); ?>
                        <div class="license-input-fields">
                            <div class="license-input-key">
                                <svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                                        <path d="m463.75 48.251c-64.336-64.336-169.01-64.335-233.35 1e-3 -43.945 43.945-59.209 108.71-40.181 167.46l-185.82 185.82c-2.813 2.813-4.395 6.621-4.395 10.606v84.858c0 8.291 6.709 15 15 15h84.858c3.984 0 7.793-1.582 10.605-4.395l21.211-21.226c3.237-3.237 4.819-7.778 4.292-12.334l-2.637-22.793 31.582-2.974c7.178-0.674 12.847-6.343 13.521-13.521l2.974-31.582 22.793 2.651c4.233 0.571 8.496-0.85 11.704-3.691 3.193-2.856 5.024-6.929 5.024-11.206v-27.929h27.422c3.984 0 7.793-1.582 10.605-4.395l38.467-37.958c58.74 19.043 122.38 4.929 166.33-39.046 64.336-64.335 64.336-169.01 0-233.35zm-42.435 106.07c-17.549 17.549-46.084 17.549-63.633 0s-17.549-46.084 0-63.633 46.084-17.549 63.633 0 17.548 46.084 0 63.633z"/>
                                    </svg>
                                <input type="text" autocomplete="off" value="<?php echo esc_attr( $this->get_license_key_for_input_field( $license_action ) ); ?>" placeholder="<?php echo esc_attr( $this->client->__trans( 'Enter your license key to activate' ) ); ?>" name="license_key"<?php echo $this->is_valid() ? 'readonly="readonly"' : ''; ?>/>
                            </div>
                            <button type="submit" name="submit"><?php echo $this->is_valid() ? $this->client->__trans( 'Deactivate License' ) : $this->client->__trans( 'Activate License' ); ?></button>
                        </div>
                    </form>
                    <p>
						<?php printf( $this->client->__trans( 'Find your license key from <a target="_blank" href="%s/my-account/license-keys/"><strong>Pluginbazar.com > My Account > License Keys</strong></a>' ), $this->license_server ); ?>
                    </p>
                    <p>
						<?php printf( $this->client->__trans( 'Download latest version manually from <a target="_blank" href="%s/my-account/downloads/"><strong>Pluginbazar.com > My Account > Downloads</strong></a>' ), $this->license_server ); ?>
                    </p>
                </div>
            </div>
        </div>
		<?php
	}


	/**
	 * Return license key for input field
	 *
	 * @param $license_action
	 *
	 * @return string
	 */
	private function get_license_key_for_input_field( $license_action ) {

		$license_key = $this->get_license_data( 'license_key' );
		$key_length  = strlen( $license_key );

		if ( $license_action == 'slm_activate' ) {
			return '';
		}

		return str_pad(
			substr( $license_key, 0, $key_length / 2 ), $key_length, '*'
		);
	}


	/**
	 * Return form URL
	 *
	 * @return string
	 */
	private function form_url() {
		return add_query_arg( $_GET, admin_url( basename( $_SERVER['SCRIPT_NAME'] ) ) );
	}


	/**
	 * Return license nonce action
	 *
	 * @return string
	 */
	private function nonce() {
		return sprintf( 'pb_license_%s', str_replace( '-', '_', $this->client->text_domain ) );
	}


	/**
	 * Check if license key is activated in this website or not
	 *
	 * @return bool
	 */
	public function is_valid() {
		return is_array( $registered_domains = $this->get_registered_domains() ) && in_array( $this->client->get_website_url(), $registered_domains );
	}


	/**
	 * Flush license data
	 */
	private function flush_license_data() {
		delete_option( $this->option_key );
	}


	/**
	 * Return array of registered domains for this license key
	 *
	 * @return array
	 */
	private function get_registered_domains() {

		$registered_domains = $this->get_license_data( 'registered_domains' );

		if ( ! is_array( $registered_domains ) ) {
			return array();
		}

		$registered_domains = array_map( function ( $domain ) {
			if ( isset( $domain['registered_domain'] ) ) {
				return $domain['registered_domain'];
			}

			return '';
		}, $registered_domains );

		return array_filter( $registered_domains );
	}


	/**
	 * Return license data
	 *
	 * @param $retrieve_data
	 *
	 * @return false|mixed|void
	 */
	public function get_license_data( $retrieve_data = '' ) {

		$license_data = $this->data;

		if ( empty( $retrieve_data ) ) {
			return $license_data;
		}

		return isset( $license_data[ $retrieve_data ] ) ? $license_data[ $retrieve_data ] : '';
	}


	/**
	 * Render css for license form
	 */
	private function render_licenses_style() {
		?>
        <style type="text/css">

            .pb-license-settings-wrapper h1 {
                margin-bottom: 30px;
            }

            .pb-license-section {
                width: 100%;
                max-width: 1100px;
                min-height: 1px;
                box-sizing: border-box;
            }

            .pb-license-settings {
                background-color: #fff;
                box-shadow: 0 3px 10px rgba(16, 16, 16, 0.05);
            }

            .pb-license-settings * {
                box-sizing: border-box;
            }

            .pb-license-title {
                background-color: #F8FAFB;
                border-bottom: 2px solid #EAEAEA;
                display: flex;
                align-items: center;
                padding: 10px 20px;
            }

            .pb-license-title svg {
                width: 30px;
                height: 30px;
                fill: #00bcd4;
            }

            .pb-license-title span {
                font-size: 17px;
                color: #444444;
                margin-left: 10px;
            }

            .pb-license-details {
                padding: 20px;
            }

            .pb-license-details p {
                font-size: 15px;
                margin: 0 0 20px 0;
            }

            .license-input-key {
                position: relative;
                flex: 0 0 72%;
                max-width: 72%;
            }

            .license-input-key input {
                background-color: #F9F9F9;
                padding: 10px 15px 10px 48px;
                border: 1px solid #E8E5E5;
                border-radius: 3px;
                height: 45px;
                font-size: 16px;
                color: #71777D;
                width: 100%;
                box-shadow: 0 0 0 transparent;
            }

            .license-input-key input:focus {
                outline: 0 none;
                border: 1px solid #E8E5E5;
                box-shadow: 0 0 0 transparent;
            }

            .license-input-key svg {
                width: 22px;
                height: 22px;
                fill: #00bcd4;
                position: absolute;
                left: 14px;
                top: 13px;
            }

            .action-slm_deactivate .pb-license-title svg,
            .action-slm_deactivate .license-input-key svg {
                fill: #E40055;
            }

            .license-input-fields {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
                max-width: 850px;
                width: 100%;
            }

            .license-input-fields button {
                color: #fff;
                font-size: 17px;
                padding: 8px;
                height: 46px;
                background-color: #00bcd4;
                border-radius: 3px;
                cursor: pointer;
                flex: 0 0 25%;
                max-width: 25%;
                border: 1px solid #00bcd4;
            }

            .action-slm_deactivate .license-input-fields button {
                background-color: #E40055;
                border-color: #E40055;
            }

            .license-input-fields button:focus {
                outline: 0 none;
            }

            .active-license-info {
                display: flex;
            }

            .single-license-info {
                min-width: 220px;
                flex: 0 0 30%;
            }

            .single-license-info h3 {
                font-size: 18px;
                margin: 0 0 12px 0;
            }

            .single-license-info p {
                margin: 0;
                color: #00C000;
            }

            .single-license-info p.occupied {
                color: #E40055;
            }
        </style>
		<?php
	}


	/**
	 * Card header
	 */
	private function show_license_page_card_header() {
		?>
        <div class="pb-license-title">
            <svg enable-background="new 0 0 299.995 299.995" version="1.1" viewBox="0 0 300 300" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                    <path d="m150 161.48c-8.613 0-15.598 6.982-15.598 15.598 0 5.776 3.149 10.807 7.817 13.505v17.341h15.562v-17.341c4.668-2.697 7.817-7.729 7.817-13.505 0-8.616-6.984-15.598-15.598-15.598z"/>
                <path d="m150 85.849c-13.111 0-23.775 10.665-23.775 23.775v25.319h47.548v-25.319c-1e-3 -13.108-10.665-23.775-23.773-23.775z"/>
                <path d="m150 1e-3c-82.839 0-150 67.158-150 150 0 82.837 67.156 150 150 150s150-67.161 150-150c0-82.839-67.161-150-150-150zm46.09 227.12h-92.173c-9.734 0-17.626-7.892-17.626-17.629v-56.919c0-8.491 6.007-15.582 14.003-17.25v-25.697c0-27.409 22.3-49.711 49.711-49.711 27.409 0 49.709 22.3 49.709 49.711v25.697c7.993 1.673 14 8.759 14 17.25v56.919h2e-3c0 9.736-7.892 17.629-17.626 17.629z"/>
                </svg>
			<?php printf( '<span>%s</span>', $this->client->__trans( 'Manage License' ) ); ?>
        </div>
		<?php
	}
}