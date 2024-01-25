<?php

/**
 * Class to add in the premium GRFWP features when validated
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'grfwpFSPPHSetup' ) ) {
class grfwpFSPPHSetup {

	/**
	 * Initialize the plugin and register hooks
	 */
	 public function __construct() {

		self::constants();
		self::includes();
		self::instantiate();
		self::wp_hooks();
	}

	/**
	 * Define plugin constants.
	 */
	protected function constants() {

	}

	/**
	 * Include necessary classes.
	 */
	protected function includes() {

		require_once( FSPPH_PLUGIN_DIR . '/includes/grfwp/Settings.class.php' );
	}

	/**
	 * Spin up instances of our plugin classes.
	 */
	protected function instantiate() {
		
		new grfwpFSPPHSettings();
	}

	/**
	 * Run walk-through, load assets, add links to plugin listing, etc.
	 */
	protected function wp_hooks() {

	}

}
}