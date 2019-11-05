// if the user added a paymail address, query polynym to get the BSV address

function mp_getAddress(object) {
	mp_payload = object;
	if (mp_payload[0]["to"].includes("@")) {
				fetch("https://api.polynym.io/getAddress/" + theAddress).then(function(r) {
  					return r.json()
  				}).then(function(r) {
					for (let i=0; i<payload.length; i++) {
  						mp_payload[i]["to"] = r.address;
  					}
  					if (k == 1 && p == 0) {
  						for (let i=0;i<mp_payload.length;i++) {
							loadButton(mp_payload[i]);
							p = 1;
    					}
    				}
	  				else {
						k = 1;
	  				}
  				})
  			}
  			else {
				if (k == 1 && p == 0) {
	  				for (let i=0;i<mp_payload.length;i++) {
						mp_loadButton(mp_payload[i]);
						p = 1;
    				}
	  			}
	  			else {
						k = 1;
	  			}
  			}
}

// ask Planaria to get information about the first transactions to get the address for income sharing and the number of transactions

function mp_querryPlanaria(object) {
	let tipbuttr = document.getElementById('tbutton');
	let mp_payload = object;
	if (mp_payload[0].nometanet == "yes") {
		setTimeout(function() {
			for (let h=0; h<mp_payload.length; h++) {
			mp_payload[h].sharing = 0.0;
			mp_payload[h].number = 0;
			mp_payload[h].buys = 0;
			mp_payload[h].tips = 0;
		}
		if (k == 1 && p == 0) {
	  		for (let i=0;i<mp_payload.length;i++) {
				mp_loadButton(mp_payload[i]);
				p = 1;
    		}
	  	}
	  	else {
			k = 1;
	  	}
	}, 2000);
	}
	else {
	 var query = {
     		 "v": 3,
      	 "q": {
        		"find": {
         	"out.b0": { "op": 106 },
         	"out.s1": "1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU",
         	"out.s3": mp_payload[0]["title"]
         },
        "limit": 120,
        "sort": { "blk.i": 1 }
       }
   }
	var b64 = btoa(JSON.stringify(query));
	var url = "https://genesis.bitdb.network/q/1FnauZ9aUH2Bex6JzdcV4eNX7oLSSEbxtN/" + b64;
	var header = {
  		headers: { key: "1CN88CMwB8wAVeoX2zm9CCZE4ZrrHDjZL5" }
	};
	fetch(url, header).then(function(r) {
  		return r.json()
	}).then(function(r) {
    	mp_results = r.c.concat(r.u);
    	if (typeof mp_results[0] !== "undefined") {
    	if (typeof mp_results[0].out[0].s7 !== "undefined") {
			for (let i=0;i<mp_payload.length;i++) {
				mp_payload[i]["sharing"] = mp_results[0].out[0].s7;
				mp_payload[i].number = mp_results.length;
    		}
    	}
    	}
		if (typeof mp_results !== "undefined" && mp_results.length > 0) {
			mp_tips = 0;
			mp_buys = 0;
			mp_buys2 = 0;
			for (let j=0; j<mp_results.length; j++) {
				if (mp_results[j].out[0].s2 == "100201") {
					mp_tips = mp_tips + 1;
				}
				if (mp_results[j].out[0].s2 == "100101") {
					mp_buys = mp_buys + 1;
				}
				if (mp_results[j].out[0].s2 == "100102") {
					mp_buys2 = mp_buys2 + 1;
				}
			}

			if (mp_payload[0].sharing > 0) {
				for (n=0; n<mp_payload.length; n++) {
					if (typeof mp_results[0].out[2] !== "undefined") {
						mp_payload[n]["firstPartner"] = mp_results[0].out[2].e.a;
					}
					else {
						mp_payload[n]["firstPartner"] = mp_results[0].in[0].e.a;
					}
					mp_payload[n]["outputs"] = mp_payload[n]["outputs"] + 1;
				}
			}
 			if (mp_payload[0].sharing > 0.1 && mp_results.length > 1 ) {
			 	for (n=0; n<mp_payload.length; n++) {
			 		if (typeof mp_results[1].out[3] !== "undefined") {
						mp_payload[n]["secondPartner"] = mp_results[1].out[3].e.a;
					}
					else {
						mp_payload[n]["secondPartner"] = mp_results[1].in[0].e.a;
					}
					mp_payload[n]["outputs"] = mp_payload[n]["outputs"] + 1;
				}
 			}
 			if (mp_payload[0].sharing > 0.2 && mp_results.length > 2 ) {
			 	for (n=0; n<mp_payload.length; n++) {
			 		if (typeof mp_results[2].out[4] !== "undefined") {
						mp_payload[n]["thirdPartner"] = mp_results[2].out[4].e.a;
					}
					else {
						mp_payload[n]["thirdPartner"] = mp_results[2].in[0].e.a;
					}
					mp_payload[n]["outputs"] = mp_payload[n]["outputs"] + 1;
				}
 			}
 			if (mp_payload[0].sharing > 0.3 && mp_results.length > 3 ) {
			 	for (n=0; n<mp_payload.length; n++) {
			 		if (typeof mp_results[3].out[5] !== "undefined") {
						mp_payload[n]["fourthPartner"] = mp_results[3].out[5].e.a;
					}
					else {
						mp_payload[n]["firstPartner"] = mp_results[3].in[0].e.a;
					}
					mp_payload[n]["outputs"] = mp_payload[n]["outputs"] + 1;
				}
 			}
    	}
    	else {
			 for (let n=0; n<mp_payload.length; n++) {
					mp_payload[n]["number"] = 0;
    		}
    	}
    	if (k == 1 && p == 0) {
	  		for (let i=0;i<mp_payload.length;i++) {
						mp_loadButton(mp_payload[i]);
				p = 1;
    		}
	  	}
	  	else {
			k = 1;
	  	}
	});
	}
}


