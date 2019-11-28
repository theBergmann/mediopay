<?php
/*
 * Plugin Name: MedioPay
 * Description: This plugin allows PayWalls and Tip Button for Wordpress
 * Version: 1.5
 * Requires at least: 4.2
 * Requires PHP: 6.2
 * Author: MedioPay
 * Author URI: https://mediopay.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Activation, Deactivation, Uninstall
register_uninstall_hook( 'mediopay/mediopay.ph', 'uninstall_mediopay' );

function uninstall_mediopay() {
	 global $wpdb;
    $table_name = $wpdb->prefix . "mediopay";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}

register_deactivation_hook( 'mediopay/mediopay.php', 'mediopaydeactivate' );

function mediopaydeactivate() {
	  global $wpdb;
    $table_name = $wpdb->prefix . "mediopay";
     $sql = "DROP TABLE IF EXISTS $table_name;";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}


register_activation_hook( 'mediopay/mediopay.php', 'mediopayactivate' );
register_activation_hook( 'mediopay/mediopay.php', 'mediopayactivate_data' );

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
		barColor tinytext NOT NULL,
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
	$barColor = 'FB9868';
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
			'noEditField' => $no_edit_field,
			'barColor' => $barColor
		)
	);
}

// add style and scripts

function mediopay_add_scripts() {
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "mediopay/lib/";
	wp_enqueue_script('jquery');
	wp_enqueue_style( 'style', $path . 'style.css' );
	wp_enqueue_script( 'moneybutton', $path . 'moneybutton.js', true);
	wp_enqueue_script( 'bsv', $path . 'bsv.min.js', true);
	wp_enqueue_script( 'scripts_pre_paywall', $path . 'scripts_pre_paywall.js', true);
	wp_enqueue_script( 'scripts_create_paywall', $path . 'scripts_create_paywall.js', true);
	wp_enqueue_script( 'scripts_after_paywall', $path . 'scripts_after_paywall.js',  true);
	//wp_enqueue_script( 'ajax-script', get_template_directory_uri() . '/js/my-ajax-script.js', array('jquery') );
	wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ )  . '/lib/scripts_pre_paywall.js', array('jquery') );	
	wp_localize_script( 'ajax-script', 'my_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'mediopay_add_scripts' );

function mediopay_add_admin_scripts() {
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "mediopay/lib/";
	wp_localize_script( 'ajax-script', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'we_value' => 1234 ) );
}

add_action( 'admin_enqueue_scripts', 'mediopay_add_admin_scripts' );

// Register Option for MedioPay

function mediopay_register_settings() {
   add_option( 'mediopay_option_name', 'This is my option value.');
   register_setting( 'mediopay_options_group', 'mediopay_option_name', 'mediopay_callback' );
}
add_action( 'admin_init', 'mediopay_register_settings' );

function mediopay_register_options_page() {
  add_options_page('mediopay Options', 'mediopay', 'manage_options', 'mediopay', 'mediopay_option_page');
}
add_action('admin_menu', 'mediopay_register_options_page');

function mediopay_option_page() {
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "mediopay/lib/";
	wp_enqueue_style( 'style', $path . 'style.css' );
	?>
	<h1 class="mediopay_options_h1">Settings › MedioPay</h1>
	<h2 class="mediopay_options_h2">Basics</h2>
	<div class="mp_options">
	<p>These options are <b><u>required</u></b> to use MedioPay.</p><?php
	global $wpdb;
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
	$mp_currentaddress = $myrows[0]->address;
	$mp_currentcurrency = $myrows[0]->currency;
	$mp_current_fixedAmount = $myrows[0]->fixedAmount;
	$mp_current_sharing = $myrows[0]->sharingQuote;
	$mp_current_ref = $myrows[0]->ref;
	$mp_current_metanet = $myrows[0]->noMetanet;
	$mp_current_tip_amount = $myrows[0]->fixedTipAmount;
	$mp_current_thankyou = $myrows[0]->fixedThankYou;
	$mp_current_edit = $myrows[0]->noEditField;
	$mp_current_color = $myrows[0]->barColor;
	if ( isset($myrows[0]->paywallMsg)) {
		$mp_current_paywall_msg = $myrows[0]->paywallMsg;
	}
	else {
	}
	if ( isset($myrows[0]->tippingMsg)) {
		$mp_current_tipping_msg = $myrows[0]->tippingMsg;
	}
	else {
	}
	
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "mediopay/mediopay.php";

	
   ?><form name='setmediopay' method='post' action="<?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
		<table class="mediopay_table">
		<tr>
		<td class="mediopay_column_small">
			<b>Your Bitcoin SV address</b></td>
		<td class="mediopay_column_large">
    	<input type='text' name='MedioPay_address'<?php if (isset($mp_currentaddress) AND $mp_currentaddress !== "none") {echo "value='" . esc_html($mp_currentaddress) . "'";} ?>
    	/>
    	<br />Enter your Bitcoin SV address, paymail address or MoneyButton ID.

    	</td>
    	</tr>
		<tr>
		<td class="mediopay_column_small">
    	<b>The currency to denominate payments.</b><br />
    	</td>
    	<td class="mediopay_column_large">
    	<select name='MedioPay_currency'>
      	<option value="USD" <?php if ($mp_currentcurrency == "USD") {echo  "selected='selected'";} ?> >US Dollar</option>
      	<option value="EUR" <?php if ($mp_currentcurrency == "EUR") {echo  "selected='selected'";} ?> >Euro</option>
      	<option value="BSV" <?php if ($mp_currentcurrency == "BSV") {echo  "selected='selected'";} ?> >Bitcoin SV</option>
      	<option value="AUD" <?php if ($mp_currentcurrency == "AUD") {echo  "selected='selected'";} ?> >Australian Dollar</option>
			<option value="CNY" <?php if ($mp_currentcurrency == "CNY") {echo  "selected='selected'";} ?> >Chinese Yen</option>
			<option value="THB" <?php if ($mp_currentcurrency == "THB") {echo  "selected='selected'";} ?> >Thai Baht</option>
			<option value="CAD" <?php if ($mp_currentcurrency == "CAD") {echo  "selected='selected'";} ?> >Canadian Dollar</option>
			<option value="RUB" <?php if ($mp_currentcurrency == "RUB") {echo  "selected='selected'";} ?> >Russian Rubles</option>
    	</select>
    	<input type='hidden' name='check' value='one'>
    	<br />Set a currency in which the payments are denominated.
		</td>
		</tr>
		<tr>
		<td class="mediopay_column_small">
    	<h2 class="mediopay_options_h2">Personalize MedioPay</h2>
    	<p>Optional: Make your Paywall fits your blogging style</p>
    	</td>
    	</tr>
			<tr>
			<td  class="mediopay_column_small">
			<b>Set color of the paywall</b>
			</td>
			<td  class="mediopay_column_large">
				<label for="MedioPay_bar_color"><?php esc_html($mp_current_color) ?>
					<input type="color" id="select_color" name="MedioPay_bar_color" value="<?php echo esc_html($mp_current_color); ?>"
		           >
		   </label>
		   <br />Apply your color themes to the PayWall.
		   </td>
		   </tr>
    	<tr>
		<tr>
		<td class="mediopay_column_small">
		<b>Your personal PayWall-Text</b>		
		</td>
		<td class="mediopay_column_large">
		<label for="MedioPay_paywall_msg">   	
    	<input type="text" name="MedioPay_paywall_msg" id="MedioPay_paywall_msg"  value="<?php if ( isset ( $mp_current_paywall_msg ) ) echo esc_html($mp_current_paywall_msg); ?>" /><br />
		Put your individual message in the PayWall fields. You know best what your readers need to know.<br />
		</td>
	</tr>
	<tr>
		<td class="mediopay_column_small">
		<b>Your personal Tipping-Field-Text</b>		
		</td>
		<td class="mediopay_column_large">
		<label for="MedioPay_tipping_msg">   	
    	<input type="text" name="MedioPay_tipping_msg" id="MedioPay_tipping_msg"  value="<?php if ( isset ( $mp_current_tipping_msg ) ) echo esc_html($mp_current_tipping_msg); ?>" /><br />
		Put your individual message in the Tipping field. Tell your readers why they should give your a tip.<br />
		</td>
	</tr> 
	<tr>
   <td class="mediopay_column_small">
   <b>Set a fixed Thank You Message for Tips</b>
   </td>
   <td class="mediopay_column_large">
   <label for="MedioPay_fixed_thank_you">
   <input type="text" name="MedioPay_fixed_thank_you" id="MedioPay_fixed_thank_you"  value="<?php if ( isset ( $mp_current_thankyou ) ) echo esc_html($mp_current_thankyou); ?>" /><br />
	When someone tips you, a thank you message is shown. You can either type it specifically for each post, or you can set a default thank you message.
	</td>
	</tr>
	<tr>
	<td class="mediopay_column_small">
	<h2 class="mediopay_options_h2">Your MedioPay Economics</h2>
   <p>Optional: Design the micropayment economy on your blog</p>  	
   </td>
   </tr>
	<tr>
   <td class="mediopay_column_small">
   <b>Set your sharing quote</b></td>
 	<td class="mediopay_column_large">
	<label for="MedioPay_sharing_quote"><?php esc_html($mp_current_sharing) ?>
	<select name='MedioPay_sharing_quote'>
      	<option value="0.0" <?php if ($mp_current_sharing == "0.0") {echo  "selected='selected'";} ?>
      	>0%</option>
      	<option value="0.1" <?php if ($mp_current_sharing == "0.1") {echo  "selected='selected'";} ?>
      	>10%</option>
      	<option value="0.2" <?php if ($mp_current_sharing == "0.2") {echo  "selected='selected'";} ?>
      	>20%</option>
      	<option value="0.3" <?php if ($mp_current_sharing == "0.3") {echo  "selected='selected'";} ?>
      	>30%</option>
			<option value="0.4" <?php if ($mp_current_sharing == "0.4") {echo  "selected='selected'";} ?>
      	>40%</option>
   </select>
   </label>
   <br />First buyers or tipers get a share of future income. Set how much you share with your readers.
	</td>
   </tr>
   <tr>
   <td class="mediopay_column_small">
   <b>Set your Reflink share</b></td>
   <td class="mediopay_column_large">
   <label for="MedioPay_ref_quote"><?php esc_html($mp_current_ref) ?>
	<select name='MedioPay_ref_quote'>
      	<option value="0.0" <?php if ($mp_current_ref == "0.0") {echo  "selected='selected'";} ?>
      	>0%</option>
      	<option value="0.1" <?php if ($mp_current_ref == "0.1") {echo  "selected='selected'";} ?>
      	>10%</option>
      	<option value="0.2" <?php if ($mp_current_ref == "0.2") {echo  "selected='selected'";} ?>
      	>20%</option>
      	<option value="0.3" <?php if ($mp_current_ref == "0.3") {echo  "selected='selected'";} ?>
      	>30%</option>
			<option value="0.4" <?php if ($mp_current_ref == "0.4") {echo  "selected='selected'";} ?>
      	>40%</option>
   </select>
	</label>
	<br />After having bought an article, readers get an affiliate link. Set how much
	 to share with it.
	</td>
	</tr>
	<tr>
	<td class="mediopay_column_small">
	<b>Set a default amount for your paywall</b>
	<br /><br /></td>
	<td class="mediopay_column_large">
	<label for="MedioPay_fixed_amount">
	<input type="number" step="0.01" name="MedioPay_fixed_amount" id="MedioPay_fixed_amount" value="<?php if ( $mp_current_fixedAmount !== "none") { echo esc_html($mp_current_fixedAmount);} ?>" />
        <?php echo "<b>" . esc_html($mp_currentcurrency) . "</b><br />" ?>
   </label>
   A default amount for the paywall makes using MedioPay more convenient for you. If you don't have set it, enter it manually
   above the second editor field or with shortcode: <code>[paywall amount="0.5"]</code>.
   </td>
   </tr>
   <tr>
   <td class="mediopay_column_small">
   <b>Set a default amount for tips</b>
   <br /></td>
   <td class="mediopay_column_large">
	<label for="MedioPay_fixed_amount_tips">
	<input type="number" step="0.01" name="MedioPay_fixed_amount_tips" id="MedioPay_fixed_amount_tips" value="<?php if ( $mp_current_tip_amount !== "none") { echo esc_html($mp_current_tip_amount);} ?>" />
        <?php echo "<b>" . esc_html($mp_currentcurrency) . "</b><br />" ?>
   </label>
   Same as the option above: Set a default tip amount.
   </td>
   </tr>
   <tr>
	<td class="mediopay_column_small">
   <h2 class="mediopay_options_h2">Deactivate features</h2>
   <p>Optional: Sometimes less is more ... </p>
	</td></tr>
   <tr>
    	<td class="mediopay_column_small">
    	<b>Deactivate Metadata</b>
		<br /><br /></td>
		<td class="mediopay_column_large">
		<label for="MedioPay_no_metadata">
		<input type="checkbox" name="MedioPay_deactivate_metadata" id="MedioPay_deactivate_metadata" value="yes" <?php if ( $mp_current_metanet == "yes" ) {echo "checked";} ?> "/>
	</label>
	<br />Don't add metadata to the transactions.
		</td>
		</tr>
		<tr>
		<td class="mediopay_column_small"><b>Deactivate PayWall Edit Field</b>
		</td>
		<td class="mediopay_column_large">
	<label for="MedioPay_no_edit_field">
		<input type="checkbox" name="MedioPay_deactivate_edit" id="MedioPay_deactivate_edit" value="yes" <?php if ( $mp_current_edit == "yes" ) {echo "checked";} ?> "/>
	</label>
	<br />In some cases the second editor field is annoying. Deactivate it and use the <code>[paywall]...[/paywall]</code>
		shortcode: Just put the paywalled content between the shortcodes.<br />
	</td>
	</tr>
	</table>
	<div id="url"></div>
	<script type="text/javascript" >
		const thisURL = window.location.href;
		document.getElementById("url").innerHTML = "<input type='hidden' name='MedioPay_thisURL' value='" + thisURL + "' />";
	</script>
   <input type="submit" class="button button-primary" value='save' />
   </form></div>
    	<?php
}
// save the settings

if(isset($_POST['MedioPay_address']) OR isset($_POST['MedioPay_currency']) OR isset($_POST['deactivate_metadata']) OR isset($_POST['MedioPay_sharing_quote']) OR isset($_POST['ref_quote']) OR isset($_POST['fixed_amount']) OR isset($_POST['fixed_amount_tips']) OR isset($_POST['fixed_thank_you']) OR isset($_POST['bar_color']) OR isset($_POST['MedioPay_paywall_msg'])) {
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
	if(isset($_POST['MedioPay_bar_color'])) {
		$newedit = sanitize_hex_color($_POST["MedioPay_bar_color"]);
		//echo $newedit;
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newedit = array( 'barColor' => $newedit );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newedit,$data_where);
	}
	else {
		//echo "no new edit";
		/*global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';*/
		$newedit = array( 'noEditField' => 'no' );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newedit,$data_where);
	}
	if(isset($_POST['MedioPay_thisURL'])) {
		$thisURL = esc_url($_POST['MedioPay_thisURL']);
		echo "<script>thisURL='" . $thisURL . "';</script>";
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
	echo "<script>location.replace(thisURL);</script>";
}


