
function mediopayHideNextElements() {
    let element1 = document.getElementById("mp_fade");
    let element2 = document.getElementById("mp_frame1");
    if (typeof element1 !== undefined) {
        element1.classList.add("mp_invisible");
        element2.classList.add("mp_invisible");
    }

}



function MedioPay_textColor(elementID) {
  console.log(elementID);
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
      }
      else {
          document.getElementById(elementID).style.color = "white";
          document.getElementById("mp_pay1").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
          document.getElementById("mp_pay2").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
          document.getElementById("mp_tip").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
      }
  }



function MedioPay_changeColor() {
    var e = document.getElementById("select_color");
    var color = e.options[e.selectedIndex].value;
    document.getElementById("select_color").style.backgroundColor = "#" + color;
}

function mp_createObject(method) {
  k = 0;
  p = 0;
  if (method == "editor") {
    secondEdit = "yes";
  }
	dataDomain = window.location.hostname;
	dataURL = window.location.pathname;
  paymentObjects = [];
  if (mp_checkBox == "yes") {
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
			 to: mp_theAddress,
			 returndata: returndata,
			 outputs: 1,
			 currency: mp_theCurrency
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
			        to: mp_theAddress,
			        outputs: 1,
			        currency: mp_theCurrency
		 }
		  if (typeof refID !== "undefined") {
			        paywall.refID = refID;
			        paywall.outputs = 2;
    	}
		 paymentObjects.push(paywall);
  }
	if (method == "mp_shortcode") {
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
          to: mp_theAddress,
          outputs: 1,
          currency: mp_theCurrency
    }
    if (typeof refID !== "undefined") {
          paywall2.refID = refID;
          paywall2.outputs = 2;
     }
     paymentObjects.push(paywall2);
  }
	mp_querryPlanaria(paymentObjects);
	mp_getAddress(paymentObjects);

}
