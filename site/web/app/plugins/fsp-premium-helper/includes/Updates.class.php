<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'fspHandleUpdates' ) ) {
class fspHandleUpdates {

	public function __construct() {

		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 20, 3 );
		add_filter( 'site_transient_update_plugins', array( $this, 'push_update' ) );

		add_action( 'upgrader_process_complete', array( $this, 'after_update' ), 10, 2 );
	}

	/*
	 * $res empty at this step
	 * $action 'plugin_information'
	 * $args stdClass Object ( [slug] => woocommerce [is_ssl] => [fields] => Array ( [banners] => 1 [reviews] => 1 [downloaded] => [active_installs] => 1 ) [per_page] => 24 [locale] => en_US )
	 */
	public function plugin_info( $res, $action, $args ){
	 
		// do nothing if this is not about getting plugin information
		if ( 'plugin_information' !== $action ) {
			return false;
		}

		$plugin_slug = 'fsp-premium-helper';
	 
		// do nothing if it is not our plugin
		if ( $plugin_slug !== $args->slug ) {
			return false;
		}
	 
		// trying to get from cache first
		if ( false == $remote = get_transient( 'fspph_update' ) ) {

			set_transient( 'fspph_update', $remote, 3600 ); // 1 hour whether successful or not
	 
			// info.json is the file with the actual plugin information on your server
			$remote = wp_remote_get( 'https://www.fivestarplugins.com/downloads/fspph-info.json', array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				) )
			);
	 
			if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {
				set_transient( 'fspph_update', $remote, 43200 ); // 12 hours cache
			}
	 
		}
	 
		if ( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {
	 
			$remote = json_decode( $remote['body'] );
			$res = new stdClass();
	 
			$res->name = $remote->name;
			$res->slug = $plugin_slug;
			$res->version = $remote->version;
			$res->tested = $remote->tested;
			$res->requires = $remote->requires;
			$res->author = '<a href="https://fivestarplugins.com">Five Star Plugins</a>';
			$res->author_profile = 'https://profiles.wordpress.org/fivestarplugins';
			$res->download_link = $remote->download_url;
			$res->trunk = $remote->download_url;
			$res->requires_php = '5.3';
			$res->last_updated = $remote->last_updated;
			$res->sections = array(
				'description' => $remote->sections->description,
				'installation' => $remote->sections->installation,
				'changelog' => $remote->sections->changelog
				// you can add your custom sections (tabs) here
			);
	 
			// in case you want the screenshots tab, use the following HTML format for its content:
			// <ol><li><a href="IMG_URL" target="_blank"><img src="IMG_URL" alt="CAPTION" /></a><p>CAPTION</p></li></ol>
			if ( ! empty( $remote->sections->screenshots ) ) {
				$res->sections['screenshots'] = $remote->sections->screenshots;
			}
	 
			/*$res->banners = array(
				'low' => 'https://YOUR_WEBSITE/banner-772x250.jpg',
				'high' => 'https://YOUR_WEBSITE/banner-1544x500.jpg'
			);*/

			return $res;
		}
	 
		return false;
	}

	public function push_update( $transient ){
 
		if ( empty($transient->checked ) ) {
			return $transient;
		}
	 
		// trying to get from cache first
		if ( false == $remote = get_transient( 'fspph_update' ) ) {

			set_transient( 'fspph_update', $remote, 3600 ); // 1 hour whether successful or not
	 
			// info.json is the file with the actual plugin information on your server
			$remote = wp_remote_get( 'https://www.fivestarplugins.com/downloads/fspph-info.json', array(
				'timeout' => 10,
				'headers' => array(
					'Accept' => 'application/json'
				) )
			);
	 
			if ( !is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && !empty( $remote['body'] ) ) {
				set_transient( 'fspph_update', $remote, 43200 ); // 12 hours cache
			}
	 
		}
	 
		if ( $remote && ! is_wp_error( $remote ) ) {
	 
			$remote = json_decode( $remote['body'] );
	 
			// your installed plugin version should be on the line below! You can obtain it dynamically of course 
			if ( $remote && version_compare( FSPPH_VERSION, $remote->version, '<' ) && version_compare( $remote->requires, get_bloginfo('version'), '<' ) ) {
				$res = new stdClass();
				$res->slug = 'fsp-premium-helper';
				$res->plugin = 'fsp-premium-helper/fsp-premium-helper.php'; 
				$res->new_version = $remote->version;
				$res->tested = $remote->tested;
				$res->package = $remote->download_url;
				$transient->response[$res->plugin] = $res;
			}
		}

		return $transient;
	}

	public function after_update( $upgrader_object, $options ) {
		
		if ( $options['action'] == 'update' && $options['type'] === 'plugin' )  {
			// just clean the cache when new plugin version is installed
			delete_transient( 'fspph_update' );
		}
	}

	/**
	 * Get credit SMS credit information
	 * @since 0.18
	 */
	public function retrieve_sms_credit_information( $args ) {
		
		$url = add_query_arg(
			array(
				'license_key' 	=> urlencode( $args['license_key'] ),
				'admin_email' 	=> urlencode( $args['purchase_email'] ),
				'plugin'		=> urlencode( $args['plugin'] )
			),
			'http://www.fivestarplugins.com/sms-handling/sms-license-check.php'
		);

		$opts = array('http'=>array('method'=>"GET"));
		$context = stream_context_create($opts);
		
		return json_decode( file_get_contents( $url, false, $context ) );
	}
}
}
?>