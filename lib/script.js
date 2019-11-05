function createObject(method) {
  k = 0;
  p = 0;
  if (method == "editor") {
    secondEdit = "yes";
  }
	dataDomain = window.location.hostname;
	dataURL = window.location.pathname;
  paymentObjects = [];
  if (checkBox == "yes") {
    var returndata = bsv.Script.buildDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100201', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();
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
			 to: theAddress,
			 returndata: returndata,
			 outputs: 1,
			 currency: theCurrency
    }
    if (typeof refID !== "undefined") {
			 tip.refID = refID;
			 tip.outputs = 2;
    }
    paymentObjects.push(tip);
	 }
   if (typeof realContent1 !== "undefined" && realContent1.length > 0) {
   	  var returndata = bsv.Script.buildDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100101', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();
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
			        to: theAddress,
			        outputs: 1,
			        currency: theCurrency
		 }
		  if (typeof refID !== "undefined") {
			        paywall.refID = refID;
			        paywall.outputs = 2;
    	}
		 paymentObjects.push(paywall);
  }
	if (method == "shortcode") {
    var returndata = bsv.Script.buildDataOut(['1NYJFDJbcSS2xGhGcxYnQWoh4DAjydjfYU', "" + '100102', "" + dataTitle, "" + dataContent, "" + dataDomain, "" + dataURL, "" + sharingQuota, "" + refQuota]).toASM();
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
          to: theAddress,
          outputs: 1,
          currency: theCurrency
    }
    if (typeof refID !== "undefined") {
          paywall2.refID = refID;
          paywall2.outputs = 2;
     }
     paymentObjects.push(paywall2);
  }
	querryPlanaria(paymentObjects);
	getAddress(paymentObjects);

}
