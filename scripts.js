// if the user added a paymail address, query polynym to get the BSV address

function getAddress(object) {
	payload = object;
	console.log(payload);
	if (payload[0]["to"].includes("@")) {
				fetch("https://api.polynym.io/getAddress/" + theAddress).then(function(r) {
  					return r.json()
  				}).then(function(r) {
					for (let i=0; i<payload.length; i++) {					  						
  						payload[i]["to"] = r.address;
  					}			
  					if (k == 1 && p == 0) {
  						for (let i=0;i<payload.length;i++) {
							loadButton(payload[i]);    	
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
	  				for (let i=0;i<payload.length;i++) {
						loadButton(payload[i]); 
						p = 1;   	
    				}	
	  			}
	  			else {
						k = 1;	  				
	  			}  			
  			}
}

// ask Planaria to get information about the first transactions to get the address for income sharing and the number of transactions

function querryPlanaria(object) {
	let tipbuttr = document.getElementById('tbutton');
	console.log("show tip button");
	console.log(tipbuttr);
	payload = object;
	if (payload[0].nometanet == "yes") {
		setTimeout(function() { 
		console.log("no metadata");
		for (let h=0; h<payload.length; h++) {
			payload[h].sharing = 0.0;	
			payload[h].number = 0;
			payload[h].buys = 0;
			payload[h].tips = 0;	
		}
		if (k == 1 && p == 0) {
	  		for (let i=0;i<payload.length;i++) {
				loadButton(payload[i]); 
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
         	"out.s3": payload[0]["title"]
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
    	results = r.c.concat(r.u);
    	if (typeof results[0] !== "undefined") {
    	if (typeof results[0].out[0].s7 !== "undefined") {
			for (let i=0;i<payload.length;i++) {
				payload[i]["sharing"] = results[0].out[0].s7;    	
    		}				
    	}
    	}
		if (typeof results !== "undefined" && results.length > 0) {	
			tips = 0;
			buys = 0;	
			buys2 = 0;	
			for (let j=0; j<results.length; j++) {
				if (results[j].out[0].s2 == "100201") {
					tips = tips + 1;			
				}
				if (results[j].out[0].s2 == "100101") {
					buys = buys + 1;			
				}	
				if (results[j].out[0].s2 == "100102") {
					buys2 = buys2 + 1;			
				}	
			}    	
    		for (let n=0; n<payload.length; n++) {
				if (payload[n]["paywall"] == "yes") {
					payload[n]["number"] = buys;			
				} 
				if (payload[n]["tip"] == "yes") {
					payload[n]["number"] = tips;			
				}
				if (payload[n]["paywall2"] == "yes") {
					payload[n]["number"] = buys2;			
				}        	
    		}
			if (payload[0].sharing > 0) {
				for (n=0; n<payload.length; n++) {
					if (typeof results[0].out[2] !== "undefined") {
						payload[n]["firstPartner"] = results[0].out[2].e.a;
					}
					else {
						payload[n]["firstPartner"] = results[0].in[0].e.a;					
					}
					payload[n]["outputs"] = payload[n]["outputs"] + 1;
				}			
			}	    	
 			if (payload[0].sharing > 0.1 && results.length > 1 ) {
			 	for (n=0; n<payload.length; n++) {
			 		if (typeof results[1].out[3] !== "undefined") {					
						payload[n]["secondPartner"] = results[1].out[3].e.a;
					}
					else {
						payload[n]["secondPartner"] = results[1].in[0].e.a;					
					}
					payload[n]["outputs"] = payload[n]["outputs"] + 1;
				}			
 			}
 			if (payload[0].sharing > 0.2 && results.length > 2 ) {
			 	for (n=0; n<payload.length; n++) {
			 		if (typeof results[2].out[4] !== "undefined") {
						payload[n]["thirdPartner"] = results[2].out[4].e.a;
					}
					else {
						payload[n]["firstPartner"] = results[2].in[0].e.a;					
					}
					payload[n]["outputs"] = payload[n]["outputs"] + 1;
				}			
 			} 
 			if (payload[0].sharing > 0.3 && results.length > 3 ) {
			 	for (n=0; n<payload.length; n++) {
			 		if (typeof results[3].out[5] !== "undefined") {
						payload[n]["fourthPartner"] = results[3].out[5].e.a;
					}
					else {
						payload[n]["firstPartner"] = results[3].in[0].e.a;					
					}
					payload[n]["outputs"] = payload[n]["outputs"] + 1;
				}			
 			}          	
    	}
    	else {
			 for (let n=0; n<payload.length; n++) {
				if (payload[n]["paywall"] == "yes") {
					payload[n]["number"] = 0;			
				} 
				if (payload[n]["tips"] == "yes") {
					payload[n]["number"] = 0;			
				}    	
    		}   	
    	}
    	if (k == 1 && p == 0) {
	  		for (let i=0;i<payload.length;i++) {
				loadButton(payload[i]); 
				p = 1;   	
    		}	
	  	}
	  	else {
			k = 1;	  				
	  	}  	
	});
	}
}    	
    	

function loadButton(word) {	
	object = word;
	//console.log(buys + " | " + tips + " | " + buys2 + " | " + object.amount);
	console.log(object);
	sharingWord = "";
	sharingStars = "<span style='color:#FB9868; font-size:15pt'>!</span>";
	sharingAbbrPay = "<abbr title='As one of the first buyers, you will receive a share when people buy this article after you'>";
	//sharingAbbrTip = "<abbr title='As one of the first tipers, you will receive a share when people tip this article after you'>";
	if (object.paywall == "yes" || object.paywall2 == "yes") {
		wording = "buy";	
	}
	if (object.tip == "yes") {
		wording = "tip";
	}	
	numbercountformat1 = "<br /><div id='box' style='border: 1px solid #FB9868;border-radius: 8px;padding:2px; background-color: #FFCEB5; width:100px;margin-left:-25px;' onmouseover='expandInfo(\"box\", " + object.amount + ")' onmouseleave='deflateInfo(\"box\", " + object.amount + ")'>"
	numbercountformat2 = "<br /><div id='box3' style='border: 1px solid #FB9868;border-radius: 8px;padding:2px; background-color: #FFCEB5; width:100px;margin-left:-25px;' onmouseover='expandInfo(\"box3\", " + object.amount + ")' onmouseleave='deflateInfo(\"box3\", " + object.amount + ")'>"
	numbercountformat3 = "<br /><div id='box4' style='border: 1px solid #FB9868;border-radius: 8px;padding:2px; background-color: #FFCEB5; width:100px;margin-left:-25px;' onmouseover='expandInfo(\"box4\", " + object.amount + ")' onmouseleave='deflateInfo(\"box4\", " + object.amount + ")'>"

		
	numbercountTips = numbercountformat2 + object.number + " tips</div>";
	//console.log(numbercount);	
	if ((object.sharing * 10) > object.number) {
		console.log("chance to share!");	
		sharingWord1 = "<div id='box2' onmouseover='expandInfo2(\"box2\")' onmouseleave='deflateInfo2(\"box2\")' ><em>income share available</em></div>";
		sharingWord2 = "<div id='box5' onmouseover='expandInfo2(\"box5\")' onmouseleave='deflateInfo2(\"box5\")' ><em>income share available</em></div>";
		sharingWord3 = "<div id='box6' onmouseover='expandInfo2(\"box6\")' onmouseleave='deflateInfo2(\"box6\")' ><em>income share available</em></div>";
	}
	else {
		sharingWord1 = "";
		sharingWord2 = "";
		sharingWord3 = "";
	}
	if (object.paywall == "yes") {
		skript = "handleSuccessfulPayment1(payment)";	
		paymentLabel = "Buy";
		element = "mbutton1";
		if (typeof object.number !== "undefined") {
			if (object.nometanet !== "yes") {
				document.getElementById("counter1").innerHTML = numbercountformat1 + object.number + " buyers</div>" + sharingWord1 + "</div>";
		   }		
		}
		else {
			document.getElementById("counter1").innerHTML = "no " + numbercountformat1 + object.number + " buyers</div>" + sharingWord1 + "</div>";		
		}
		paywallreturn1 = object.returndata;	
	}
	if (object.tip == "yes") {
		skript = "handleSuccessfulTip(payment)";
		paymentLabel = "Tip";
		element = "tbutton";	
		console.log(element);
		if (typeof object.number !== "undefined") {
			if (object.nometanet !== "yes") {
				document.getElementById("counterTips").innerHTML = numbercountTips + sharingWord3 + "</div>";
			}
		}
		else {
			//document.getElementById("counterTips").innerHTML = "Nobody tiped the author. " + sharingWord3;	
			document.getElementById("counterTips").innerHTML = "Nobody tiped the author. ";	
		}
		tipreturn = object.returndata;
	}
	if (object.paywall2 == "yes") {
		skript = "handleSuccessfulPayment2(payment)";	
		paymentLabel = "Buy";
		element = "mbutton2";
		if (typeof object.number !== "undefined") {
			document.getElementById("counter2").innerHTML = numbercountformat3 + object.number + " buyers</div>" + sharingWord2 + "</div>";
		}
		else {
			document.getElementById("counter2").innerHTML = "no " + numbercountformat3 + object.number + " buyers</div>" + sharingWord2 + "</div>";		
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
					handleSuccessfulPayment1(payment);										
				}
			}		                 		
         if (typeof tipreturn != "undefined") {
          	if (payment.paymentOutputs[0].script == tipreturn) {
              handleSuccessfulTip(payment);
     			}
     		}
     		if (typeof paywallreturn2 != "undefined") {
				if (payment.paymentOutputs[0].script == paywallreturn2) {
					handleSuccessfulPayment2(payment);										
				}
			}	
     	},  	           
      onError: function (arg) { console.log('onError', arg) }
   }
   console.log(element);
	const div = document.getElementById(element);
	console.log(div);
	let tipbuttr = document.getElementById('tbutton');
	console.log("show tip button");
	console.log(tipbuttr);
   moneyButton.render(div,	mbobject);		   
 }


function expandInfo(elem, amount) {
	console.log(elem);
	console.log(amount);
	if (elem == "box") {
			verb = "bought";
			console.log(buys + " | " +  object.amount);
			document.getElementById("box").innerHTML = buys + " happy people " + verb + " this article and spend " + (amount * buys).toFixed(2) + " " + object.currency + " for it. Join them! <a href='http://mediopay.com/bsv-how'>Learn how</a>";	
			document.getElementById("box").style.width = "400px";
	}	
	if (elem == "box3") {
			verb = "tipped";
			document.getElementById("box3").innerHTML = tips + " happy people " + verb + " this article and spend " + (amount * tips).toFixed(2) + " " + object.currency + " for it. Join them! <a href='http://mediopay.com/bsv-how'>Learn how</a>";	
			document.getElementById("box3").style.width = "400px"; 
	}
	if (elem == "box4") {
			verb = "bought";
			document.getElementById("box4").innerHTML = buys2 + " happy people " + verb + " this article and spend " + (amount * buys2).toFixed(2) + " " + object.currency + " for it. Join them! <a href='http://mediopay.com/bsv-how'>Learn how</a>";	
			document.getElementById("box4").style.width = "400px"; 
	}
}

function deflateInfo(elem) {
	console.log(elem);
	if (elem == "box") {
		noum = " buyers";	
		document.getElementById("box").innerHTML = buys + noum;
		document.getElementById("box").style.width = "100px";
	}	
	if (elem == "box3") {
		noum = " tips";;
		document.getElementById("box3").innerHTML = tips + noum;
		document.getElementById("box3").style.width = "100px"; 
	}
	if (elem == "box4") {
		noum = " buyers";	
		document.getElementById("box4").innerHTML = buys2 + noum;
		document.getElementById("box4").style.width = "100px";
	}	
}

function expandInfo2(elem) {
	if (elem == "box2") {
		noum = buys + 1 + ". ";	
		verb = "buyer of ";
		verb2 = "purchases";
	}
	if (elem == "box5") {
		noum = buys2 + 1 + ". ";
		verb = "buyer of ";
		verb2 = "purchases";
	}
	else {
		noum = tips + 1 + ". ";	
		verb = "to tip ";
		verb2 = "tips";
	}
	document.getElementById(elem).innerHTML = "<em>You are the " + noum + verb + "this article and will get an income share for all " + verb2 + " after you.</em>";
	document.getElementById(elem).style.width = "400px"; 
}

function deflateInfo2(elem) {
	document.getElementById(elem).innerHTML = "<em>income share available</em>";
	document.getElementById(elem).style.width = "250px"; 
}


