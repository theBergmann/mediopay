 function mp_handleSuccessfulPayment1(payment) {
     mp_unlockContent1(payment);
}
function mp_handleFailedPayment1(error) {
        alert("Sorry, the payment did not process correctly.")
}
function mp_unlockContent1(payment) {
  mp_element_fade = document.getElementById("mp_fade") ;
  mp_element_fade.parentNode.removeChild(mp_element_fade);
  mp_element = document.getElementById("mp_tipFrame");
  if (typeof mp_element !== "undefined") {
      if (mp_element !== null) {
      console.log("make it visible again");
      mp_element.classList.remove("mp_invisible");
      document.getElementById("mp_tip").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
    }
  }
        document.getElementById("mp_unlockable1").innerHTML = realContent1;
       document.getElementById("mp_unlockable1").classList.toggle("mp_unlocked");
    document.getElementById("mp_frame1").classList.toggle("mp_paid");
        document.getElementById("mp_frame1").innerHTML="<em>Share <a href='" + dataLink + "?ref=" + payment.userId + "'>this link</a> to get a share of later payments.</em><br />See the <a href='https://www.mediopay.com/value-list/'>Ranking of the most valuable posts</a>";

}

function mp_unlockContent2(payment) {
  mp_element_fade = document.getElementById("mp_fade2");
  mp_element_fade.parentNode.removeChild(mp_element_fade);
  mp_element_after = document.getElementById("mp_fade");
  mp_element_after2 = document.getElementById("mp_frame1");
  document.getElementById("mp_unlockable2_content").innerHTML = realContent2;
  document.getElementById("mp_unlockable2").classList.toggle("mp_unlocked");
  document.getElementById("mp_frame2").classList.toggle("mp_paid");
  document.getElementById("mp_frame2").innerHTML="<em>Share <a href='" + dataLink + "?ref=" + payment.userId + "'>this link</a> to get a share of later payments.</em><br />See the <a href='https://www.mediopay.com/value-list/'>Ranking of the most valuable posts</a>";
  if (typeof mp_element_after !== "undefined") {
      mp_element_after.classList.remove("mp_invisible");
      mp_element_after2.classList.remove("mp_invisible");
      //document.getElementById("mp_pay").innerHTML = "<img src='" + MedioPayPath + "questionmark-white.png' width='17' /></span><br />"
  }

}



function mp_handleSuccessfulTip(payment) {
  document.getElementById("mp_tipFrame").innerHTML = "<h2>" + mp_thankYou + "</h2><em>Share <a href='" + dataLink + "?ref=" + payment.userId + "'>this link</a> to get a share of later payments.</em><br />See the <a href='https://www.mediopay.com/value-list/'>Ranking of the most valuable posts</a>";
}

function mp_handleSuccessfulPayment2(payment) {
     mp_unlockContent2(payment);

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
