<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<!-- disable iPhone inital scale -->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>R'YouIN</title>
	
	<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
    <link href="css/media-queries.css" rel="stylesheet" type="text/css">
	
        <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>
        <script type="text/javascript" src="js/jquery.inputfocus-0.9.min.js"></script>
        <script type="text/javascript" src="js/jquery.main.js"></script>
        <script type="text/javascript" src="../main.js"></script>
        <script type="text/javascript" src="js/spin.min.js"></script>-->

</head>
<body>

<div id="parent-container">

    <div id="button1" class="child-container">
    <!--Sandbox button-->
        <p>Payment: 0.02 EUR</p>

        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="8RLTCNLYYQKLQ">
            <input type="hidden" name="lc" value="FI">
            <input type="hidden" name="item_name" value="R'YouIN Test">
            <input type="hidden" name="amount" value="0.02">
            <input type="hidden" name="currency_code" value="EUR">
            <input type="hidden" name="button_subtype" value="services">
            <input type="hidden" name="no_note" value="0">
            <input type="hidden" name="cn" value="Add special instructions to the seller:">
            <input type="hidden" name="no_shipping" value="2">
            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
            <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>

    <div id="button2" class="child-container">
        <p>Payment: 0.09 EUR</p>
        <!--Sandbox button2-->
        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_xclick">
            <input type="hidden" name="business" value="8RLTCNLYYQKLQ">
            <input type="hidden" name="lc" value="FI">
            <input type="hidden" name="item_name" value="R'YouIN Test">
            <input type="hidden" name="amount" value="0.09">
            <input type="hidden" name="currency_code" value="EUR">
            <input type="hidden" name="button_subtype" value="services">
            <input type="hidden" name="no_note" value="0">
            <input type="hidden" name="cn" value="Add special instructions to the seller:">
            <input type="hidden" name="no_shipping" value="2">
            <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
            <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>

    <div id="button2" class="child-container">
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
        <input type="hidden" name="cmd" value="_xclick-subscriptions">
        <input type="hidden" name="business" value="J6PTBF3GND9CL">
        <input type="hidden" name="lc" value="FI">
        <input type="hidden" name="item_name" value="R'YouIN license">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="rm" value="1">
        <input type="hidden" name="return" value="https://dev-areyouin.azurewebsites.net/payment/success.html">
        <input type="hidden" name="cancel_return" value="https://dev-areyouin.azurewebsites.net/payment/cancel.html">
        <input type="hidden" name="src" value="1">
        <input type="hidden" name="currency_code" value="EUR">
        <input type="hidden" name="bn" value="PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHosted">
        <table>
        <tr><td id="currency_select"><input type="hidden" name="on0" value="Currency options">Select currency</td></tr><tr><td><select name="os0">
            <option value="EUR" id="EUR">EUR : €7.00 EUR - monthly</option>
            <option value="USD" id="USD">USD : €2.00 EUR - monthly</option>
            <option value="GBP" id="GBP">GBP : €3.00 EUR - monthly</option>
        </select> </td></tr>
        </table>
        <br>
        <input type="hidden" name="currency_code" value="EUR">
        <input type="hidden" name="option_select0" value="EUR">
        <input type="hidden" name="option_amount0" value="7.00">
        <input type="hidden" name="option_period0" value="M">
        <input type="hidden" name="option_frequency0" value="1">
        <input type="hidden" name="option_select1" value="USD">
        <input type="hidden" name="option_amount1" value="2.00">
        <input type="hidden" name="option_period1" value="M">
        <input type="hidden" name="option_frequency1" value="1">
        <input type="hidden" name="option_select2" value="GBP">
        <input type="hidden" name="option_amount2" value="3.00">
        <input type="hidden" name="option_period2" value="M">
        <input type="hidden" name="option_frequency2" value="1">
        <input type="hidden" name="option_index" value="0">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribeCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
    </div>
</div>


<script>

	//alert("showUser() gets called.");
	//if (playerID == "" || teamID == "") {
	//	document.getElementById("userlogin").innerHTML = "getLoginInformation";
	//	return;
	//}
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            if(strcmp(xmlhttp.responseText, 'USD') > 0)
			    document.getElementById("USD").innerHTML = xmlhttp.responseText;
            if(strcmp(xmlhttp.responseText, 'GBP') > 0)
                document.getElementById("GBP").innerHTML = xmlhttp.responseText;
		}
	}

	var variables = "amount=7.00"  + "&from=EUR" + "&from=USD";
	xmlhttp.open("GET", "onlineCurrency.php?" + variables, true);
	xmlhttp.send();


</script>

</body>

