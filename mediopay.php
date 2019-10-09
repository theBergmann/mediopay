<?php
/*
 * Plugin Name: MedioPay
 * Description: This plugin allows PayWalls and Tip Button for Wordpress
 * Version: 0.1
 * Requires at least: 4.6
 * Requires PHP: 7.2
 * Author: MedioPay
 * Author URI: https://mediopay.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html 

 */

// Activation, Deactivation, Uninstall
register_uninstall_hook( 'MedioPay/mediopay.ph', 'uninstall_mediopay' );

function uninstall_mediopay() {
	 global $wpdb;
    $table_name = $wpdb->prefix . "mediopay";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}	

register_deactivation_hook( 'MedioPay/mediopay.php', 'mediopaydeactivate' );

function mediopaydeactivate() {
	  global $wpdb;
    $table_name = $wpdb->prefix . "mediopay";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}


register_activation_hook( 'MedioPay/mediopay.php', 'mediopayactivate' );
register_activation_hook( 'MedioPay/mediopay.php', 'mediopayactivate_data' );

// Create Table in Database

function mediopayactivate() {
	global $wpdb;
   $table_name = $wpdb->prefix . "mediopay";
   $charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		address tinytext NOT NULL,
		currency tinytext NOT NULL,
		versionNumber tinytext NOT NULL,
		sharingQuote tinytext NOT NULL,
		ref tinytext NOT NULL,
		fixedAmount tinytext NOT NULL,
		fixedTipAmount tinytext NOT NULL,
		fixedThankYou tinytext NOT NULL,
		noMetanet tinytext NOT NULL,
		noEditField tinytext NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql ); 
}

// Set Dummy attributes in table

function mediopayactivate_data() {
	global $wpdb;
	$welcome_name = 'none';
	$welcome_address = 'none';
	$version_number = '0.1';
	$sharing_quote = '0.1';
	$ref = '0.1';
	$fixed_amount = 'none';
	$fixed_tip_amount = 'none';
	$fixed_thank_you = 'none';
	$no_metanet = 'no';
	$table_name = $wpdb->prefix . 'mediopay';
	$no_edit_field = 'no';
	$wpdb->insert( 
		$table_name, 
		array( 
			//'time' => current_time( 'mysql' ), 
			'address' => $welcome_name, 
			'currency' => $welcome_address,
			'versionNumber' => $version_number,
			'sharingQuote' => $sharing_quote,
			'ref' => $ref,
			'fixedAmount' => $fixed_amount,
			'fixedTipAmount' => $fixed_tip_amount,
			'fixedThankYou' => $fixed_thank_you,
			'noMetanet' => $no_metanet,
			'noEditField' => $no_edit_field			
		) 
	);
}

// Register Option for MedioPay

function mediopay_register_settings() {
   add_option( 'mediopay_option_name', 'This is my option value.');
   register_setting( 'mediopay_options_group', 'mediopay_option_name', 'mediopay_callback' );
}
add_action( 'admin_init', 'mediopay_register_settings' );

function mediopay_register_options_page() {
  add_options_page('MedioPay Options', 'MedioPay', 'manage_options', 'mediopay', 'mediopay_option_page');
}
add_action('admin_menu', 'mediopay_register_options_page');

