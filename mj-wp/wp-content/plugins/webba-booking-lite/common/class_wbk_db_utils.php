<?php
// check if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WBK_Db_Utils {
	// create tables
	static function createTables() {
		global $wpdb;
		// service table
	   	$wpdb->query(
	        "CREATE TABLE IF NOT EXISTS wbk_services (
	            id int unsigned NOT NULL auto_increment PRIMARY KEY,
	            name varchar(128) default '',
	            email varchar(128) default '',
	            description varchar(255) default '',
	            business_hours varchar(255) default '',
	            users varchar(512) default '',
	            duration int unsigned NOT NULL,	            
	            step int unsigned NOT NULL,
	            interval_between int unsigned NOT NULL,
				form int unsigned NOT NULL default 0,
				quantity int unsigned NOT NULL default 1,
				price FLOAT NOT NULL DEFAULT 0,
				notification_template int unsigned NOT NULL default 0,
				reminder_template int unsigned NOT NULL default 0,
				payment_methods varchar(255) NOT NULL DEFAULT '',
	            prepare_time int unsigned NOT NULL default 0,
	            UNIQUE KEY id (id)
	       		) 
		        DEFAULT CHARACTER SET = utf8
		        COLLATE = utf8_general_ci"
	    );
		// custom on/off days
	   	$wpdb->query(
	        "CREATE TABLE IF NOT EXISTS wbk_days_on_off (
	            id int unsigned NOT NULL auto_increment PRIMARY KEY,
	            service_id int unsigned NOT NULL,
	            day int unsigned NOT NULL,
	            status int unsigned NOT NULL,
	            UNIQUE KEY id (id)
	        ) 
	        DEFAULT CHARACTER SET = utf8
	        COLLATE = utf8_general_ci"
		);
	   	// custom locked timeslots
	   	$wpdb->query(
	        "CREATE TABLE IF NOT EXISTS wbk_locked_time_slots (
	            id int unsigned NOT NULL auto_increment PRIMARY KEY,
	            service_id int unsigned NOT NULL,
	            time int unsigned NOT NULL,
	            UNIQUE KEY id (id)
	        ) 
	        DEFAULT CHARACTER SET = utf8
	        COLLATE = utf8_general_ci"
		);
		// appointments table
	   	$wpdb->query(
	        "CREATE TABLE IF NOT EXISTS wbk_appointments (
	            id int unsigned NOT NULL auto_increment PRIMARY KEY,
	            name varchar(128) default '',
	            email varchar(128) default '',
	            phone varchar(128) default '',
	            description varchar(255) default '',
	            extra varchar(1000) default '',
	            attachment varchar(255) default '',
	           	service_id int unsigned NOT NULL,
				time int unsigned NOT NULL,
				day int unsigned NOT NULL,
				duration int unsigned NOT NULL,
				quantity int unsigned NOT NULL default 1,
				status varchar(255) default 'pending',
				payed  FLOAT NOT NULL DEFAULT 0,
				payment_id varchar(255) default '',
	            UNIQUE KEY id (id)
	        ) 
		        DEFAULT CHARACTER SET = utf8
		        COLLATE = utf8_general_ci"
	    );
	    // email templates
	   	$wpdb->query(
	        "CREATE TABLE IF NOT EXISTS wbk_email_templates (
	            id int unsigned NOT NULL auto_increment PRIMARY KEY,
	            name varchar(128) default '',
	            template varchar(2000) default '',
	            UNIQUE KEY id (id)
	        ) 
	        DEFAULT CHARACTER SET = utf8
	        COLLATE = utf8_general_ci"
		);
	}
	// drop tables
	static function dropTables() {
		global $wpdb;
		$wpdb->query( 'DROP TABLE IF EXISTS wbk_services' );
	  	$wpdb->query( 'DROP TABLE IF EXISTS wbk_appointments' );
	  	$wpdb->query( 'DROP TABLE IF EXISTS wbk_locked_time_slots' );
		$wpdb->query( 'DROP TABLE IF EXISTS wbk_days_on_off' );
		$wpdb->query( 'DROP TABLE IF EXISTS wbk_email_templates' );

	}
	// add fields used since 1.2.0
	static function update_1_2_0(){
		global $wpdb; 
		$table_name = 'wbk_services';
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'form' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_services` ADD `form` int unsigned NOT NULL default 0");
		}
 	}
	// add fields used since 1.3.0
	static function update_1_3_0(){
		global $wpdb; 
		$table_name = 'wbk_services';
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'quantity' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_services` ADD `quantity` int unsigned NOT NULL default 1");
		}
		$table_name = 'wbk_appointments';
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'quantity' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_appointments` ADD `quantity` int unsigned NOT NULL default 1");
		}
 	}
	// add fields used since 3.0.0
	static function update_3_0_0(){
		global $wpdb; 
		$table_name = 'wbk_services';
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'price' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_services` ADD `price` FLOAT NOT NULL DEFAULT '0'");
		}
	 	$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'payment_methods' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_services` ADD `payment_methods` varchar(255) NOT NULL DEFAULT ''");
		}
		$table_name = 'wbk_appointments';
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'status' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_appointments` ADD `status`  varchar(255) NOT NULL DEFAULT 'pending'");
		}
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'payed' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_appointments` ADD `payed` FLOAT NOT NULL DEFAULT 0");
		}
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'payment_id' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_appointments` ADD `payment_id` varchar(255) NOT NULL DEFAULT ''");
		}
 	}
 	// add tables and fields used since 3.0.3
	static function update_3_0_3(){
		global $wpdb;
		// email templates table
	   	$wpdb->query(
	        "CREATE TABLE IF NOT EXISTS wbk_email_templates (
	            id int unsigned NOT NULL auto_increment PRIMARY KEY,
	            name varchar(128) default '',
	            template varchar(2000) default '',
	            UNIQUE KEY id (id)
	        ) 
	        DEFAULT CHARACTER SET = utf8
	        COLLATE = utf8_general_ci"
		);
		$table_name = 'wbk_services';
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'notification_template' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_services` ADD `notification_template` int unsigned NOT NULL default 0");
		}		
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'reminder_template' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_services` ADD `reminder_template` int unsigned NOT NULL default 0");
		}	

	}
 	// add fields used since 3.0.15
	static function update_3_0_15(){
		global $wpdb;
		$table_name = 'wbk_services';
		$found = false;
		foreach ( $wpdb->get_col( "DESC " . $table_name, 0 ) as $column_name ) {
			if ( $column_name == 'prepare_time' ){
				$found = true;
			}
		}
		if ( !$found ){
			 $wpdb->query("ALTER TABLE `wbk_services` ADD `prepare_time` int unsigned NOT NULL default 0");
		}	
	}

	// get services  
	static function getServices() {
	 	global $wpdb;
		$result = $wpdb->get_col( " SELECT id FROM wbk_services " );
		return $result;
	}
	// get not-admin users
	static function getNotAdminUsers() {
		$arr_users = array();
		$arr_temp = get_users( array( 'role__not_in' => array( 'subscriber', 'administrator'), 'fields' => 'user_login' ) );
		if ( count( $arr_temp ) > 0 ) {
			array_push( $arr_users, $arr_temp );  
		}
	 	return $arr_users;
	}	
	// get not-admin users
	static function getAdminUsers() {
		$arr_users = array();
		array_push( $arr_users, get_users( array( 'role' => 'administrator', 'fields' => 'user_login' ) ) );  
	 	return $arr_users;
	}	
	// check if service name is free
	static function isServiceNameFree( $value ) {
		global $wpdb;
		$count = $wpdb->get_var( $wpdb->prepare( " SELECT COUNT(*) FROM wbk_services WHERE name = %s ", $value ) );
		if ( $count > 0 ){
			return false;
		} else {
			return true;
		}
	}
	// get CF7 forms
	static function getCF7Forms() {
		$args = array( 'post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1 );
		$result = array();
		if( $cf7Forms = get_posts( $args ) ) {
			foreach( $cf7Forms as $cf7Form ) {
				$form = new stdClass();
				$form ->name = $cf7Form->post_title;
				$form->id = $cf7Form->ID;
				array_push( $result, $form );
			}
		}	
		return $result;	
	}
	// get service id by appointment id
	static function getServiceIdByAppointmentId( $appointment_id ){
		global $wpdb;
		$service_id = $wpdb->get_var( $wpdb->prepare( " SELECT service_id FROM wbk_appointments WHERE id = %d ", $appointment_id ) );
		if ( $service_id == null ){
			return false;
		} else {
			return $service_id;
		}
	}
 	// get tomorrow appointments for the service
	static function getTomorrowAppointmentsForService( $service_id ) {
	 	global $wpdb;
		$tomorrow = strtotime('tomorrow');
		$result = $wpdb->get_col( $wpdb->prepare( " SELECT id FROM wbk_appointments WHERE service_id=%d AND day=%d  ORDER BY time ", $service_id, $tomorrow  ) );
		return $result;
	}
 	// lock appointments of others services
	static function lockTimeSlotsOfOthersServices( $service_id, $appointment_id ) {
		global $wpdb;
		
		// getting data about booked service 
		$service = new WBK_Service();
		if ( !$service->setId( $service_id ) ) {
			return FALSE;
		}
		if ( !$service->load() ) {
 			return FALSE;
		}
		if ( $service->getQuantity() > 1 ){
			return FALSE;
		}					
		$appointment = new WBK_Appointment();
		if ( !$appointment->setId( $appointment_id ) ) {
			return FALSE;
		}
		if ( !$appointment->load() ) {
 			return FALSE;
		}
		$start = $appointment->getTime();
		$end = $start + $appointment->getDuration() * 60 + $service->getInterval() * 60;

		// iteration over others services
		$arrIds = WBK_Db_Utils::getServices();
	 	if ( count( $arrIds ) < 1 ) {
	 		return TRUE;
	 	} 
	 	foreach ( $arrIds as $service_id_this ) {
 
	 		if ( $service_id == $service_id_this ){
	 			continue;
	 		}
	 		$service = new WBK_Service();
			if ( !$service->setId( $service_id_this ) ) {
				continue;
			}
			if ( !$service->load() ) {
	 			continue;
			}
			if ( $service->getQuantity() > 1 ){
				continue;
			}		
			 
			$service_schedule = new WBK_Service_Schedule();
 			$service_schedule->setServiceId( $service_id_this );
 			$service_schedule->load();
 			$midnight = strtotime('today', $start );
 			$service_schedule->buildSchedule( $midnight );
		 
		 	$this_duration = $service->getDuration() * 60  + $service->getInterval() * 60; 

			$timeslots_to_lock = $service_schedule->getNotBookedTimeSlots();

 
			foreach ( $timeslots_to_lock as $time_slot_start ) {
				$cur_start = $time_slot_start;
				$cur_end = $time_slot_start + $this_duration;

			 	$intersect = false;

				if ( $cur_start == $start ){
					$intersect = true;					
				}
				if ( $cur_start > $start && $cur_start < $end ){
					$intersect = true;					
				}
				if ( $cur_end > $start && $cur_end <= $end  ){
					$intersect = true;					
				}
				if( $intersect == true ) {					

					if ( $wpdb->query( $wpdb->prepare( "DELETE FROM wbk_locked_time_slots WHERE time = %d and service_id = %d",  $time_slot_start, $service_id_this ) ) === false ){
						echo -1;
						die();
						return;
					}
					if ( $wpdb->insert( 'wbk_locked_time_slots', array( 'service_id' => $service_id_this, 'time' => $time_slot_start ), array( '%d', '%d' ) ) === false ){
						echo -1;
						die();
						return;
					}			  				 
				}
 
			}

 
	 	}
	}	
	// set payment if for appointment()
	static function setPaymentId( $appointment_id, $payment_id ){
		global $wpdb;
		if( !is_numeric( $appointment_id ) ){
			return FALSE;
		}
		$result = $wpdb->update( 
						'wbk_appointments', 
						array( 'payment_id' => $payment_id ), 
						array( 'id' => $appointment_id), 
						array( '%s' ), 
						array( '%d' ) 
					);
		if( $result == false || $result == 0 ){
			return FALSE;
		} else {
			return TRUE;
		}
	}	
	// get amount by payment id 
	static function getAmountByPaymentId( $payment_id ){
		global $wpdb;
		if ( $payment_id == '' || !isset( $payment_id) ){
			return FALSE;
		}
		$quantity = $wpdb->get_var( $wpdb->prepare( "SELECT quantity FROM wbk_appointments WHERE payment_id = %s", $payment_id ) );
		if ( $quantity == null ){
			return FALSE;
		}  
		$appointment_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM wbk_appointments WHERE payment_id = %s", $payment_id ) );
		if ( $appointment_id == null ){
			return FALSE;
		}  
		$service_id = WBK_Db_Utils::getServiceIdByAppointmentId( $appointment_id );
		$price = $wpdb->get_var( $wpdb->prepare( "SELECT price FROM wbk_services WHERE id = %d", $service_id ) );
		if ( $appointment_id == null ){
			return FALSE;
		}
		return array( $price, $quantity );
	}
	// update payment status
	static function updatePaymentStatus( $payment_id, $amount ){
		global $wpdb;
		
		$result = $wpdb->update( 
						'wbk_appointments', 
						array( 'status' => 'paid', 'payed' => $amount ), 
						array( 'payment_id' => $payment_id), 
						array( '%s', '%f' ), 
						array( '%s' ) 
					);
		if( $result == false || $result == 0 ){
			return FALSE;
		} else {
			return TRUE;
		}
	}
	// get indexed names  
	static function getIndexedNames( $table ) {
	 	global $wpdb;
		$result = $wpdb->get_results( "	SELECT id, name from $table " );
		return $result;
	}  
	static function getEmailTemplate( $id ){
		global $wpdb;
		$result =  $wpdb->get_var( $wpdb->prepare( " SELECT template FROM wbk_email_templates WHERE id = %d ", $id ) ); 
		return $result;
	}
	// $appointment_id provided to get the date and include in free results
	static function getFreeTimeslotsArray( $appointment_id ){
		$result = false;
		if( !is_numeric( $appointment_id ) ){
	        return $result;
	    }
	    $service_id = self::getServiceIdByAppointmentId( $appointment_id );
	    $service_schedule = new WBK_Service_Schedule();
	    if ( !$service_schedule->setServiceId( $service_id ) ){
	        return $result;
	    }
	    if ( !$service_schedule->load() ){
	        return $result;
	    }
	    $appointment = new WBK_Appointment();
		if ( !$appointment->setId( $appointment_id ) ) {
			return $result;
		}
		if ( !$appointment->load() ) {
 			return $result;
		}
	    $midnight = $appointment->getDay();
	    $day_status =  $service_schedule->getDayStatus( $midnight );
	    if ( $day_status == 0 ) {
	    	return $result;
	    }
	    $service_schedule->buildSchedule( $midnight );
	    $result = $service_schedule->getFreeTimeslotsPlusGivenAppointment( $appointment_id );
	    return $result;
	}
	// return blank array
	static function blankArray(){
		return array();
	}
}
?>