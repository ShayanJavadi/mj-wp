<?php
/**
 * Plugin Name: Woo Update Variations In Cart
 * Plugin URI: http://codingkart.com/
 * Description: WooCommerce Update Variations In Cart.
 * Version: 0.0.1
 * Author: Ganesh
 * Author URI: http://codingkart.com/
 * Developer: Ganesh pawar
 * Developer URI: http://codingkart.com/
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 *
 * Copyright: © 20016-2020 WooCommerce.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly
//Plugin Path
if ( ! defined( 'WUVIC_WOO_UPDATE_CART_ASSESTS_URL' ) ) {
	define('WUVIC_WOO_UPDATE_CART_ASSESTS_URL', plugin_dir_url(__FILE__).'assets/');
}
/**
 * Check if WooCommerce is active
 **/

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {   
         
		//update_option( 'WOO_CK_WUVIC_status', 'true');
		 //add js file to cart 
		add_action('wp_head','WOO_CK_WUVIC_hook_js');
		add_action( 'wp_head', 'WOO_CK_WUVIC_itr_global_js_vars' );
		 //add edit link on cart page 
		add_filter( 'woocommerce_cart_item_name', 'WOO_CK_WUVIC_cart_product_title', 20, 3);
		//get variation form using ajax
		add_action( 'wp_ajax_get_variation_form', 'WOO_CK_WUVIC_get_variation_form' );
        add_action( 'wp_ajax_nopriv_get_variation_form', 'WOO_CK_WUVIC_get_variation_form' );
        //update product
		add_action( 'wp_ajax_update_product_in_cart', 'WOO_CK_WUVIC_update_product_in_cart' );
        add_action( 'wp_ajax_nopriv_update_product_in_cart', 'WOO_CK_WUVIC_update_product_in_cart' ); 
}
else
{ 
add_action( 'admin_notices', 'WOO_CK_WUVIC_woocommerce_admin_notice' );
} 
if ( !function_exists( 'WOO_CK_WUVIC_woocommerce_admin_notice' ) ) {
function WOO_CK_WUVIC_woocommerce_admin_notice() {
?>
<div class="error">
<p><?php _e( 'WooCommerce Update Variations In Cart is enabled but not effective. It requires WooCommerce in order to work.', 'WOO_CK_WUVIC_woocommerce_admin_notice-woocommerce' ); ?></p>
</div>
<?php
}
}
function WOO_CK_WUVIC_hook_js() {	//apply if condition here
	global $wpdb;
	$WOO_CK_WUVIC_status = get_option( 'WOO_CK_WUVIC_status' );
	if($WOO_CK_WUVIC_status=="true"){
if(is_cart())
{
wp_enqueue_script( 'wc-add-to-cart-variation' );
wp_enqueue_script("ck-cart-js",WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'js/cart.js',array('jquery'),'0.1',true);  
wp_enqueue_style( 'ck-cart-css',WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'css/style.css',false,'0.1','all'); 
}
}
}
function WOO_CK_WUVIC_cart_product_title( $title, $values, $cart_item_key ) {

if(get_option( "WOO_CK_WUVIC_edit_link_text" )=="")
{
$WOO_CK_WUVIC_edit_link_text="Edit";
}
else
{
$WOO_CK_WUVIC_edit_link_text=get_option( "WOO_CK_WUVIC_edit_link_text" );
}
if(count($values['variation']) && get_option( 'WOO_CK_WUVIC_status' )=="true" ) //apply if condition here in if()
{
	$targetPath = WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'/img/loader.gif';
return $title . '<br><span class="WOO_CK_WUVIC_buttom '.get_option( "WOO_CK_WUVIC_edit_link_class" ).'" id="'.$cart_item_key.'" >'.$WOO_CK_WUVIC_edit_link_text.'</span>'.'<img src="'.$targetPath.'" alt="Smiley face" height="42" width="42" id="loder_img" style="display:none;">';
}else{ return $title; }
}
function WOO_CK_WUVIC_itr_global_js_vars() {
	global $wpdb;
	$WOO_CK_WUVIC_update_btn = get_option( 'WOO_CK_WUVIC_update_btn_text' );
    $ajax_url = 'var update_variation_params = {"ajax_url":"'. admin_url( 'admin-ajax.php' ) .'","update_text":"'.$update_btn.'","cart_updated_text":"Cart updated."};';
    echo "<script type='text/javascript'>\n";
    echo "/* <![CDATA[ */\n";
    echo $ajax_url;
    echo "\n/* ]]> */\n";
    echo "</script>\n";
}
function WOO_CK_WUVIC_get_variation_form()
{
global $woocommerce;
$items = $woocommerce->cart->get_cart_item($_POST['current_key_value']);
$product_woo_ck = wc_get_product($items['product_id']);
$selected_variation=$items['variation'];
$selected_qty=$items['quantity'];
$available_variations=$product_woo_ck->get_available_variations();
$attributes=$product_woo_ck->get_variation_attributes();
?>
<script type='text/javascript' src='<?php echo plugins_url(); ?>/woocommerce/assets/js/frontend/add-to-cart-variation.js?ver=2.6.8'></script>
<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product_woo_ck->id ); ?>" data-product_variations="<?php echo htmlspecialchars( json_encode( $available_variations ) ) ?>">
	<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>
						<td class="value">
							<?php

							
