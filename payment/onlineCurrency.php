<?php

function convertCurrency($amount, $from, $to) {
    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
    $data = file_get_contents($url);
    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
    //return 'Euros set:' . $amount . ' and result in ' . $to . ': ' .  round($converted, 2);
    return  round($converted, 2);
}
//Usage
// echo convertCurrency(7, "EUR", "USD");
// echo "<br>";
// echo convertCurrency(7, "EUR", "GBP");

session_start();

//Price in Euros
$price = '7.00';

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en'>";
echo "<head>";	
	echo "<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'/>";
	echo "<meta http-equiv='Content-Type' content='text/html;charset=UTF-8' />";
    echo "<title>R'YouIN license</title>";    
    
    echo "<link rel='stylesheet' type='text/css' href='css/style.css' media='all' />";
    echo "<link href='css/media-queries.css' rel='stylesheet' type='text/css'>";
    
echo "</head>";

echo "<body>";

echo "<p>The team from session: " .  $_SESSION['myteamname'] . "</p>";

echo "<div id='parent-container'>";

    echo "<div id='button1' class='child-container'>";

        echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
        echo"<input type='hidden' name='cmd' value='_xclick-subscriptions'>";
        echo"<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
        echo"<input type='hidden' name='lc' value='FI'>";
        echo"<input type='hidden' name='item_name' value='RYouIN subscription'>";
        echo"<input type='hidden' name='no_note' value='1'>";
        echo"<input type='hidden' name='no_shipping' value='1'>";
        echo"<input type='hidden' name='rm' value='1'>";
        echo"<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
        echo"<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
        echo"<input type='hidden' name='src' value='1'>";
        echo"<input type='hidden' name='currency_code' value='EUR'>";
        echo"<input type='hidden' name='bn' value='PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest'>";
        echo"<p>€ " . $price . " EUR - monthly </p>";

        echo"<table style='display:none;>";
        echo"<tr style='display:none;'><td><input type='hidden' name='on0' value=''></td></tr><tr><td><select name='os0'>";
        echo"	<option value='Option 1' style='display:none;>Option 1 : € " . $price . " EUR - monthly</option>";
        echo"</select style='display:none;> </td></tr>";
        echo"</table style='display:none;>";

        echo"<input type='hidden' name='currency_code' value='EUR'>";
        echo"<input type='hidden' name='option_select0' value='Option 1'>";
        echo"<input type='hidden' name='option_amount0' value='" . $price . "'>";
        echo"<input type='hidden' name='option_period0' value='M'>";
        echo"<input type='hidden' name='option_frequency0' value='1'>";
        echo"<input type='hidden' name='option_index' value='0'>";
        echo"<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
        echo"<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
        echo"</form>";
    
     echo "</div>";
    
    echo "<div id='button2' class='child-container'>";

        echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
        echo"<input type='hidden' name='cmd' value='_xclick-subscriptions'>";
        echo"<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
        echo"<input type='hidden' name='lc' value='FI'>";
        echo"<input type='hidden' name='item_name' value='RYouIN subscription'>";
        echo"<input type='hidden' name='no_note' value='1'>";
        echo"<input type='hidden' name='no_shipping' value='1'>";
        echo"<input type='hidden' name='rm' value='1'>";
        echo"<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
        echo"<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
        echo"<input type='hidden' name='src' value='1'>";
        echo"<input type='hidden' name='currency_code' value='USD'>";
        echo"<input type='hidden' name='bn' value='PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest'>";
        echo"<p>$ " . convertCurrency($price, 'EUR', 'USD') . " USD - monthly </p>";

        echo"<table style='display:none;";
        echo"<tr style='display:none;><td><input type='hidden' name='on0' value=''></td></tr><tr><td><select name='os0'>";
        echo"	<option value='Option 1' style='display:none;>Option 1 : " . convertCurrency($price, 'EUR', 'USD') . " USD - monthly</option>";
        echo"</select style='display:none;> </td></tr>";
        echo"</table style='display:none;>";
        
        echo"<input type='hidden' name='currency_code' value='USD'>";
        echo"<input type='hidden' name='option_select0' value='Option 1'>";
        echo"<input type='hidden' name='option_amount0' value='" . convertCurrency($price, 'EUR', 'USD') . "'>";
        echo"<input type='hidden' name='option_period0' value='M'>";
        echo"<input type='hidden' name='option_frequency0' value='1'>";
        echo"<input type='hidden' name='option_index' value='0'>";
        echo"<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
        echo"<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
        echo"</form>";
    
     echo "</div>";    
    
        echo "<div id='button2' class='child-container'>";

        echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
        echo"<input type='hidden' name='cmd' value='_xclick-subscriptions'>";
        echo"<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
        echo"<input type='hidden' name='lc' value='FI'>";
        echo"<input type='hidden' name='item_name' value='RYouIN subscription'>";
        echo"<input type='hidden' name='no_note' value='1'>";
        echo"<input type='hidden' name='no_shipping' value='1'>";
        echo"<input type='hidden' name='rm' value='1'>";
        echo"<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
        echo"<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
        echo"<input type='hidden' name='src' value='1'>";
        echo"<input type='hidden' name='currency_code' value='GBP'>";
        echo"<input type='hidden' name='bn' value='PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest'>";
        echo"<p>£ " . convertCurrency($price, 'EUR', 'GBP') . " GBP - monthly </p>";

        echo"<table style='display:none;>";
        echo"<tr style='display:none;><td><input type='hidden' name='on0' value=''></td></tr><tr><td><select name='os0'>";
        echo"	<option value='Option 1' style='display:none;>Option 1 : " . convertCurrency($price, 'EUR', 'GBP') . " GBP - monthly</option>";
        echo"</select style='display:none;> </td></tr>";
        echo"</table style='display:none;>";
        
        echo"<input type='hidden' name='currency_code' value='GBP'>";
        echo"<input type='hidden' name='option_select0' value='Option 1'>";
        echo"<input type='hidden' name='option_amount0' value='" . convertCurrency($price, 'EUR', 'GBP') . "'>";
        echo"<input type='hidden' name='option_period0' value='M'>";
        echo"<input type='hidden' name='option_frequency0' value='1'>";
        echo"<input type='hidden' name='option_index' value='0'>";
        echo"<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
        echo"<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
        echo"</form>";
    
     echo "</div>";  
 
    //     //Production
    //     //echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
        
    //     //Sandbox
    //     echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
    //     echo "<input type='hidden' name='cmd' value='_xclick-subscriptions'>";
    //     echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
    //     echo "<input type='hidden' name='lc' value='FI'>";
    //     echo "<input type='hidden' name='item_name' value='RYouIN license'>";
    //     echo "<input type='hidden' name='no_note' value='1'>";
    //     echo "<input type='hidden' name='no_shipping' value='1'>";
    //     echo "<input type='hidden' name='rm' value='1'>";
    //     echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
    //     echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
    //     echo "<input type='hidden' name='src' value='1'>";
    //     echo "<input type='hidden' name='currency_code' value='EUR'>";
    //     echo "<input type='hidden' name='bn' value='PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHosted'>";
    //     echo "<table>";
    //     echo "<tr><td id='currency_select'><input type='hidden' name='on0' value='Currency options'>Select currency</td></tr><tr><td><select name='os0'>";
    //         echo "<option value='EUR' id='EUR'>EUR : €" . $price . " EUR - monthly</option>";
    //         echo "<option value='USD' id='USD'>USD : $" . convertCurrency($price, 'EUR', 'USD') . " USD - monthly</option>";
    //         echo "<option value='GBP' id='GBP'>GBP : £" . convertCurrency($price, 'EUR', 'GBP') . " GBP - monthly</option>";
    //     echo "</select> </td></tr>";
    //     echo "</table>";
    //     echo "<br>";
    //     echo "<input type='hidden' name='currency_code' value='EUR'>";
    //     echo "<input type='hidden' name='option_select0' value='EUR'>";
    //     echo "<input type='hidden' name='option_amount0' value='" . $price . "'>";
    //     echo "<input type='hidden' name='option_period0' value='M'>";
    //     echo "<input type='hidden' name='option_frequency0' value='1'>";
    //     echo "<input type='hidden' name='option_select1' value='USD'>";
    //     echo "<input type='hidden' name='option_amount1' value='" . convertCurrency(7.00, 'EUR', 'USD') . "'>";
    //     echo "<input type='hidden' name='option_period1' value='M'>";
    //     echo "<input type='hidden' name='option_frequency1' value='1'>";
    //     echo "<input type='hidden' name='option_select2' value='GBP'>";
    //     echo "<input type='hidden' name='option_amount2' value='" . convertCurrency(7.00, 'EUR', 'GBP') . "'>";
    //     echo "<input type='hidden' name='option_period2' value='M'>";
    //     echo "<input type='hidden' name='option_frequency2' value='1'>";
    //     echo "<input type='hidden' name='option_index' value='0'>";
    //     echo "<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
    //     echo "<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>"; 
    //     echo "</form>";
    // echo "</div>";

    // echo "<div id='button1' class='child-container'>";

    //     //Production
    //     //echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
    
echo "</div>";

echo "</body>";

echo "</html>";

?>
