
function mediopayHideNextElements() {
  setTimeout(function() {
    let element1 = document.getElementById("mp_fade");
    let element2 = document.getElementById("mp_frame1");
    let element3 = document.getElementById("mp_tipFrame");
    if (element1 !== null) {
        element1.classList.add("mp_invisible");
    }
    if (element2 !== null) {
        element2.classList.add("mp_invisible");
    }
    if (element3 !== null) {
      console.log("it exists");
        element3.classList.add("mp_invisible");
    }
  },200);
}



function MedioPay_textColor(elementID) {
  console.log(elementID);
  setTimeout(function() {
    // Variables for red, green, blue values
      var r, g, b, hsp;
      color = mp_barColor;
      // Check the format of the color, HEX or RGB?
      if (color.match(/^rgb/)) {
          // If HEX --> store the red, green, blue values in separate variables
          color = mp_barColor.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
          r = color[1];
          g = color[2];
          b = color[3];
      }
      else {
          // If RGB --> Convert it to HEX: http://gist.github.com/983661
          color = +("0x" + color.slice(1).replace(
          color.length < 5 && /./g, '$&$&'));

          r = color >> 16;
          g = color >> 8 & 255;
          b = color & 255;
      }

      // HSP (Highly Sensitive Poo) equation from http://alienryderflex.com/hsp.html
      hsp = Math.sqrt(
      0.299 * (r * r) +
      0.587 * (g * g) +
      0.114 * (b * b)
      );

      // Using the HSP value, determine whether the color is light or dark
      if (hsp>127.5) {
          document.getElementById(elementID).style.color = "black";
          if (document.getElementById("mp_tipFrame") !== null) {
            document.getElementById("mp_tipFrame").style.color = "black";
          }

      }
      else {
          document.getElementById(elementID).style.color = "white";
          if (document.getElementById("mp_tipFrame") !== null) {
            document.getElementById("mp_tipFrame").style.color = "white";
          }
          if (document.getElementById("mp_pay1") !== null) {
            document.getElementById("mp_pay1").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
          }
          if (document.getElementById("mp_pay2") !== null) {
            document.getElementById("mp_pay2").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
          }
          if (document.getElementById("mp_tip") !== null) {
            document.getElementById("mp_tip").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
          }
      }
    }, 200);
  }



function MedioPay_changeColor() {
    var e = document.getElementById("select_color");
    var color = e.options[e.selectedIndex].value;
    document.getElementById("select_color").style.backgroundColor = "#" + color;
}

function mp_createObject(method) {
	console.log(method);
	console.log(mp_preview);
	mp_getcookie = document.cookie;
	console.log(mp_getcookie);
	let mp_current_url = window.location.href;
	console.log(mp_current_url);
	if (mp_current_url.includes("?ref")) {
		let mp_ref_position = mp_current_url.indexOf("?ref");	
		mp_current_url = mp_current_url.substring(0, mp_ref_position);
	}
	if (mp_getcookie.includes(mp_current_url)) {
		if (method == "mp_shortcode") {
			mp_method = "mp_shortcode";
			if (mp_getcookie.includes("2X")) {
				if (mp_getcookie.includes("X=")) {
					let mp_paymentid_start = mp_getcookie.indexOf("2X");
					let mp_paymentid_stop = mp_getcookie.indexOf("X=");
					mp_paymentid = mp_getcookie.substring((mp_paymentid_start + 2), mp_paymentid_stop);
					console.log(mp_paymentid);	
				}
			}
		}	
		else if (method == "editor") {
			mp_method = "second_editor";
			if (mp_getcookie.includes("1X")) {
				console.log("includes 1x");
				if (mp_getcookie.includes("X=")) {
					console.log("includes X=");
					let mp_paymentid_start = mp_getcookie.indexOf("1X");
					let mp_paymentid_stop = mp_getcookie.indexOf("X=");
					mp_paymentid = mp_getcookie.substring((mp_paymentid_start + 2), mp_paymentid_stop);
					console.log(mp_paymentid);	
				}
			}		
		}
		else {
			mp_method=0;		
		}
		console.log(mp_method);
		jQuery(document).ready(function($) {
		    var data = {
			     'action': 'mp_process_cookies',
			      'MedioPay_postid': mp_mypostid,
            'mp_cookies': mp_getcookie,
            'mp_position': mp_method
		     };
		console.log("turning data over");
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		  jQuery.post(my_ajax_object.ajax_url, data, function(response) {
		  	console.log(response);
		  	console.log(mp_method);
			  if (mp_method == "mp_shortcode") {
			  		if (response.length > 10) {
						 mp_unlockContent2(0, response);			  
				   }
			  }
			  else if (mp_method == "second_editor") {
			  	console.log("unlock them");
				  	if (response.length > 10) {
				  		mp_unlockContent1(0, response);	
				  	}
			  }
		   });
	   });
	}

	
	console.log(method);
  k = 0;
  p = 0;
  if (method == "editor") {
    secondEdit = "yes";
  }
	dataDomain = window.location.hostname;
	dataURL = window.location.pathname;
  paymentObjects = [];
  if (mp_checkBox == "yes") {
    	 if (nometanet == "yes" || mp_preview == "yes") {
		var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100201']).toASM();
	 }
	 else {
			var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100201', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();	 
	 }
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
			 to: mp_theAddress,
			 returndata: returndata,
			 outputs: 1,
			 currency: mp_theCurrency
    }
    if (typeof mp_refID !== "undefined") {
			 tip.refID = mp_refID;
			 tip.outputs = 2;
    }
    paymentObjects.push(tip);
	 }
   if (typeof realContentLength !== "undefined" && realContentLength > 5) {
  		  if (nometanet == "yes" || mp_preview == "yes") {
				var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100101']).toASM();	
	 		}
	 		else {
				 var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100101', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();	
	 		}
		 paywall = {
			       paywall: "yes",
			        tips: "no",
			        typenumber: "100101",
			        title: dataTitle,
			        amount: paymentAmount1,
			        baseurl: dataDomain,
			        path: dataURL,
			        sharing: sharingQuota,
              ref: refQuota,
              nometanet: nometanet,
			        returndata: returndata,
			        to: mp_theAddress,
			        outputs: 1,
			        currency: mp_theCurrency,
              mypostid: mp_mypostid,
		 }
		  if (typeof mp_refID !== "undefined") {
			        paywall.refID = mp_refID;
			        paywall.outputs = 2;
    	}
		 paymentObjects.push(paywall);
  }
	if (method == "mp_shortcode") {
  	 if (nometanet == "yes" || mp_preview == "yes") {
		var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100102']).toASM();
	 }
	 else {
		var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100102', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();	 
	 }
    paywall2 = {
          paywall2: "yes",
          tips: "no",
          typenumber: "100102",
          title: dataTitle,
          amount: paymentAmount2,
          baseurl: dataDomain,
          path: dataURL,
          sharing: sharingQuota,
          ref: refQuota,
          nometanet: nometanet,
          returndata: returndata,
          to: mp_theAddress,
          outputs: 1,
          currency: mp_theCurrency,
          mypostid: mp_mypostid,
    }
    if (typeof mp_refID !== "undefined") {
          paywall2.refID = mp_refID;
          paywall2.outputs = 2;
     }
     paymentObjects.push(paywall2);
  }
	mp_querryPlanaria(paymentObjects);
	//mp_getAddress(paymentObjects);

}
