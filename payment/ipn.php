<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    //session_start();

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("areyouin", $con)or die("cannot select DB");




    // STEP 1: read POST data
    // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
    // Instead, read raw POST data from the input stream.
    $raw_post_data = file_get_contents('php://input');
    $raw_post_array = explode('&', $raw_post_data);
    $myPost = array();
    foreach ($raw_post_array as $keyval) {
    $keyval = explode ('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = urldecode($keyval[1]);
    }
    // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
    $req = 'cmd=_notify-validate';
    if (function_exists('get_magic_quotes_gpc')) {
        $get_magic_quotes_exists = true;
    }
    foreach ($myPost as $key => $value) {
        if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
            $value = urlencode(stripslashes($value));
        } else {
            $value = urlencode($value);
        }
        $req .= "&$key=$value";
    }

    // Step 2: POST IPN data back to PayPal to validate
    //$ch = curl_init('https://www.paypal.com/cgi-bin/webscr'); //Production
    $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'); //Sandbox    
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
    // In wamp-like environments that do not come bundled with root authority certificates,
    // please download 'cacert.pem' from "http://curl.haxx.se/docs/caextract.html" and set
    // the directory path of the certificate as shown below:
    // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
    curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . "/cert/cacert.pem");

    if ( !($res = curl_exec($ch)) ) {
        // error_log("Got " . curl_error($ch) . " when processing IPN data");
        curl_close($ch);
        exit;
    }
    curl_close($ch);

    // inspect IPN validation result and act accordingly
    if (strcmp ($res, "VERIFIED") == 0) {
        // The IPN is verified, process it:
        // check whether the payment_status is Completed
        // check that txn_id has not been previously processed
        // check that receiver_email is your Primary PayPal email
        // check that payment_amount/payment_currency are correct
        // process the notification
        // assign posted variables to local variables
        $item_name = $_POST['item_name'];
        $item_number = $_POST['item_number'];
        $payment_status = $_POST['payment_status'];
        $payment_amount = $_POST['mc_gross'];
        $payment_currency = $_POST['mc_currency'];
        $txn_id = $_POST['txn_id'];
        $receiver_email = $_POST['receiver_email'];
        $payer_email = $_POST['payer_email'];

        $date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO payments (team_TeamID, time, payer, amount, debug) VALUES (1, '" . $date . "', 1, 1.11, '" . $res . "')";
        $result = mysql_query($sql);

        // IPN message values depend upon the type of notification sent.
        // To loop through the &_POST array and print the NV pairs to the screen:
        // foreach($_POST as $key => $value) {
        //     echo $key . " = " . $value . "<br>";
        // }

    } else if (strcmp ($res, "INVALID") == 0) {
        // IPN invalid, log for manual investigation
        echo "The response from IPN was: <b>" .$res ."</b>";
    }

    mysql_close($con);   

    header("HTTP/1.1 200 OK");

    // // Read Post variable pairs
    // if ( ! count($_POST)) {
    //     throw new Exception("Missing POST Data");
    // }
    // $raw_post_data = file_get_contents('php://input');
    // $raw_post_array = explode('&', $raw_post_data);
    // $myPost = [];
    // foreach ($raw_post_array as $keyval) {
    //     $keyval = explode('=', $keyval);
    //     if (count($keyval) == 2) {
    //         // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
    //         if ($keyval[0] === 'payment_date') {
    //             if (substr_count($keyval[1], '+') === 1) {
    //                 $keyval[1] = str_replace('+', '%2B', $keyval[1]);
    //             }
    //         }
    //         $myPost[$keyval[0]] = urldecode($keyval[1]);
    //     }
    // }

    // // Build the body of the verification post request, adding the _notify-validate command.
    // $req = 'cmd=_notify-validate';
    // $get_magic_quotes_exists = false;
    // if (function_exists('get_magic_quotes_gpc')) {
    //     $get_magic_quotes_exists = true;
    // }
    // foreach ($myPost as $key => $value) {
    //     if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
    //         $value = urlencode(stripslashes($value));
    //     } else {
    //         $value = urlencode($value);
    //     }
    //     $req .= "&$key=$value";
    // }

    // // Post the data back to paypal, using curl. Throw exceptions if errors occur.
    // //$ch = curl_init('https://www.paypal.com/cgi-bin/webscr'); //Production
    // $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'); //Sandbox
    
    // curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
    // curl_setopt($ch, CURLOPT_SSLVERSION, 6);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    // // This is often required if the server is missing a global cert bundle, or is using an outdated one.
    // curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . "/cert/cacert.pem");
    // curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);
    // $res = curl_exec($ch);
    // $info = curl_getinfo($ch);
    // $http_code = $info['http_code'];

    // if ($http_code != 200) {
    //     throw new Exception("PayPal responded with http code $http_code");
    //     $date = date('Y-m-d H:i:s');
    //     $sql = "INSERT INTO payments (team_TeamID, time, payer, amount, debug) VALUES (1, '" . $date . "', 1, 1.11, '" . $res . "')";
    //     $result = mysql_query($sql);
    // }
    // if ( ! ($res)) {
    //     $errno = curl_errno($ch);
    //     $errstr = curl_error($ch);
    //     curl_close($ch);
    //     throw new Exception("cURL error: [$errno] $errstr");
    //     $date = date('Y-m-d H:i:s');
    //     $sql = "INSERT INTO payments (team_TeamID, time, payer, amount, debug) VALUES (1, '" . $date . "', 1, 9.11, '" . $res . "')";
    //     $result = mysql_query($sql);        
    // }
    // curl_close($ch);

    // mysql_close($con);    

    // //Test db insert, works!
    // // $date = date('Y-m-d H:i:s');
    // // $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 7.77)";
    // // $result = mysql_query($sql);

    // // read the post from PayPal system and add 'cmd_notify‐validate' to the end
    // $req = 'cmd=_notify‐validate';
    // foreach ($_POST as $key => $value) {
    //     $value = urlencode(stripslashes($value));
    //     $req .= "&$key=$value";
    // }

    // // $req = 'cmd=_notify-validate';
    // // $get_magic_quotes_exists = false;
    // // if(function_exists('get_magic_quotes_gpc'))
    // // {
    // //     $get_magic_quotes_exists = true;
    // // }
    // // // handle escape characters, which depends on setting of magic quotes
    // // foreach ($_POST as $key => $value)
    // // {
    // //     if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1)
    // //     {
    // //         $value = urlencode(stripslashes($value));
    // //     }
    // //     else
    // //     {
    // //         $value = urlencode($value);
    // //     }
    // //     $req .= "&$key=$value";
    // // }


    // // post back to PayPal system to validate
    // $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
    // $header .= "Host: www.sandbox.paypal.com:443\r\n";
    // // $header .= "Host: www.paypal.com:443\r\n";
    // $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
    // $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

    // //Production
    // //$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

    // //Sandbox
    // $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

    // if (!$fp) {

    //      // HTTP ERROR      

    // } else {

    //     fputs ($fp, $header . $req);

    //     while (!feof($fp)) {
            
            
    //         $res = fgets ($fp, 1024);

    //         //Test, this comes through
    //         $date = date('Y-m-d H:i:s');
    //         $sql = "INSERT INTO payments (team_TeamID, time, payer, amount, debug) VALUES (1, '" . $date . "', 1, 1.11, '" . $res . "')";
    //         $result = mysql_query($sql);

    //         if (strcasecmp  (trim($res), "VERIFIED") == 0) {    
    //             // PAYMENT VALIDATED & VERIFIED!

    //             $date = date('Y-m-d H:i:s');
    //             $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 7.77)";
    //             $result = mysql_query($sql);
    //         }
        
    //         else if (strcasecmp  (trim($res), "INVALID") == 0) {
    //             // PAYMENT INVALID & INVESTIGATE MANUALY!

    //             $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 9.99)";
    //             $result = mysql_query($sql);
    //         }
    //     }
    
    // fclose ($fp);
    // }
    
 

?>

