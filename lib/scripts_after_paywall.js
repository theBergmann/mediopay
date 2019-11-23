 function mp_handleSuccessfulPayment1(payment, object) {
	mp_outputs = []	
	for (let i=0; i<payment.paymentOutputs.length; i++) {
		mp_outputs.push(payment.paymentOutputs[i].to)
	}   
    mp_destination_address = payment.paymentOutputs[1].to;
    mp_mypostid = object.mypostid;
    jQuery(document).ready(function($) {
		    var data = {
			     'action': 'mp_throwcontent',
			      'MedioPay_postid': mp_mypostid,
            'MedioPay_outputs': mp_outputs
		     };
		console.log("turning data over");
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		  jQuery.post(my_ajax_object.ajax_url, data, function(response) {
			  mp_unlockContent1(payment, response);
		   });
	   });
}
function mp_handleFailedPayment1(error) {
        alert("Sorry, the payment did not process correctly.")
}
function mp_unlockContent1(payment, response) {
	console.log(payment);
	console.log(response);
	console.log(payment.userId);
	console.log("unlock it 1");
  mp_element_fade = document.getElementById("mp_fade") ;
  mp_element_fade.parentNode.removeChild(mp_element_fade);
  mp_element = document.getElementById("mp_tipFrame");
  if (typeof mp_element !== "undefined") {
      if (mp_element !== null) {
      console.log("make it visible again - 1");
      mp_element.classList.remove("mp_invisible");
      document.getElementById("mp_tip").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
    }
  }
  let mp_secretcheck = response.substring(0, 6);
  console.log(mp_secretcheck);
  if (mp_secretcheck == "secret") {
	let mp_secret1 = response.substring(6, 12);
  	let mp_current_url = window.location.href;
	if (mp_current_url.includes("?ref")) {
		let mp_ref_position = mp_current_url.indexOf("?ref");	
		mp_current_url = mp_current_url.substring(0, mp_ref_position);
		console.log(mp_current_url);	
	}
	let mp_date = new Date();
	let mp_exdays = 800;
	mp_date.setTime(mp_date.getTime() + (mp_exdays*24*60*60*1000));
  	var mp_expires = "expires="+ mp_date.toUTCString();
	document.cookie = mp_current_url + "1X" + payment.userId + "X=" + mp_secret1 + ";" + mp_expires;
	}
	else {
		console.log("no secret");	
	}
	
	if (payment == 0) {
		mp_paid_text1 = response; 
 	}
 	else {
  		mp_paid_text1 = response.substring(12, response.length);
  		mp_paymentid = payment.userId;
  	}
        document.getElementById("mp_unlockable1").innerHTML = mp_paid_text1; 
        console.log("printed response");
       document.getElementById("mp_unlockable1").classList.toggle("mp_unlocked");
    document.getElementById("mp_frame1").classList.toggle("mp_paid");
        document.getElementById("mp_frame1").innerHTML="<em>Share <a href='" + dataLink + "?ref=" + mp_paymentid + "'>this link</a> to get a share of later payments.</em><br />See the <a href='https://www.mediopay.com/value-list/'>Ranking of the most valuable posts</a>";

}

function mp_handleSuccessfulPayment2(payment, object) {
  console.log("handle successfull payment 2");
  mp_destination_address = payment.paymentOutputs[1].to;
  mp_mypostid = object.mypostid;
	mp_outputs = []	
	for (let i=0; i<payment.paymentOutputs.length; i++) {
		mp_outputs.push(payment.paymentOutputs[i].to)
	}   
  jQuery(document).ready(function($) {
      var data = {
         'action': 'mp_throwcontent_2',
          'MedioPay_postid': mp_mypostid,
          'MedioPay_outputs': mp_outputs
       };
   console.log("ask api");
  jQuery.post(my_ajax_object.ajax_url, data, function(response) {
      mp_unlockContent2(payment, response);
       });
   });
}