function mediopay_option_page() {
	?>
	<h1>MedioPay Micropayment Options</h1>
	<h2>Basic Options</h2>
	<p>This options are required to use MedioPay.</p>	
	 <?php
	global $wpdb;
	echo $wpdb->dbname;
	echo "<script>console.log('this is " . $wpdb->prefix . "');</script>";
	$table_name = $wpdb->prefix . 'mediopay';
	echo "<script>console.log('this is " . $table_name . "');</script>";
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE id = 1" );
	$currentaddress = $myrows[0]->address;
     echo "<script>console.log('this is " . $currentaddress . "');</script>";
	$myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	$currentcurrency = $myrows[0]->currency; 
	$myrows = $wpdb->get_results( "SELECT fixedAmount FROM " . $table_name . " WHERE id = 1" ); 
	$current_fixedAmount = $myrows[0]->fixedAmount;
	$myrows = $wpdb->get_results( "SELECT sharingQuote FROM " . $table_name . " WHERE id = 1" ); 
	$current_sharing = $myrows[0]->sharingQuote;
	$myrows = $wpdb->get_results( "SELECT ref FROM " . $table_name . " WHERE id = 1" ); 
	$current_ref = $myrows[0]->ref;
	$myrows = $wpdb->get_results( "SELECT noMetanet FROM " . $table_name . " WHERE id = 1" ); 
	$current_metanet = $myrows[0]->noMetanet;
	$myrows = $wpdb->get_results( "SELECT fixedTipAmount FROM " . $table_name . " WHERE id = 1" ); 
	$current_tip_amount = $myrows[0]->fixedTipAmount;
	$myrows = $wpdb->get_results( "SELECT fixedThankYou FROM " . $table_name . " WHERE id = 1" ); 
	$current_thankyou = $myrows[0]->fixedThankYou;
	$myrows = $wpdb->get_results( "SELECT noEditField FROM " . $table_name . " WHERE id = 1" );
	$current_edit = $myrows[0]->noEditField;    
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "MedioPay/mediopay.php";
	echo "<script>console.log('" . $path . "');</script>";
	
   ?>
   Set your BitCoin address, your MoneyButton ID or your PayMail address<br />   
    	<form name='setmediopay' method='post' action=" <?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
    	<!--<form name="setmediopay" id="setmediopay">-->
    	<input type='text' name='address' <?php if (isset($currentaddress) AND $currentaddress !== "none") {echo "value='" . $currentaddress . "'";} ?>
    	/><br /><br />
    	Set the currency to denominate payments.<br />
    	<select name='currency'>
      	<option value="USD" <?php if ($currentcurrency == "USD") {echo  "selected='selected'";} ?> >US Dollar</option>
      	<option value="EUR" <?php if ($currentcurrency == "EUR") {echo  "selected='selected'";} ?> >Euro</option>
      	<option value="BSV" <?php if ($currentcurrency == "BSV") {echo  "selected='selected'";} ?> >Bitcoin SV</option>
      	<option value="AUD" <?php if ($currentcurrency == "AUD") {echo  "selected='selected'";} ?> >Australian Dollar</option>
			<option value="CNY" <?php if ($currentcurrency == "CNY") {echo  "selected='selected'";} ?> >Chinese Yen</option>
			<option value="THB" <?php if ($currentcurrency == "THB") {echo  "selected='selected'";} ?> >Thai Baht</option>
			<option value="CAD" <?php if ($currentcurrency == "CAD") {echo  "selected='selected'";} ?> >Canadian Dollar</option>
			<option value="RUB" <?php if ($currentcurrency == "RUB") {echo  "selected='selected'";} ?> >Russian Rubles</option>
    	</select>
    	<input type='hidden' name='check' value='one'>
    	<br />
    	<h2>Advanced Options</h2>
    	<p>This are optional settings.</p>
		<label for="no_metadata">Deactivate Metadata in Transaction <br />
		<input type="checkbox" name="deactivate_metadata" id="deactivate_metadata" value="yes" <?php if ( $current_metanet == "yes" ) {echo "checked";} ?> "/>		
	</label><br /><br />
	<label for="no_edit_field">Deactivate PayWall Edit Field <br />
		<input type="checkbox" name="deactivate_edit" id="deactivate_edit" value="yes" <?php if ( $current_edit == "yes" ) {echo "checked";} ?> "/>		
	</label><br /><br />
	<label for="sharing_quote">Set your sharing quote (zero to deactivate sharing revenue)<?php $current_sharing ?><br />
	<select name='sharing_quote'>
      	<option value="0.0" <?php if ($current_sharing == "0.0") {echo  "selected='selected'";} ?>    	
      	>0%</option>
      	<option value="0.1" <?php if ($current_sharing == "0.1") {echo  "selected='selected'";} ?>    	
      	>10%</option>
      	<option value="0.2" <?php if ($current_sharing == "0.2") {echo  "selected='selected'";} ?>    	
      	>20%</option>
      	<option value="0.3" <?php if ($current_sharing == "0.3") {echo  "selected='selected'";} ?>    	
      	>30%</option>
			<option value="0.4" <?php if ($current_sharing == "0.4") {echo  "selected='selected'";} ?>    	
      	>40%</option>
   </select>
   </label><br /><br />
   <label for="ref_quote">Set your Reflink share (zero to deactivate Reflinks)<?php $current_ref ?><br />
	<select name='ref_quote'>
      	<option value="0.0" <?php if ($current_ref == "0.0") {echo  "selected='selected'";} ?>    	
      	>0%</option>
      	<option value="0.1" <?php if ($current_ref == "0.1") {echo  "selected='selected'";} ?>    	
      	>10%</option>
      	<option value="0.2" <?php if ($current_ref == "0.2") {echo  "selected='selected'";} ?>    	
      	>20%</option>
      	<option value="0.3" <?php if ($current_ref == "0.3") {echo  "selected='selected'";} ?>    	
      	>30%</option>
			<option value="0.4" <?php if ($current_ref == "0.4") {echo  "selected='selected'";} ?>    	
      	>40%</option>
   </select>
	</label><br />
	<label for="fixed_amount">Set a default amount for your paywall<br />	
	<input type="number" step="0.01" name="fixed_amount" id="fixed_amount" value="<?php if ( $current_fixedAmount !== "none") { echo $current_fixedAmount;} ?>" />            
        <?php echo "<b>" . $currentcurrency . "</b><br />" ?>
   </label><br />
	<label for="fixed_amount_tips">Set a default amount for tips<br />	
	<input type="number" step="0.01" name="fixed_amount_tips" id="fixed_amount_tips" value="<?php if ( $current_tip_amount !== "none") { echo $current_tip_amount;} ?>" />            
        <?php echo "<b>" . $currentcurrency . "</b><br />" ?>
   </label><br />
   <label for="fixed_thank_you">Set a fixed Thank You Message for Tips<br />
   <input type="text" name="fixed_thank_you" id="fixed_thank_you"  value="<?php if ( isset ( $current_thankyou ) ) echo $current_thankyou; ?>" /><br /><br />
	<div id="url"></div>	
	<script type="text/javascript" >
		const thisURL = window.location.href;
		document.getElementById("url").innerHTML = "<input type='hidden' name='thisURL' value='" + thisURL + "' />";
	</script>
   <input type="submit" class="button button-primary" value='save' />
   </form>
    	<?php
}

