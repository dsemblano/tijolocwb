<?php

/**
 * Class to add in the premium RTB features when validated
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'rtbFSPPHSetup' ) ) {
class rtbFSPPHSetup {

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

		require_once( FSPPH_PLUGIN_DIR . '/includes/rtb/Settings.class.php' );
	}

	/**
	 * Spin up instances of our plugin classes.
	 */
	protected function instantiate() {
		
		new rtbFSPPHSettings();
	}

	/**
	 * Run walk-through, load assets, add links to plugin listing, etc.
	 */
	protected function wp_hooks() {
		add_action( 'rtb_insert_booking', array( $this, 'schedule_new_booking_notification' ), 10, 1 );
		add_action( 'rtb_update_booking', array( $this, 'schedule_update_booking_notification' ), 10, 1 );

		add_action( 'fspph_new_device_token_saved', array( $this, 'welcome_new_device_notification') );
		add_action( 'fspph_license_expire_notification', array( $this, 'expire_license_notification') );

		add_action( 'fspph_push_booking_notification_for_rtu', array( $this, 'notify_app_booking' ), 10 );
	}

	/*
	* Schedules a new push notification to be sent in the background on the next page load
	*/
	public function schedule_new_booking_notification( $booking ) {
		
		$valid_api_keys = fsp_decode_infinite_table_setting( get_option( 'fsp-api-keys' ) );

		if ( empty( $valid_api_keys ) ) { return; }

		$args = array(
			'booking'		=> $booking,
			'type'			=> 'new',
			'request_count'	=> 0,
		);
		
		wp_schedule_single_event( time(), 'fspph_push_booking_notification_for_rtu', array( 'args' => $args ) );
	}

	/*
	* Schedules a new push notification to be sent in the background on the next page load
	*/
	public function schedule_update_booking_notification( $booking ) {
		
		$valid_api_keys = fsp_decode_infinite_table_setting( get_option( 'fsp-api-keys' ) );

		if ( empty( $valid_api_keys ) ) { return; }

		$args = array(
			'booking'		=> $booking,
			'type'			=> 'update',
			'request_count'	=> 0,
		);

		wp_schedule_single_event( time(), 'fspph_push_booking_notification_for_rtu', array( 'args' => $args ) );
	}

	/**
	 * New notification generated when a booking is received or updated
	 * */
	public function notify_app_booking( $args ) {

		//If this notification has been attempted 10 or more times, stop trying
		if ( $args['request_count'] >= 10 ) { return false; }

		$booking = ! empty( $args['booking'] ) ? $args['booking'] : false;

		if ( empty( $booking->ID ) or get_class( $booking ) != 'rtbBooking' ) { return false; }

		$title = $args['type'] == 'update' ? __( 'Booking modified', 'restaurant-reservations' ) : __( 'New booking', 'restaurant-reservations' );

		$text = $args['type'] == 'update' ? __( '%s made changes. New booking is on %s, email: %s', 'restaurant-reservations' ) : __( '%s booked for %s, email: %s', 'restaurant-reservations' );

		$body = sprintf(
			$text,
			$booking->name,
			$booking->date,
			$booking->email
		);

		$notification_args = array(
			'plugin'	=> 'rtu',
			'license'	=> get_option( 'rtb-license-key' ),
			'title'		=> apply_filters( 'rtb_push_notification_title', $title, $booking ),
			'body'		=> apply_filters( 'rtb_push_notification_body', $body, $booking ),
			'data'		=> array( 'notice' => false ),
		);
		
		require_once( FSPPH_PLUGIN_DIR . '/includes/PushNotification.class.php' );

		$notification = new fspphPushNotification();

		$notification->set_notification( $notification_args );

		if ( ! $notification->send_notification() and $args['request_count'] < 10 ) {

			$args['request_count']++;

			wp_schedule_single_event( time() + ( 60 * intval( $args['request_count'] ) ), 'fspph_push_booking_notification_for_rtu', array( $args ) );
		}
	}

	/**
	 * New notification generated and added to queue to be sent
	 * */
	public function welcome_new_device_notification() {
		
		$notification_args = array(
			'plugin'	=> 'rtu',
			'license'	=> get_option( 'rtb-license-key' ),
			'title' 	=> __( 'Welcome to Five Star Restaurant Manager', 'restaurant-reservations' ),
			'body'		=> __( 'You have successfully registered a new device for push notifications', 'restaurant-reservations' ),
			'data'		=> array( 'notice' => true ),
		);

		require_once( FSPPH_PLUGIN_DIR . '/includes/PushNotification.class.php' );

		$notification = new fspphPushNotification();

		$notification->set_notification( $notification_args );

		$notification->send_notification();
	}

	/**
	 * New notification generated and added to queue to be sent
	 * */
	public function expire_license_notification() {
		
		$notification_args = array(
			'plugin'	=> 'rtu',
			'license'	=> get_option( 'rtb-license-key' ),
			'title' 	=> __( 'License Expired', 'restaurant-reservations' ),
			'body'		=> __( 'The annual license associated with your product key is expired. Please make sure that you\'ve paid your annual license fee to be able to access all app features.', 'restaurant-reservations' ),
			'data'		=> array( 'notice' => true ),
		);

		require_once( FSPPH_PLUGIN_DIR . '/includes/PushNotification.class.php' );

		$notification = new fspphPushNotification();

		$notification->set_notification( $notification_args );

		$notification->send_notification();
	}
}
}