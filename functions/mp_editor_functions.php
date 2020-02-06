<?php
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

function mediopay_custom_meta_tips() {
 add_meta_box( 'tips', __( 'MedioPay Tips', 'mediopay-textdomain' ), 'mediopay_meta_callback_tips', 'post', 'side' );
}

function mediopay_custom_meta_second_receiver() {
 add_meta_box( 'second_receiver', __( 'MedioPay second_receiver', 'mediopay-textdomain' ), 'mediopay_meta_second_receiver', 'post', 'side' );
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
	 $myrows = $wpdb->get_results( " SELECT paywallMsg FROM " . $table_name . " WHERE id = 1" );
	 $default_paywall_msg = $myrows[0]->paywallMsg;
	 if ($currency == "BSV") {
		 $mp_steps = "0.0001";	 
	 }
	 else {
		$mp_steps = "0.01";	 
	 }
	 	 ?>
	 <label for="meta-amount"><b>PayWall Cost</b>
				<input type="number" name="meta-amount" step="<?php 
					if ($currency == "BSV") {
		 				echo "0.0001";	 
	 				}			
	 				else {
						echo "0.01";	 
	 				}							
				
			 ?>" id="meta-amount" value="<?php 
			 	if ( isset ( $mediopay_stored_meta['meta-amount'] ) ) {
					if ($currency !== "BSV") {
						echo esc_html(round($mediopay_stored_meta['meta-amount'][0], 2)); 
					}
					else {
						echo esc_html($mediopay_stored_meta['meta-amount'][0]);
					}	
				}			
			?>" />
        <?php echo "<b>" . esc_html($currency) . "</b><br />" ?></label><br /> 
	<label for "meta-paywall-msg"><b>Paywall Message</b>
		<input type="text" name="meta-paywall-msg" id="meta-paywall-msg" value="<?php 
		if ( isset ($mediopay_stored_meta['PaywallMsg'][0] ) ) {
			if ($mediopay_stored_meta['PaywallMsg'][0] !== "none") { 
				echo esc_html($mediopay_stored_meta['PaywallMsg'][0]);
			}
		} 
		else { 
			echo $default_paywall_msg;
		}
		 ?>" />        
		</label><br />        
        <?php
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
            <?php // _e( 'Checkbox label', 'mediopay-textdomain' )?>
            <?php _e( 'Add Button', 'mediopay-textdomain' )?>
            <br />Set an Amount<br />
       </label>
       <label for="meta-tipAmount">
            <input type="number" step="0.01" name="meta-tipAmount" id="meta-tipAmount" value="<?php if ( isset ( $mediopay_stored_meta['meta-tipAmount'] ) ) echo esc_html($mediopay_stored_meta['meta-tipAmount'][0]); ?>" /><?php echo "<b>" . esc_html($currency) . "</b><br />" ?>
       </label><br />
       <label for="meta-textarea">
            Add a Thank You Message or Link<br />
            <input type="text" name="meta-textarea" id="meta-textarea"  value="<?php if ( isset ( $mediopay_stored_meta['meta-textarea'] ) ) echo esc_html($mediopay_stored_meta['meta-textarea'][0]); ?>" />
        </label>
    </div>
	</p>
	<?php
}

function mediopay_meta_second_receiver( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'mediopay_nonce' );
    $mediopay_stored_meta = get_post_meta( $post->ID );
    if (isset($mediopay_stored_meta->address2) AND strlen($mediopay_stored_meta->address2) > 0) {
		$mediopay_second_address = $mediopay_stored_meta->address2;
    } 
    ?>
    <p>
    <span class="mediopay-row-title"><?php _e( 'Add address, paymail or MoneyButton ID of a second receiver of payments', 'mediopay-textdomain' )?></span>
    <div class="mediopay-row-content">
			<label for="meta-second_address">
            <input type="text" name="meta-second_address" id="meta-second_address"  value="<?php if ( isset ( $mediopay_stored_meta['address2'] ) ) echo esc_html($mediopay_stored_meta['address2'][0]); ?>" />
        </label>    
			<br />Set share in percent for the second receiver<br />    
    		<label for="meta-second_address_share">
            <input type="number" step="0.1" name="meta-second_address_share" id="meta-second_address_share" value="<?php if ( isset ( $mediopay_stored_meta['address2_share'] ) ) echo esc_html($mediopay_stored_meta['address2_share'][0]); ?>" />
        </label><br />
    </div>
	</p>
	<?php
}



function mediopay_meta_save( $post_id ) {
	 $mp_is_published = get_post_status( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'mediopay_nonce' ] ) && wp_verify_nonce( $_POST[ 'mediopay_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    $mediopay_stored_meta = get_post_meta( $post_id );
    if ( $is_revision || !$is_valid_nonce ) {
        return;
    }
    if ( isset($_POST['meta-paywall-msg'])) {
		update_post_meta ($post_id, 'PaywallMsg', sanitize_text_field($_POST[ 'meta-paywall-msg']));
	}
	else {
		update_post_meta ($post_id, 'PaywallMsg', "none");
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
	if (isset($_POST['meta-second_address'])) {
		update_post_meta( $post_id, 'address2', sanitize_text_field( $_POST[ 'meta-second_address' ] ) );
	}
	if (isset($_POST['meta-second_address_share'])) {
		update_post_meta( $post_id, 'address2_share', sanitize_text_field( $_POST[ 'meta-second_address_share' ] ) );
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




?>