$selected=$selected_variation[ 'attribute_' . sanitize_title( $attribute_name ) ];
								wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product_woo_ck, 'selected' => $selected ) );
								echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . __( 'Clear', 'woocommerce' ) . '</a>' ) : '';
							?>
						</td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		
		<div class="single_variation_wrap">
			<div class="woocommerce-variation single_variation" style="">
	<div class="woocommerce-variation-description">
	</div>

	<div class="woocommerce-variation-price">
		<span class="price"></span>
	</div>

	<div class="woocommerce-variation-availability">
	</div>
</div><div class="woocommerce-variation-add-to-cart variations_button woocommerce-variation-add-to-cart-enabled">
<?php $targetPath = WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'img/loader.gif'; ?>
<img src="<?php echo $targetPath; ?>" alt="Smiley face" height="42" width="42" id="loder_img_btn" style="display:none;">
			<div class="quantity">
	<?php woocommerce_quantity_input( array( 'input_value' => isset( $selected_qty ) ? wc_stock_amount( $selected_qty  
	 ) : 1 ) ); ?>
</div>
<input type="hidden" id="product_thumbnail" value='<?php echo $product_woo_ck->get_image();  ?>'>

<button type="submit" class="single_add_to_cart_button button alt <?php echo get_option( 'WOO_CK_WUVIC_update_btn_class' ); ?>" id="single_add_to_cart_button_id"><?php if(get_option('WOO_CK_WUVIC_update_btn_text')!="") { echo get_option( 'WOO_CK_WUVIC_update_btn_text' ); }else{ echo "Update"; }?></button>

<span id="cancel" class="<?php echo get_option( 'WOO_CK_WUVIC_cancel_btn_class' ); ?>" onclick="cancel_update_variations('<?php echo $_POST['current_key_value']; ?>');" title="Close" style="cursor: pointer; "><?php if(get_option( 'WOO_CK_WUVIC_cancel_btn' )!=""){ echo get_option( 'WOO_CK_WUVIC_cancel_btn' ); }else{?>cancel<?php } ?></span>

<input type="hidden" name="add-to-cart" value="<?php echo absint( $product_woo_ck->id ); ?>">
<input type="hidden" name="product_id" value="<?php echo absint( $product_woo_ck->id ); ?>">
<input type="hidden" name="variation_id" class="variation_id" value="9">
<input name="old_key" class="old_key" type="hidden" value="<?php echo $_POST['current_key_value']; ?>">
</div>
</div>
</form>
<?php
die();
}
function WOO_CK_WUVIC_update_product_in_cart()
{
global $wpdb,$woocommerce;
$cart_url = $woocommerce->cart->get_cart_url();
$woocommerce->cart->remove_cart_item($_POST['old_key']);
wp_redirect($cart_url.'?'.$_POST['form_data']);die();
}
/********************** ADD MENU IN WORDPRESS DASHBOARD ************************/
/** Step 2 (from text above). */
add_action( 'admin_menu', 'WOO_CK_WUVIC_cart_variation_plugin_menu' );

