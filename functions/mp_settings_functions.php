<?php
function mediopay_register_settings() {
   add_option( 'mediopay_option_name', 'This is my option value.');
   register_setting( 'mediopay_options_group', 'mediopay_option_name', 'mediopay_callback' );
}

function mediopay_register_options_page() {
  add_options_page('mediopay Options', 'mediopay', 'manage_options', 'mediopay', 'mediopay_option_page');
}

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
	if ( isset($myrows[0]->editableTips)) {
		$mp_editable_tips = $myrows[0]->editableTips;
	}
	if ( isset($myrows[0]->address2)) {
		$mp_currentaddress_2 = $myrows[0]->address2;
	}
	else {
		$mp_currentaddress_2 = 0;	
	}
	if ( isset($myrows[0]->secondAddressShare)) {
		$mp_second_address_share = $myrows[0]->secondAddressShare;
	}
	else {
		$mp_second_address_share = 0;	
	}
	$path = plugin_dir_url( 'mediopay.php');
	$path = $path . "mediopay/mediopay.php";
	if ( isset($myrows[0]->linkColor)) {
		$mp_current_color_link = $myrows[0]->linkColor;
	}
	
   ?><form name='setmediopay' method='post' action="<?php esc_url( $_SERVER['REQUEST_URI'] ) ?>">
		<table class="mediopay_table">
		<tr>
		<td class="mediopay_column_small">
			<b>Your Bitcoin SV address</b></td>
		<td class="mediopay_column_large">
    	<input type='text' name='MedioPay_address'<?php if (isset($mp_currentaddress) AND $mp_currentaddress !== "none") {echo "value='" . esc_html($mp_currentaddress) . "'";} ?>
    	/>
    	<br />Enter your Bitcoin SV (BSV) address, paymail address or MoneyButton ID.

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
			<td  class="mediopay_column_small">
			<b>Set color of the link</b>
			</td>
			<td  class="mediopay_column_large">
				<label for="MedioPay_link_color"><?php if (isset($mp_current_color_link)) { esc_html($mp_current_color_link); } ?>
					<input type="color" id="select_color" name="MedioPay_link_color" value="<?php if (isset($mp_current_color_link)) { echo esc_html($mp_current_color_link); } ?>"
		           >
		   </label>
		   <br />Design the link color in the paywall field. Warning: If you change it, it will override the default setting according to your theme. To pick a color, you can install a color picker plugin
		   for your browser.
		   </td>
		</tr>
    	<!--<tr>
			<td  class="mediopay_column_small">
			<b>Align left</b>
			</td>
			<td  class="mediopay_column_large">
				<label for="MedioPay_align_left">
				<input type="checkbox" name="MedioPay_align_left" id="MedioPay_align_left" value="yes" <?php if ( isset($mp_align_left)) { if ($mp_align_left == 'yes') { echo "checked";} } ?> "/>   			 
				</label>
  	 <br />Sometimes themes have a weird effect on MoneyButton positions, and sometimes centering them doesn't fit in your design. With this option
  	 you can align the content of the paywall / tipping field to the left.
		   </td>
		</tr>-->
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
	<input type="number" step="<?php 
		if ($mp_currentcurrency == "BSV") {
		 echo "0.0001";	
	 	}
	 	else {
			echo "0.01";
	 	}	
	?>" name="MedioPay_fixed_amount" id="MedioPay_fixed_amount" value="<?php 
		if ( $mp_current_fixedAmount !== "none") { 
			if ($mp_currentcurrency !== "BSV") {
				echo esc_html(round($mp_current_fixedAmount,2));
			}			
			else {
				echo esc_html($mp_current_fixedAmount);			
			}
		}	
	 ?>" />
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
	<input type="number" step="<?php 
		if ($mp_currentcurrency == "BSV") {
			 echo "0.0001";	
	 	}
	 	else {
			echo "0.01";
	 	}	
	?>" name="MedioPay_fixed_amount_tips" id="MedioPay_fixed_amount_tips" value="<?php 
		if ( $mp_current_tip_amount !== "none") { 
			if ($mp_currentcurrency !== "BSV") {
				echo esc_html(round($mp_current_tip_amount,2));
			}			
			else {
				echo esc_html($mp_current_tip_amount);			
			}
		}			
	?>" />
        <?php echo "<b>" . esc_html($mp_currentcurrency) . "</b><br />" ?>
   </label>
   Same as the option above: Set a default tip amount.
   </td>
   </tr>
    <tr>
   <td class="mediopay_column_small">
   <b>Activate Editable Tips</b>
   <br /></td>
   <td class="mediopay_column_large">
	<label for="MedioPay_editable_tips">
	<input type="checkbox" name="MedioPay_editable_tips" id="MedioPay_editable_tips" value="yes" <?php if ( isset($mp_editable_tips)) { if ($mp_editable_tips == 'yes') { echo "checked";} } ?> "/> 

   </label>
   <br />Let your users chose the amount they tip you. Most people are more gracious if they can decide for themselves how much to give.
   </td>
   </tr>
   <tr>
	<td class="mediopay_column_small">
   <h2 class="mediopay_options_h2">Add a second receiver of payments</h2>
   <p>If you work in a team, you can set a second address to get a share of the payments. You can also set it at the side widgets on the editor page. 
   Settings for single posts will override default settings for this post.</code></p>
	</td></tr>
	<tr>
		<td class="mediopay_column_small">
			<b>Enter second BSV address</b></td>
		<td class="mediopay_column_large">
    	<input type='text' name='MedioPay_address_2'<?php if (isset($mp_currentaddress_2) AND $mp_currentaddress_2 !== "none") {echo "value='" . esc_html($mp_currentaddress_2) . "'";} ?>
    	/>
    	<br />Enter a second BSV address, paymail address or MoneyButton ID to share the payments. Sharing is deactivated if address is not set.

    	</td>
    	</tr>
		<tr>
   	<td class="mediopay_column_small">
   	<b>Set share of second address</b></td>
 		<td class="mediopay_column_large">
		<label for="MedioPay_second_address_share"><?php if ($mp_second_address_share !== 0) { esc_html($mp_second_address_share);} ?>
		<select name='MedioPay_second_address_share'>
      	<option value="0.0" <?php if ($mp_second_address_share == "0.0") {echo  "selected='selected'";} ?>
      	>0%</option>
      	<option value="0.1" <?php if ($mp_second_address_share == "0.1") {echo  "selected='selected'";} ?>
      	>10%</option>
      	<option value="0.2" <?php if ($mp_second_address_share == "0.2") {echo  "selected='selected'";} ?>
      	>20%</option>
      	<option value="0.3" <?php if ($mp_second_address_share == "0.3") {echo  "selected='selected'";} ?>
      	>30%</option>
			<option value="0.4" <?php if ($mp_second_address_share == "0.4") {echo  "selected='selected'";} ?>
      	>40%</option>
      	<option value="0.5" <?php if ($mp_second_address_share == "0.5") {echo  "selected='selected'";} ?>
      	>40%</option>
   	</select>
   	</label>
   	<br />Define the share of the second address. If set to 0 percent, payouts to second address are disabled.
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
	<input type="hidden" name="settings" value="yes">
	<div id="url"></div>
	<script type="text/javascript" >
		const thisURL = window.location.href;
		document.getElementById("url").innerHTML = "<input type='hidden' name='MedioPay_thisURL' value='" + thisURL + "' />";
	</script>
   <input type="submit" class="button button-primary" value='save' />
   </form></div>
    	<?php
}





?>