function mp_loadButton(word) {
	let object = word;
	console.log(word);
	let sharingWord = "";
	let sharingStars = "<span style='color:#FB9868; font-size:15pt'>!</span>";
	let sharingAbbrPay = "<abbr title='As one of the first buyers, you will receive a share when people buy this article after you'>";
	//sharingAbbrTip = "<abbr title='As one of the first tipers, you will receive a share when people tip this article after you'>";
	if (object.paywall == "yes" || object.paywall2 == "yes") {
		wording = "buy";
	}
	if (object.tip == "yes") {
		wording = "tip";
	}
	numbercountformat1 = "<span id='mp_box' onclick='mp_expandInfo(\"box\", " + object.amount + ", \"" + object.currency + "\")' onmouseover='mp_expandInfo(\"box\", " + object.amount + ", \"" + object.currency + "\")' style='margin-top:-50px' >"
	numbercountformat2 = "<span id='mp_box3' onclick='mp_expandInfo(\"box3\", " + object.amount + ", \"" + object.currency + "\")' onmouseover='mp_expandInfo(\"box3\", " + object.amount + ", \"" + object.currency + "\")'>"
	numbercountformat3 = "<span id='mp_box4' onclick='mp_expandInfo(\"box4\", " + object.amount + ", \"" + object.currency + "\")' onmouseover='mp_expandInfo(\"box4\", " + object.amount + ", \"" + object.currency + "\")'>"


	numbercountTips = numbercountformat2 + "<span class='icon'>&#10084;</span> " + object.number;
	if ((object.sharing * 10) > object.number) {
		switch (object.number) {
			case(0):
				shareQuote = object.sharing*100;
				break;
			case(1):
				shareQuote = object.sharing * 50;
				break;
			case(2):
				shareQuote = object.sharing * 30;
				break;
			case(3):
				shareQuote = object.sharing * 20;
				break;
		}

		sharingWord1 = "&nbsp;&nbsp;&nbsp;&nbsp;<span id='mp_box2' onclick='mp_expandInfo2(\"mp_box2\")' onmouseover='mp_expandInfo2(\"mp_box2\")'><span class='icon'>&cent;</span> " + shareQuote + "%";
		sharingWord2 = "&nbsp;&nbsp;&nbsp;&nbsp;<span id='mp_box5' onclick='mp_expandInfo2(\"mp_box5\")' onmouseover='mp_expandInfo2(\"mp_box5\")'><span class='icon'>&cent;</span> " + shareQuote + "%";
		sharingWord3 = "&nbsp;&nbsp;&nbsp;&nbsp;<span id='mp_box6' onclick='mp_expandInfo2(\"mp_box6\")'  onmouseover='mp_expandInfo2(\"mp_box6\")'><span class='icon'>&cent;</span> " + shareQuote + "%";
	}
	else {
		sharingWord1 = "";
		sharingWord2 = "";
		sharingWord3 = "";
	}
	if (object.paywall == "yes") {
		mp_skript = "mp_handleSuccessfulPayment1(payment)";
		paymentLabel = "Buy";
		mp_element = "mbutton1";
		if (typeof mp_buys !== "undefined") {
			if (object.nometanet !== "yes" && mp_buys !== 0) {
				document.getElementById("mp_counter1").innerHTML = numbercountformat1 + "<span class='icon'>&#x1F48E;</span>" + mp_buys + "</span>" + sharingWord1 + "</span>";
		   }
		   else {
		   	document.getElementById("mp_counter1").innerHTML = numbercountformat1 + "<span class='icon'>&#x1F48E;</span>" + mp_buys + "</span>" + sharingWord1 + "</span>";

		   }
		}
		else {
			document.getElementById("mp_counter1").innerHTML = numbercountformat1 + "  <span class='icon'>&#x1F48E;</span> 0</span> " + sharingWord1 + "</span>";
		}
		paywallreturn1 = object.returndata;
	}
	if (object.tip == "yes") {
		mp_skript = "mp_handleSuccessfulTip(payment)";
		paymentLabel = "Tip";
		mp_element = "tbutton";
		if (typeof mp_tips !== "undefined") {
			if (object.nometanet !== "yes" && mp_tips !== 0) {
				document.getElementById("counterTips").innerHTML = numbercountformat2 + " <span class='icon'>&#10084;</span>" + mp_tips + "</span>" + sharingWord3 + "</div>";
			}
			else {
				document.getElementById("counterTips").innerHTML = numbercountformat2 + " <span class='icon'>&#128580;</span>" + mp_tips + "</span>" + sharingWord3 + "</div>";
			}
		}
		else {
			//document.getElementById("counterTips").innerHTML = "Nobody tiped the author. " + sharingWord3;
			document.getElementById("counterTips").innerHTML = numbercountformat2 + " <span class='icon'>&#x1F644;</span> 0</span>" + sharingWord3 + "</span>";
		}
		tipreturn = object.returndata;
	}
	if (object.paywall2 == "yes") {
		mp_skript = "mp_shandleSuccessfulPayment2(payment)";
		paymentLabel = "Buy";
		mp_element = "mbutton2";
		if (typeof mp_buys2 !== "undefined") {

			if (mp_buys2 == 0) {
				document.getElementById("mp_counter2").innerHTML = numbercountformat3 + "<span class='icon'>&#x1F48E;</span>" + mp_buys2 + "</span>" + sharingWord2 + "</span>";
			}
			else {
				document.getElementById("mp_counter2").innerHTML = numbercountformat3 + "<span class='icon'>&#x1F48E;</span>" + mp_buys2 + "</span>" + sharingWord2 + "</span>";
			}
		}
		else {
			document.getElementById("mp_counter2").innerHTML = numbercountformat3 + " <span class='icon'>&#x1F48E;</span> 0</span>" + sharingWord2 + "</span>";
		}
		paywallreturn2 = object.returndata;
	}
	if (typeof object["refID"] !== "undefined") {
		object.refAmount = object.amount * object.ref;
	}
	else {
		object.refAmount = 0;
	}
	if (typeof object.fourthPartner !== "undefined") {
		object.sharingamount1 = object.amount * (object.sharing * 0.35);
		object.sharingamount2 = object.amount * (object.sharing * 0.30);
		object.sharingamount3 = object.amount * (object.sharing * 0.20);
		object.sharingamount4 = object.amount * (object.sharing * 0.15);
		object.amount = object.amount * (1 - object.sharing - object.refAmount);
	}
	else if (typeof object.thirdPartner !== "undefined") {
		object.sharingamount1 = object.amount * (object.sharing * 0.50);
		object.sharingamount2 = object.amount * (object.sharing * 0.30);
		object.sharingamount3 = object.amount * (object.sharing * 0.20);
		object.amount = object.amount * (1 - object.sharing - object.refAmount);
	}
	else if (typeof object.secondPartner !== "undefined") {
		object.sharingamount1 = object.amount * (object.sharing * 0.60);
		object.sharingamount2 = object.amount * (object.sharing * 0.40);
		object.amount = object.amount * (1 - object.sharing - object.refAmount);
	}
	else if (typeof object.firstPartner !== "undefined")  {
		object.sharingamount1 = object.amount * object.sharing;
		object.amount = object.amount * (1 - object.sharing - object.refAmount);
	}
	outPuts = [
		{
			to: object.to,
         currency: object.currency,
         amount: object.amount
		}
	]
	if (typeof object.firstPartner !== "undefined")  {
		outPuts1st = {
			to: object.firstPartner,
         currency: object.currency,
         amount: object.sharingamount1
		}
		outPuts.push(outPuts1st);
	}
	if (typeof object.secondPartner !== "undefined")  {
		outPuts2nd = {
			to: object.secondPartner,
         currency: object.currency,
         amount: object.sharingamount2
		}
		outPuts.push(outPuts2nd);
	}
	if (typeof object.thirdPartner !== "undefined")  {
		outPuts3rd = {
			to: object.thirdPartner,
         currency: object.currency,
         amount: object.sharingamount3
		}
		outPuts.push(outPuts3rd);
	}
	if (typeof object.fourthPartner !== "undefined")  {
		outPuts4th = {
			to: object.fourthPartner,
         currency: object.currency,
         amount: object.sharingamount4
		}
		outPuts.push(outPuts4th);
	}
	if (typeof object["refID"] !== "undefined") {
		outPutsRef = {
			to: object.refID,
         currency: object.currency,
         amount: object.refAmount
		}
		outPuts.push(outPutsRef);
	}
	if (typeof object.nometanet !== "undefined" && object.nometanet == "no") {
		outPutsMeta = {
			type: 'SCRIPT',
         script: object.returndata,
         amount: '0',
         currency: 'BSV'
		}
		outPuts.push(outPutsMeta);
	}


	mbobject = {
		outputs: outPuts,
		label: paymentLabel,
   	onPayment: function (payment) {
			if (typeof paywallreturn1 != "undefined") {
				if (payment.paymentOutputs[0].script == paywallreturn1) {
					mp_handleSuccessfulPayment1(payment);
				}
			}
         if (typeof tipreturn != "undefined") {
          	if (payment.paymentOutputs[0].script == tipreturn) {
              mp_handleSuccessfulTip(payment);
     			}
     		}
     		if (typeof paywallreturn2 != "undefined") {
				if (payment.paymentOutputs[0].script == paywallreturn2) {
					mp_handleSuccessfulPayment2(payment);
				}
			}
     	},
      onError: function (arg) { console.log('onError', arg) }
   }
	const div = document.getElementById(mp_element);
  moneyButton.render(div,	mbobject);
 }