// save the settings

if(isset($_POST['address']) OR isset($_POST['currency']) OR isset($_POST['deactivate_metadata']) OR isset($_POST['sharing_quote']) OR isset($_POST['ref_quote']) OR isset($_POST['fixed_amount']) OR isset($_POST['fixed_amount_tips']) OR isset($_POST['fixed_thank_you'])) {
    echo "<script>console.log('trying to save');</script>";
	if(isset($_POST['address'])) {		
		$newaddress = $_POST["address"];
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newaddress = array( 'address' => $newaddress );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newaddress,$data_where);
	}
	if(isset($_POST['currency'])) {
		$newcurrency = $_POST["currency"];
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newcurrency = array( 'currency' => $newcurrency );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newcurrency,$data_where);	
	}
	if(isset($_POST['sharing_quote'])) {
		$newsharing = $_POST["sharing_quote"];
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newsharing = array( 'sharingQuote' => $newsharing );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newsharing,$data_where);	
	}
	if(isset($_POST['ref_quote'])) {
		$newref = $_POST["ref_quote"];
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newref= array( 'ref' => $newref );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newref,$data_where);	
	}
	if(isset($_POST['fixed_amount'])) {
		$newfixed = $_POST["fixed_amount"];
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newfixed = array( 'fixedAmount' => $newfixed );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newfixed,$data_where);	
	}
	if(isset($_POST['fixed_amount_tips'])) {
		$newfixedtips = $_POST["fixed_amount_tips"];
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newfixedtips = array( 'fixedTipAmount' => $newfixedtips );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newfixedtips,$data_where);	
	}
	if(isset($_POST['fixed_thank_you'])) {
		$newthankyou = $_POST["fixed_thank_you"];
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newthankyou = array( 'fixedThankYou' => $newthankyou );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newthankyou,$data_where);	
	}
	if(isset($_POST['deactivate_metadata'])) {
		$newmetadata = $_POST["deactivate_metadata"];
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newmetadata = array( 'noMetanet' => $newmetadata );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newmetadata,$data_where);	
	}
	else {
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newmetadata = array( 'noMetanet' => 'no' );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newmetadata,$data_where);		
	}
	if(isset($_POST['deactivate_edit'])) {
		$newedit = $_POST["deactivate_edit"];
		//echo $newedit;
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newedit = array( 'noEditField' => $newedit );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newedit,$data_where);	
	}
	else {
		//echo "no new edit";
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newedit = array( 'noEditField' => 'no' );	
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newedit,$data_where);		
	}
	if(isset($_POST['thisURL'])) {	
		$thisURL = $_POST['thisURL'];
		echo "<script>thisURL='" . $thisURL . "';</script>";
		//echo $thisURL;
	}
	echo "<script>location.replace(thisURL);</script>";
}

// add Metaboxes to editor site

// first: an editor for the paywalled content

