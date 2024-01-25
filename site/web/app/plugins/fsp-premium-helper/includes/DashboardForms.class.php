<?php

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( !class_exists( 'fspDashboardForms' ) ) {
class fspDashboardForms {

	public function __construct() {

		add_filter( 'fsp_dashboard_top', array( $this, 'display_upgrade_form' ), 10, 3 );
		add_filter( 'fsp_dashboard_top_kses_allowed_tags', array( $this, 'allowed_tags_upgrade_form' ), 10 );
		add_filter( 'fsp_trial_button', array( $this, 'display_trial_form' ), 10, 2 );

		add_action( 'fsp_trial_happening', array( $this, 'display_trial_information' ), 10, 1 );
	}

	public function display_trial_form( $string, $plugin ) {

		fspph_enqueue_admin_assets();

		$form_action = $this->get_form_action( $plugin );

		$trial_form = '<form method="post" id="fsp-trial-form" action="' . $form_action . '">';
		$trial_form .= '<input name="key" type="hidden" value="FSP Trial">';
		$trial_form .= '<input type="hidden" name="plugin_name" value="' . $plugin . '" />';
		$trial_form .= '<input name="fsp_upgrade_to_full" type="hidden" value="fsp_upgrade_to_full">';
		$trial_form .= '<button class="fsp-premium-helper-dashboard-get-premium-widget-button fsp-premium-helper-dashboard-new-trial-button">GET FREE 7-DAY TRIAL</button>';
		$trial_form .= '</form>';

		return $trial_form;
	}

	public function display_upgrade_form( $string, $plugin, $link ) {

		fspph_enqueue_admin_assets();

		$form_action = $this->get_form_action( $plugin );

		$upgrade_form = '<div class="fsp-premium-helper-dashboard-new-widget-box fsp-widget-box-full">';
		$upgrade_form .= '<div class="fsp-premium-helper-dashboard-new-widget-box-top">';
		$upgrade_form .= '<form method="post" action="' . $form_action . '" class="fsp-premium-helper-dashboard-key-widget">';
		$upgrade_form .= '<input class="fsp-premium-helper-dashboard-key-widget-input" name="key" type="text" placeholder="' . __('Enter Product Key Here', 'fsp-premium-helper') . '">';
		$upgrade_form .= '<input type="hidden" name="plugin_name" value="' . $plugin . '" />';
		$upgrade_form .= '<input class="fsp-premium-helper-dashboard-key-widget-submit" name="fsp_upgrade_to_full" type="submit" value="' . __('UNLOCK PREMIUM', 'fsp-premium-helper') . '">';
		$upgrade_form .= '<div class="fsp-premium-helper-dashboard-key-widget-text">' . sprintf( __("Don't have a key? Use the <a href='%s&utm_source=" . $plugin . "_admin&utm_content=enter_key_field' target='_blank'>UPGRADE NOW</a> button above to purchase and unlock all premium features.", 'fsp-premium-helper'), $link ) . '</div>';
		$upgrade_form .= '</form>';
		$upgrade_form .= '</div>';
		$upgrade_form .= '</div>';
		
		return $upgrade_form;

	}

	public function allowed_tags_upgrade_form( $allowed_tags ) {
		
		$allowed_atts = array(
			'id' => array(),
			'name' => array(),
			'type' => array(),
			'class' => array(),
			'style' => array(),
			'value' => array(),
			'method' => array(),
			'action' => array(),
			'placeholder' => array()
		);

		$allowed_tags = array_merge(
			$allowed_tags,
			array(
				'form'  => $allowed_atts,
				'input' => $allowed_atts
			)
		);

		return $allowed_tags;
	}

	public function display_trial_information( $plugin ) {

		fspph_enqueue_admin_assets();

		$trial_expiry_time = get_option( $plugin . "_Trial_Expiry_Time" );

		if ( ! $trial_expiry_time ) { return; }

		$current_time = time();
		$trial_time_left = $trial_expiry_time - $current_time;
		$trial_time_left_days = date("d", $trial_time_left) - 1;
		$trial_time_left_hours = date("H", $trial_time_left);
		
		?>

		<div class="fsp-premium-helper-dashboard-new-widget-box-bottom">
			<div class="fsp-premium-helper-dashboard-get-premium-widget-trial-time">
				<div class="fsp-premium-helper-dashboard-get-premium-widget-trial-days"><?php echo $trial_time_left_days; ?><span>days</span></div>
				<div class="fsp-premium-helper-dashboard-get-premium-widget-trial-hours"><?php echo $trial_time_left_hours; ?><span>hours</span></div>
			</div>
			<div class="fsp-premium-helper-dashboard-get-premium-widget-trial-time-left">LEFT IN TRIAL</div>
		</div>
	
	<?php }

	public function get_form_action( $plugin ) {

		switch ( $plugin ) {
	
			case 'FDM':
				return 'edit.php?post_type=fdm-menu&page=fdm-dashboard';
				break;

			default:
				return 'admin.php?page=' . strtolower( $plugin ) . '-dashboard';
				break;
		}		
	}
}
}