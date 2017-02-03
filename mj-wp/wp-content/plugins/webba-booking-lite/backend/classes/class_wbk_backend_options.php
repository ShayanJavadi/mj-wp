<?php
// Webba Booking options page class
// check if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
require_once  dirname(__FILE__).'/../../common/class_wbk_date_time_utils.php';
require_once  dirname(__FILE__).'/../../common/class_wbk_business_hours.php';
class WBK_Backend_Options extends WBK_Backend_Component {
	public function __construct() {
		//set component-specific properties
		$this->name          = 'wbk-options';
		$this->title         = 'Settings';
		$this->main_template = 'tpl_wbk_backend_options.php';
		$this->capability    = 'manage_options';
		// init settings
		add_action( 'admin_init', array( $this, 'initSettings' ) );
		// init scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueueScripts') );
		// mce plugin
	 	add_filter( 'mce_buttons',  array( $this, 'wbk_mce_add_button' ) );
	 	add_filter( 'mce_external_plugins',  array( $this, 'wbk_mce_add_javascript' ) );
	 	add_filter( 'wp_default_editor', create_function( '', 'return \'tinymce\';' ) );
	 	add_filter( 'tiny_mce_before_init', array( $this, 'customizeEditor' ) );
	}
	public function customizeEditor( $in ) {
		if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'wbk-options' ) {
			$in['remove_linebreaks'] = false;
		 	$in['remove_redundant_brs'] = false;
	 		$in['wpautop'] = false;
	 	}
	 	return $in;
	}
	public function wbk_mce_add_button( $buttons ) {
		if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'wbk-options' ) {
			$buttons[] = 'wbk_service_name_button';
			$buttons[] = 'wbk_customer_name_button';
			$buttons[] = 'wbk_appointment_day_button';
			$buttons[] = 'wbk_appointment_time_button';
			$buttons[] = 'wbk_customer_phone_button';
			$buttons[] = 'wbk_customer_email_button';
			$buttons[] = 'wbk_customer_comment_button';
			$buttons[] = 'wbk_customer_custom_button';
			$buttons[] = 'wbk_items_count';
			$buttons[] = 'wbk_tomorrow_agenda';
			$buttons[] = 'wbk_group_customer';
		}
		return $buttons;
	}
	public function wbk_mce_add_javascript( $plugin_array ) {
		if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'wbk-options' ) {
			$plugin_array['wbk_tinynce'] =  plugins_url( 'js/wbk-tinymce.js', dirname( __FILE__ ) );
		}
		return $plugin_array;
	}
	// init wp settings api objects for options page
	public function initSettings() {
		// general settings section init
		add_settings_section(
	        'wbk_general_settings_section',
	        __( 'General', 'wbk' ),
	        array( $this, 'wbk_general_settings_section_callback'),
	        'wbk-options'
   		);
    	// start of week
		add_settings_field(
	        'wbk_start_of_week',
	        __( 'Week starts on', 'wbk' ),
	        array( $this, 'render_start_of_week'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_start_of_week',
        	array ( $this, 'validate_start_of_week' )
    	);
 		// date format
    	add_settings_field(
	        'wbk_date_format',
	        __( 'Date format', 'wbk' ),
	        array( $this, 'render_date_format'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_date_format',
        	array ( $this, 'validate_date_format' )
    	);
    	// time format
    	add_settings_field(
	        'wbk_time_format',
	        __( 'Time format', 'wbk' ),
	        array( $this, 'render_time_format'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_time_format',
        	array ( $this, 'validate_time_format' )
    	);
    	// timezone
		add_settings_field(
	        'wbk_timezone',
	        __( 'Timezone', 'wbk' ),
	        array( $this, 'render_timezone'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_timezone',
        	array ( $this, 'validate_timezone' )
    	);
    	// phone mask
		add_settings_field(
	        'wbk_phone_mask',
	        __( 'Phone number mask input', 'wbk' ),
	        array( $this, 'render_phone_mask'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_phone_mask',
        	array ( $this, 'validate_phone_mask' )
    	);
    	// phone format
		add_settings_field(
	        'wbk_phone_format',
	        __( 'Phone format', 'wbk' ),
	        array( $this, 'render_phone_format'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_phone_format',
        	array ( $this, 'validate_phone_format' )
    	);
    	// booked slots
		add_settings_field(
	        'wbk_show_booked_slots',
	        __( 'Show booked time slots', 'wbk' ),
	        array( $this, 'render_show_booked_slots'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_show_booked_slots',
        	array ( $this, 'validate_show_booked_slots' )
    	);
    	// auto lock slots
    	add_settings_field(
	        'wbk_appointments_auto_lock',
	        __( 'Auto lock appointments', 'wbk' ),
	        array( $this, 'render_appointments_auto_lock'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_appointments_auto_lock',
        	array ( $this, 'validate_appointments_auto_lock' )
    	);
    	// hide form when booking done
    	add_settings_field(
	        'wbk_hide_from_on_booking',
	        __( 'Hide form on booking', 'wbk' ),
	        array( $this, 'render_hide_from_on_booking'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_hide_from_on_booking',
        	array ( $this, 'validate_hide_from_on_booking' )
    	);    
		// shortcode checking
    	add_settings_field(
	        'wbk_check_short_code',
	        __( 'Check shortcode on booking form initialization', 'wbk' ),
	        array( $this, 'render_check_short_code'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_check_short_code',
        	array ( $this, 'validate_check_short_code' )
    	);        	
		// show cancel button
    	add_settings_field(
	        'wbk_show_cancel_button',
	        __( 'Show cancel button', 'wbk' ),
	        array( $this, 'render_show_cancel_button'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_show_cancel_button',
        	array ( $this, 'validate_show_cancel_button' )
    	);      
		// disable day on all booked
    	add_settings_field(
	        'wbk_disable_day_on_all_booked',
	        __( 'Disable booked dates in calendar', 'wbk' ),
	        array( $this, 'render_disable_day_on_all_booked'),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_disable_day_on_all_booked',
        	array ( $this, 'validate_disable_day_on_all_booked' )
    	);      
    	// holyday settings section init
    	add_settings_section(
	        'wbk_schedule_settings_section',
	        __( 'Holidays', 'wbk' ),
	        array( $this, 'wbk_schedule_settings_section_callback'),
	        'wbk-options'
   		);
    	// holydays
    	add_settings_field(
	        'wbk_holydays',
	        __( 'Holidays', 'wbk' ),
	        array( $this, 'render_holydays' ),
	        'wbk-options',
	        'wbk_schedule_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_holydays',
         	 array ( $this, 'validate_holydays' )
    	);
    	// email settings section init
	 	add_settings_section(
	        'wbk_email_settings_section',
	        __( 'Email notifications', 'wbk' ),
	        array( $this, 'wbk_email_settings_section_callback'),
	        'wbk-options'
   		);
   		add_settings_field(
	        'wbk_email_customer_book_status',
	        __( 'Send customer an email', 'wbk' ),
	        array( $this, 'render_email_customer_book_status' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_customer_book_status',
         	 array ( $this, 'validate_email_customer_book_status' )
    	);
		add_settings_field(
	        'wbk_email_customer_book_subject',
	        __( 'Subject of an email to a customer', 'wbk' ),
	        array( $this, 'render_email_customer_book_subject' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_customer_book_subject',
         	 array ( $this, 'validate_email_customer_book_subject' )
    	);
		add_settings_field(
	        'wbk_email_customer_book_message',
	        __( 'Message to a customer', 'wbk' ),
	        array( $this, 'render_email_customer_book_message' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_customer_book_message',
         	 array ( $this, 'validate_email_customer_book_message' )
    	);
    		    add_settings_field(
	        'wbk_email_secondary_book_status',
	        __( 'Send an email to other customers from the group (if provided)', 'wbk' ),
	        array( $this, 'render_email_secondary_book_status' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_secondary_book_status',
         	 array ( $this, 'validate_email_secondary_book_status' )
    	);
		add_settings_field(
	        'wbk_email_secondary_book_subject',
	        __( 'Subject of an email to a customers from the group', 'wbk' ),
	        array( $this, 'render_email_secondary_book_subject' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_secondary_book_subject',
         	 array ( $this, 'validate_email_secondary_book_subject' )
    	);
		add_settings_field(
	        'wbk_email_secondary_book_message',
	        __( 'Message to a customers from the group', 'wbk' ),
	        array( $this, 'render_email_secondary_book_message' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_secondary_book_message',
         	 array ( $this, 'validate_email_secondary_book_message' )
    	);
   		add_settings_field(
	        'wbk_email_admin_book_status',
	        __( 'Send administrator an email', 'wbk' ),
	        array( $this, 'render_email_admin_book_status' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_admin_book_status',
         	 array ( $this, 'validate_email_admin_book_status' )
    	);
		add_settings_field(
	        'wbk_email_admin_book_subject',
	        __( 'Subject of an email to an administrator', 'wbk' ),
	        array( $this, 'render_email_admin_book_subject' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_admin_book_subject',
         	 array ( $this, 'validate_email_admin_book_subject' )
    	);
		add_settings_field(
	        'wbk_email_admin_book_message',
	        __( 'Message to an administrator', 'wbk' ),
	        array( $this, 'render_email_admin_book_message' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_admin_book_message',
         	 array ( $this, 'validate_email_admin_book_message' )
    	);
	 	add_settings_field(
	        'wbk_email_admin_daily_status',
	        __( 'Send administrator reminders', 'wbk' ),
	        array( $this, 'render_email_admin_daily_status' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_admin_daily_status',
         	 array ( $this, 'validate_email_admin_daily_status' )
    	);
    	//
		add_settings_field(
	        'wbk_email_admin_daily_subject',
	        __( 'Subject of administrator reminders', 'wbk' ),
	        array( $this, 'render_email_admin_daily_subject' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_admin_daily_subject',
         	 array ( $this, 'validate_email_admin_daily_subject' )
    	);
    	add_settings_field(
	        'wbk_email_admin_daily_message',
	        __( 'Administrator reminders message', 'wbk' ),
	        array( $this, 'render_email_admin_daily_message' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_admin_daily_message',
         	 array ( $this, 'validate_email_admin_daily_message' )
    	);
    	// customer daily
		add_settings_field(
	        'wbk_email_customer_daily_status',
	        __( 'Send customer reminders', 'wbk' ),
	        array( $this, 'render_email_customer_daily_status' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_customer_daily_status',
         	 array ( $this, 'validate_email_customer_daily_status' )
    	);
    	//
		add_settings_field(
	        'wbk_email_customer_daily_subject',
	        __( 'Subject of customer reminders', 'wbk' ),
	        array( $this, 'render_email_customer_daily_subject' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_customer_daily_subject',
         	 array ( $this, 'validate_email_customer_daily_subject' )
    	);
    	add_settings_field(
	        'wbk_email_customer_daily_message',
	        __( 'Customer reminders message', 'wbk' ),
	        array( $this, 'render_email_customer_daily_message' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_customer_daily_message',
         	 array ( $this, 'validate_email_customer_daily_message' )
    	);
    	// customer daily end
    	add_settings_field(
	        'wbk_email_admin_daily_time',
	        __( 'Time of a daily reminder', 'wbk' ),
	        array( $this, 'render_email_admin_daily_time' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_admin_daily_time',
         	 array ( $this, 'validate_email_admin_daily_time' )
    	);
		add_settings_field(
	        'wbk_from_name',
	        __( 'From: name', 'wbk' ),
	        array( $this, 'render_from_name' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_from_name',
         	 array ( $this, 'validate_from_name' )
    	);
		add_settings_field(
	        'wbk_from_email',
	        __( 'From: email', 'wbk' ),
	        array( $this, 'render_from_email' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_from_email',
         	 array ( $this, 'validate_from_email' )
    	);
		add_settings_field(
	        'wbk_super_admin_email',
	        __( 'Send copies of service emails to', 'wbk' ),
	        array( $this, 'render_super_admin_email' ),
	        'wbk-options',
	        'wbk_email_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_super_admin_email',
         	 array ( $this, 'validate_super_admin_email' )
    	);

		// mode settings section init
 	 	add_settings_section(
	        'wbk_mode_settings_section',
	        __( 'Mode', 'wbk' ),
	        array( $this, 'wbk_mode_settings_section_callback'),
	        'wbk-options'
   		);
 	 	// mode field
   		add_settings_field(
	        'wbk_mode',
	        __( 'Mode', 'wbk' ),
	        array( $this, 'render_mode' ),
	        'wbk-options',
	        'wbk_mode_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_mode',
         	 array ( $this, 'validate_mode' )
    	);
    	// timeslot time string
   		add_settings_field(
	        'wbk_timeslot_time_string',
	        __( 'Time slot time string', 'wbk' ),
	        array( $this, 'render_timeslot_time_string' ),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_timeslot_time_string',
         	 array ( $this, 'validate_timeslot_time_string' )
    	);
    	// timeslot format
   		add_settings_field(
	        'wbk_timeslot_format',
	        __( 'Time slot format', 'wbk' ),
	        array( $this, 'render_timeslot_format' ),
	        'wbk-options',
	        'wbk_general_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_timeslot_format',
         	 array ( $this, 'validate_timeslot_format' )
    	);
    	// translation settings section
 		add_settings_section(
	        'wbk_translation_settings_section',
	        __( 'Translation', 'wbk' ),
	        array( $this, 'wbk_translation_settings_section_callback'),
	        'wbk-options'
   		);
		add_settings_field(
	        'wbk_service_label',
	        __( 'Select service label', 'wbk' ),
	        array( $this, 'render_service_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_service_label',
         	 array ( $this, 'validate_service_label' )
    	);
		add_settings_field(
	        'wbk_date_extended_label',
	        __( 'Select date label (extended mode)', 'wbk' ),
	        array( $this, 'render_date_extended_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_date_extended_label',
         	 array ( $this, 'validate_date_extended_label' )
    	);
		add_settings_field(
	        'wbk_date_basic_label',
	        __( 'Select date label (basic mode)', 'wbk' ),
	        array( $this, 'render_date_basic_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_date_basic_label',
         	 array ( $this, 'validate_date_basic_label' )
    	);
    	// *** 2.2.8 settings pack
    	add_settings_field(
	        'wbk_date_input_placeholder',
	        __( 'Select date input placeholder', 'wbk' ),
	        array( $this, 'render_date_input_placeholder' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_date_input_placeholder',
         	 array ( $this, 'validate_date_input_placeholder' )
    	);
    	// end 2.2.8 settings pack
		add_settings_field(
	        'wbk_hours_label',
	        __( 'Select hours label', 'wbk' ),
	        array( $this, 'render_hours_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_hours_label',
         	 array ( $this, 'validate_hours_label' )
    	);
		add_settings_field(
	        'wbk_slots_label',
	        __( 'Select time slots label', 'wbk' ),
	        array( $this, 'render_slots_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_slots_label',
         	 array ( $this, 'validate_slots_label' )
    	);
		add_settings_field(
	        'wbk_form_label',
	        __( 'Booking form label', 'wbk' ),
	        array( $this, 'render_form_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_form_label',
         	 array ( $this, 'validate_form_label' )
    	);
 		add_settings_field(
	        'wbk_book_items_quantity_label',
	        __( 'Booking items count label', 'wbk' ),
	        array( $this, 'render_book_items_quantity_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_book_items_quantity_label',
         	 array ( $this, 'validate_book_items_quantity_label' )
    	);
    	// booked slot
 		add_settings_field(
	        'wbk_booked_text',
	        __( 'Booked time slot text', 'wbk' ),
	        array( $this, 'render_booked_text' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_booked_text',
         	 array ( $this, 'validate_booked_text' )
    	);
        // booked slot end
   		// 2.2.8 settings pack
		// *** book ( timeslot )
		add_settings_field(
	        'wbk_book_text_timeslot',
	        __( 'Book button text (time slot)', 'wbk' ),
	        array( $this, 'render_book_text_timeslot' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_book_text_timeslot',
         	 array ( $this, 'validate_book_text_timeslot' )
    	);
		// *** book ( form )
		add_settings_field(
	        'wbk_book_text_form',
	        __( 'Book button text (form)', 'wbk' ),
	        array( $this, 'render_book_text_form' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_book_text_form',
         	 array ( $this, 'validate_book_text_form' )
    	);
   		// *** name
		add_settings_field(
	        'wbk_name_label',
	        __( 'Name label (booking form)', 'wbk' ),
	        array( $this, 'render_name_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_name_label',
         	 array ( $this, 'validate_name_label' )
    	);
   		// *** email
		add_settings_field(
	        'wbk_email_label',
	        __( 'Email label (booking form)', 'wbk' ),
	        array( $this, 'render_email_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_email_label',
         	 array ( $this, 'validate_email_label' )
    	);
    	// *** phone
		add_settings_field(
	        'wbk_phone_label',
	        __( 'Phone label (booking form)', 'wbk' ),
	        array( $this, 'render_phone_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_phone_label',
         	 array ( $this, 'validate_phone_label' )
    	);
		// *** comment
		add_settings_field(
	        'wbk_comment_label',
	        __( 'Comment label (booking form)', 'wbk' ),
	        array( $this, 'render_comment_label' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_comment_label',
         	 array ( $this, 'validate_comment_label' )
    	);
   		// end 2.2.8 settings pack
   		add_settings_field(
	        'wbk_book_thanks_message',
	        __( 'Booking done message', 'wbk' ),
	        array( $this, 'render_book_thanks_message' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_book_not_found_message',
         	 array ( $this, 'validate_book_not_found_message' )
    	);
   		add_settings_field(
	        'wbk_book_not_found_message',
	        __( 'Time slots not found message', 'wbk' ),
	        array( $this, 'render_book_not_found_message' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_book_thanks_message',
         	 array ( $this, 'validate_book_thanks_message' )
    	);
		add_settings_field(
	        'wbk_payment_pay_with_paypal_btn_text',
	        __( 'PayPal payment button text', 'wbk' ),
	        array( $this, 'render_payment_pay_with_paypal_btn_text' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_pay_with_paypal_btn_text',
         	 array ( $this, 'validate_payment_pay_with_paypal_btn_text' )
    	);
	    add_settings_field(
	        'wbk_payment_pay_with_cc_btn_text',
	        __( 'Credit card payment button text', 'wbk' ),
	        array( $this, 'render_payment_pay_with_cc_btn_text' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_pay_with_cc_btn_text',
         	 array ( $this, 'validate_payment_pay_with_cc_btn_text' )
    	);
	    add_settings_field(
	        'wbk_payment_details_title',
	        __( 'Payment details title', 'wbk' ),
	        array( $this, 'render_payment_details_title' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_details_title',
         	 array ( $this, 'validate_payment_details_title' )
    	);
	    add_settings_field(
	        'wbk_payment_item_name',
	        __( 'Payment item name', 'wbk' ),
	        array( $this, 'render_payment_item_name' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_item_name',
         	 array ( $this, 'validate_payment_item_name' )
    	);
		add_settings_field(
	        'wbk_payment_price_format',
	        __( 'Price format', 'wbk' ),
	        array( $this, 'render_payment_price_format' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_price_format',
         	 array ( $this, 'validate_payment_price_format' )
    	);
		add_settings_field(
	        'wbk_payment_subtotal_title',
	        __( 'Subtotal title', 'wbk' ),
	        array( $this, 'render_payment_subtotal_title' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_subtotal_title',
         	 array ( $this, 'validate_payment_subtotal_title' )
    	);
    	add_settings_field(
	        'wbk_payment_total_title',
	        __( 'Total title', 'wbk' ),
	        array( $this, 'render_payment_total_title' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_total_title',
         	 array ( $this, 'validate_payment_total_title' )
    	);
    	add_settings_field(
	        'wbk_payment_approve_text',
	        __( 'Approve payment', 'wbk' ),
	        array( $this, 'render_payment_approve_text' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_approve_text',
         	 array ( $this, 'validate_payment_approve_text' )
    	);
		add_settings_field(
	        'wbk_payment_result_title',
	        __( 'Payment result title', 'wbk' ),
	        array( $this, 'render_payment_result_title' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_result_title',
         	 array ( $this, 'validate_payment_result_title' )
    	);
		add_settings_field(
	        'wbk_payment_success_message',
	        __( 'Payment result success message', 'wbk' ),
	        array( $this, 'render_payment_success_message' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_success_message',
         	 array ( $this, 'validate_payment_success_message' )
    	);
		add_settings_field(
	        'wbk_payment_cancel_message',
	        __( 'Payment result cancel message', 'wbk' ),
	        array( $this, 'render_payment_cancel_message' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_payment_cancel_message',
         	 array ( $this, 'validate_payment_cancel_message' )
    	);
		add_settings_field(
	        'wbk_cancel_button_text',
	        __( 'Booking cancel button text', 'wbk' ),
	        array( $this, 'render_cancel_button_text' ),
	        'wbk-options',
	        'wbk_translation_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_cancel_button_text',
         	 array ( $this, 'validate_cancel_button_text' )
    	);

		// paypal settings section init ******************************************************************************
 	 	add_settings_section(
	        'wbk_paypal_settings_section',
	        __( 'PayPal', 'wbk' ),
	        array( $this, 'wbk_paypal_settings_section_callback'),
	        'wbk-options'
   		);
   		// mode 
		add_settings_field(
	        'wbk_paypal_mode',
	        __( 'PayPal mode', 'wbk' ),
	        array( $this, 'render_paypal_mode' ),
	        'wbk-options',
	        'wbk_paypal_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_paypal_mode',
         	 array ( $this, 'validate_paypal_mode' )
    	);
		add_settings_field(
	        'wbk_paypal_sandbox_clientid',
	        __( 'PayPal Sandbox ClientID', 'wbk' ),
	        array( $this, 'render_paypal_sandbox_clientid' ),
	        'wbk-options',
	        'wbk_paypal_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_paypal_sandbox_clientid',
         	 array ( $this, 'validate_paypal_sandbox_clientid' )
    	);

		add_settings_field(
	        'wbk_paypal_sandbox_secret',
	        __( 'PayPal Sandbox Secret', 'wbk' ),
	        array( $this, 'render_paypal_sandbox_secret' ),
	        'wbk-options',
	        'wbk_paypal_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_paypal_sandbox_secret',
         	 array ( $this, 'validate_paypal_sandbox_secret' )
    	);
        add_settings_field(
	        'wbk_paypal_live_clientid',
	        __( 'PayPal Live ClientID', 'wbk' ),
	        array( $this, 'render_paypal_live_clientid' ),
	        'wbk-options',
	        'wbk_paypal_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_paypal_live_clientid',
         	 array ( $this, 'validate_paypal_live_clientid' )
    	);

		add_settings_field(
	        'wbk_paypal_live_secret',
	        __( 'PayPal Live Secret', 'wbk' ),
	        array( $this, 'render_paypal_live_secret' ),
	        'wbk-options',
	        'wbk_paypal_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_paypal_live_secret',
         	 array ( $this, 'validate_paypal_live_secret' )
    	);
		add_settings_field(
	        'wbk_paypal_currency',
	        __( 'PayPal currency', 'wbk' ),
	        array( $this, 'render_paypal_currency' ),
	        'wbk-options',
	        'wbk_paypal_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_paypal_currency',
         	 array ( $this, 'validate_paypal_currency' )
    	);
		add_settings_field(
	        'wbk_paypal_tax',
	        __( 'Tax', 'wbk' ),
	        array( $this, 'render_paypal_tax' ),
	        'wbk-options',
	        'wbk_paypal_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_paypal_tax',
         	 array ( $this, 'validate_paypal_tax' )
    	);
		add_settings_field(
	        'wbk_paypal_hide_address',
	        __( 'Hide address', 'wbk' ),
	        array( $this, 'render_paypal_hide_address' ),
	        'wbk-options',
	        'wbk_paypal_settings_section',
	        array()
    	);
    	register_setting(
        	'wbk_options',
        	'wbk_paypal_hide_address',
         	 array ( $this, 'validate_paypal_hide_address' )
    	);
	 

		// paypal settings section init end ******************************************************************************
		 
	



	}
	// init styles and scripts
	public function enqueueScripts() {
 		if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'wbk-options' ) {
	        wp_enqueue_script( 'jquery-plugin', plugins_url( 'js/jquery.plugin.js', dirname( __FILE__ ) ), array( 'jquery' ) );
	        wp_enqueue_script( 'multidate-picker', plugins_url( 'js/jquery.datepick.min.js', dirname( __FILE__ ) ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ) );
	        wp_enqueue_script( 'wbk-options', plugins_url( 'js/wbk-options.js', dirname( __FILE__ ) ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-ui-tabs' ) );
            wp_enqueue_script( 'wbk-minicolors', plugins_url( 'js/jquery.minicolors.min.js', dirname( __FILE__ ) ), array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ) );
			wp_enqueue_style( 'wbk-datepicker-css', plugins_url( 'css/jquery.datepick.css', dirname( __FILE__ ) )  );
    	}
	}
    // general settings section callback
	public function wbk_general_settings_section_callback( $arg ) {
	}
    // schedule settings section callback
	public function wbk_schedule_settings_section_callback( $arg ) {
	}
    // email settings section callback
	public function wbk_email_settings_section_callback( $arg ) {
	}
    // appearance  settings section callback
	public function wbk_mode_settings_section_callback( $arg ) {
	}
	// appearance  settings section callback
	public function wbk_translation_settings_section_callback( $arg ) {
	}
	// activation settings section callback
	public function wbk_activation_settings_section_callback( $arg ) {
	}
	// paypal settings section callback
	public function wbk_paypal_settings_section_callback( $arg ) {
	}
	// render start of week
	public function render_start_of_week() {
		$html = '<select id="wbk_start_of_week" name="wbk_start_of_week">
				    <option '.selected(  get_option('wbk_start_of_week'), 'sunday', false ).' value="sunday">'.__( 'Sunday', 'wbk' ).'</option>
				    <option '.selected(  get_option('wbk_start_of_week'), 'monday', false ).' value="monday">'.__( 'Monday', 'wbk' ).'</option>
				    <option '.selected(  get_option('wbk_start_of_week'), 'wordpress', false ).' value="wordpress">'.__( 'Wordpress default', 'wbk' ).'</option>
  				</select>';
  		echo $html;
	}
	// validate start of week
	public function validate_start_of_week( $input ) {
		$input = trim( $input );
		if ( $input != 'sunday' && $input != 'monday' && $input != 'wordpress' ) {
			add_settings_error( 'wbk_start_of_week', 'wbk_start_of_week_error', __( 'Incorrect start of week', 'wbk' ) );
			return 'monday';
		} else {
			return $input;
		}
	}
	// render date format
	public function render_date_format() {
		$value = get_option( 'wbk_date_format' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_date_format" name="wbk_date_format" value="'.$value.'" >' . 
		        '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank" ><span class="dashicons dashicons-editor-help"></span></a>';
  		$html .= '<p class="description">' . __( 'Leave empty to use Wordpress Date Format. ', 'wbk' ) .  '</p>';
  		echo $html;
	}
	// validate date format
	public function validate_date_format( $input ) {
		$input = trim( $input );
		if ( strlen( $input ) > 20 ) {
			$input = substr( $input, 0, 19 );
			add_settings_error( 'wbk_date_format', 'wbk_date_format_error', __( 'Date format updated', 'wbk' ), 'updated' );
		}
		$input = sanitize_text_field( $input );
		return $input;
	}
	// render time format
	public function render_time_format() {
		$value = get_option( 'wbk_time_format' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_time_format" name="wbk_time_format" value="'.$value.'" >' . '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank" ><span class="dashicons dashicons-editor-help"></span></a>';
  		$html .= '<p class="description">' . __( 'Leave empty to use Wordpress Time Format. ', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate time format
	public function validate_time_format( $input ) {
		$input = trim( $input );
		if ( strlen( $input ) > 20 ) {
			$input = substr( $input, 0, 19 );
			add_settings_error( 'wbk_time_format', 'wbk_time_format_error', __( 'Time format updated', 'wbk' ), 'updated' );
		}
		$input = sanitize_text_field( $input );
		return $input;
	}
	// render phone mask
	public function render_phone_mask() {
		$value = get_option( 'wbk_phone_mask' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_phone_mask" name="wbk_phone_mask">
				    <option ' . selected(  $value, 'enabled', false ) . ' value="enabled">' . __( 'Enabled', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'disabled', false ) . ' value="disabled">' . __( 'Disabled', 'wbk' ).'</option>
   				 </select>';
  		echo $html;
	}
	// validate phone mask
	public function validate_phone_mask( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'enabled' && $value != 'disabled' ){
			$value = 'enabled';
		}
 		return $input;
	}
	// render phone format
	public function render_phone_format() {
		$value = get_option( 'wbk_phone_format' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_phone_format" name="wbk_phone_format" value="'.$value.'" >' . '<a href="http://digitalbush.com/projects/masked-input-plugin/" target="_blank" ><span class="dashicons dashicons-editor-help"></span></a>';
  		$html .= '<p class="description">' . __( 'a - Represents an alpha character (A-Z,a-z), 9 - Represents a numeric character (0-9), * - Represents an alphanumeric character (A-Z,a-z,0-9)', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate phone format
	public function validate_phone_format( $input ) {
		$input = trim( $input );
		$input = sanitize_text_field( $input );
 		return $input;
	}
	// render timezone
	public function render_timezone() {
		$value = get_option( 'wbk_timezone' );
		$arr_timezones = timezone_identifiers_list();
		$html = '<select id="wbk_timezone" name="wbk_timezone" >';
		foreach ( $arr_timezones as $timezone ) {
			if ( $timezone == $value ) {
				$selected = 'selected';
			} else {
				$selected = '';
			}
			$html .= "<option $selected value=\"$timezone\">$timezone</option>";
		}
		$html .= '</select>';
		echo $html;
	}
	// validate timezone
	public function validate_timezone( $input ) {
		return $input;
	}
	// render holydays
	public function render_holydays() {
 		$value = get_option( 'wbk_holydays' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_holydays" name="wbk_holydays" value="'.$value.'" >';
		$html .= '<p class="description">' . __( 'Pick a holidays. This option should be used to set only holidays. Do not use it to set weekend (there is a Business hours parameter of a services for this porpose)', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate holydays
	public function validate_holydays( $input ) {
		return $input;
	}
	// render email to customer
	public function render_email_customer_book_status() {
 		$value = get_option( 'wbk_email_customer_book_status' );
		$html = '<input type="checkbox" ' . checked( 'true', $value, false ) . ' id="wbk_email_customer_book_status" name="wbk_email_customer_book_status" value="true" >';
		$html .= '<label for="wbk_email_customer_book_status">' . __( 'Check if you\'d like to send customer an email', 'wbk' ) . '</a>';
  		echo $html;
	}
	// validate email to customer
	public function validate_email_customer_book_status( $input ) {
        if ( $input != 'true'  && $input != '' ) {
			$input = '';
			add_settings_error( 'wbk_email_customer_book_status', 'wbk_email_customer_book_status_error', __( 'Email status updated', 'wbk' ), 'updated' );
		}
		return $input;
	}
	// render email to customer message
	public function render_email_customer_book_message() {
 		$value = get_option( 'wbk_email_customer_book_message' );
 		$args = array(
            	'media_buttons' => false,
            	'editor_height' => 300
            );
 		echo '<a class="button wbk_email_editor_toggle">' . __( 'Toggle editor', 'wbk' ) . '</a><div class="wbk_email_editor_wrap" style="display:none;">';
		wp_editor( $value, 'wbk_email_customer_book_message', $args );
		echo '</div>';
 	}
	// validate email to customer message
	public function validate_email_customer_book_message( $input ) {
		return $input;
	}
	// render customer email subject
	public function render_email_customer_book_subject() {
		$value = get_option( 'wbk_email_customer_book_subject' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_email_customer_book_subject" name="wbk_email_customer_book_subject" value="'.$value.'" >';
  		echo $html;
	}
	// validate email to customer message
	public function validate_email_customer_book_subject( $input ) {
		$input = sanitize_text_field( $input );
  		if ( !WBK_Validator::checkStringSize( $input, 1, 100 ) ) {
			add_settings_error( 'wbk_email_customer_book_subject', 'wbk_email_customer_book_subject_error', __( 'Customer email subject is wrong', 'wbk' ), 'error' );
		} else {
			return $input;
		}
	}
	// render email to secondary
	public function render_email_secondary_book_status() {
 		$value = get_option( 'wbk_email_secondary_book_status' );
		$html = '<input type="checkbox" ' . checked( 'true', $value, false ) . ' id="wbk_email_secondary_book_status" name="wbk_email_secondary_book_status" value="true" >';
		$html .= '<label for="wbk_email_secondary_book_status">' . __( 'Check if you\'d like to send an email to a customers from the group', 'wbk' ) . '</a>';
  		echo $html;
	}
	// validate email to secondary
	public function validate_email_secondary_book_status( $input ) {
        if ( $input != 'true'  && $input != '' ) {
			$input = '';
			add_settings_error( 'wbk_email_secondary_book_status', 'wbk_email_secondary_book_status_error', __( 'Email status updated', 'wbk' ), 'updated' );
		}
		return $input;
	}
	// render email to secondary message
	public function render_email_secondary_book_message() {
 		$value = get_option( 'wbk_email_secondary_book_message' );
 		$args = array(
            	'media_buttons' => false,
            	'editor_height' => 300
            );
		echo '<a class="button wbk_email_editor_toggle">' . __( 'Toggle editor', 'wbk' ) . '</a><div class="wbk_email_editor_wrap" style="display:none;">';
		wp_editor( $value, 'wbk_email_secondary_book_message', $args );
		echo '</div>';

 	}
	// validate email to secondary message
	public function validate_email_secondary_book_message( $input ) {
		return $input;
	}
	// render secondary email subject
	public function render_email_secondary_book_subject() {
		$value = get_option( 'wbk_email_secondary_book_subject' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_email_secondary_book_subject" name="wbk_email_secondary_book_subject" value="'.$value.'" >';
  		echo $html;
	}
	// validate email to secondary message
	public function validate_email_secondary_book_subject( $input ) {
		$input = sanitize_text_field( $input );
  		if ( !WBK_Validator::checkStringSize( $input, 1, 100 ) ) {
		} else {
			return $input;
		}
	}
	// render admin email subject
	public function render_email_admin_book_subject() {
		$value = get_option( 'wbk_email_admin_book_subject' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_email_admin_book_subject" name="wbk_email_admin_book_subject" value="'.$value.'" >';
  		echo $html;
	}
	// validate email to admin message
	public function validate_email_admin_book_subject( $input ) {
		$input = sanitize_text_field( $input );
  		if ( !WBK_Validator::checkStringSize( $input, 1, 100 ) ) {
			add_settings_error( 'wbk_email_admin_book_subject', 'wbk_email_admin_book_subject_error', __( 'Administrator email subject is wrong', 'wbk' ), 'error' );
		} else {
			return $input;
		}
	}
	 // render admin daily subject
	public function render_email_admin_daily_subject() {
		$value = get_option( 'wbk_email_admin_daily_subject' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_email_admin_daily_subject" name="wbk_email_admin_daily_subject" value="'.$value.'" >';
  		echo $html;
	}
	// validate email to admin message
	public function validate_email_admin_daily_subject( $input ) {
		$input = sanitize_text_field( $input );
		return $input;
	}
 	// render customer daily subject
	public function render_email_customer_daily_subject() {
		$value = get_option( 'wbk_email_customer_daily_subject' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_email_customer_daily_subject" name="wbk_email_customer_daily_subject" value="'.$value.'" >';
  		echo $html;
	}
	// validate email to customer message
	public function validate_email_customer_daily_subject( $input ) {
		$input = sanitize_text_field( $input );
		return $input;		 
	}
 	// render admin daily time
	public function render_email_admin_daily_time() {
		$value = get_option( 'wbk_email_admin_daily_time' );
		$value = sanitize_text_field( $value );
		$html = '<select  id="wbk_email_admin_daily_time" name="wbk_email_admin_daily_time" >';
		$format = WBK_Date_Time_Utils::getTimeFormat();
		for( $i = 0; $i < 86400; $i +=  3600 ){
			$html .= '<option  ' . selected(  $value, $i, false ) . '  value="'.$i.'">' . date_i18n( $format, $i ) . '</option>';
		}
		$html .= '</select>';
		$html .= '<p class="description">' . __( 'Current local time:', 'wbk' ) . ' ' . current_time( $format, 0 ) . '</p>';
  		echo $html;
	}
	// validate email to admin message
	public function validate_email_admin_daily_time( $input ) {
		$input = sanitize_text_field( $input );
  		if ( !WBK_Validator::checkStringSize( $input, 1, 100 ) ) {
			add_settings_error( 'wbk_email_admin_daily_time', 'wbk_email_admin_daily_time_error', __( 'Administrator daily email time is wrong', 'wbk' ), 'error' );
		} else {
			return $input;
		}
	}
 	// render email to admin
	public function render_email_admin_book_status() {
 		$value = get_option( 'wbk_email_admin_book_status' );
		$html = '<input type="checkbox" ' . checked( 'true', $value, false ) . ' id="wbk_email_admin_book_status" name="wbk_email_admin_book_status" value="true" >';
		$html .= '<label for="wbk_email_admin_book_status">' . __( 'Check if you\'d like to send administrator an email', 'wbk' ) . '</a>';
  		echo $html;
	}
	// validate email to admin
	public function validate_email_admin_book_status( $input ) {
        if ( $input != 'true'  && $input != '' ) {
			$input = '';
			add_settings_error( 'wbk_email_admin_book_status', 'wbk_email_admin_book_status_error', __( 'Email status updated', 'wbk' ), 'updated' );
		}
		return $input;
	}
 	// render email to admin daily
	public function render_email_admin_daily_status() {
 		$value = get_option( 'wbk_email_admin_daily_status' );
		$html = '<input type="checkbox" ' . checked( 'true', $value, false ) . ' id="wbk_email_admin_daily_status" name="wbk_email_admin_daily_status" value="true" >';
		$html .= '<label for="wbk_email_admin_daily_status">' . __( 'Check if you\'d like to send reminders to administrator', 'wbk' ) . '</a>';
  		echo $html;
	}
 	// validate email to admin
	public function validate_email_admin_daily_status( $input ) {
        if ( $input != 'true'  && $input != '' ) {
			$input = '';
			add_settings_error( 'wbk_email_admin_daily_status', 'wbk_email_admin_daily_status_error', __( 'Email status updated', 'wbk' ), 'updated' );
		}
		return $input;
	}
	// render email to customer daily
	public function render_email_customer_daily_status() {
 		$value = get_option( 'wbk_email_customer_daily_status' );
		$html = '<input type="checkbox" ' . checked( 'true', $value, false ) . ' id="wbk_email_customer_daily_status" name="wbk_email_customer_daily_status" value="true" >';
		$html .= '<label for="wbk_email_customer_daily_status">' . __( 'Check if you\'d like to send reminders to customer', 'wbk' ) . '</a>';
  		echo $html;
	}
 	// validate email to customer
	public function validate_email_customer_daily_status( $input ) {
        if ( $input != 'true'  && $input != '' ) {
			$input = '';
			add_settings_error( 'wbk_email_customer_daily_status', 'wbk_email_customer_daily_status_error', __( 'Email status updated', 'wbk' ), 'updated' );
		}
		return $input;
	}
	// render email to admin message
	public function render_email_admin_book_message() {
 		$value = get_option( 'wbk_email_admin_book_message' );
 		$args = array(
            	'media_buttons' => false,
            	'editor_height' => 300
            );
	
		echo '<a class="button wbk_email_editor_toggle">' . __( 'Toggle editor', 'wbk' ) . '</a><div class="wbk_email_editor_wrap" style="display:none;">';
		wp_editor( $value, 'wbk_email_admin_book_message', $args );
		echo '</div>';

 	}
	// validate email to admin nessage
	public function validate_email_admin_book_message( $input ) {
		return $input;
	}
	// render email to admin  daily message
	public function render_email_admin_daily_message() {
 		$value = get_option( 'wbk_email_admin_daily_message' );
 		$args = array(
            	'media_buttons' => false,
            	'editor_height' => 300
            );
		
		echo '<a class="button wbk_email_editor_toggle">' . __( 'Toggle editor', 'wbk' ) . '</a><div class="wbk_email_editor_wrap" style="display:none;">';
		wp_editor( $value, 'wbk_email_admin_daily_message', $args );
		echo '</div>';
 	}
	// validate email to admin daily nessage
	public function validate_email_admin_daily_message( $input ) {
		return $input;
	}
	// render email to customer  daily message
	public function render_email_customer_daily_message() {
 		$value = get_option( 'wbk_email_customer_daily_message' );
 		$args = array(
            	'media_buttons' => false,
            	'editor_height' => 300
            );
		
		echo '<a class="button wbk_email_editor_toggle">' . __( 'Toggle editor', 'wbk' ) . '</a><div class="wbk_email_editor_wrap" style="display:none;">';
		wp_editor( $value, 'wbk_email_customer_daily_message', $args );
		echo '</div>';
 	}
	// validate email to customer daily nessage
	public function validate_email_customer_daily_message( $input ) {
		return $input;
	}
	// render from email
	public function render_from_email() {
		$value = get_option( 'wbk_from_email' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_from_email" name="wbk_from_email" value="'.$value.'" >';
  		echo $html;
	}
	// validate from email
	public function validate_from_email( $input ) {
  		if ( !WBK_Validator::checkEmail( $input ) ) {
			add_settings_error( 'wbk_from_email', 'wbk_from_email_error', __( '"From: email" is wrong', 'wbk' ), 'error' );
		} else {
			return $input;
		}
	}
	// render super admin email
	public function render_super_admin_email() {
		$value = get_option( 'wbk_super_admin_email' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_super_admin_email" name="wbk_super_admin_email" value="'.$value.'" >';
  		echo $html;
	}
	// validate super admin email
	public function validate_super_admin_email( $input ) {
		$input = trim( $input );
  		if ( !WBK_Validator::checkEmail( $input ) && $input != '' ) {
			add_settings_error( 'wbk_super_admin_email', 'wbk_super_admin_email_error', __( 'Incorrect value for "Send copies of service emails to" parameter', 'wbk' ), 'error' );
		} else {
			return $input;
		}
	}

	// render name from
	public function render_from_name() {
		$value = get_option( 'wbk_from_name' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_from_name" name="wbk_from_name" value="'.$value.'" >';
  		echo $html;
	}
	// validate from name
	public function validate_from_name( $input ) {
		$input = sanitize_text_field( $input );
  		if ( !WBK_Validator::checkStringSize( $input, 1, 100 ) ) {
			add_settings_error( 'wbk_from_name', 'wbk_from_name_error', __( '"From: name" is wrong', 'wbk' ), 'error' );
		} else {
			return $input;
		}
	}
	// render mode
	public function render_mode() {
		$value = get_option( 'wbk_mode', 'extended' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_mode" name="wbk_mode">
				    <option ' . selected(  $value, 'extended', false ) . ' value="extended">' . __( 'Extended', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'simple', false ) . ' value="simple">' . __( 'Basic', 'wbk' ).'</option>
   				 </select>';
  		echo $html;
	}
	// validate mode
	public function validate_mode( $input ) {
		return $input;
	}
	// render timeslot time string
	public function render_timeslot_time_string() {
		$value = get_option( 'wbk_timeslot_time_string', 'start' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_timeslot_time_string" name="wbk_timeslot_time_string">
				    <option ' . selected(  $value, 'start', false ) . ' value="start">' . __( 'Start', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'start_end', false ) . ' value="start_end">' . __( 'Start', 'wbk' ) . ' - ' . __( 'end', 'wbk' ) .'</option>
   				 </select>';
  		echo $html;
	}
	// validate timeslot time string
	public function validate_timeslot_time_string( $input ) {
		return $input;
	}
	// render timeslot format
	public function render_timeslot_format() {
		$value = get_option( 'wbk_timeslot_format', 'detailed' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_timeslot_format" name="wbk_timeslot_format">
				    <option ' . selected(  $value, 'detailed', false ) . ' value="detailed">' . __( 'Show details and BOOK button', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'time_only', false ) . ' value="time_only">' . __( 'Show time button only', 'wbk' )  . '</option>
   				 </select>';
  		echo $html;
	}
	// validate timeslot format
	public function validate_timeslot_format( $input ) {
		return $input;
	}
	// render service label
	public function render_service_label() {
		$value = get_option( 'wbk_service_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_service_label" name="wbk_service_label" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Service frontend label', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate service label
	public function validate_service_label( $input ) {
		return sanitize_text_field( $input );
	}
	// render date extended label
	public function render_date_extended_label() {
		$value = get_option( 'wbk_date_extended_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_date_extended_label" name="wbk_date_extended_label" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Date frontend label', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate date extended label
	public function validate_date_extended_label( $input ) {
		return  sanitize_text_field( $input );
	}
	// render date basic label
	public function render_date_basic_label() {
		$value = get_option( 'wbk_date_basic_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_date_basic_label" name="wbk_date_basic_label" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Date frontend label', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate date basic label
	public function validate_date_basic_label( $input ) {
		return  sanitize_text_field( $input );
	}
	// render hours label
	public function render_hours_label() {
		$value = get_option( 'wbk_hours_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_hours_label" name="wbk_hours_label" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Hours frontend label', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate hours label
	public function validate_hours_label( $input ) {
		return  sanitize_text_field( $input );
	}
	// render slots label
	public function render_slots_label() {
		$value = get_option( 'wbk_slots_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_slots_label" name="wbk_slots_label" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Time slots frontend label', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate slots label
	public function validate_slots_label( $input ) {
		return  sanitize_text_field( $input );
	}
	// render form label
	public function render_form_label() {
		$value = get_option( 'wbk_form_label', '' );
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$value = wp_kses( $value, $allowed_tags );
		$value = htmlentities($value);
		$html = '<input type="text" id="wbk_form_label" name="wbk_form_label" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Message before the booking form', 'wbk' ) . '</p>';
		$html .= '<p class="description">' . __( 'Allowed HTML tags', 'wbk' ) . ': strong, em, b, i , br, p, a</p>';
		$html .= '<p class="description">' . __( 'Available placeholders', 'wbk' ) . ': #service, #date, #time.' . '</p>';
  		echo $html;
	}
	// validate form label
	public function validate_form_label( $input ) {
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$input =  wp_kses( $input, $allowed_tags );
		return  $input;
	}
	// render booked text
	public function render_booked_text() {
		$value = get_option( 'wbk_booked_text', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_booked_text" name="wbk_booked_text" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Text on booked time slot. Available placeholders: #username, #time.', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate booked text
	public function validate_booked_text( $input ) {
		return  sanitize_text_field( $input );
	}
	// 2.2.8 settings pack
	// render book text (timeslot)
	public function render_book_text_timeslot() {
		$value = get_option( 'wbk_book_text_timeslot', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_book_text_timeslot" name="wbk_book_text_timeslot" value="'.$value.'" >';  		 
  		echo $html;
	}
	// validate book text (timeslot)
	public function validate_book_text_timeslot( $input ) {
		return  sanitize_text_field( $input );
	}
	// render book text (form)
	public function render_book_text_form() {
		$value = get_option( 'wbk_book_text_form', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_book_text_form" name="wbk_book_text_form" value="'.$value.'" >';  		 
  		echo $html;
	}
	// validate book text (form)
	public function validate_book_text_form( $input ) {
		return  sanitize_text_field( $input );
	}
	// render name label 
	public function render_name_label() {
		$value = get_option( 'wbk_name_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_name_label" name="wbk_name_label" value="'.$value.'" >';  		 
  		echo $html;
	}
	// validate name label
	public function validate_name_label( $input ) {
		return  sanitize_text_field( $input );
	}
	// render email label 
	public function render_email_label() {
		$value = get_option( 'wbk_email_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_email_label" name="wbk_email_label" value="'.$value.'" >';  		 
  		echo $html;
	}
	// validate email label
	public function validate_email_label( $input ) {
		return  sanitize_text_field( $input );
	}
	// render email label 
	public function render_date_input_placeholder() {
		$value = get_option( 'wbk_date_input_placeholder', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_date_input_placeholder" name="wbk_date_input_placeholder" value="'.$value.'" >';  		 
  		echo $html;
	}
	// validate email label
	public function validate_date_input_placeholder( $input ) {
		return  sanitize_text_field( $input );
	}


	
	// render phone label 
	public function render_phone_label() {
		$value = get_option( 'wbk_phone_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_phone_label" name="wbk_phone_label" value="'.$value.'" >';  		 
  		echo $html;
	}
	// validate phone label
	public function validate_phone_label( $input ) {
		return  sanitize_text_field( $input );
	}
	// render comment label 
	public function render_comment_label() {
		$value = get_option( 'wbk_comment_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_comment_label" name="wbk_comment_label" value="'.$value.'" >';  		 
  		echo $html;
	}
	// validate comment label
	public function validate_comment_label( $input ) {
		return  sanitize_text_field( $input );
	}
	// end 2.2.8 settings pack


	// render quantiy label
	public function render_book_items_quantity_label() {
		$value = get_option( 'wbk_book_items_quantity_label', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_book_items_quantity_label" name="wbk_book_items_quantity_label" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Booking items count frontend label', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate quantity label
	public function validate_book_items_quantity_label( $input ) {
		return  sanitize_text_field( $input );
	}

	// render booking cancel button text
	public function render_cancel_button_text() {
		$value = get_option( 'wbk_cancel_button_text', __( 'Cancel', 'wbk' ) );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_cancel_button_text" name="wbk_cancel_button_text" value="'.$value.'" >';
  		echo $html;
	}
	// validate booking cancel button text
	public function validate_cancel_button_text( $input ) {
		return  sanitize_text_field( $input );
	}

	// render pay with paypal button text
	public function render_payment_pay_with_paypal_btn_text() {
		$value = get_option( 'wbk_payment_pay_with_paypal_btn_text', __( 'Pay now with PayPal', 'wbk' )  );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_pay_with_paypal_btn_text" name="wbk_payment_pay_with_paypal_btn_text" value="'.$value.'" >';
  		echo $html;
	}
	// validate pay with paypal button text
	public function validate_payment_pay_with_paypal_btn_text( $input ) {
		return  sanitize_text_field( $input );
	}
	// render pay with cc button text
	public function render_payment_pay_with_cc_btn_text() {
		$value = get_option( 'wbk_payment_pay_with_cc_btn_text', __( 'Pay now with credit card', 'wbk' )  );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_pay_with_cc_btn_text" name="wbk_payment_pay_with_cc_btn_text" value="'.$value.'" >';
  		echo $html;
	}
	// validate pay with cc button text
	public function validate_payment_pay_with_cc_btn_text( $input ) {
		return  sanitize_text_field( $input );
	}
	// render payment details title
	public function render_payment_details_title() {
		$value = get_option( 'wbk_payment_details_title', __( 'Payment details', 'wbk' )  );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_details_title" name="wbk_payment_details_title" value="'.$value.'" >';
  		echo $html;
	}
	// validate payment details title
	public function validate_payment_details_title( $input ) {
		return  sanitize_text_field( $input );
	}
	// render payment item name
	public function render_payment_item_name() {
		$value = get_option( 'wbk_payment_item_name', '#service' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_item_name" name="wbk_payment_item_name" value="'.$value.'" >';
		$html .= '<p class="description">' . __( 'Available placeholders: #service, #date, #time, #id.', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate payment item name
	public function validate_payment_item_name( $input ) {
		return  sanitize_text_field( $input );
	}
	// render payment price format
	public function render_payment_price_format() {
		$value = get_option( 'wbk_payment_price_format', '$#price' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_price_format" name="wbk_payment_price_format" value="'.$value.'" >';
		$html .= '<p class="description">' . __( 'Required placeholder: #price.', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate payment price format
	public function validate_payment_price_format( $input ) {
		return  sanitize_text_field( $input );
	}
	// render subtotal title
	public function render_payment_subtotal_title() {
		$value = get_option( 'wbk_payment_subtotal_title', 'Subtotal' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_subtotal_title" name="wbk_payment_subtotal_title" value="'.$value.'" >';
  		echo $html;
	}
	// validate payment subtotal title
	public function validate_payment_subtotal_title( $input ) {
		return  sanitize_text_field( $input );
	}
	// render total title
	public function render_payment_total_title() {
		$value = get_option( 'wbk_payment_total_title', 'Total' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_total_title" name="wbk_payment_total_title" value="'.$value.'" >';
  		echo $html;
	}
	// validate payment total title
	public function validate_payment_total_title( $input ) {
		return  sanitize_text_field( $input );
	}
    // render approve payment text
	public function render_payment_approve_text() {
		$value =  get_option( 'wbk_payment_approve_text', __( 'Approve payment', 'wbk' ) );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_approve_text" name="wbk_payment_approve_text" value="'.$value.'" >';
  		echo $html;
	}
	// validate approve payment text
	public function validate_payment_approve_text( $input ) {
		return  sanitize_text_field( $input );
	}
	 // render payment result title
	public function render_payment_result_title() {
		$value = get_option( 'wbk_payment_result_title', __( 'Payment status', 'wbk' ) );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_payment_result_title" name="wbk_payment_result_title" value="'.$value.'" >';
  		echo $html;
	}
	// validate payment result title
	public function validate_payment_result_title( $input ) {
		return  sanitize_text_field( $input );
	}
	// render thanks message
	public function render_book_thanks_message() {
		$value = get_option( 'wbk_book_thanks_message', '' );
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$value = wp_kses( $value, $allowed_tags );
		$value = htmlentities($value);
		$html = '<input type="text" id="wbk_book_thanks_message" name="wbk_book_thanks_message" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Booking done message', 'wbk' ) . '</p>';
		$html .= '<p class="description">' . __( 'Allowed HTML tags', 'wbk' ) . ': strong, em, b, i , br, p, a</p>';
  		echo $html;
	}
	// validate thanks message
	public function validate_book_thanks_message( $input ) {
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$input =  wp_kses( $input, $allowed_tags );
		return  $input;
	}
	// render not found message
	public function render_book_not_found_message() {
		$value = get_option( 'wbk_book_not_found_message', '' );
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$value = wp_kses( $value, $allowed_tags );
		$value = htmlentities($value);
		$html = '<input type="text" id="wbk_book_not_found_message" name="wbk_book_not_found_message" value="'.$value.'" >';
  		$html .= '<p class="description">' . __( 'Time slots not found message', 'wbk' ) . '</p>';
  		$html .= '<p class="description">' . __( 'Allowed HTML tags', 'wbk' ) . ': strong, em, b, i , br, p, a</p>';
  		echo $html;
	}
	// validate not found message
	public function validate_book_not_found_message( $input ) {
		$allowed_tags = array(
			    //formatting
			    'strong' => array(),
			    'em'     => array(),
			    'b'      => array(),
			    'i'      => array(),
				'br'      => array(),
			    //links
			    'a'     => array(
			        'href' => array(),
			        'class' => array()
			    ),
			    //links
			    'p'     => array(
			        'class' => array()
			    )
		);
		$input =  wp_kses( $input, $allowed_tags );
		return  $input;
	}
	// render purchase code
	public function render_purchase_code() {
		$value = get_option( 'wbk_purchase_code', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_purchase_code" name="wbk_purchase_code" value="'.$value.'" >';
  		echo $html;
	}
	// validate purchase code
	public function validate_purchase_code( $input ) {
		$input = sanitize_text_field( $input );
		return $input;
	}
	// render show booked slots  
	public function render_show_booked_slots() {
		$value = get_option( 'wbk_show_booked_slots', 'disabled' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_show_booked_slots" name="wbk_show_booked_slots">
				    <option ' . selected(  $value, 'enabled', false ) . ' value="enabled">' . __( 'Enabled', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'disabled', false ) . ' value="disabled">' . __( 'Disabled', 'wbk' ).'</option>
   				 </select>';
  		echo $html;
	}
	// validate show booked slots
	public function validate_show_booked_slots( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'enabled' && $value != 'disabled' ){
			$value = 'disabled';
		}
 		return $input;
	}
	// render lock appointments  
	public function render_appointments_auto_lock() {
		$value = get_option( 'wbk_appointments_auto_lock', 'disabled' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_appointments_auto_lock" name="wbk_appointments_auto_lock">
				    <option ' . selected(  $value, 'enabled', false ) . ' value="enabled">' . __( 'Enabled', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'disabled', false ) . ' value="disabled">' . __( 'Disabled', 'wbk' ).'</option>
   				 </select>';
   		$html .= '<p class="description">' . __( 'Enable this option for auto lock time slots of all services on booking (connection between services).', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate lock appointments
	public function validate_appointments_auto_lock( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'enabled' && $value != 'disabled' ){
			$value = 'disabled';
		}
 		return $input;
	}
	// render hide form on booking  
	public function render_check_short_code() {
		$value = get_option( 'wbk_check_short_code', 'disabled' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_check_short_code" name="wbk_check_short_code">
				    <option ' . selected(  $value, 'enabled', false ) . ' value="enabled">' . __( 'Enabled', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'disabled', false ) . ' value="disabled">' . __( 'Disabled', 'wbk' ).'</option>
   				 </select>';
   		$html .= '<p class="description">' . __( 'Enable this option to check if the page has shortode before booking form initialized.', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate hide booking form
	public function validate_check_short_code( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'enabled' && $value != 'disabled' ){
			$value = 'disabled';
		}
 		return $input;
	}	
 
	// render show cancel button
	public function render_show_cancel_button() {
		$value = get_option( 'wbk_show_cancel_button', 'disabled' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_show_cancel_button" name="wbk_show_cancel_button">
				    <option ' . selected(  $value, 'enabled', false ) . ' value="enabled">' . __( 'Enabled', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'disabled', false ) . ' value="disabled">' . __( 'Disabled', 'wbk' ).'</option>
   				 </select>';
   		$html .= '<p class="description">' . __( 'Enable this option to show cancel button on the steps of the booking process.', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate show cancel button
	public function validate_show_cancel_button( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'enabled' && $value != 'disabled' ){
			$value = 'disabled';
		}
 		return $input;
	}	
 
	// render disable date on all booked
	public function render_disable_day_on_all_booked() {
		$value = get_option( 'wbk_disable_day_on_all_booked', 'disabled' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_disable_day_on_all_booked" name="wbk_disable_day_on_all_booked">
				    <option ' . selected(  $value, 'enabled', false ) . ' value="enabled">' . __( 'Yes', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'disabled', false ) . ' value="disabled">' . __( 'No', 'wbk' ).'</option>
   				 </select>';
   		$html .= '<p class="description">' . __( 'Disable date in the calendar if no free time slots found.', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate disable date on all booked
	public function validate_disable_day_on_all_booked( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'enabled' && $value != 'disabled' ){
			$value = 'disabled';
		}
 		return $input;
	}	

	// render check shortcode  
	public function render_hide_from_on_booking() {
		$value = get_option( 'wbk_hide_from_on_booking', 'disabled' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_hide_from_on_booking" name="wbk_hide_from_on_booking">
				    <option ' . selected(  $value, 'enabled', false ) . ' value="enabled">' . __( 'Enabled', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'disabled', false ) . ' value="disabled">' . __( 'Disabled', 'wbk' ).'</option>
   				 </select>';
   		$html .= '<p class="description">' . __( 'Enable this option to hide all sections of the booking form when booking is done.', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate check shortcode
	public function validate_hide_from_on_booking( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'enabled' && $value != 'disabled' ){
			$value = 'disabled';
		}
 		return $input;
	}	


	// render payment success mesage
	public function render_payment_success_message() {
		$value = get_option( 'wbk_payment_success_message', __( 'Payment complete.') );
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$value = wp_kses( $value, $allowed_tags );
		$value = htmlentities($value);
		$html = '<input type="text" id="wbk_payment_success_message" name="wbk_payment_success_message" value="'.$value.'" >';		 
		$html .= '<p class="description">' . __( 'Allowed HTML tags', 'wbk' ) . ': strong, em, b, i , br, p, a</p>';	 
  		echo $html;
	}
	// validate form label
	public function validate_payment_success_message( $input ) {
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$input =  wp_kses( $input, $allowed_tags );
		return  $input;
	}
	// render payment cancel mesage
	public function render_payment_cancel_message() {
		$value = get_option( 'wbk_payment_cancel_message', __( 'Payment canceled.') );
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$value = wp_kses( $value, $allowed_tags );
		$value = htmlentities($value);
		$html = '<input type="text" id="wbk_payment_cancel_message" name="wbk_payment_cancel_message" value="'.$value.'" >';		 
		$html .= '<p class="description">' . __( 'Allowed HTML tags', 'wbk' ) . ': strong, em, b, i , br, p, a</p>';
  		echo $html;
	}
	// validate form label
	public function validate_payment_cancel_message( $input ) {
		$allowed_tags = array(
		    //formatting
		    'strong' => array(),
		    'em'     => array(),
		    'b'      => array(),
		    'i'      => array(),
			'br'      => array(),
		    //links
		    'a'     => array(
		        'href' => array(),
		        'class' => array()
		    ),
		    //links
		    'p'     => array(
		        'class' => array()
		    )
		);
		$input =  wp_kses( $input, $allowed_tags );
		return  $input;
	}

	// paypal options functions ******************************************************************************************************
	// render paypal mode
	public function render_paypal_mode() {
		$value = get_option( 'wbk_paypal_mode', 'Sandbox' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_paypal_mode" name="wbk_paypal_mode">
				    <option ' . selected(  $value, 'Sandbox', false ) . ' value="Sandbox">' . __( 'Sandbox', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'Live', false ) . ' value="Live">' . __( 'Live', 'wbk' ).'</option>
   				 </select>';
  		echo $html;
	}
	// validate paypal mode
	public function validate_paypal_mode( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'Sandbox' && $value != 'Live' ){
			$value = 'Sandbox';
		}
 		return $input;
	}
	// render paypal sandbox client id
	public function render_paypal_sandbox_clientid() {
		$value = get_option( 'wbk_paypal_sandbox_clientid', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_paypal_sandbox_clientid" name="wbk_paypal_sandbox_clientid" value="'.$value.'" >' .
         		'<a href="https://developer.paypal.com/developer/applications/"  target="_blank" ><span class="dashicons dashicons-editor-help"></span></a>';
  		echo $html;
	}
	// validate paypal sandbox client id
	public function validate_paypal_sandbox_clientid( $input ) {
		$input = sanitize_text_field( $input );
		return $input;
	}
    // render paypal sandbox secret
	public function render_paypal_sandbox_secret() {
		$value = get_option( 'wbk_paypal_sandbox_secret', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="password" id="wbk_paypal_sandbox_secret" name="wbk_paypal_sandbox_secret" value="'.$value.'" >' .
		        '<a href="https://developer.paypal.com/developer/applications/"  target="_blank" ><span class="dashicons dashicons-editor-help"></span></a>';
  		echo $html;
	}
	// paypal sandbox client id
	public function validate_paypal_sandbox_secret( $input ) {
		$input = sanitize_text_field( $input );
		return $input;
	}
    // render paypal live client id
	public function render_paypal_live_clientid() {
		$value = get_option( 'wbk_paypal_live_clientid', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_paypal_live_clientid" name="wbk_paypal_live_clientid" value="'.$value.'" >'.
         		'<a href="https://developer.paypal.com/developer/applications/"  target="_blank" ><span class="dashicons dashicons-editor-help"></span></a>';
  		echo $html;
	}
	// paypal live client id
	public function validate_paypal_live_clientid( $input ) {
		$input = sanitize_text_field( $input );
		return $input;
	}
    // render paypal live secret
	public function render_paypal_live_secret() {
		$value = get_option( 'wbk_paypal_live_secret', '' );
		$value = sanitize_text_field( $value );
		$html = '<input type="password" id="wbk_paypal_live_secret" name="wbk_paypal_live_secret" value="'.$value.'" >' .
        		'<a href="https://developer.paypal.com/developer/applications/" target="_blank"  ><span class="dashicons dashicons-editor-help"></span></a>';
  		echo $html;
	}
	// paypal live secret
	public function validate_paypal_live_secret( $input ) {
		$input = sanitize_text_field( $input );
		return $input;
	}
	// render paypal currency
	public function render_paypal_currency() {
		$value = get_option( 'wbk_paypal_currency', 'USD' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_paypal_currency" name="wbk_paypal_currency">
				    <option ' . selected(  $value, 'AUD', false ) . ' value="AUD">' . __( 'Australian Dollar', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'BRL', false ) . ' value="BRL">' . __( 'Brazilian Real', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'CAD', false ) . ' value="CAD">' . __( 'Canadian Dollar', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'CZK', false ) . ' value="CZK">' . __( 'Czech Koruna', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'DKK', false ) . ' value="DKK">' . __( 'Danish Krone', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'EUR', false ) . ' value="EUR">' . __( 'Euro', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'HKD', false ) . ' value="HKD">' . __( 'Hong Kong Dollar', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'HUF', false ) . ' value="HUF">' . __( 'Hungarian Forint', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'ILS', false ) . ' value="ILS">' . __( 'Israeli New Sheqel', 'wbk' ) . '</option>
				    <option ' . selected(  $value, 'JPY', false ) . ' value="JPY">' . __( 'Japanese Yen', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'MYR', false ) . ' value="MYR">' . __( 'Malaysian Ringgit', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'MXN', false ) . ' value="MXN">' . __( 'Mexican Peso', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'NOK', false ) . ' value="NOK">' . __( 'Norwegian Krone', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'NZD', false ) . ' value="NZD">' . __( 'New Zealand Dollar', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'PHP', false ) . ' value="PHP">' . __( 'Philippine Peso', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'PLN', false ) . ' value="PLN">' . __( 'Polish Zloty', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'GBP', false ) . ' value="GBP">' . __( 'Pound Sterling', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'SGD', false ) . ' value="SGD">' . __( 'Singapore Dollar', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'SEK', false ) . ' value="SEK">' . __( 'Swedish Krona', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'CHF', false ) . ' value="CHF">' . __( 'Swiss Franc', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'TWD', false ) . ' value="TWD">' . __( 'Taiwan New Dollar', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'THB', false ) . ' value="THB">' . __( 'Thai Baht', 'wbk' ) . '</option>
					<option ' . selected(  $value, 'USD', false ) . ' value="USD">' . __( 'U.S. Dollar', 'wbk' ) . '</option>
   				 </select>';
  		echo $html;
	}
	// validate mode
	public function validate_paypal_currency( $input ) {
		return $input;
	}
	// render paypal tax
	public function render_paypal_tax() {
		$value = get_option( 'wbk_paypal_tax', 0 );
		$value = sanitize_text_field( $value );
		$html = '<input type="text" id="wbk_paypal_tax" name="wbk_paypal_tax" value="'.$value.'" > %';
		$html .= '<p class="description">' . __( 'Percentage of an amount', 'wbk' ) . '</p>';

  		echo $html;
	}
	// paypal tax
	public function validate_paypal_tax( $input ) {
		$input = sanitize_text_field( $input );
		if ( is_numeric( $input ) ){
			if ( $input < 0 || $input > 100 ){
				$input = 0;
				add_settings_error( 'wbk_paypal_tax', 'wbk_paypal_tax_error', __( 'Tax updated', 'wbk' ), 'updated' );
			}
		} else {			 
			$input = 0;
			add_settings_error( 'wbk_paypal_tax', 'wbk_paypal_tax_error', __( 'Tax updated', 'wbk' ), 'updated' );	 
		}
		return $input;
	}
	// render paypal hide adress
	public function render_paypal_hide_address() {
		$value = get_option( 'wbk_paypal_hide_address', 'disabled' );
		$value = sanitize_text_field( $value );
		$html = '<select id="wbk_paypal_hide_address" name="wbk_paypal_hide_address">
				    <option ' . selected(  $value, 'enabled', false ) . ' value="enabled">' . __( 'Enabled', 'wbk' ).'</option>
				    <option ' . selected(  $value, 'disabled', false ) . ' value="disabled">' . __( 'Disabled', 'wbk' ).'</option>
   				 </select>';
   		$html .= '<p class="description">' . __( 'Enable this option to hide adress on PayPal checkout.', 'wbk' ) . '</p>';
  		echo $html;
	}
	// validate paypal hide adress
	public function validate_paypal_hide_address( $input ) {
		$input = trim( $input );
		return $input;
		$value = sanitize_text_field( $value );
		if ( $value != 'enabled' && $value != 'disabled' ){
			$value = 'disabled';
		}
 		return $input;
	}
    // paypal options functions end ******************************************************************************************************

}
?>