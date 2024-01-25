<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'fdmFSPPHSettings' ) ) {
class fdmFSPPHSettings {

  public function __construct() {

    add_filter( 'fdm_defaults', array( $this, 'fdm_settings_set_defaults' ), 10, 2 );

    add_filter( 'fdm_settings_page', array( $this, 'fdm_settings__non_premium_tabs' ), 10, 2 );

    add_filter( 'fdm_settings_page', array( $this, 'fdm_settings_advanced_tab' ), 10, 2 );
    add_filter( 'fdm_settings_page', array( $this, 'fdm_settings_ordering_tab' ), 10, 2 );
    add_filter( 'fdm_settings_page', array( $this, 'fdm_settings_custom_fields_tab' ), 10, 2 );
    add_filter( 'fdm_settings_page', array( $this, 'fdm_settings_labelling_tab' ), 10, 2 );
    add_filter( 'fdm_settings_page', array( $this, 'fdm_settings_styling_tab' ), 10, 2 );
  }

  public function get_permission( $permission_type = '' ) {
    global $fdm_controller;
  
    $fdm_premium_permissions = array();
  
    if ( ! $fdm_controller->permissions->check_permission( $permission_type ) ) {
      $fdm_premium_permissions = array(
        'disabled'    => true,
        'disabled_image'=> '#',
        'purchase_link' => 'https://www.fivestarplugins.com/plugins/five-star-restaurant-menu/?utm_source=fdm_lockbox'
      );
    }
  
    return $fdm_premium_permissions;
  }

  public function fdm_settings_set_defaults( $defaults, $fdmSettings ) {

    $defaults = array_merge(
      $defaults,
      array(
        // Any default which you are certain that won't be used for free version.
      )
    );
  
    return $defaults;
  }

  public function fdm_settings__non_premium_tabs( $sap, $fdmSettings ) {

    return $sap;
  }

  // "Premium" Tab
  public function fdm_settings_advanced_tab( $sap, $fdmSettings ) {
    
    // Create a section to enable/disable filtering features
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-filtering-settings',
          'title'     => __( 'Filtering', 'food-and-drink-menu' ),
          'description' => __( 'Choose what filtering, if any, of the menu items you wish to enable.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-advanced-tab'
        ),
        $this->get_permission( 'filtering' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-settings',
      'checkbox',
      array(
        'id'      => 'fdm-text-search',
        'title'     => __( 'Menu Item Search', 'food-and-drink-menu' ),
        'description' => __( 'Choose what menu items features, if any, should be searchable.', 'food-and-drink-menu' ),
        'options'   => array(
          'name'      => 'Name',
          'description'   => 'Description'
        )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-settings',
      'toggle',
      array(
        'id'      => 'fdm-enable-price-filtering',
        'title'     => __( 'Price Filtering', 'food-and-drink-menu' ),
        'description' => __( 'Allow visitors to search menu items in a specific price range. <strong>Please be aware that, since the additional price fields are just text fields, into which you can input whatever combination of text/numbers/etc. that you want, you need to make sure you have formatted your additional prices to have only one number in them, to ensure they display correctly in the price filter/slider.</strong>', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-settings',
      'select',
      array(
        'id'      => 'fdm-price-filtering-type',
        'title'     => __( 'Price Filtering Control', 'food-and-drink-menu' ),
        'description' => __( 'Choose the type of control available to visitors if price filtering is enabled.', 'food-and-drink-menu' ),
        'blank_option'  => false,
        'options'   => array(
          'textbox'   => 'Text Boxes',
          'slider'  => 'Slider'
        ),
        'conditional_on'    => 'fdm-enable-price-filtering',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-settings',
      'toggle',
      array(
        'id'      => 'fdm-enable-sorting',
        'title'     => __( 'Menu Item Sorting', 'food-and-drink-menu' ),
        'description' => __( 'Allow visitors to sort menu items by name, price, date added, etc. to find items they may be interested in.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-settings',
      'checkbox',
      array(
        'id'      => 'fdm-item-sorting',
        'title'     => __( 'Sortable Items', 'food-and-drink-menu' ),
        'description' => __( 'Choose what menu items features, if any, should be sortable.', 'food-and-drink-menu' ),
        'options'   => array(
          'name'      => 'Name',
          'price'     => 'Price',
          'date_added'  => 'Date Added'
        ),
        'conditional_on'    => 'fdm-enable-sorting',
        'conditional_on_value'  => true
      )
    );
    
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-advanced-enable-settings',
          'title'     => __( 'Functionality', 'food-and-drink-menu' ),
          'description' => __( 'Choose what features of the menu items you wish to enable or disable.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-advanced-tab'
        ),
        $this->get_permission( 'flags' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-enable-settings',
      'radio',
      array(
        'id'      => 'fdm-related-items',
        'title'     => __( 'Related Items', 'food-and-drink-menu' ),
        'description' => __( 'Should related items be displayed when viewing a particular item.', 'food-and-drink-menu' ),
        'options'   => array(
          'none'      => 'None',
          'automatic'   => 'Automatic',
          'manual'    => 'Manual'
        ),
        'default'   => $fdmSettings->defaults['fdm-related-items']
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-enable-settings',
      'toggle',
      array(
        'id'      => 'fdm-disable-menu-item-flags',
        'title'     => __( 'Disable Menu Item Flags', 'food-and-drink-menu-pro' ),
        'description' => __( 'Disable the flags which can be assigned to menu items.', 'food-and-drink-menu-pro' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-enable-settings',
      'toggle',
      array(
        'id'      => 'fdm-disable-specials',
        'title'     => __( 'Disable Menu Item Specials', 'food-and-drink-menu-pro' ),
        'description' => __( 'Disable the specials options for menu items.', 'food-and-drink-menu-pro' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-enable-settings',
      'toggle',
      array(
        'id'      => 'fdm-disable-price-discounted',
        'title'     => __( 'Disable Discounted Price', 'food-and-drink-menu-pro' ),
        'description' => __( 'Disable discounted pricing options for menu items.', 'food-and-drink-menu-pro' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-enable-settings',
      'toggle',
      array(
        'id'      => 'fdm-disable-src',
        'title'     => __( 'Disable Source', 'food-and-drink-menu-pro' ),
        'description' => __( 'Disable all source options in menus.', 'food-and-drink-menu-pro' )
      )
    );

    return $sap;
  }

  // "Ordering" Tab
  public function fdm_settings_ordering_tab( $sap, $fdmSettings ) {
    
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-basic-ordering-settings',
          'title'     => __( 'Basic', 'food-and-drink-menu' ),
          'description' => __( 'Enable and set up ordering', 'food-and-drink-menu' ),
          'tab'     => 'fdm-ordering-tab',
          'ultimate_needed' => true,
        ),
        $this->get_permission( 'ordering' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-basic-ordering-settings',
      'toggle',
      array(
        'id'      => 'fdm-enable-ordering',
        'title'     => __( 'Enable Ordering', 'food-and-drink-menu' ),
        'description' => __( 'Allow visitors to add menu items to a cart, which is then emailed to the site administrator.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-basic-ordering-settings',
      'toggle',
      array(
        'id'      => 'fdm-ordering-pause-ordering',
        'title'     => __( 'Pause All Ordering', 'food-and-drink-menu' ),
        'description' => __( 'Overrides all other settings. Allows you to turn off ordering temporarily for any reason.', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-basic-ordering-settings',
      'text',
      array(
        'id'      => 'fdm-ordering-minimum-order',
        'title'     => __( 'Minimum Order', 'food-and-drink-menu' ),
        'description' => __( 'What is the minimum amount (in dollars, euros, etc.) before an order can be sent? Leave blank for no minimum.', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-basic-ordering-settings',
      'text',
      array(
        'id'      => 'fdm-ordering-maximum-received-orders',
        'title'     => __( 'Maximum Received Orders', 'food-and-drink-menu' ),
        'description' => __( 'Set a maximum number of orders that have been received and not assigned a new status (e.g. preparing, etc.) before ordering is stopped, to prevent being overwhelmed by a sudden surge in orders. Leave blank for no maximum to be applied.', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-basic-ordering-settings',
      'text',
      array(
        'id'      => 'fdm-ordering-order-delete-time',
        'title'     => __( 'Delete Orders Delay Days', 'food-and-drink-menu' ),
        'description' => __( 'How many days after an order is created should it be deleted from the database?', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-basic-ordering-settings',
      'checkbox',
      array(
        'id'      => 'fdm-ordering-required-fields',
        'title'     => __( 'Required Fields', 'food-and-drink-menu' ),
        'description' => __( 'Choose which ordering information fields, if any, should be required.', 'food-and-drink-menu' ),
        'options'   => array(
          'name'      => 'Name',
          'phone'     => 'Phone',
          'email'     => 'Email'
        ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    // $sap->add_setting(
    //  'food-and-drink-menu-settings',
    //  'fdm-basic-ordering-settings',
    //  'text',
    //  array(
    //    'id'      => 'fdm-ordering-redirect-page',
    //    'title'     => __( 'Redirect Page', 'food-and-drink-menu' ),
    //    'description' => __( 'Specify the URL of a page you would like your customer to be redirected to after they place an order. (This is not mandatory. Not entering anything here will just leave it so that it stays on the menu page after an order is placed.)', 'food-and-drink-menu' )
    //  )
    // );

    // Translateable strings for scheduler components
    $scheduler_strings = array(
      'add_rule'      => __( 'Add new scheduling rule', 'food-and-drink-menu' ),
      'weekly'      => _x( 'Weekly', 'Format of a scheduling rule', 'food-and-drink-menu' ),
      'monthly'     => _x( 'Monthly', 'Format of a scheduling rule', 'food-and-drink-menu' ),
      'date'        => _x( 'Date', 'Format of a scheduling rule', 'food-and-drink-menu' ),
      'weekdays'      => _x( 'Days of the week', 'Label for selecting days of the week in a scheduling rule', 'food-and-drink-menu' ),
      'month_weeks'   => _x( 'Weeks of the month', 'Label for selecting weeks of the month in a scheduling rule', 'food-and-drink-menu' ),
      'date_label'    => _x( 'Date', 'Label to select a date for a scheduling rule', 'food-and-drink-menu' ),
      'time_label'    => _x( 'Time', 'Label to select a time slot for a scheduling rule', 'food-and-drink-menu' ),
      'allday'      => _x( 'All day', 'Label to set a scheduling rule to last all day', 'food-and-drink-menu' ),
      'start'       => _x( 'Start', 'Label for the starting time of a scheduling rule', 'food-and-drink-menu' ),
      'end'       => _x( 'End', 'Label for the ending time of a scheduling rule', 'food-and-drink-menu' ),
      'set_time_prompt' => _x( 'All day long. Want to %sset a time slot%s?', 'Prompt displayed when a scheduling rule is set without any time restrictions', 'food-and-drink-menu' ),
      'toggle'      => _x( 'Open and close this rule', 'Toggle a scheduling rule open and closed', 'food-and-drink-menu' ),
      'delete'      => _x( 'Delete rule', 'Delete a scheduling rule', 'food-and-drink-menu' ),
      'delete_schedule' => __( 'Delete scheduling rule', 'food-and-drink-menu' ),
      'never'       => _x( 'Never', 'Brief default description of a scheduling rule when no weekdays or weeks are included in the rule', 'food-and-drink-menu' ),
      'weekly_always'   => _x( 'Every day', 'Brief default description of a scheduling rule when all the weekdays/weeks are included in the rule', 'food-and-drink-menu' ),
      'monthly_weekdays'  => _x( '%s on the %s week of the month', 'Brief default description of a scheduling rule when some weekdays are included on only some weeks of the month. %s should be left alone and will be replaced by a comma-separated list of days and weeks in the following format: M, T, W on the first, second week of the month', 'food-and-drink-menu' ),
      'monthly_weeks'   => _x( '%s week of the month', 'Brief default description of a scheduling rule when some weeks of the month are included but all or no weekdays are selected. %s should be left alone and will be replaced by a comma-separated list of weeks in the following format: First, second week of the month', 'food-and-drink-menu' ),
      'all_day'     => _x( 'All day', 'Brief default description of a scheduling rule when no times are set', 'food-and-drink-menu' ),
      'before'      => _x( 'Ends at', 'Brief default description of a scheduling rule when an end time is set but no start time. If the end time is 6pm, it will read: Ends at 6pm', 'food-and-drink-menu' ),
      'after'       => _x( 'Starts at', 'Brief default description of a scheduling rule when a start time is set but no end time. If the start time is 6pm, it will read: Starts at 6pm', 'food-and-drink-menu' ),
      'separator'     => _x( '&mdash;', 'Separator between times of a scheduling rule', 'food-and-drink-menu' ),
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-basic-ordering-settings',
      'scheduler',
      array(
        'id'      => 'schedule-open',
        'title'     => __( 'Schedule', 'food-and-drink-menu' ),
        'description' => __( 'Define the weekly schedule times during which you accept orders.', 'food-and-drink-menu' ),
        'weekdays'    => array(
          'monday'    => _x( 'Mo', 'Monday abbreviation', 'food-and-drink-menu' ),
          'tuesday'   => _x( 'Tu', 'Tuesday abbreviation', 'food-and-drink-menu' ),
          'wednesday'   => _x( 'We', 'Wednesday abbreviation', 'food-and-drink-menu' ),
          'thursday'    => _x( 'Th', 'Thursday abbreviation', 'food-and-drink-menu' ),
          'friday'    => _x( 'Fr', 'Friday abbreviation', 'food-and-drink-menu' ),
          'saturday'    => _x( 'Sa', 'Saturday abbreviation', 'food-and-drink-menu' ),
          'sunday'    => _x( 'Su', 'Sunday abbreviation', 'food-and-drink-menu' )
        ),
        'time_format' => $fdmSettings->get_setting( 'time-format' ),
        'date_format' => $fdmSettings->get_setting( 'date-format' ),
        'disable_weeks' => true,
        'disable_date'  => true,
        'strings' => $scheduler_strings,
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    $scheduler_strings['all_day'] = _x( 'Closed all day', 'Brief default description of a scheduling exception when no times are set', 'food-and-drink-menu' );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-basic-ordering-settings',
      'scheduler',
      array(
        'id'        => 'schedule-closed',
        'title'       => __( 'Exceptions', 'food-and-drink-menu' ),
        'description'   => __( "Define special opening hours for holidays, events or other needs. Leave the time empty if you're closed all day.", 'food-and-drink-menu' ),
        'time_format'   => esc_attr( $fdmSettings->get_setting( 'time-format' ) ),
        'date_format'   => esc_attr( $fdmSettings->get_setting( 'date-format' ) ),
        'disable_weekdays'  => true,
        'disable_weeks'   => true,
        'strings' => $scheduler_strings,
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    // Create a section to handle premium ordering options
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-advanced-ordering-settings',
          'title'     => __( 'Advanced', 'food-and-drink-menu' ),
          'description' => __( 'Choose what advanced features should be enabled.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-ordering-tab',
          'ultimate_needed' => true,
        ),
        $this->get_permission( 'ordering' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-ordering-settings',
      'toggle',
      array(
        'id'      => 'fdm-enable-ordering-options',
        'title'     => __( 'Enable Advanced Ordering Options', 'food-and-drink-menu' ),
        'description' => __( 'Allow ordering options (ex. lettuce, tomato, cheese, bacon for a burger or toppings for a pizza) as well as notes for individual items.', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-ordering-settings',
      'toggle',
      array(
        'id'      => 'fdm-enable-ordering-progress-display',
        'title'     => __( 'Enable Order Progress Display', 'food-and-drink-menu' ),
        'description' => __( 'Display the status of a visitor\'s order on the menu page after they place an order. ', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-ordering-settings',
      'toggle',
      array(
        'id'      => 'fdm-enable-ordering-eta',
        'title'     => __( 'Enable Order ETA', 'food-and-drink-menu' ),
        'description' => __( 'Allow the admin to add an ETA to each order. When filled in, this will be displayed on the order page after checkout and can be used in notifications.', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-advanced-ordering-settings',
      'toggle',
      array(
        'id'      => 'fdm-ordering-additional-prices',
        'title'     => __( 'Include Additional Prices', 'food-and-drink-menu' ),
        'description' => __( 'Enabling this will include any additional prices you have set for a menu item in the ordering functionality. <strong>Please be aware that, since the additional price fields are just text fields, into which you can input whatever combination of text/numbers/etc. that you want, you need to make sure you have formatted your additional prices to have only one number in them and in such a way that they will make sense for your ordering cart.</strong>', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    // Create a section to handle ordering notifications
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-ordering-notifications-settings',
          'title'     => __( 'Notifications', 'food-and-drink-menu' ),
          'description' => __( 'Choose settings for the order notifications.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-ordering-tab',
          'ultimate_needed' => true,
        ),
        $this->get_permission( 'ordering' )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-notifications-settings',
      'warningtip',
      array(
        'id'      => 'fdm-notifications-reminder',
        'title'     => __( 'REMINDER:', 'food-and-drink-menu' ),
        'placeholder' => __( 'Five-Star Restaurant Menu uses the default WordPress mailing functions. If you\'d like to customize how your emails are sent, you can do so by editing your settings or using a plugin such as <a target="_blank" href="https://wordpress.org/plugins/ultimate-wp-mail/">Ultimate WP Mail</a>.' )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-notifications-settings',
      'text',
      array(
        'id'      => 'fdm-ordering-notification-email',
        'title'     => __( 'Order Email Address', 'food-and-drink-menu' ),
        'description' => __( 'What email address should orders be sent to?', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-notifications-settings',
      'text',
      array(
        'id'      => 'fdm-ordering-reply-to-name',
        'title'     => __( 'Reply-To Name', 'food-and-drink-menu' ),
        'description' => __( 'The name which should appear in the Reply-To field of a user notification email', 'food-and-drink-menu' ),
        'placeholder' => $fdmSettings->defaults['fdm-ordering-reply-to-name'],
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-notifications-settings',
      'text',
      array(
        'id'      => 'fdm-ordering-reply-to-address',
        'title'     => __( 'Reply-To Email Address', 'food-and-drink-menu' ),
        'description' => __( 'The email address which should appear in the Reply-To field of a user notification email.', 'food-and-drink-menu' ),
        'placeholder' => $fdmSettings->defaults['fdm-ordering-reply-to-address'],
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-notifications-settings',
      'text',
      array(
        'id'      => 'admin-sms-phone-number',
        'title'     => __( 'Admin SMS Phone Number', 'food-and-drink-menu' ),
        'description' => __( 'The phone number for the administrator, if any SMS notifications are being sent to them.', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-notifications-settings',
      'text',
      array(
        'id'      => 'ultimate-purchase-email',
        'title'     => __( '"Ultimate" Purchase Email', 'food-and-drink-menu' ),
        'description' => __( 'The email used to purchase the "Ultimate" license of the plugin, if using SMS notifications.', 'food-and-drink-menu' ),
        'default'   => $fdmSettings->defaults['ultimate-purchase-email'],
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );


    // Create a section with the notifications infinite table
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-ordering-notifications-table',
          'title'     => __( 'Notifications Table', 'food-and-drink-menu' ),
          'description' => __( 'Create notifications sent to either the customer or admin when an order\'s status changes.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-ordering-tab',
          'ultimate_needed' => true,
        ),
        $this->get_permission( 'ordering' )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-notifications-table',
      'html',
      array(
        'id'      => 'template-tags-description',
        'title'     => __( 'Template Tags', 'food-and-drink-menu' ),
        'html'      => '
          <p class="description">' . __( 'Use the following tags to automatically add order information to the emails and SMS messages. Tags with an * do not work for SMS messages.', 'food-and-drink-menu' ) . '</p>' .
          $this->render_template_tag_descriptions(),
      )
    );

    $statuses = fdm_get_order_statuses();

    $status_options = array();

    foreach ( $statuses as $status => $data ) {

      $status_options[ $status ] = $data['label'];
    }

    $fdm_credit_information = $this->get_sms_credit_information();

    $description = sprintf( __( 'Your ultimate license key is valid until %s, and you have a SMS credit balance of %s. As a reminder, each SMS message segment takes between 1 and 5 credits to send.', 'food-and-drink-menu' ), $fdm_credit_information['expiry'], $fdm_credit_information['balance'] );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-notifications-table',
      'infinite_table',
      array(
        'id'          => 'order-notifications',
        'title'       => __( 'Notifications', 'food-and-drink-menu' ),
        'add_label'   => __( '+ ADD', 'food-and-drink-menu' ),
        'del_label'   => __( 'Delete', 'food-and-drink-menu' ),
        'description' => $description,
        'fields'      => array(
          'enabled'    => array(
            'type'     => 'toggle',
            'label'    => 'Enabled',
            'options' => array(
              'true'     => __( '', 'food-and-drink-menu' ),
            ),
          ),
          'status'  => array(
            'type'    => 'select',
            'label'   => __( 'Status', 'food-and-drink-menu' ),
            'options' => $status_options,
          ),
          'type'    => array(
            'type'    => 'select',
            'label'   => __( 'Type', 'food-and-drink-menu' ),
            'options' => array(
              'email'    => __( 'Email', 'food-and-drink-menu' ),
              'sms'      => __( 'SMS', 'food-and-drink-menu' ),
            )
          ),
          'target'    => array(
            'type'    => 'select',
            'label'   => __( 'Target', 'food-and-drink-menu' ),
            'options' => array(
              'user'     => __( 'Customer', 'food-and-drink-menu' ),
              'admin'    => __( 'Admin', 'food-and-drink-menu' ),
            )
          ),
          'subject' => array(
            'type'     => 'text',
            'label'    => __( 'Subject', 'food-and-drink-menu' ),
          ),
          'message' => array(
            'type'     => 'editor',
            'label'    => __( 'Message', 'food-and-drink-menu' ),
          )
        )
      )
    );

    // Create a section to handle ordering payments
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-ordering-payments-settings',
          'title'     => __( 'Payment', 'food-and-drink-menu' ),
          'description' => __( 'Settings for handling order payments.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-ordering-tab',
          'ultimate_needed' => true,
        ),
        $this->get_permission( 'ordering' )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'toggle',
      array(
        'id'      => 'enable-payment',
        'title'     => __( 'Enable Payment', 'food-and-drink-menu' ),
        'description'     => __( 'Let customers pay for their order when they submit it online.', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-enable-ordering',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'toggle',
      array(
        'id'      => 'payment-optional',
        'title'     => __( 'Pay-In-Store Option', 'food-and-drink-menu' ),
        'description'     => __( 'Give customers the option of paying for their order in person.', 'food-and-drink-menu' ),
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'select',
      array(
        'id'            => 'ordering-currency',
        'title'         => __( 'Currency', 'food-and-drink-menu' ),
        'description'   => __( 'Select the currency that your menu is listed in (and you use for your payments, if enabled).', 'food-and-drink-menu' ),
        'blank_option'  => false,
        'options'       => $fdmSettings->currency_options
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'text',
      array(
        'id'            => 'ordering-tax-rate',
        'title'         => __( 'Tax Rate', 'food-and-drink-menu' ),
        'description'   => __( 'The tax rate that should apply to online orders, as a percentage. For example, enter 15 to add 15% tax to each order.', 'food-and-drink-menu' ),
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'radio',
      array(
        'id'      => 'ordering-payment-gateway',
        'title'     => __( 'Payment Gateway', 'food-and-drink-menu' ),
        'description' => __( 'Which payment gateway should be used to accept payments.', 'food-and-drink-menu' ),
        'options'   => array(
          'paypal'    => 'PayPal',
          'stripe'    => 'Stripe'
        ),
        'default'   => $fdmSettings->defaults['ordering-payment-gateway'],
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'toggle',
      array(
        'id'      => 'fdm-stripe-sca',
        'title'     => __( 'Strong Customer Authorization (SCA)', 'food-and-drink-menu' ),
        'description'     => __( 'User will be redirected to Stripe and presented with 3D secure or bank redirect if necessary for payment authentication. (May be necessary for EU compliance.)', 'food-and-drink-menu' ),
        'conditional_on'    => 'ordering-payment-gateway',
        'conditional_on_value'  => 'stripe'
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'toggle',
      array(
        'id'                    => 'fdm-stripe-hold',
        'title'                 => __( 'Hold & Charge Separately', 'food-and-drink-menu' ),
        'description'           => __( 'With this enabled, the deposit will be taken as a hold and not charged right away. The payment can then be charged/captured manually later. If not captured, the hold on the amount will be released after 7 days. <em>SCA (option above this one) must be enabled to use this hold feature.</em>', 'food-and-drink-menu' ),
        'conditional_on'        => 'fdm-stripe-sca',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'radio',
      array(
        'id'      => 'ordering-payment-mode',
        'title'     => __( 'Test/Live Mode', 'food-and-drink-menu' ),
        'description' => __( 'Should the system use test or live mode? Test mode should only be used for testing, no payments will actually be processed while turned on.', 'food-and-drink-menu' ),
        'options'   => array(
          'test'      => 'Test',
          'live'      => 'Live'
        ),
        'default'   => $fdmSettings->defaults['ordering-payment-mode'],
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'text',
      array(
        'id'            => 'paypal-email',
        'title'         => __( 'PayPal Email Address', 'food-and-drink-menu' ),
        'description'   => __( 'The email address you\'ll be using to accept payments, if you\'re using Paypal for payments.', 'food-and-drink-menu' ),
        'placeholder' => $fdmSettings->defaults['paypal-email'],
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'text',
      array(
        'id'            => 'stripe-live-secret',
        'title'         => __( 'Stripe Live Secret', 'food-and-drink-menu' ),
        'description'   => __( 'The live secret that you have set up for your Stripe account, if you\'re using Stripe for payments.', 'food-and-drink-menu' ),
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'text',
      array(
        'id'            => 'stripe-live-publishable',
        'title'         => __( 'Stripe Live Publishable', 'food-and-drink-menu' ),
        'description'   => __( 'The live publishable that you have set up for your Stripe account, if you\'re using Stripe for payments.', 'food-and-drink-menu' ),
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'text',
      array(
        'id'            => 'stripe-test-secret',
        'title'         => __( 'Stripe Test Secret', 'food-and-drink-menu' ),
        'description'   => __( 'The test secret that you have set up for your Stripe account, if you\'re using Stripe for payments. Only needed for testing payments.', 'food-and-drink-menu' ),
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-payments-settings',
      'text',
      array(
        'id'            => 'stripe-test-publishable',
        'title'         => __( 'Stripe Test Publishable', 'food-and-drink-menu' ),
        'description'   => __( 'The test publishable that you have set up for your Stripe account, if you\'re using Stripe for payments. Only needed for testing payments.', 'food-and-drink-menu' ),
        'conditional_on'    => 'enable-payment',
        'conditional_on_value'  => true
      )
    );

    return $sap;
  }

  // "Custom Fields" Tab
  public function fdm_settings_custom_fields_tab( $sap, $fdmSettings ) {
    
    // Create a section for the custom fields
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-custom-fields-settings-section',
          'title'     => __( 'Custom Fields', 'food-and-drink-menu' ),
          'description' => __( 'Create custom fields for your menu.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-custom-fields-tab',
        ),
        $this->get_permission( 'custom_fields' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-custom-fields-settings-section',
      'toggle',
      array(
        'id'      => 'hide-blank-custom-fields',
        'title'     => __( 'Hide Blank Custom Fields', 'food-and-drink-menu' ),
        'description'     => __( 'Hide custom fields that don\'t have a value.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-custom-fields-settings-section',
      'infinite_table',
      array(
        'id'      => 'fdm-custom-fields',
        'title'     => __( 'Custom Fields', 'food-and-drink-menu' ),
        'add_label'   => __( 'Add Field', 'food-and-drink-menu' ),
        'del_label'   => __( 'Delete', 'food-and-drink-menu' ),
        'description' => __( 'Use this table to create custom fields that can be filled in for each menu item. This information is displayed when a specific item is viewed, either on it\'s own page or via the lightbox if you have that option enabled.<br /> Looking to add nutritional information? <span class="fdm-custom-fields-add-nutrional-information">Add it in one click</span>.', 'food-and-drink-menu' ),
        'fields'    => array(
          'name' => array(
            'type'    => 'text',
            'label'   => 'Name',
            'required'  => true
          ),
          'slug' => array(
            'type'    => 'text',
            'label'   => 'Slug',
            'required'  => true
          ),
          'type' => array(
            'type'    => 'select',
            'label'   => 'Type',
            'required'  => true,
            'options'   => array(
              'section'   => 'Section',
              'text'    => 'Short Text',
              'textarea'  => 'Long Text',
              'select'  => 'Dropdown',
              'checkbox'  => 'Checkboxes'
            )
          ),
          'applicable' => array(
            'type'    => 'select',
            'label'   => 'Applicable To',
            'required'  => true,
            'options'   => array(
              'menu_item' => 'Menu Item',
              'order'   => 'Order'
            )
          ),
          'values' => array(
            'type'    => 'text',
            'label'   => 'Values',
            'required'  => false
          )
        )
      )
    );

    return $sap;
  }

  // "Labelling" Tab
  public function fdm_settings_labelling_tab( $sap, $fdmSettings ) {
    
    $sap->add_section(
      'food-and-drink-menu-settings',
      array_merge(
        array(
          'id'            => 'fdm-menu-labelling',
          'title'         => __( 'Menu', 'food-and-drink-menu' ),
          'tab'           => 'fdm-labelling-tab'
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-menu-labelling',
      'text',
      array(
        'id'          => 'label-custom-fields',
        'title'       => __( 'Custom Fields', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-menu-labelling',
      'text',
      array(
        'id'          => 'label-related-items',
        'title'       => __( 'Related Items', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-menu-labelling',
      'text',
      array(
        'id'          => 'label-on-sale',
        'title'       => __( 'On Sale', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-menu-labelling',
      'text',
      array(
        'id'          => 'label-special-offer',
        'title'       => __( 'Special Offer', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-menu-labelling',
      'text',
      array(
        'id'          => 'label-featured',
        'title'       => __( 'Featured', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-menu-labelling',
      'text',
      array(
        'id'          => 'label-sold-out',
        'title'       => __( 'Sold Out', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-menu-labelling',
      'text',
      array(
        'id'          => 'label-sidebar-expand-button',
        'title'       => __( '"View Sections" Button (for sidebar on mobile)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_section(
      'food-and-drink-menu-settings',
      array_merge(
        array(
          'id'            => 'fdm-filtering-labelling',
          'title'         => __( 'Filtering', 'food-and-drink-menu' ),
          'tab'           => 'fdm-labelling-tab'
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-filtering',
        'title'       => __( 'Filtering', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-search',
        'title'       => __( 'Search', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-search-items',
        'title'       => __( 'Search Items...', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-filtering-price',
        'title'       => __( 'Price', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-sorting',
        'title'       => __( 'Sorting', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-name-asc',
        'title'       => __( 'Name (A -> Z)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-name-desc',
        'title'       => __( 'Name (Z -> A)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-price-asc',
        'title'       => __( 'Price (Ascending)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-price-desc',
        'title'       => __( 'Price (Descending)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-date-added-asc',
        'title'       => __( 'Date Added (Ascending)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-date-added-desc',
        'title'       => __( 'Date Added (Descending)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-section-asc',
        'title'       => __( 'Section (Ascending)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-filtering-labelling',
      'text',
      array(
        'id'          => 'label-section-desc',
        'title'       => __( 'Section (Descending)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_section(
      'food-and-drink-menu-settings',
      array_merge(
        array(
          'id'            => 'fdm-ordering-labelling',
          'title'         => __( 'Ordering', 'food-and-drink-menu' ),
          'tab'           => 'fdm-labelling-tab'
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-add-to-cart',
        'title'       => __( 'Add to Cart', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-discount',
        'title'       => __( 'Discount:', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-remove',
        'title'       => __( 'Remove', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-ordering-price',
        'title'       => __( 'Price:', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-order-item-details',
        'title'       => __( 'Order Item Details', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-item-note',
        'title'       => __( 'Item Note', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-confirm-details',
        'title'       => __( 'Confirm Details', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-order-progress',
        'title'       => __( 'Order Progress', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-order-summary',
        'title'       => __( 'Order Summary', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-your-order',
        'title'       => __( 'Your Order', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-item-in-cart',
        'title'       => __( 'Item in Your Cart', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-items-in-cart',
        'title'       => __( 'Items in Your Cart', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-item-s-in-cart',
        'title'       => __( 'Item(s) in Your Cart', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-quantity',
        'title'       => __( 'Quantity', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-clear',
        'title'       => __( 'Clear', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-tax',
        'title'       => __( 'Tax', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-total',
        'title'       => __( 'Total', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-check-out',
        'title'       => __( 'Check Out', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-name',
        'title'       => __( 'Name', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-email',
        'title'       => __( 'Email', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-phone',
        'title'       => __( 'Phone', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-order-note',
        'title'       => __( 'Order Note', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-pay-in-store',
        'title'       => __( 'Pay in Store', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-pay-online',
        'title'       => __( 'Pay Online', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-submit-order',
        'title'       => __( 'Submit Order', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-add-another-item',
        'title'       => __( '+ Add another item', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-pay-via-paypal',
        'title'       => __( 'Pay via PayPal', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-card-number',
        'title'       => __( 'Card Number', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-cvc',
        'title'       => __( 'CVC', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-expiration',
        'title'       => __( 'Expiration (MM/YYYY)', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-pay-now',
        'title'       => __( 'Pay Now', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-order-failed',
        'title'       => __( 'Order not successfully created', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-order-success',
        'title'       => __( 'Order was successfully created', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-order-payment-success',
        'title'       => __( 'You have successfully made a payment of %s', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-ordering-labelling',
      'text',
      array(
        'id'          => 'label-order-payment-failed',
        'title'       => __( 'Your payment was declined with the following error code %s', 'food-and-drink-menu' ),
        'description' => ''
      )
    );

    return $sap;
  }

  // "Styling" Tab
  public function fdm_settings_styling_tab( $sap, $fdmSettings ) {
    global $fdm_controller;
    
    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-styling-settings',
          'title'     => __( 'Menu', 'food-and-drink-menu' ),
          'description' => __( 'Styling options for the menu.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-styling-tab'
        ),
        $this->get_permission( 'styling' )
      )
    );

    $style_options = array();
    
    foreach( $fdm_controller->prostyles as $prostyle ) {

      $style_options[ $prostyle->id ] = $prostyle->label;
    }

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'select',
      array(
        'id'      => 'fdm-pro-style',
        'title'     => __( 'Menu Style', 'food-and-drink-menu' ),
        'description' => __( 'Choose the style for your menus.', 'food-and-drink-menu' ),
        'blank_option'  => false,
        'options'   => $style_options
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'radio',
      array(
        'id'      => 'fdm-image-style-columns',
        'title'     => __( 'Image Style Columns', 'food-and-drink-menu' ),
        'description' => __( 'Choose how many columns you want to display in the Image Style layout.', 'food-and-drink-menu' ),
        'options'   => array(
          'three'   => '3',
          'four'    => '4',
          'five'    => '5'
        ),
        'default'   => $fdmSettings->defaults['fdm-image-style-columns'],
        'conditional_on'    => 'fdm-pro-style',
        'conditional_on_value'  => 'image'
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'radio',
      array(
        'id'          => 'fdm-refined-style-columns',
        'title'       => __( 'Refined Style Columns', 'food-and-drink-menu' ),
        'description' => __( 'Choose how many columns you want to display when using the Refined style.', 'food-and-drink-menu' ),
        'default'     => $fdmSettings->defaults['fdm-refined-style-columns'],
        'options'     => array(
          'one' => '1',
          'two' => '2'
        ),
        'conditional_on'        => 'fdm-pro-style',
        'conditional_on_value'  => 'refined'
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'radio',
      array(
        'id'          => 'fdm-ordering-style-columns',
        'title'       => __( 'Ordering Style Columns', 'food-and-drink-menu' ),
        'description' => __( 'Choose how many columns you want to display when using the Ordering style. Note that this will apply to larger/desktop screens. For mobile/smaller screens, the responsive layout may default to fewer columns.', 'food-and-drink-menu' ),
        'default'     => $fdmSettings->defaults['fdm-ordering-style-columns'],
        'options'     => array(
          'three' => '3',
          'four' => '4',
          'five' => '5'
        ),
        'conditional_on'        => 'fdm-pro-style',
        'conditional_on_value'  => 'ordering'
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'          => 'fdm-ordering-style-accent-color',
        'title'       => __( 'Ordering Style Accent Color', 'food-and-drink-menu' ),
        'description' => __( 'This sets the accent color when the Ordering style is enabled. Applies to elements such as the "add to cart" buttons and several elements in the cart pane.', 'food-and-drink-menu' ),
        'conditional_on'        => 'fdm-pro-style',
        'conditional_on_value'  => 'ordering'
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'          => 'fdm-ordering-style-accent-hover-color',
        'title'       => __( 'Ordering Style Accent Hover Color', 'food-and-drink-menu' ),
        'description' => __( 'This sets the accent color for hover elements when the Ordering style is enabled. Applies to elements such as the "add to cart" buttons and several elements in the cart pane.', 'food-and-drink-menu' ),
        'conditional_on'        => 'fdm-pro-style',
        'conditional_on_value'  => 'ordering'
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'select',
      array(
        'id'      => 'fdm-item-flag-icon-size',
        'title'     => __( 'Menu Item Flag Icon Size', 'food-and-drink-menu' ),
        'description' => __( 'The size in pixels of menu item flag icons (if enabled).', 'food-and-drink-menu' ),
        'options'   => array(
          '32' => __( '32x32 (default)', 'food-and-drink-menu' ),
          '64' => __( '64x64', 'food-and-drink-menu' )
        )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-section-title-font-family',
        'title'     => __( 'Section Title Font Family', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font family for the section titles. (Please note that the font family must already be loaded on the site. This does not load it.)', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-section-title-font-size',
        'title'     => __( 'Section Title Font Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font size for the section titles. Include the unit (e.g. 20px or 2em).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-section-title-color',
        'title'     => __( 'Section Title Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the section titles', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-item-name-font-family',
        'title'     => __( 'Item Name Font Family', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font family for the names of the menu items. (Please note that the font family must already be loaded on the site. This does not load it.)', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-item-name-font-size',
        'title'     => __( 'Item Name Font Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font size for the names of the menu items. Include the unit (e.g. 18px or 1.3em).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-item-name-color',
        'title'     => __( 'Item Name Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the names of the menu items', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-item-description-font-family',
        'title'     => __( 'Item Description Font Family', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font family for the descriptions of the menu items. (Please note that the font family must already be loaded on the site. This does not load it.)', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-item-description-font-size',
        'title'     => __( 'Item Description Font Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font size for the descriptions of the menu items. Include the unit (e.g. 12px or 1em).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-item-description-color',
        'title'     => __( 'Item Description Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the descriptions of the menu items', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-item-price-font-size',
        'title'     => __( 'Item Price Font Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font size for the prices of the menu items. Include the unit (e.g. 12px or 1em).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-item-price-color',
        'title'     => __( 'Item Price Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the prices of the menu items', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-image-width',
        'title'     => __( 'Item Image Width', 'food-and-drink-menu' ),
        'description' => __( 'Choose the width of the menu item images. Include the unit (e.g. 20% or 200px). Default is 33%.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-image-border-size',
        'title'     => __( 'Item Image Border Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the size of the border around menu item images. It is automatically in pixels, so no need to set the unit (e.g. just put 1 or 3, etc.).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-image-border-color',
        'title'     => __( 'Item Image Border Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the border around the menu item images', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-separating-line-size',
        'title'     => __( 'Separating Line Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the size of the line that separates different menu sections. It is automatically in pixels, so no need to set the unit (e.g. just put 1 or 3, etc.).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-separating-line-color',
        'title'     => __( 'Separating Line Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the line that separates different menu sections', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-filtering-font-family',
        'title'     => __( 'Filtering Font Family', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font family for the filtering area. (Please note that the font family must already be loaded on the site. This does not load it.)', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-filtering-title-font-size',
        'title'     => __( 'Filtering Title Font Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font size for the filtering area title. Include the unit (e.g. 20px or 2em).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-filtering-title-color',
        'title'     => __( 'Filtering Title Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the filtering area title', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-filtering-labels-font-size',
        'title'     => __( 'Filtering Labels Font Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font size for the filtering area labels. Include the unit (e.g. 14px or 1.2em).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-filtering-labels-color',
        'title'     => __( 'Filtering Labels Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the filtering area labels', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-sidebar-font-family',
        'title'     => __( 'Sidebar Font Family', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font family for the menu sidebar. (Please note that the font family must already be loaded on the site. This does not load it.)', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-sidebar-title-font-size',
        'title'     => __( 'Sidebar Titles Font Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font size for the section titles in the menu sidebar. Include the unit (e.g. 20px or 2em).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-sidebar-title-color',
        'title'     => __( 'Sidebar Titles Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the section titles in the menu sidebar', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'text',
      array(
        'id'      => 'fdm-styling-sidebar-description-font-size',
        'title'     => __( 'Sidebar Descriptions Font Size', 'food-and-drink-menu' ),
        'description' => __( 'Choose the font size for the section descriptions in the menu sidebar. Include the unit (e.g. 14px or 1em).', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-sidebar-description-color',
        'title'     => __( 'Sidebar Descriptions Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the section descriptions in the menu sidebar', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-sidebar-expand-button-background-color',
        'title'     => __( 'Sidebar Expand Button Background Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the background color for the sidebar expand button.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-sidebar-expand-button-text-color',
        'title'     => __( 'Sidebar Expand Button Text Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the text color for the sidebar expand button.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-sidebar-expand-button-hover-background-color',
        'title'     => __( 'Sidebar Expand Button Hover Background Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the background color for the sidebar expand button on hover.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-sidebar-expand-button-hover-text-color',
        'title'     => __( 'Sidebar Expand Button Hover Text Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the text color for the sidebar expand button on hover.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-item-icon-color',
        'title'     => __( 'Item Icon Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the color for the item icons', 'food-and-drink-menu' )
      )
    );

    $sap->add_section(
      'food-and-drink-menu-settings', // Page to add this section to
      array_merge( 
        array(                // Array of key/value pairs matching the AdminPageSection class constructor variables
          'id'      => 'fdm-styling-ordering-settings',
          'title'     => __( 'Ordering', 'food-and-drink-menu' ),
          'description' => __( 'Styling options for food ordering features.', 'food-and-drink-menu' ),
          'tab'     => 'fdm-styling-tab',
          'ultimate_needed' => true
        ),
        $this->get_permission( 'ordering' )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'radio',
      array(
        'id'          => 'fdm-order-cart-location',
        'title'       => __( 'Order Cart Location', 'food-and-drink-menu' ),
        'description' => __( 'Where should the ordering cart be located?', 'food-and-drink-menu' ),
        'default'     => $fdmSettings->defaults['fdm-order-cart-location'],
        'options'     => array(
          'side' => 'Side',
          'bottom' => 'Bottom'
        )
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'radio',
      array(
        'id'          => 'fdm-order-cart-style',
        'title'       => __( 'Order Cart Style', 'food-and-drink-menu' ),
        'description' => __( 'Which style should be used for the ordering cart? (This setting only applies to the bottom cart location.)', 'food-and-drink-menu' ),
        'default'     => $fdmSettings->defaults['fdm-order-cart-style'],
        'options'     => array(
          'default' => 'Default',
          'alt' => 'Alternate'
        ),
        'conditional_on'        => 'fdm-order-cart-location',
        'conditional_on_value'  => 'bottom'
      )
    );

    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-add-to-cart-background-color',
        'title'     => __( 'Add to Cart Button Background Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the background color for the add-to-cart button.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-add-to-cart-background-hover-color',
        'title'     => __( 'Add to Cart Button Background Hover Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the background color for the add-to-cart button. (Only applicable for the Ordering menu style.)', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-pro-style',
        'conditional_on_value'  => 'ordering'
     )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-add-to-cart-text-non-hover-color',
        'title'     => __( 'Add to Cart Button Text Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the text color for the add-to-cart button when you hover over it. (Only applicable for the Ordering menu style.)', 'food-and-drink-menu' ),
        'conditional_on'    => 'fdm-pro-style',
        'conditional_on_value'  => 'ordering'
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-add-to-cart-text-color',
        'title'     => __( 'Add to Cart Button Text Hover Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the text color for the add-to-cart button when you hover over it.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-shopping-cart-accent-color',
        'title'     => __( 'Shopping Cart Accent Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose an accent color for the shopping cart pane, when using the side order cart location. This will apply to elements like the heading and the clear button.', 'food-and-drink-menu' ),
        'conditional_on'        => 'fdm-order-cart-location',
        'conditional_on_value'  => 'side'
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-order-progress-color',
        'title'     => __( 'Order Progress Bar Fill Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the fill color for the order progress bar.', 'food-and-drink-menu' )
      )
    );
    $sap->add_setting(
      'food-and-drink-menu-settings',
      'fdm-styling-ordering-settings',
      'colorpicker',
      array(
        'id'      => 'fdm-styling-order-progress-border-color',
        'title'     => __( 'Order Progress Bar Border Color', 'food-and-drink-menu' ),
        'description' => __( 'Choose the border color for the order progress bar.', 'food-and-drink-menu' )
      )
    );

    return $sap;
  }

  /**
   * Render HTML code of descriptions for the template tags
   * @since 0.18
   */
  public function render_template_tag_descriptions() {

    $descriptions = apply_filters( 'fdm_notification_template_tag_descriptions', array(
        '{site_name}'       => __( 'The name/title of your site, as specific in your WordPress General settings', 'food-and-drink-menu' ),
        '{order_number}'    => __( 'The ID of the order', 'food-and-drink-menu' ),
        '{name}'            => __( 'Name of the user who placed the order', 'food-and-drink-menu' ),
        '{email}'           => __( 'Email if supplied with the order', 'food-and-drink-menu' ),
        '{phone}'           => __( 'Phone number if supplied with the order', 'food-and-drink-menu' ),
        '{note}'            => __( 'The customer’s note, if they left one', 'food-and-drink-menu' ),
        '{eta}'             => __( 'The estimated time the customer\'s order will be ready, if estimated timesa are enabled', 'food-and-drink-menu' ),
        '{custom_fields}'   => __( 'The custom fields submitted with an order, as label/value pairs', 'food-and-drink-menu' ),
        '{payment_amount}'  => __( 'The total payment amount, if payments are enabled', 'food-and-drink-menu' ),
        '{order_items}'     => __( '* A list of the items ordered by the customer', 'food-and-drink-menu' ),
        '{site_link}'       => __( 'A link back to your site', 'food-and-drink-menu' ),
        '{accept_link}'     => __( 'A link to your admin area to accept this order', 'food-and-drink-menu' ),
        '{current_time}'    => __( 'The time the order was placed', 'food-and-drink-menu' ),
      )
    );

    $output = '';

    foreach ( $descriptions as $tag => $description ) {
      $output .= '
        <div class="fdm-template-tags-box">
          <strong>' . $tag . '</strong> ' . $description . '
        </div>';
    }

    return $output;
  }

  /**
   * Render HTML code of descriptions for the template tags
   * @since 0.18
   */
  public function get_sms_credit_information() {
    global $fsp_premium_helper;
    global $fdm_controller;

    if ( ! get_transient( 'fdm-credit-information' ) ) { 

      // Set the transient to blank, to prevent 100's of requests at once if our site is down
      $transient = array(
        'expiry'  => time() + 3600 * 4,
        'balance' => 'Unknown',
      );

      set_transient( 'fdm-credit-information', $transient, 3600 * 3 );

      $args = array(
        'license_key'     => get_option( 'fdm-ultimate-license-key' ),
        'purchase_email'  => $fdm_controller->settings->get_setting( 'ultimate-purchase-email' ),
        'plugin'          => 'fdm',
      );

      $credit_information = $fsp_premium_helper->updates->retrieve_sms_credit_information( $args );

      $transient = array(
        'expiry'  => $credit_information->success ? $credit_information->expiry : __( 'No Ultimate license found', 'food-and-drink-menu' ),
        'balance' => $credit_information->success ? $credit_information->balance : 0,
      );

      set_transient( 'fdm-credit-information', $transient, 3600 * 24 * 7 );
    }

    $args = array();

    return get_transient( 'fdm-credit-information' );
  }

}
}