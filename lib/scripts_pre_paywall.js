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
        element3.classList.add("mp_invisible");
    }
  },200);
}



function MedioPay_textColor(elementID) {
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
      	console.log("change color");
          document.getElementById(elementID).style.color = "white";
          if (document.getElementById("mp_tipFrame") !== null) {
            document.getElementById("mp_tipFrame").style.color = "white";
          }
          else {
          console.log("no tipframe")
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


	
function mp_checkCookie(method, method2) {	
	console.log("1. " + method);
	console.log("2. " + method2);
	mp_getcookie = document.cookie;
	console.log(mp_getcookie);
	let mp_current_url = window.location.href;
	if (mp_getcookie.includes(mp_current_url)) {		
		if (method == "mp_shortcode" || (typeof realContentLength !== "undefined" && realContentLength > 5 ) || method == "editor") {
			console.log("shortcode or editor - we have a paywall");
			if (method == "mp_shortcode") {
				cookieHandler = "2X";
				console.log("precicely: with shortcode");
			}
			else {
				cookieHandler = "1X";
				console.log("precicely: with editor");					
				method = "editor";
				console.log(method);		
			}			
			cookieHandler2 = "X=";		
			/*
			if (mp_getcookie.includes("2X"))  {
				console.log("includes 2x");			
			}*/
			if ( ( mp_getcookie.includes("2X") && method == "mp_shortcode" && mp_getcookie.includes("X=") ) || ( mp_getcookie.includes("1X") && method !== "shortcode" && mp_getcookie.includes("X=") ) ) {
					console.log("cookie matches one of the correct formats");					
					mp_cookie2_exists = "yes";
					let mp_paymentid_start = mp_getcookie.indexOf(cookieHandler);
					let mp_paymentid_stop = mp_getcookie.indexOf(cookieHandler2);
					mp_paymentid = mp_getcookie.substring((mp_paymentid_start + 2), mp_paymentid_stop);
					let mp_secret_start = mp_paymentid_stop + 2;
					let mp_secret_stop = mp_secret_start + 6;
					mp_secret = mp_getcookie.substring(mp_secret_start, mp_secret_stop);
					console.log(method2);
					if (mp_secret.length == 6) {
						console.log("secret found with 6 digits " + method);
						 let mp_data = {
							 		'MedioPay_postid': mp_mypostid,
            					'mp_cookies': mp_getcookie,
            					'mp_position': method	
	 						}
	 						console.log(mp_data);
	 						console.log(mp_blogpath + '/wp-json/mediopay/v1/processcookies/');
	 						fetch(mp_blogpath + '/wp-json/mediopay/v1/processcookies/', {
       						method: 'POST', // or 'PUT'
       						headers: {
           						'Content-Type': 'application/json',
       						},
       						body: JSON.stringify(mp_data),
    						})
    						.then((response) => {
        						return response.json();
    						})
    						.then((myJson) => {
       						console.log(myJson);
       						myJson = JSON.parse(myJson);
       						console.log(myJson);
								if (mp_checkBox == "yes" || method2 == "mp_tipme") {	
					  					console.log(method2);
					  					if (method2 == "mp_tipme") {
											mp_createObject("mp_tipme");								  					
					  					}	
					  					else {
											mp_createObject('nothing');
										}
									}	
				  					if (myJson.paidcontent.length > 10) {
				  						//console.log(response);
				  						 mp_unlockContent(method, 0, 0, myJson["paidcontent"], method2);
										 //mp_unlockContent2(0, response);
									}
									else {
										//console.log(response);
										mp_cookie_failed_2 = "yes";										
										mp_createObject(method, method2);
									}								         						
    						})
    						.catch((error) => {
      						console.error('There has been a problem with your fetch operation:', error);
    						})	 
					}
					else {
						console.log("secret has wrong length");	
						mp_createObject(method, method2);					
					}
			}
			else {
				console.log("cookie has no secret / format doesn't fit");
				console.log(method + " " + method2);
				mp_createObject(method, method2);
			}
		}	
		else {
			console.log("no paywall detected");
			mp_createObject(method, method2);
		}				
	}
	else {
		console.log("no cookie found which includes this url");
		mp_createObject(method, method2);
	}		
}

function mp_createObject(method, method2) {
	 //console.log("method ");
	//console.log("START " + method);
	console.log(method);
	let mp_current_url = window.location.href;
	//console.log(mp_current_url);
	if (mp_current_url.includes("?ref")) {
		let mp_ref_position = mp_current_url.indexOf("?ref");	
		mp_current_url = mp_current_url.substring(0, mp_ref_position);
	}
	
  k = 0;
  p = 0;
 
  if (method == "editor") {
    secondEdit = "yes";
  }
	dataDomain = window.location.hostname;
	dataURL = window.location.pathname;
  paymentObjects = [];
  if (method == "mp_shortcode") {
  	 if (nometanet == "yes" || mp_preview == "yes") {
		var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100102']).toASM();
	 }
	 else {
		var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100102', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();	 
	 }
	 if (paymentAmount2 == "" || paymentAmount2 == 0) {
		paymentAmount2 == mp_fixedAmount;	 
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
     if (mp_newCounter == "yes") {
			paywall2.newCounter = "yes"; 
			paywall2.mp_buys2 = mp_buys2;
			if (typeof mp_first_buys2 !== "undefined") {
				paywall2.firstPartner = mp_first_buys2;			
			}
			if (typeof mp_second_buys2 !== "undefined") {
				paywall2.secondPartner = mp_second_buys2;			
			}
			if (typeof mp_third_buys2 !== "undefined") {
				paywall2.thirdPartner = mp_third_buys2;			
			}
			if (typeof mp_fourth_buys2 !== "undefined") {
				paywall2.fourthPartner = mp_fourth_buys2;			
			}    
    }
    else {
		 paywall2.newCounter = "no";   
    }
    if (mp_preview == "yes") {
		paywall2.preview = "yes";    
    }
   else {
		 paywall2.preview = "no";    
   }
   if (typeof mp_cookie2_exists !== "undefined" && typeof mp_cookie_failed_2 == "undefined") {
		paywall2.cookie2 = "yes";			
	}
	if (typeof mp_address2 !== "undefined" && mp_address2.length > 0) {
			if (mp_address2 !== "none") {
				paywall2.address2 = mp_address2;
				paywall2.address2share = mp_secondAddressShare;
			}		
		}	
   paymentObjects.push(paywall2);
  }
  if (method == "mp_tipme" || method2 == "mp_tipme") {
  	console.log("tip me");
  			 if (nometanet == "yes" || mp_preview == "yes") {
		var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100201']).toASM();
	 }
	 else {
			var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100201', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();	 
	 }
    paymentLabel = "tip";
    tip2 = {
			 tip2: "yes",
			 paywall: "no",
			 typenumber: "100202",
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
			 currency: mp_theCurrency,
			 mypostid: mp_mypostid,
    }
    if (typeof mp_refID !== "undefined") {
			 tip2.refID = mp_refID;
			 tip2.outputs = 2;
    }
    if (mp_newCounter == "yes") {
		tip2.newCounter = "yes";
		tip2.mp_tips = mp_tips;
			if (typeof mp_first_tips !== "undefined") {
				tip2.firstPartner = mp_first_tips;			
			}
			if (typeof mp_second_tips !== "undefined") {
				tip2.secondPartner = mp_second_tips;			
			}
			if (typeof mp_third_tips !== "undefined") {
				tip2.thirdPartner = mp_third_tips;			
			}
			if (typeof mp_fourth_tips !== "undefined") {
				tip2.fourthPartner = mp_fourth_tips;			
			}
  		}
		if (typeof mp_address2 !== "undefined" && mp_address2.length > 0) {
			if (mp_address2 !== "none") {
				tip2.address2 = mp_address2;
				tip2.address2share = mp_secondAddressShare;
			}		
		}	  		
  		
  		paymentObjects.push(tip2);
 
  }
    if (method == "editableTips") {
  		tipAmount = document.getElementById("mp_editable_1").value;
  		console.log("editable tips");
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
			 typenumber: "100202",
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
			 currency: mp_theCurrency,
			 mypostid: mp_mypostid,
			 editable: "yes",
    }
    if (typeof mp_refID !== "undefined") {
			 tip.refID = mp_refID;
			 tip.outputs = 2;
    }
    if (mp_newCounter == "yes") {
		tip.newCounter = "yes";
		tip.mp_tips = mp_tips;
			if (typeof mp_first_tips !== "undefined") {
				tip.firstPartner = mp_first_tips;			
			}
			if (typeof mp_second_tips !== "undefined") {
				tip.secondPartner = mp_second_tips;			
			}
			if (typeof mp_third_tips !== "undefined") {
				tip.thirdPartner = mp_third_tips;			
			}
			if (typeof mp_fourth_tips !== "undefined") {
				tip.fourthPartner = mp_fourth_tips;			
			}
		}	
		if (typeof mp_address2 !== "undefined" && mp_address2.length > 0) {
			if (mp_address2 !== "none") {
				tip.address2 = mp_address2;
				tip.address2share = mp_secondAddressShare;
			}		
		}	
 
  		paymentObjects.push(tip);
  
  }
  if (method == "editableTips2") {
  		tipAmount = document.getElementById("mp_editable").value;
  		console.log("editable tips");
  					 if (nometanet == "yes" || mp_preview == "yes") {
		var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100201']).toASM();
	 }
	 else {
			var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100201', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();	 
	 }
    paymentLabel = "tip";
    tip2 = {
			 tip2: "yes",
			 paywall: "no",
			 typenumber: "100202",
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
			 currency: mp_theCurrency,
			 mypostid: mp_mypostid,
			 editable: "yes",
    }
    if (typeof mp_refID !== "undefined") {
			 tip2.refID = mp_refID;
			 tip2.outputs = 2;
    }
    if (mp_newCounter == "yes") {
		tip2.newCounter = "yes";
		tip2.mp_tips = mp_tips;
			if (typeof mp_first_tips !== "undefined") {
				tip2.firstPartner = mp_first_tips;			
			}
			if (typeof mp_second_tips !== "undefined") {
				tip2.secondPartner = mp_second_tips;			
			}
			if (typeof mp_third_tips !== "undefined") {
				tip2.thirdPartner = mp_third_tips;			
			}
			if (typeof mp_fourth_tips !== "undefined") {
				tip2.fourthPartner = mp_fourth_tips;			
			}
			if (typeof mp_address2 !== "undefined") {
		if (mp_address2 !== "none" && mp_address2.length > 0) {
			tip2.address2 = mp_address2;
			tip2.address2share = mp_secondAddressShare;
		}		
	}	
  		}
  		paymentObjects.push(tip2);
  
  }
  if ((typeof realContentLength !== "undefined" && realContentLength > 5 && method !== "editableTips2" && method !== "nothing") ) {
  	console.log(paymentAmount1);
  	if (paymentAmount1 == "" || paymentAmount1 == 0) {
		paymentAmount1 == mp_fixedAmount;	 
	 }
   	console.log("want paywall " + method);
  		  if (nometanet == "yes" || mp_preview == "yes") {
				var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100101']).toASM();	
	 		}
	 		else {
				 var returndata = bsv.Script.buildSafeDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100101', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();	
	 		}
	 		console.log(mp_newCounter);
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
    	if (mp_newCounter == "yes") {
    		console.log(mp_buys1);
    		console.log(mp_buys1 + 1);
			paywall.newCounter = "yes";  
			paywall.mp_buys1 = mp_buys1;
			if (typeof mp_first_buys1 !== "undefined") {
				paywall.firstPartner = mp_first_buys1;			
			}
			if (typeof mp_second_buys1 !== "undefined") {
				paywall.secondPartner = mp_second_buys1;			
			}
			if (typeof mp_third_buys1 !== "undefined") {
				paywall.thirdPartner = mp_third_buys1;			
			}
			if (typeof mp_fourth_buys1 !== "undefined") {
				paywall.fourthPartner = mp_fourth_buys1;			
			}
   	}	  	
    else {
		 paywall.newCounter = "no";   
    }
     if (mp_preview == "yes") {
		paywall.preview = "yes";    
    }
    else {
		 paywall.preview = "no";    
    }
    if (typeof mp_cookie_exists !== "undefined" && typeof mp_cookie_failed_1 == "undefined") {
		paywall.cookie = "yes";			
	}
	
	if (typeof mp_address2 !== "undefined" && mp_address2.length > 0) {
		if (mp_address2 !== "none") {
			paywall.address2 = mp_address2;
			paywall.address2share = mp_secondAddressShare;
		}		
	}	
	paymentObjects.push(paywall);
  }
  if (mp_checkBox == "yes") {
  	console.log(method + " " + method2)
  	console.log("checkboxtips");
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
			 currency: mp_theCurrency,
			 mypostid: mp_mypostid,
    }
    if (typeof mp_refID !== "undefined") {
			 tip.refID = mp_refID;
			 tip.outputs = 2;
    }
    if (mp_newCounter == "yes") {
		tip.newCounter = "yes";
		tip.mp_tips = mp_tips;
			if (typeof mp_first_tips !== "undefined") {
				tip.firstPartner = mp_first_tips;			
			}
			if (typeof mp_second_tips !== "undefined") {
				tip.secondPartner = mp_second_tips;			
			}
			if (typeof mp_third_tips !== "undefined") {
				tip.thirdPartner = mp_third_tips;			
			}
			if (typeof mp_fourth_tips !== "undefined") {
				tip.fourthPartner = mp_fourth_tips;			
			}
    }
    else {
		 tip.newCounter = "no";   
    }
     if (mp_preview == "yes") {
		tip.preview = "yes";    
    }
    else {
		 tip.preview = "no";    
    }
	if (typeof mp_address2 !== "undefined" && mp_address2.length > 0) {
			if (mp_address2 !== "none") {
				tip.address2 = mp_address2;
				tip.address2share = mp_secondAddressShare;
		}		
	}	
    paymentObjects.push(tip);
	}
	else {
		console.log("no checkbox");
		console.log(mp_checkBox);	
	}
	console.log(paymentObjects);
	mp_prepareObject(paymentObjects);
	//mp_getAddress(paymentObjects);

}
