<?php
//WBK stat class

// check if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
class WBK_Admin_Notices {
	public static function labelUpdate(){
		
		if (get_option( 'wbk_service_label', '' ) == '' || 
			get_option( 'wbk_date_extended_label', '' ) == '' || 
			get_option( 'wbk_date_basic_label', '' ) == '' ||
		 	get_option( 'wbk_hours_label', '' ) == '' ||
		  	get_option( 'wbk_slots_label', '' ) == '' ||
	  		get_option( 'wbk_form_label', '' ) == '' ||
		  	get_option( 'wbk_book_items_quantity_label', '' )  ==  '' ||
		  	get_option( 'wbk_book_thanks_message', '' )  ==  '' ||
		  	get_option( 'wbk_book_not_found_message', '' )  ==  '' 

		  	){
  
			return '<div class="notice notice-warning is-dismissible"><p>WEBBA Booking: please setup translation at settings page. 				 
					</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	  	} 

	 	return;
	}	 
	public static function colorUpdate(){		
		if ( get_option( 'wbk_button_background', '' ) == '' || 
			 get_option( 'wbk_button_color', '' ) == ''  
 		   ){
  			return '<div class="notice notice-warning is-dismissible"><p>WEBBA Booking: Please setup colors at appearance settings section. 				 
					</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	  	} 
	 	return;
	}	
	public static function appearanceUpdate(){
		if ( get_option( 'wbk_appearance_saved', '' ) != 'true' ) { 
			return '<div class="notice notice-warning is-dismissible"><p>WEBBA Booking: Please setup appearance settings. 				 
					</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	  	} 
	 	return;
	}	
}
?>