// add Metaboxes to editor site
// first: an editor for the paywalled content
function mediopay_custom_meta_paidcontent() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT noEditField FROM " . $table_name . " WHERE id = 1" );
	$mp_current_edit = $myrows[0]->noEditField;
	if (isset($mp_current_edit) && $mp_current_edit == "yes") {
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
				<input type="number" name="meta-amount" step="0.01" id="meta-amount" value="<?php if ( isset ( $mediopay_stored_meta['meta-amount'] ) ) echo esc_html($mediopay_stored_meta['meta-amount'][0]); ?>" />
        <?php echo "<b>" . esc_html($currency) . "</b><br />" ?></label><br /> <?php
	 wp_editor( $content, $editor_id, $settings );
}

function mediopay_meta_callback_tips( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'mediopay_nonce' );
    $mediopay_stored_meta = get_post_meta( $post->ID );
    global $wpdb;
    $table_name = $wpdb->prefix . 'mediopay';
	 $myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	 $currency = $myrows[0]->currency;
    ?>
    <p>
    <span class="mediopay-row-title"><?php _e( 'Add a Button for Tips', 'mediopay-textdomain' )?></span>
    <div class="mediopay-row-content">
        <label for="mp_meta_checkbox">
            <input type="checkbox" name="mp_meta_checkbox" id="mp_meta_checkbox" value="yes" <?php if ( isset ( $mediopay_stored_meta['mp_meta_checkbox'] ) ) checked( $mediopay_stored_meta['mp_meta_checkbox'][0], 'yes' ); ?> />
            <?php _e( 'Checkbox label', 'mediopay-textdomain' )?>
            <?php _e( 'Add Button', 'mediopay-textdomain' )?>
            <br />Set an Amount<br />
       </label>
       <label for="meta-tipAmount">
            <input type="number" step="0.01" name="meta-tipAmount" id="meta-tipAmount" value="<?php if ( isset ( $mediopay_stored_meta['meta-tipAmount'] ) ) echo esc_html($mediopay_stored_meta['meta-tipAmount'][0]); ?>" /><?php echo "<b>" . esc_html($currency) . "</b><br />" ?></label><br />
            Add a Thank You Message or Link<br />
            <input type="text" name="meta-textarea" id="meta-textarea"  value="<?php if ( isset ( $mediopay_stored_meta['meta-textarea'] ) ) echo esc_html($mediopay_stored_meta['meta-textarea'][0]); ?>" />
        </label>
    </div>
	</p>
	<?php
}