function mediopay_custom_meta_paidcontent() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT noEditField FROM " . $table_name . " WHERE id = 1" ); 
	$current_edit = $myrows[0]->noEditField;
	if (isset($current_edit) && $current_edit == "yes") {
	} 
   else {
   	add_meta_box( 'paywall', __( 'Content behind a paywall', 'mediopay-textdomain' ), 'mediopay_meta_callback_paidcontent', 'post');
	}
}

// a side checkbox for tips

function mediopay_custom_meta_tips() {
 add_meta_box( 'tips', __( 'MedioPay Tips', 'mediopay-textdomain' ), 'mediopay_meta_callback_tips', 'post', 'side' );
}

function mediopay_meta_callback_paidcontent( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'mediopay_nonce' );
    $mediopay_stored_meta = get_post_meta( $post->ID );
	 $editor_id = 'meta_paidcontent';
	 $settings = array(
    'editor_height' => 450, 
	);
	 $content = $mediopay_stored_meta["meta-paidcontent"][0];
	 //$content = var_dump($mediopay_stored_meta);	
	 //$content = var_dump($post); 
	 global $wpdb;
	 $table_name = $wpdb->prefix . 'mediopay';
	 $myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	 $currency = $myrows[0]->currency;	 
	 ?>
	 
	 <label for="meta-amount"><b>PayWall Cost</b>
				<input type="number" name="meta-amount" step="0.01" id="meta-amount" value="<?php if ( isset ( $mediopay_stored_meta['meta-amount'] ) ) echo $mediopay_stored_meta['meta-amount'][0]; ?>" />            
        <?php echo "<b>" . $currency . "</b><br />" ?></label><br /> <?php
	 wp_editor( $content, $editor_id, $settings ); 
	    
}

function mediopay_meta_callback_tips( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'mediopay_nonce' );
    $mediopay_stored_meta = get_post_meta( $post->ID );
    global $wpdb;
    $table_name = $wpdb->prefix;
	 $myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	 $currency = $myrows[0]->currency;	
    ?>
    <p>
    <span class="mediopay-row-title"><?php _e( 'Add a Button for Tips', 'mediopay-textdomain' )?></span>
    <div class="mediopay-row-content">
        <label for="meta-checkbox">
            <input type="checkbox" name="meta-checkbox" id="meta-checkbox" value="yes" <?php if ( isset ( $mediopay_stored_meta['meta-checkbox'] ) ) checked( $mediopay_stored_meta['meta-checkbox'][0], 'yes' ); ?> />
            <?php _e( 'Checkbox label', 'mediopay-textdomain' )?>
            <?php _e( 'Add Button', 'mediopay-textdomain' )?>
            <br />Set an Amount<br />
            <input type="number" step="0.01" name="meta-amount" id="meta-amount" value="<?php if ( isset ( $mediopay_stored_meta['meta-tipAmount'] ) ) echo $mediopay_stored_meta['meta-tipAmount'][0]; ?>" />            
        <?php echo "<b>" . $currency . "</b><br />" ?></label><br />
            Add a Thank You Message or Link<br />
            <input type="text" name="meta-textarea" id="meta-textarea"  value="<?php if ( isset ( $mediopay_stored_meta['meta-textarea'] ) ) echo $mediopay_stored_meta['meta-textarea'][0]; ?>" />
        </label>
    </div>
	</p>
	<?php
}

add_action( 'add_meta_boxes', 'mediopay_custom_meta_paidcontent' );
add_action( 'add_meta_boxes', 'mediopay_custom_meta_tips' );


// Save metabox content as Metadata

function mediopay_meta_save( $post_id ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'mediopay_nonce' ] ) && wp_verify_nonce( $_POST[ 'mediopay_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    if( isset( $_POST[ 'meta-checkbox' ] ) ) {
        update_post_meta( $post_id, 'meta-checkbox', $_POST[ 'meta-checkbox' ] );
    }
    else {
		   update_post_meta( $post_id, 'meta-checkbox', "no" );  
    }
	 if( isset( $_POST[ 'meta-textarea' ] ) ) {
        update_post_meta( $post_id, 'meta-textarea', sanitize_text_field( $_POST[ 'meta-textarea' ] ) );
    }
    if( isset( $_POST[ 'meta-amount' ] ) ) {
        update_post_meta( $post_id, 'meta-amount', sanitize_text_field( $_POST[ 'meta-amount' ] ) );
    }
    if( isset( $_POST[ 'meta-tipAmount' ] ) ) {
        update_post_meta( $post_id, 'meta-tipAmount', sanitize_text_field( $_POST[ 'meta-tipAmount' ] ) );
    }
    if(isset($_POST['meta_paidcontent']) ){
		//update_option('my_content', wp_kses_post($_POST['meta_paidcontent']));
		update_post_meta ( $post_id, 'meta-paidcontent', wp_kses_post( $_POST[ 'meta_paidcontent' ]));
	}
}
add_action( 'save_post', 'mediopay_meta_save' );