/** Step 1. */
function WOO_CK_WUVIC_cart_variation_plugin_menu() {
	add_options_page( 'Cart Variation Update', 'Cart Variation Update', 'manage_options', 'woocommerce-edit-variation', 'WOO_CK_WUVIC_cart_variation_plugin_menu_option' );
}

/** Step 3. */
function WOO_CK_WUVIC_cart_variation_plugin_menu_option() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
 ?>
<h1>Woocommerce Edit Variation On cart Setting</h1>
<div class="wrap">
	<div class="col span_12" id="WOO_CK_WUVIC_form_success" style="display: none;font-size: 21px; font-style: italic;"></div>
	<form id="update_cvar_content" method="post" action="">
		<table class="form-table">
		<tbody>
		<tr>
		<th scope="row"><label for="cvar_enable">Enable</label></th>
		<td><input name="WOO_CK_WUVIC_enable" type="checkbox" id="WOO_CK_WUVIC_enable" value="enable" checked>
		<p class="description" id="cvar_enable_descr">Uncheck this box to completely disable plugin.</p>
		</td>
		</tr><!-- Enable Field -->
		<tr>
		<th scope="row"><label for="cvar_edit_link">Edit Link Text</label></th>
		<td><input name="WOO_CK_WUVIC_edit_link" type="text" id="WOO_CK_WUVIC_edit_link" value="<?php echo get_option( 'WOO_CK_WUVIC_edit_link_text' ); ?>" class="regular-text">
		<p class="description" id="cvar_link_text_descr">Text for edit link.</p>
		</td>
		</tr><!-- Edit Link Text Field -->
		<tr>
		<th scope="row"><label for="cvar_edit_link_class">Css Class For Edit Link</label></th>
		<td><input name="WOO_CK_WUVIC_edit_link_class" type="text" id="WOO_CK_WUVIC_edit_link_class" value="<?php echo get_option( 'WOO_CK_WUVIC_edit_link_class' ); ?>" class="regular-text">
		<p class="description" id="cvar_link_class_descr">Add css classes.</p>
		</td>
		</tr><!-- Css Class For Edit Link Field -->
		<tr>
		<th scope="row"><label for="cvar_update_btn">Update Button Text</label></th>
		<td><input name="WOO_CK_WUVIC_update_btn" type="text" id="WOO_CK_WUVIC_update_btn" value="<?php echo get_option( 'WOO_CK_WUVIC_update_btn_text' ); ?>" class="regular-text">
		<p class="description" id="cvar_update_btn_descr">Text for update button.</p>
		</td>
		</tr><!-- Update Button Text Field -->
		<tr>
		<th scope="row"><label for="cvar_update_btn_class">Css Class For Update Button</label></th>
		<td><input name="WOO_CK_WUVIC_update_btn_class" type="text" id="WOO_CK_WUVIC_update_btn_class" value="<?php echo get_option( 'WOO_CK_WUVIC_update_btn_class' ); ?>" class="regular-text">
		<p class="description" id="cvar_update_btn_class_descr">Add css class for update button.</p>
		</td>
		</tr><!-- Css Class For Update Button Field -->
		<tr>
		<th scope="row"><label for="cvar_cancel_btn">Cancel Button Text</label></th>
		<td><input name="WOO_CK_WUVIC_cancel_btn" type="text" id="WOO_CK_WUVIC_cancel_btn" value="" class="regular-text">
		<p class="description" id="cvar_cancel_btn_descr">Text for cancel button.</p>
		</td>
		</tr><!-- cancel Button Field -->
		<tr>
		<th scope="row"><label for="cvar_cancel_btn_class">Css Class For Cancel Button</label></th>
		<td><input name="WOO_CK_WUVIC_cancel_btn_class" type="text" id="WOO_CK_WUVIC_cancel_btn_class" value="" class="regular-text">
		<p class="description" id="cvar_cancel_btn_class_descr">Text for cancel button class.</p>
		</td>
		</tr><!-- Css Class For cancel Button Field -->
		</tbody>
		</table>
		<?php $WOO_CK_WUVIC_img_loader = WUVIC_WOO_UPDATE_CART_ASSESTS_URL.'img/uploading.gif'; ?>
		<img src="<?php echo $WOO_CK_WUVIC_img_loader; ?>" alt="Smiley face" height="42" width="42" 
		id="loder_img_cvform" style="display:none;">
		<p class="submit"><input type="button" name="cvar_submit" id="cvar_submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>