function mp_expandInfo(mp_elem, mp_amount, mp_currency) {
	console.log(mp_elem, mp_amount, mp_currency)
	if (mp_elem == "box") {
			mp_verb = "bought";
			if (typeof mp_buys == "undefined") {
				mp_buys = 0
			}
			document.getElementById("mp_box").innerHTML = "<span class='icon'>&#x1F48E;</span>" + mp_buys + " people " + mp_verb + " this article and spend " + (mp_amount * mp_buys).toFixed(2) + " " + mp_currency;
			document.getElementById("mp_box").style.width = "400px";
			document.getElementById("mp_box").style.cursor = "default";
			document.getElementById("mp_box").setAttribute( "onClick", "javascript: mp_deflateInfo('mp_box', " + mp_amount + ")");
			document.getElementById("mp_box").onmouseleave = function() { mp_deflateInfo('box', mp_amount);	};
	}
	if (mp_elem == "mp_box3") {
			if (typeof mp_tips == "undefined") {
				mp_tips = 0
			}
			mp_verb = "tipped";
			mp_theIcon = "&#10084;";
			if (mp_tips == 0) {
				mp_theIcon = "&#x1F641;";
			}
		  	document.getElementById("mp_box3").innerHTML = "<span class='icon'>" + mp_theIcon + "</span>" + mp_tips + " people " + mp_verb + " this article and spend " + (mp_amount * mp_tips).toFixed(2) + " " + mp_currency;
				document.getElementById("mp_box3").style.width = "400px";
			document.getElementById("mp_box3").style.cursor = "default";
			document.getElementById("mp_box3").setAttribute( "onClick", "javascript: mp_deflateInfo('box3', " + mp_amount + ")");
		 document.getElementById("mp_box3").onmouseleave = function() { deflateInfo('mp_box3', mp_amount);	};
	}
	if (mp_elem == "box4") {
		if (typeof mp_buys2 == "undefined") {
				mp_buys2 = 0
			}
			mp_verb = "bought";
			document.getElementById("mp_box4").innerHTML = "<span class='icon'>&#x1F48E;</span>" + mp_buys2 + " people " + mp_verb + " this article and spend " + (mp_amount * mp_buys2).toFixed(2) + " " + mp_currency;
				document.getElementById("mp_box4").style.width = "400px";
			document.getElementById("mp_box4").style.cursor = "default";
			document.getElementById("mp_box4").setAttribute( "onClick", "javascript: mp_deflateInfo('mp_box4', " + mp_amount + ")");
			document.getElementById("mp_box4").onmouseleave = function() { console.log("now");mp_deflateInfo('mp_box4', mp_amount);	};
	}
}

