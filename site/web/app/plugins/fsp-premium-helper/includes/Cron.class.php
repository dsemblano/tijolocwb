<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'fspphCron' ) ) {
/**
 * This class handles scheduling of cron jobs
 */
class fspphCron {

	/**
	 * Adds the necessary filter and action calls
	 */
	public function __construct() {
		add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );
	}

	/**
	 * Adds in 10 minute cron interval
	 *
	 * @var array $schedules
	 */
	public function add_cron_interval( $schedules ) {
		$schedules['ten_minutes'] = array(
			'interval' => 600,
			'display' => esc_html__( 'Every Ten Minutes' )
		);

		return $schedules;
	}

	/**
	 * Creates a scheduled action called by wp_cron every 10 minutes 
	 * The class hooks into those calls for reminders and late arrivals
	 */
	public function schedule_events() {
		if ( ! wp_next_scheduled ( 'fspph_cron_jobs_10' ) ) {
			wp_schedule_event( time(), 'ten_minutes', 'fspph_cron_jobs_10' );
		}
	}

	/**
	 * Clears the rtb_cron_job hook so that it's no longer called after the plugin is deactivated
	 */
	public function unschedule_events() {
		wp_clear_scheduled_hook( 'fspph_cron_jobs_10' );
	}
}
} // endif;