<?php
add_script_cart_variation();
}
?>
<?php 
/*********************  cart variation Jquery  ********************/
function add_script_cart_variation(){
?>
<script type="text/javascript">
jQuery(document).ready(function(){
jQuery('#cvar_submit').click(function(e){
e.preventDefault();
jQuery('#WOO_CK_WUVIC_form_success').css('display', 'none');
jQuery('#loder_img_cvform').css('display','block');
var i="yes";
var WOO_CK_WUVIC_edit_link_text = jQuery("#WOO_CK_WUVIC_edit_link").val();
var WOO_CK_WUVIC_edit_link_class = jQuery("#WOO_CK_WUVIC_edit_link_class").val();
var WOO_CK_WUVIC_update_btn = jQuery("#WOO_CK_WUVIC_update_btn").val();
var WOO_CK_WUVIC_update_btn_class = jQuery("#WOO_CK_WUVIC_update_btn_class").val();
var WOO_CK_WUVIC_cancel_btn = jQuery("#WOO_CK_WUVIC_cancel_btn").val();
var WOO_CK_WUVIC_cancel_btn_class = jQuery("#WOO_CK_WUVIC_cancel_btn_class").val();

if (jQuery('input#WOO_CK_WUVIC_enable').is(':checked')) { 
	var WOO_CK_WUVIC_enable = "true";
}
else{
	var WOO_CK_WUVIC_enable = "false";
}
var baseUrl = document.location.origin;	
var final_sring="action=cart_variation_edit&WOO_CK_WUVIC_edit_link="+WOO_CK_WUVIC_edit_link_text+"&WOO_CK_WUVIC_edit_link_class="+WOO_CK_WUVIC_edit_link_class+"&WOO_CK_WUVIC_update_btn="+
WOO_CK_WUVIC_update_btn+"&WOO_CK_WUVIC_update_btn_class="+WOO_CK_WUVIC_update_btn_class+"&WOO_CK_WUVIC_enable="+WOO_CK_WUVIC_enable+"&WOO_CK_WUVIC_cancel_btn="+WOO_CK_WUVIC_cancel_btn+"&WOO_CK_WUVIC_cancel_btn_class="+WOO_CK_WUVIC_cancel_btn_class; 
var ajaxurl =baseUrl+'/wp-admin/admin-ajax.php';
jQuery.ajax({
type:    "POST",
url:     ajaxurl,
dataType: 'json',
data:    final_sring,
// async : false,
success: function(data){
	jQuery('#loder_img_cvform').css('display','none');
	jQuery('#WOO_CK_WUVIC_form_success').css('display', 'block');
	jQuery('#WOO_CK_WUVIC_form_success').text('Updated Successfully');
}
}); 
});
});
</script>
<?php } 
add_action('wp_ajax_nopriv_cart_variation_edit', 'cart_variation_edit_callback');
add_action('wp_ajax_cart_variation_edit', 'cart_variation_edit_callback');
function cart_variation_edit_callback() {
update_option( 'WOO_CK_WUVIC_status', sanitize_text_field($_POST['WOO_CK_WUVIC_enable']) );
update_option( 'WOO_CK_WUVIC_edit_link_text', sanitize_text_field($_POST['WOO_CK_WUVIC_edit_link']) );
update_option( 'WOO_CK_WUVIC_edit_link_class', sanitize_text_field($_POST['WOO_CK_WUVIC_edit_link_class']) );
update_option( 'WOO_CK_WUVIC_update_btn_text',sanitize_text_field($_POST['WOO_CK_WUVIC_update_btn']) );
update_option( 'WOO_CK_WUVIC_update_btn_class', sanitize_text_field($_POST['WOO_CK_WUVIC_update_btn_class']) );
update_option( 'WOO_CK_WUVIC_cancel_btn',sanitize_text_field($_POST['WOO_CK_WUVIC_cancel_btn']));
update_option( 'WOO_CK_WUVIC_cancel_btn_class', sanitize_text_field($_POST['WOO_CK_WUVIC_cancel_btn_class']) );
die();
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );
function add_action_links ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'options-general.php?page=woocommerce-edit-variation' ) . '">Settings</a>',
 );
return array_merge( $links, $mylinks );
}
?>