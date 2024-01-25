<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'fspHandleVersionReversion' ) ) {
class fspHandleVersionReversion {

	public function __construct() {

		if ( get_option( "BPFWP_Trial_Happening" ) == "Yes" and time() > get_option( "BPFWP_Trial_Expiry_Time" ) ) { $this->fsp_bpfwp_version_reversion(); }
		if ( get_option( "FDM_Trial_Happening" ) == "Yes" and time() > get_option( "FDM_Trial_Expiry_Time" ) ) { $this->fsp_fdm_version_reversion(); }
		if ( get_option( "FDMU_Trial_Happening" ) == "Yes" and time() > get_option( "FDMU_Trial_Expiry_Time" ) ) { $this->fsp_fdmu_version_reversion(); }
		if ( get_option( "GRFWP_Trial_Happening" ) == "Yes" and time() > get_option( "GRFWP_Trial_Expiry_Time" ) ) { $this->fsp_grfwp_version_reversion(); }
		if ( get_option( "RTB_Trial_Happening" ) == "Yes" and time() > get_option( "RTB_Trial_Expiry_Time" ) ) { $this->fsp_rtb_version_reversion(); }
		if ( get_option( "RTU_Trial_Happening" ) == "Yes" and time() > get_option( "RTU_Trial_Expiry_Time" ) ) { $this->fsp_rtu_version_reversion(); }
	}

	public function fsp_plugin_deactivation_reversion() {

		if ( get_option( 'bpfwp-permission-level' ) and get_option( "BPFWP_Trial_Happening" ) == "Yes" ) { $this->fsp_bpfwp_version_reversion(); }
		if ( get_option( 'fdm-permission-level' ) and get_option( "FDM_Trial_Happening" ) == "Yes" ) { $this->fsp_fdm_version_reversion(); }
		if ( get_option( 'fdm-permission-level' ) and get_option( "FDMU_Trial_Happening" ) == "Yes" ) { $this->fsp_fdmu_version_reversion(); }
		if ( get_option( 'grfwp-permission-level' ) and get_option( "GRFWP_Trial_Happening" ) == "Yes" ) { $this->fsp_grfwp_version_reversion(); }
		if ( get_option( 'rtb-permission-level' ) and get_option( "RTB_Trial_Happening" ) == "Yes" ) { $this->fsp_rtb_version_reversion(); }
		if ( get_option( 'rtb-permission-level' ) and get_option( "RTU_Trial_Happening" ) == "Yes" ) { $this->fsp_rtu_version_reversion(); }
	}

	public function fsp_bpfwp_version_reversion() {
		global $bpfwp_controller;

		if(
			!isset($bpfwp_controller) 
			|| !is_a($bpfwp_controller, 'bpfwpInit') 
			|| !property_exists($bpfwp_controller, 'settings')
		) {
			return;
		}

		$default_order = array(
			'name'                => __( 'Name', 'business-profile' ),
			'address'             => __( 'Address', 'business-profile' ),
			'phone'               => __( 'Phone', 'business-profile' ),
			'cell_phone'          => __( 'Cell Phone', 'business-profile' ),
			'whatsapp'            => __( 'WhatsApp', 'business-profile' ),
			'fax_phone'           => __( 'Fax', 'business-profile' ),
			'ordering-link'       => __( 'Ordering Link', 'business-profile' ),
			'contact'             => __( 'Contact', 'business-profile' ),
			'exceptions'          => __( 'Exceptions', 'business-profile' ),
			'opening_hours'       => __( 'Opening Hours', 'business-profile' ),
			'map'                 => __( 'Maps', 'business-profile' ),
			'parent_organization' => __( 'Parent Organization', 'business-profile' )
		);

		$bpfwp_controller->settings->set_setting( 'contact-card-elements-order', json_encode( $default_order ) );

		$bpfwp_controller->settings->set_setting( 'article-rich-snippets', false );
		$bpfwp_controller->settings->set_setting( 'schema-default-helpers', false );
		$bpfwp_controller->settings->set_setting( 'woocommerce-integration', false );

		$bpfwp_controller->settings->set_setting( 'label-opening-hours', false );
		$bpfwp_controller->settings->set_setting( 'label-place-an-order', false );
		$bpfwp_controller->settings->set_setting( 'label-get-directions', false );
		$bpfwp_controller->settings->set_setting( 'label-contact', false );
		$bpfwp_controller->settings->set_setting( 'label-monday', false );
		$bpfwp_controller->settings->set_setting( 'label-tuesday', false );
		$bpfwp_controller->settings->set_setting( 'label-wednesday', false );
		$bpfwp_controller->settings->set_setting( 'label-thursday', false );
		$bpfwp_controller->settings->set_setting( 'label-friday', false );
		$bpfwp_controller->settings->set_setting( 'label-saturday', false );
		$bpfwp_controller->settings->set_setting( 'label-sunday', false );
		$bpfwp_controller->settings->set_setting( 'label-monday-abbreviation', false );
		$bpfwp_controller->settings->set_setting( 'label-tuesday-abbreviation', false );
		$bpfwp_controller->settings->set_setting( 'label-wednesday-abbreviation', false );
		$bpfwp_controller->settings->set_setting( 'label-thursday-abbreviation', false );
		$bpfwp_controller->settings->set_setting( 'label-friday-abbreviation', false );
		$bpfwp_controller->settings->set_setting( 'label-saturday-abbreviation', false );
		$bpfwp_controller->settings->set_setting( 'label-sunday-abbreviation', false );
		$bpfwp_controller->settings->set_setting( 'label-open', false );
		$bpfwp_controller->settings->set_setting( 'label-open-until', false );
		$bpfwp_controller->settings->set_setting( 'label-open-from', false );
		$bpfwp_controller->settings->set_setting( 'label-closed', false );
		$bpfwp_controller->settings->set_setting( 'label-special-opening-hours', false );
		$bpfwp_controller->settings->set_setting( 'label-holidays', false );

		$bpfwp_controller->settings->save_settings();

		delete_option( 'BPFWP_Trial_Expiry_Time' );
		update_option( 'BPFWP_Trial_Happening', 'No' );

		update_option( 'bpfwp-permission-level', get_option( 'bpfwp-pre-permission-level' ) ? get_option( 'bpfwp-pre-permission-level' ) : 1 );
	}

	public function fsp_fdm_version_reversion() {
		global $fdm_controller;

		if(
			!isset($fdm_controller) 
			|| !is_a($fdm_controller, 'fdmFoodAndDrinkMenu') 
			|| !property_exists($fdm_controller, 'settings')
		) {
			return;
		}

		$fdm_controller->settings->set_setting( 'fdm-styling-section-title-font-family', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-section-title-font-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-section-title-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-name-font-family', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-name-font-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-name-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-description-font-family', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-description-font-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-description-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-price-font-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-price-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-image-width', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-image-border-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-image-border-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-separating-line-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-separating-line-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-filtering-font-family', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-filtering-title-font-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-filtering-title-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-filtering-labels-font-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-filtering-labels-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-sidebar-font-family', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-sidebar-title-font-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-sidebar-title-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-sidebar-description-font-size', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-sidebar-description-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-item-icon-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-add-to-cart-background-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-add-to-cart-text-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-shopping-cart-accent-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-order-progress-color', '' );
		$fdm_controller->settings->set_setting( 'fdm-styling-order-progress-border-color', '' );

		$fdm_controller->settings->set_setting( 'fdm-text-search', array() );
		$fdm_controller->settings->set_setting( 'fdm-enable-price-filtering', false );
		$fdm_controller->settings->set_setting( 'fdm-enable-sorting', false );

		$fdm_controller->settings->set_setting( 'fdm-related-items', 'none' );
		$fdm_controller->settings->set_setting( 'fdm-disable-menu-item-flags', true );
		$fdm_controller->settings->set_setting( 'fdm-disable-specials', true );
		$fdm_controller->settings->set_setting( 'fdm-disable-price-discounted', true );
		$fdm_controller->settings->set_setting( 'fdm-disable-src', true );
		$fdm_controller->settings->set_setting( 'fdm-disable-src-map', true );
		
		$fdm_controller->settings->set_setting( 'fdm-enable-ordering-options', false );
		$fdm_controller->settings->set_setting( 'fdm-enable-ordering-progress-display', false );
		$fdm_controller->settings->set_setting( 'enable-payment', false );

		$fdm_controller->settings->set_setting( 'fdm-custom-fields', array() );

		$fdm_controller->settings->set_setting( 'label-custom-fields', false );
		$fdm_controller->settings->set_setting( 'label-related-items', false );
		$fdm_controller->settings->set_setting( 'label-on-sale', false );
		$fdm_controller->settings->set_setting( 'label-special-offer', false );
		$fdm_controller->settings->set_setting( 'label-featured', false );
		$fdm_controller->settings->set_setting( 'label-sidebar-expand-button', false );

		$fdm_controller->settings->set_setting( 'label-search', false );
		$fdm_controller->settings->set_setting( 'label-search-items', false );
		$fdm_controller->settings->set_setting( 'label-filtering-price', false );
		$fdm_controller->settings->set_setting( 'label-sorting', false );
		$fdm_controller->settings->set_setting( 'label-name-asc', false );
		$fdm_controller->settings->set_setting( 'label-name-desc', false );
		$fdm_controller->settings->set_setting( 'label-price-asc', false );
		$fdm_controller->settings->set_setting( 'label-price-desc', false );
		$fdm_controller->settings->set_setting( 'label-date-added-asc', false );
		$fdm_controller->settings->set_setting( 'label-date-added-desc', false );
		$fdm_controller->settings->set_setting( 'label-section-asc', false );
		$fdm_controller->settings->set_setting( 'label-section-desc', false );

		$fdm_controller->settings->set_setting( 'label-add-to-cart', false );
		$fdm_controller->settings->set_setting( 'label-discount', false );
		$fdm_controller->settings->set_setting( 'label-remove', false );
		$fdm_controller->settings->set_setting( 'label-ordering-price', false );
		$fdm_controller->settings->set_setting( 'label-order-item-details', false );
		$fdm_controller->settings->set_setting( 'label-item-note', false );
		$fdm_controller->settings->set_setting( 'label-confirm-details', false );
		$fdm_controller->settings->set_setting( 'label-order-progress', false );
		$fdm_controller->settings->set_setting( 'label-order-summary', false );
		$fdm_controller->settings->set_setting( 'label-item-in-cart', false );
		$fdm_controller->settings->set_setting( 'label-items-in-cart', false );
		$fdm_controller->settings->set_setting( 'label-item-s-in-cart', false );
		$fdm_controller->settings->set_setting( 'label-quantity', false );
		$fdm_controller->settings->set_setting( 'label-clear', false );
		$fdm_controller->settings->set_setting( 'label-total', false );
		$fdm_controller->settings->set_setting( 'label-check-out', false );
		$fdm_controller->settings->set_setting( 'label-name', false );
		$fdm_controller->settings->set_setting( 'label-email', false );
		$fdm_controller->settings->set_setting( 'label-phone', false );
		$fdm_controller->settings->set_setting( 'label-order-note', false );
		$fdm_controller->settings->set_setting( 'label-pay-in-store', false );
		$fdm_controller->settings->set_setting( 'label-pay-online', false );
		$fdm_controller->settings->set_setting( 'label-submit-order', false );
		$fdm_controller->settings->set_setting( 'label-add-another-item', false );
		$fdm_controller->settings->set_setting( 'label-pay-via-paypal', false );
		$fdm_controller->settings->set_setting( 'label-card-number', false );
		$fdm_controller->settings->set_setting( 'label-cvc', false );
		$fdm_controller->settings->set_setting( 'label-expiration', false );
		$fdm_controller->settings->set_setting( 'label-pay-now', false );
		$fdm_controller->settings->set_setting( 'label-order-failed', false );
		$fdm_controller->settings->set_setting( 'label-order-success', false );
		$fdm_controller->settings->set_setting( 'label-order-payment-success', false );
		$fdm_controller->settings->set_setting( 'label-order-payment-failed', false );

		$fdm_controller->settings->save_settings();

		delete_option( 'FDM_Trial_Expiry_Time' );
		update_option( 'FDM_Trial_Happening', 'No' );

		update_option( 'fdm-permission-level', get_option( 'fdm-pre-permission-level' ) ? get_option( 'fdm-pre-permission-level' ) : 1 );
	}	

	public function fsp_grfwp_version_reversion() {
		global $grfwp_controller;

		if(
			!isset($grfwp_controller) 
			|| !is_a($grfwp_controller, 'grfwpInit') 
			|| !property_exists($grfwp_controller, 'settings')
		) {
			return;
		}

		$grfwp_controller->settings->set_setting( 'grfwp-styling-layout', 'default' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-stars-color', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-rating-font-size', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-review-text-font-size', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-review-text-color', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-author-font-size', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-author-color', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-organization-font-size', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-organization-color', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-read-more-background-color', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-read-more-text-color', '' );
		$grfwp_controller->settings->set_setting( 'grfwp-styling-icon-color', '' );

		$grfwp_controller->settings->save_settings();

		delete_option( 'GRFWP_Trial_Expiry_Time' );
		update_option( 'GRFWP_Trial_Happening', 'No' );

		update_option( 'grfwp-permission-level', get_option( 'grfwp-pre-permission-level' ) ? get_option( 'grfwp-pre-permission-level' ) : 1 );
	}

	public function fsp_rtb_version_reversion() {
		global $rtb_controller;

		if(
			!isset($rtb_controller) 
			|| !is_a($rtb_controller, 'rtbInit') 
			|| !property_exists($rtb_controller, 'settings')
		) {
			return;
		}

		$rtb_controller->settings->set_setting( 'view-bookings-page', '' );
		$rtb_controller->settings->set_setting( 'view-bookings-arrivals', false );
		
		$rtb_controller->settings->set_setting( 'auto-confirm-max-reservations', '' );
		$rtb_controller->settings->set_setting( 'auto-confirm-max-seats', '' );
		
		$rtb_controller->settings->set_setting( 'mc-lists', '' );
		
		$rtb_controller->settings->set_setting( 'rtb-styling-layout', 'default' );
		$rtb_controller->settings->set_setting( 'rtb-styling-section-title-font-family', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-section-title-font-size', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-section-title-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-section-background-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-section-border-size', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-section-border-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-label-font-family', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-label-font-size', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-label-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-add-message-button-background-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-add-message-button-background-hover-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-add-message-button-text-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-add-message-button-text-hover-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-request-booking-button-background-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-request-booking-button-background-hover-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-request-booking-button-text-color', '' );
		$rtb_controller->settings->set_setting( 'rtb-styling-request-booking-button-text-hover-color', '' );

		$rtb_controller->settings->set_setting( 'label-book-table', false );
		$rtb_controller->settings->set_setting( 'label-location', false );
		$rtb_controller->settings->set_setting( 'label-date', false );
		$rtb_controller->settings->set_setting( 'label-date-today', false );
		$rtb_controller->settings->set_setting( 'label-date-clear', false );
		$rtb_controller->settings->set_setting( 'label-date-close', false );
		$rtb_controller->settings->set_setting( 'label-time', false );
		$rtb_controller->settings->set_setting( 'label-time-clear', false );
		$rtb_controller->settings->set_setting( 'label-no-times-available', false );
		$rtb_controller->settings->set_setting( 'label-party', false );
		$rtb_controller->settings->set_setting( 'label-table-s', false );
		$rtb_controller->settings->set_setting( 'label-table-min', false );
		$rtb_controller->settings->set_setting( 'label-table-max', false );
		$rtb_controller->settings->set_setting( 'label-contact-details', false );
		$rtb_controller->settings->set_setting( 'label-name', false );
		$rtb_controller->settings->set_setting( 'label-email', false );
		$rtb_controller->settings->set_setting( 'label-phone', false );
		$rtb_controller->settings->set_setting( 'label-add-message', false );
		$rtb_controller->settings->set_setting( 'label-message', false );
		$rtb_controller->settings->set_setting( 'label-request-booking', false );

		$rtb_controller->settings->set_setting( 'label-payment-gateway', false );
		$rtb_controller->settings->set_setting( 'label-proceed-to-deposit', false );
		$rtb_controller->settings->set_setting( 'label-request-or-deposit', false );
		$rtb_controller->settings->set_setting( 'label-pay-via-paypal', false );
		$rtb_controller->settings->set_setting( 'label-deposit-required', false );
		$rtb_controller->settings->set_setting( 'label-deposit-placing-hold', false );
		$rtb_controller->settings->set_setting( 'label-card-detail', false );
		$rtb_controller->settings->set_setting( 'label-card-number', false );
		$rtb_controller->settings->set_setting( 'label-cvc', false );
		$rtb_controller->settings->set_setting( 'label-expiration', false );
		$rtb_controller->settings->set_setting( 'label-please-wait', false );
		$rtb_controller->settings->set_setting( 'label-make-deposit', false );

		$rtb_controller->settings->set_setting( 'label-modify-reservation', false );
		$rtb_controller->settings->set_setting( 'label-modify-make-reservation', false );
		$rtb_controller->settings->set_setting( 'label-modify-using-form', false );
		$rtb_controller->settings->set_setting( 'label-modify-form-email', false );
		$rtb_controller->settings->set_setting( 'label-modify-find-reservations', false );
		$rtb_controller->settings->set_setting( 'label-modify-no-bookings-found', false );
		$rtb_controller->settings->set_setting( 'label-modify-cancel', false );
		$rtb_controller->settings->set_setting( 'label-modify-cancelled', false );
		$rtb_controller->settings->set_setting( 'label-modify-deposit', false );
		$rtb_controller->settings->set_setting( 'label-modify-guest', false );
		$rtb_controller->settings->set_setting( 'label-modify-guests', false );

		$rtb_controller->settings->set_setting( 'label-view-arrived', false );
		$rtb_controller->settings->set_setting( 'label-view-time', false );
		$rtb_controller->settings->set_setting( 'label-view-party', false );
		$rtb_controller->settings->set_setting( 'label-view-name', false );
		$rtb_controller->settings->set_setting( 'label-view-email', false );
		$rtb_controller->settings->set_setting( 'label-view-phone', false );
		$rtb_controller->settings->set_setting( 'label-view-table', false );
		$rtb_controller->settings->set_setting( 'label-view-status', false );
		$rtb_controller->settings->set_setting( 'label-view-details', false );
		$rtb_controller->settings->set_setting( 'label-view-set-status-arrived', false );
		$rtb_controller->settings->set_setting( 'label-view-arrived-yes', false );
		$rtb_controller->settings->set_setting( 'label-view-arrived-no', false );

		$rtb_controller->settings->set_setting( 'label-cancel-link-tag', false );
		$rtb_controller->settings->set_setting( 'label-bookings-link-tag', false );
		$rtb_controller->settings->set_setting( 'label-confirm-link-tag', false );
		$rtb_controller->settings->set_setting( 'label-close-link-tag', false );
		
		$rtb_controller->settings->save_settings();

		delete_option( 'RTB_Trial_Expiry_Time' );
		update_option( 'RTB_Trial_Happening', 'No' );

		update_option( 'rtb-permission-level', get_option( 'rtb-pre-permission-level' ) ? get_option( 'rtb-pre-permission-level' ) : 1 );
	}

	public function fsp_rtu_version_reversion() {
		global $rtb_controller;

		if(
			!isset($rtb_controller) 
			|| !is_a($rtb_controller, 'rtbInit') 
			|| !property_exists($rtb_controller, 'settings')
		) {
			return;
		}

		$rtb_controller->settings->set_setting( 'require-deposit', false );
		
		$rtb_controller->settings->set_setting( 'time-reminder-user', '' );
		$rtb_controller->settings->set_setting( 'time-late-user', '' );
		
		$rtb_controller->settings->set_setting( 'enable-tables', false );
		$rtb_controller->settings->set_setting( 'require-table', false );

		$rtb_controller->settings->save_settings();

		delete_option( 'RTU_Trial_Expiry_Time' );
		update_option( 'RTU_Trial_Happening', 'No' );

		if ( get_option( 'rtb-pre-permission-level' ) == 1 ) { $this->fsp_rtb_version_reversion(); }

		update_option( 'rtb-permission-level', get_option( 'rtb-pre-permission-level' ) ? get_option( 'rtb-pre-permission-level' ) : 1 );
	}

	public function fsp_fdmu_version_reversion() {
		global $fdm_controller;

		if(
			!isset($fdm_controller) 
			|| !is_a($fdm_controller, 'fdmFoodAndDrinkMenu') 
			|| !property_exists($fdm_controller, 'settings')
		) {
			return;
		}

		$fdm_controller->settings->set_setting( 'fdm-enable-ordering', false );

		$fdm_controller->settings->save_settings();

		delete_option( 'FDMU_Trial_Expiry_Time' );
		update_option( 'FDMU_Trial_Happening', 'No' );

		if ( get_option( 'fdm-pre-permission-level' ) == 1 ) { $this->fsp_fdm_version_reversion(); }

		update_option( 'fdm-permission-level', get_option( 'fdm-pre-permission-level' ) ? get_option( 'fdm-pre-permission-level' ) : 1 );
	}

}
}

 ?>