function mp_deflateInfo(mp_elem, mp_amount) {
	if (mp_elem == "box") {
		mp_noum = " buyers";
		mp_theIcon = "&#x1F48E;";
		if (mp_buys == 0) {
			mp_theIcon = "&#x1F48E;";
		}
		document.getElementById("mp_box").innerHTML = "<span class='icon'>" + mp_theIcon + "</span>" + mp_buys ;
		document.getElementById("mp_box").style.width = "120px";
		document.getElementById("mp_box").style.cursor = "help";
		document.getElementById("mp_box").setAttribute( "onClick", "javascript: mp_expandInfo('mp_box', " + mp_amount + ")");
		document.getElementById("mp_box").onmouseon = function() { mp_expandInfo('mp_box', mp_amount);	};

	}
	if (mp_elem == "mp_box3") {
		mp_noum = " tips";
		mp_theIcon = "&#10084;";
			mp_theIcon = "&#x1F641;";
			if (mp_tips == 0) {
		}
		document.getElementById("mp_box3").innerHTML = "<span class='icon'>" + mp_theIcon + "</span>" + mp_tips;
		document.getElementById("mp_box3").style.width = "120px";
		document.getElementById("mp_box3").style.cursor = "help";
		document.getElementById("mp_box3").setAttribute( "onClick", "javascript: mp_expandInfo('mp_box3', " + mp_amount + ")");
		document.getElementById("mp_box3").onmouseon = function() { mp_expandInfo('mp_box3', mp_amount);	};
	}
	if (mp_elem == "mp_box4") {
		mp_noum = " buyers";
		mp_theIcon = "&#x1F48E;";
		if (mp_buys2 == 0) {
			mp_theIcon = "&#x1F48E;";
		}
		document.getElementById("mp_box4").innerHTML =  "<span class='icon'>" + mp_theIcon + "</span>" + mp_buys2;
		document.getElementById("mp_box4").style.width = "120px";
		document.getElementById("mp_box4").style.cursor = "help";
		document.getElementById("mp_box4").setAttribute( "onClick", "javascript: mp_expandInfo('mp_box4', " + object.amount + ")");
		document.getElementById("mp_box4").onmouseon = function() { mp_expandInfo('mp_box4', mp_amount);	};
	}
}