// activate PayWall from the second editor field

function wpdev_before_after($post_content) {	
	// get all the data and transform it in JavaScript
	if (isset($_GET["ref"])) {
		$refID = $_GET["ref"];
		echo "<script>refID='" . $refID . "';</script>";
	}
	
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$mypost_id = url_to_postid($actual_link);
	global $wpdb;
	$table_name = $wpdb->prefix;
	$meta_paidcontent = get_post_meta( $mypost_id, 'meta-paidcontent', true );
	$meta_checkbox = get_post_meta( $mypost_id, 'meta-checkbox', true );
	$meta_thankyou = get_post_meta( $mypost_id, 'meta-textarea', true );		
	$meta_amount  = get_post_meta( $mypost_id, 'meta-amount', true );
	$meta_tip_amount  = get_post_meta( $mypost_id, 'meta-tipAmount', true );
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE id = 1" );
	$address = $myrows[0]->address;
	$myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	$currency = $myrows[0]->currency;  
	$myrows = $wpdb->get_results( "SELECT fixedAmount FROM " . $table_name . " WHERE id = 1" ); 
	$current_fixedAmount = $myrows[0]->fixedAmount;
	$myrows = $wpdb->get_results( "SELECT sharingQuote FROM " . $table_name . " WHERE id = 1" ); 
	$current_sharing = $myrows[0]->sharingQuote;
	$myrows = $wpdb->get_results( "SELECT ref FROM " . $table_name . " WHERE id = 1" ); 
	$current_ref = $myrows[0]->ref;
	$myrows = $wpdb->get_results( "SELECT noMetanet FROM " . $table_name . " WHERE id = 1" ); 
	$current_metanet = $myrows[0]->noMetanet;
	$myrows = $wpdb->get_results( "SELECT fixedTipAmount FROM " . $table_name . " WHERE id = 1" ); 
	$current_tip_amount = $myrows[0]->fixedTipAmount;
	$myrows = $wpdb->get_results( "SELECT fixedThankYou FROM " . $table_name . " WHERE id = 1" ); 
	$current_thankyou = $myrows[0]->fixedThankYou;    
	
	if (!isset($meta_amount) OR $meta_amount == 0) {
		$meta_amount = $current_fixedAmount;	
	}	
	if (!isset($meta_tip_amount) OR $meta_tip_amount == 0) {
		$meta_tip_amount = $current_tip_amount;	
	}	
	if (!isset($meta_thankyou)) {
		$meta_thankyou = $current_thankyou;	
	}	
	else {
		if (strlen($meta_thankyou < 1)) {
			$meta_thankyou = $current_thankyou;	
		}
	}
	$shortcode = "no";
	/*if ( shortcode_exists( 'paywall' ) ) {
     $shortcode = "yes";
     echo "<script>shortCode='" . $shortcode . "';</script>";
	}*/
	if ( has_shortcode( $post_content, 'paywall' )) {
		 echo "<script>console.log('content has shortcode');</script>";	
		 $shortcode = "yes";
	}
	else {
		echo "<script>console.log('no shortcode in content');</script>";		
	}
	echo "<script>shortCode='" . $shortcode . "';</script>";
	
	echo "<script>thankYou=\"" . $meta_thankyou . "\";</script>";
	echo "<script>theAddress='" . $address . "';</script>";
	echo "<script>theCurrency='" . $currency . "';</script>";
	echo "<script>sharingQuota='" . $current_sharing . "';</script>";
	echo "<script>refQuota='" . $current_ref . "';</script>";
	echo "<script>nometanet='" . $current_metanet . "';</script>";
	
	// create dummy content
	
	$lengthContent = strlen($meta_paidcontent);
	$realContent1 = $meta_paidcontent;
	$realContent1 =  json_encode($realContent1);
	$blackenedContent1 = "";	
	for ($i=0; $i<$lengthContent; $i++) {
			$blackenedContent1 .=	"<span style='background-color:#fb9868'>&nbsp;</span>" ;	
			// #7B7878	
	}	
	echo "<script>realContent1=" . $realContent1 . ";</script>";
	echo "<script>lengthText1=\"" . $lengthContent . "\";</script>";
	
	$dataContent = get_the_content();
	$dataContent = substr($dataContent, 0, 168);
	$dataContent = wp_strip_all_tags( $dataContent );
	echo "<script>dataContent=\"" . $dataContent . "\";</script>";
	echo "<script>dataLink=\"" . get_permalink() . "\";</script>";
	echo "<script>dataTitle=\"" . get_the_title() . "\";dataTitle = encodeURI(dataTitle); </script>";
	echo "<script>paymentAmount=\"" . $meta_amount . "\";</script>";
	echo "<script>checkBox=\"" . $meta_checkbox . "\";</script>";
	echo "<script>tipAmount=\"" . $meta_tip_amount	 . "\";</script>";
	if ($meta_paidcontent) {
	   $fullcontent1 = $post_content . "<div id='frame1'><div id='counter1'></div><div class='money-button' id='mbutton1'></div></div><div id='unlockable1'>" . $blackenedContent1 . "</div>";
	   if ($meta_checkbox == "yes");
	   	$fullcontent1 = $fullcontent1 . "<div id='counterTips'></div><div class='money-button' id='tbutton'></div>";
	}
	else if ($meta_checkbox == "yes") {
		$fullcontent1 = $post_content . "<div id='counterTips'></div><div class='money-button' id='tbutton'></div></div>";
	}
	else {
		$fullcontent1 = $post_content;	
	}   
	
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "MedioPay/scripts.js";
	//echo "<script>scriptPath=\"" . $path . "\";</script>";
	echo "<script src='" . $path . "'></script>";
	
	// style the locked and unlocked content
   ?>
   <style>
        /* The CSS you'll need in your website */
        #unlockable1 {
            /*visibility: hidden;
            opacity: 0;
            transition: opacity 5s;*/
            opacity:0.8;
            color: #7B7878;
    			text-shadow: 0 0 10px black,
                 0 0 50px black,
                 0 0 20px black,
                 0 0 20px black,
                 0 0 20px black,
                 0 0 20px black;
        }

        #unlockable1.unlocked {
        		opacity:1;
        		transition: opacity 3s;
            color:#2F2F2F;
            transition: color 3s;
            text-shadow: 0 0 0 white;
            transition: text-shadow 3s;
        }
        #frame1 {
				/*border-left:10px solid #4772F6;*/    
				height:110px;
				padding-left:10px;
        
        }
		 #frame1.paid {
				border-left:10px solid #4772F6;
				height:80px;	 
		 }        
        
    </style>   
