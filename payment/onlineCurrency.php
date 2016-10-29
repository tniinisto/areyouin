<?php

include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );


function convertCurrency($amount, $from, $to) {
    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
    $data = file_get_contents($url);
    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
    //return 'Euros set:' . $amount . ' and result in ' . $to . ': ' .  round($converted, 2);
    return  round($converted, 2);
}

session_start();

$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


//Price in Euros
$price = '5.00';
$price2 = '25.00';

    //Get price from db
    try {

        $sql2 = "SELECT * from EuroPrices";        
        $stmt2 = $dbh->prepare($sql2);       
        $result2 = $stmt2->execute();   
        
        $row2;
        while($row2 = $stmt2->fetch()) {
        
           switch ($row2['licenseDays']) {

            case "30":
                $price = $row2['euroPrice'];
                $price = round($price, 2);
                break;

            case "180":
                $price2 = $row2['euroPrice'];
                $price2 = round($price2, 2);
                break;

            // default:
            //     $price = $row2['europrice']
            }
        }
    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }



// echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";
// echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en'>";
// echo "<head>";	
// 	echo "<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'/>";
// 	echo "<meta http-equiv='Content-Type' content='text/html;charset=UTF-8' />";
//     echo "<title>R'YouIN license</title>";    
    
//     echo "<link rel='stylesheet' type='text/css' href='css/style_license.css' media='all' />";
//     echo "<link href='css/media-queries_license.css' rel='stylesheet' type='text/css'>";
    
// echo "</head>";

// echo "<body>";

//echo "<p>The team from session: " .  $_SESSION['myteamname'] . "</p>";


//License info/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    try {
        $sql3 = "SELECT * from registration where team_teamid = :teamid";        
        $stmt3 = $dbh->prepare($sql3);
        $stmt3->bindParam(':teamid', $_SESSION['myteamid'], PDO::PARAM_INT);
        $result3 = $stmt3->execute();           
        $row3 = $stmt3->fetch();

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }

    $licenseValidDate = new DateTime($row3['licenseValid']);
    $licenseValidDate = $licenseValidDate->format('Y-m-d');

    echo "<fieldset id='license_info'>";
        echo "<legend style='text-align: left;'><h2>License info</h2></legend>";
        echo "<div style='background-color: #b9b9b9; margin: 5px; padding-top: 5px; padding-bottom: 15px;' >";
            echo "<h3 id='team_license' style='text-align: center;'>License valid to:</h3>";
            echo "<h4 id='team_license_value' style='text-align: center; margin-top: 0px;'>" . $licenseValidDate . "</h4>";
        echo "</div>";
    echo "</fieldset>";        


//Subscription buttons////////////////////////////////////////////////////////////////////////////////////////////////////////
echo "<div id='parent-container1' class='parent-container'>";

