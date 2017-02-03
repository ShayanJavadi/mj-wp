// WEBBA Booking service dialog javascript

// onload functions
jQuery(function ($) {
 	jQuery('#wbk-add-shortcode').click(function() {
 	 	jQuery( '#wbk-service-dialog' ).dialog({
		resizable: false,
		width: 400,
		title: wbkl10n.formtitle,
	    height:240,
	    modal: true,
	    buttons: [
	    	{
	    		text: wbkl10n.add,
	    		click: function() {
					var service_id = jQuery( '#wbk-service-id' ).val();	
					if ( service_id == 0) {
				        window.send_to_editor('[webba_booking]');
					} else {
				        window.send_to_editor('[webba_booking service="' + service_id + '"]');						
					}
					jQuery( '#wbk-service-dialog' ).dialog( 'close' ); 
	    		}
	    	},
	    	{
	    		text: wbkl10n.cancel,
	    		click: function() {
	    			jQuery( '#wbk-service-dialog' ).dialog( 'close' ); 		
	    		} 
	    	}
		    ]
	    });
 	});
});