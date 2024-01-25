<?php 

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'fspHandleUpgrades' ) ) {
class fspHandleUpgrades {

	// The plugin that is being manipulated
	public $plugin = ''; 

	// The product key entered into the upgrade form
	public $key = '';

	private $controller_variable_name = '';

	public function __construct() {

		if ( isset( $_POST['fsp_upgrade_to_full'] ) ) {

			$this->plugin = $_POST['plugin_name'];
			$this->key = trim($_POST['key']);

			$this->set_controller_variable();

			add_action( 'admin_init', array( $this, 'fsp_upgrade_to_full') );
		}
	}

 	public function fsp_upgrade_to_full() {
		
		if ( $this->key == "FSP Trial" and ! $this->is_trial_happening() ) {
			
			$this->message_type = 'update';
			$this->message = __("Trial successfully started!", 'fsp-premium-helper');

			add_action( 'admin_notices', array( $this, 'display_message') );

			$this->set_trial_options();

			$this->update_version_status();

			$this->send_trial_information();
		}
		elseif ( strlen( $this->key ) < 18 or strlen( $this->key ) > 22 ) {

			$this->message_type = 'error';
			$this->message = __("Invalid Product Key", 'fsp-premium-helper');

			add_action( 'admin_notices', array( $this, 'display_message') );
		}
		elseif ( $this->key != "FSP Trial" ) {
			
			$premium_response = $this->check_product_key();
			
			if ( $premium_response['Message_Type'] == "Error" && false == $premium_response['bypass'] ) {
				
				$this->message_type = 'error';
				$this->message = $premium_response['Message'];

				add_action( 'admin_notices', array( $this, 'display_message') );
			}
			else {

				if( true == $premium_response['bypass'] ) {
					wp_mail(
						'contact@fivestarplugins.com', 
						"{$this->plugin} activation server communication", 
						"This server is unable to connect to the Five Star server for activation.\n\nThe license key is: $this->key\n\nWebsite: ".get_bloginfo( 'name' )
					);
				}

				$this->message_type = 'update';
				$this->message = $premium_response['Message'];
				$this->permission_level = isset( $premium_response['Permission_Level'] ) ? $premium_response['Permission_Level'] : 2;

				add_action( 'admin_notices', array( $this, 'display_message') );
	
				$this->do_upgrade();
			}
		}

		do_action( 'fsp_plugin_upgrade', $this );
	}

	public function set_controller_variable() {

		$this->controller_variable_name = $this->plugin == 'RTU' ? 'rtb_controller' : 
										  ( $this->plugin == 'FDMU' ? 'fdm_controller' : strtolower( $this->plugin ) . '_controller' );
	}

	public function is_trial_happening() {

		return get_option( $this->plugin . '_Trial_Happening' ) != '' ? true : false;
	}

	public function display_message() { ?>
		<div class='<?php echo $this->message_type; ?>'><p><?php echo $this->message; ?></p></div>
	<?php }

	public function set_trial_options() {

		$this->permission_level = ( $this->plugin == 'RTU' or $this->plugin == 'FDMU' ) ? 3 : 2;

		update_option( $this->plugin .'_Trial_Expiry_Time', time() + (7*24*60*60) );
		update_option( $this->plugin . '_Trial_Happening', 'Yes');

		if ( $this->plugin == 'RTU' ) {
			update_option( 'rtb-pre-permission-level', get_option( 'rtb-permission-level' ) );
			update_option( 'rtb-permission-level', $this->permission_level );
		}
		if ( $this->plugin == 'FDMU' ) {
			update_option( 'fdm-pre-permission-level', get_option( 'fdm-permission-level' ) );
			update_option( 'fdm-permission-level', $this->permission_level );
		}
		else {
			update_option( strtolower( $this->plugin ) . '-pre-permission-level', get_option( strtolower( $this->plugin ) . '-permission-level' ) );
			update_option( strtolower( $this->plugin ) . '-permission-level', $this->permission_level );
		}
	}

	public function update_version_status() {
		global ${$this->controller_variable_name};

		${$this->controller_variable_name}->permissions->update_permissions();
	}

	public function send_trial_information() {

		$admin_email = get_option( 'admin_email' );

		$response = wp_remote_get( 'http://www.fivestarplugins.com/key-check/Register_Trial.php?Plugin=' . $this->plugin . '&Admin_Email=' . $admin_email . '&Site=' . get_bloginfo( 'wpurl' ) );
	}

	public function check_product_key() {
		$theme = wp_get_theme();
		$theme_name = $theme->get( 'Name' );

		$response = wp_remote_get( 'http://www.fivestarplugins.com/key-check/FSP_' . $this->plugin . '_KeyCheck.php?Key=' . $this->key . '&Site=' . get_bloginfo( 'wpurl' ) . '&Theme_Name=' . $theme_name );

		if($response instanceof WP_Error) {
			if(defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
				error_log('FSP Premium Helper ('.$response->get_error_code().') : '.$response->get_error_message());
			}

			return array(
				'Message_Type' => 'Error',
				'Message' => 'Unable to connect to Five Star servers. If the plugin does not activate, please contact our support team at contact@fivestarplugins.com.',
				'bypass' => true
			);
		}

		$response = unserialize( wp_remote_retrieve_body( $response ) );
		$response['bypass'] = false;

		return $response;
	}

	public function do_upgrade() {

		delete_option( $this->plugin . '_Trial_Expiry_Time' );
		update_option( $this->plugin . '_Trial_Happening', 'No' );
		update_option( strtolower( $this->plugin ) . '-permission-level', $this->permission_level );

		if ( $this->plugin == 'RTB' ) {
			delete_option( 'RTU_Trial_Expiry_Time' );
			update_option( 'RTU_Trial_Happening', 'No' );
		}
		elseif ( $this->plugin == 'FDM' ) {
			delete_option( 'FDMU_Trial_Expiry_Time' );
			update_option( 'FDMU_Trial_Happening', 'No' );
		}
	
		$this->update_version_status();
	
		update_option( strtolower( $this->plugin ) . '-license-key', $this->key );
		if ( $this->permission_level == 3 ) { update_option( strtolower( $this->plugin ) . '-ultimate-license-key', $this->key ); }

		if ( $this->plugin == 'RTU' ) { delete_transient( 'rtb-credit-information' ); }
		elseif ( $this->plugin == 'FDMU' ) { delete_transient( 'fdm-credit-information' ); }
	}
}
}

 ?>