// //Single pay buttons////////////////////////////////////////////////////////////////////////////////////////////////////////
// echo "<div id='parent-container1' class='parent-container'>";
    
    echo "<p class='license_p'>Purchase one month - 30 day license</p>";

    echo "<div id='button4' class='child-container'>";

         echo "<p>€ " . $price . " EUR</p>";

         echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
            echo "<input type='hidden' name='cmd' value='_xclick'>";
            echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
            echo "<input type='hidden' name='lc' value='FI'>";
            echo "<input type='hidden' name='item_name' value='RYouIN single month subscription'>";
            echo "<input type='hidden' name='amount' value=" . $price . ">";
            echo "<input type='hidden' name='currency_code' value='EUR'>";
            echo "<input type='hidden' name='button_subtype' value='services'>";
            echo "<input type='hidden' name='no_note' value='0'>";
            echo "<input type='hidden' name='cn' value='Add special instructions to the seller:'>";
            echo "<input type='hidden' name='no_shipping' value='2'>";            
            echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";
            echo "<input type='hidden' name='tax_rate' value='24.000'>";
            echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
            echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
        
            //Custonm field, send: "teamid | playerid | licenseDays"
            echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . " | 30'>";

            echo "<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
            echo "<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
         echo "</form>";

    echo "</div>";

    echo "<div id='button5' class='child-container'>";

         echo"<p>$ " . convertCurrency($price, 'EUR', 'USD') . " USD</p>";

         echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
            echo "<input type='hidden' name='cmd' value='_xclick'>";
            echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
            echo "<input type='hidden' name='lc' value='FI'>";
            echo "<input type='hidden' name='item_name' value='RYouIN single month subscription'>";
            echo "<input type='hidden' name='amount' value=" . convertCurrency($price, 'EUR', 'USD') . ">";
            echo "<input type='hidden' name='currency_code' value='USD'>";
            echo "<input type='hidden' name='button_subtype' value='services'>";
            echo "<input type='hidden' name='no_note' value='0'>";
            echo "<input type='hidden' name='cn' value='Add special instructions to the seller:'>";
            echo "<input type='hidden' name='no_shipping' value='2'>";            
            echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";
            echo "<input type='hidden' name='tax_rate' value='24.000'>";
            echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
            echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";            
        
            //Custonm field, send: "teamid | playerid | licenseDays"
            echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . " | 30'>";

            echo "<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
            echo "<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
         echo "</form>";

    echo "</div>";

    echo "<div id='button6' class='child-container'>";

         echo"<p>£ " . convertCurrency($price, 'EUR', 'GBP') . " GBP</p>";

         echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
            echo "<input type='hidden' name='cmd' value='_xclick'>";
            echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
            echo "<input type='hidden' name='lc' value='FI'>";
            echo "<input type='hidden' name='item_name' value='RYouIN single month subscription'>";
            echo "<input type='hidden' name='amount' value=" . convertCurrency($price, 'EUR', 'GBP') . ">";
            echo "<input type='hidden' name='currency_code' value='GBP'>";
            echo "<input type='hidden' name='button_subtype' value='services'>";
            echo "<input type='hidden' name='no_note' value='0'>";
            echo "<input type='hidden' name='cn' value='Add special instructions to the seller:'>";
            echo "<input type='hidden' name='no_shipping' value='2'>";            
            echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";
            echo "<input type='hidden' name='tax_rate' value='24.000'>";
            echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
            echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
                    
            //Custonm field, send: "teamid | playerid | licenseDays"
            echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . " | 30'>";

            echo "<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
            echo "<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
         echo "</form>";

    echo "</div>";

    echo "<p class='license_p'>Purchase half year - 180 day license</p>";

    echo "<div id='button4' class='child-container'>";

         echo "<p>€ " . $price2 . " EUR</p>";

         echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
            echo "<input type='hidden' name='cmd' value='_xclick'>";
            echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
            echo "<input type='hidden' name='lc' value='FI'>";
            echo "<input type='hidden' name='item_name' value='RYouIN half year subscription'>";
            echo "<input type='hidden' name='amount' value='" . $price2 . "'>";
            echo "<input type='hidden' name='currency_code' value='EUR'>";
            echo "<input type='hidden' name='button_subtype' value='services'>";
            echo "<input type='hidden' name='no_note' value='0'>";
            echo "<input type='hidden' name='cn' value='Add special instructions to the seller:'>";
            echo "<input type='hidden' name='no_shipping' value='2'>";            
            echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";
            echo "<input type='hidden' name='tax_rate' value='24.000'>";
            echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
            echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
        
            //Custonm field, send: "teamid | playerid | licenseDays"
            echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . " | 180'>";

            echo "<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
            echo "<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
         echo "</form>";

    echo "</div>";

    echo "<div id='button5' class='child-container'>";

         echo"<p>$ " . convertCurrency($price2, 'EUR', 'USD') . " USD</p>";

         echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
            echo "<input type='hidden' name='cmd' value='_xclick'>";
            echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
            echo "<input type='hidden' name='lc' value='FI'>";
            echo "<input type='hidden' name='item_name' value='RYouIN half year subscription'>";
            echo "<input type='hidden' name='amount' value='" . convertCurrency($price2, 'EUR', 'USD') . "'>";
            echo "<input type='hidden' name='currency_code' value='USD'>";
            echo "<input type='hidden' name='button_subtype' value='services'>";
            echo "<input type='hidden' name='no_note' value='0'>";
            echo "<input type='hidden' name='cn' value='Add special instructions to the seller:'>";
            echo "<input type='hidden' name='no_shipping' value='2'>";            
            echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";
            echo "<input type='hidden' name='tax_rate' value='24.000'>";
            echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
            echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";            
        
            //Custonm field, send: "teamid | playerid | licenseDays"
            echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . " | 180'>";

            echo "<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
            echo "<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
         echo "</form>";

    echo "</div>";

    echo "<div id='button6' class='child-container'>";

         echo"<p>£ " . convertCurrency($price2, 'EUR', 'GBP') . " GBP</p>";

         echo "<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
            echo "<input type='hidden' name='cmd' value='_xclick'>";
            echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
            echo "<input type='hidden' name='lc' value='FI'>";
            echo "<input type='hidden' name='item_name' value='RYouIN half year subscription'>";
            echo "<input type='hidden' name='amount' value='" . convertCurrency($price2, 'EUR', 'GBP') . "'>";
            echo "<input type='hidden' name='currency_code' value='GBP'>";
            echo "<input type='hidden' name='button_subtype' value='services'>";
            echo "<input type='hidden' name='no_note' value='0'>";
            echo "<input type='hidden' name='cn' value='Add special instructions to the seller:'>";
            echo "<input type='hidden' name='no_shipping' value='2'>";            
            echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";
            echo "<input type='hidden' name='tax_rate' value='24.000'>";
            echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
            echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
                    
            //Custonm field, send: "teamid | playerid | licenseDays"
            echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . " | 180'>";

            echo "<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
            echo "<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
         echo "</form>";

    echo "</div>";