add_action( 'add_meta_boxes', 'mediopay_custom_meta_paidcontent' );
add_action( 'add_meta_boxes', 'mediopay_custom_meta_tips' );
//add_action( 'add_meta_boxes', 'mediopay_secret1_box' );
//add_action( 'add_meta_boxes', 'mediopay_secret2_box' );

// Save metabox content as Metadata
function mediopay_meta_save( $post_id ) {
	 $mp_is_published = get_post_status( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'mediopay_nonce' ] ) && wp_verify_nonce( $_POST[ 'mediopay_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    $mediopay_stored_meta = get_post_meta( $post_id );
    if ( $is_revision || !$is_valid_nonce ) {
        return;
    }
    if( isset( $_POST[ 'mp_meta_checkbox' ] ) ) {
        update_post_meta( $post_id, 'mp_meta_checkbox', sanitize_text_field($_POST[ 'mp_meta_checkbox' ] ) );
    }
    else {
		   update_post_meta( $post_id, 'mp_meta_checkbox', 'no' );
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
    if( isset($_POST['meta_paidcontent']) ){
			update_post_meta ( $post_id, 'meta-paidcontent', wp_kses_post( $_POST[ 'meta_paidcontent' ]));
	}
	if (!isset($mediopay_stored_meta['meta-secretword-1'])) {
		$mp_meta_secret_01 = rand(100000, 999999);
		update_post_meta ( $post_id, 'meta-secretword-1', $mp_meta_secret_01 );
	}
	if (!isset($mediopay_stored_meta['meta-secretword-2'])) {
		$mp_meta_secret_02 = rand(100000, 999999);
		update_post_meta ( $post_id, 'meta-secretword-2', $mp_meta_secret_02 );
	}
	if ( $mp_is_published !== "publish")  {
		update_post_meta ( $post_id, 'meta-newcounter', "yes");
		update_post_meta ( $post_id, 'meta-newcounter2', "yes");
		update_post_meta ( $post_id, 'meta-newcounter3', "yes");
	}
}
add_action( 'save_post', 'mediopay_meta_save' );

// activate PayWall from the second editor field

function mediopay_create_paywall($post_content) {
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$mypost_id = url_to_postid($actual_link);
	global $wpdb;
	$table_name = $wpdb->prefix;
	$meta_paidcontent = get_post_meta( $mypost_id, 'meta-paidcontent', true );
	$mp_meta_checkbox = get_post_meta( $mypost_id, 'mp_meta_checkbox', true );
	$meta_thankyou = get_post_meta( $mypost_id, 'meta-textarea', true );
	$meta_amount  = get_post_meta( $mypost_id, 'meta-amount', true );
	$meta_tip_amount  = get_post_meta( $mypost_id, 'meta-tipAmount', true );
	$mp_meta_secret1  = get_post_meta( $mypost_id, 'meta-secretword-1', true );
	$mp_meta_secret2  = get_post_meta( $mypost_id, 'meta-secretword-2', true );
	//echo "<script>mp_metasecret1='" . hash('sha256', $mp_meta_secret1) . "';</script>";
	//echo "<script>mp_metasecret2='" . hash('sha256', $mp_meta_secret2) . "';</script>";
	$mp_newcounter = get_post_meta( $mypost_id, 'meta-newcounter', true );
	if (isset($mp_newcounter)) {
		echo "<script>mp_newCounter ='" . $mp_newcounter . "';console.log(mp_newCounter);</script>";
		$mp_buys1 = get_post_meta( $mypost_id, 'meta_buys1', true );
		$mp_buys2 = get_post_meta( $mypost_id, 'meta_buys2', true );
		$mp_tips = get_post_meta( $mypost_id, 'meta_tips', true );
		$mp_first_buys1 = get_post_meta( $mypost_id, 'meta-first-buys1', true );
		$mp_second_buys1 = get_post_meta( $mypost_id, 'meta-second-buys1', true );	
		$mp_third_buys1 = get_post_meta( $mypost_id, 'meta-third-buys1', true );	
		$mp_fourth_buys1 = get_post_meta( $mypost_id, 'meta-fourth-buys1', true );	
		$mp_first_buys2 = get_post_meta( $mypost_id, 'meta-first-buys2', true );
		$mp_second_buys2 = get_post_meta( $mypost_id, 'meta-second-buys2', true );	
		$mp_third_buys2 = get_post_meta( $mypost_id, 'meta-third-buys2', true );	
		$mp_fourth_buys2 = get_post_meta( $mypost_id, 'meta-fourth-buys2', true );
		$mp_first_tips = get_post_meta( $mypost_id, 'meta-first-tips', true );
		$mp_second_tips = get_post_meta( $mypost_id, 'meta-second-tips', true );	
		$mp_third_tips = get_post_meta( $mypost_id, 'meta-third-tips', true );	
		$mp_fourth_tips = get_post_meta( $mypost_id, 'meta-fourth-tips', true );	
		if (isset($mp_buys1) AND strlen($mp_buys1) > 0) {
			echo "<script>mp_buys1=" . $mp_buys1 . ";console.log('buys1db'); console.log('mp buys ' + mp_buys1);</script>";
		}	
		else {
			echo "<script>mp_buys1=0;console.log('buys1zero');console.log(mp_buys1);</script>";		
		}
		if (isset($mp_buys2) AND strlen($mp_buys2) > 0) {
			echo "<script>mp_buys2=" . $mp_buys2 . ";</script>";
		}
		else {
			echo "<script>mp_buys2=0;</script>";		
		}
		if (isset($mp_tips) AND strlen($mp_tips) > 0) {
			echo "<script>mp_tips=" . $mp_tips . ";</script>";
		}	
		else {
			echo "<script>mp_tips=0;</script>";		
		}	
		if (isset($mp_first_buys1) AND strlen($mp_first_buys1) > 0) {
			echo "<script>mp_first_buys1='" . $mp_first_buys1 . "';</script>";
		}
		if (isset($mp_second_buys1) AND strlen($mp_second_buys1) > 0) {
			echo "<script>mp_second_buys1='" . $mp_second_buys1 . "';</script>";
		}	
		if (isset($mp_third_buys1) AND strlen($mp_third_buys1) > 0) {
			echo "<script>mp_third_buys1='" . $mp_third_buys1 . "';</script>";
		}		
		if (isset($mp_fourth_buys1) AND strlen($mp_fourth_buys1) > 0) {
			echo "<script>mp_fourth_buys1='" . $mp_fourth_buys1 . "';</script>";
		}	
		if (isset($mp_first_buys2) AND strlen($mp_first_buys2) > 0) {
			echo "<script>mp_first_buys2='" . $mp_first_buys2 . "';</script>";
		}
		if (isset($mp_second_buys2) AND strlen($mp_second_buys2) > 0) {
			echo "<script>mp_second_buys2='" . $mp_second_buys2 . "';</script>";
		}	
		if (isset($mp_third_buys2) AND strlen($mp_third_buys2) > 0) {
			echo "<script>mp_third_buys2='" . $mp_third_buys2 . "';</script>";
		}		
		if (isset($mp_fourth_buys2) AND strlen($mp_fourth_buys2) > 0) {
			echo "<script>mp_fourth_buys2='" . $mp_fourth_buys2 . "';</script>";
		}	
		if (isset($mp_first_tips) AND strlen($mp_first_tips) > 0) {
			echo "<script>mp_first_tips='" . $mp_first_tips . "';</script>";
		}
		if (isset($mp_second_tips) AND strlen($mp_second_tips) > 0) {
			echo "<script>mp_second_tips='" . $mp_second_tips . "';</script>";
		}	
		if (isset($mp_third_tips) AND strlen($mp_third_tips) > 0) {
			echo "<script>mp_third_tips='" . $mp_third_tips . "';</script>";
		}		
		if (isset($mp_fourth_tips) AND strlen($mp_fourth_tips) > 0) {
			echo "<script>mp_fourth_tips='" . $mp_fourth_tips . "';</script>";
		}					
	}
	else {
		echo "<script>mp_newCounter ='no';</script>";	
	}

	if ((isset($meta_paidcontent) AND (strlen($meta_paidcontent)) > 0) OR (isset($mp_meta_checkbox)) AND (strlen($mp_meta_checkbox)) > 0) {
		if (isset($_GET["ref"])) {
			$mp_refID = $_GET["ref"];
			echo "<script>mp_refID='" . 	esc_js($mp_refID) . "';</script>";
		}
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
	$mp_address = $myrows[0]->address;
	$mp_currency = $myrows[0]->currency;
	$mp_current_fixedAmount = $myrows[0]->fixedAmount;
	$mp_meta_share  = get_post_meta( $mypost_id, 'meta_share', true );
	if (isset($mp_meta_share) AND strlen($mp_meta_share) > 0) {
		echo "<script>console.log('sharing quote meta " . $mp_meta_share . "');</script>";
		$mp_current_sharing = $mp_meta_share;
	}
	else {
		$mp_current_sharing = $myrows[0]->sharingQuote;
	}
	$mp_current_ref = $myrows[0]->ref;
	$mp_current_metanet = $myrows[0]->noMetanet;
	$mp_current_tip_amount = $myrows[0]->fixedTipAmount;
	$mp_current_thankyou = $myrows[0]->fixedThankYou;
	$mp_barColor = $myrows[0]->barColor;
	if (!isset($meta_amount) OR $meta_amount == 0) {
		$meta_amount = $mp_current_fixedAmount;
	}
	if (!isset($meta_tip_amount) OR $meta_tip_amount == 0) {
		$meta_tip_amount = $mp_current_tip_amount;
	}
	if (!isset($meta_thankyou)) {
		$meta_thankyou = $mp_current_thankyou;
	}
	else {
		if (strlen($meta_thankyou < 1)) {
			$meta_thankyou = $mp_current_thankyou;
		}
	}
	if ( isset($myrows[0]->paywallMsg)) {
		if (strlen($myrows[0]->paywallMsg) > 1) {
			$mp_current_paywall_msg = $myrows[0]->paywallMsg;
		}
		else {
			$mp_current_paywall_msg = "Tip the author and continue reading";
		}
	}
	else {
		$mp_current_paywall_msg = "Tip the author and continue reading";
	}	
	if ( isset($myrows[0]->tippingMsg)) {
		$mp_current_tipping_msg = $myrows[0]->tippingMsg;
	}
	else {
		$mp_current_tipping_msg = "Tip the author and share this post";
	}	
	
	$mp_shortcode = "no";
	if ( has_shortcode( $post_content, 'paywall' )) {
		 $mp_shortcode = "yes";
	}
	else {
	}
	echo "<script>mp_shortCode='" . $mp_shortcode . "';</script>";
	echo "<script>mp_thankYou=\"" . esc_js($meta_thankyou) . "\";</script>";
	echo "<script>mp_theAddress='" . esc_js($mp_address) . "';</script>";
	echo "<script>mp_theCurrency='" . esc_js($mp_currency) . "';</script>";
	echo "<script>sharingQuota='" . esc_js($mp_current_sharing) . "';</script>";
	echo "<script>refQuota='" . esc_js($mp_current_ref) . "';</script>";
	echo "<script>nometanet='" . esc_js($mp_current_metanet) . "';</script>";
	echo "<script>mp_barColor='" . esc_js($mp_barColor) . "';</script>";
	echo "<script>mp_mypostid='" . esc_js($mypost_id) . "';</script>";

	if (substr($post_content, -1) == ",") {
		substr_replace(post_content ,"", -1);
	}
	// create dummy content
	$mp_lengthContent = strlen($meta_paidcontent);
	$realContent1 = $meta_paidcontent;
	$realContent1 = nl2br($realContent1);
	$realContent1 =  json_encode($realContent1);

	if (strlen($meta_paidcontent) > 300) {
		$mp_fading_content = substr( $meta_paidcontent, 0, 300);
		$mp_fading_content = wp_strip_all_tags( $mp_fading_content);
	}
	else {
			$mp_fading_content = $meta_paidcontent;
			$mp_fading_content = wp_strip_all_tags( $mp_fading_content);
	}
	$mp_length = strlen($meta_paidcontent);
	if (strlen($meta_paidcontent) > 0) {
		$path = plugin_dir_url( 'mediopay.php');
		$path = $path . "mediopay/lib/";
		echo "<script>MedioPayPath=\"" . $path . "\";</script>";
		$behindTheWall = 	"<font size='5'>" . $mp_current_paywall_msg . "</font><br />" . strlen($meta_paidcontent) . "</b> characters for " . esc_html($meta_amount) . " " . esc_html($mp_currency) . "<br /><em>No registration. No subscription. Just one swipe.</em>  <span id='mp_pay1' onclick='mp_getInfo(\"mp_pay1\")'><img src='" . $path . "questionmark.png' width='17'
	 /></span><br />";
	}
	else {
		$behindTheWall = "";
	}
	echo "<script>realContentLength=" . strlen($realContent1) . ";</script>";
	echo "<script>lengthText1=\"" . $mp_lengthContent . "\";</script>";


	$dataContent = get_the_content();
	$dataContent = substr($dataContent, 0, 168);
	$dataContent = wp_strip_all_tags( $dataContent );
	echo "<script>dataContent=\"" . esc_js($dataContent) . "\";</script>";
	echo "<script>dataLink=\"" . get_permalink() . "\";</script>";
	echo "<script>dataTitle=\"" . get_the_title() . "\";dataTitle = encodeURI(dataTitle); </script>";
	echo "<script>paymentAmount1=\"" . esc_js($meta_amount) . "\";</script>";
	echo "<script>mp_checkBox=\"" . esc_js($mp_meta_checkbox) . "\";</script>";
	echo "<script>tipAmount=\"" . esc_js($meta_tip_amount)	 . "\";</script>";
	if ($meta_paidcontent) {
		 $mp_fullcontent1 = $post_content;
		 if ($mp_shortcode == "yes") {
			 	$mp_fullcontent1 .= "<div id='mp_fade' class='mp_fading mp_invisible' >";
		 }
		 else {
			 $mp_fullcontent1 .= "<div id='mp_fade' class='mp_fading' >";
		 }
	   $mp_fullcontent1 .= $mp_fading_content . "</div>";
		 if ($mp_shortcode == "yes") {
			 $mp_fullcontent1 .= "<div class='mp_frame1 mp_invisible' id='mp_frame1' style='background-color:" . $mp_barColor . "'>";
		}
		 else {
			 	$mp_fullcontent1 .= "<div class='mp_frame1' id='mp_frame1' style='background-color:" . $mp_barColor . "'>";
		 }
		$mp_fullcontent1 .= $behindTheWall . "<script>MedioPay_textColor('mp_frame1');
	   		</script><div class='money-button' id='mbutton1'></div>
	   		<div id='mp_counter1'></div>
	   	</div>
	   		<div id='mp_unlockable1'>
	   		</div>";
	   if ($mp_meta_checkbox == "yes") {
	   		$mp_fullcontent1 = $mp_fullcontent1 . "<div style='clear:both;'></div><div class='mp_frame1 mp_invisible' id='mp_tipFrame' style='background-color:" . $mp_barColor . "'><script>MedioPay_textColor('mp_tipFrame');</script><font size='5'>". $mp_current_tipping_msg . "</font><br />
				<em>No registration. No subscription. Just one swipe.</em>  <span id='mp_tip' onclick='mp_getInfo(\"mp_tip\")'><img src='" . $path . "questionmark.png' width='17'
		 	/></span><br /><div class='money-button' id='tbutton'></div><div id='counterTips'></div></div>";
		}
	}
	else if ($mp_meta_checkbox == "yes") {
		$path = plugin_dir_url( 'mediopay.php');
		$path = $path . "mediopay/lib/";
		$mp_fullcontent1 = $post_content . "<div style='clear:both;'><div class='mp_frame1' id='mp_tipFrame' style='background-color:" . $mp_barColor . "'><font size='5'>" . $mp_current_tipping_msg . "</font><br />
		<em>No registration. No subscription. Just one swipe.</em>  <span id='mp_tip' onclick='mp_getInfo(\"mp_tip\")'><img src='" . $path . "questionmark.png' width='17'
	 /></span><br /><div class='money-button' id='tbutton'></div><div id='counterTips'></div></div>";
	}
	else {
		$mp_fullcontent1 = $post_content;
		}

	/*if (strpos($mp_address, '@') !== false) {
			$mp_polynym_url = 'https://api.polynym.io/getAddress/' . $mp_address;
			$mp_address = json_decode(file_get_contents($mp_polynym_url));
			$mp_address = $mp_address->address;
			echo "<script>mp_theAddress='" . esc_js($mp_address) . "';</script>";
	}*/
	if(is_preview()){ 
		echo "<script>var mp_preview='yes';</script>";
	}
	else { 
		echo "<script>var mp_preview='no';</script>";
	};
   ?>
	 <script>
	 if (mp_shortCode == "yes" ) {
	 }
	 else {
		 	mp_createObject("editor");
	 }
	 </script>
<?php
   return $mp_fullcontent1;

}
else {

    return $post_content;
}
}

add_filter('the_content', 'mediopay_create_paywall');


// use PayWall with shortcodes. All the operations are the same as with the second editor field.


add_shortcode( 'paywall', 'MedioPay_paywall_function' );


//function cp_hb_shortcode_cb( $attr, $content ) {
function MedioPay_paywall_function( $attr, $content) {
	ob_start();
	global $wpdb;
	$table_name = $wpdb->prefix;
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$mypost_id = url_to_postid($actual_link);
	$meta_paidcontent = get_post_meta( $mypost_id, 'meta-paidcontent', true );
	$mp_meta_checkbox = get_post_meta( $mypost_id, 'mp_meta_checkbox', true );
	$meta_thankyou = get_post_meta( $mypost_id, 'meta-textarea', true );
	$meta_amount  = get_post_meta( $mypost_id, 'meta-amount', true );
	$meta_tip_amount  = get_post_meta( $mypost_id, 'meta-tipAmount', true );
	$mp_meta_secret1  = get_post_meta( $mypost_id, 'meta-secretword-1', true );
	$mp_meta_secret2  = get_post_meta( $mypost_id, 'meta-secretword-2', true );
	$mp_meta_share  = get_post_meta( $mypost_id, 'meta-share', true );
	echo "<script>mp_metasecret1='" . hash('sha256', $mp_meta_secret1) . "';</script>";
	echo "<script>mp_metasecret2='" . hash('sha256', $mp_meta_secret2) . "';</script>";
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" );
	$mp_address = $myrows[0]->address;
	$mp_currency = $myrows[0]->currency;
	$mp_current_fixedAmount = $myrows[0]->fixedAmount;
	$mp_meta_share  = get_post_meta( $mypost_id, 'meta_share', true );
	if (isset($mp_meta_share) AND strlen($mp_meta_share) > 0) {
		echo "<script>console.log('sharing quote meta " . $mp_meta_share . "');</script>";
		$mp_current_sharing = $mp_meta_share;
	}
	else {
		$mp_current_sharing = $myrows[0]->sharingQuote;
	}
	$mp_current_ref = $myrows[0]->ref;
	$mp_current_metanet = $myrows[0]->noMetanet;
	$mp_current_tip_amount = $myrows[0]->fixedTipAmount;
	$mp_current_thankyou = $myrows[0]->fixedThankYou;
	$mp_barColor = $myrows[0]->barColor;
	$mypost_id = url_to_postid($actual_link);
	if ( isset($myrows[0]->paywallMsg)) {
		$mp_current_paywall_msg = $myrows[0]->paywallMsg;
	}
	else {
		$mp_current_paywall_msg = "Tip the author and continue reading";
	}	
	
	$mp_newcounter2 = get_post_meta( $mypost_id, 'meta-newcounter2', true );
	if (isset($mp_newcounter2)) {
		echo "<script>mp_newCounter2 ='" . $mp_newcounter2 . "';</script>";
		$mp_buys1 = get_post_meta( $mypost_id, 'meta_buys1', true );
		$mp_buys2 = get_post_meta( $mypost_id, 'meta_buys2', true );
		echo "<script>mp_buys2 ='" . $mp_buys2 . "';</script>";
		$mp_tips = get_post_meta( $mypost_id, 'meta_tips', true );
		$mp_first_buys1 = get_post_meta( $mypost_id, 'meta-first-buys1', true );
		$mp_second_buys1 = get_post_meta( $mypost_id, 'meta-second-buys1', true );	
		$mp_third_buys1 = get_post_meta( $mypost_id, 'meta-third-buys1', true );	
		$mp_fourth_buys1 = get_post_meta( $mypost_id, 'meta-fourth-buys1', true );	
		$mp_first_buys2 = get_post_meta( $mypost_id, 'meta-first-buys2', true );
		$mp_second_buys2 = get_post_meta( $mypost_id, 'meta-second-buys2', true );	
		$mp_third_buys2 = get_post_meta( $mypost_id, 'meta-third-buys2', true );	
		$mp_fourth_buys2 = get_post_meta( $mypost_id, 'meta-fourth-buys2', true );
		$mp_first_tips = get_post_meta( $mypost_id, 'meta-first-tips', true );
		$mp_second_tips = get_post_meta( $mypost_id, 'meta-second-tips', true );	
		$mp_third_tips = get_post_meta( $mypost_id, 'meta-third-tips', true );	
		$mp_fourth_tips = get_post_meta( $mypost_id, 'meta-fourth-tips', true );	
		if (isset($mp_buys1) AND strlen($mp_buys1) > 0) {
			echo "<script>mp_buys1=" . $mp_buys1 . ";console.log('buys1db'); console.log('mp buys ' + mp_buys1);</script>";
		}	
		else {
			echo "<script>mp_buys1=0;console.log('buys1zero');console.log(mp_buys1);</script>";		
		}
		if (isset($mp_buys2) AND strlen($mp_buys2) > 0) {
			echo "<script>mp_buys2=" . $mp_buys2 . ";</script>";
		}
		else {
			echo "<script>mp_buys2=0;</script>";		
		}
		if (isset($mp_tips) AND strlen($mp_tips) > 0) {
			echo "<script>mp_tips=" . $mp_tips . ";</script>";
		}	
		else {
			echo "<script>mp_tips=0;</script>";		
		}	
		if (isset($mp_first_buys1) AND strlen($mp_first_buys1) > 0) {
			echo "<script>mp_first_buys1='" . $mp_first_buys1 . "';</script>";
		}
		if (isset($mp_second_buys1) AND strlen($mp_second_buys1) > 0) {
			echo "<script>mp_second_buys1='" . $mp_second_buys1 . "';</script>";
		}	
		if (isset($mp_third_buys1) AND strlen($mp_third_buys1) > 0) {
			echo "<script>mp_third_buys1='" . $mp_third_buys1 . "';</script>";
		}		
		if (isset($mp_fourth_buys1) AND strlen($mp_fourth_buys1) > 0) {
			echo "<script>mp_fourth_buys1='" . $mp_fourth_buys1 . "';</script>";
		}	
		if (isset($mp_first_buys2) AND strlen($mp_first_buys2) > 0) {
			echo "<script>mp_first_buys2='" . $mp_first_buys2 . "';</script>";
		}
		if (isset($mp_second_buys2) AND strlen($mp_second_buys2) > 0) {
			echo "<script>mp_second_buys2='" . $mp_second_buys2 . "';</script>";
		}	
		if (isset($mp_third_buys2) AND strlen($mp_third_buys2) > 0) {
			echo "<script>mp_third_buys2='" . $mp_third_buys2 . "';</script>";
		}		
		if (isset($mp_fourth_buys2) AND strlen($mp_fourth_buys2) > 0) {
			echo "<script>mp_fourth_buys2='" . $mp_fourth_buys2 . "';</script>";
		}	
		if (isset($mp_first_tips) AND strlen($mp_first_tips) > 0) {
			echo "<script>mp_first_tips='" . $mp_first_tips . "';</script>";
		}
		if (isset($mp_second_tips) AND strlen($mp_second_tips) > 0) {
			echo "<script>mp_second_tips='" . $mp_second_tips . "';</script>";
		}	
		if (isset($mp_third_tips) AND strlen($mp_third_tips) > 0) {
			echo "<script>mp_third_tips='" . $mp_third_tips . "';</script>";
		}		
		if (isset($mp_fourth_tips) AND strlen($mp_fourth_tips) > 0) {
			echo "<script>mp_fourth_tips='" . $mp_fourth_tips . "';</script>";
		}					
	}
	else {
		echo "<script>mp_newCounter2 ='no';</script>";	
	}	
	
	echo "<script>mp_mypostid='" . esc_js($mypost_id) . "';</script>";

	if (!isset($meta_amount) OR $meta_amount == 0) {
		$meta_amount = $mp_current_fixedAmount;
	}
	if (!isset($meta_tip_amount) OR $meta_tip_amount == 0) {
		$meta_tip_amount = $mp_current_tip_amount;
	}
	if (!isset($meta_thankyou)) {
		$meta_thankyou = $mp_current_thankyou;
	}
	else {
		if (strlen($meta_thankyou < 1)) {
			$meta_thankyou = $mp_current_thankyou;
		}
	}


	echo "<script>mp_theAddress='" . esc_js($mp_address) . "';</script>";
	echo "<script>mp_theCurrency='" . esc_js($mp_currency) . "';</script>";
	echo "<script>sharingQuota='" . esc_js($mp_current_sharing) . "';</script>";
	echo "<script>refQuota='" . esc_js($mp_current_ref) . "';</script>";
	echo "<script>nometanet='" . esc_js($mp_current_metanet) . "';</script>";
	echo "<script>mp_barColor='" . esc_js($mp_barColor) . "';</script>";


	if (isset($attr["amount"])){
		echo "<script>paymentAmount2=\"" . esc_js($attr["amount"]) . "\";</script>";
		$mp_amount = $attr["amount"];
	}
	else {
		echo "<script>paymentAmount2=\"" . esc_js($mp_current_fixedAmount) . "\";</script>";
		$mp_amount = $mp_current_fixedAmount;
	}

	$mp_lengthContent = strlen($content);
	$mp_realContent2 = $content;


	$mp_realContent2 =  json_encode($mp_realContent2);

	if (strlen($mp_realContent2) > 300) {
		$mp_fading_content_2 = substr( $content, 0, 300);
		$mp_fading_content_2 = wp_strip_all_tags( $mp_fading_content_2);
	}
	else {
			$mp_fading_content_2 =  wp_strip_all_tags( $content );
	}

	//echo "<script>realContent2=" . $mp_realContent2 . ";</script>";
	echo "<script>lengthText=\"" . $mp_lengthContent . "\";</script>";
	echo "<script>mp_paymentAmount2=\"" . esc_js($mp_amount) . "\";</script>";

	$dataContent = get_the_content();
	$dataContent = substr($dataContent, 0, 168);
	$dataContent = wp_strip_all_tags( $dataContent );

	echo "<script>dataContent=\"" . esc_js($dataContent) . "\";</script>";
	echo "<script>dataLink=\"" . get_permalink() . "\";</script>";
	echo "<script>dataTitle=\"" . get_the_title() . "\";dataTitle = encodeURI(dataTitle); </script>";
	
	$mp_length = strlen($content);	
	
	if (strlen($content) > 0) {
		$path = plugin_dir_url( 'mediopay.php');
		$path = $path . "mediopay/lib/";
		echo "<script>MedioPayPath=\"" . $path . "\";</script>";
		$behindTheWall2 = "<font size='5'>" . $mp_current_paywall_msg . "</font><br />" . strlen($content) . "</b> characters for " . esc_html($mp_amount) . " " . esc_html($mp_currency) .
		"<br /><em>No registration. No subscription. Just one swipe.</em>  <span id='mp_pay2' onclick='mp_getInfo(\"mp_pay2\")'><img src='" . $path . "questionmark.png' width='17'
	 /></span><br />";
 	}
	else {
		$behindTheWall2 = "";
	}
	if(is_preview()){ 
		echo "<script>var mp_preview='yes';</script>";
	}
	else { 
		echo "<script>var mp_preview='no';</script>";
	};
?>
<div id='mp_fade2' class='mp_fading' ><?php echo $mp_fading_content_2 ?></div>
		<div class='mp_frame2' id='mp_frame2' style='background-color:<?php echo $mp_barColor ?>'><script>MedioPay_textColor('mp_frame2');</script><?php echo $behindTheWall2 ?><div class='money-button' id='mbutton2'></div>
			<div id='mp_counter2'></div>
		</div>
			<div id='mp_unlockable2'><div id='mp_unlockable2_content'></div>

<script>
	if (typeof mp_checkBox == "undefined") {
		mp_checkBox = "no";
	}
	mp_createObject("mp_shortcode");
	mediopayHideNextElements();
	</script>
<?php
	return ob_get_clean();
}

add_action ( 'wp_ajax_mp_throwcontent', 'mp_throwcontent' );
add_action ( 'wp_ajax_nopriv_mp_throwcontent', 'mp_throwcontent' );


function mp_throwcontent() {
	$mp_mypost_id = $_POST['MedioPay_postid'];
	$mp_outputs = $_POST['MedioPay_outputs'];
	$mp_number = $_POST['MedioPay_number'] + 1;
	$mp_userid = $_POST['MedioPay_userID'];
	$mp_newCounter = $_POST['Mediopay_newCounter'];
	$mp_share = $_POST['MedioPay_shareQuote'];
	if ($mp_newCounter == "yes") {
		update_post_meta( $mp_mypost_id, 'meta_buys1', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($mp_number == 1) {
			update_post_meta( $mp_mypost_id, 'meta-first-buys1', $mp_userid);			
		}
		if ($mp_number == 2) {
			update_post_meta( $mp_mypost_id, 'meta-second-buys1', $mp_userid);			
		}
		if ($mp_number == 3) {
			update_post_meta( $mp_mypost_id, 'meta-third-buys1', $mp_userid);			
		}
		if ($mp_number == 4) {
			update_post_meta( $mp_mypost_id, 'meta-fourth-buys1', $mp_userid);			
		}												
	}
	else {
		echo "update newcounter";
		update_post_meta($mp_mypost_id, 'meta-newcounter', 'yes');
		update_post_meta( $mp_mypost_id, 'meta_buys1', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($_POST['MedioPay_firstPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-first-buys1', $_POST['MedioPay_firstPartner']);				
		}
		if ($_POST['MedioPay_secondPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-second-buys1', $_POST['MedioPay_secondPartner']);				
		}
		if ($_POST['MedioPay_thirdPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-third-buys1', $_POST['MedioPay_thirdPartner']);				
		}
		if ($_POST['MedioPay_fourthPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-fourth-buys1', $_POST['MedioPay_fourthPartner']);				
		}
	}
	// if meta-newCounter doesn't exist // is no: Meta-newcounter = yes.
	global $wpdb;
	$table_name = $wpdb->prefix . 'mediopay';
	$mp_paid_content_1 = get_post_meta( $mp_mypost_id, 'meta-paidcontent', true );
	$mp_paid_content_1= nl2br($mp_paid_content_1);
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE id = 1" );
	$mp_address = $myrows[0]->address;
	if (in_array($mp_address, $mp_outputs)) {
		$mp_meta_secret1 = get_post_meta( $mp_mypost_id, 'meta-secretword-1', true );	
		if (strlen($mp_meta_secret1) > 0) {
				echo "secret" . $mp_meta_secret1 . $mp_paid_content_1;
			}
			else {
				echo "nosecret1111" . $mp_meta_secret1 . $mp_paid_content_1;
			}
	}
	else {
		echo "12345654321 Address doesn't match. Are you trying to cheat?";	
	}
wp_die();
}

add_action ( 'wp_ajax_mp_throwcontent_2', 'mp_throwcontent_2' );
add_action ( 'wp_ajax_nopriv_mp_throwcontent_2', 'mp_throwcontent_2' );

function mp_throwcontent_2() {
	$mp_mypost_id = $_POST['MedioPay_postid'];
	$mp_outputs = $_POST['MedioPay_outputs'];
	$mp_number = $_POST['MedioPay_number'] + 1;
	$mp_userid = $_POST['MedioPay_userID'];
	$mp_newCounter = $_POST['Mediopay_newCounter'];
	$mp_share = $_POST['MedioPay_shareQuote'];
	if ($mp_newCounter == "yes") {
		update_post_meta( $mp_mypost_id, 'meta_buys2', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($mp_number == 1) {
			update_post_meta( $mp_mypost_id, 'meta-first-buys2', $mp_userid);			
		}
		if ($mp_number == 2) {
			update_post_meta( $mp_mypost_id, 'meta-second-buys2', $mp_userid);			
		}
		if ($mp_number == 3) {
			update_post_meta( $mp_mypost_id, 'meta-third-buys2', $mp_userid);			
		}
		if ($mp_number == 4) {
			update_post_meta( $mp_mypost_id, 'meta-fourth-buys2', $mp_userid);			
		}												
	}
	else {
		echo "update newcounter";
		update_post_meta($mp_mypost_id, 'meta-newcounter2', 'yes');
		update_post_meta( $mp_mypost_id, 'meta_buys2', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($_POST['MedioPay_firstPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-first-buys2', $_POST['MedioPay_firstPartner']);				
		}
		if ($_POST['MedioPay_secondPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-second-buys2', $_POST['MedioPay_secondPartner']);				
		}
		if ($_POST['MedioPay_thirdPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-third-buys2', $_POST['MedioPay_thirdPartner']);				
		}
		if ($_POST['MedioPay_fourthPartner'] !== "no")	{
			update_post_meta( $mp_mypost_id, 'meta-fourth-buys2', $_POST['MedioPay_fourthPartner']);				
		}
	}
	global $wpdb;
	$table_name = $wpdb->prefix . 'posts';
	$myrows = $wpdb->get_results( "SELECT post_content FROM " . $table_name . " WHERE ID = " . $mp_mypost_id );
	$mp_paid_content_2 = $myrows[0]->post_content;
	$mp_pos = strpos($mp_paid_content_2, "[paywall");
	$mp_pos_helper = substr($mp_paid_content_2,$mp_pos, 20);
	$mp_pos_helper_pos = strpos($mp_pos_helper, "]");
	$mp_pos = $mp_pos + $mp_pos_helper_pos + 1;
	$mp_pos2 = strpos($mp_paid_content_2, "[/paywall]");
	$mp_paid_content_2 = substr($mp_paid_content_2, $mp_pos, ($mp_pos2 - $mp_pos - 9));
	$mp_paid_content_2 =  nl2br($mp_paid_content_2);
	//$mp_paid_content_2 = wp_kses_post($mp_paid_content_2);
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE id = 1" );
	$mp_address = $myrows[0]->address;
	if (in_array($mp_address, $mp_outputs)) {
		if (get_post_meta( $mp_mypost_id, 'meta-secretword-2', true ) !== null) {
			$mp_meta_secret2 = get_post_meta( $mp_mypost_id, 'meta-secretword-2', true );	
			if (strlen($mp_meta_secret2) > 0) {
				echo "secret" . $mp_meta_secret2 . $mp_paid_content_2;
			}
			else {
				echo "nosecret" . $mp_meta_secret2 . $mp_paid_content_2;
			}
		}
		else {
			echo "absolutely no secret" . $mp_paid_content_2;
		}
	}
	else {
		echo "12345654321 Address doesn't match. Are you trying to cheat?";	
	}
wp_die();
}

add_action ( 'wp_ajax_mp_process_tip', 'mp_process_tip' );
add_action ( 'wp_ajax_nopriv_mp_process_tip', 'mp_process_tip' );


function mp_process_tip() {
	$mp_mypost_id = $_POST['MedioPay_postid'];
	$mp_outputs = $_POST['MedioPay_outputs'];
	$mp_number = $_POST['MedioPay_number'] + 1;
	$mp_userid = $_POST['MedioPay_userID'];
	$mp_newCounter = $_POST['Mediopay_newCounter'];
	$mp_share = $_POST['MedioPay_shareQuote'];
	if ($mp_newCounter == "yes") {
		update_post_meta( $mp_mypost_id, 'meta_tips', $mp_number );
		update_post_meta( $mp_mypost_id, 'meta_share', $mp_share );
		if ($mp_number == 1) {
			update_post_meta( $mp_mypost_id, 'meta-first-tips', $mp_userid);			
		}
		if ($mp_number == 2) {
			update_post_meta( $mp_mypost_id, 'meta-second-tips', $mp_userid);			
		}
		if ($mp_number == 3) {
			update_post_meta( $mp_mypost_id, 'meta-third-tips', $mp_userid);			
		}
		if ($mp_number == 4) {
			update_post_meta( $mp_mypost_id, 'meta-fourth-tips', $mp_userid);			
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


add_action ( 'wp_ajax_mp_process_cookies', 'mp_process_cookies' );
add_action ( 'wp_ajax_nopriv_mp_process_cookies', 'mp_process_cookies' );

function mp_process_cookies() {
	$mp_mypost_id = $_POST['MedioPay_postid'];
	$mp_cookies = $_POST['mp_cookies'];
	$mp_position_paywall = $_POST['mp_position'];
	$mp_meta_secret1 = get_post_meta( $mp_mypost_id, 'meta-secretword-1', true );
	$mp_meta_secret2 = get_post_meta( $mp_mypost_id, 'meta-secretword-2', true );
	if ($mp_position_paywall == "second_editor") {	
		if ( strpos($mp_cookies, $mp_meta_secret1) !== false ) {
			global $wpdb;
	   	$table_name = $wpdb->prefix . 'mediopay';
			$mp_paid_content_1 = get_post_meta( $mp_mypost_id, 'meta-paidcontent', true );
			$mp_paid_content_1= nl2br($mp_paid_content_1);	
			echo $mp_paid_content_1;	
		}
		else {
		}
	}
	if ($mp_position_paywall == "mp_shortcode") {	
		if ( strpos($mp_cookies, $mp_meta_secret2) !== false ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'posts';
			$myrows = $wpdb->get_results( "SELECT post_content FROM " . $table_name . " WHERE ID = " . $mp_mypost_id );
			$mp_paid_content_2 = $myrows[0]->post_content;
			$mp_pos = strpos($mp_paid_content_2, "[paywall]");
			$mp_pos2 = strpos($mp_paid_content_2, "[/paywall]");
			$mp_paid_content_2 = substr($mp_paid_content_2, ($mp_pos + 9), ($mp_pos2 - $mp_pos - 9));
			$mp_paid_content_2 =  nl2br($mp_paid_content_2);
			echo $mp_paid_content_2;
		}
		else {
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
*/

/*function add_non_fake_textarea_field( $default ) {
	$commenter = wp_get_current_commenter();
	$default['comment_notes_after'] .= 
	'<p class="comment-form-just_another_id">
	<label for="just_another_id">Comment:</label>
	<textarea id="just_another_id" name="just_another_id" cols="45" rows="8" aria-required="true"></textarea>
	</p>';
	return $default;
}*/
 
add_filter('comment_form_defaults', 'add_non_fake_textarea_field');


?>
