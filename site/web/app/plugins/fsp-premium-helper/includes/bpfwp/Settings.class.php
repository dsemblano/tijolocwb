<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'bpfwpFSPPHSettings' ) ) {
class bpfwpFSPPHSettings {

  public function __construct() {

    add_filter( 'bpfwp_defaults', array( $this, 'bpfwp_settings_set_defaults' ), 10, 2 );

    add_filter( 'bpfwp_settings_page', array( $this, 'bpfwp_settings__non_premium_tabs' ), 10, 2 );

    add_filter( 'bpfwp_settings_page', array( $this, 'bpfwp_settings_premium_tab' ), 10, 2 );

    add_filter( 'bpfwp_settings_page', array( $this, 'bpfwp_settings_labelling_tab' ), 10, 2 );

    add_filter( 'bpfwp_settings_page', array( $this, 'bpfwp_settings_styling_tab' ), 10, 2 );
  }

  public function get_permission( $permission_type = '' ) {
    global $bpfwp_controller;
  
    $bpfwp_premium_permissions = array();
  
    if ( ! $bpfwp_controller->permissions->check_permission( $permission_type ) ) {
      $bpfwp_premium_permissions = array(
        'disabled'    => true,
        'disabled_image'=> '#',
        'purchase_link' => 'https://www.fivestarplugins.com/plugins/five-star-business-profile/?utm_source=bpfwp_lockbox'
      );
    }
  
    return $bpfwp_premium_permissions;
  }

  public function bpfwp_settings_set_defaults( $defaults, $bpfwpSettings ) {

    $defaults = array_merge(
      $defaults,
      array(
        // Any default which you are certain that won't be used for free version.
      )
    );
  
    return $defaults;
  }

  public function bpfwp_settings__non_premium_tabs( $sap, $bpfwpSettings ) {

    return $sap;
  }

  // "Premium" Tab
  public function bpfwp_settings_premium_tab( $sap, $bpfwpSettings ) {

      $sap->add_section(
        'bpfwp-settings',
        array_merge(
          array(
            'id'    => 'bpfwp-contact-card-elements-order',
            'title' => __( 'Contact Elements Order', 'business-profile' ),
            'tab'   => 'bpfwp-premium-tab',
          ),
          $this->get_permission( 'premium' )
        )
      );
  
      $sap->add_setting(
        'bpfwp-settings',
        'bpfwp-contact-card-elements-order',
        'ordering-table',
        array(
          'id'          => 'contact-card-elements-order',
          'title'       => __( 'Contact Elements Order', 'business-profile' ),
          'description' => __( 'You can use this table to drag-and-drop the elements of the contact card into a different order.', 'business-profile' ), 
          'items'       => $bpfwpSettings->get_setting( 'contact-card-elements-order' )
        )
      );

      $sap->add_section(
        'bpfwp-settings',
        array_merge(
          array(
            'id'    => 'bpfwp-contact-card-custom-fields',
            'title' => __( 'Custom Fields', 'business-profile' ),
            'tab'   => 'bpfwp-premium-tab',
          ),
          $this->get_permission( 'premium' )
        )
      );
  
      $fields_description = ''
        . __( 'Should any extra fields be added to the contact card?', 'business-profile' ). '<br />'
        . __( 'The "Field Values" should be a comma-separated list of values for the select, radio or checkbox field types (no extra spaces after the comma).', 'business-profile' );
  
    // an option in a section
    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-contact-card-custom-fields',
      'infinite_table',
      array(
        'id'          => 'custom-fields',
        'title'       => __( 'Custom Fields', 'business-profile' ),
        'add_label'   => __( '+ ADD', 'business-profile' ),
        'del_label'   => __( 'Delete', 'business-profile' ),
        'description' => $fields_description,
        'fields'      => array(
          'id'      => array(
            'type'     => 'hidden',
            'label'    => 'Field ID',
            'required' => true
          ),
          'name'    => array(
            'type'     => 'text',
            'label'    => 'Field Name',
            'required' => true
          ),
          'type'    => array(
            'type'    => 'select',
            'label'   => __( 'Field Type', 'business-profile' ),
            'options' => array(
              'text'     => __( 'Text', 'business-profile' ),
              'textarea' => __( 'Text Area', 'business-profile' ),
              'select'   => __( 'Select Box', 'business-profile' ),
              'radio'    => __( 'Radio Buttons', 'business-profile' ),
              'checkbox' => __( 'Checkbox', 'business-profile' ),
              'file'     => __( 'File', 'business-profile' ),
              'link'     => __( 'Link', 'business-profile' ),
              'date'     => __( 'Date', 'business-profile' ),
              'datetime' => __( 'Date/Time', 'business-profile' ),
            )
          ),
          'options' => array(
            'type'     => 'text',
            'label'    => __( 'Field Values', 'business-profile' ),
            'required' => false
          )
        )
      )
    );

      $sap->add_section(
        'bpfwp-settings',
        array_merge(
          array(
            'id'            => 'bpfwp-premium-general',
            'title'         => __( 'General', 'business-profile' ),
            'tab'           => 'bpfwp-premium-tab'
          ),
          $this->get_permission( 'premium' )
        )
      );

      $sap->add_setting(
        'bpfwp-settings',
        'bpfwp-premium-general',
        'toggle',
        array(
          'id'      => 'article-rich-snippets',
          'title'     => __( 'Post Rich Snippets', 'business-profile' ),
          'description' => __( 'Automatically enable article \'Rich Snippets\' for Google for all regular posts on the site.', 'business-profile' ),
          'args'      => array(
            'label_for' => 'bpfwp-settings[article-rich-snippets]',
            'class'   => 'bpfwp-article-rich-snippets'
          )

        )
      );

      $sap->add_setting(
        'bpfwp-settings',
        'bpfwp-premium-general',
        'toggle',
        array(
          'id'      => 'schema-default-helpers',
          'title'     => __( 'Schema Default Helpers', 'business-profile' ),
          'description' => __( 'Adds a helper pop-up that can be accessed on click on the Schema edit screen that list the available default options, functions and metas', 'business-profile' ),
          'args'      => array(
            'label_for' => 'bpfwp-settings[schema-default-helpers]',
            'class'   => 'bpfwp-schema-default-helpers'
          )

        )
      );

      $sap->add_section(
        'bpfwp-settings',
        array_merge(
          array(
            'id'            => 'bpfwp-integrations',
            'title'         => __( 'Plugin Integrations', 'business-profile' ),
            'tab'          => 'bpfwp-premium-tab'
          ),
          $this->get_permission( 'integrations' )
        )
      );

      $sap->add_setting(
        'bpfwp-settings',
        'bpfwp-integrations',
        'toggle',
        array(
          'id'      => 'woocommerce-integration',
          'title'     => __( 'WooCommerce Integration', 'business-profile' ),
          'description' => __( 'Automatically enable product \'Rich Snippets\' for Google.', 'business-profile' ),
          'args'      => array(
            'label_for' => 'bpfwp-settings[woocommerce-integration]',
            'class'   => 'bpfwp-woocommerce-integration'
          )

        )
      );
      
    return $sap;
  }

  // "Labelling" Tab
  public function bpfwp_settings_labelling_tab( $sap, $bpfwpSettings ) {
    
    $sap->add_section(
      'bpfwp-settings',
      array_merge(
        array(
          'id'            => 'bpfwp-general-labelling',
          'title'         => __( 'General', 'business-profile' ),
          'tab'           => 'bpfwp-labelling-tab'
        ),
        $this->get_permission( 'labelling' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-opening-hours',
        'title'       => __( 'Opening Hours', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-place-an-order',
        'title'       => __( 'Place an Order', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-get-directions',
        'title'       => __( 'Get Directions', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-contact',
        'title'       => __( 'Contact', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-monday',
        'title'       => __( 'Monday', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-tuesday',
        'title'       => __( 'Tuesday', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-wednesday',
        'title'       => __( 'Wednesday', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-thursday',
        'title'       => __( 'Thursday', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-friday',
        'title'       => __( 'Friday', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-saturday',
        'title'       => __( 'Saturday', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-sunday',
        'title'       => __( 'Sunday', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-monday-abbreviation',
        'title'       => __( 'Mo (Monday Abbreviation)', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-tuesday-abbreviation',
        'title'       => __( 'Tu (Tuesday Abbreviation)', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-wednesday-abbreviation',
        'title'       => __( 'We (Wednesday Abbreviation)', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-thursday-abbreviation',
        'title'       => __( 'Th (Thursday Abbreviation)', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-friday-abbreviation',
        'title'       => __( 'Fr (Friday Abbreviation)', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-saturday-abbreviation',
        'title'       => __( 'Sa (Saturday Abbreviation)', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-sunday-abbreviation',
        'title'       => __( 'Su (Sunday Abbreviation)', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-open',
        'title'       => __( 'Open', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-open-until',
        'title'       => __( 'Open Until', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-open-from',
        'title'       => __( 'Open From', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-closed',
        'title'       => __( 'Closed', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-special-opening-hours',
        'title'       => __( 'Special Opening Hours', 'business-profile' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-holidays',
        'title'       => __( 'Holidays', 'business-profile' ),
        'description' => ''
      )
    );

    return $sap;
  }

  // "Styling" Tab
  public function bpfwp_settings_styling_tab( $sap, $bpfwpSettings ) {
    
    $sap->add_section(
      'bpfwp-settings',
      array_merge(
        array(
          'id'            => 'bpfwp-styling-contact-card',
          'title'         => __( 'Contact Card', 'business-profile' ),
          'tab'           => 'bpfwp-styling-tab'
        ),
        $this->get_permission( 'styling' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'radio',
      array(
        'id'          => 'styling-contact-card-layout',
        'title'       => __( 'Contact Card Layout', 'business-profile' ),
        'description' => __( 'Choose the main layout for the contact card. Base Flex uses flex styling, which offers improved ability to customize it with CSS. Flex Centered uses the Base Flex styling and centers all elements. Panels lays it out in two columns. Panels Centered uses the Panels layout and centers certain elements and text. For the Panels layouts, you\'ll likely want to adjust the order of your elements (in the Premium tab) to optimize the layout.', 'business-profile' ),
        'options'     => array(
          'default'         => 'Default',
          'baseflex'        => 'Base Flex',
          'flexcentered'    => 'Flex Centered',
          'panels'          => 'Panels',
          'panelscentered'  => 'Panels Centered',
        ),
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'text',
      array(
        'id'          => 'styling-map-width',
        'title'       => __( 'Map Width', 'business-profile' ),
        'description' => __( 'Lets you set the width of the map, if you\'re showing it in your contact card. Include the unit (e.g. 50%, 200px, etc.).', 'business-profile' ),
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'toggle',
      array(
        'id'          => 'styling-disable-icons',
        'title'       => __( 'Disable Icons', 'business-profile' ),
        'description' => __( 'Removes the icons that show beside various elements in the contact card (e.g. phone numbers, open hours, etc.).', 'business-profile' ),
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'text',
      array(
        'id'      => 'styling-main-font-family',
        'title'     => __( 'Font Family', 'business-profile' ),
        'description' => __( 'Choose the font family for the text in the contact card. (Please note that the font family must already be loaded on the site. This does not load it.)', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'text',
      array(
        'id'      => 'styling-main-font-size',
        'title'     => __( 'Font Size', 'business-profile' ),
        'description' => __( 'Choose the font size for the text in the contact card. Include the unit (e.g. 20px or 2em).', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'colorpicker',
      array(
        'id'      => 'styling-main-text-color',
        'title'     => __( 'Text Color', 'business-profile' ),
        'description' => __( 'Main text color for the contact card.', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'text',
      array(
        'id'      => 'styling-name-font-family',
        'title'     => __( 'Business Name Font Family', 'business-profile' ),
        'description' => __( 'Choose the font family for business name. (Please note that the font family must already be loaded on the site. This does not load it.)', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'text',
      array(
        'id'      => 'styling-name-font-size',
        'title'     => __( 'Business Name Font Size', 'business-profile' ),
        'description' => __( 'Choose the font size for the business name. Include the unit (e.g. 20px or 2em).', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'colorpicker',
      array(
        'id'      => 'styling-name-text-color',
        'title'     => __( 'Business Name Color', 'business-profile' ),
        'description' => __( 'Text color for the business name.', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'text',
      array(
        'id'      => 'styling-heading-font-family',
        'title'     => __( 'Heading Font Family', 'business-profile' ),
        'description' => __( 'Choose the font family for the headings (e.g. "Opening Hours") in the contact card. (Please note that the font family must already be loaded on the site. This does not load it.)', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'text',
      array(
        'id'      => 'styling-heading-font-size',
        'title'     => __( 'Heading Font Size', 'business-profile' ),
        'description' => __( 'Choose the font size for the headings in the contact card. Include the unit (e.g. 20px or 2em).', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'colorpicker',
      array(
        'id'      => 'styling-heading-text-color',
        'title'     => __( 'Heading Color', 'business-profile' ),
        'description' => __( 'Text color for headings in the contact card.', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'colorpicker',
      array(
        'id'      => 'styling-link-color',
        'title'     => __( 'Link Color', 'business-profile' ),
        'description' => __( 'Color for the link (e.g. phone number, email, etc.).', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'colorpicker',
      array(
        'id'      => 'styling-link-color',
        'title'     => __( 'Link Color', 'business-profile' ),
        'description' => __( 'Color for links (e.g. phone number, email, etc.).', 'business-profile' )
      )
    );

    $sap->add_setting(
      'bpfwp-settings',
      'bpfwp-styling-contact-card',
      'colorpicker',
      array(
        'id'      => 'styling-link-hover-color',
        'title'     => __( 'Link Hover Color', 'business-profile' ),
        'description' => __( 'Hover color for links.', 'business-profile' )
      )
    );

    return $sap;
  }
}
}