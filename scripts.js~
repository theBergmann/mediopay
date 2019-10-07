// if the user added a paymail address, query polynym to get the BSV address

function getAddress(object) {
	payload = object;
	console.log("getAddress");
	console.log(payload[0]["to"]);
	if (payload[0]["to"].includes("@")) {
				console.log("paymail");
				fetch("https://api.polynym.io/getAddress/" + theAddress).then(function(r) {
  					return r.json()
  				}).then(function(r) {
  					console.log("got address" + payload[0].to);
  					console.log(payload.length);
					for (let i=0; i<payload.length; i++) {	
						console.log(i);	  					  						
  						payload[i]["to"] = r.address;
						console.log("to " + payload[i].to)
  					}			
  					if (k == 1 && p == 0) {
  						for (let i=0;i<payload.length;i++) {
  							console.log("load button " +  i);
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
  				console.log("no paymail detected");
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
	payload = object;
	console.log("here we are!");
	console.log(payload);
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
	console.log("ask Planaria");
	fetch(url, header).then(function(r) {
  		return r.json()
	}).then(function(r) {
    	results = r.c.concat(r.u);
    	console.log("Here we go");
    	//console.log(results);
    	console.log("R-length: ")
    	console.log(results.length);
    	if (typeof results[0] !== "undefined") {
    	if (typeof results[0].out[0].s7 !== "undefined") {
			for (let i=0;i<payload.length;i++) {
				payload[i]["sharing"] = results[0].out[0].s7;    	
    		}				
    	}
    	}
		if (typeof results !== "undefined" && results.length > 0) {		
			console.log("might have partners");
			tips = 0;
			buys = 0;		
			for (let j=0; j<results.length; j++) {
				if (results[j].out[0].s2 == "100201") {
					console.log("spotted former tip");
					tips = tips + 1;			
				}
				if (results[j].out[0].s2 == "100101") {
					console.log("spotted former pay");
					buys = buys + 1;			
				}	
			}    	
    		for (let n=0; n<payload.length; n++) {
				if (payload[n]["paywall"] == "yes") {
					payload[n]["number"] = buys;			
				} 
				if (payload[n]["tip"] == "yes") {
					payload[n]["number"] = tips;			
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
 				console.log("second partner?");
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
	  			console.log("load button");
				loadButton(payload[i]); 
				p = 1;   	
    		}	
	  	}
	  	else {
			k = 1;	  				
	  	}  	
	});
}    	
    	

function loadButton(word) {
	object = word;
	console.log("show object");
	console.log(object);
	console.log("type " + object.typenumber);
	if (object.typenumber == "100101") {
		paywallreturn = object.returndata;	
			document.getElementById("frame").innerHTML = object.number + " people have bought this article.";
	}
	if (object.typenumber == "100201") {
		tipreturn = object.returndata;
			document.getElementById("frame").innerHTML = object.number + " people have tiped the author.";
	}
	if (object.paywall == "yes") {
		skript = "handleSuccessfulPayment(payment)";	
		paymentLabel = "Buy";
		element = "mbutton";
	}
	if (object.tip == "yes") {
		skript = "handleSuccessfulTip(payment)";
		paymentLabel = "Tip";
		element = "tbutton";	
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
		console.log("1 partner");
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
		console.log("one partner");
		outPuts1st = {
			to: object.firstPartner,
         currency: object.currency,
         amount: object.sharingamount1
		}	
		outPuts.push(outPuts1st);
	}
	else {
		console.log("no partner");
	}	
	if (typeof object.secondPartner !== "undefined")  {
		console.log("2 partner");
		outPuts2nd = {
			to: object.secondPartner,
         currency: object.currency,
         amount: object.sharingamount2
		}	
		outPuts.push(outPuts2nd);
	}
	if (typeof object.thirdPartner !== "undefined")  {
	console.log("3 partner");
		outPuts3rd = {
			to: object.thirdPartner,
         currency: object.currency,
         amount: object.sharingamount3
		}	
		outPuts.push(outPuts3rd);
	}	
	if (typeof object.fourthPartner !== "undefined")  {
	console.log("4 partner");
		outPuts4th = {
			to: object.fourthPartner,
         currency: object.currency,
         amount: object.sharingamount4
		}	
		outPuts.push(outPuts4th);
	}			
	if (typeof object["refID"] !== "undefined") {
	console.log("ref partner");
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
			if (typeof paywallreturn != undefined && payment.paymentOutputs[0].script == paywallreturn) {
				handleSuccessfulPayment(payment);										
			}                 		
         if (typeof tipreturn != undefined && payment.paymentOutputs[0].script == tipreturn) {
              handleSuccessfulTip(payment);
     		}
     	},  	           
      onError: function (arg) { console.log('onError', arg) }
   }
	console.log(mbobject);
	const div = document.getElementById(element);
   moneyButton.render(div,	mbobject);		   
   


 }