//     echo "<p class='license_p'>Purchase license</p>";
// // <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top">
// // <input type="hidden" name="cmd" value="_xclick">
// // <input type="hidden" name="business" value="8RLTCNLYYQKLQ">
// // <input type="hidden" name="lc" value="FI">
// // <input type="hidden" name="item_name" value="RYouIN license">
// // <input type="hidden" name="button_subtype" value="services">
// // <input type="hidden" name="no_note" value="1">
// // <input type="hidden" name="no_shipping" value="1">
// // <input type="hidden" name="rm" value="1">
// // <input type="hidden" name="return" value="https://www.mystore.com/success">
// // <input type="hidden" name="cancel_return" value="https://www.mystore.com/cancel">
// // <input type="hidden" name="currency_code" value="EUR">
// // <input type="hidden" name="tax_rate" value="24.000">
// // <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
// // <table>
// // <tr><td><input type="hidden" name="on0" value="License options">License options</td></tr><tr><td><select name="os0">
// // 	<option value="30 days">30 days €7.00 EUR</option>
// // 	<option value="90 days">90 days €18.00 EUR</option>
// // </select> </td></tr>
// // </table>
// // <input type="hidden" name="currency_code" value="EUR">
// // <input type="hidden" name="option_select0" value="30 days">
// // <input type="hidden" name="option_amount0" value="7.00">
// // <input type="hidden" name="option_select1" value="90 days">
// // <input type="hidden" name="option_amount1" value="18.00">
// // <input type="hidden" name="option_index" value="0">
// // <input type="image" src="https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
// // <img alt="" border="0" src="https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
// // </form>