function mp_unlockContent2(payment, response) {
  console.log("unlock it 2");	
console.log(payment.userId);
  mp_element_fade = document.getElementById("mp_fade2");
  if (mp_element_fade !== null) {
	  	mp_element_fade.parentNode.removeChild(mp_element_fade);
  	  	mp_element_after = document.getElementById("mp_fade");
  		mp_element_after2 = document.getElementById("mp_frame1");
  		mp_element_after3 = document.getElementById("mp_tipFrame");
  	}
  //document.getElementById("mp_unlockable2_content").innerHTML = response;
  let mp_secretcheck = response.substring(0, 6);
  console.log(mp_secretcheck);
  if (mp_secretcheck == "secret") {
  	console.log("it's a secret")
	let mp_secret2 = response.substring(6, 12);
	console.log(mp_secret2);  
  	let mp_current_url = window.location.href;
	if (mp_current_url.includes("?ref")) {
		let mp_ref_position = mp_current_url.indexOf("?ref");	
		mp_current_url = mp_current_url.substring(0, mp_ref_position);
		console.log(mp_current_url);	
	}
	let mp_date = new Date();
	let mp_exdays = 800;
	mp_date.setTime(mp_date.getTime() + (mp_exdays*24*60*60*1000));
  	var mp_expires = "expires="+ mp_date.toUTCString();
  	console.log("set cookie");
  	console.log(mp_current_url + "2X" + payment.userId + "X=" + mp_secret2 + ";" + mp_expires);
	document.cookie = mp_current_url + "2X" + payment.userId + "X=" + mp_secret2 + ";" + mp_expires;
	}
  else {
		console.log("no secret");  
  }
  if (payment == 0) {
		mp_paid_text2 = response;  
  }
  else {
  		mp_paid_text2 = response.substring(12, response.length);
  		mp_paymentid = payment.userId;
  }
  document.getElementById("mp_unlockable2_content").innerHTML = mp_paid_text2;
  document.getElementById("mp_unlockable2").classList.toggle("mp_unlocked");
  document.getElementById("mp_frame2").classList.toggle("mp_paid");
  document.getElementById("mp_frame2").innerHTML="<em>Share <a href='" + dataLink + "?ref=" + mp_paymentid + "'>this link</a> to get a share of later payments.</em><br />See the <a href='https://www.mediopay.com/value-list/'>Ranking of the most valuable posts</a>";
  if (mp_element_after !== null) {  	
      mp_element_after.classList.remove("mp_invisible");
      mp_element_after2.classList.remove("mp_invisible");
		
		mp_getcookie = document.cookie;
		let mp_current_url = window.location.href;	
		if (mp_current_url.includes("?ref")) {
			let mp_ref_position = mp_current_url.indexOf("?ref");	
			mp_current_url = mp_current_url.substring(0, mp_ref_position);
		}
		if (mp_getcookie.includes(mp_current_url)) {
		mp_method = "second_editor";		
			jQuery(document).ready(function($) {
		    var data = {
			     'action': 'mp_process_cookies',
			      'MedioPay_postid': mp_mypostid,
            'mp_cookies': mp_getcookie,
            'mp_position': mp_method
		     };
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		  jQuery.post(my_ajax_object.ajax_url, data, function(response) {
		    		if (response.length > 10) {
						 mp_unlockContent1(0, response);			  
				   }
			  	})
		   })
		}
	 	if (mp_element_after3 !== null) {
   	 if (mp_element_after == null) {
      	mp_element_after3.classList.remove("mp_invisible");
    		}	
  		}

	}
}


function mp_handleSuccessfulTip(payment) {
  document.getElementById("mp_tipFrame").innerHTML = "<h2>" + mp_thankYou + "</h2><em>Share <a href='" + dataLink + "?ref=" + payment.userId + "'>this link</a> to get a share of later payments.</em><br />See the <a href='https://www.mediopay.com/value-list/'>Ranking of the most valuable posts</a>";
}




function mp_getInfo(mp_field) {
mp_verb = "read the rest of the article";
if (mp_field == "mp_tip") {
  mp_verb = "tip the author";
}
document.getElementById(mp_field).innerHTML = "<br /><br />You can " + mp_verb + " with Bitcoin SV (BSV) and MoneyButton. <a href='http://mediopay.com/bsv-how'>Learn how and get your starting BSV</a>. We promise you will pass the paywall very quickly.<br /></div>";
document.getElementById(mp_field).setAttribute( "onClick", "javascript: mp_noInfo('" + mp_field + "')");
}
function mp_noInfo(mp_field) {
document.getElementById(mp_field).innerHTML = "<b>(?)</b>";
document.getElementById(mp_field).setAttribute( "onClick", "javascript: mp_getInfo('" + mp_field + "')");
}


function mp_create_cookie(mp_hash) {
	console.log(mp_hash);
	let mp_current_url = window.location.href;
	if (mp_current_url.includes("?ref")) {
		let mp_ref_position = mp_current_url.indexOf("?ref");	
		mp_current_url = mp_current_url.substring(0, mp_ref_position);
		console.log(mp_current_url);	
	}
	let mp_date = new Date();
	let mp_exdays = 800;
	mp_date.setTime(mp_date.getTime() + (mp_exdays*24*60*60*1000));
  	var mp_expires = "expires="+ mp_date.toUTCString();
	document.cookie = mp_current_url + "=" + mp_hash + ";" + mp_expires;
}