<script src="https://unpkg.com/bsv@0.30.0/bsv.min.js"></script>
<script src="https://www.moneybutton.com/moneybutton.js"></script>
<!--<script src="/blog/wp-content/plugins/MedioPay/scripts.js"></script>-->
    <script>
	// create Objects to pass to the money button creation script    
	secondEdit = "yes";
	dataDomain = window.location.hostname;
	dataURL = window.location.pathname;
   paymentObjects = [];
    if (checkBox == "yes") {
    	  var returndata = bsv.Script.buildDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100201', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();
    	  paymentLabel = "tip";
    	  tip = {
			 tip: "yes",
			 paywall: "no",
			 typenumber: "100201",
			 title: dataTitle,
			 amount: tipAmount,
			 baseurl: dataDomain,
			 path: dataURL,
			 ref: refQuota,
			 sharing: sharingQuota,
			 nometanet: nometanet,
			 to: theAddress,
			 returndata: returndata,
			 outputs: 1,
			 currency: theCurrency    	  
    	  }
    	  if (typeof refID !== "undefined") {
			 tip.refID = refID;    	 
			 tip.outputs = 2;   
    	  }
    	  paymentObjects.push(tip);
	 }
	 else {    	
    	 paymentLabel = "buy";
   }
   if (typeof realContent1 !== "undefined" && realContent1.length > 0) {
   	  var returndata = bsv.Script.buildDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100101', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();
		 	paywall = {
			paywall: "yes",
			tips: "no",
			typenumber: "100101",
			title: dataTitle,
			amount: paymentAmount,
			baseurl: dataDomain,
			path: dataURL,
			sharing: sharingQuota,
			ref: refQuota,
			nometanet: nometanet,
			returndata: returndata,
			to: theAddress,
			outputs: 1,
			currency: theCurrency    	    	    		 
		 }
		  if (typeof refID !== "undefined") {
			 paywall.refID = refID; 
			 paywall.outputs = 2;  	  
    	  }
		 paymentObjects.push(paywall);
   }
	k = 0;
	p = 0;

	console.log(paymentObjects);
	// load functions with the objects
	if (shortCode == "yes") {
		console.log("shortcodes detected");
	}
	else {
		console.log("pass object to script");
		querryPlanaria(paymentObjects);
		getAddress(paymentObjects);
	}
		
	// End new!		
		
	//var returndata = bsv.Script.buildDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100101', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL]).toASM();		*/	
    function handleSuccessfulPayment1(payment) {
         unlockContent1(payment);        
    }
    function handleFailedPayment1(error) {
            alert("Sorry, the payment did not process correctly.")
    }
    function unlockContent1(payment) {
        		document.getElementById("unlockable1").innerHTML = realContent1;
           document.getElementById("unlockable1").classList.toggle("unlocked");
				document.getElementById("frame1").classList.toggle("paid");
            document.getElementById("frame1").innerHTML="<em>You can share this link to get your share of later payments: <a href='" + dataLink + "?ref=" + payment.userId + "'>" +  dataLink + "</a></em>";
            document.getElementById("counter1").innerHTML="";
				document.getElementById("mbutton1").innerHTML="";
    }
	 function handleSuccessfulTip(payment) {
			document.getElementById("tbutton").innerHTML = thankYou;				 
	 }    
    
    </script>	