//     //EUR/////////////////////////////////////////////////////
//     echo "<div id='button11' class='child-container'>";
//         //echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
//         echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
//             echo "<input type='hidden' name='cmd' value='_xclick'>";
//             echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
//             //echo "<input type='hidden' name='lc' value='FI'>";
//             echo "<input type='hidden' name='item_name' value='License options EUR'>";
//             //echo "<input type='hidden' name='button_subtype' value='services'>";
//             // echo "<input type='hidden' name='no_note' value='1'>";
//             // echo "<input type='hidden' name='no_shipping' value='1'>";
//             // echo "<input type='hidden' name='rm' value='1'>";
//             // echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
//             // echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
//             echo "<input type='hidden' name='currency_code' value='EUR'>";
//             echo "<input type='hidden' name='tax_rate' value='24.000'>";
//             echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";
//             echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . "'>";            
//             echo "<table>";
//             echo "<tr><td><input type='hidden' name='on0' value='License options'>License options</td></tr><tr><td><select name='os0'>";
//                 echo "<option value='30 days'>30 days " . $price . " EUR</option>";
//                 echo "<option value='90 days'>90 days " . $price2 . " EUR</option>";
//             echo "</select> </td></tr>";
//             echo "</table>";
//             //echo "<input type='hidden' name='currency_code' value='EUR'>";
//             echo "<input type='hidden' name='option_select0' value='30 days'>";
//             echo "<input type='hidden' name='option_amount0' value='" . $price . "'>";
//             echo "<input type='hidden' name='option_select1' value='90 days'>";
//             echo "<input type='hidden' name='option_amount1' value='" . $price2 . "'>";
//             echo "<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
//             echo "<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
//             //echo "<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
//             //echo "<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
//         echo "</form>";
//     echo "</div>";

//     //USD/////////////////////////////////////////////////////
//     echo "<div id='button12' class='child-container'>";
//         //echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
//         echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";

//             echo "<input type='hidden' name='cmd' value='_xclick'>";
//             echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
//             echo "<input type='hidden' name='lc' value='FI'>";
//             echo "<input type='hidden' name='item_name' value='License options'>";
//             echo "<input type='hidden' name='button_subtype' value='services'>";
//             echo "<input type='hidden' name='no_note' value='1'>";
//             echo "<input type='hidden' name='no_shipping' value='1'>";
//             echo "<input type='hidden' name='rm' value='1'>";
//             echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
//             echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
//             echo "<input type='hidden' name='currency_code' value='USD'>";
//             echo "<input type='hidden' name='tax_rate' value='24.000'>";
//             echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";            
//             echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . "'>";
//             echo "<table>";
//             echo "<tr><td><input type='hidden' name='on0' value='License options'>License options</td></tr><tr><td><select name='os0'>";
//                 echo "<option value='30 days'>30 days " . convertCurrency($price, 'EUR', 'USD') . " USD</option>";
//                 echo "<option value='90 days'>90 days " . convertCurrency($price2, 'EUR', 'USD') . " USD</option>";
//             echo "</select> </td></tr>";
//             echo "</table>";
//             echo "<input type='hidden' name='currency_code' value='USD'>";
//             echo "<input type='hidden' name='option_select0' value='30 days'>";
//             echo "<input type='hidden' name='option_amount0' value='" . convertCurrency($price, 'EUR', 'USD') . "'>";
//             echo "<input type='hidden' name='option_select1' value='90 days'>";
//             echo "<input type='hidden' name='option_amount1' value='" . convertCurrency($price2, 'EUR', 'USD') . "'>";            
//             echo "<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
//             echo "<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
//         echo "</form>";
//     echo "</div>";

//     //GBP/////////////////////////////////////////////////////
//     echo "<div id='button13' class='child-container'>";
//         //echo "<form action='https://www.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
//         echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";

