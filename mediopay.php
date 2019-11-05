<?php
/*
 * Plugin Name: MedioPay-test
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

function MedioPay_add_scripts() {
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "MedioPay/lib/";
	wp_enqueue_style( 'style', $path . 'style.css' );
	wp_enqueue_script( 'moneybutton', $path . 'moneybutton.js', true);
	wp_enqueue_script( 'bsv', $path . 'bsv.min.js', true);
	wp_enqueue_script( 'scripts_pre_paywall', $path . 'scripts_pre_paywall.js', true);
	wp_enqueue_script( 'scripts_create_paywall', $path . 'scripts_create_paywall.js', true);
	wp_enqueue_script( 'scripts_after_paywall', $path . 'scripts_after_paywall.js',  true);
}
add_action( 'wp_enqueue_scripts', 'MedioPay_add_scripts' );

function MedioPay_add_admin_scripts() {
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "MedioPay/lib/";
	//admin_enqueue_scripts( 'style', $path . 'style.css' );
	//admin_enqueue_scripts( 'scripts_pre_paywall', $path . 'scripts_pre_paywall.js', true);
}

add_action( 'admin_enqueue_scripts', 'MedioPay_add_admin_scripts' );

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

	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "MedioPay/lib/";
	wp_enqueue_style( 'style', $path . 'style.css' );
	?>
	<h1>MedioPay Micropayment Options</h1>
	<h2>Basic Options</h2>
	<p>These options are required to use MedioPay.</p>
	 <?php
	global $wpdb;
	//echo $wpdb->dbname;
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE id = 1" );
	$mp_currentaddress = $myrows[0]->address;
	$myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	$mp_currentcurrency = $myrows[0]->currency;
	$myrows = $wpdb->get_results( "SELECT fixedAmount FROM " . $table_name . " WHERE id = 1" );
	$mp_current_fixedAmount = $myrows[0]->fixedAmount;
	$myrows = $wpdb->get_results( "SELECT sharingQuote FROM " . $table_name . " WHERE id = 1" );
	$mp_current_sharing = $myrows[0]->sharingQuote;
	$myrows = $wpdb->get_results( "SELECT ref FROM " . $table_name . " WHERE id = 1" );
	$mp_current_ref = $myrows[0]->ref;
	$myrows = $wpdb->get_results( "SELECT noMetanet FROM " . $table_name . " WHERE id = 1" );
	$mp_current_metanet = $myrows[0]->noMetanet;
	$myrows = $wpdb->get_results( "SELECT fixedTipAmount FROM " . $table_name . " WHERE id = 1" );
	$mp_current_tip_amount = $myrows[0]->fixedTipAmount;
	$myrows = $wpdb->get_results( "SELECT fixedThankYou FROM " . $table_name . " WHERE id = 1" );
	$mp_current_thankyou = $myrows[0]->fixedThankYou;
	$myrows = $wpdb->get_results( "SELECT noEditField FROM " . $table_name . " WHERE id = 1" );
	$mp_current_edit = $myrows[0]->noEditField;
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "MedioPay/mediopay.php";
	$myrows = $wpdb->get_results( "SELECT barColor FROM " . $table_name . " WHERE id = 1" );
	$mp_current_color = $myrows[0]->barColor;
   ?>

    	<form name='setmediopay' method='post' action=" <?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
    	<!--<form name="setmediopay" id="setmediopay">-->
		<table class="mediopay_table">
		<tr>
		<td>
			<b>Your Bitcoin SV address</b><br />Enter your Bitcoin SV address, your paymail address or just your MoneyButton handle. You can use any BSV-Wallet, but we recommend <a href='https://moneybutton.com' target='_blank'>
			MoneyButton</a> as it allows you to test your paywall for yourself.
		</td>
		<td>
    	<input type='text' name='MedioPay_address' <?php if (isset($mp_currentaddress) AND $mp_currentaddress !== "none") {echo "value='" . esc_html($mp_currentaddress) . "'";} ?>
    	/>
    	</td>
    	</tr>
		<tr>
		<td>
    	<b>The currency to denominate payments.</b><br />Set a currency in which the payments are denominated.
    	</td>
    	<td>
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
		</td>
		</tr>
		<tr>
		<td>
    	<h2>Advanced Options</h2>These settings are optional.
    	</td>
    	</tr>
			<tr>
			<td>
			<b>Set color of the paywall</b><br />Individualize your paywall so it fits the design of your blog.
			</td>
			<td>
				<label for="MedioPay_bar_color"><?php esc_html($mp_current_color) ?><br />
					<input type="color" id="select_color" name="MedioPay_bar_color" value="<?php echo esc_html($mp_current_color); ?>"
		           >
		   </label>
		   </td>
		   </tr>

    	<tr>
    	<td>
    	<b>Deactivate Metadata</b><br />Don't add metadata to the transactions.
		<br /><br /></td>
		<td>
		<label for="MedioPay_no_metadata">
		<input type="checkbox" name="MedioPay_deactivate_metadata" id="MedioPay_deactivate_metadata" value="yes" <?php if ( $mp_current_metanet == "yes" ) {echo "checked";} ?> "/>
	</label>
		</td>
		</tr>
		<tr>
		<td><b>Deactivate PayWall Edit Field</b><br />In some cases the second editor field is annoying. Deactivate it and use the <code>[paywall]...[/paywall]</code>
		shortcode: Just put the paywalled content between the shortcodes.<br />
		</td>
		<td>
	<label for="MedioPay_no_edit_field">
		<input type="checkbox" name="MedioPay_deactivate_edit" id="MedioPay_deactivate_edit" value="yes" <?php if ( $mp_current_edit == "yes" ) {echo "checked";} ?> "/>
	</label>
	</td>
	</tr>
	   <tr>
   <td>
   <b>Set your sharing quote</b><br />First buyers or tipers get a share of future income. Set how much you want to share with your readers.
 	<br /><br /></td>
 	<td>
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
   </td>
   </tr>
   <tr>
   <td>
   <b>Set your Reflink share</b><br />After buying your article, readers get an affiliate link for it. Set how much
	 you want to share with the affiliate link.   <br /><br /></td>
   <td>
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
	</td>
	</tr>
	<tr>
	<td>
	<b>Set a default amount for your paywall</b><br />You can set a default amount for the paywall. This makes it more convenient for you, as you don't need to set the amount with each paywall. If
	you set not default amount, you have to enter it manually, either above the second editor field, or in the shortcode: <code>[paywall amount="0.5"]</code>.
	<br /><br /></td>
	<td>
	<label for="MedioPay_fixed_amount">
	<input type="number" step="0.01" name="MedioPay_fixed_amount" id="MedioPay_fixed_amount" value="<?php if ( $mp_current_fixedAmount !== "none") { echo esc_html($mp_current_fixedAmount);} ?>" />
        <?php echo "<b>" . esc_html($mp_currentcurrency) . "</b><br />" ?>
   </label>
   </td>
   </tr>
   <tr>
   <td>
   <b>Set a default amount for tips</b><br />Same as with the paywall amount, but for tips: Set a default tip amount, so you don't need to set it for each post.
   <br /></td>
   <td>
	<label for="MedioPay_fixed_amount_tips">
	<input type="number" step="0.01" name="MedioPay_fixed_amount_tips" id="MedioPay_fixed_amount_tips" value="<?php if ( $mp_current_tip_amount !== "none") { echo esc_html($mp_current_tip_amount);} ?>" />
        <?php echo "<b>" . esc_html($mp_currentcurrency) . "</b><br />" ?>
   </label>
   </td>
   </tr>
   <tr>
   <td>
   <b>Set a fixed Thank You Message for Tips</b><br />When someone tips you, a thank you message is shown. You can either type it specifically for each post, or you can set a default thank you message.
   </td>
   <td>
   <label for="MedioPay_fixed_thank_you">
   <input type="text" name="MedioPay_fixed_thank_you" id="MedioPay_fixed_thank_you"  value="<?php if ( isset ( $mp_current_thankyou ) ) echo esc_html($mp_current_thankyou); ?>" /><br /><br />
	<div id="url"></div>
	</td>
	</tr>
	</table>
	<script type="text/javascript" >
		const thisURL = window.location.href;
		document.getElementById("url").innerHTML = "<input type='hidden' name='MedioPay_thisURL' value='" + thisURL + "' />";
	</script>
   <input type="submit" class="button button-primary" value='save' />
   </form>
    	<?php
}
// save the settings

if(isset($_POST['MedioPay_address']) OR isset($_POST['MedioPay_currency']) OR isset($_POST['deactivate_metadata']) OR isset($_POST['MedioPay_sharing_quote']) OR isset($_POST['ref_quote']) OR isset($_POST['fixed_amount']) OR isset($_POST['fixed_amount_tips']) OR isset($_POST['fixed_thank_you']) OR isset($_POST['bar_color'])) {
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
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newcurrency = array( 'currency' => $newcurrency );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newcurrency,$data_where);
	}
	if(isset($_POST['MedioPay_sharing_quote'])) {
		$newsharing = sanitize_text_field($_POST["MedioPay_sharing_quote"]);
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
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
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
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
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newthankyou = array( 'fixedThankYou' => $newthankyou );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newthankyou,$data_where);
	}
	if(isset($_POST['MedioPay_deactivate_metadata'])) {
		$newmetadata = sanitize_text_field($_POST["MedioPay_deactivate_metadata"]);
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
	if(isset($_POST['MedioPay_deactivate_edit'])) {
		$newedit = sanitize_text_field($_POST["MedioPay_deactivate_edit"]);
		//echo $newedit;
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newedit = array( 'noEditField' => $newedit );
		$data_where = array( 'id' => 1);
		$wpdb->update($table_name,$newedit,$data_where);
	}
	if(isset($_POST['MedioPay_bar_color'])) {
		$newedit = sanitize_hex_color($_POST["MedioPay_bar_color"]);
		//echo $newedit;
		global $wpdb;
		$table_name = $wpdb->prefix . 'mediopay';
		$newedit = array( 'barColor' => $newedit );
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
	if(isset($_POST['MedioPay_thisURL'])) {
		$thisURL = esc_url($_POST['MedioPay_thisURL']);
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
            <input type="number" step="0.01" name="meta-tipAmount" id="meta-tipAmount" value="<?php if ( isset ( $mediopay_stored_meta['meta-tipAmount'] ) ) echo esc_html($mediopay_stored_meta['meta-tipAmount'][0]); ?>" />
        <?php echo "<b>" . esc_html($currency) . "</b><br />" ?></label><br />
            Add a Thank You Message or Link<br />
            <input type="text" name="meta-textarea" id="meta-textarea"  value="<?php if ( isset ( $mediopay_stored_meta['meta-textarea'] ) ) echo esc_html($mediopay_stored_meta['meta-textarea'][0]); ?>" />
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
    if( isset( $_POST[ 'mp_meta_checkbox' ] ) ) {
				echo "<script>console.log('update checkbox');</script>";
        update_post_meta( $post_id, 'mp_meta_checkbox', sanitize_text_field($_POST[ 'mp_meta_checkbox' ] ) );
    }
    else {
		   update_post_meta( $post_id, 'mp_meta_checkbox', "no" );
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

	if ((isset($meta_paidcontent) AND (strlen($meta_paidcontent)) > 0) OR (isset($mp_meta_checkbox)) AND (strlen($mp_meta_checkbox)) > 0) {
		if (isset($_GET["ref"])) {
			$mp_refID = $_GET["ref"];
			echo "<script>mp_refID='" . 	esc_js($mp_refID) . "';</script>";
		}


	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE id = 1" );
	$mp_address = $myrows[0]->address;
	$myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	$mp_currency = $myrows[0]->currency;
	$myrows = $wpdb->get_results( "SELECT fixedAmount FROM " . $table_name . " WHERE id = 1" );
	$mp_current_fixedAmount = $myrows[0]->fixedAmount;
	$myrows = $wpdb->get_results( "SELECT sharingQuote FROM " . $table_name . " WHERE id = 1" );
	$mp_current_sharing = $myrows[0]->sharingQuote;
	$myrows = $wpdb->get_results( "SELECT ref FROM " . $table_name . " WHERE id = 1" );
	$mp_current_ref = $myrows[0]->ref;
	$myrows = $wpdb->get_results( "SELECT noMetanet FROM " . $table_name . " WHERE id = 1" );
	$mp_current_metanet = $myrows[0]->noMetanet;
	$myrows = $wpdb->get_results( "SELECT fixedTipAmount FROM " . $table_name . " WHERE id = 1" );
	$mp_current_tip_amount = $myrows[0]->fixedTipAmount;
	$myrows = $wpdb->get_results( "SELECT fixedThankYou FROM " . $table_name . " WHERE id = 1" );
	$mp_current_thankyou = $myrows[0]->fixedThankYou;
	$myrows = $wpdb->get_results( "SELECT barColor FROM " . $table_name . " WHERE id = 1" );
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

	if (substr($post_content, -1) == ",") {
		substr_replace(post_content ,"", -1);
	}
	// create dummy content

	$mp_lengthContent = strlen($meta_paidcontent);
	$realContent1 = $meta_paidcontent;
	$realContent1 =  json_encode($realContent1);

	if (strlen($meta_paidcontent) > 400) {
		$mp_fading_content = substr( $meta_paidcontent, 0, 400);
	}
	else {
			$mp_fading_content = $meta_paidcontent;
	}
	if (strlen($meta_paidcontent) > 0) {
		$path = plugin_dir_url( 'mediopay.php');
		$path = $path . "MedioPay/lib/";
		echo "<script>MedioPayPath=\"" . $path . "\";</script>";
		$behindTheWall = 	"<h3>Tip the Author and continue reading</h3>" . strlen($meta_paidcontent) . "</b> characters for " . esc_html($meta_amount) . " " . esc_html($mp_currency) . "<br /><em>No registration. No subscription. Just one swipe.</em>  <span id='mp_pay1' onclick='mp_getInfo(\"mp_pay1\")'><img src='" . $path . "questionmark.png' width='17'
	 /></span><br />";
	}
	else {
		$behindTheWall = "";
	}
	echo "<script>realContent1=" . $realContent1 . ";</script>";
	echo "<script>lengthText1=\"" . $mp_lengthContent . "\";</script>";


	$dataContent = get_the_content();
	$dataContent = substr($dataContent, 0, 168);
	$dataContent = wp_strip_all_tags( $dataContent );
	echo "<script>dataContent=\"" . $dataContent . "\";</script>";
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
	   $mp_fullcontent1 .= $mp_fading_content . "</div></div>";
		 if ($mp_shortcode == "yes") {
			 $mp_fullcontent1 .= "<div class='mp_frame1 mp_invisible' id='mp_frame1' style='background-color:" . $mp_barColor . "'>";
		}
		 else {
			 	$mp_fullcontent1 .= "<div class='mp_frame1' id='mp_frame1' style='background-color:" . $mp_barColor . "'>";
		 }
		$mp_fullcontent1 .=		$behindTheWall .
	   		"<script>MedioPay_textColor('mp_frame1');</script><div class='money-button' id='mbutton1'></div>
	   		<div id='mp_counter1'></div>
	   	</div>
	   		<div id='mp_unlockable1'>
	   		</div>";
	   if ($mp_meta_checkbox == "yes") {
	   		$mp_fullcontent1 = $mp_fullcontent1 . "<div class='mp_frame1 mp_invisible' id='mp_tipFrame' style='background-color:" . $mp_barColor . "'><script>MedioPay_textColor('mp_tipFrame');</script><h3>Tip the author!</h3>
				<em>No registration. No subscription. Just one swipe.</em>  <span id='mp_tip' onclick='mp_getInfo(\"mp_tip\")'><img src='" . $path . "questionmark.png' width='17'
		 	/></span><br /><div class='money-button' id='tbutton'></div><div id='counterTips'></div></div>";
		}
	}
	else if ($mp_meta_checkbox == "yes") {
		$mp_fullcontent1 = $post_content . "<div class='mp_frame1' id='mp_frame1'><h3>Tip the author and share the post</h3>
		<em>No registration. No subscription. Just one swipe.</em>  <span id='mp_tip' onclick='mp_getInfo(\"mp_tip\")'><img src='" . $path . "questionmark.png' width='17'
	 /></span><br /><div class='money-button' id='tbutton'></div><div id='counterTips'></div></div>";
	}
	else {
		$mp_fullcontent1 = $post_content;
		}

   ?>
	 <script>
	 if (mp_shortCode == "yes" ) {
		 console.log("shortcode detected");
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
	$table_name = $wpdb->prefix . 'mediopay';
	$myrows = $wpdb->get_results( "SELECT address FROM " . $table_name . " WHERE id = 1" );
	$mp_address = $myrows[0]->address;
	$myrows = $wpdb->get_results( "SELECT currency FROM " . $table_name . " WHERE id = 1" );
	$mp_currency = $myrows[0]->currency;
	$myrows = $wpdb->get_results( "SELECT fixedAmount FROM " . $table_name . " WHERE id = 1" );
	$mp_current_fixedAmount = $myrows[0]->fixedAmount;
	$myrows = $wpdb->get_results( "SELECT sharingQuote FROM " . $table_name . " WHERE id = 1" );
	$mp_current_sharing = $myrows[0]->sharingQuote;
	$myrows = $wpdb->get_results( "SELECT ref FROM " . $table_name . " WHERE id = 1" );
	$mp_current_ref = $myrows[0]->ref;
	$myrows = $wpdb->get_results( "SELECT noMetanet FROM " . $table_name . " WHERE id = 1" );
	$mp_current_metanet = $myrows[0]->noMetanet;
	$myrows = $wpdb->get_results( "SELECT fixedTipAmount FROM " . $table_name . " WHERE id = 1" );
	$mp_current_tip_amount = $myrows[0]->fixedTipAmount;
	$myrows = $wpdb->get_results( "SELECT fixedThankYou FROM " . $table_name . " WHERE id = 1" );
	$mp_current_thankyou = $myrows[0]->fixedThankYou;
	$myrows = $wpdb->get_results( "SELECT barColor FROM " . $table_name . " WHERE id = 1" );
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


	echo "<script>mp_theAddress='" . esc_js($mp_address) . "';</script>";
	echo "<script>mp_theCurrency='" . esc_js($mp_currency) . "';</script>";
	echo "<script>sharingQuota='" . esc_js($mp_current_sharing) . "';</script>";
	echo "<script>refQuota='" . esc_js($mp_current_ref) . "';</script>";
	echo "<script>nometanet='" . esc_js($mp_current_metanet) . "';</script>";
	echo "<script>barColor='" . esc_js($mp_barColor) . "';</script>";

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
	if (strlen($meta_paidcontent) > 400) {
		$mp_fading_content_2 = substr( $content, 0, 400);
	}
	else {
			$mp_fading_content_2 = $content;
	}

	echo "<script>realContent2=" . $mp_realContent2 . ";</script>";
	echo "<script>lengthText=\"" . $mp_lengthContent . "\";</script>";
	echo "<script>mp_paymentAmount2=\"" . esc_js($mp_amount) . "\";</script>";

	$dataContent = get_the_content();
	$dataContent = substr($dataContent, 0, 168);
	$dataContent = wp_strip_all_tags( $dataContent );

	echo "<script>dataContent=\"" . $dataContent . "\";</script>";
	echo "<script>dataLink=\"" . get_permalink() . "\";</script>";
	echo "<script>dataTitle=\"" . get_the_title() . "\";dataTitle = encodeURI(dataTitle); </script>";

	if (strlen($content) > 0) {
		$path = plugin_dir_url( 'mediopay.php');
		$path = $path . "MedioPay/lib/";
		echo "<script>MedioPayPath=\"" . $path . "\";</script>";
		$behindTheWall2 = "<h3>Tip the author and continue reading</h3>" . strlen($content) . "</b> characters for " . esc_html($mp_amount) . " " . esc_html($mp_currency) .
		"<br /><em>No registration. No subscription. Just one swipe.</em>  <span id='mp_pay2' onclick='mp_getInfo(\"mp_pay2\")'><img src='" . $path . "questionmark.png' width='17'
	 /></span><br />";
 	}
	else {
		$behindTheWall2 = "";
	}
?>
<div id='mp_fade2' class='mp_fading' > <?php echo $mp_fading_content_2 ?></div>
		<div class='mp_frame2' id='mp_frame2' style='background-color: <?php echo $mp_barColor ?>'><script>MedioPay_textColor('mp_frame2');</script>
			<?php echo $behindTheWall2 ?>
			<div class='money-button' id='mbutton2'></div>
			<div id='mp_counter2'></div>
		</div>
			<div id='mp_unlockable2'>
			</div>
<script>
	mp_createObject("mp_shortcode");
	mediopayHideNextElements();
	</script>


				<?php
	return ob_get_clean();
}
?>
