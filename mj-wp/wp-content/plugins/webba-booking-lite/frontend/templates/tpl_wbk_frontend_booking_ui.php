<?php
    // check if accessed directly
    if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="wbk-outer-container">
	<div class="wbk-inner-container">
 	<img src=<?php echo get_site_url() . '/wp-content/plugins/webba-booking-lite/frontend/images/loading.svg' ?> style="display:block;width:0px;height:0px;">
		<div class="wbk-frontend-row" id="wbk-service-container" >
			<div class="wbk-col-12-12" >		
				 <?php 			
				 	if ( $data[0] <> 0 ){
				 		echo '<input type="hidden" id="wbk-service-id" value="' . $data[0] . '" />';	 	 		
				 	} else {
				 		$label = '';	
				 		if ( get_option( 'wbk_mode', 'extended' ) == 'extended' ) {
					 		$label = get_option( 'wbk_service_label', 'Select service' );
				 		} else {
					 		$label = get_option( 'wbk_service_label', 'Select service' );
				 		}
						echo  '<label class="wbk-input-label">' . $label . '</label>';
				 		echo '<select class="wbk-select wbk-input" id="wbk-service-id">'; 
				 		echo '<option value="0" selected="selected">' . __( 'select...', 'wbk' ) . '</option>';
				 		$arrIds = WBK_Db_Utils::getServices();
				 		foreach ( $arrIds as $id ) {
				 			$service = new WBK_Service();
				 			if ( !$service->setId( $id ) ) {  
				 				continue;
				 			}
				 			if ( !$service->load() ) {  
				 				continue;
				 			}
				 			echo '<option value="' . $service->getId() . '" >' . $service->getName() . '</option>';
				 		}
				 		echo '</select>';
				 	}
				 ?>
			</div>
			<?php 
				echo WBK_Date_Time_Utils::renderBHDisabilitiesFull();
				// add get parameters
				$html_get  = '<script type=\'text/javascript\'>';
      			$html_get .= 'var wbk_get_converted = {';
				foreach ( $_GET as $key => $value ) {
					$value = urldecode($value);
					$key = urldecode($key);
			 		
			 		$value = str_replace('"', '', $value);
			 		$key = str_replace('"', '', $key);

			 		$value = str_replace('\'', '', $value);
			 		$key = str_replace('\'', '', $key);

			 		$value = str_replace('/', '', $value);
			 		$key = str_replace('/', '', $key);

			 		$value = str_replace('\\', '', $value);
			 		$key = str_replace('\\', '', $key);
				
					$value = sanitize_text_field($value);
					$key = sanitize_text_field($key);
					
					if ( $key != 'action' && $key != 'time' && $key != 'service' && $key != 'step' ){

					}

					$html_get .= '"'.$key.'"'. ':"' . $value . '",';			  						 
				}  					
				$html_get .= '"blank":"blank"';
  				$html_get .= '};</script>';
  				echo $html_get;
			?>

		</div>
		<div class="wbk-frontend-row" id="wbk-date-container">	
		</div> 
		<div class="wbk-frontend-row" id="wbk-time-container">
		</div>
		<div class="wbk-frontend-row" id="wbk-slots-container">				 
		</div>
		<div class="wbk-frontend-row" id="wbk-booking-form-container">		 
		</div>
		<div class="wbk-frontend-row" id="wbk-booking-done">
		</div>
		<div class="wbk-frontend-row" id="wbk-payment">
		</div>
	</div>	
</div>