<?php  
   return $fullcontent1;    
}
     
add_filter('the_content', 'wpdev_before_after');


// use PayWall with shortcodes. All the operations are the same as with the second editor field.

//add_shortcode( 'cp-hide-button', 'cp_hb_shortcode_cb' );
add_shortcode( 'paywall', 'paywall_function' );


//function cp_hb_shortcode_cb( $attr, $content ) {
function paywall_function( $attr, $content) {
	echo "<script>console.log('create paywall with shortcode');</script>";
	ob_start();
	global $wpdb;
	$table_name = $wpdb->prefix;
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$mypost_id = url_to_postid($actual_link);
	$meta_paidcontent = get_post_meta( $mypost_id, 'meta-paidcontent', true );
	$meta_checkbox = get_post_meta( $mypost_id, 'meta-checkbox', true );
	$meta_thankyou = get_post_meta( $mypost_id, 'meta-textarea', true );		
	$meta_amount  = get_post_meta( $mypost_id, 'meta-amount', true );
	$meta_tip_amount  = get_post_meta( $mypost_id, 'meta-tipAmount', true );
	$table_name = $wpdb->prefix . 'mediopay';	
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE = 1" );
	$address = $myrows[0]->address;
	$myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	$currency = $myrows[0]->currency;  
	$myrows = $wpdb->get_results( "SELECT fixedAmount FROM " . $table_name . " WHERE id = 1" ); 
	$current_fixedAmount = $myrows[0]->fixedAmount;
	$myrows = $wpdb->get_results( "SELECT sharingQuote FROM " . $table_name . " WHERE id = 1" ); 
	$current_sharing = $myrows[0]->sharingQuote;
	$myrows = $wpdb->get_results( "SELECT ref FROM " . $table_name . " WHERE id = 1" ); 
	$current_ref = $myrows[0]->ref;
	$myrows = $wpdb->get_results( "SELECT noMetanet FROM " . $table_name . " WHERE id = 1" ); 
	$current_metanet = $myrows[0]->noMetanet;
	$myrows = $wpdb->get_results( "SELECT fixedTipAmount FROM " . $table_name . " WHERE id = 1" ); 
	$current_tip_amount = $myrows[0]->fixedTipAmount;
	$myrows = $wpdb->get_results( "SELECT fixedThankYou FROM " . $table_name . " WHERE id = 1" ); 
	$current_thankyou = $myrows[0]->fixedThankYou;    	
	
	if (!isset($meta_amount) OR $meta_amount == 0) {
		$meta_amount = $current_fixedAmount;	
	}	
	if (!isset($meta_tip_amount) OR $meta_tip_amount == 0) {
		$meta_tip_amount = $current_tip_amount;	
	}	
	if (!isset($meta_thankyou)) {
		$meta_thankyou = $current_thankyou;	
	}	
	else {
		if (strlen($meta_thankyou < 1)) {
			$meta_thankyou = $current_thankyou;	
		}
	}
	
	echo "<script>theAddress='" . $address . "';</script>";
	echo "<script>theCurrency='" . $currency . "';</script>";
	echo "<script>sharingQuota='" . $current_sharing . "';</script>";
	echo "<script>refQuota='" . $current_ref . "';</script>";
	echo "<script>nometanet='" . $current_metanet . "';</script>";	
	
	$lengthContent = strlen($content);
	$realContent2 = $content;
	$realContent2 =  json_encode($realContent2);
	$blackenedContent2 = "";	
	for ($i=0; $i<$lengthContent; $i++) {
		if (ctype_space(substr($realContent2, $i))) {
			$blackenedContent2 .= "&nbsp;";		
		}
		else {
			$blackenedContent2 .=	"<span style='background-color:#7B7878'>&nbsp;</span>";	
		}			
	}	
	echo "<script>realContent2=" . $realContent2 . ";</script>";
	echo "<script>lengthText=\"" . $lengthContent . "\";</script>";
	echo "<script>paymentAmount=\"" . $meta_amount . "\";</script>";
	echo "<script>checkBox=\"" . $meta_checkbox . "\";</script>";
	echo "<script>tipAmount=\"" . $meta_tip_amount	 . "\";</script>";
	
	$dataContent = get_the_content();
	$dataContent = substr($dataContent, 0, 168);
	$dataContent = wp_strip_all_tags( $dataContent );
	echo "<script>dataContent=\"" . $dataContent . "\";</script>";
	echo "<script>dataLink=\"" . get_permalink() . "\";</script>";
	echo "<script>dataTitle=\"" . get_the_title() . "\";dataTitle = encodeURI(dataTitle); </script>";
	if (isset($attr["amount"])){
		echo "<script>paymentAmount=\"" . $attr["amount"] . "\";</script>";
	}
	else {
		echo "<script>paymentAmount=\"" . $current_fixedAmount . "\";</script>";	
	}
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "MedioPay/scripts.js";
	//echo "<script>scriptPath=\"" . $path . "\";</script>";
	echo "<script src='" . $path . "'></script>";
	?>
	<div id="frame2"><div id="counter2"></div>
	<div class="money-button" id="mbutton2"></div></div>
   <style>
        /* The CSS you'll need in your website */
        #unlockable2 {
            /*visibility: hidden;
            opacity: 0;
            transition: opacity 5s;*/
            opacity:0.8;
            color: #7B7878;
    			text-shadow: 0 0 10px black,
                 0 0 50px black,
                 0 0 20px black,
                 0 0 20px black,
                 0 0 20px black,
                 0 0 20px black;
        }

        #unlockable2.unlocked {
        		opacity:1;
        		transition: opacity 3s;
            color:#2F2F2F;
            transition: color 3s;
            text-shadow: 0 0 0 white;
            transition: text-shadow 3s;
        }
        #frame2 {
				/*border-left:10px solid #4772F6;*/    
				height:110px;
				padding-left:10px;
        
        }
		 #frame2.paid {
				border-left:10px solid #4772F6;
				height:80px;	 
		 }        
        
    </style>   
    <script>
	dataDomain = window.location.hostname;
	dataURL = window.location.pathname;
	var returndata = bsv.Script.buildDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100102', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();
	paywall2 = {
				paywall2: "yes",
				tips: "no",
				typenumber: "100102",
				title: dataTitle,
				amount: paymentAmount,
				baseurl: dataDomain,
				path: dataURL,
				sharing: sharingQuota,
				ref: refQuota,
				nometanet: nometanet,
				returndata: returndata,
				to: theAddress,
				outputs: 1,
				currency: theCurrency    	    	    		 
	}
	if (typeof refID !== "undefined") {
			 	paywall2.refID = refID; 
			 	paywall2.outputs = 2;  	  
   }
   paymentObjects.push(paywall2);
   

	k = 0;
	p = 0;
	querryPlanaria(paymentObjects);
	getAddress(paymentObjects);
	function handleSuccessfulPayment2(payment) {
         unlockContent2(payment);
            
    }
    function handleFailedPayment(error) {
            alert("Sorry, the payment did not process correctly.")
    }
    function unlockContent2(payment) {
        		document.getElementById("unlockable2").innerHTML = realContent2;
            document.getElementById("unlockable2").classList.toggle("unlocked");
				document.getElementById("frame2").classList.toggle("paid");
            document.getElementById("frame2").innerHTML="<em>You can share this link to get your share of later payments: <a href='" + dataLink + "?ref=" + payment.userId + "'>" +  dataLink + "</a></em>";
            document.getElementById("counter2").innerHTML="";
				document.getElementById("mbutton2").innerHTML="";
    }
    </script>
    <div id="unlockable2">
		<?= $blackenedContent2 ?>
    </div>	
    <!--<div class='money-button' id='tbutton'></div>-->
	<?php	
	return ob_get_clean();
}
?>