function mp_expandInfo2(mp_elem) {
	if (mp_elem == "mp_box2") {
		if (typeof mp_buys == "undefined") {
			mp_buys = 0;
		}
		mp_noum = mp_buys + 1 + ". ";
		mp_verb = "buyer of ";
		mp_verb2 = "purchases";
	}
	if (mp_elem == "mp_box5") {
		if (typeof mp_buys2 == "undefined") {
			mp_buys2 = 0;
		}
		mp_noum = mp_buys2 + 1 + ". ";
		mp_verb = "buyer of ";
		mp_verb2 = "purchases";
	}
	else {
		if (typeof mp_tips == "undefined") {
			mp_tips = 0;
		}
		mp_noum = mp_tips + 1 + ". ";
		mp_verb = "to tip ";
		mp_verb2 = "tips";
	}
	document.getElementById(mp_elem).innerHTML = "<span class='icon'>&cent;</span> You are the " + mp_noum + mp_verb + "this article and will receive an income share for all " + mp_verb2 + " after you for up to " + shareQuote + "%";
	document.getElementById(mp_elem).style.width = "400px";
	document.getElementById(mp_elem).style.cursor = "default";
	document.getElementById(mp_elem).setAttribute( "onClick", "javascript: mp_deflateInfo2('" + mp_elem + "')");
	document.getElementById(mp_elem).onmouseleave = function() { mp_deflateInfo2(mp_elem);	};


}

function mp_deflateInfo2(mp_elem) {
	document.getElementById(mp_elem).innerHTML = "<span class='icon'>&cent;</span> " + shareQuote + "%";;
	document.getElementById(mp_elem).style.width = "250px";
	document.getElementById(mp_elem).style.cursor = "help";
	document.getElementById(mp_elem).setAttribute( "onClick", "javascript: mp_expandInfo2('" + mp_elem + "')");
	document.getElementById(mp_elem).onmouseon = function() { expandInfo2(mp_elem);	};
}
