<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'grfwpFSPPHSettings' ) ) {
class grfwpFSPPHSettings {

  public function __construct() {

    add_filter( 'grfwp_defaults', array( $this, 'grfwp_settings_set_defaults' ), 10, 2 );

    add_filter( 'grfwp_settings_page', array( $this, 'grfwp_settings__non_premium_tabs' ), 10, 2 );

    add_filter( 'grfwp_settings_page', array( $this, 'grfwp_settings_custom_fields_tab' ), 10, 2 );
    add_filter( 'grfwp_settings_page', array( $this, 'grfwp_settings_labelling_tab' ), 10, 2 );
    add_filter( 'grfwp_settings_page', array( $this, 'grfwp_settings_styling_tab' ), 10, 2 );
  }

  public function get_permission( $permission_type = '' ) {
    global $grfwp_controller;
  
    $grfwp_premium_permissions = array();
  
    if ( ! $grfwp_controller->permissions->check_permission( $permission_type ) ) {
      $grfwp_premium_permissions = array(
        'disabled'    => true,
        'disabled_image'=> '#',
        'purchase_link' => 'https://www.fivestarplugins.com/plugins/five-star-restaurant-reviews/?utm_source=grfwp_lockbox'
      );
    }
  
    return $grfwp_premium_permissions;
  }

  public function grfwp_settings_set_defaults( $defaults, $grfwpSettings ) {

    $defaults = array_merge(
      $defaults,
      array(
        // Any default which you are certain that won't be used for free version.
      )
    );
  
    return $defaults;
  }

  public function grfwp_settings__non_premium_tabs( $sap, $grfwpSettings ) {

    return $sap;
  }

  // "Custom Fields" Tab
  public function grfwp_settings_custom_fields_tab( $sap, $grfwpSettings ) {
    
    $sap->add_section(
        'grfwp-settings',
        array_merge(
          array(
            'id'            => 'grfwp-cf-options',
            'title'         => __( 'Manage Fields', 'good-reviews-wp' ),
            'tab'           => 'grfwp-custom-fields-tab',
          ),
          $this->get_permission( 'custom_fields' )
        )
      );

      $sap->add_setting(
        'grfwp-settings',
        'grfwp-cf-options',
        'toggle',
        array(
          'id'      => 'grfwp-cf-status',
          'title'     => __( 'Enable Custom Fields', 'good-reviews-wp' ),
          'description' => __( 'Use the table below to add extra custom fields that can be used in your reviews.', 'good-reviews-wp' ),
          'options'   => array(
            'default'   => 'Enable?'
          )
        )
      );

      $sap->add_setting(
        'grfwp-settings',
        'grfwp-cf-options',
        'infinite_table',
        array(
          'id'          => 'grfwp-custom-fields',
          'title'       => 'Fields',
          'add_label'   => '+ ADD',
          'del_label'   => 'Delete',
          'description' => '',
          'fields'      => array(
            'cf_field_name' => array(
              'type'     => 'text',
              'label'    => 'Field Name',
              'required' => true
            ),
            'cf_required'  => array(
              'type'     => 'select',
              'label'    => 'Required',
              'required' => true,
              'options'  => array(
                'true'  => 'Yes',
                'false' => 'No'
              )
            ),
            'cf_type'  => array(
              'type'     => 'select',
              'label'    => 'Type',
              'required' => true,
              'options'  => array(
                'text_box'   => 'Text Box',
                'text_area'  => 'Text Area',
                'number'=> 'Number',
                'dropdown'   => 'Dropdown',
                // 'checkbox'   => 'Checkbox',
                // 'radio'      => 'Radio',
                'date'       => 'Date'
              )
            ),
            'cf_options'  => array(
              'type'     => 'text',
              'label'    => 'Options',
              'required' => false
            )
          ),
          'conditional_on'    => 'grfwp-cf-status',
          'conditional_on_value'  => true,
        )
      );
      
    return $sap;
  }

  // "Styling" Tab
  public function grfwp_settings_labelling_tab( $sap, $grfwpSettings ) {
    
    $sap->add_section(
        'grfwp-settings',
        array_merge(
          array(
            'id'            => 'grfwp-general-labelling',
            'title'         => __( 'General', 'good-reviews-wp' ),
            'tab'           => 'grfwp-labelling-tab',
          ),
          $this->get_permission( 'labelling' )
        )
      );

    $sap->add_setting(
      'grfwp-settings',
      'grfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-review',
        'title'       => __( 'Review', 'good-reviews-wp' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'grfwp-settings',
      'grfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-reviews',
        'title'       => __( 'Reviews', 'good-reviews-wp' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'grfwp-settings',
      'grfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-submit-review',
        'title'       => __( 'Submit Review', 'good-reviews-wp' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'grfwp-settings',
      'grfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-rating',
        'title'       => __( 'Rating', 'good-reviews-wp' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'grfwp-settings',
      'grfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-review-author',
        'title'       => __( 'Review Author', 'good-reviews-wp' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'grfwp-settings',
      'grfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-show-more',
        'title'       => __( 'Show More', 'good-reviews-wp' ),
        'description' => ''
      )
    );

    $sap->add_setting(
      'grfwp-settings',
      'grfwp-general-labelling',
      'text',
      array(
        'id'          => 'label-select-menu-item',
        'title'       => __( 'Select the menu item this review applies to, if any.', 'good-reviews-wp' ),
        'description' => ''
      )
    );

    return $sap;
  }

    // "Styling" Tab
  public function grfwp_settings_styling_tab( $sap, $grfwpSettings ) {
    
    $sap->add_section(
        'grfwp-settings',
        array_merge(
          array(
            'id'            => 'grfwp-general-styling',
            'title'         => __( 'General', 'good-reviews-wp' ),
            'tab'           => 'grfwp-styling-tab',
          ),
          $this->get_permission( 'styling' )
        )
      );

      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'radio',
        array(
          'id'      => 'grfwp-styling-layout',
          'title'     => __( 'Layout', 'good-reviews-wp' ),
          'description' => __( 'Choose which layout you want to use for your reviews', 'good-reviews-wp' ),
          'options'   => array(
            'default'   => 'Default',
            'thumbnail'   => 'Thumbnail',
            'image'     => 'Image',
          )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'toggle',
        array(
          'id'      => 'grfwp-styling-image-hover',
          'title'     => __( 'Image Hover', 'good-reviews-wp' ),
          'description' => __( 'Enable the hover effect for the Image layout.', 'good-reviews-wp' ),
          'conditional_on'    => 'grfwp-styling-layout',
          'conditional_on_value'  => 'image',
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-stars-color',
          'title'     => __( 'Rating/Stars Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the color for the rating numbers and stars that show in reviews.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'text',
        array(
          'id'      => 'grfwp-styling-rating-font-size',
          'title'     => __( 'Rating Font Size', 'good-reviews-wp' ),
          'description' => __( 'Choose the font size for the number rating. Include the unit (e.g. 20px or 2em).', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'text',
        array(
          'id'      => 'grfwp-styling-review-text-font-size',
          'title'     => __( 'Review Text Font Size', 'good-reviews-wp' ),
          'description' => __( 'Choose the font size for the review text. Include the unit (e.g. 20px or 2em).', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-review-text-color',
          'title'     => __( 'Review Text Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the color for the review text.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'text',
        array(
          'id'      => 'grfwp-styling-author-font-size',
          'title'     => __( 'Author Font Size', 'good-reviews-wp' ),
          'description' => __( 'Choose the font size for the author/name of the review. Include the unit (e.g. 20px or 2em).', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-author-color',
          'title'     => __( 'Author Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the color for the author/name of the review.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'text',
        array(
          'id'      => 'grfwp-styling-organization-font-size',
          'title'     => __( 'Organization Font Size', 'good-reviews-wp' ),
          'description' => __( 'Choose the font size for the organization name. Include the unit (e.g. 20px or 2em).', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-organization-color',
          'title'     => __( 'Organization Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the color for the organization name.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-read-more-background-color',
          'title'     => __( 'Read More Background Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the background color for the read more button.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-read-more-text-color',
          'title'     => __( 'Read More Text Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the text color for the read more button.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-icon-color',
          'title'     => __( 'Icon Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the color of the author, organization and date icons.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-show-more-background-color',
          'title'     => __( 'Show More Background Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the background color for the show more button.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-show-more-background-hover-color',
          'title'     => __( 'Show More Background Hover Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the background color for the show more button on hover.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-show-more-text-color',
          'title'     => __( 'Show More Text Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the text color for the show more button.', 'good-reviews-wp' )
        )
      );
      $sap->add_setting(
        'grfwp-settings',
        'grfwp-general-styling',
        'colorpicker',
        array(
          'id'      => 'grfwp-styling-show-more-border-color',
          'title'     => __( 'Show More Border Color', 'good-reviews-wp' ),
          'description' => __( 'Choose the border color for the show more button.', 'good-reviews-wp' )
        )
      );

    return $sap;
  }

}
}