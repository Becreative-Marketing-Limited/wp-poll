<?php
/**
 * Pluginbazar SDK Client
 */

namespace Pluginbazar;

/**
 * Class Updater
 *
 * @package Pluginbazar
 */
class Updater {

	protected $cache_key;
	protected $data;

	/**
	 * @var Client null
	 */
	private $client = null;


	/**
	 * Updater constructor.
	 *
	 * @param Client $client
	 */
	function __construct( Client $client ) {

		$this->client = $client;
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_plugin_update' ) );
	}


	/**
	 * Check for Update for this specific project
	 *
	 * @param $transient_data
	 *
	 * @return mixed
	 */
	public function check_plugin_update( $transient_data ) {
		global $pagenow;

		if ( ! is_object( $transient_data ) ) {
			$transient_data = new \stdClass;
		}

		if ( 'plugins.php' == $pagenow && is_multisite() ) {
			return $transient_data;
		}

		if ( ! empty( $transient_data->response ) && ! empty( $transient_data->response[ $this->client->basename() ] ) ) {
			return $transient_data;
		}

		$version_info = $this->get_version_info();

		if ( false !== $version_info && is_object( $version_info ) && isset( $version_info->new_version ) ) {

			if ( isset( $version_info->sections ) ) {
				unset( $version_info->sections );
			}

			if ( version_compare( $this->client->plugin_version, $version_info->new_version, '<' ) ) {
				$transient_data->response[ $this->client->basename() ] = $version_info;
			} else {
				$transient_data->no_update[ $this->client->basename() ] = $version_info;
			}

			$transient_data->last_checked                         = time();
			$transient_data->checked[ $this->client->basename() ] = $this->client->plugin_version;
		}


		return $transient_data;
	}


	/**
	 * Get version information
	 */
	private function get_version_info() {
		$version_info = $this->get_cached_version_info();

		if ( false === $version_info ) {
			$version_info = $this->get_project_latest_version();
			$this->set_cached_version_info( $version_info );
		}

		return $version_info;
	}


	/**
	 * Get latest version information from server
	 *
	 * @return array
	 */
	function get_project_latest_version() {

		$response = $this->client->send_request( 'plugin/' . $this->client->license()->get_license_data( 'license_key' ), array(), false, false, false );

		if ( isset( $response->icons ) ) {
			$response->icons = (array) $response->icons;
		}

		if ( isset( $response->banners ) ) {
			$response->banners = (array) $response->banners;
		}

		if ( isset( $response->sections ) ) {
			$response->sections = (array) $response->sections;
		}

		return $response;
	}


	/**
	 * Set version info to database
	 *
	 * @param $value
	 */
	private function set_cached_version_info( $value ) {
		if ( $value ) {
			set_transient( $this->cache_key, $value, 3 * HOUR_IN_SECONDS );
		}
	}


	/**
	 * Get version info from database
	 *
	 * @return false|mixed
	 */
	private function get_cached_version_info() {
		global $pagenow;

		if ( 'update-core.php' == $pagenow ) {
			return false;
		}

		$value = get_transient( $this->cache_key );

		if ( ! $value && ! isset( $value->name ) ) {
			return false;
		}

		if ( isset( $value->icons ) ) {
			$value->icons = (array) $value->icons;
		}

		if ( isset( $value->banners ) ) {
			$value->banners = (array) $value->banners;
		}

		if ( isset( $value->sections ) ) {
			$value->sections = (array) $value->sections;
		}

		return $value;
	}
}