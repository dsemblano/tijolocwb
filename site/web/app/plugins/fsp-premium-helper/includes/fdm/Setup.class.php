<?php

/**
 * Class to add in the premium FDM features when validated
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'fdmFSPPHSetup' ) ) {
class fdmFSPPHSetup {

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

		require_once( FSPPH_PLUGIN_DIR . '/includes/fdm/Settings.class.php' );
	}

	/**
	 * Spin up instances of our plugin classes.
	 */
	protected function instantiate() {
		
		new fdmFSPPHSettings();
	}

	/**
	 * Run walk-through, load assets, add links to plugin listing, etc.
	 */
	protected function wp_hooks() {

		add_action( 'draft_to_fdm_order_received', array( $this, 'schedule_new_order_notification' ), 10, 1 );
		add_action( 'fspph_new_device_token_saved', array( $this, 'welcome_new_device_notification') );
		add_action( 'fspph_license_expire_notification', array( $this, 'expire_license_notification') );

		add_action( 'fspph_push_order_notification_for_fdm', array( $this, 'notify_app_new_order' ), 10 );
	}

	/*
	* Schedules a new push notification to be sent in the background on the next page load
	*/
	public function schedule_new_order_notification( $post ) {

		$valid_api_keys = fsp_decode_infinite_table_setting( get_option( 'fsp-api-keys' ) );

		if ( empty( $valid_api_keys ) ) { return; }
		
		$args = array(
			'order_id'		=> $post->ID,
			'request_count'	=> 0,
		);

		wp_schedule_single_event( time(), 'fspph_push_order_notification_for_fdm', array( 'args' => $args ) );
	}

	/**
	 * New notification generated when an order is received
	 * */
	public function notify_app_new_order( $args ) {
		
		//If this notification has been attempted 10 or more times, stop trying
		if ( $args['request_count'] >= 10 ) { return false; }

		$order_id = ! empty( $args['order_id'] ) ? intval( $args['order_id'] ) : 0;

		require_once( FDM_PLUGIN_DIR . '/includes/class-order-item.php' );

		$order = new fdmOrderItem();

		$order->load( $order_id );

		if ( empty( $order->ID ) ) { return false; }

		$body = sprintf(
			__( '%s: From %s with email %s', 'food-and-drink-menu' ),
			$order->ID,
			$order->name,
			$order->email
		);

		$notification_args = array(
			'plugin'	=> 'fdm',
			'license'	=> get_option( 'fdm-license-key' ),
			'title'		=> apply_filters( 'fdm_push_noti_title', __( 'New Order', 'food-and-drink-menu' ), $order ),
			'body'		=> apply_filters( 'fdm_push_noti_body', $body, $order ),
			'data'		=> array( 'notice' => false ),
		);

		require_once( FSPPH_PLUGIN_DIR . '/includes/PushNotification.class.php' );

		$notification = new fspphPushNotification();

		$notification->set_notification( $notification_args );

		if ( ! $notification->send_notification() and $args['request_count'] < 10 ) {

			$args['request_count']++;

			wp_schedule_single_event( time(), 'fspph_push_order_notification_for_fdm', array( 'args' => $args ) );
		}
	}

	/**
	 * New notification generated when a device token is saved
	 * */
	public function welcome_new_device_notification() {

		$notification_args = array(
			'plugin'	=> 'fdm',
			'license'	=> get_option( 'fdm-license-key' ),
			'title' 	=> __( 'Welcome to Five Star Restaurant Manager', 'food-and-drink-menu' ),
			'body'		=> __( 'You have successfully registered a new device for push notifications', 'food-and-drink-menu' ),
			'data'		=> array( 'notice' => true ),
		);

		require_once( FSPPH_PLUGIN_DIR . '/includes/PushNotification.class.php' );

		$notification = new fspphPushNotification();

		$notification->set_notification( $notification_args );

		$notification->send_notification();
	}

	/**
	 * New notification generated when a license for push notifications expires
	 * */
	public function expire_license_notification() {
		
		$notification_args = array(
			'plugin'	=> 'fdm',
			'license'	=> get_option( 'fdm-license-key' ),
			'title' 	=> __( 'License Expired', 'food-and-drink-menu' ),
			'body'		=> __( 'The annual license associated with your product key is expired. Please make sure that you\'ve paid your annual license fee to be able to access all app features.', 'food-and-drink-menu' ),
			'data'		=> array( 'notice' => true ),
		);

		require_once( FSPPH_PLUGIN_DIR . '/includes/PushNotification.class.php' );

		$notification = new fspphPushNotification();

		$notification->set_notification( $notification_args );

		$notification->send_notification();
	}
}
}