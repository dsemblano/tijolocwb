<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'rtbFSPPHSettings' ) ) {
class rtbFSPPHSettings {

  public function __construct() {

    add_filter( 'rtb_defaults', array( $this, 'rtb_settings_set_defaults' ), 10, 2 );

    add_filter( 'rtb_settings_page', array( $this, 'rtb_settings__non_premium_tabs' ), 10, 2 );

    add_filter( 'rtb_settings_page', array( $this, 'rtb_settings_advanced_tab' ), 10, 2 );
    add_filter( 'rtb_settings_page', array( $this, 'rtb_settings_notifications_tab' ), 10, 2 );
    add_filter( 'rtb_settings_page', array( $this, 'rtb_settings_payments_tab' ), 10, 2 );
    add_filter( 'rtb_settings_page', array( $this, 'rtb_settings_export_tab' ), 10, 2 );
    add_filter( 'rtb_settings_page', array( $this, 'rtb_settings_labelling_tab' ), 10, 2 );
    add_filter( 'rtb_settings_page', array( $this, 'rtb_settings_styling_tab' ), 10, 2 );

    // Maybe convert RTB notifications to table-style on activation or upgrade
    add_action( 'fsp_plugin_upgrade', array( $this, 'maybe_convert_rtb_notifications_to_table' ) );
    if ( get_transient( 'rtb_convert_notifications' ) ) { add_action( 'wp_loaded', array( $this, 'maybe_convert_rtb_notifications_to_table' ) ); }
  }

  public function get_permission( $permission_type = '' ) {
    global $rtb_controller;
  
    $rtb_premium_permissions = array();
  
    if ( ! $rtb_controller->permissions->check_permission( $permission_type ) ) {
      $rtb_premium_permissions = $rtb_controller->settings->premium_permissions[ $permission_type ];
    }
  
    return $rtb_premium_permissions;
  }

  public function rtb_settings_set_defaults( $defaults, $rtbSettings ) {

    $defaults = array_merge(
      $defaults,
      array(
        // Any default which you are certain that won't be used for free version.
      )
    );
  
    return $defaults;
  }

  public function rtb_settings__non_premium_tabs( $sap, $rtbSettings ) {

    return $sap;
  }

  // "Advanced" Tab
  public function rtb_settings_advanced_tab( $sap, $rtbSettings ) {
    global $rtb_controller;
    
    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-seat-assignments',
          'title'         => __( 'Seat Restrictions', 'restaurant-reservations' ),
          'tab'           => 'rtb-advanced-tab',
        ),
        $this->get_permission( 'premium_seat_restrictions' )
      )
    );

    $dining_block_length_options = array();

    for ( $i = 10; $i <= 240; $i = $i +5 ) {

      $dining_block_length_options[$i] = $i;
    }

    $sap->add_setting(
      'rtb-settings',
      'rtb-seat-assignments',
      'select',
      array(
        'id'      => 'rtb-dining-block-length',
        'title'     => __( 'Dining Block Length', 'restaurant-reservations' ),
        'description'     => __( 'How long, in minutes, does a meal generally last? This setting affects a how long a slot and/or seat unavailable for after someone makes a reservation.', 'restaurant-reservations' ),
        'default'   => $rtbSettings->defaults['rtb-dining-block-length'],
        'blank_option'  => false,
        'options'   => $dining_block_length_options
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-seat-assignments',
      'toggle',
      array(
        'id'      => 'rtb-enable-max-tables',
        'title'     => __( 'Enable Maximums', 'restaurant-reservations' ),
        'description'     => __( 'Only allow a certain number of reservations (set below) during a specific time. Once the maximum number of reservations has been reached, visitors will only be able to select other reservation times.', 'restaurant-reservations' )
      )
    );

    $max_reservation_options = array();
    $max_reservations_upper_limit = apply_filters( 'rtb-max-reservations-upper-limit', 100 );

    for ( $i = 1; $i <= $max_reservations_upper_limit; $i++ ) {

      $max_reservation_options[$i] = $i;
    }

    $sap->add_setting(
      'rtb-settings',
      'rtb-seat-assignments',
      'select',
      array(
        'id'      => 'rtb-max-tables-count',
        'title'     => __( 'Max Reservations', 'restaurant-reservations' ),
        'description'     => __( 'How many reservations, if enabled above, should be allowed at the same time? Set dining block length setting above to change how long a meal typically lasts.', 'restaurant-reservations' ),
        'options'   => $max_reservation_options
      )
    );

    $max_people_options = array();
    $max_people_upper_limit = apply_filters( 'rtb-max-people-upper-limit', 400 );

    for ( $i = 1; $i <= $max_people_upper_limit; $i++ ) {

      $max_people_options[$i] = $i;
    }

    $sap->add_setting(
      'rtb-settings',
      'rtb-seat-assignments',
      'select',
      array(
        'id'          => 'rtb-max-people-count',
        'title'       => __( 'Max People', 'restaurant-reservations' ),
        'description'   => __( 'How many people, if enabled above, should be allowed to be present in the restaurant at the same time? Set dining block length setting above to change how long a meal typically lasts. May not work correctly if max reservations is set.', 'restaurant-reservations' ),
        'options'   => $max_people_options
      )
    );

    $max_auto_confirm_reservation_options = array();
    $max_auto_confirm_reservations_upper_limit = apply_filters( 'rtb-auto-confirm-reservations-upper-limit', 100 );

    for ( $i = 1; $i <= $max_auto_confirm_reservations_upper_limit; $i++ ) {

      $max_auto_confirm_reservation_options[$i] = $i;
    }

    $sap->add_setting(
      'rtb-settings',
      'rtb-seat-assignments',
      'select',
      array(
        'id'      => 'auto-confirm-max-reservations',
        'title'         => __( 'Automatically Confirm Below Reservation Number', 'restaurant-reservations' ),
        'description'   => __( 'Set a maximum number of reservations at one time below which all bookings will be automatically confirmed.', 'restaurant-reservations' ),
        'options'   => $max_auto_confirm_reservation_options
      )
    );

    $max_auto_confirm_seats_options = array();
    $max_auto_confirm_seats_upper_limit = apply_filters( 'rtb-auto-confirm-seats-upper-limit', 400 );

    for ( $i = 1; $i <= $max_auto_confirm_seats_upper_limit; $i++ ) {

      $max_auto_confirm_seats_options[$i] = $i;
    }

    $sap->add_setting(
      'rtb-settings',
      'rtb-seat-assignments',
      'select',
      array(
        'id'      => 'auto-confirm-max-seats',
        'title'         => __( 'Automatically Confirm Below Seats Number', 'restaurant-reservations' ),
        'description'   => __( 'Set a maximum number of seats at one time below which all bookings will be automatically confirmed.', 'restaurant-reservations' ),
        'options'   => $max_auto_confirm_seats_options
      )
    );

    $sap->add_section(
      'rtb-settings',
      array_merge( 
        array(
          'id'            => 'rtb-view-bookings-form',
          'title'         => __( 'View Bookings Form', 'restaurant-reservations' ),
          'tab'           => 'rtb-advanced-tab',
        ),
        $this->get_permission( 'premium_view_bookings' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-view-bookings-form',
      'post',
      array(
        'id'            => 'view-bookings-page',
        'title'         => __( 'View Bookings Page', 'restaurant-reservations' ),
        'description'   => __( 'Select a page on your site to automatically display the view bookings form. Useful for restaurant staff checking guests in as they arrive.', 'restaurant-reservations' ),
        'blank_option'  => true,
        'args'      => array(
          'post_type'     => 'page',
          'posts_per_page'  => -1,
          'post_status'   => 'publish',
          'orderby'       => 'title',
          'order'         => 'ASC',
        ),
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-view-bookings-form',
      'checkbox',
      array(
        'id'          => 'rtb-view-bookings-columns',
        'title'       => __( 'View Bookings Columns', 'restaurant-reservations' ),
        'description' => __( 'Which columns should be displayed on the view bookings page.', 'restaurant-reservations' ),
        'options'     => $rtbSettings->view_bookings_column_options,
        'default'     => $rtbSettings->defaults['rtb-view-bookings-columns'],
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-view-bookings-form',
      'toggle',
      array(
        'id'      => 'view-bookings-private',
        'title'     => __( 'Keep View Bookings Private', 'restaurant-reservations' ),
        'description'     => __( 'Only display the view bookings form to visitors who are logged in to your site.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-view-bookings-form',
      'toggle',
      array(
        'id'      => 'view-bookings-arrivals',
        'title'     => __( 'Check In Arrivals', 'restaurant-reservations' ),
        'description'     => __( 'Allow guests to be checked in as they arrive. This is necessary for late arrival reminders to work correctly.', 'restaurant-reservations' )
      )
    );

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-mailchimp',
          'title'         => __( 'MailChimp', 'restaurant-reservations' ),
          'tab'         => 'rtb-advanced-tab',
        ),
        $this->get_permission( 'mailchimp' )
      )
    );

    // MailChimp API key
    $sap->add_setting(
      'rtb-settings',
      'rtb-mailchimp',
      'mcapikey',
      array(
        'id'            => 'mc-apikey',
        'title'         => __( 'MailChimp API Key', 'restaurant-reservations' ),
        'description'   => '<a href="https://admin.mailchimp.com/account/api/" target="_blank">' . __( 'Retrieve or create an API key for your MailChimp account', 'restaurant-reservations' ) . '</a>',
        'placeholder' => __( 'API Key', 'restaurant-reservations' ),
        'string_status_connected' => __( 'Connected', 'restaurant-reservations' ),
        'string_status_error'   => __( 'Invalid Key', 'restaurant-reservations' ),
      )
    );

    // Don't show the settings until an API key has been successfully entered
    if ( $rtb_controller->mailchimp->status === true ) {

      // MailChimp list and merge fields
      $sap->add_setting(
        'rtb-settings',
        'rtb-mailchimp',
        'mclistmerge',
        array(
          'id'            => 'mc-lists',
          'title'         => __( 'Audience', 'restaurant-reservations' ),
          'description'   => __( 'New booking requests will be subscribed to this audience.', 'restaurant-reservations' ),
          'fields'    => $rtb_controller->mailchimp->merge_fields,
          'string_loading'  => __( 'Loading...', 'restaurant-reservations' ),
        )
      );

      // Opt-out Option
      $sap->add_setting(
        'rtb-settings',
        'rtb-mailchimp',
        'select',
        array(
          'id'            => 'mc-optout',
          'title'         => __( 'Opt-in', 'restaurant-reservations' ),
          'description'   => __( 'Whether to show an option for users to opt-in to being signed up for your mailing list when making a reservation.', 'restaurant-reservations' ),
          'blank_option'  => false,
          'options'   => array(
            ''      => __( 'Show opt-in prompt', 'restaurant-reservations' ),
            'checked' => __( 'Show pre-checked opt-in prompt', 'restaurant-reservations' ),
            'no'    => __( 'Don\'t show opt-in prompt', 'restaurant-reservations' ),
          ),
        )
      );

      // Opt-out prompt text
      $sap->add_setting(
        'rtb-settings',
        'rtb-mailchimp',
        'text',
        array(
          'id'            => 'mc-optprompt',
          'title'         => __( 'Opt-in Prompt', 'restaurant-reservations' ),
          'description'   => __( 'Text to display with the opt-in option.', 'restaurant-reservations' ),
          'placeholder' => $rtbSettings->defaults['mc-optprompt'],
        )
      );
    }

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-table-assignments',
          'title'         => __( 'Table Restrictions', 'restaurant-reservations' ),
          'tab'         => 'rtb-advanced-tab',
        ),
        $this->get_permission( 'premium_table_restrictions' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-table-assignments',
      'toggle',
      array(
        'id'      => 'enable-tables',
        'title'     => __( 'Enable Table Selection', 'restaurant-reservations' ),
        'description' => __( 'Allow guests to select a table that they\'d like to sit at during their visit.', 'restaurant-reservations' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-table-assignments',
      'toggle',
      array(
        'id'      => 'require-table',
        'title'     => __( 'Require Table Selection', 'restaurant-reservations' ),
        'description' => __( 'Don\'t allow a reservation to be made without a valid table selected, even if all other booking criteria are met (acceptable party size, below max reservations/seats).', 'restaurant-reservations' ),
        //'conditional_on'        => 'enable-tables',
        //'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-table-assignments',
      'infinite_table',
      array(
        'id'      => 'rtb-table-sections',
        'title'     => __( 'Sections', 'restaurant-reservations' ),
        'add_label'   => __( 'Add Section', 'restaurant-reservations' ),
        'del_label'   => __( 'Delete', 'restaurant-reservations' ),
        'description' => __( 'Use this area to create sections for your tables. These can help your guests to book a table in their preferred area.', 'restaurant-reservations' ),
        'fields'    => array(
          'section_id' => array(
            'type'    => 'id',
            'label'   => __('Section ID', 'restaurant-reservations' ),
            'required'  => true
          ),
          'name' => array(
            'type'    => 'text',
            'label'   => __('Section Name', 'restaurant-reservations' ),
            'required'  => true
          ),
          'description' => array(
            'type'    => 'textarea',
            'label'   => __('Description', 'restaurant-reservations' ),
            'required'  => true
          )
        ),
        //'conditional_on'        => 'enable-tables',
        //'conditional_on_value'  => true
      )
    );

    $deposit_column = method_exists( $rtbSettings, 'get_table_deposit_column' ) ? $rtbSettings->get_table_deposit_column() : array();

    $sap->add_setting(
      'rtb-settings',
      'rtb-table-assignments',
      'infinite_table',
      array(
        'id'      => 'rtb-tables',
        'title'     => __( 'Tables', 'restaurant-reservations' ),
        'add_label'   => __( 'Add Table', 'restaurant-reservations' ),
        'del_label'   => __( 'Delete', 'restaurant-reservations' ),
        'description' => __( 'Use this area to create tables that can each be customized. This information will be used to let customers select a table that meets their requirements (party size, date/time available).', 'restaurant-reservations' ),
        'fields'    => array_merge(
            array(
              'number' => array(
                'type'    => 'text',
                'label'   => __('Table Number', 'restaurant-reservations' ),
                'required'  => true
              ),
              'min_people' => array(
                'type'    => 'number',
                'label'   => __('Min. People', 'restaurant-reservations' ),
                'required'  => true
              ),
              'max_people' => array(
                'type'    => 'number',
                'label'   => __('Max. People', 'restaurant-reservations' ),
                'required'  => true
              ),
              'section' => array(
                'type'    => 'select',
                'label'   => __('Section', 'restaurant-reservations' ),
                'required'  => false,
                'options'   => $rtbSettings->get_table_section_options()
              ),
              'combinations' => array(
                'type'    => 'text',
                'label'   => __('Combines With', 'restaurant-reservations' ),
                'required'  => false
              ),
            ),
            $deposit_column
        ),
        //'conditional_on'        => 'enable-tables',
        //'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-table-assignments',
      'toggle',
      array(
        'id'      => 'enable-tables-graphic',
        'title'     => __( 'Enable Tables Graphic', 'restaurant-reservations' ),
        'description' => __( 'This adds a column to the booking form and creates space for a table layout graphic. This plugin does not generate the tables graphic. You must upload the image using the option below this one.', 'restaurant-reservations' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-table-assignments',
      'file-upload',
      array(
        'id'          => 'tables-graphic',
        'title'     => __( 'Tables Graphic', 'restaurant-reservations' ),
        'description' => __( 'Use this to select or upload the image you want to use to represent your table layout. You can use a tool like <a href="https://www.socialtables.com/" target="_blank">Social Tables</a> to build your layout.', 'restaurant-reservations' ),
        // 'conditional_on'       => 'enable-tables-graphic',
        // 'conditional_on_value' => true
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-table-assignments',
      'radio',
      array(
        'id'          => 'tables-graphic-location',
        'title'       => __( 'Tables Graphic Location', 'restaurant-reservations' ),
        'description' => __( 'Where should your table layout graphic be located, relative to the booking form? The left and right options will apply to bigger screens. On smaller screens, the left option will move to above and the right option will move to below.', 'restaurant-reservations' ),
        'options'     => array(
          'above'         => 'Above',
          'left'          => 'Left',
          'right'         => 'Right',
          'below'         => 'Below'
        ),
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-table-assignments',
      'text',
      array(
        'id'      => 'tables-graphic-width',
        'title'     => __( 'Tables Graphic Width', 'restaurant-reservations' ),
        'description' => __( 'Set the percentage width of the tables graphic area. Default is 100 for above and below and 30 for left and right. Just write the number and do not put the percent sign (e.g. just write 40).', 'restaurant-reservations' ),
        'small'       => true
      )
    );

    return $sap;
  }

  // "Notifications" Tab
  public function rtb_settings_notifications_tab( $sap, $rtbSettings ) {
    global $rtb_controller;

    // Use an infinite table for notifications instead of discrete notification settings
    // if premium is active
    $this->maybe_remove_basic_notification_settings( $sap );
    
    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-email-templates',
          'title'         => __( 'Email Templates', 'restaurant-reservations' ),
          'tab'           => 'rtb-notifications-tab',
        ),
        $this->get_permission( 'designer' )
      )
    );

    $notifications_settings_url = admin_url( '/admin.php?page=rtb-settings&tab=rtb-notifications-tab' );
    $customizer_url = admin_url( '/customize.php?etfrtb_designer=1&return=' . urlencode( $notifications_settings_url ) );
  
    $sap->add_setting(
      'rtb-settings',
      'rtb-email-templates',
      'html',
      array(
        'id'       => 'etfrtb-load-customizer',
        'title'    => __( 'Email Designer', 'restaurant-reservations' ),
        'html'     => '<a href="' . esc_url( $customizer_url ) . '" class="button">' . __( 'Launch Email Designer', 'restaurant-reservations' ) . '</a>',
        'position' => array( 'top' ),
      )
    );

    // only add in this section if premium is enabled
    // 
    // we stop the standard disable option from displaying so that a blank booking-notifications
    // setting isn't saved before premium is enabled
    if ( $rtb_controller->permissions->check_permission( 'advanced' ) ) {

      $sap->add_section(
        'rtb-settings',
        array_merge(
          array(
            'id'            => 'rtb-reservation-notifications-table',
            'title'         => __( 'Reservation Notifications', 'restaurant-reservations' ),
            'tab'     => 'rtb-notifications-tab',
            'description' => __( 'Create unlimited notifications went a booking is created or its status changes.' ),
          ),
          $this->get_permission( 'advanced' )
        )
      );

      //Defaults
      $cron_event_options = array();

      $payment_event_options = array();

      $message_types = array(
          'email'    => __( 'Email', 'restaurant-reservations' ),
        );

      $description = '';
  
      if ( $rtb_controller->permissions->check_permission( 'reminders' ) ) {
  
        $cron_event_options = array(
          'booking_reminder'        => __( 'Reservation Reminder', 'restaurant-reservations' ),
          'late_for_booking'        => __( 'Late for Reservation', 'restaurant-reservations' ),
          'post_booking_follow_up'  => __( 'Post-Reservation', 'restaurant-reservations' ),
        );

        if ( ! empty( $rtbSettings->get_setting( 'require-deposit' ) ) ) {

          $payment_event_options['booking_payment_pending'] = __( 'Payment Pending (15 min. delay)', 'restaurant-reservations' );
        }

        $message_types = array(
          'email'    => __( 'Email', 'restaurant-reservations' ),
          'sms'      => __( 'SMS', 'restaurant-reservations' ),
        );

        $rtb_credit_information = $this->get_sms_credit_information();
  
        $description = sprintf( __( 'Your ultimate license key is valid until %s, and you have a SMS credit balance of %s. As a reminder, each SMS message segment takes between 1 and 5 credits to send.', 'restaurant-reservations' ), $rtb_credit_information['expiry'], $rtb_credit_information['balance'] );
      }

      // Allow notifications setting to be nulled if no notifications exist, so that the import process can be re-run
      if ( function_exists( 'rtb_decode_infinite_table_setting' ) and empty( rtb_decode_infinite_table_setting( $rtb_controller->settings->get_setting( 'booking-notifications' ) ) ) ) {

        $description .= '<p class="rtb-settings-reset-notifications">' . __( 'Reset Notifications Table Data', 'restaurant-reservations' ) . '</p>';
      }
  
      $events = array_merge(
        array(
          'new_booking'             => __( 'New Booking', 'restaurant-reservations' ),
          'auto_confirmed_booking'  => __( 'Auto-Confirmed Booking', 'restaurant-reservations' ),
          'booking_cancelled'       => __( 'Booking Cancelled', 'restaurant-reservations' ),
          'booking_confirmed'       => __( 'Booking Confirmed', 'restaurant-reservations' ),
          'booking_closed'          => __( 'Booking Closed', 'restaurant-reservations' ),
        ),
        $payment_event_options,
        $cron_event_options
      );
  
      $timing_counts = array();
  
      for ( $i = 1; $i <= 60; $i ++ ) { $timing_counts[ $i ] = $i; }
  
      $sap->add_setting(
        'rtb-settings',
        'rtb-reservation-notifications-table',
        'infinite_table',
        array(
          'id'          => 'booking-notifications',
          'title'       => __( 'Notifications', 'restaurant-reservations' ),
          'add_label'   => __( '+ ADD', 'restaurant-reservations' ),
          'del_label'   => __( 'Delete', 'restaurant-reservations' ),
          'description' => $description,
          'fields'      => array(
            'enabled'    => array(
              'type'     => 'toggle',
              'label'    => 'Enabled',
              'options' => array(
                'true'     => __( '', 'restaurant-reservations' ),
              ),
            ),
            'id'      => array(
              'type'    => 'id',
              'label'   => 'ID',
              'classes' => array( 'sap-hidden' ),
            ),
            'event'  => array(
              'type'    => 'select',
              'label'   => __( 'Event', 'restaurant-reservations' ),
              'options' => $events,
            ),
            'type'    => array(
              'type'    => 'select',
              'label'   => __( 'Type', 'restaurant-reservations' ),
              'options' => $message_types,
            ),
            'target'    => array(
              'type'    => 'select',
              'label'   => __( 'Target', 'restaurant-reservations' ),
              'options' => array(
                'user'     => __( 'Customer', 'restaurant-reservations' ),
                'admin'    => __( 'Admin', 'restaurant-reservations' ),
              )
            ),
            'timing1'    => array(
              'type'    => 'select',
              'label'   => __( 'Timing', 'restaurant-reservations' ),
              'options' => $timing_counts,
              'conditional_on' => 'event',
              'conditional_on_value' => array( 'booking_reminder', 'late_for_booking', 'post_booking_follow_up' )
            ),
            'timing2'    => array(
              'type'    => 'select',
              'label'   => __( '', 'restaurant-reservations' ),
              'options' => array(
                'minutes' => __( 'Minutes', 'restaurant-reservations' ),
                'hours'   => __( 'Hours', 'restaurant-reservations' ),
                'days'    => __( 'Days', 'restaurant-reservations' ),
              ),
              'conditional_on' => 'event',
              'conditional_on_value' => array( 'booking_reminder', 'late_for_booking', 'post_booking_follow_up' )
            ),
            'subject' => array(
              'type'     => 'text',
              'label'    => __( 'Subject', 'restaurant-reservations' ),
            ),
            'message' => array(
              'type'     => 'editor',
              'label'    => __( 'Message', 'restaurant-reservations' ),
            )
          )
        )
      );
    }

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-sms-notifications',
          'title'         => __( 'SMS Notifications', 'restaurant-reservations' ),
          'tab'     => 'rtb-notifications-tab',
          'description' => __( 'Set up reservation and late arrival reminders.' ),
        ),
        $this->get_permission( 'reminders' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-sms-notifications',
      'text',
      array(
        'id'      => 'ultimate-purchase-email',
        'title'     => __( 'Ultimate Plan Purchase Email', 'restaurant-reservations' ),
        'description' => __( 'The email used to purchase your \'Ultimate\' plan subscription. Used to verify SMS requests are actually being sent from your site.', 'restaurant-reservations' ),
        'placeholder' => $rtbSettings->defaults['ultimate-purchase-email'],
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-sms-notifications',
      'text',
      array(
        'id'      => 'admin-sms-phone-number',
        'title'     => __( 'Admin SMS Phone Number', 'restaurant-reservations' ),
        'description' => __( 'The phone number for the administrator, if any SMS notifications are being sent to them.', 'restaurant-reservations' ),
      )
    );

    $country_phone_display_array = array();
    foreach ( $rtbSettings->country_phone_array as $country_code => $country_array ) {
      $country_phone_display_array[$country_code] = $country_array['name'] . ' (+' . $country_array['code'] . ')';
    }

    $sap->add_setting(
      'rtb-settings',
      'rtb-sms-notifications',
      'select',
      array(
        'id'            => 'rtb-country-code',
        'title'         => __( 'Country Code', 'restaurant-reservations' ),
        'description'   => __( 'What country code should be added to SMS reminders? If no country is specified, phone numbers for reservations should start with +XXX (a plus sign followed by the country code), followed by a space or dash, or else the number the phone number will be assumed to be North American.', 'restaurant-reservations' ),
        'blank_option'  => true,
        'options'       => $country_phone_display_array
      )
    );

    $sap->add_section(
      'rtb-settings',
      array(
        'id' => 'rtb-notifications-advanced',
        'title' => __( 'Advanced', 'restaurant-reservations' ),
        'description' => __( "Modifying the settings below can prevent your emails from being delivered. Do not make changes unless you know what you're doing.", 'restaurant-reservations' ),
        'tab' => 'rtb-notifications-tab',
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-notifications-advanced',
      'text',
      array(
        'id'      => 'from-email-address',
        'title'     => __( 'FROM Email Address Header', 'restaurant-reservations' ),
        'description' => sprintf( __( "Change the email address used in the FROM header of all emails sent by this plugin. In most cases you should not change this. Modifying this can prevent your emails from being delivered. %sLearn more%s.", 'restaurant-reservations' ), '<a href="http://doc.fivestarplugins.com/plugins/restaurant-reservations/user/faq#no-emails-from-header">', '</a>' ),
        'placeholder' => $rtbSettings->defaults['from-email-address'],
      )
    );

    return $sap;
  }

  // "Payments" Tab
  public function rtb_settings_payments_tab( $sap, $rtbSettings ) {
    
    // Add settings group in Payment settings tab
      $sap->add_section(
        'rtb-settings',
        array_merge(
          array(
            'id'    => 'rtb-general-payment',
            'title' => __( 'General', 'restaurant-reservations' ),
            'tab'   => 'rtb-payments-tab',
          ),
          $this->get_permission( 'payments' )
        )
      );
  
      // Add settings in General settings group
      $sap->add_setting(
        'rtb-settings',
        'rtb-general-payment',
        'toggle',
        array(
          'id'          => 'require-deposit',
          'title'       => __( 'Require Deposit', 'restaurant-reservations' ),
          'description' => __( 'Require guests to make a deposit when making a reservation.', 'restaurant-reservations' )
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-general-payment',
        'checkbox',
        array(
          'id'          => 'rtb-payment-gateway',
          'title'       => __( 'Payment Gateway', 'restaurant-reservations' ),
          'description' => __( 'Which payment gateway should be used to accept deposits.', 'restaurant-reservations' ),
          'options'     => $rtbSettings->payment_gateway_options,
          'default'     => array('')
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-general-payment',
        'radio',
        array(
          'id'          => 'rtb-deposit-type',
          'title'       => __( 'Deposit Type', 'restaurant-reservations' ),
          'description' => __( 'What type of deposit should be required, per reservation, per guest or per table?', 'restaurant-reservations' ),
          'options'     => array(
            'reservation' => 'Per Reservation',
            'guest'       => 'Per Guest',
            'table'       => 'Per Table'
          ),
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-general-payment',
        'text',
        array(
          'id'          => 'rtb-deposit-amount',
          'title'       => __( 'Deposit Amount', 'restaurant-reservations' ),
          'description' => __( 'What deposit amount is required (either per reservation or per guest, depending on the setting above)? Minimum is $0.50 in most currencies.', 'restaurant-reservations' ),
          'conditional_on'        => 'rtb-deposit-type',
          'conditional_on_value'  => array( 'reservation', 'guest' ),
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-general-payment',
        'select',
        array(
          'id'           => 'rtb-currency',
          'title'        => __( 'Currency', 'restaurant-reservations' ),
          'description'  => __( 'Select the currency you accept for your deposits.', 'restaurant-reservations' ),
          'blank_option' => false,
          'options'      => $rtbSettings->currency_options,
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-general-payment',
        'text',
        array(
          'id'            => 'rtb-stripe-currency-symbol',
          'title'         => __( 'Currency Symbol', 'restaurant-reservations' ),
          'description'   => __( 'The currency symbol you\'d like displayed before or after the required deposit amount.', 'restaurant-reservations' )
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-general-payment',
        'radio',
        array(
          'id'          => 'rtb-currency-symbol-location',
          'title'       => __( 'Currency Symbol Location', 'restaurant-reservations' ),
          'description' => __( 'Should the currency symbol be placed before or after the deposit amount?', 'restaurant-reservations' ),
          'options'     => array(
            'before' => 'Before',
            'after'  => 'After'
          )
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-general-payment',
        'radio',
        array(
          'id'          => 'rtb-deposit-applicable',
          'title'       => __( 'Deposit Applicable', 'restaurant-reservations' ),
          'description' => __( 'If enabled, under what circumstances should a deposit be required?', 'restaurant-reservations' ),
          'options'     => array(
            'always'    => 'At All Times',
            'time_based'  => 'Specific Days/Times (selected below)',
            'size_based'  => 'Groups Over a Certain Size (selected below)'
          ),
          'default'     => $rtbSettings->defaults['rtb-deposit-applicable'],
        )
      );

      // Translateable strings for scheduler components
      $scheduler_strings = array(
        'add_rule'      => __( 'Add new scheduling rule', 'restaurant-reservations' ),
        'weekly'      => _x( 'Weekly', 'Format of a scheduling rule', 'restaurant-reservations' ),
        'monthly'     => _x( 'Monthly', 'Format of a scheduling rule', 'restaurant-reservations' ),
        'date'        => _x( 'Date', 'Format of a scheduling rule', 'restaurant-reservations' ),
        'weekdays'      => _x( 'Days of the week', 'Label for selecting days of the week in a scheduling rule', 'restaurant-reservations' ),
        'month_weeks'   => _x( 'Weeks of the month', 'Label for selecting weeks of the month in a scheduling rule', 'restaurant-reservations' ),
        'date_label'    => _x( 'Date', 'Label to select a date for a scheduling rule', 'restaurant-reservations' ),
        'time_label'    => _x( 'Time', 'Label to select a time slot for a scheduling rule', 'restaurant-reservations' ),
        'allday'      => _x( 'All day', 'Label to set a scheduling rule to last all day', 'restaurant-reservations' ),
        'start'       => _x( 'Start', 'Label for the starting time of a scheduling rule', 'restaurant-reservations' ),
        'end'       => _x( 'End', 'Label for the ending time of a scheduling rule', 'restaurant-reservations' ),
        'set_time_prompt' => _x( 'All day long. Want to %sset a time slot%s?', 'Prompt displayed when a scheduling rule is set without any time restrictions', 'restaurant-reservations' ),
        'toggle'      => _x( 'Open and close this rule', 'Toggle a scheduling rule open and closed', 'restaurant-reservations' ),
        'delete'      => _x( 'Delete rule', 'Delete a scheduling rule', 'restaurant-reservations' ),
        'delete_schedule' => __( 'Delete scheduling rule', 'restaurant-reservations' ),
        'never'       => _x( 'Never', 'Brief default description of a scheduling rule when no weekdays or weeks are included in the rule', 'restaurant-reservations' ),
        'weekly_always'   => _x( 'Every day', 'Brief default description of a scheduling rule when all the weekdays/weeks are included in the rule', 'restaurant-reservations' ),
        'monthly_weekdays'  => _x( '%s on the %s week of the month', 'Brief default description of a scheduling rule when some weekdays are included on only some weeks of the month. %s should be left alone and will be replaced by a comma-separated list of days and weeks in the following format: M, T, W on the first, second week of the month', 'restaurant-reservations' ),
        'monthly_weeks'   => _x( '%s week of the month', 'Brief default description of a scheduling rule when some weeks of the month are included but all or no weekdays are selected. %s should be left alone and will be replaced by a comma-separated list of weeks in the following format: First, second week of the month', 'restaurant-reservations' ),
        'all_day'     => _x( 'All day', 'Brief default description of a scheduling rule when no times are set', 'restaurant-reservations' ),
        'before'      => _x( 'Ends at', 'Brief default description of a scheduling rule when an end time is set but no start time. If the end time is 6pm, it will read: Ends at 6pm', 'restaurant-reservations' ),
        'after'       => _x( 'Starts at', 'Brief default description of a scheduling rule when a start time is set but no end time. If the start time is 6pm, it will read: Starts at 6pm', 'restaurant-reservations' ),
        'separator'     => _x( '&mdash;', 'Separator between times of a scheduling rule', 'restaurant-reservations' ),
      );

      $sap->add_setting(
      'rtb-settings',
      'rtb-general-payment',
      'scheduler',
      array(
        'id'      => 'rtb-deposit-schedule',
        'title'     => __( 'Deposit Applicable Days/Times', 'restaurant-reservations' ),
        'description' => __( 'If selected above, on what days and times should a deposit be required?', 'restaurant-reservations' ),
        'weekdays'    => array(
          'monday'    => _x( 'Mo', 'Monday abbreviation', 'restaurant-reservations' ),
          'tuesday'   => _x( 'Tu', 'Tuesday abbreviation', 'restaurant-reservations' ),
          'wednesday'   => _x( 'We', 'Wednesday abbreviation', 'restaurant-reservations' ),
          'thursday'    => _x( 'Th', 'Thursday abbreviation', 'restaurant-reservations' ),
          'friday'    => _x( 'Fr', 'Friday abbreviation', 'restaurant-reservations' ),
          'saturday'    => _x( 'Sa', 'Saturday abbreviation', 'restaurant-reservations' ),
          'sunday'    => _x( 'Su', 'Sunday abbreviation', 'restaurant-reservations' )
        ),
        'time_format' => $rtbSettings->get_setting( 'time-format' ),
        'date_format' => $rtbSettings->get_setting( 'date-format' ),
        'disable_weeks' => true,
        'disable_date'  => true,
        'strings' => $scheduler_strings,
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-general-payment',
      'select',
      array(
        'id'            => 'rtb-deposit-min-party-size',
        'title'         => __( 'Deposit Applicable Party Size', 'restaurant-reservations' ),
        'description'   => __( 'If selected above, at what party size should deposits be required?', 'restaurant-reservations' ),
        'blank_option'  => false,
        'options'       => $rtbSettings->get_party_size_setting_options(),
      )
    );
  
      // Add settings group in Payment settings tab
      $sap->add_section(
        'rtb-settings',
        array_merge(
          array(
            'id'    => 'rtb-paypal-payment',
            'title' => __( 'PayPal', 'restaurant-reservations' ),
            'tab'   => 'rtb-payments-tab',
          ),
          $this->get_permission( 'payments' )
        )
      );
  
      // Add settings in PayPal settings group
      $sap->add_setting(
        'rtb-settings',
        'rtb-paypal-payment',
        'text',
        array(
          'id'            => 'rtb-paypal-email',
          'title'         => __( 'PayPal Email Address', 'restaurant-reservations' ),
          'description'   => __( 'The email address you\'ll be using to accept PayPal payments.', 'restaurant-reservations' ),
          'placeholder'   =>$rtbSettings->defaults['rtb-paypal-email']
        )
      );
  
      // Add settings group in Payment settings tab
      $sap->add_section(
        'rtb-settings',
        array_merge(
          array(
            'id'    => 'rtb-stripe-payment',
            'title' => __( 'Stripe', 'restaurant-reservations' ),
            'tab'   => 'rtb-payments-tab',
          ),
          $this->get_permission( 'payments' )
        )
      );
  
      // Add settings in Stripe settings group
      $sap->add_setting(
        'rtb-settings',
        'rtb-stripe-payment',
        'toggle',
        array(
          'id'          => 'rtb-stripe-sca',
          'title'       => __( 'Strong Customer Authorization (SCA)', 'restaurant-reservations' ),
          'description' => __( 'User will be redirected to Stripe and presented with 3D secure or bank redirect for payment authentication. (May be necessary for certain EU compliance.)', 'restaurant-reservations' )
        )
      );
	    $sap->add_setting(
	      'rtb-settings',
	      'rtb-stripe-payment',
	      'toggle',
	      array(
	        'id'                    => 'rtb-stripe-hold',
	        'title'                 => __( 'Hold & Charge Separately', 'restaurant-reservations' ),
	        'description'           => __( 'With this enabled, the deposit will be taken as a hold and not charged right away. The payment can then be charged/captured manually later. If not captured, the hold on the amount will be released after 7 days. <em>SCA (option above this one) must be enabled to use this hold feature.</em>', 'restaurant-reservations' ),
	        'conditional_on'        => 'rtb-stripe-sca',
	        'conditional_on_value'  => true
	      )
	    );
      $sap->add_setting(
        'rtb-settings',
        'rtb-stripe-payment',
        'toggle',
        array(
          'id'          => 'rtb-expiration-field-single',
          'title'       => __( 'CC Expiration Single Field', 'restaurant-reservations' ),
          'description' => __( 'Should the field for card expiry details be a single field with a mask or two separate fields for month and year?', 'restaurant-reservations' )
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-stripe-payment',
        'radio',
        array(
          'id'          => 'rtb-stripe-mode',
          'title'       => __( 'Test/Live Mode', 'restaurant-reservations' ),
          'description' => __( 'Should the system use test or live mode? Test mode should only be used for testing, no deposits will actually be processed while turned on.', 'restaurant-reservations' ),
          'options'     => array(
            'test' => 'Test',
            'live' => 'Live'
          )
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-stripe-payment',
        'text',
        array(
          'id'          => 'rtb-stripe-live-secret',
          'title'       => __( 'Stripe Live Secret', 'restaurant-reservations' ),
          'description' => __( 'The live secret that you have set up for your Stripe account.', 'restaurant-reservations' )
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-stripe-payment',
        'text',
        array(
          'id'          => 'rtb-stripe-live-publishable',
          'title'       => __( 'Stripe Live Publishable', 'restaurant-reservations' ),
          'description' => __( 'The live publishable that you have set up for your Stripe account.', 'restaurant-reservations' )
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-stripe-payment',
        'text',
        array(
          'id'          => 'rtb-stripe-test-secret',
          'title'       => __( 'Stripe Test Secret', 'restaurant-reservations' ),
          'description' => __( 'The test secret that you have set up for your Stripe account. Only needed for testing payments.', 'restaurant-reservations' )
        )
      );
      $sap->add_setting(
        'rtb-settings',
        'rtb-stripe-payment',
        'text',
        array(
          'id'          => 'rtb-stripe-test-publishable',
          'title'       => __( 'Stripe Test Publishable', 'restaurant-reservations' ),
          'description' => __( 'The test publishable that you have set up for your Stripe account. Only needed for testing payments.', 'restaurant-reservations' )
        )
      );

    return $sap;
  }

  // "Export" Tab
  public function rtb_settings_export_tab( $sap, $rtbSettings ) {
    
    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-export',
          'title'         => __( 'Settings', 'restaurant-reservations' ),
          'tab'         => 'rtb-export-tab',
        ),
        $this->get_permission( 'export' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-export',
      'select',
      array(
        'id'            => 'ebfrtb-paper-size',
        'title'         => __( 'Paper Size', 'restaurant-reservations' ),
        'description'   => __( 'Select your preferred paper size.', 'restaurant-reservations' ),
        'blank_option'  => false,
        'options'       => array(
          'A4'    => 'A4',
          'LETTER'  => 'Letter (U.S.)',
        )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-export',
      'select',
      array(
        'id'            => 'ebfrtb-pdf-lib',
        'title'         => __( 'PDF Renderer', 'restaurant-reservations' ),
        'description'   => __( 'mPDF looks nicer but is not compatible with all servers. Select TCPDF only if you get errors when trying to export a PDF.', 'restaurant-reservations' ),
        'blank_option'  => false,
        'options'       => array(
          'mpdf'  => 'mPDF',
          'tcpdf' => 'TCPDF',
        ),
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-export',
      'text',
      array(
        'id'            => 'ebfrtb-csv-date-format',
        'title'         => __( 'Excel/CSV Date Format', 'restaurant-reservations' ),
        'description'   => __( 'Enter a custom date format to be used when generating Excel/CSV exports if you want the format to be different than your WordPress setting. This is useful if you need the date in a machine-readable format.', 'restaurant-reservations' ),
        'placeholder' => $rtbSettings->defaults['ebfrtb-csv-date-format'],
      )
    );

    return $sap;
  }

  // "Labelling" Tab
  public function rtb_settings_labelling_tab( $sap, $rtbSettings ) {

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-reservation-form-labelling',
          'title'         => __( 'Reservation Form', 'restaurant-reservations' ),
          'tab'           => 'rtb-labelling-tab',
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-book-table',
        'title'       => __( 'Book a table', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-location',
        'title'       => __( 'Location', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-date',
        'title'       => __( 'Date', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-date-today',
        'title'       => __( 'Today', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-date-clear',
        'title'       => __( 'Clear', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-date-close',
        'title'       => __( 'Close', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-time',
        'title'       => __( 'Time', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-time-clear',
        'title'       => __( 'Clear', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-no-times-available',
        'title'       => __( 'There are currently no times available for booking on your selected date.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-party',
        'title'       => __( 'Party', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-table-s',
        'title'       => __( 'Table(s)', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-table-min',
        'title'       => __( 'min.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-table-max',
        'title'       => __( 'max.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-contact-details',
        'title'       => __( 'Contact Details', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-name',
        'title'       => __( 'Name', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-email',
        'title'       => __( 'Email', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-phone',
        'title'       => __( 'Phone', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-add-message',
        'title'       => __( 'Add a Message', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-message',
        'title'       => __( 'Message', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-request-booking',
        'title'       => __( 'Request Booking', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-labelling',
      'text',
      array(
        'id'          => 'label-table-layout',
        'title'       => __( 'Table Layout', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-reservation-form-validation-labelling',
          'title'         => __( 'Reservation Form Validation', 'restaurant-reservations' ),
          'tab'           => 'rtb-labelling-tab',
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-enter-date-to-book',
        'title'       => __( 'Please enter the date you would like to book.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-date-entered-not-valid',
        'title'       => __( 'The date you entered is not valid. Please select from one of the dates in the calendar.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-enter-time-to-book',
        'title'       => __( 'Please enter the time you would like to book.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-time-entered-not-valid',
        'title'       => __( 'The time you entered is not valid. Please select from one of the times provided.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-bookings-cannot-be-made-more-than-days-in-advance',
        'title'       => __( 'Sorry, bookings can not be made more than %s days in advance.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-bookings-cannot-be-made-in-past',
        'title'       => __( 'Sorry, bookings can not be made in the past.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-bookings-cannot-be-made-same-day',
        'title'       => __( 'Sorry, bookings can not be made for the same day.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-bookings-must-be-made-more-than-days-in-advance',
        'title'       => __( 'Sorry, bookings must be made more than %s days in advance.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-bookings-must-be-made-more-than-hours-in-advance',
        'title'       => __( 'Sorry, bookings must be made more than %s hours in advance.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-bookings-must-be-made-more-than-minutes-in-advance',
        'title'       => __( 'Sorry, bookings must be made more than %s minutes in advance.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-no-bookings-accepted-then',
        'title'       => __( 'Sorry, no bookings are being accepted then.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-no-bookings-accepted-on-that-date',
        'title'       => __( 'Sorry, no bookings are being accepted on that date.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-no-bookings-accepted-at-that-time',
        'title'       => __( 'Sorry, no bookings are being accepted at that time.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-enter-name-for-booking',
        'title'       => __( 'Please enter a name for this booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-how-many-people-in-party',
        'title'       => __( 'Please let us know how many people will be in your party.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-only-accept-bookings-for-parties-up-to',
        'title'       => __( 'We only accept bookings for parties of up to %d people.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-only-accept-bookings-for-parties-more-than',
        'title'       => __( 'We only accept bookings for parties of more than %d people.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-enter-email-address-to-confirm-booking',
        'title'       => __( 'Please enter an email address so we can confirm your booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-enter-valid-email-address-to-confirm-booking',
        'title'       => __( 'Please enter a valid email address so we can confirm your booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-provide-phone-number-to-confirm-booking',
        'title'       => __( 'Please provide a phone number so we can confirm your booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-select-table-for-booking',
        'title'       => __( 'Please select a table for your booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-select-valid-table-for-booking',
        'title'       => __( 'Please select a valid table for your booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-no-table-available',
        'title'       => __( 'No table available at this time. Please change your selection.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-fill-out-recaptcha',
        'title'       => __( 'Please fill out the reCAPTCHA box before submitting.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-fill-out-recaptcha-again',
        'title'       => __( 'Please fill out the reCAPTCHA box again and re-submit.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-if-encounter-multiple-recaptcha-errors',
        'title'       => __( 'If you encounter reCAPTCHA error multiple times, please contact us.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-complete-this-field-to-request-booking',
        'title'       => __( 'Please complete this field to request a booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-booking-has-been-rejected',
        'title'       => __( 'Your booking has been rejected. Please call us if you would like to make a booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-maximum-reservations-reached',
        'title'       => __( 'The maximum number of reservations for that timeslot has been reached. Please select a different timeslot.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-maximum-seats-reached',
        'title'       => __( 'With your party, the maximum number of seats for that timeslot would be exceeded. Please select a different timeslot or reduce your party size.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-booking-info-exactly-matches',
        'title'       => __( 'Your booking and personal information exactly matches another booking. If this was not caused by refreshing the page, please call us to make a booking.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-validation-labelling',
      'text',
      array(
        'id'          => 'label-something-went-wrong',
        'title'       => __( 'Something went wrong. Please try again and, if the issue persists, please contact us.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-reservation-payment-labelling',
          'title'         => __( 'Reservation Payments', 'restaurant-reservations' ),
          'tab'           => 'rtb-labelling-tab',
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-payment-gateway',
        'title'       => __( 'Payment Gateway', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-proceed-to-deposit',
        'title'       => __( 'Proceed to Deposit', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-request-or-deposit',
        'title'       => __( 'Request Booking or Proceed to Deposit', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-pay-via-paypal',
        'title'       => __( 'Pay via PayPal', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-deposit-required',
        'title'       => __( 'Deposit Required', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-deposit-placing-hold',
        'title'       => __( 'We are only placing a hold for the above amount on your payment instrument. You will be charged later.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-card-detail',
        'title'       => __( 'Card Detail', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-card-number',
        'title'       => __( 'Card Number', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-cvc',
        'title'       => __( 'CVC', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-expiration',
        'title'       => __( 'Expiration (MM/YYYY)', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-please-wait',
        'title'       => __( 'Please wait. Do not refresh until the button enables or the page reloads.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-payment-labelling',
      'text',
      array(
        'id'          => 'label-make-deposit',
        'title'       => __( 'Make Deposit', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-modify-reservation-labelling',
          'title'         => __( 'Modify Reservations', 'restaurant-reservations' ),
          'tab'           => 'rtb-labelling-tab',
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-reservation',
        'title'       => __( 'View/Cancel a Reservation', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-make-reservation',
        'title'       => __( 'Make a reservation', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-using-form',
        'title'       => __( 'Use the form below to modify your reservation', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-form-email',
        'title'       => __( 'Email:', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-find-reservations',
        'title'       => __( 'Find Reservations', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-no-bookings-found',
        'title'       => __( 'No bookings were found for the email address you entered.', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-cancel',
        'title'       => __( 'Cancel', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-cancelled',
        'title'       => __( 'Cancelled', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-deposit',
        'title'       => __( 'Deposit', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-guest',
        'title'       => __( 'guest', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-modify-reservation-labelling',
      'text',
      array(
        'id'          => 'label-modify-guests',
        'title'       => __( 'guests', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-view-reservations-labelling',
          'title'         => __( 'View Bookings', 'restaurant-reservations' ),
          'tab'           => 'rtb-labelling-tab',
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-arrived',
        'title'       => __( 'Arrived', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-time',
        'title'       => __( 'Time', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-party',
        'title'       => __( 'Party', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-name',
        'title'       => __( 'Name', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-email',
        'title'       => __( 'Email', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-phone',
        'title'       => __( 'Phone', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-table',
        'title'       => __( 'Table', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-status',
        'title'       => __( 'Status', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-details',
        'title'       => __( 'Details', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-set-status-arrived',
        'title'       => __( 'Set reservation status to \'Arrived\'?', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-arrived-yes',
        'title'       => __( 'Yes', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-view-reservations-labelling',
      'text',
      array(
        'id'          => 'label-view-arrived-no',
        'title'       => __( 'No', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-emails-labelling',
          'title'         => __( 'Emails', 'restaurant-reservations' ),
          'tab'           => 'rtb-labelling-tab',
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-emails-labelling',
      'text',
      array(
        'id'          => 'label-cancel-link-tag',
        'title'       => __( 'Cancel booking', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-emails-labelling',
      'text',
      array(
        'id'          => 'label-bookings-link-tag',
        'title'       => __( 'View pending bookings', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-emails-labelling',
      'text',
      array(
        'id'          => 'label-confirm-link-tag',
        'title'       => __( 'Confirm this booking', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-emails-labelling',
      'text',
      array(
        'id'          => 'label-close-link-tag',
        'title'       => __( 'Reject this booking', 'restaurant-reservations' ),
        'description' => ''
      )
    );

    return $sap;
  }

  // "Styling" Tab
  public function rtb_settings_styling_tab( $sap, $rtbSettings ) {
    
    $sap->add_section(
      'rtb-settings',
      array_merge(
        array(
          'id'            => 'rtb-reservation-form-styling',
          'title'         => __( 'Reservation Form', 'restaurant-reservations' ),
          'tab'           => 'rtb-styling-tab',
        ),
        $this->get_permission( 'styling' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'radio',
      array(
        'id'      => 'rtb-styling-layout',
        'title'     => __( 'Layout', 'restaurant-reservations' ),
        'description' => __( 'Choose which layout you want to use for your reservation form', 'restaurant-reservations' ),
        'options'   => array(
          'default'   => 'Default',
          'minimal'   => 'Minimal',
          'contemporary'  => 'Contemporary',
          'columns'   => 'Columns',
          'columns_alternate'   => 'Columns Alternate',
        )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'text',
      array(
        'id'      => 'rtb-styling-section-title-font-family',
        'title'     => __( 'Section Title Font Family', 'restaurant-reservations' ),
        'description' => __( 'Choose the font family for the section titles. (Please note that the font family must already be loaded on the site. This does not load it.)', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'text',
      array(
        'id'      => 'rtb-styling-section-title-font-size',
        'title'     => __( 'Section Title Font Size', 'restaurant-reservations' ),
        'description' => __( 'Choose the font size for the section titles. Include the unit (e.g. 20px or 2em).', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-section-title-color',
        'title'     => __( 'Section Title Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the color for the section titles.', 'restaurant-reservations' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-section-background-color',
        'title'     => __( 'Section Background Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the form sections.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'text',
      array(
        'id'      => 'rtb-styling-section-border-size',
        'title'     => __( 'Section Border Size', 'restaurant-reservations' ),
        'description' => __( 'Choose the border size for the form sections (in the default layout). Include the unit (e.g. 2px).', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-section-border-color',
        'title'     => __( 'Section Border Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the color for the section border (in the default layout).', 'restaurant-reservations' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'text',
      array(
        'id'      => 'rtb-styling-label-font-family',
        'title'     => __( 'Label Font Family', 'restaurant-reservations' ),
        'description' => __( 'Choose the font family for the form field labels. (Please note that the font family must already be loaded on the site. This does not load it.)', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'text',
      array(
        'id'      => 'rtb-styling-label-font-size',
        'title'     => __( 'Label Font Size', 'restaurant-reservations' ),
        'description' => __( 'Choose the font size for the form field labels. Include the unit (e.g. 20px or 2em).', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-label-color',
        'title'     => __( 'Label Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the color for the form field labels.', 'restaurant-reservations' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-add-message-button-background-color',
        'title'     => __( '"Add a Message" Button Background Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the "Add a Message" button.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-add-message-button-background-hover-color',
        'title'     => __( '"Add a Message" Button Background Hover Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the "Add a Message" button on hover.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-add-message-button-text-color',
        'title'     => __( '"Add a Message" Button Text Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the text color for the "Add a Message" button.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-add-message-button-text-hover-color',
        'title'     => __( '"Add a Message" Button Text Hover Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the text color for the "Add a Message" button on hover.', 'restaurant-reservations' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-request-booking-button-background-color',
        'title'     => __( '"Request Booking" Button Background Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the "Request Booking" button.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-request-booking-button-background-hover-color',
        'title'     => __( '"Request Booking" Button Background Hover Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the "Request Booking" button on hover.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-request-booking-button-text-color',
        'title'     => __( '"Request Booking" Button Text Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the text color for the "Request Booking" button.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-request-booking-button-text-hover-color',
        'title'     => __( '"Request Booking" Button Text Hover Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the text color for the "Request Booking" button on hover.', 'restaurant-reservations' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-cancel-button-background-color',
        'title'     => __( '"Cancel Reservation" Button Background Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the cancel reservation toggle button button.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-cancel-button-background-hover-color',
        'title'     => __( '"Cancel Reservation" Button Background Hover Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the cancel reservation toggle button on hover.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-cancel-button-text-color',
        'title'     => __( '"Cancel Reservation" Text Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the text color for the cancel reservation toggle button.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-cancel-button-text-hover-color',
        'title'     => __( '"Cancel Reservation" Text Hover Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the text color for the cancel reservation toggle button on hover.', 'restaurant-reservations' )
      )
    );

    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-find-reservations-button-background-color',
        'title'     => __( '"Find Reservations" Button Background Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the "Find Reservations" button.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-find-reservations-button-background-hover-color',
        'title'     => __( '"Find Reservations" Button Background Hover Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the background color for the "Find Reservations" button on hover.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-find-reservations-button-text-color',
        'title'     => __( '"Find Reservations" Button Text Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the text color for the "Find Reservations" button.', 'restaurant-reservations' )
      )
    );
    $sap->add_setting(
      'rtb-settings',
      'rtb-reservation-form-styling',
      'colorpicker',
      array(
        'id'      => 'rtb-styling-find-reservations-button-text-hover-color',
        'title'     => __( '"Find Reservations" Button Text Hover Color', 'restaurant-reservations' ),
        'description' => __( 'Choose the text color for the "Find Reservations" button on hover.', 'restaurant-reservations' )
      )
    );
    
    return $sap;
  }

  /**
   * Retrieves the number of SMS credits remaining for an ultimate license key
   * @since 0.0.19
   */
  public function get_sms_credit_information() {
    global $fsp_premium_helper;
    global $rtb_controller;

    if ( ! get_transient( 'rtb-credit-information' ) ) { 

      // Set the transient to blank, to prevent 100's of requests at once if our site is down
      $transient = array(
        'expiry'  => time() + 3600 * 4,
        'balance' => 'Unknown',
      );

      set_transient( 'rtb-credit-information', $transient, 3600 * 3 );

      $args = array(
        'license_key'     => get_option( 'rtb-ultimate-license-key' ),
        'purchase_email'  => $rtb_controller->settings->get_setting( 'ultimate-purchase-email' ),
        'plugin'          => 'rtu',
      );

      $credit_information = $fsp_premium_helper->updates->retrieve_sms_credit_information( $args );

      $transient = array(
        'expiry'  => $credit_information->success ? $credit_information->expiry : __( 'No Ultimate license found', 'restaurant-reservations' ),
        'balance' => $credit_information->success ? $credit_information->balance : 0,
      );

      set_transient( 'rtb-credit-information', $transient, 3600 * 24 * 7 );
    }

    $args = array();

    return get_transient( 'rtb-credit-information' );
  }

  /**
   * Removes a setting from a section after it's been initialized
   *
   * @since 0.0.19
   */
  public function maybe_remove_basic_notification_settings( $sap ) {
    global $rtb_controller;

    if ( ! $rtb_controller->permissions->check_permission( 'advanced' ) ) { return; }

    if ( ! method_exists( $sap, 'remove_setting' ) ) { return; }

    $sap->remove_setting( 'rtb-settings', 'rtb-notifications', 'admin-email-option' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications', 'admin-confirmed-email-option' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications', 'admin-cancelled-email-option' );

    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'subject-booking-admin' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'template-booking-admin' );

    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'subject-booking-confirmed-admin' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'template-booking-confirmed-admin' );

    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'subject-booking-cancelled-admin' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'template-booking-cancelled-admin' );

    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'subject-booking-user' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'template-booking-user' );

    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'subject-confirmed-user' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'template-confirmed-user' );

    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'subject-rejected-user' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'template-rejected-user' );

    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'subject-booking-cancelled-user' );
    $sap->remove_setting( 'rtb-settings', 'rtb-notifications-templates', 'template-booking-cancelled-user' );
  }

  /**
   * Determine whether notifications need to be converted to the table-style
   *
   * @since 0.0.19
   */
  public function maybe_convert_rtb_notifications_to_table( $upgrader = null ) {
    global $rtb_controller;

    if ( empty( $rtb_controller ) ) { return; }

    if ( ! $rtb_controller->permissions->check_permission( 'advanced' ) ) { return; }

    if ( ! empty( $rtb_controller->settings->get_setting( 'booking-notifications' ) ) ) { return; }

    $this->convert_booking_notifications_to_table();

    delete_transient( 'rtb_convert_notifications' );
  }

  /**
   * Convert classic booking notifications to the table-style notifications
   *
   * @since 0.0.19
   */
  public function convert_booking_notifications_to_table() {
    global $rtb_controller;

    if ( empty( $rtb_controller ) ) { return; }

    if ( ! $rtb_controller->permissions->check_permission( 'advanced' ) ) { return; }

    if ( ! empty( $rtb_controller->settings->get_setting( 'booking-notifications' ) ) ) { return; }

    $this->convert_etfrtb_settings();

    $reminder_timing = $this->convert_count_to_timing_fields( $rtb_controller->settings->get_setting( 'time-reminder-user' ) );
    $late_user_timing = $this->convert_count_to_timing_fields( $rtb_controller->settings->get_setting( 'time-late-user' ) );
    $follow_up_timing = $this->convert_count_to_timing_fields( $rtb_controller->settings->get_setting( 'time-post-reservation-follow-up-user' ) );

    $new_notification_settings = array(
      array(
        'id'      => 1,
        'enabled' => $rtb_controller->settings->get_setting( 'admin-email-option' ),
        'event'  => 'new_booking',
        'type'    => 'email',
        'target'  => 'admin',
        'subject' => $rtb_controller->settings->get_setting( 'subject-booking-admin' ),
        'message' => $rtb_controller->settings->get_setting( 'template-booking-admin' ),
      ),
      array(
        'id'      => 2,
        'enabled' => $rtb_controller->settings->get_setting( 'admin-confirmed-email-option' ),
        'event'  => 'auto_confirmed_booking',
        'type'    => 'email',
        'target'  => 'admin',
        'subject' => $rtb_controller->settings->get_setting( 'subject-booking-confirmed-admin' ),
        'message' => $rtb_controller->settings->get_setting( 'template-booking-confirmed-admin' ),
      ),
      array(
        'id'      => 3,
        'enabled' => $rtb_controller->settings->get_setting( 'admin-cancelled-email-option' ),
        'event'  => 'booking_cancelled',
        'type'    => 'email',
        'target'  => 'admin',
        'subject' => $rtb_controller->settings->get_setting( 'subject-booking-cancelled-admin' ),
        'message' => $rtb_controller->settings->get_setting( 'template-booking-cancelled-admin' ),
      ),

      array(
        'id'      => 4,
        'enabled' => true,
        'event'  => 'new_booking',
        'type'    => 'email',
        'target'  => 'user',
        'subject' => $rtb_controller->settings->get_setting( 'subject-booking-user' ),
        'message' => $rtb_controller->settings->get_setting( 'template-booking-user' ),
      ),
      array(
        'id'      => 5,
        'enabled' => true,
        'event'  => 'booking_confirmed',
        'type'    => 'email',
        'target'  => 'user',
        'subject' => $rtb_controller->settings->get_setting( 'subject-confirmed-user' ),
        'message' => $rtb_controller->settings->get_setting( 'template-confirmed-user' ),
      ),
      
      array(
        'id'      => 6,
        'enabled' => true,
        'event'  => 'auto_confirmed_booking',
        'type'    => 'email',
        'target'  => 'user',
        'subject' => $rtb_controller->settings->get_setting( 'subject-confirmed-user' ),
        'message' => $rtb_controller->settings->get_setting( 'template-confirmed-user' ),
      ),
      array(
        'id'      => 7,
        'enabled' => true,
        'event'  => 'booking_closed',
        'type'    => 'email',
        'target'  => 'user',
        'subject' => $rtb_controller->settings->get_setting( 'subject-rejected-user' ),
        'message' => $rtb_controller->settings->get_setting( 'template-rejected-user' ),
      ),
    );

    if ( $rtb_controller->permissions->check_permission( 'reminders' ) ) {

      $new_notification_settings = array_merge(
        $new_notification_settings,
        array(
          array(
            'id'      => 8,
            'enabled' => $rtb_controller->settings->get_setting( 'reminder-notification-format' ) == 'none' ? false : true,
            'event'  => 'booking_reminder',
            'type'    => $rtb_controller->settings->get_setting( 'reminder-notification-format' ) == 'text' ? 'sms' : 'email',
            'target'  => 'user',
            'timing1'  => $reminder_timing['count'],
            'timing2'  => $reminder_timing['unit'],
            'subject' => $rtb_controller->settings->get_setting( 'subject-reminder-user' ),
            'message' => $rtb_controller->settings->get_setting( 'template-reminder-user' ),
          ),
          array(
            'id'      => 9,
            'enabled' => $rtb_controller->settings->get_setting( 'late-notification-format' ) == 'none' ? false : true,
            'event'  => 'late_for_booking',
            'type'    => $rtb_controller->settings->get_setting( 'late-notification-format' ) == 'text' ? 'sms' : 'email',
            'target'  => 'user',
            'timing1'  => $late_user_timing['count'],
            'timing2'  => $late_user_timing['unit'],
            'subject' => $rtb_controller->settings->get_setting( 'subject-late-user' ),
            'message' => $rtb_controller->settings->get_setting( 'template-late-user' ),
          ),
          array(
            'id'      => 10,
            'enabled' => $rtb_controller->settings->get_setting( 'post-reservation-follow-up-notification-format' ) == 'none' ? false : true,
            'event'  => 'post_booking_follow_up',
            'type'    => $rtb_controller->settings->get_setting( 'post-reservation-follow-up-notification-format' ) == 'text' ? 'sms' : 'email',
            'target'  => 'user',
            'timing1'  => $follow_up_timing['count'],
            'timing2'  => $follow_up_timing['unit'],
            'subject' => $rtb_controller->settings->get_setting( 'subject-post-reservation-follow-up-user' ),
            'message' => $rtb_controller->settings->get_setting( 'template-post-reservation-follow-up-user' ),
          )
        )
      );
    }

    $rtb_controller->settings->set_setting( 'booking-notifications', json_encode( $new_notification_settings ) );

    $rtb_controller->settings->save_settings();

    // update all recent bookings, so that reminders/late arrivals/follow ups aren't resent
    if ( $rtb_controller->permissions->check_permission( 'reminders' ) ) {

      $after_datetime = new DateTime( 'now', wp_timezone() );
      $before_datetime = new DateTime( 'now', wp_timezone() );

      $after_datetime->setTimestamp( time() - 14*24*3600 );
      $before_datetime->setTimestamp( time() + 30*24*3600 );

      $args = array(
        'post_status' => 'confirmed,',
        'posts_per_page' => -1,
        'date_query' => array(
          'before' => $before_datetime->format( 'Y-m-d H:i:s' ),
          'after' => $after_datetime->format( 'Y-m-d H:i:s' ),
          'column' => 'post_date'
        )
      );

      require_once( RTB_PLUGIN_DIR . '/includes/Query.class.php' );

      $query = new rtbQuery( $args );
  
      $query->prepare_args();

      foreach( $query->get_bookings() as $booking ) {

        if ( $booking->reminder_sent ) { $booking->reservation_notifications[] = 8; }
        if ( $booking->late_arrival_sent ) { $booking->reservation_notifications[] = 9; }
        if ( $booking->post_reservation_follow_up_sent ) { $booking->reservation_notifications[] = 10; }

        $booking->insert_post_meta();
      }
    }
  }

  /**
   * Helper function for converting to notifications table format
   *
   * @since 0.0.19
   */
  public function convert_count_to_timing_fields( $count_value ) {

    $count = strpos( strval( $count_value ), '_' ) !== false ? substr( $count_value, 0, strpos( $count_value, '_' ) ) : $count_value; 
    $unit = strpos( strval( $count_value ), '_' ) !== false ? substr( $count_value, strpos( $count_value, '_' ) + 1 ) : '';

    return array( 'count' => $count, 'unit' => $unit );
  }

  /**
   * Converts all of the customizer email settings to work with the new
   * table notification format
   *
   * @since 0.0.19
   */
  public function convert_etfrtb_settings() {
    global $rtb_controller;

    $etfrtb_settings = array(
      0 => array(
        'template'        => get_option( 'etfrtb_admin_notice_template', 'conversations.php' ),
        'lead'            => get_option( 'etfrtb_admin_notice_headline', $rtb_controller->settings->get_setting( 'subject-admin-notice' ) ),
        'footer_message'  => get_option( 'etfrtb_admin_notice_footer_message', '' ),
      ),
      1 => array(
        'template'        => get_option( 'etfrtb_booking_admin_template', 'conversations.php' ),
        'lead'            => get_option( 'etfrtb_booking_admin_headline', $rtb_controller->settings->get_setting( 'subject-booking-admin' ) ),
        'footer_message'  => get_option( 'etfrtb_booking_admin_footer_message', '' ),
      ),
      2 => array(
        'template'        => 'conversations.php',
        'lead'            => $rtb_controller->settings->get_setting( 'subject-booking-confirmed-admin' ),
        'footer_message'  => '',
      ),
      3 => array(
        'template'        => 'conversations.php',
        'lead'            => $rtb_controller->settings->get_setting( 'subject-booking-cancelled-admin' ),
        'footer_message'  => '',
      ),
      4 => array(
        'template'        => get_option( 'etfrtb_booking_user_template', 'conversations.php' ),
        'lead'            => get_option( 'etfrtb_booking_user_headline', $rtb_controller->settings->get_setting( 'subject-booking-user' ) ),
        'footer_message'  => get_option( 'etfrtb_booking_user_footer_message', '' ),
      ),
      5 => array(
        'template'        => get_option( 'etfrtb_confirmed_user_template', 'conversations.php' ),
        'lead'            => get_option( 'etfrtb_confirmed_user_headline', $rtb_controller->settings->get_setting( 'subject-confirmed-user' ) ),
        'footer_message'  => get_option( 'etfrtb_confirmed_user_footer_message', '' ),
      ),
      6 => array(
        'template'        => get_option( 'etfrtb_confirmed_user_template', 'conversations.php' ),
        'lead'            => get_option( 'etfrtb_confirmed_user_headline', $rtb_controller->settings->get_setting( 'subject-confirmed-user' ) ),
        'footer_message'  => get_option( 'etfrtb_confirmed_user_footer_message', '' ),
      ),
      7 => array(
        'template'        => get_option( 'etfrtb_rejected_user_template', 'conversations.php' ),
        'lead'            => get_option( 'etfrtb_rejected_user_headline', $rtb_controller->settings->get_setting( 'subject-rejected-user' ) ),
        'book_again'      => get_option( 'etfrtb_rejected_user_book_again', __( 'Book Another Time', 'email-templates-for-rtb' ) ),
        'footer_message'  => get_option( 'etfrtb_rejected_user_footer_message', '' ),
      ),
      8 => array(
        'template'        => get_option( 'etfrtb_reminder_user_template', 'conversations.php' ),
        'lead'            => get_option( 'etfrtb_reminder_user_headline', $rtb_controller->settings->get_setting( 'subject-reminder-user' ) ),
        'footer_message'  => get_option( 'etfrtb_reminder_user_footer_message', '' ),
      ),
      9 => array(
        'template'        => get_option( 'etfrtb_late_user_template', 'conversations.php' ),
        'lead'            => get_option( 'etfrtb_late_user_headline', $rtb_controller->settings->get_setting( 'subject-late-user' ) ),
        'footer_message'  => get_option( 'etfrtb_late_user_footer_message', '' ),
      ),
      10 => array(
        'template'        => 'conversations.php',
        'lead'            => $rtb_controller->settings->get_setting( 'subject-post-reservation-follow-up-user' ),
        'footer_message'  => '',
      ),
    );

    update_option( 'rtb-customizer-booking-settings', $etfrtb_settings );
  }

}
}