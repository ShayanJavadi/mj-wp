<?php
/*
Plugin Name: Webba Booking Lite
Plugin URI: http://webba-booking.com
Description: Responsive appointment booking plugin.
Version: 3.0.15
Author: Webba Agency
Author URI: http://webba-booking.com
License: Commercial
*/
// check if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
// entities classes
include 'common/class_wbk_entity.php';
// backend class include
include 'backend/class_wbk_backend.php';
// utils classes include
include 'common/class_wbk_db_utils.php';
// ajax controller
include 'common/class_wbk_ajax_controller.php';
// frontend class include
include 'frontend/class_wbk_frontend.php';
// include email notification class
include 'common/class_wbk_email_notifications.php';
 // include admin notices
include 'backend/class_wbk_admin_notices.php';
// localization
add_action( 'init', 'wbk_load_textdomain' );
function wbk_load_textdomain() {
 	load_plugin_textdomain( 'wbk', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
// PayPal integration
require 'common/PayPal/class_wbk_paypal.php'; 
// activation/deactivation hooks
register_activation_hook( __FILE__, 'wbk_activate' );
register_deactivation_hook( __FILE__, 'wbk_deactivate' );
register_uninstall_hook( __FILE__, 'wbk_uninstall');
add_action( 'plugins_loaded', 'wbk_update_data' );
add_action( 'admin_init', 'wbk_admin_init' );
function wbk_activate() {
 	// create tables if not created
	 
	WBK_Db_Utils::createTables();
	add_option( 'wbk_start_of_week', '' );
	add_option( 'wbk_date_format', '' );
	add_option( 'wbk_time_format', '' );
	add_option( 'wbk_timezone', '' );
	add_option( 'wbk_email_customer_book_status', '' );
	add_option( 'wbk_email_customer_book_message', '<p>Dear #customer_name,</p><p>You have successfully booked #service_name on #appointment_day at #appointment_time</p><p>Thank you for choosing our company!</p>' );
	add_option( 'wbk_email_customer_book_subject', __( 'You have successfully booked an appointment', 'wbk' ) );
	add_option( 'wbk_email_admin_book_status', '' );
	add_option( 'wbk_email_admin_book_message', '<p>Details of booking:</p><p>Date: #appointment_day<br />Time: #appointment_time<br />Customer name: #customer_name<br />Customer phone: #customer_phone<br />Customer email: #customer_email<br />Customer comment: #customer_comment</p><p> </p>' );
	add_option( 'wbk_email_admin_daily_status', '' );
	add_option( 'wbk_email_admin_daily_subject', 'Agenda for tomorrow\'s appointments' );
	add_option( 'wbk_email_admin_daily_message', '<p>Your tomorrow\'s appointments:<br /><br />#tomorrow_agenda</p>' );
	add_option( 'wbk_email_customer_daily_status', '' );
	add_option( 'wbk_email_customer_daily_subject', 'Appointment reminder' );
	add_option( 'wbk_email_customer_daily_message', '<p>Dear, #customer_name!</p><p>We would like to remind that you have booked the #service_name<br />tomorrow at #appointment_time.</p>' );
	add_option( 'wbk_email_admin_daily_time', '64800' );
	add_option( 'wbk_email_admin_book_subject', __( 'New appointment booking', 'wbk' ) );
	add_option( 'wbk_from_name', get_option( 'blogname' ) );
	add_option( 'wbk_from_email', get_option( 'admin_email' ) );
	add_option( 'wbk_mode', 'extended' );
	add_option( 'wbk_service_label', __( 'Select a service', 'wbk' ) );
	add_option( 'wbk_date_extended_label', __( 'Book an appointment on or after', 'wbk' ) );
	add_option( 'wbk_date_basic_label', __( 'Book an appointment on', 'wbk' ) );
	add_option( 'wbk_hours_label', __( 'Suitable hours', 'wbk' ) );
	add_option( 'wbk_slots_label', __( 'Available time slots', 'wbk' ) );
	add_option( 'wbk_form_label', __( 'You are booking #service on #date  at #time <br>Please, fill in a form:', 'wbk' ) );
	add_option( 'wbk_book_items_quantity_label', __( 'Booking items count', 'wbk' ) );
	add_option( 'wbk_book_thanks_message', __( 'Thanks for booking appointment', 'wbk' ) );
	add_option( 'wbk_book_not_found_message', __( 'Unfortunately we were unable to meet your search criteria. Please change the criteria and try again.', 'wbk' ) );
	add_option( 'wbk_phone_mask', 'enabled' );
	add_option( 'wbk_phone_format', '(999) 999-9999' );
	add_option( 'wbk_booking_forms',  '' );
	add_option( 'wbk_button_background', '#186762' );
	add_option( 'wbk_button_color', '#ffffff' );
	add_option( 'wbk_timeslot_time_string', 'start' );
	add_option( 'wbk_show_booked_slots', 'disabled' );
	add_option( 'wbk_booked_text', __( 'Booked', 'wbk' ) );
	add_option( 'wbk_email_secondary_book_subject', __( 'Appointment notification', 'wbk' ) );
	add_option( 'wbk_email_secondary_book_message', '<p>Dear #group_customer_name,</p><p>#customer_name invited you to #service_name on #appointment_day at #appointment_time.</p>' );
	add_option( 'wbk_appointments_auto_lock', 'disabled' );
	add_option( 'wbk_name_label', __( 'Name', 'wbk' ) );
	add_option( 'wbk_email_label', __( 'Email', 'wbk' ) );
	add_option( 'wbk_phone_label', __( 'Phone', 'wbk' ) );
	add_option( 'wbk_comment_label', __( 'Comment', 'wbk' ) );
	add_option( 'wbk_date_input_placeholder', __( 'date...', 'wbk' ) );
	add_option( 'wbk_book_text_form', __( 'Book', 'wbk' ) );
	add_option( 'wbk_book_text_timeslot', __( 'Book', 'wbk' ) );
	add_option( 'wbk_payment_pay_with_paypal_btn_text', __( 'Pay now with PayPal', 'wbk' ) );
	add_option( 'wbk_payment_pay_with_cc_btn_text', __( 'Pay now with credit card', 'wbk' ) );
	add_option( 'wbk_payment_details_title', __( 'Payment details', 'wbk' ) );
	add_option( 'wbk_payment_item_name', __( '#service on #date at #time', 'wbk' ) );
	add_option( 'wbk_payment_price_format', '$#price' );
	add_option( 'wbk_payment_subtotal_title', __( 'Subtotal', 'wbk' ) );
	add_option( 'wbk_payment_total_title', __( 'Total', 'wbk' ) );
    add_option( 'wbk_paypal_currency', 'USD' );
    add_option( 'wbk_paypal_tax', 0 );
    add_option( 'wbk_paypal_fee', 0 );
    add_option( 'wbk_payment_approve_text', __( 'Approve payment', 'wbk' ) );
    add_option( 'wbk_payment_result_title', __( 'Payment status', 'wbk' ) );
    add_option( 'wbk_payment_success_message', __( 'Payment completed.', 'wbk'));
    add_option( 'wbk_payment_cancel_message', __( 'Payment canceled.', 'wbk' ));
    add_option( 'wbk_paypal_hide_address', 'disabled' );
    add_option( 'wbk_hide_from_on_booking', 'disabled' );
    add_option( 'wbk_check_short_code', 'disabled' );
	add_option( 'wbk_show_cancel_button', 'disabled' );
	add_option( 'wbk_cancel_button_text', __( 'Cancel', 'wbk' ) );
	add_option( 'wbk_disable_day_on_all_booked', 'disabled' );
	add_option( 'wbk_super_admin_email', '' );

}
add_action( 'wbk_daily_event', 'wbk_daily' );
function wbk_daily() {
	$noifications = new WBK_Email_Notifications( 0, 0 );
	$noifications->send( 'daily' );
}
function wbk_update_data(){
	// update tables
	WBK_Db_Utils::update_1_2_0();
	WBK_Db_Utils::update_1_3_0();
	WBK_Db_Utils::update_3_0_0();
 	WBK_Db_Utils::update_3_0_3();
	WBK_Db_Utils::update_3_0_15();
}
function wbk_admin_init(){
	// update appearance settings
	$slf = new SoloFramework( 'wbk_settings_data' );
	$css_compil_version = get_option( 'wbk_css_compil_version', '' );
	$plugin_data = get_plugin_data( __FILE__ );
	$current_version = $plugin_data['Version'];
	if ( strcmp ( $current_version, $css_compil_version) != 0 ){
	 	$slf->getSetionsSet( 'wbk_extended_appearance_options' )->compileFrontendCssFromStored();
		update_option('wbk_css_compil_version', $current_version );
	}
}
function wbk_deactivate() {
   wp_clear_scheduled_hook( 'wbk_daily_event' );
}
function wbk_uninstall() {
	return;
	// drop tables
	WBK_Db_Utils::dropTables();
	delete_option( 'wbk_start_of_week' );
	delete_option( 'wbk_date_format' );
	delete_option( 'wbk_time_format' );
	delete_option( 'wbk_timezone' );
	delete_option( 'wbk_email_customer_book_status' );
	delete_option( 'wbk_email_customer_book_message' );
	delete_option( 'wbk_email_customer_book_subject' );
	delete_option( 'wbk_email_admin_book_status' );
	delete_option( 'wbk_email_admin_book_message' );
	delete_option( 'wbk_email_admin_daily_status', '' );
	delete_option( 'wbk_email_admin_daily_subject' );
	delete_option( 'wbk_email_admin_daily_message');
	delete_option( 'wbk_email_customer_daily_status' );
	delete_option( 'wbk_email_customer_daily_subject' );
	delete_option( 'wbk_email_customer_daily_message' );
	delete_option( 'wbk_email_admin_daily_time' );
	delete_option( 'wbk_email_admin_book_subject' );
	delete_option( 'wbk_from_name' );
	delete_option( 'wbk_from_email' );
	delete_option( 'wbk_mode' );
	delete_option( 'wbk_service_label'  );
	delete_option( 'wbk_date_extended_label'  );
	delete_option( 'wbk_date_basic_label');
	delete_option( 'wbk_hours_label' );
	delete_option( 'wbk_slots_label' );
	delete_option( 'wbk_form_label' );
	delete_option( 'wbk_book_items_quantity_label' );
	delete_option( 'wbk_book_thanks_message'  );
	delete_option( 'wbk_book_not_found_message'  );
	delete_option( 'wbk_phone_mask'  );
	delete_option( 'wbk_phone_format' );
	delete_option( 'wbk_booking_forms'  );
	delete_option( 'wbk_button_background'  );
	delete_option( 'wbk_button_color' );
	delete_option( 'wbk_timeslot_time_string'  );
	delete_option( 'wbk_show_booked_slots'  );
	delete_option( 'wbk_booked_text' );
	delete_option( 'wbk_email_secondary_book_subject'  );
	delete_option( 'wbk_email_secondary_book_message'  ); 
	delete_option( 'wbk_appointments_auto_lock' );
	delete_option( 'wbk_name_label'  );
	delete_option( 'wbk_email_label' );
	delete_option( 'wbk_phone_label'  );
	delete_option( 'wbk_comment_label'  );
	delete_option( 'wbk_date_input_placeholder'  );
	delete_option( 'wbk_book_text_form'  );
	delete_option( 'wbk_book_text_timeslot'  );
	delete_option( 'wbk_payment_pay_with_paypal_btn_text'  );
	delete_option( 'wbk_payment_pay_with_cc_btn_text'  );
	delete_option( 'wbk_payment_details_title'  );
	delete_option( 'wbk_payment_item_name'  );
	delete_option( 'wbk_payment_price_format'  );
	delete_option( 'wbk_payment_subtotal_title' );
	delete_option( 'wbk_payment_total_title'  );
    delete_option( 'wbk_paypal_currency'  );
    delete_option( 'wbk_paypal_tax' );
    delete_option( 'wbk_paypal_fee' );
    delete_option( 'wbk_payment_approve_text' );
    delete_option( 'wbk_payment_result_title' );
    delete_option( 'wbk_payment_success_message' );
    delete_option( 'wbk_payment_cancel_message' );
    delete_option( 'wbk_paypal_hide_address');
    delete_option( 'wbk_hide_from_on_booking' );
    delete_option( 'wbk_check_short_code'  );
	delete_option( 'wbk_show_cancel_button'  );
	delete_option( 'wbk_cancel_button_text'  );
	delete_option( 'wbk_disable_day_on_all_booked'  );
	delete_option( 'wbk_super_admin_email' );
}
// set timezone
$timezone = get_option( 'wbk_timezone', '' );
if ( $timezone != '' ){
	date_default_timezone_set( $timezone );
}
// common ajax controller
$ajaxController = new WBK_Ajax_Controller();
// check frontend / backend
if ( is_admin() ) {
	$backend = new WBK_Backend();
} else {
	$frontend = new WBK_Frontend();
}
?>