//             echo "<input type='hidden' name='cmd' value='_xclick'>";
//             echo "<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
//             echo "<input type='hidden' name='lc' value='FI'>";
//             echo "<input type='hidden' name='item_name' value='License options'>";
//             echo "<input type='hidden' name='button_subtype' value='services'>";
//             echo "<input type='hidden' name='no_note' value='1'>";
//             echo "<input type='hidden' name='no_shipping' value='1'>";
//             echo "<input type='hidden' name='rm' value='1'>";
//             echo "<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
//             echo "<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
//             echo "<input type='hidden' name='currency_code' value='GBP'>";
//             echo "<input type='hidden' name='tax_rate' value='24.000'>";
//             echo "<input type='hidden' name='bn' value='PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted'>";
//             echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . "'>";
//             echo "<table>";
//             echo "<tr><td><input type='hidden' name='on0' value='License options'>License options</td></tr><tr><td><select name='os0'>";
//                 echo "<option value='30 days'>30 days  " . convertCurrency($price, 'EUR', 'GBP') . " GBP</option>";
//                 echo "<option value='90 days'>90 days  " . convertCurrency($price2, 'EUR', 'GBP') . " GBP</option>";
//             echo "</select> </td></tr>";
//             echo "</table>";
//             echo "<input type='hidden' name='currency_code' value='GBP'>";
//             echo "<input type='hidden' name='option_select0' value='30 days'>";
//             echo "<input type='hidden' name='option_amount0' value='" . convertCurrency($price, 'EUR', 'GBP') . "'>";
//             echo "<input type='hidden' name='option_select1' value='90 days'>";
//             echo "<input type='hidden' name='option_amount1' value='" . convertCurrency($price2, 'EUR', 'GBP') . "'>";
//             echo "<input type='image' src='https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
//             echo "<img alt='' border='0' src='https://www.paypalobjects.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
//         echo "</form>";
//     echo "</div>";


    //Subscribe buttons
    // echo "<div id='button1' class='child-container'>";

    //     echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
    //     echo"<input type='hidden' name='cmd' value='_xclick-subscriptions'>";
    //     echo"<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
    //     echo"<input type='hidden' name='lc' value='FI'>";
    //     echo"<input type='hidden' name='item_name' value='RYouIN subscription'>";
    //     echo"<input type='hidden' name='no_note' value='1'>";
    //     echo"<input type='hidden' name='no_shipping' value='1'>";
    //     echo"<input type='hidden' name='rm' value='1'>";
    //     echo"<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
    //     echo"<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
    //     echo"<input type='hidden' name='src' value='1'>";
    //     echo"<input type='hidden' name='currency_code' value='EUR'>";
    //     echo"<input type='hidden' name='bn' value='PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest'>";
    //     //Custonm field, send: "teamid | playerid"
    //     echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . "'>";
    //     echo "<input type='hidden' name='tax_rate' value='24.000'>";
        
    //     echo"<p>€ " . $price . " EUR - monthly </p>";

    //     echo"<table style='display:none;>";
    //     echo"<tr style='display:none;'><td><input type='hidden' name='on0' value=''></td></tr><tr><td><select name='os0'>";
    //     echo"	<option value='Option 1' style='display:none;>Option 1 : € " . $price . " EUR - monthly</option>";
    //     echo"</select style='display:none;> </td></tr>";
    //     echo"</table style='display:none;>";

    //     echo"<input type='hidden' name='currency_code' value='EUR'>";
    //     echo"<input type='hidden' name='option_select0' value='Option 1'>";
    //     echo"<input type='hidden' name='option_amount0' value='" . $price . "'>";
    //     echo"<input type='hidden' name='option_period0' value='M'>";
    //     echo"<input type='hidden' name='option_frequency0' value='1'>";
    //     echo"<input type='hidden' name='option_index' value='0'>";
    //     echo"<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
    //     echo"<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
    //     echo"</form>";
    
    //  echo "</div>";
    
//     echo "<div id='button2' class='child-container'>";

//         echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
//         echo"<input type='hidden' name='cmd' value='_xclick-subscriptions'>";
//         echo"<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
//         echo"<input type='hidden' name='lc' value='FI'>";
//         echo"<input type='hidden' name='item_name' value='RYouIN subscription'>";
//         echo"<input type='hidden' name='no_note' value='1'>";
//         echo"<input type='hidden' name='no_shipping' value='1'>";
//         echo"<input type='hidden' name='rm' value='1'>";
//         echo"<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
//         echo"<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
//         echo"<input type='hidden' name='src' value='1'>";
//         echo"<input type='hidden' name='currency_code' value='USD'>";
//         echo"<input type='hidden' name='bn' value='PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest'>";
//         //Custonm field, send: "teamid | playerid"
//         echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . "'>";
//         echo "<input type='hidden' name='tax_rate' value='24.000'>";
        
//         echo"<p>$ " . convertCurrency($price, 'EUR', 'USD') . " USD - monthly </p>";

