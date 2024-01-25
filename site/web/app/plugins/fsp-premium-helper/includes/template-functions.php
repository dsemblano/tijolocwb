<?php

if ( ! function_exists( 'fsp_decode_infinite_table_setting' ) ) {
function fsp_decode_infinite_table_setting( $values ) {
	
	return is_array( json_decode( html_entity_decode( $values ) ) ) ? json_decode( html_entity_decode( $values ) ) : array();
}
}

function fspph_enqueue_admin_assets() {
	
	wp_enqueue_style( 'fspph-admin-css', FSPPH_PLUGIN_URL . '/assets/css/admin.css', array(), FSPPH_VERSION );
}

/**
 * error_log() wrapper to only log if debuggin enabled and asked for log
 * */
function fspph_debug( $message = '' ) {
	if( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
		error_log( $message );
	}
}

/**
 * Helper for testing whether the user has access to some ultimate features
 * */
function rtb_ultimate_active() {
	global $rtb_controller;

	return ( isset( $rtb_controller ) and $rtb_controller->permissions->check_permission( 'api_usage' ) ) ? true : false;
}

/**
 * Helper for testing whether the user has access to some ultimate features
 * */
function fdm_ultimate_active() {
	global $fdm_controller;

	return ( isset( $fdm_controller ) and $fdm_controller->permissions->check_permission( 'api_usage' ) ) ? true : false;
}