<?php
/*
 * Plugin Name: MedioPay
 * Description: This plugin allows PayWalls and Tip Button for Wordpress
 * Version: 1.7
 * Requires at least: 4.7
 * Requires PHP: 6.2
 * Author: MedioPay
 * Author URI: https://mediopay.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */


//
// Starting the plugin: Activate and Deactive, load files and scripts
//

// Activation, Deactivation, Uninstall
register_uninstall_hook( 'mediopay/mediopay.ph', 'uninstall_mediopay' );

require('functions/mp_basic_functions.php');
require('functions/mp_editor_functions.php');
require('functions/mp_settings_functions.php');
require('classes/mp_post_data.php');
require('functions/mp_html_functions.php');


function mp_load_post_data() {
	
}
add_action( 'init', 'mp_load_post_data' );


register_deactivation_hook( 'mediopay/mediopay.php', 'mediopaydeactivate' );
register_activation_hook( 'mediopay/mediopay.php', 'mediopayactivate' );
register_activation_hook( 'mediopay/mediopay.php', 'mediopayactivate_data' );
add_action( 'wp_enqueue_scripts', 'mediopay_add_scripts' );
add_action( 'admin_enqueue_scripts', 'mediopay_add_admin_scripts' );

// Register Option for MedioPay

add_action( 'admin_init', 'mediopay_register_settings' );
add_action('admin_menu', 'mediopay_register_options_page');


//
// save settings a user made in the dashboard
//

