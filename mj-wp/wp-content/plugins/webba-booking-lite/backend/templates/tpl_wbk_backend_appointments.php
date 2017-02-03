<!-- Webba Booking backend options page template --> 
<?php
    // check if accessed directly
    if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap">
 	<h2 class="wbk_panel_title"><?php  echo __( 'Appointments', 'wbk' ); ?>
    <a style="text-decoration:none;" href="http://webba-booking.com/documentation/working-with-appointments" target="_blank"><span class="dashicons dashicons-editor-help"></span></a>
    </h2>
    <div class="notice notice-warning is-dismissible"><p>Please, note that Email notifications and PayPal sections are for demo purpose only. To unlock notifications and payment features, please, upgrade to Premium version. <a href="https://codecanyon.net/item/appointment-booking-for-wordpress-webba-booking/13843131?ref=Webbagency" target="_blank">Upgrade now</a>. </p>
    <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
    </div>

        <?php        
            $table = new WBK_Appointments_Table();
            $html = $table->render();
            echo $html;
        ?>                                            
</div>
