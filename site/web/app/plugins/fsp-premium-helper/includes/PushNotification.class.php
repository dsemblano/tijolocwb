<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'fspphPushNotification' ) ) {
/**
 * This class create and process push notifications
 */
class fspphPushNotification {

    public function __construct() {}

    public function set_notification( $notification_args ) {
        
        $this->license = ! empty( $notification_args['license'] ) ? $notification_args['license'] : '';
        $this->plugin = ! empty( $notification_args['plugin'] ) ? $notification_args['plugin'] : '';
        $this->notification = $notification_args;
    }

    public function send_notification() {

        if ( empty( $this->license ) or empty( $this->plugin ) ) { 

            $message = __( 'There was no license passed as an argument for the push notification request. Please make sure that the license field is correctly filled in.', 'fsp-premium-helper' );

            $this->add_error_log_entry( 'LCNS001', $message );

            return false; 
        }

        $url = add_query_arg(
            array(
                'license_key'   => urlencode( $this->license ),
                'plugin'        => urlencode( $this->plugin ),
                'data'          => urlencode( base64_encode( serialize( $this->notification ) ) )
            ),
            'https://www.fivestarplugins.com/pushnotihandler/send-push-notification.php'
        );

        $response = wp_remote_get( $url );

        if ( $response instanceof WP_Error ) {

            $message = $response->has_errors() ? $response->get_error_message() : __( 'The push notification token did not process properly.', 'fsp-premium-helper' );

            $this->add_error_log_entry( 'SND001', $message );
            
            return false;
        }
        
        $body = json_decode( wp_remote_retrieve_body( $response ), true );
        
        if ( ! isset( $body['success'] ) ) {

            $this->add_error_log_entry( 'JSN001', print_r( $response, true ) );

            return true;
        }
        elseif ( $body['success'] == false ) {

            $error_code = isset( $body['code'] ) ? $body['code'] : __( 'Unknown Code', 'fsp-premium-helper' );
            $message = ! empty( $body['message'] ) ? $body['message'] : __( 'The token did not process properly. If you keep getting this error, contact Five Star Plugins support at contact@fivestarplugins.com.', 'fsp-premium-helper' );
                        
            $this->add_error_log_entry( $error_code, $message );

            if ( $error_code == 'E005' ) { do_action( 'fspph_license_expire_notification' ); }

            //if no error code is set, retry sending, stop otherwise
            return isset( $body['code'] );
        }


        return true;
    }

    /**
     * Adds an entry to the error log option
     * @since 0.0.16
     */
    public function add_error_log_entry( $error_code, $message ) {
          
        $error_messages = is_array( get_option( 'fsrm-error-log' ) ) ? get_option( 'fsrm-error-log' ) : array();
        
        $error_messages[] = array(
            'timestamp'     => time(),
            'error_code'    => $error_code,
            'message'       => $message,
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
}
}