//         echo"<table style='display:none;";
//         echo"<tr style='display:none;><td><input type='hidden' name='on0' value=''></td></tr><tr><td><select name='os0'>";
//         echo"	<option value='Option 1' style='display:none;>Option 1 : " . convertCurrency($price, 'EUR', 'USD') . " USD - monthly</option>";
//         echo"</select style='display:none;> </td></tr>";
//         echo"</table style='display:none;>";
        
//         echo"<input type='hidden' name='currency_code' value='USD'>";
//         echo"<input type='hidden' name='option_select0' value='Option 1'>";
//         echo"<input type='hidden' name='option_amount0' value='" . convertCurrency($price, 'EUR', 'USD') . "'>";
//         echo"<input type='hidden' name='option_period0' value='M'>";
//         echo"<input type='hidden' name='option_frequency0' value='1'>";
//         echo"<input type='hidden' name='option_index' value='0'>";
//         echo"<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
//         echo"<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
//         echo"</form>";
    
//      echo "</div>";    
    
//         echo "<div id='button3' class='child-container'>";

//         echo"<form action='https://www.sandbox.paypal.com/cgi-bin/webscr' method='post' target='_top'>";
//         echo"<input type='hidden' name='cmd' value='_xclick-subscriptions'>";
//         echo"<input type='hidden' name='business' value='8RLTCNLYYQKLQ'>";
//         echo"<input type='hidden' name='lc' value='FI'>";
//         echo"<input type='hidden' name='item_name' value='RYouIN subscription'>";
//         echo"<input type='hidden' name='no_note' value='1'>";
//         echo"<input type='hidden' name='no_shipping' value='1'>";
//         echo"<input type='hidden' name='rm' value='1'>";
//         echo"<input type='hidden' name='return' value='https://dev-areyouin.azurewebsites.net/payment/success.html'>";
//         echo"<input type='hidden' name='cancel_return' value='https://dev-areyouin.azurewebsites.net/payment/cancel.html'>";
//         echo"<input type='hidden' name='src' value='1'>";
//         echo"<input type='hidden' name='currency_code' value='GBP'>";
//         echo"<input type='hidden' name='bn' value='PP-SubscriptionsBF:btn_subscribeCC_LG.gif:NonHostedGuest'>";
//         //Custonm field, send: "teamid | playerid"
//         echo "<input type='hidden' name='custom' value='" . $_SESSION['myteamid'] . " | " . $_SESSION['myplayerid'] . "'>";
//         echo "<input type='hidden' name='tax_rate' value='24.000'>";
        
//         echo"<p>£ " . convertCurrency($price, 'EUR', 'GBP') . " GBP - monthly </p>";

//         echo"<table style='display:none;>";
//         echo"<tr style='display:none;><td><input type='hidden' name='on0' value=''></td></tr><tr><td><select name='os0'>";
//         echo"	<option value='Option 1' style='display:none;>Option 1 : " . convertCurrency($price, 'EUR', 'GBP') . " GBP - monthly</option>";
//         echo"</select style='display:none;> </td></tr>";
//         echo"</table style='display:none;>";
        
//         echo"<input type='hidden' name='currency_code' value='GBP'>";
//         echo"<input type='hidden' name='option_select0' value='Option 1'>";
//         echo"<input type='hidden' name='option_amount0' value='" . convertCurrency($price, 'EUR', 'GBP') . "'>";
//         echo"<input type='hidden' name='option_period0' value='M'>";
//         echo"<input type='hidden' name='option_frequency0' value='1'>";
//         echo"<input type='hidden' name='option_index' value='0'>";
//         echo"<input type='image' src='https://www.sandbox.paypal.com/en_US/i/btn/btn_subscribeCC_LG.gif' border='0' name='submit' alt='PayPal - The safer, easier way to pay online!'>";
//         echo"<img alt='' border='0' src='https://www.sandbox.paypal.com/en_US/i/scr/pixel.gif' width='1' height='1'>";
//         echo"</form>";
    
//      echo "</div>";  
    
// echo "</div>";

echo "</div>";

$dbh = null;

// echo "</body>";
// echo "</html>";

?>
