<?php
/**
 * Plugin Name: FSP Premium Helper
 * Plugin URI: https://www.fivestarplugins.com/upgrading-to-premium/
 * Description: Allows a website to access the premium trial and unlocks premium features of Five Star Design plugins with an upgrade code
 * Version: 0.0.32
 * Author: Five Star Plugins
 * Author URI: https://profiles.wordpress.org/fivestarplugins/
 * Text Domain: fsp-premium-helper
 */
if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'fspPremiumHelper' ) ) {
class fspPremiumHelper {

	/**
	 * Initialize the plugin and register hooks
	 */
	public function __construct() {

		// Common strings
		define( 'FSPPH_VERSION', '0.0.32' );
		define( 'FSPPH_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'FSPPH_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'FSPPH_PLUGIN_FNAME', plugin_basename( __FILE__ ) );

		register_deactivation_hook( FSPPH_PLUGIN_FNAME, array( $this, 'register_deactivation') );

		// Initialize the plugin
		add_action( 'init', array( $this, 'load_textdomain' ) );

		// add_action( 'plugins_loaded', array( $this, 'boot' ), 9 );
	}

	public function boot() {

		// Load form adding class
		require_once( FSPPH_PLUGIN_DIR . '/includes/DashboardForms.class.php' );
		new fspDashboardForms();

		// Load upgrade class
		require_once( FSPPH_PLUGIN_DIR . '/includes/Upgrade.class.php' );
		new fspHandleUpgrades();

		// Load version reversion class
		require_once( FSPPH_PLUGIN_DIR . '/includes/VersionReversion.class.php' );
		new fspHandleVersionReversion();

		// Load API handling class
		require_once( FSPPH_PLUGIN_DIR . '/includes/ApiHandler.class.php' );
		$this->apis = new fspAPIHandler();

		// Load update class
		require_once( FSPPH_PLUGIN_DIR . '/includes/Updates.class.php' );
		$this->updates = new fspHandleUpdates();

		// Load helper functions
		require_once( FSPPH_PLUGIN_DIR . '/includes/template-functions.php' );

		// Create cron jobs
		require_once( FSPPH_PLUGIN_DIR . '/includes/Cron.class.php' );
		$this->cron = new fspphCron();
		register_activation_hook( __FILE__, array( $this, 'cron_schedule_events' ) );
		register_deactivation_hook( __FILE__, array( $this, 'cron_unschedule_events' ) );

		// Extend BPFWP with premium features if validated
		add_action( 'bpfwp_initialized', array( $this, 'extend_bpfwp_plugin' ) );

		// Extend FDM with premium features if validated
		add_action( 'fdm_initialized', array( $this, 'extend_fdm_plugin' ) );

		// Extend GRFWP with premium features if validated
		add_action( 'grfwp_initialized', array( $this, 'extend_grfwp_plugin' ) );

		// Extend RTB with premium features if validated
		add_action( 'rtb_initialized', array( $this, 'extend_rtb_plugin' ) );

		register_activation_hook( __FILE__, array( $this, 'set_convert_rtb_notifications_transient' ) );
		add_action( 'upgrader_process_complete', array( $this, 'maybe_set_convert_rtb_notifications_transient' ), 10, 2 );
	}

	/**
	 * Load the plugin textdomain for localistion
	 * @since 0.0.1
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'fsp-premium-helper', false, plugin_basename( dirname( __FILE__ ) ) . "/languages/" );
	}

	/**
	 * Add links to the plugin listing on the installed plugins page
	 * @since 0.0.1
	 */
	public function plugin_action_links( $links, $plugin ) {

		if ( $plugin == FSPPH_PLUGIN_FNAME ) {

			$links['help'] = '<a href="https://www.fivestarplugins.com/premium-upgrade-instructions/" title="' . __( 'View detailed instructions on how to upgrade.', 'fsp-premium-helper' ) . '">' . __( 'Help Upgrading', 'fsp-premium-helper' ) . '</a>';
		}

		return $links;
	}

	public function register_deactivation() {

		// Load version reversion class
		require_once( FSPPH_PLUGIN_DIR . '/includes/VersionReversion.class.php' );
		$version_reversion = new fspHandleVersionReversion();

		$version_reversion->fsp_plugin_deactivation_reversion();
	}

	public function extend_bpfwp_plugin() {
		global $bpfwp_controller;
		
		if ( ! is_object( $bpfwp_controller ) ) { return; }

		require_once( FSPPH_PLUGIN_DIR . '/includes/bpfwp/Setup.class.php' );

		new bpfwpFSPPHSetup();
	}

	public function extend_fdm_plugin() {
		global $fdm_controller;
		
		if ( ! is_object( $fdm_controller ) ) { return; }

		require_once( FSPPH_PLUGIN_DIR . '/includes/fdm/Setup.class.php' );

		new fdmFSPPHSetup();
	}

	public function extend_grfwp_plugin() {
		global $grfwp_controller;
		
		if ( ! is_object( $grfwp_controller ) ) { return; }

		require_once( FSPPH_PLUGIN_DIR . '/includes/grfwp/Setup.class.php' );

		new grfwpFSPPHSetup();
	}

	public function extend_rtb_plugin() {
		global $rtb_controller;
		
		if ( ! is_object( $rtb_controller ) ) { return; }

		require_once( FSPPH_PLUGIN_DIR . '/includes/rtb/Setup.class.php' );

		new rtbFSPPHSetup();
	}

	/**
	 * On update of this plugin, call the notifications conversion transient function
	 *
	 * @since 0.19
	 */
	public function maybe_set_convert_rtb_notifications_transient( $upgrader, $options ) {

		if ( empty( $options['action'] ) or $options['action'] != 'update' ) { return; }

		if ( empty( $options['type'] ) or $options['type'] != 'plugin' ) { return; }

		foreach ( $options['plugins'] as $plugin ) {

			if ( $plugin == FSPPH_PLUGIN_FNAME ) { 

				$this->set_convert_rtb_notifications_transient();
			}
		}
	}

	/**
	 * Create a transient to check whether notifications need to be converted
	 * @since 0.19
	 */
	public function set_convert_rtb_notifications_transient() {

		set_transient( 'rtb_convert_notifications', true, 3600 );

		wp_remote_get( site_url() );
	}

	/**
	 * Register the cron hook that the plugin uses
	 */
	public function cron_schedule_events() {
		$this->cron->schedule_events();
	}

	/**
	 * Unregister the cron hook that the plugin uses
	 */
	public function cron_unschedule_events() {
		$this->cron->unschedule_events();
	}

}
} // endif;

global $fsp_premium_helper;
$fsp_premium_helper = new fspPremiumHelper();

/**
 * Because we refer $fsp_premium_helper in many other modules during their object
 * construction, separating the object construction from its uses. Please refer 
 * to the link after the constructor's official PHP definition.
 * 
 * "Classes which have a constructor method call this method on each newly-
 * created object, so it is suitable for any initialization that the object 
 * may need before it is used."
 * 
 * https://www.php.net/manual/en/language.oop5.decon.php
 */
$fsp_premium_helper->boot();