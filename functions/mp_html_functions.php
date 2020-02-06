<?php

function behindthewall($paywallnumber, $mp_lengthContent, $mp_post_data, $content) {
	if ($mp_lengthContent > 0) {
		if ($paywallnumber == "eins") {
			$stringlength = strlen($mp_post_data->paidcontent);	
			$spanid = "mp_pay1";	
		}
		else if ($paywallnumber == "zwei") {
			$stringlength = strlen($content);	
			$spanid = "mp_pay2";
		
		}
		$path = plugin_dir_url( 'mediopay.php');
		$path = $path . "mediopay/lib/";
		echo "<script>MedioPayPath=\"" . $path . "\";</script>";
		return "<span class='paywallheader'>" . $mp_post_data->paywallmsg . "</span><br />" . $stringlength . "</b> characters for " . esc_html($mp_post_data->amount) . " " . esc_html($mp_post_data->currency) .
		"<br /><span class='paywallbody'>No registration. No subscription. Just one swipe.</span>";
 	}
	else {
		return "";
	}
}







?>