if (isset($_POST['settings'])) {
if(isset($_POST['MedioPay_address']) OR 
	isset($_POST['MedioPay_currency']) OR 
	isset($_POST['MedioPay_deactivate_metadata']) OR 
	isset($_POST['MedioPay_sharing_quote']) OR 
	isset($_POST['MedioPay_ref_quote']) OR 
	isset($_POST['MedioPay_fixed_amount']) OR 
	isset($_POST['MedioPay_fixed_amount_tips']) OR 
	isset($_POST['MedioPay_fixed_thank_you']) OR 
	isset($_POST['MedioPay_bar_color']) OR 
	isset($_POST['MedioPay_paywall_msg']) OR 
	isset($_POST['MedioPay_editable_tips']) OR 
	isset($_POST['MedioPay_address_2']) OR 
	isset($_POST['MedioPay_second_address_share']) OR 
	isset($_POST['MedioPay_link_color']) OR
	isset($_POST['MedioPay_align_left'])		
  ) {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		
		if(isset($_POST['MedioPay_address'])) {
			$newaddress = sanitize_text_field($_POST["MedioPay_address"]);
		$newaddress = array( 'address' => $newaddress );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newaddress,$data_where);
	}
	if(isset($_POST['MedioPay_currency'])) {
		$newcurrency = sanitize_text_field($_POST['MedioPay_currency']);
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newcurrency = array( 'currency' => $newcurrency );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newcurrency,$data_where);
	}
	if(isset($_POST['MedioPay_sharing_quote'])) {
		$newsharing = sanitize_text_field($_POST["MedioPay_sharing_quote"]);
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newsharing = array( 'sharingQuote' => $newsharing );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newsharing,$data_where);
	}
	if(isset($_POST['MedioPay_ref_quote'])) {
		$newref = sanitize_text_field($_POST["MedioPay_ref_quote"]);
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newref= array( 'ref' => $newref );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newref,$data_where);
	}
	if(isset($_POST['MedioPay_fixed_amount'])) {
		$newfixed = sanitize_text_field($_POST["MedioPay_fixed_amount"]);
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newfixed = array( 'fixedAmount' => $newfixed );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newfixed,$data_where);
	}
	if(isset($_POST['MedioPay_fixed_amount_tips'])) {
		$newfixedtips = sanitize_text_field($_POST["MedioPay_fixed_amount_tips"]);
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newfixedtips = array( 'fixedTipAmount' => $newfixedtips );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newfixedtips,$data_where);
	}
	if(isset($_POST['MedioPay_fixed_thank_you'])) {
		$newthankyou = sanitize_text_field($_POST["MedioPay_fixed_thank_you"]);
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newthankyou = array( 'fixedThankYou' => $newthankyou );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newthankyou,$data_where);
	}
	if(isset($_POST['MedioPay_deactivate_metadata'])) {
		$newmetadata = sanitize_text_field($_POST["MedioPay_deactivate_metadata"]);
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newmetadata = array( 'noMetanet' => $newmetadata );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newmetadata,$data_where);
	}
	else {
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newmetadata = array( 'noMetanet' => 'no' );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newmetadata,$data_where);
	}
	if(isset($_POST['MedioPay_deactivate_edit'])) {
		$newedit = sanitize_text_field($_POST["MedioPay_deactivate_edit"]);
		//echo $newedit;
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newedit = array( 'noEditField' => $newedit );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newedit,$data_where);
	}
	else {
		$newedit = array( 'noEditField' => 'no' );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newedit,$data_where);
	}
	if(isset($_POST['MedioPay_bar_color'])) {
		$newedit = sanitize_hex_color($_POST["MedioPay_bar_color"]);
		//echo $newedit;
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newedit = array( 'barColor' => $newedit );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newedit,$data_where);
	}
	
	if(isset($_POST['MedioPay_thisURL'])) {
		$thisURL = esc_url($_POST['MedioPay_thisURL']);
		//echo "<script>thisURL='" . $thisURL . "';</script>";
		//echo $thisURL;
	}
	if(isset($_POST['MedioPay_paywall_msg'])) {
		$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
		if ( isset($myrows[0]->paywallMsg)) {
			$newedit = sanitize_text_field($_POST["MedioPay_paywall_msg"]);
			$newedit = array( 'paywallMsg' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
		else {
			 $wpdb->query("ALTER TABLE " . $table_name . " ADD paywallMsg tinytext NOT NULL");
			 $newedit = sanitize_text_field($_POST["MedioPay_paywall_msg"]);
			$newedit = array( 'paywallMsg' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);
		}
	}	
	if(isset($_POST['MedioPay_tipping_msg'])) {
		$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
		if ( isset($myrows[0]->tippingMsg)) {
			$newedit = sanitize_text_field($_POST["MedioPay_tipping_msg"]);
			$newedit = array( 'tippingMsg' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
		else {
			 $wpdb->query("ALTER TABLE " . $table_name . " ADD tippingMsg tinytext NOT NULL");
			 $newedit = sanitize_text_field($_POST["MedioPay_tipping_msg"]);
			$newedit = array( 'tippingMsg' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);
		}
	}	
	if(isset($_POST['MedioPay_editable_tips'])) {
		$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
		if ( isset($myrows[0]->editableTips)) {
			$newedit = sanitize_text_field($_POST["MedioPay_editable_tips"]);
			$newedit = array( 'editableTips' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
		else {
			 $wpdb->query("ALTER TABLE " . $table_name . " ADD editableTips tinytext NOT NULL");
			 $newedit = sanitize_text_field($_POST["MedioPay_editable_tips"]);
			$newedit = array( 'editableTips' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);
		}
	}	
	else {
		if ( isset($myrows[0]->editableTips)) {
			$newmetadata = array( 'editableTips' => 'no' );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newmetadata,$data_where);
		}
		else {
			$wpdb->query("ALTER TABLE " . $table_name . " ADD editableTips tinytext NOT NULL");
			$newedit = 'no';
			$newedit = array( 'editableTips' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
	}
	if(isset($_POST['MedioPay_address_2'])) {
		$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
		if ( isset($myrows[0]->address2)) {
			$newedit = sanitize_text_field($_POST["MedioPay_address_2"]);
			$newedit = array( 'address2' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
		else {
			 $wpdb->query("ALTER TABLE " . $table_name . " ADD address2 tinytext NOT NULL");
			 $newedit = sanitize_text_field($_POST["MedioPay_address_2"]);
			$newedit = array( 'address2' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
	}	
	else {
		if ( isset($myrows[0]->address2)) {
			$newmetadata = array( 'address2' => 'none' );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newmetadata,$data_where);
		}
		else {
			$wpdb->query("ALTER TABLE " . $table_name . " ADD address2 tinytext NOT NULL");
			$newedit = 'none';
			$newedit = array( 'address2' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
	}	
	
	if(isset($_POST['MedioPay_second_address_share'])) {
		$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
		if ( isset($myrows[0]->secondAddressShare)) {
			$newedit = sanitize_text_field($_POST["MedioPay_second_address_share"]);
			$newedit = array( 'secondAddressShare' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
		else {
			 $wpdb->query("ALTER TABLE " . $table_name . " ADD secondAddressShare tinytext NOT NULL");
			 $newedit = sanitize_text_field($_POST["MedioPay_second_address_share"]);
			$newedit = array( 'secondAddressShare' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);
		}
	}	
	else {
		if ( isset($myrows[0]->secondAddressShare)) {
			$newmetadata = array( 'secondAddressShare' => 'none' );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newmetadata,$data_where);
		}
		else {
			$wpdb->query("ALTER TABLE " . $table_name . " ADD secondAddressShare tinytext NOT NULL");
			$newedit = 'none';
			$newedit = array( 'secondAddressShare' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
	}	
	if(isset($_POST['MedioPay_link_color'])) {
		//echo "Set link color";
		$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
		if ( isset($myrows[0]->linkColor)) {
			$newedit = sanitize_text_field($_POST["MedioPay_link_color"]);
			$newedit = array( 'linkColor' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
		else {
			 $wpdb->query("ALTER TABLE " . $table_name . " ADD linkColor tinytext NOT NULL");
			 $newedit = sanitize_text_field($_POST["MedioPay_link_color"]);
			$newedit = array( 'linkColor' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);
		}
	}
	/*
	if(isset($_POST['MedioPay_align_left'])) {
		$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
		if ( isset($myrows[0]->editableTips)) {
			$newedit = sanitize_text_field($_POST["MedioPay_align_left"]);
			$newedit = array( 'alignLeft' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
		else {
			 $wpdb->query("ALTER TABLE " . $table_name . " ADD alignLeft tinytext NOT NULL");
			 $newedit = sanitize_text_field($_POST["MedioPay_align_left"]);
			$newedit = array( 'alignLeft' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);
		}
	}	
	else {
		if ( isset($myrows[0]->editableTips)) {
			$newmetadata = array( 'alignLeft' => 'no' );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newmetadata,$data_where);
		}
		else {
			$wpdb->query("ALTER TABLE " . $table_name . " ADD alignLeft tinytext NOT NULL");
			$newedit = 'no';
			$newedit = array( 'alignLeft' => $newedit );
			$data_where = array( 'id' => 1);
			$wpdb->update($table_name,$newedit,$data_where);	
		}
	}*/	
	//echo "<script>location.replace(thisURL);</script>";
}
}


// Hook functions in the dashboard

add_action( 'add_meta_boxes', 'mediopay_custom_meta_paidcontent' );
add_action( 'add_meta_boxes', 'mediopay_custom_meta_tips' );
add_action( 'add_meta_boxes', 'mediopay_custom_meta_second_receiver' );
add_action( 'save_post', 'mediopay_meta_save' );


//
// Load a blog page
//


// activate PayWall from the second editor field

function mediopay_create_paywall($post_content) {
	$blogpath = get_bloginfo($show = 'wpurl') . "/";
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$mp_fullcontent1 = $post_content;
	if ($blogpath !== $actual_link) {	
	
	$mp_shortcode_paywall = "no";
	$mp_shortcode_paywall = "no";
	$mp_shortcode_tipme = "no";
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$mp_home_url = get_home_url() . "/";
	$mypost_id = url_to_postid($actual_link);
	
	if(is_preview()){ 
		echo "<script>var mp_preview='yes';</script>";
	}
	else { 
		echo "<script>var mp_preview='no';</script>";
	}
	$mp_activate1 = get_post_meta( $mypost_id, 'meta-paidcontent', true );
	$mp_activate2 = get_post_meta( $mypost_id, 'mp_meta_checkbox', true );
	
	
	if (strlen($mp_activate1) > 3 OR $mp_activate2 == "yes") {
			$mp_editableTips = (isset($mp_post_data->editableTips)) ? $mp_post_data->editableTips : "no";
			$mp_post_data = new stdClass();
			mp_create_object($mp_post_data);		
			if (strlen($mp_activate1) > 3) {
				$mp_realContent = get_post_meta( $mypost_id, 'meta-paidcontent', true );
				$mp_realContent =  json_encode($mp_realContent);
				$mp_lengthContent = strlen($mp_realContent);
				if ($mp_lengthContent > 300) {
					$mp_fading_content = substr( $mp_post_data->paidcontent, 0, 300);
					$mp_fading_content = wp_strip_all_tags( $mp_fading_content);
				}
				else {
					$mp_fading_content =  wp_strip_all_tags( $mp_post_data->paidcontent);
				}
			}	
	}		
	if ( has_shortcode( $post_content, 'paywall' ) ) {
		 		echo "<script>mp_shortCode = 'paywall'; </script>";
		 		$mp_shortcode_paywall = "yes";
			 	$mp_fullcontent1 .= "</div><div id='mp_fade' class='mp_fading mp_invisible' >";
				if (isset($mp_fading_content)) {
					 $mp_fullcontent1 .= $mp_fading_content . "</div>";				
				}				
	}
	if ( has_shortcode( $post_content, 'tipme' ) ) {
		$mp_shortcode_tipme = "yes";
		if ($mp_shortcode_paywall == "yes") {
			echo "<script>mp_shortCode2 = 'tipme';</script>";	
		}
		else {
			echo "<script>mp_shortCode = 'tipme';</script>";
			$mp_fullcontent1 .= "</div><div id='mp_fade' class='mp_fading' >";	
			if (isset($mp_fading_content)) {
				$mp_fullcontent1 .= $mp_fading_content . "</div>";
			}
		}
	}
	else if (!has_shortcode( $post_content, 'paywall' )) {
			 $mp_fullcontent1 .= "<div id='mp_fade' class='mp_fading' >";
			 if (isset($mp_fading_content)) {
				 $mp_fullcontent1 .= $mp_fading_content . "</div>";
			}
			 echo "<script>mp_shortCode = 'no'; console.log('no shortcode')</script>";
   }	

	if (strlen($mp_activate1) > 3) {
		$path = plugin_dir_url( 'mediopay.php');
		$path = $path . "mediopay/lib/";
		if ( has_shortcode( $post_content, 'paywall' ) ) {
		 	$mp_fullcontent1 .= "<div class='mp_frame1 mp_invisible' id='mp_frame1' style='background-color:" . $mp_post_data->barColor  . "'>";
		}
		else {
		 	$mp_fullcontent1 .= "<div class='mp_frame1' id='mp_frame1' style='background-color:" . $mp_post_data->barColor  . "'>";
		}
		$mp_fullcontent1 .= behindthewall("eins", $mp_lengthContent, $mp_post_data, "0") . "<script>MedioPay_textColor('mp_frame1');
	   		</script>
	   		<div class='money-button' id='mbutton1'></div>
	   		<div id='mp_counter1' style='margin-top:7px'></div>
	   	</div>
	   	<div id='mp_unlockable1'>
	   	</div>";
	   if ($mp_post_data->checkbox === "yes") {
	   	echo "<script>console.log('checkbox at bottom');</script>";
	   	echo "<script>mp_checkBox = 'yes';</script>";
	   	if ($mp_editableTips == "yes") {
				$mp_fullcontent1 .= "<div style='clear:both;'></div>
	   			<br /><div class='mp_frame1 mp_invisible' id='mp_tipFrame' style='background-color:" . $mp_post_data->barColor  . "'>
	   			<script>MedioPay_textColor('mp_tipFrame');</script>
	   			<span class='paywallheader'>". $mp_post_data->tippingMsg . "</span><br />
					<span class='paywallbody'>No registration. No subscription. Just one swipe.</span>  
					<div id='editable_mbutton_wrap_1' class='mp_choose_amount' style='margin-top:20px'>
								How much do you want to tip?<br />						
								<input type='number' id='mp_editable_1' style='width:100px' step='.01'></input> " . $mp_post_data->currency . 
								"&nbsp; &nbsp; &nbsp; &nbsp; <input type='button' onclick='mp_createObject(\"editableTips\")' value='Tip'>
						</div>
						<div class='money-button' id='editable_mbutton_1' style='margin-top:20px'></div>
					<div id='counterTips'></div>
					</div>";
					//echo "<script>mp_alignLeft('mp_tipFrame', 'editable_mbutton_1');</script>";
				?>
	 			<script>
	 				if (typeof mp_shortCode !== "undefined") {
						if (mp_shortCode !== "paywall" && mp_shortCode !== "tipme") {	
							mp_checkCookie("editor", "undefine");	
						}
					}
					else {
						mp_checkCookie("editor", "undefine");
					}
			 	</script>					
				<?php
	   	}		
	   	else {
	   		$mp_fullcontent1 .=  
	   			"<div style='clear:both;'></div>
	   			<br /><div class='mp_frame1 mp_invisible' id='mp_tipFrame' style='background-color:" . $mp_post_data->barColor  . "'>
	   			<script>MedioPay_textColor('mp_tipFrame');</script>
	   			<span class='paywallheader'>". $mp_post_data->tippingMsg . "</span><br />
					<span class='paywallbody'>No registration. No subscription. Just one swipe.</span>
					<div class='money-button' id='tbutton' style='margin-top:20px'></div>
					<div id='counterTips'></div>
					</div>";
				?>
	 			<script>
	 				if (typeof mp_shortCode !== "undefined") {
						if (mp_shortCode !== "paywall" && mp_shortCode !== "tipme") {	
							mp_checkCookie("editor", "undefine");	
						}
					}
					else {
						mp_checkCookie("editor", "undefine");
					}
			 	</script>					
				<?php
		 	}
		}
		else if ($mp_post_data->checkbox === "no") {
			echo "<script>console.log('here1' + '" . strlen($mp_realContent) . "');</script>";
				  ?>
	 				<script>
	 				if (typeof mp_shortCode !== "undefined") {
						if (mp_shortCode !== "paywall" && mp_shortCode !== "tipme") {	
							mp_checkCookie("editor", "undefine");	
						}
					}
					else {
						mp_checkCookie("editor", "undefine");
					}
			 		</script>					
					<?php	
			}
		}						
		else {
			$mp_class = ($mp_shortcode_paywall == "yes") ? "mp_frame1 mp_invisible" : "mp_frame1";	
			if ($mp_editableTips === "yes") {
	   			$mp_fullcontent1 .=  
				"</div><div style='clear:both;'></div>
	   			<div class='" . $mp_class; echo "' id='mp_tipFrame' style='background-color:" . $mp_post_data->barColor; echo "'>
	   			<script>MedioPay_textColor('mp_tipFrame');</script>
	   			<span class='paywallheader'>". $mp_post_data->tippingMsg; echo "</span><br />
					<span class='paywallbody'>No registration. No subscription. Just one swipe.</span>  
					<div id='editable_mbutton_wrap_1' style='margin-top:20px' class='mp_choose_amount'>
								How much do you want to tip?<br />						
								<input type='number' id='mp_editable_1' style='width:100px' step='.01'></input> " . $mp_post_data->currency; echo
								"&nbsp; &nbsp; &nbsp; &nbsp; <input type='button' onclick='mp_createObject(\"editableTips\")' value='Tip'>
						</div>
						<div class='money-button' id='editable_mbutton_1' style='margin-top:20px'></div>
					<div id='counterTips'></div>
					</div><script>MedioPay_textColor('mp_tipFrame');
	   		</script>";
				//echo "<script>mp_alignLeft('mp_tipFrame', 'tbutton');</script>";
				  ?>
	 				<script>
	 				if (typeof mp_shortCode !== "undefined") {
						if (mp_shortCode !== "paywall" && mp_shortCode !== "tipme") {	
							mp_checkCookie("editor", "undefine");	
						}
					}
					else {
						mp_checkCookie("editor", "undefine");
					}
			 		</script>					
					<?php
	   	}		
			else {
				$mp_fullcontent1 .=  
				"</div><div style='clear:both;'></div>
				<div class='" . $mp_class . "' id='mp_tipFrame' style='background-color:" . $mp_post_data->barColor . "'>
				<span class='paywallheader'>" . $mp_post_data->tippingMsg . "</span><br />
				<span class='paywallbody'>No registration. No subscription. Just one swipe.</span>
				<div class='money-button' id='tbutton' style='margin-top:20px'></div>
				<div id='counterTips'></div>
				</div>
				<script>MedioPay_textColor('mp_tipFrame');
	   		</script>";
	   		  ?>
	 				<script>
	 				if (typeof mp_shortCode !== "undefined") {
						if (mp_shortCode !== "paywall" && mp_shortCode !== "tipme") {	
							mp_checkCookie("editor", "undefine");	
						}
					}
					else {
						mp_checkCookie("editor", "undefine");
					}
			 		</script>				
					<?php
	  		}
		}
	}
   return $mp_fullcontent1;
}


add_filter('the_content', 'mediopay_create_paywall');


// use PayWall with shortcodes. All the operations are the same as with the second editor field.


add_shortcode( 'paywall', 'MedioPay_paywall_function' );

function MedioPay_paywall_function( $attr, $content) {
	ob_start();	
	$blogpath = get_bloginfo($show = 'wpurl') . "/";
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	if ($blogpath !== $actual_link) {
		global $wpdb;
		if (isset($mp_post_data)) {
		}
		else {
			$mp_post_data = new stdClass();
			mp_create_object($mp_post_data);
			//var_dump($mp_post_data);
		}
		$table_name = $wpdb->prefix;
		$mypost_id = url_to_postid($actual_link);
		if (isset($attr["amount"])){
			echo "<script>paymentAmount2=\"" . esc_js($attr["amount"]) . "\";</script>";
			$mp_amount = $attr["amount"];
		}
		else {
			echo "<script>paymentAmount2=\"" . esc_js($mp_post_data->amount) . "\";</script>";
			$mp_amount = $mp_post_data->amount;
		}

		$mp_lengthContent = strlen($content);
		$mp_realContent2 = $content;
		$mp_realContent2 = json_encode($mp_realContent2);
		if (strlen($mp_realContent2) > 300) {
			$mp_fading_content_2 = substr( $content, 0, 300);
			$mp_fading_content_2 = wp_strip_all_tags( $mp_fading_content_2);
		}
		else {
			$mp_fading_content_2 = wp_strip_all_tags( $content );
		}
		echo "<script>lengthText=\"" . $mp_lengthContent . "\";</script>";
		echo "<script>mp_paymentAmount2=\"" . esc_js($mp_post_data->amount) . "\";</script>";	
		$dataContent = get_the_content();
		$dataContent = substr($dataContent, 0, 168);
		$dataContent = wp_strip_all_tags( $dataContent );

		echo "<script>dataContent=\"" . esc_js($dataContent) . "\";</script>";
		echo "<script>dataLink=\"" . get_permalink() . "\";</script>";
		echo "<script>dataTitle=\"" . get_the_title() . "\";dataTitle = encodeURI(dataTitle); </script>";
	
		$mp_length = strlen($content);	
		if(is_preview()){ 
			echo "<script>var mp_preview='yes';</script>";
		}
		else { 
			echo "<script>var mp_preview='no';</script>";
		};
		?>
		<div id='mp_fade2' class='mp_fading' ><?php echo $mp_fading_content_2 ?></div>
		<div class='mp_frame2' id='mp_frame2' style='background-color:
			<?php 
				echo $mp_post_data->barColor 
			?>
		'>
				<script>MedioPay_textColor('mp_frame2');
				</script>
				<?php 
					echo behindthewall("zwei", $mp_lengthContent, $mp_post_data, $content) 
				?>
				<div class='money-button' id='mbutton2'></div>
				<div id='mp_counter2' style="margin-top:7px"></div>
		</div>
		<br />
		<div id='mp_unlockable2'>
			<div id='mp_unlockable2_content'>
			</div>
			<script>
				if (typeof mp_checkBox == "undefined") {
					mp_checkBox = "no";
				}
				if (typeof mp_shortCode2 !== "undefined") {
					if (mp_shortCode2 == 'tipme') {	
					}	
				}
				else {	
					mp_checkCookie("mp_shortcode");
					console.log("SHORTCODE SECOND " + mp_shortCode);
				}
			//mp_alignLeft('mp_frame2', 'mbutton2');
				mediopayHideNextElements();
			</script>
	<?php
	}
	return ob_get_clean();
}

// Create tipping field inside the text

add_shortcode( 'tipme', 'MedioPay_tipping_function' );

function MedioPay_tipping_function( $attr, $content) {
	ob_start();
	$blogpath = get_bloginfo($show = 'wpurl') . "/";
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if ($blogpath !== $actual_link) {
	
	global $wpdb;
	if (isset($mp_post_data)) {
	}
	else {
		$mp_post_data = new stdClass();
		mp_create_object($mp_post_data);
		//var_dump($mp_post_data);
	}
	$table_name = $wpdb->prefix;
	$mypost_id = url_to_postid($actual_link);
	$mp_amount = (isset($attr["amount"])) ? $mp_amount = $attr["amount"] : $mp_amount = $mp_post_data->tip_amount;
	echo "<script>tippingAmount2=\"" . $mp_amount . "\";</script>";
	if (isset($attr["msg"])) {
		$mp_post_data->tippingMsg = $attr["msg"];
		echo "<script>tipMeMsg = \"" . esc_js($attr["msg"]) . "\";</script>";
		$mp_tipMeMsg = $attr["msg"];
	}
	else {
		$mp_tipMeMsg = $mp_post_data->tippingMsg;	
	}	
	$mp_editableTips = (isset($mp_post_data->editableTips)) ? $mp_post_data->editableTips : "no";
	$path = plugin_dir_url( 'mediopay.php');
	$path .=  "mediopay/lib/";
	global $wpdb;
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
	$mp_barColor = $myrows[0]->barColor;
	if ($mp_editableTips === "yes") {
			echo 
	   			"<div style='clear:both;'></div>
	   				<br /><div class='mp_frame1' id='mp_tipFrame2' style='background-color:" . $mp_post_data->barColor . "'>
	   					<span class='paywallheader'>". $mp_post_data->tippingMsg . "</span><br />
						<span class='paywallbody'>No registration. No subscription. Just one swipe.</span>  
						<br /><div id='editable_mbutton_wrap' class='mp_choose_amount'><br />
						How much do you want to tip?<br />						
						<input type='number' id='mp_editable' style='width:100px' step='.01'></input> " . $mp_post_data->currency . 
						"&nbsp; &nbsp; &nbsp; &nbsp; <input type='button' onclick='mp_createObject(\"editableTips2\")' value='Tip'>
						</div>
						<div class='money-button' id='editable_mbutton' style='margin-top:20px'>
						</div>
						<div id='counterTips2'></div>
						</div><script>MedioPay_textColor('mp_tipFrame2');
	   		</script><br />";
	   		/*echo "<script>mp_alignLeft('mp_tipFrame2', 'editable_mbutton');
	   			mp_html_node = document.getElementById('mp_tipframe2'); 
	   			console.log('Hello ' + mp_html_node);
	   			</script>";*/
	   }
	   else {
		 	echo "<br /><div style='clear:both;'><div class='mp_frame1' id='mp_tipFrame2' style='background-color:" . $mp_post_data->barColor . "'><font size='5'>" . $mp_post_data->tippingMsg . "</font><br />
		<em>No registration. No subscription. Just one swipe.</em>  <span id='mp_tip' onclick='mp_getInfo(\"mp_tip\")'><img src='" . $path . "questionmark.png' width='17'
	 /></span><br /><div class='money-button' id='tbutton2'></div><div id='counterTips2'></div></div><script>MedioPay_textColor('mp_tipFrame2');
	   		</script><br />";
	   		//echo "<script>mp_alignLeft('mp_tipFrame2', 'tbutton2');</script>";
	   }
	?>
	<script>
		if (mp_shortCode == "paywall") {
			console.log("SHORTCODE EXISTS");
			mp_checkCookie("mp_shortcode", "mp_tipme");
		}
		else {
			mp_checkCookie("mp_tipme");	
		}
	</script>
	<?php
	}		
	return ob_get_clean();
}




/*add_filter('comment_form_defaults', 'add_non_fake_textarea_field');*/

//
// Processing the payments
//

// register Rest API endpoint

add_action( 'rest_api_init', function () {
  register_rest_route( 'mediopay/v1', '/throwcontent1/', array(
    'methods' => 'POST',
    'callback' => 'mp_throw_content',
  ) );
} );

// Deliver content after a paywall was payed.


function mp_throw_content( WP_REST_Request $mp_request ) {
	$mp_method = $mp_request['MedioPay_method'];
	$mp_mypost_id = $mp_request['MedioPay_postid'];
	$mp_outputs = $mp_request['MedioPay_outputs'];
	$mp_is_preview = $mp_request['MedioPay_preview'];
	if ($mp_is_preview === "yes") {
		$mp_number = $_POST['MedioPay_number'];
	}
	else {
		$mp_number = $mp_request['MedioPay_number'] + 1;
	}	
	$mp_userid = $mp_request['MedioPay_userID'];
	$mp_newCounter = $mp_request['Mediopay_newCounter'];
	$mp_share = $mp_request['MedioPay_shareQuote'];
	// if meta-newCounter doesn't exist // is no: Meta-newcounter = yes.
	if ($mp_method === "editor") {
		$mp_paid_content = get_post_meta( $mp_mypost_id, 'meta-paidcontent', true );
		$mp_paid_content = nl2br($mp_paid_content);
		$mp_paid_content = "<br />" . $mp_paid_content;
	}
	if ($mp_method === "shortcode") {
		global $wpdb;
		$table_name = $wpdb->prefix . 'posts';
		$myrows = $wpdb->get_results( "SELECT post_content FROM " . $table_name . " WHERE ID = " . $mp_mypost_id );
		$mp_paid_content = $myrows[0]->post_content;
		$mp_pos = strpos($mp_paid_content, "[paywall");
		$mp_pos_helper = substr($mp_paid_content,$mp_pos, 120);
		$mp_pos_helper_pos = strpos($mp_pos_helper, "]");
		$mp_pos = $mp_pos + $mp_pos_helper_pos + 1;
		$mp_pos2 = strpos($mp_paid_content, "[/paywall]");
		$mp_paid_content = substr($mp_paid_content, $mp_pos, ($mp_pos2 - $mp_pos));
		$mp_paid_content =  nl2br($mp_paid_content);
	}
	
	//$mp_chars_to_kill = strpos($mp_paid_content_1, "<h2><br />");
	//echo $mp_chars_to_kill;
	global $wpdb;
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE id = 1" );
	$mp_address = $myrows[0]->address;
	if (in_array($mp_address, $mp_outputs)) {
		if ($mp_method === "editor")  {
			if (get_post_meta( $mp_mypost_id, 'meta-secretword-1', true ) !== null) {
				$mp_meta_secret = get_post_meta( $mp_mypost_id, 'meta-secretword-1', true );
				if (strlen($mp_meta_secret) > 0) {
				}	
				else {
					$mp_meta_secret = rand(100000, 999999);
					update_post_meta ( $mp_mypost_id, 'meta-secretword-1', $mp_meta_secret );
				}
			}	
			else {
				$mp_meta_secret = rand(100000, 999999);
				update_post_meta ( $mp_mypost_id, 'meta-secretword-1', $mp_meta_secret );
			}
		}
		if ($mp_method === "shortcode") {
			if (get_post_meta( $mp_mypost_id, 'meta-secretword-2', true ) !== null) {
				$mp_meta_secret = get_post_meta( $mp_mypost_id, 'meta-secretword-2', true );
				if (strlen($mp_meta_secret) > 0) {
				}	
				else {
					$mp_meta_secret = rand(100000, 999999);
					update_post_meta ( $mp_mypost_id, 'meta-secretword-2', $mp_meta_secret );
				}
			}	
			else {
				$mp_meta_secret = rand(100000, 999999);
				update_post_meta ( $mp_mypost_id, 'meta-secretword-2', $mp_meta_secret );
			}
		}
		if ($mp_newCounter === "yes") {
			update_post_meta( $mp_mypost_id, 'meta_buys1', $mp_number );
			update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($mp_number === 1) {
			update_post_meta( $mp_mypost_id, 'meta-first-buys1', $mp_userid);			
		}
		if ($mp_number === 2) {
			update_post_meta( $mp_mypost_id, 'meta-second-buys1', $mp_userid);			
		}
		if ($mp_number === 3) {
			update_post_meta( $mp_mypost_id, 'meta-third-buys1', $mp_userid);			
		}
		if ($mp_number === 4) {
			update_post_meta( $mp_mypost_id, 'meta-fourth-buys1', $mp_userid);			
		}												
	}
	else {
		//echo "update newcounter";
		update_post_meta($mp_mypost_id, 'meta-newcounter', 'yes');
		update_post_meta( $mp_mypost_id, 'meta_buys1', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($mp_request['MedioPay_firstPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-first-buys1', $mp_request['MedioPay_firstPartner']);				
		}
		if ($mp_request['MedioPay_secondPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-second-buys1', $mp_request['MedioPay_secondPartner']);				
		}
		if ($mp_request['MedioPay_thirdPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-third-buys1', $mp_request['MedioPay_thirdPartner']);				
		}
		if ($mp_request['MedioPay_fourthPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-fourth-buys1', $mp_request['MedioPay_fourthPartner']);				
		}
	}
		if (strlen($mp_meta_secret) > 0) {
				$mp_output->secret = $mp_meta_secret;
				$mp_output->paidcontent = $mp_paid_content;
				$mp_output->method = $mp_method;
				$mp_output_json = json_encode($mp_output);
				return $mp_output_json;	
				//echo "secret" . $mp_meta_secret1 . "<br />" . $mp_paid_content_1;			
		}			
		else {
				return "nosecret1111" . $mp_meta_secret . $mp_paid_content;
			}
	}
	else {
		return "12345654321 Address doesn't match. Are you trying to cheat?"  . $mp_address . var_dump($mp_outputs);	
	}
	wp_die();
}


// Process tips - no Rest API needed


add_action( 'rest_api_init', function () {
  register_rest_route( 'mediopay/v1', '/handletips/', array(
    'methods' => 'POST',
    'callback' => 'mp_handleTips',
  ) );
} );

function mp_handleTips( WP_REST_Request $mp_tipsRequest ) {
	//return json_encode("hi");	
	
	$mp_mypost_id = $mp_tipsRequest['MedioPay_postid'];
	$mp_outputs = $mp_tipsRequest['MedioPay_outputs'];
	$mp_is_preview = $mp_tipsRequest['MedioPay_preview'];
	$mp_amount = $mp_tipsRequest['MedioPay_amount'];
	if ($mp_is_preview === "yes") {
		$mp_number = $mp_tipsRequest['MedioPay_number'];
	}
	else {
		$mp_number = $mp_tipsRequest['MedioPay_number'] + 1;
	}
	$mp_userid = $mp_tipsRequest['MedioPay_userID'];
	$mp_newCounter = $mp_tipsRequest['Mediopay_newCounter'];
	$mp_share = $mp_tipsRequest['MedioPay_shareQuote'];
	if ($mp_newCounter === "yes") {
		update_post_meta( $mp_mypost_id, 'meta_tips', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($mp_number === 1) {
			update_post_meta( $mp_mypost_id, 'meta-first-tips', $mp_userid);			
		}
		if ($mp_number === 2) {
			update_post_meta( $mp_mypost_id, 'meta-second-tips', $mp_userid);			
		}
		if ($mp_number === 3) {
			update_post_meta( $mp_mypost_id, 'meta-third-tips', $mp_userid);			
		}
		if ($mp_number === 4) {
			update_post_meta( $mp_mypost_id, 'meta-fourth-tips', $mp_userid);			
		}	
		$current_amount =  get_post_meta( $mp_mypost_id, 'meta-tippedAmount', true );
		if (isset($current_amount)) {
			if (strlen($current_amount) > 0 && $current_amount > 0) {
				$newamount = $current_amount + $mp_amount;
				update_post_meta( $mp_mypost_id, 'meta-tipped-amount', $newamount);							
			}		
			else {
				update_post_meta( $mp_mypost_id, 'meta-tipped-amount', $mp_amount);			
			}
		}
		else {
			update_post_meta( $mp_mypost_id, 'meta-tipped-amount', $mp_amount);
		}							
	}
	else {
		update_post_meta( $mp_mypost_id, 'meta-newcounter', 'yes');
		update_post_meta( $mp_mypost_id, 'meta_tips', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($mp_tipsRequest['MedioPay_firstPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-first-tips', $mp_tipsRequest['MedioPay_firstPartner']);				
		}
		if ($mp_tipsRequest['MedioPay_secondPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-second-tips', $mp_tipsRequest['MedioPay_secondPartner']);				
		}
		if ($mp_tipsRequest['MedioPay_thirdPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-third-tips', $mp_tipsRequest['MedioPay_thirdPartner']);				
		}
		if ($mp_tipsRequest['MedioPay_fourthPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-fourth-tips', $mp_tipsRequest['MedioPay_fourthPartner']);				
		}
	}
	return json_encode("handled tips");
wp_die();
}

add_action ( 'wp_ajax_mp_process_tip', 'mp_process_tip' );
add_action ( 'wp_ajax_nopriv_mp_process_tip', 'mp_process_tip' );


function mp_process_tip() {
	$mp_mypost_id = $_POST['MedioPay_postid'];
	$mp_outputs = $_POST['MedioPay_outputs'];
	$mp_is_preview = $_POST['MedioPay_preview'];
	$mp_amount = $_POST['MedioPay_amount'];
	echo $mp_amount;
	if ($mp_is_preview === "yes") {
		$mp_number = $_POST['MedioPay_number'];
	}
	else {
		$mp_number = $_POST['MedioPay_number'] + 1;
	}
	$mp_userid = $_POST['MedioPay_userID'];
	$mp_newCounter = $_POST['Mediopay_newCounter'];
	$mp_share = $_POST['MedioPay_shareQuote'];
	if ($mp_newCounter === "yes") {
		update_post_meta( $mp_mypost_id, 'meta_tips', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($mp_number === 1) {
			update_post_meta( $mp_mypost_id, 'meta-first-tips', $mp_userid);			
		}
		if ($mp_number === 2) {
			update_post_meta( $mp_mypost_id, 'meta-second-tips', $mp_userid);			
		}
		if ($mp_number === 3) {
			update_post_meta( $mp_mypost_id, 'meta-third-tips', $mp_userid);			
		}
		if ($mp_number === 4) {
			update_post_meta( $mp_mypost_id, 'meta-fourth-tips', $mp_userid);			
		}	
		$current_amount =  get_post_meta( $mp_mypost_id, 'meta-tippedAmount', true );
		if (isset($current_amount)) {
			if (strlen($current_amount) > 0 && $current_amount > 0) {
				$newamount = $current_amount + $mp_amount;
				update_post_meta( $mp_mypost_id, 'meta-tipped-amount', $newamount);							
			}		
			else {
				update_post_meta( $mp_mypost_id, 'meta-tipped-amount', $mp_amount);			
			}
		}
		else {
			update_post_meta( $mp_mypost_id, 'meta-tipped-amount', $mp_amount);
		}							
	}
	else {
		update_post_meta( $mp_mypost_id, 'meta-newcounter', 'yes');
		update_post_meta( $mp_mypost_id, 'meta_tips', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($_POST['MedioPay_firstPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-first-tips', $_POST['MedioPay_firstPartner']);				
		}
		if ($_POST['MedioPay_secondPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-second-tips', $_POST['MedioPay_secondPartner']);				
		}
		if ($_POST['MedioPay_thirdPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-third-tips', $_POST['MedioPay_thirdPartner']);				
		}
		if ($_POST['MedioPay_fourthPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-fourth-tips', $_POST['MedioPay_fourthPartner']);				
		}
	}
wp_die();
}


// Process Cookies - Rest API needed

add_action( 'rest_api_init', function () {
  register_rest_route( 'mediopay/v1', '/processcookies/', array(
    'methods' => 'POST',
    'callback' => 'mp_processCookies',
  ) );
} );

// Deliver content after a paywall was payed.


function mp_processCookies( WP_REST_Request $mp_cookieRequest ) {
	$mp_mypost_id = $mp_cookieRequest['MedioPay_postid'];
	$mp_cookies = $mp_cookieRequest['mp_cookies'];
	$mp_position_paywall = $mp_cookieRequest['mp_position'];
	
	$mp_output->position = $mp_position_paywall;		
	$mp_meta_secret1 = get_post_meta( $mp_mypost_id, 'meta-secretword-1', true );
	$mp_meta_secret2 = get_post_meta( $mp_mypost_id, 'meta-secretword-2', true );
	if ($mp_position_paywall === "editor") {	
		if ( strpos($mp_cookies, $mp_meta_secret1) !== false ) {
			global $wpdb;
	   	$table_name = $wpdb->prefix . 'mediopay';
			$mp_paid_content_1 = get_post_meta( $mp_mypost_id, 'meta-paidcontent', true );
			$bodytag = str_replace("%body%", "schwarz", "<body text='%body%'>");	
			$mp_paid_content_1 = nl2br($mp_paid_content_1);
			$mp_paid_content_1 = str_replace("</h2><br />", "</h2><p>", $mp_paid_content_1);		
			$mp_output->paidcontent = "<br />" . $mp_paid_content_1;
			$mp_output_json = json_encode($mp_output);
			return $mp_output_json;	
		}
		else {
			return "f... ";
		}
	}
	if ($mp_position_paywall === "mp_shortcode") {	
		if ( strpos($mp_cookies, $mp_meta_secret2) !== false ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'posts';
			$myrows = $wpdb->get_results( "SELECT post_content FROM " . $table_name . " WHERE ID = " . $mp_mypost_id );
			$mp_paid_content_2 = $myrows[0]->post_content;
			$mp_pos = strpos($mp_paid_content_2, "[paywall]");
			$mp_pos2 = strpos($mp_paid_content_2, "[/paywall]");
			$mp_paid_content_2 = substr($mp_paid_content_2, ($mp_pos + 9), ($mp_pos2 - $mp_pos - 9));
			$mp_paid_content_2 = nl2br($mp_paid_content_2);
			$mp_output->paidcontent = $mp_paid_content_2;
			$mp_output_json = json_encode($mp_output);
			return $mp_output_json;	
		}
		else {	
			return "f. " . $mp_meta_secret2;
		}
	}
	wp_die();
}	











add_action ( 'wp_ajax_mp_process_cookies', 'mp_process_cookies' );
add_action ( 'wp_ajax_nopriv_mp_process_cookies', 'mp_process_cookies' );

function mp_process_cookies() {
	$mp_mypost_id = $_POST['MedioPay_postid'];
	$mp_cookies = $_POST['mp_cookies'];
	$mp_position_paywall = $_POST['mp_position'];
	$mp_meta_secret1 = get_post_meta( $mp_mypost_id, 'meta-secretword-1', true );
	$mp_meta_secret2 = get_post_meta( $mp_mypost_id, 'meta-secretword-2', true );
	if ($mp_position_paywall === "second_editor") {	
		if ( strpos($mp_cookies, $mp_meta_secret1) !== false ) {
			global $wpdb;
	   	$table_name = $wpdb->prefix . 'mediopay';
			$mp_paid_content_1 = get_post_meta( $mp_mypost_id, 'meta-paidcontent', true );
			
			$bodytag = str_replace("%body%", "schwarz", "<body text='%body%'>");
						
			$mp_paid_content_1= nl2br($mp_paid_content_1);
			$mp_paid_content_1 = str_replace("</h2><br />", "</h2><p>", $mp_paid_content_1);		
			echo "<br />" . $mp_paid_content_1;	
		}
		else {
			echo "f... ";
		}
	}
	if ($mp_position_paywall === "mp_shortcode") {	
		if ( strpos($mp_cookies, $mp_meta_secret2) !== false ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'posts';
			$myrows = $wpdb->get_results( "SELECT post_content FROM " . $table_name . " WHERE ID = " . $mp_mypost_id );
			$mp_paid_content_2 = $myrows[0]->post_content;
			$mp_pos = strpos($mp_paid_content_2, "[paywall]");
			$mp_pos2 = strpos($mp_paid_content_2, "[/paywall]");
			$mp_paid_content_2 = substr($mp_paid_content_2, ($mp_pos + 9), ($mp_pos2 - $mp_pos - 9));
			$mp_paid_content_2 = nl2br($mp_paid_content_2);
			//$mp_chars_to_kill = strpos($mp_paid_content_1, "<h2><br />");
			//echo "to kill" . $mp_chars_to_kill;
			return $mp_paid_content_2;
		}
		else {	
			return "f. " . $mp_meta_secret2;
		}
	}
wp_die();
}

/*
do_action( ' pre_comment_on_post', int $comment_post_ID );

function action_pre_comment_on_post( $array ) {
	echo "<script>console.log('comment');</script>";
}

add_action( 'pre_comment_on_post', 'action_pre_comment_on_post', 10, 1);

function add_non_fake_textarea_field( $default ) {
	$commenter = wp_get_current_commenter();
	$default['comment_notes_after'] .= 
	'<p class="comment-form-just_another_id">
	<label for="just_another_id">Comment:</label>
	<textarea id="just_another_id" name="just_another_id" cols="45" rows="8" aria-required="true"></textarea>
	</p>';
	return $default;
}*/
 





?>
