<?php
/**
 * Class to handle all custom API endpoints for RTB
 */

if ( !defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'fspAPIHandler' ) ) {
class fspAPIHandler {

	/**
	 * Hook in to register apis
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add in all of the custom endpoints necessary
		add_action( 'rest_api_init', array( $this, 'register_api_endpoints' ) );

		// Add in filters to add the API settings panel to the different FSP plugins
		add_filter( 'rtb_settings_page', array( $this, 'add_plugin_settings_panel' ) );
		add_filter( 'fdm_settings_page', array( $this, 'add_plugin_settings_panel' ) );
		add_filter( 'bpfwp_settings_page', array( $this, 'add_plugin_settings_panel' ) );

		// Synchronize API keys across the different plugin options
		if ( ! empty( $_POST['rtb-settings']['fsp-api-keys'] ) ) {
			$this->save_and_sync_api_keys( $_POST['rtb-settings']['fsp-api-keys'] );
		}
		if ( ! empty( $_POST['food-and-drink-menu-settings']['fsp-api-keys'] ) ) {
			$this->save_and_sync_api_keys( $_POST['food-and-drink-menu-settings']['fsp-api-keys'] );
		}
		if ( ! empty( $_POST['bpfwp-settings']['fsp-api-keys'] ) ) {
			$this->save_and_sync_api_keys( $_POST['bpfwp-settings']['fsp-api-keys'] );
		}

		add_action( 'admin_enqueue_scripts', 	array( $this, 'enqueue_admin_assets' ) );
	}

	/**
	 * Call plugin-specific API registrations if conditions met
	 * @since 1.0.0
	 */
	public function register_api_endpoints() {

		//Add in an endpoint to validate the API connection on account save
		$args = array(
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'handle_validation_get_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'fsp-premium-helper/v1', '/validate/', $args );

		//Add in an endpoint where any our app will send the device token for push notifications
		$args = array(
			'methods' 				=> 'POST',
			'callback' 				=> array( $this, 'handle_push_notification_device_token' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'fsp-premium-helper/v1', '/registerpushnotisub/', $args );

		if ( $this->rtb_endpoints_valid() ) { $this->register_restaurant_reservation_api_endpoints(); }
		if ( $this->fdm_endpoints_valid() ) { $this->register_food_and_drink_menu_api_endpoints(); }
		if ( $this->bpfwp_endpoints_valid() ) { $this->register_business_profile_api_endpoints(); }
	}

	/**
	 * Determine whether restaurant reservation apis should be activated
	 * @since 1.0.0
	 */
	public function rtb_endpoints_valid() {
		global $rtb_controller;

		if ( empty( $rtb_controller ) or ! is_object( $rtb_controller ) ) { return false; }

		if ( ! $rtb_controller->permissions->check_permission( 'api_usage' ) ) { return false; }

		return true;
	}

	/**
	 * Determine whether food and drink menu apis should be activated
	 * @since 1.0.0
	 */
	public function fdm_endpoints_valid() {
		global $fdm_controller;

		if ( empty( $fdm_controller ) or ! is_object( $fdm_controller ) ) { return false; }

		if ( ! $fdm_controller->permissions->check_permission( 'api_usage' ) ) { return false; }

		return true;
	}

	/**
	 * Determine whether business profile apis should be activated
	 * @since 1.0.0
	 */
	public function bpfwp_endpoints_valid() {
		global $bpfwp_controller;

		if ( empty( $bpfwp_controller ) or ! is_object( $bpfwp_controller ) ) { return false; }

		if ( ! $bpfwp_controller->permissions->check_permission( 'api_usage' ) ) { return false; }

		return true;
	}

	/**
	 * Register restaurant reservation required apis
	 * @since 1.0.0
	 */
	public function register_restaurant_reservation_api_endpoints() {

		//Add in an endpoint for getting a list of IDs, created times, names and party size for all reservations within a recent time frame
		$args = array(
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'handle_reservations_get_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'restaurant-reservations/v1', '/reservations/', $args );

		//Add in an endpoint for getting a list of possible booking statuses
		$args = array(
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'handle_reservation_statuses_get_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'restaurant-reservations/v1', '/reservation-statuses/', $args );

		//Add in an endpoint for getting a list of possible booking locations
		$args = array(
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'handle_reservation_locations_get_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'restaurant-reservations/v1', '/reservation-locations/', $args );

		//Add in an endpoint for getting specific reservations by ID
		$args = array(
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'handle_reservation_get_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'restaurant-reservations/v1', '/reservation/(?P<id>\d+)', $args );

		//Add in an endpoint for setting the status of a specific reservations by ID
		$args = array(
			'methods' 				=> 'POST',
			'callback' 				=> array( $this, 'handle_reservation_set_status' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'restaurant-reservations/v1', '/set-reservation-status/(?P<id>\d+)', $args );
	}

	/**
	 * Register food and drink menu required apis
	 * @since 1.0.0
	 */
	public function register_food_and_drink_menu_api_endpoints() {

		//Add in an endpoint for getting a list of IDs, created times and statuses for all orders within a recent time frame
		$args = array(
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'handle_orders_get_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'food-and-drink-menu/v1', '/orders/', $args );

		//Add in an endpoint for getting specific orders by ID
		$args = array(
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'handle_order_get_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'food-and-drink-menu/v1', '/order/(?P<id>\d+)', $args );

		//Add in an endpoint for getting specific orders by ID
		$args = array(
			'methods' 				=> 'POST',
			'callback' 				=> array( $this, 'handle_order_set_status' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'food-and-drink-menu/v1', '/set-order-status/(?P<id>\d+)', $args );
	}

	/**
	 * Register business profile required apis
	 * @since 1.0.0
	 */
	public function register_business_profile_api_endpoints() {

		//Add in an endpoint for getting the contact and scheduling information for the site
		$args = array(
			'methods' 				=> 'GET',
			'callback' 				=> array( $this, 'handle_contact_get_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'business-profile/v1', '/contact-information/', $args );

		//Add in an endpoint to update the contact and scheduling information for the site
		$args = array(
			'methods' 				=> 'POST',
			'callback' 				=> array( $this, 'handle_contact_set_request' ),
			'permission_callback' 	=> '__return_true',
		);
		register_rest_route( 'business-profile/v1', '/set-contact-information/', $args );
	}

	/**
	 * Verify whether a submitted API key is valid
	 * @since 1.0.0
	 */
	public function verify_api_key( $api_key ) {

		$valid_api_keys = fsp_decode_infinite_table_setting( get_option( 'fsp-api-keys' ) );

		foreach ( $valid_api_keys as $valid_api_key ) {

			if ( $api_key == $valid_api_key->api_key ) { return true; }
		}

		return false;
	}

	/**
	 * Verify whether a submitted API key is valid
	 * @since 1.0.0
	 */
	public function verify_write_api_key_access( $api_key ) {

		$valid_api_keys = fsp_decode_infinite_table_setting( get_option( 'fsp-api-keys' ) );

		foreach ( $valid_api_keys as $valid_api_key ) {

			if ( $api_key == $valid_api_key->api_key and $valid_api_key->access == 'update' ) { return true; }
		}

		return false;
	}

	/**
	 * Return a success or failure message to validate an API key
	 * @since 1.0.0
	 */
	public function handle_validation_get_request( $request ) {

		$success = $this->verify_api_key( $request->get_param( 'apiKey' ) );

		if ( ! $success ) {

			return array(
				'success' => false,
				'message' => __( 'API key is not valid. Please be sure to copy it exactly from your plugin settings page.', 'fsp-premium-helper' )
			);
		}

		return array( 'success' => true, 'message' => __( 'Settings have been successfully saved.', 'fsp-premium-helper' ) );
	}

	/**
	 * Get a list of all reservations with names, party size, reservation time and IDs
	 * @since 1.0.0
	 */
	public function handle_reservations_get_request( $request ) {

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		$args = array(
			'posts_per_page' 	=> 400, 
			'orderby'			=> 'date',
		);

		if ( ! empty( $request->get_param( 'status' ) ) ) {

			$args['post_status'] = $request->get_param( 'status' );
		}

		if ( ! empty( $request->get_param( 'location' ) ) ) {

			$args['location'] = $request->get_param( 'location' );
		}

		if ( ! empty( $request->get_param( 'start_date_time' ) ) ) {

			$args['start_date'] = substr( $request->get_param( 'start_date_time' ), 0, 10 );
		}

		if ( ! empty( $request->get_param( 'end_date_time' ) ) ) {

			$args['end_date'] = substr( $request->get_param( 'end_date_time' ), 0, 10 );
		}
		
		$email = ! empty( $request->get_param( 'email' ) ) ? sanitize_text_field( $request->get_param( 'email' ) ) : false;
		$phone = ! empty( $request->get_param( 'phone' ) ) ? sanitize_text_field( $request->get_param( 'phone' ) ) : false;
		$name = ! empty( $request->get_param( 'name' ) ) ? sanitize_text_field( $request->get_param( 'name' ) ) : false;

		$reservations = new rtbQuery( $args );

		$reservations->prepare_args();

		$return_data = array();
		foreach ( $reservations->get_bookings() as $reservation ) {

			if ( ! empty( $email ) and strpos( $reservation->email, $email ) === false ) { continue; }
			if ( ! empty( $phone ) and strpos( $reservation->phone, $phone ) === false ) { continue; }
			if ( ! empty( $name ) and strpos( $reservation->name, $name ) === false ) { continue; }

			$reservation->set_location_name();

			$data = array(
				'id' 			=> $reservation->ID,
				'time' 			=> $reservation->date,
				'date' 			=> $reservation->date, //for backwards compatibility
				'name'			=> $reservation->name,
				'party'			=> $reservation->party,
				'email'			=> $reservation->email,
				'phone'			=> $reservation->phone,
				'status'		=> ucfirst( $reservation->post_status ),
				'location'		=> $reservation->location_name,
				'table'			=> implode( ',', $reservation->table ),
				'note'			=> $reservation->message,
				'custom_fields'	=> implode( ', ', $this->get_reservation_custom_fields( $reservation ) ),
				'deposit'		=> $reservation->deposit
			);

			$return_data[] = $data;
		}

		return array( 'success' => true, 'data' => $return_data );
	}

	/**
	 * Returns all custom fields for a booking as $field_title:$value entries
	 * @since 0.18
	 */
	public function get_reservation_custom_fields( $booking ) {
		global $rtb_controller;

		$custom_fields = rtb_get_custom_fields();

		$return_custom_fields = array();
			
		$booking_fields = isset( $booking->custom_fields ) ? $booking->custom_fields : array();

		foreach( $custom_fields as $custom_field ) {
	
		    if ( $custom_field->type == 'fieldset' ) {
		        continue;
		    }

		    $val = isset( $booking_fields[ $custom_field->slug ] ) ? $booking_fields[ $custom_field->slug ] : '';
		
		    $return_custom_fields[] = $custom_field->title . ': ' . $rtb_controller->fields->get_display_value( $val, $custom_field, '', false );
		}

		return $return_custom_fields;
	}

	/**
	 * Get a list of all possible reservation statuses
	 * @since 1.0.0
	 */
	public function handle_reservation_statuses_get_request( $request ) {
		global $rtb_controller;

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		$return_data = array();
		foreach( $rtb_controller->cpts->booking_statuses as $status => $args ) {

			$return_data[ $status ] = $args['label'];
		}

		return array( 'success' => true, 'data' => $return_data );
	}

	/**
	 * Get a list of all possible reservation locations
	 * @since 0.0.16
	 */
	public function handle_reservation_locations_get_request( $request ) {
		global $rtb_controller;

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		$locations = $rtb_controller->locations->get_location_options();

		return array( 'success' => true, 'data' => $locations );
	}

	/**
	 * Get a specific order based on its ID
	 * @since 1.0.0
	 */
	public function handle_reservation_get_request( $request ) {

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}
		
		require_once( RTB_PLUGIN_DIR . '/includes/Booking.class.php');

		$reservation = new rtbBooking();
		
		if ( ! $reservation->load_post( intval( $request['id'] ) ) ) {
			return array( 'success' => false, 'message' => 'Invalid Reservation ID' );
		}

		if ( method_exists( $reservation, 'set_location_name' ) ) { $reservation->set_location_name(); }
		else { $reservation->location_name = ''; }

		return array( 'success' => true, 'data' => $reservation );
	}

	/**
	 * Set the status for a reservation
	 * @since 1.0.0
	 */
	public function handle_reservation_set_status( $request ) {

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		if ( ! $this->verify_write_api_key_access( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_insufficient_api_key_privileges();
		}

		if ( empty( $request->get_param( 'id' ) ) or empty( $request->get_param( 'postStatus' ) ) ) { return false; }

		$post_id = intval( $request->get_param( 'id' ) );
		$post_status = sanitize_text_field( $request->get_param( 'postStatus' ) );

		$args = array(
			'ID'			=> $post_id,
			'post_status'	=> $post_status
		);

		wp_update_post( $args );

		return array( 'success' => true, 'message' => __( 'Reservation status has been updated successfully', 'fsp-premium-helper' ) );
	}

	/**
	 * Get a list of all orders with names, statuses, order time and IDs
	 * @since 1.0.0
	 */
	public function handle_orders_get_request ( $request ) {

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		$order_statuses = fdm_get_order_statuses();

		$date_query = array(
			array(
				'after'	=> '24 hours ago'
			)
		);

		$args = array( 
			'posts_per_page' 	=> -1, 
			'post_type' 		=> FDM_ORDER_POST_TYPE,
			'post_status'		=> array_keys( $order_statuses ),
			//'date_query'		=> $date_query,
			'orderby'			=> 'date',
			'order'				=> 'DESC'
		);

		$date_query = array(
			'column' => 'post_date',
		);

		if ( ! empty( $request->get_param( 'start_date_time' ) ) ) {

			$date_query['after'] = $request->get_param( 'start_date_time' );
		}

		if ( ! empty( $request->get_param( 'end_date_time' ) ) ) {

			$date_query['before'] = $request->get_param( 'end_date_time' );
		}

		if ( sizeof( $date_query ) > 1 ) {

			$args['date_query'] = $date_query;
		}

		if ( ! empty( $request->get_param( 'status' ) ) ) {

			$args['post_status'] = $request->get_param( 'status' );
		}

		$order_posts = get_posts( $args );

		$email = ! empty( $request->get_param( 'email' ) ) ? sanitize_text_field( $request->get_param( 'email' ) ) : false;
		$phone = ! empty( $request->get_param( 'phone' ) ) ? sanitize_text_field( $request->get_param( 'phone' ) ) : false;
		$name = ! empty( $request->get_param( 'name' ) ) ? sanitize_text_field( $request->get_param( 'name' ) ) : false;

		$return_data = array();
		foreach ( $order_posts as $order_post ) {

			$order = new fdmOrderItem();

			$order->load( $order_post );

			if ( ! empty( $email ) and strpos( $order->email, $email ) === false ) { continue; }
			if ( ! empty( $phone ) and strpos( $order->phone, $phone ) === false ) { continue; }
			if ( ! empty( $name ) and strpos( $order->name, $name ) === false ) { continue; }

			$data = array(
				'id' 				=> $order->ID,
				'time' 				=> $order->date,
				'date' 				=> $order->date, //for backwards compatibility
				'status'			=> $order_statuses[ $order->post_status ]['label'],
				'name'				=> $order->name,
				'phone'				=> $order->phone,
				'email'				=> $order->email,
				'items'				=> implode( ', ' , $this->get_order_items( $order ) ),
				'note'				=> $order->note,
				'order_total'		=> $order->get_order_total_tax_in(),
				'receipt_id'		=> $order->receipt_id,
				'estimated_time'	=> $order->estimated_time,
				'custom_fields'		=> implode( ', ', $this->get_order_custom_fields( $order ) ),
				'payment_amount'	=> $order->payment_amount,
			);

			$return_data[] = $data;
		}

		return array( 'success' => true, 'data' => $return_data );
	}

	/**
	 * Returns all items in an order as $item x $quantity entries
	 * @since 0.18
	 */
	public function get_order_items( $order ) {
		global $fdm_controller;

		$return_order_items = array();

		$order_items = $order->get_order_items();

		foreach ( $order_items as $order_item ) {			

			$return_order_items[] = get_the_title( $order_item->id ) . ( property_exists( $order_item, 'quantity' ) ? ' x ' . $order_item->quantity : '' );
		}

		return $return_order_items;
	}

	/**
	 * Returns all custom fields for an order as $field_title:$value entries
	 * @since 0.18
	 */
	public function get_order_custom_fields( $order ) {
		global $fdm_controller;

		$custom_fields = $fdm_controller->settings->get_ordering_custom_fields();

		$return_custom_fields = array();

		foreach ( $custom_fields as $custom_field ) {

			if ( ! isset( $order->custom_fields[ $custom_field->slug ] ) ) { continue; }

			$return_custom_fields[] = $custom_field->name . ': ' . $order->custom_fields[ $custom_field->slug ];
		}

		return $return_custom_fields;
	}

	/**
	 * Get a specific order based on its ID
	 * @since 1.0.0
	 */
	public function handle_order_get_request( $request ) {

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}
		
		$order = new fdmOrderItem();
		
		if ( ! $order->load( intval( $request['id'] ) ) ) {

			return array( 'success' => false, 'Invalid Order ID' );
		}

		$order_total_price = 0;
		$display_items = array();
		foreach ( $order->get_order_items() as $order_item ) {

			$order_item->title = get_the_title( $order_item->id );

			$selected_option_names = array();
			if ( ! empty( $order_item->selected_options ) ) {

				$ordering_options = get_post_meta( $order_item->id, '_fdm_ordering_options', true );
				if ( ! is_array( $ordering_options ) ) { $ordering_options = array(); }

				foreach ( $order_item->selected_options as $selected_option ) {
					if ( array_key_exists( $selected_option, $ordering_options ) ) { $selected_option_names[] = $ordering_options[ $selected_option ]['name']; }
				}
			}

			$item_price = fdm_calculate_admin_price( $order_item );
			$order_total_price += $item_price;

			$display_item = array(
				'id'				=> $order_item->id,
				'name'				=> $order_item->title,
				'quantity'			=> $order_item->quantity,
				'item_price'		=> $item_price,
				'ordering_options' 	=> $selected_option_names,
				'note'				=> $order_item->note
			);

			$display_items[] = $display_item;
		}

		$order->display_items = $display_items;

		$order->total_price = fdm_add_tax_to_price( $order_total_price );

		$order->payment_amount = $order->payment_amount ? $order->payment_amount : 0;

		return array( 'success' => true, 'data' => $order );
	}

	/**
	 * Set the status for an order
	 * @since 1.0.0
	 */
	public function handle_order_set_status( $request ) {
		
		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		if ( ! $this->verify_write_api_key_access( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_insufficient_api_key_privileges();
		}

		if ( empty( $request->get_param( 'id' ) ) or empty( $request->get_param( 'postStatus' ) ) ) { return; }
		
		$post_id = intval( $request->get_param( 'id' ) );
		$post_status = sanitize_text_field( $request->get_param( 'postStatus' ) );

		$args = array(
			'ID'			=> $post_id,
			'post_status'	=> $post_status
		);

		wp_update_post( $args );
		
		return array( 'success' => true, 'message' => __( 'Order has been successfully updated.', 'fsp-premium-helper' ) );
	}

	/**
	 * Get a list of contact and scheduling information for this site
	 * @since 1.0.0
	 */
	public function handle_contact_get_request ( $request ) {
		global $bpfwp_controller;

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		$return_data = array(
			'name'			=> $bpfwp_controller->settings->get_setting( 'name' ),
			'address'		=> $bpfwp_controller->settings->get_setting( 'address' ),
			'phone'			=> $bpfwp_controller->settings->get_setting( 'phone' ),
			'email'			=> $bpfwp_controller->settings->get_setting( 'contact-email' ),
			'hours'			=> $bpfwp_controller->settings->get_setting( 'opening-hours' ),
			'exceptions'	=> $bpfwp_controller->settings->get_setting( 'exceptions' ),
		);
		
		return array( 'success' => true, 'data' => $return_data );
	}

	/**
	 * Update contact and scheduling information for this site
	 * @since 1.0.0
	 */
	public function handle_contact_set_request( $request ) {
		global $bpfwp_controller;

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		if ( ! $this->verify_write_api_key_access( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_insufficient_api_key_privileges();
		}
		
		if ( ! empty( $request->get_param( 'name' ) ) ) { $bpfwp_controller->settings->set_setting( 'name', sanitize_text_field( $request->get_param( 'name' ) ) ); }
		if ( ! empty( $request->get_param( 'phone' ) ) ) { $bpfwp_controller->settings->set_setting( 'phone', sanitize_text_field( $request->get_param( 'phone' ) ) ); }
		if ( ! empty( $request->get_param( 'email' ) ) ) { $bpfwp_controller->settings->set_setting( 'contact-email', sanitize_text_field( $request->get_param( 'email' ) ) ); }

		if ( ! empty( $request->get_param( 'address' ) ) ) { 

			$address = $bpfwp_controller->settings->get_setting( 'address' );

			$address['text'] = sanitize_textarea_field( $request->get_param( 'address' ) );

			$bpfwp_controller->settings->set_setting( 'address', $address ); 
		}

		// if ( ! empty( $request->get_param( 'opening-hours' ) ) ) { $bpfwp_controller->settings->set_setting( 'opening-hours', sanitize_text_field( $request->get_param( 'opening-hours' ) ) ); }
		// if ( ! empty( $request->get_param( 'exceptions' ) ) ) { $bpfwp_controller->settings->set_setting( 'exceptions', sanitize_text_field( $request->get_param( 'exceptions' ) ) ); }

		$bpfwp_controller->settings->save_settings();

		return array( 'success' => true, 'message' => __( 'Contact data has been successfully updated', 'fsp-premium-helper' ) );
	}

	/**
	 * Save token to database sent from app
	 * */
	public function handle_push_notification_device_token( $request ) {

		if ( ! $this->verify_api_key( $request->get_param( 'apiKey' ) ) ) {

			return $this->return_invalid_api_key();
		}

		if ( ! rtb_ultimate_active() and ! fdm_ultimate_active() ) {

			return $this->return_no_ultimate_active();
		}

		$token = $request->get_param( 'token' );

		$messages = array();

		if ( rtb_ultimate_active() ) {

			$messages[] = ! empty( get_option( 'rtb-license-key', false ) ) ? $this->add_notification_token( $token, 'rtu', get_option( 'rtb-license-key' ) ) : __( 'Invalid Restaurant Reservations Ultimate license key.', 'fsp-premium-helper' );			
		}

		if ( fdm_ultimate_active() ) {

			$messages[] = ! empty( get_option( 'fdm-license-key', false ) ) ? $this->add_notification_token( $token, 'fdm', get_option( 'fdm-license-key' ) ) : __( 'Invalid Restaurant Menu Ultimate license key.', 'fsp-premium-helper' );
		}

		$messages = array_unique( $messages );
			
		if ( sizeof( $messages ) > 1 or reset( $messages ) != __( 'You have been subscribed for push notifications.', 'fsp-premium-helper' ) ) {

			return array( 'success' => false, 'message' => implode( '\n', $messages ) );
		}

		do_action( 'fspph_new_device_token_saved' );

		return array( 'success' => true, 'message' => reset( $messages ) );
	}

	/**
	 * Save token to database sent from app
	 * */
	protected function add_notification_token( $token, $plugin, $license_key ) {
		
		$url = add_query_arg(
			array(
				'license_key' => urlencode( $license_key ),
				'plugin'      => urlencode( $plugin ),
				'token_list'  => urlencode( $token )
			),
			'https://www.fivestarplugins.com/pushnotihandler/save-device-token.php'
		);

		$response = wp_remote_get( $url );
		fspph_debug( 'response ' . print_r( $response, true ) );

		if ( $response instanceof WP_Error ) {

			$message = $response->has_errors() ? $response->get_error_message() : __( 'The push notification token did not process properly.', 'fsp-premium-helper' );

			$this->add_error_log_entry( 'TKN001', $message );

			return $message;
		}
		
		$body = json_decode( wp_remote_retrieve_body( $response ), true );
					
		if ( ! $body['success'] ) {

			$error_code = isset( $body['code'] ) ? $body['code'] : __( 'Unknown Code', 'fsp-premium-helper' );
			$message = ! empty( $body['message'] ) ? $body['message'] : __( 'The token did not process properly. If you keep getting this error, contact Five Star Plugins support at contact@fivestarplugins.com.', 'fsp-premium-helper' );
						
			$this->add_error_log_entry( $error_code, $message );

			return $message;
		}
		
		return __( 'You have been subscribed for push notifications.', 'fsp-premium-helper' );
	}

	/**
	 * Return a message when an API request isn't accompanied by a valid key
	 * @since 1.0.0
	 */
	public function return_invalid_api_key() {

		return array( 'success' => false, 'message' => __( 'The API key sent with your request was not valid.', 'fsp-premium-helper' ) );
	}

	/**
	 * Return a message when a push notification save request doesn't have the ultimate version active
	 * @since 1.0.0
	 */
	public function return_no_ultimate_active() {

		return array( 'success' => false, 'message' => __( 'The API key sent with your request is not associated with a valid Ultimate plugin license.', 'fsp-premium-helper' ) );
	}

	/**
	 * Return a message when an API request doesn't have updating privileges
	 * @since 1.0.0
	 */
	public function return_insufficient_api_key_privileges() {

		return array( 'success' => false, 'message' => __( 'The API key sent with your request does not having updating privileges.', 'fsp-premium-helper' ) );
	}

	/**
	 * Add the API key settings panel to FSP plugins
	 * @since 1.0.0
	 */
	public function add_plugin_settings_panel( $sap ) {
		global $rtb_controller;
		global $fdm_controller;
		global $bpfwp_controller;

		$plugin = key( $sap->pages );

		if ( $plugin == 'rtb-settings' ) { 
			$permission = $rtb_controller->permissions->check_permission( 'api_usage' );
			$purchase_url = 'restaurant-reservations';
		 }
		elseif ( $plugin == 'food-and-drink-menu-settings' ) { 
			$permission = $fdm_controller->permissions->check_permission( 'api_usage' );
			$purchase_url = 'restaurant-menu';
		}
		elseif ( $plugin == 'bpfwp-settings' ) { 
			$permission = $bpfwp_controller->permissions->check_permission( 'api_usage' );
			$purchase_url = 'business-profile';
		}
		
		if ( ! $permission ) {
			$api_permissions = array(
				'disabled'        => true,
				'disabled_image'  => '#',
				'purchase_link'   => 'https://www.fivestarplugins.com/plugins/five-star-' . $purchase_url . '/',
				'ultimate_needed' => ( $plugin == 'bpfwp-settings' ) ? false : true,
			);
		}
		else { $api_permissions = array(); }

		$sap->add_section(
			$plugin,
			array(
				'id'            => $plugin . '-api-tab',
				'title'         => __( 'API', 'fsp-premium-helper' ),
				'is_tab'		=> true,
			)
		);

		$sap->add_section(
			$plugin,
			array_merge( 
				array(
					'id'    => 'fsp-api-' . $plugin,
					'title' => __( 'App Access Codes', 'fsp-premium-helper' ),
					'tab'	=> $plugin . '-api-tab',
				),
				$api_permissions
			)
		);

		$sap->add_setting(
			$plugin,
			'fsp-api-' . $plugin,
			'infinite_table',
			array(
				'id'			=> 'fsp-api-keys',
				'title'			=> __( 'App API Keys', 'fsp-premium-helper' ),
				'add_label'		=> __( 'Add Key', 'fsp-premium-helper' ),
				'del_label'		=> __( 'Delete', 'fsp-premium-helper' ),
				'description'	=> __( '', 'fsp-premium-helper' ),
				'fields'		=> array(
					'api_key' => array(
						'type' 		=> 'text',
						'label' 	=> 'Key',
						'required' 	=> true
					),
					'access' => array(
						'type' 		=> 'select',
						'label'		=> 'Access',
						'required'	=> true,
						'options' 	=> array(
							'view' 		=> 'View',
							'update' 	=> 'Update',
						)
					)
				)
			)
		);

		$sap->add_setting(
			$plugin,
			'fsp-api-' . $plugin,
			'html',
			array(
				'id'			=> 'fsrm-error-messages',
				'title'			=> __( 'Five-Star Restaurant Manager Error Log', 'restaurant-reservations' ),
				'html'			=> '
					<p class="description">' . __( 'The table below holds the 10 most recent error notifications that are less than a week old.', 'fsp-premium-helper' ) . '</p>' .
					$this->render_error_log_table(),
			)
		);

		return $sap;
	}

	/**
	 * Delete FSRM error messages older than a week, then display a table with up to 10 messages
	 * @since 0.0.16
	 */
	public function render_error_log_table() {

		$error_messages = is_array( get_option( 'fsrm-error-log' ) ) ? get_option( 'fsrm-error-log' ) : array();

		foreach ( $error_messages as $key => $error_message ) {

			if ( $error_message['timestamp'] + 7*24*3600 < time() ) { unset( $error_messages[ $key ] ); }
		}

		update_option( 'fsrm-error-log', $error_messages );

		if ( empty( $error_messages ) ) {

			return '<p>' . __( 'No error messages to display', 'fsp-premium-helper' ) . '</p>';
		}

		ob_start();

		?>

		<table>

			<thead>
				<tr>
					<th><?php _e( 'Date/Time', 'fsp-premium-helper' ); ?></th>
					<th><?php _e( 'Error Code', 'fsp-premium-helper' ); ?></th>
					<th><?php _e( 'Message', 'fsp-premium-helper' ); ?></th>
				</tr>
			</thead>

			<tbody>

				<?php foreach ( $error_messages as $error_message ) { ?>

					<tr>
						<td><?php echo date( 'Y-m-d H:i:s', $error_message['timestamp'] ); ?></td>
						<td><?php echo esc_html( $error_message['error_code'] ); ?></td>
						<td><?php echo esc_html( $error_message['message'] ); ?></td>
					</tr>

				<?php } ?>

			</tbody>

		</table>

		<?php 

		return ob_get_clean();
	}

	/**
	 * Adds an entry to the error log option
	 * @since 0.0.16
	 */
	public function add_error_log_entry( $error_code, $message ) {
		  
		$error_messages = is_array( get_option( 'fsrm-error-log' ) ) ? get_option( 'fsrm-error-log' ) : array();

		$error_messages[] = array(
			'timestamp'		=> time(),
			'error_code'	=> $error_code,
			'message'		=> $message,
		);

		usort( $error_messages, array( $this, 'sort_by_timestamp' ) );
		 
		update_option( 'fsrm-error-log', array_slice( $error_messages, 0, 10 ) );
	}

	/**
	 * Adds an entry to the error log option
	 * @since 0.0.16
	 */
	public function sort_by_timestamp( $a, $b ) {

		return $b['timestamp'] <=> $a['timestamp'];
	}

	/**
	 * Save the API keys and sync across the different plugins
	 * @since 1.0.0
	 */
	public function save_and_sync_api_keys( $api_keys ) {

		$api_keys = sanitize_text_field( $api_keys );

		update_option( 'fsp-api-keys', $api_keys );

		$rtb_settings = is_array( get_option( 'rtb-settings' ) ) ? get_option( 'rtb-settings' ) : array();
		$fdm_settings = is_array( get_option( 'food-and-drink-menu-settings' ) ) ? get_option( 'food-and-drink-menu-settings' ) : array();
		$bpfwp_settings = is_array( get_option( 'bpfwp-settings' ) ) ? get_option( 'bpfwp-settings' ) : array();

		$rtb_settings['fsp-api-keys'] = $fdm_settings['fsp-api-keys'] = $bpfwp_settings['fsp-api-keys'] = $api_keys;

		update_option( 'rtb-settings', $rtb_settings );
		update_option( 'food-and-drink-menu-settings', $fdm_settings );
		update_option( 'bpfwp-settings', $bpfwp_settings );
	}

	/**
	 * Add in the JS to create a random 20 character key on 'Add' click
	 * @since 1.0.0
	 */
	public function enqueue_admin_assets( $hook ) {
		
		if ( $hook != 'bookings_page_rtb-settings' and $hook != 'business-profile_page_bpfwp-settings' and $hook != 'fdm-menu_page_food-and-drink-menu-settings' ) { return; }

		wp_enqueue_script( 'fsp-admin-js', FSPPH_PLUGIN_URL . '/assets/js/fsp-admin.js', array( 'jquery' ), FSPPH_VERSION, true );
	}
}
}

?>