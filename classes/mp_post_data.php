<?php

function mp_create_object($mp_post_data) {
	$path = plugin_dir_url( 'mediopay.php') . "mediopay/lib/";
	echo "<script>MedioPayPath = '" . $path . "';</script>";
	$blogpath = get_bloginfo($show = 'wpurl');
	echo "<script>mp_blogpath = '" . $blogpath . "';</script>";
	$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$mypost_id = url_to_postid($actual_link);
	global $wpdb;
	$table_name = $wpdb->prefix . 'mediopay';
	$mp_myrows = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE id = 1" ); 
	if (isset($mp_myrows[0]->alignLeft)) {		
		$mp_post_data->alignLeft = $mp_myrows[0]->alignLeft;
		echo "<script>mp_alignLeft = '" . $mp_post_data->alignLeft . "';</script>";
	}
	else {
		$mp_post_data->alignLeft = "no";
		echo "<script>mp_alignLeft = '" . $mp_post_data->alignLeft . "';</script>";
	}
	
	$mp_post_data->secret1 = get_post_meta( $mypost_id, 'meta-secretword-1', true ); 
	$mp_post_data->secret2 = get_post_meta( $mypost_id, 'meta-secretword-2', true );
	$mp_post_data->paywallmsg = get_post_meta( $mypost_id, 'PaywallMsg', true);
	if (strlen($mp_post_data->paywallmsg) == 0 OR $mp_myrows[0]->paywallMsg !== "none") {
		$mp_post_data->paywallmsg = (strlen($mp_myrows[0]->paywallMsg) > 0 AND $mp_myrows[0]->paywallMsg !== "none") ? $mp_myrows[0]->paywallMsg : $mp_post_data->paywallmsg = "Tip the author and continue reading.";	
	}		
	//var_dump($mp_myrows[0]);
	if (isset($mp_myrows[0]->editableTips)) {
		$mp_post_data->editableTips = $mp_myrows[0]->editableTips;
		echo "<script>mp_editable_tips='" . $mp_post_data->editableTips . "';</script>";
	}
	/*else {
		$mp_post_data->editableTips = 'noo';
	}*/
	$mp_post_data->paidcontent = get_post_meta( $mypost_id, 'meta-paidcontent', true );
	//echo "<script>console.log('post data " . $mp_post_data->paidcontent . "');</script>";
	$mp_post_data->checkbox = get_post_meta( $mypost_id, 'mp_meta_checkbox', true );
	$mp_post_data->thankyou = get_post_meta( $mypost_id, 'meta-textarea', true ); 
	if (strlen($mp_post_data->thankyou) == 0) {
		if (strlen($mp_myrows[0]->fixedThankYou) > 0) {
			$mp_post_data->thankyou = $mp_myrows[0]->fixedThankYou;
		}
	}
	$mp_post_data->tippingMsg = $mp_myrows[0]->tippingMsg;
	if (strlen($mp_post_data->tippingMsg) == 0) {
		$mp_post_data->tippingMsg = "Be generous and tipshare this post.";
	}

	$mp_post_data->address2 = get_post_meta( $mypost_id, 'address2', true);
	if (isset ($mp_post_data->address2)) {
		if (strlen($mp_post_data->address2) > 0) {
		}
		else {
			if (isset($mp_myrows[0]->address2)) {
				if (strlen($mp_myrows[0]->address2) > 0) {
					$mp_post_data->address2 = $mp_myrows[0]->address2;			
				}			
			}		
		}
	}
	else {
		if (isset($mp_myrows[0]->address2)) {
				if (strlen($mp_myrows[0]->address2) > 0) {
					$mp_post_data->address2 = $mp_myrows[0]->address2;			
				}			
		}		
	}
	echo "<script>mp_address2 ='" . $mp_post_data->address2 . "';</script>";	
	$mp_post_data->secondAddressShare = get_post_meta( $mypost_id, 'address2_share', true);
	if (isset ($mp_post_data->secondAddressShare)) {
		if ($mp_post_data->secondAddressShare > 0) {
			$mp_post_data->secondAddressShare = $mp_post_data->secondAddressShare / 100;
		}
		else {
			if (isset($mp_myrows[0]->secondAddressShare)) {
				if (strlen($mp_myrows[0]->secondAddressShare) > 0) {
					$mp_post_data->secondAddressShare = $mp_myrows[0]->secondAddressShare;			
				}			
			}		
		}
	}
	else {
		if (isset($mp_myrows[0]->secondAddressShare)) {
				if (strlen($mp_myrows[0]->secondAddressShare) > 0) {
					$mp_post_data->secondAddressShare = $mp_myrows[0]->secondAddressShare;			
				}			
		}		
	}
	echo "<script>mp_secondAddressShare ='" . $mp_post_data->secondAddressShare . "';</script>";
	$mp_post_data->amount = get_post_meta( $mypost_id, 'meta-amount', true ); 
	if (strlen($mp_post_data->amount) == 0 || $mp_post_data->amount == 0) {
			$mp_post_data->amount = $mp_myrows[0]->fixedAmount; 
	}	
	$mp_post_data->fixedAmount = $mp_myrows[0]->fixedAmount; 
	echo "<script>mp_fixedAmount ='" . $mp_post_data->fixedAmount . "';</script>";
	$mp_post_data->tip_amount = get_post_meta( $mypost_id, 'meta-tipAmount', true );
	if (strlen($mp_post_data->tip_amount) == 0) {
			$mp_post_data->tip_amount = $mp_myrows[0]->fixedTipAmount; 
	}	
	$mp_post_data->share  = get_post_meta( $mypost_id, 'meta_share', true );
	if (strlen($mp_post_data->share) == 0) {
			$mp_post_data->share = $mp_myrows[0]->sharingQuote;; 
	}	
	
	

	$mp_post_data->mp_tipped_amount = get_post_meta ($mypost_id, 'mp-tipped-amount');
	
	if (isset($mp_post_data->mp_tipped_amount)) {
		if (gettype($mp_post_data->mp_tipped_amount)	== "array") {
			 if (isset($mp_post_data->mp_tipped_amount[0])) {
					echo "<script>mp_tippedAmount ='" . $mp_post_data->mp_tipped_amount . "';</script>";	
			}
		}
		else {
			 if ($mp_post_data->mp_tipped_amount > 0) {
					echo "<script>mp_tippedAmount ='" . $mp_post_data->mp_tipped_amount . "';</script>";	
			}
		}	
	}
	$mp_post_data->newcounter = get_post_meta( $mypost_id, 'meta-newcounter', true );
	$mp_post_data->buys1 = get_post_meta( $mypost_id, 'meta_buys1', true ); 
	$mp_post_data->buys2 = get_post_meta( $mypost_id, 'meta_buys2', true ); 
	$mp_post_data->tips = get_post_meta( $mypost_id, 'meta_tips', true );
	$mp_post_data->first_buys1 = get_post_meta( $mypost_id, 'meta-first-buys1', true );
	$mp_post_data->second_buys1 = get_post_meta( $mypost_id, 'meta-second-buys1', true );	
	$mp_post_data->third_buys1 = get_post_meta( $mypost_id, 'meta-third-buys1', true );
	$mp_post_data->fourth_buys1 = get_post_meta( $mypost_id, 'meta-fourth-buys1', true );
	$mp_post_data->first_buys2 = get_post_meta( $mypost_id, 'meta-first-buys2', true );
	$mp_post_data->second_buys2 = get_post_meta( $mypost_id, 'meta-second-buys2', true );
	$mp_post_data->third_buys2 = get_post_meta( $mypost_id, 'meta-third-buys2', true );
	$mp_post_data->fourth_buys2 = get_post_meta( $mypost_id, 'meta-fourth-buys2', true );
	$mp_post_data->first_tips = get_post_meta( $mypost_id, 'meta-first-tips', true );
	$mp_post_data->second_tips = get_post_meta( $mypost_id, 'meta-second-tips', true ); 
	$mp_post_data->third_tips = get_post_meta( $mypost_id, 'meta-third-tips', true );
	$mp_post_data->fourth_tips = get_post_meta( $mypost_id, 'meta-fourth-tips', true );
	$mp_post_data->address = $mp_myrows[0]->address;
	$mp_post_data->currency = $mp_myrows[0]->currency;
	$mp_post_data->ref = $mp_myrows[0]->ref;
	$mp_post_data->noMetanet = $mp_myrows[0]->noMetanet;
	$mp_post_data->barColor = $mp_myrows[0]->barColor;
	if (isset($mp_myrows[0]->linkColor)) {	
		$mp_post_data->linkColor = $mp_myrows[0]->linkColor;
		if (strlen($mp_post_data->linkColor) > 3) {
			echo "<script>mp_linkColor ='" . $mp_post_data->linkColor . "';</script>";			
		}	
	}


	// To JavaScript ... 

	if (strlen($mp_post_data->newcounter) > 0) {
		echo "<script>mp_newCounter ='" . $mp_post_data->newcounter . "';</script>";	
			if (strlen($mp_post_data->buys1) > 0) {
				echo "<script>mp_buys1=" . $mp_post_data->buys1 . ";</script>";
			}	
			else {
				echo "<script>mp_buys1=0;</script>";		
			}
			if (strlen($mp_post_data->buys2) > 0) {
				echo "<script>mp_buys2=" . $mp_post_data->buys2 . ";</script>";
			}	
			else {
				echo "<script>mp_buys2=0;</script>";		
			}			
			if (strlen($mp_post_data->tips) > 0) {
				echo "<script>mp_tips=" . $mp_post_data->tips . ";</script>";
			}	
			else {
				echo "<script>mp_tips=0;</script>";		
			}
			if (strlen($mp_post_data->first_buys1) > 0) {
				echo "<script>mp_first_buys1='" . $mp_post_data->first_buys1 . "';</script>";
			}	
			else {
				echo "<script>mp_first_buys1=0;</script>";		
			}
			if (strlen($mp_post_data->second_buys1) > 0) {
				echo "<script>mp_second_buys1='" . $mp_post_data->second_buys1 . "';</script>";
			}	
			else {
				echo "<script>mp_second_buys1=0;</script>";		
			}
			if (strlen($mp_post_data->third_buys1) > 0) {
				echo "<script>mp_third_buys1='" . $mp_post_data->third_buys1 . "';</script>";
			}	
			else {
				echo "<script>mp_third_buys1=0;</script>";		
			}
			if (strlen($mp_post_data->fourth_buys1) > 0) {
				echo "<script>mp_fourth_buys1='" . $mp_post_data->fourth_buys1 . "';</script>";
			}	
			else {
				echo "<script>mp_fourth_buys1=0;</script>";		
			}
			if (strlen($mp_post_data->first_buys2) > 0) {
				echo "<script>mp_first_buys2='" . $mp_post_data->first_buys2 . "';</script>";
			}	
			else {
				echo "<script>mp_first_buys2=0;</script>";		
			}
			if (strlen($mp_post_data->second_buys2) > 0) {
				echo "<script>mp_second_buys2='" . $mp_post_data->second_buys2 . "';</script>";
			}	
			else {
				echo "<script>mp_second_buys2=0;</script>";		
			}
			if (strlen($mp_post_data->third_buys2) > 0) {
				echo "<script>mp_third_buys2='" . $mp_post_data->third_buys2 . "';</script>";
			}	
			else {
				echo "<script>mp_third_buys2=0;</script>";		
			}
			if (strlen($mp_post_data->fourth_buys2) > 0) {
				echo "<script>mp_fourth_buys2='" . $mp_post_data->fourth_buys2 . "';</script>";
			}	
			else {
				echo "<script>mp_fourth_buys2=0;</script>";		
			}
			if (strlen($mp_post_data->first_tips) > 0) {
				echo "<script>mp_first_tips='" . $mp_post_data->first_tips . "';</script>";
			}	
			else {
				echo "<script>mp_first_tips=0;</script>";		
			}
			if (strlen($mp_post_data->second_tips) > 0) {
				echo "<script>mp_second_tips='" . $mp_post_data->second_tips . "';</script>";
			}	
			else {
				echo "<script>mp_second_tips=0;</script>";		
			}
			if (strlen($mp_post_data->third_tips) > 0) {
				echo "<script>mp_third_tips='" . $mp_post_data->third_tips . "';</script>";
			}	
			else {
				echo "<script>mp_third_tips=0;</script>";		
			}
			if (strlen($mp_post_data->fourth_tips) > 0) {
				echo "<script>mp_fourth_tips='" . $mp_post_data->fourth_tips . "';</script>";
			}	
			else {
				echo "<script>mp_fourth_tips=0;</script>";		
			}				
		}
		else {
			echo "<script>mp_newCounter ='no';</script>";	
		}		
		echo "<script>mp_thankYou=\"" . esc_js($mp_post_data->thankyou) . "\";</script>";
		echo "<script>mp_theAddress='" . esc_js($mp_post_data->address) . "';</script>";
		echo "<script>mp_theCurrency='" . esc_js($mp_post_data->currency) . "';</script>";
		echo "<script>sharingQuota='" . esc_js($mp_post_data->share) . "';</script>";
		echo "<script>refQuota='" . esc_js($mp_post_data->ref) . "';</script>";
		echo "<script>nometanet='" . esc_js($mp_post_data->noMetanet) . "';</script>";
		echo "<script>mp_barColor='" . esc_js($mp_post_data->barColor) . "';</script>";
		echo "<script>mp_mypostid='" . esc_js($mypost_id) . "';</script>";
		echo "<script>mp_checkBox=\"" . esc_js($mp_post_data->checkbox) . "\";</script>";		
		echo "<script>dataLink=\"" . get_permalink() . "\";</script>";
		echo "<script>dataTitle=\"" . get_the_title() . "\";dataTitle = encodeURI(dataTitle); </script>";
				
		if(is_preview()){ 
			echo "<script>var mp_preview='yes';</script>";
		}
		else { 
			echo "<script>var mp_preview='no';</script>";
		};
		$dataContent = get_the_content();
		$dataContent = substr($dataContent, 0, 168);
		$dataContent = wp_strip_all_tags( $dataContent );
		echo "<script>dataContent=\"" . esc_js($dataContent) . "\";</script>";
		echo "<script>paymentAmount1=\"" . esc_js($mp_post_data->amount) . "\";</script>";
		echo "<script>paymentAmount2=\"" . esc_js($mp_post_data->amount) . "\";</script>";
		echo "<script>tipAmount=\"" . esc_js($mp_post_data->tip_amount)	 . "\";</script>";
		if (strlen($mp_post_data->paidcontent) > 0) {
			echo "<script>realContentLength=\"" . strlen($mp_post_data->paidcontent) . "\";</script>";	
		}
		
}




?>
