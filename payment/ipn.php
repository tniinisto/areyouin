<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    //PDO///////////////////////////////////////////////////////////////////
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    $ch = curl_init('https://www.paypal.com/cgi-bin/webscr'); //Production
    //$ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr'); //Sandbox    
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

    date_default_timezone_set("UTC");

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
        $payment_date = $_POST['payment_date'];
        
        //Custom field data
        $teaminfo = explode("|", $_POST['custom']);
        $myteamid = $teaminfo[0];
        $myuserid = $teaminfo[1];
        $licensedays = $teaminfo[2]; //How many days has been payed
        $licensedays = '+' . $licensedays . ' day'; //eg. +30 day, format that can be used with datetime modify()

        $date = date('Y-m-d H:i:s');
        $date .= " UTC";

        try { //Add payment into & update team registration info to db

            $sql = "INSERT INTO payments (team_TeamID, time, payer, amount, payment_currency, payment_date, debug) 
                    VALUES (:teamid, :date, :myuserid, :payment, :currency, :paymentdate, :result)";
        
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':teamid', $myteamid, PDO::PARAM_INT);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':myuserid', $myuserid, PDO::PARAM_INT);
            $stmt->bindParam(':payment', $payment_amount, PDO::PARAM_STR);
            $stmt->bindParam(':currency', $payment_currency, PDO::PARAM_STR);
            $stmt->bindParam(':paymentdate', $payment_date, PDO::PARAM_STR);
            $stmt->bindParam(':result', $res, PDO::PARAM_STR);

            $result = $stmt->execute();


            //Get current team registration info /////////////////////////////////////
            $sql1 = "select * from registration WHERE Team_teamID = :teamID";
            $stmt1 = $dbh->prepare($sql1);
            $stmt1->bindParam(':teamID', $myteamid, PDO::PARAM_INT);
            $result1 = $stmt1->execute();   
            $row1 = $stmt1->fetch();

            $currentValid = new DateTime($row1['licenseValid']); //Current license valid date
            $currentDate = new DateTime(date("Y-n-j H:i:s")); //Now

            //LOGIC for license
            $licenseValid = ''; //New license valid until date
            $licenseRenewed = date("Y-n-j H:i:s"); //TimeStamp to db, when license is updated

            if($currentDate >= $currentValid) { //If current license has already expired, add days to now()

                $licenseValid = $currentDate->modify($licensedays);
                $licenseValid = $licenseValid->format('Y-m-d H:i:s');

            }
            else { //Current license still valid, add days to that

                $licenseValid = $currentValid->modify($licensedays);
                $licenseValid = $licenseValid->format('Y-m-d H:i:s');

            }

            //Update register table with new license information
            $sql8 = "UPDATE registration SET licenseRenewed = :licenseRenewed, licenseValid = :licenseValid WHERE Team_teamID = :teamID";
            $stmt8 = $dbh->prepare($sql8);
            
            $stmt8->bindParam(':licenseRenewed', $licenseRenewed, PDO::PARAM_STR);
            $stmt8->bindParam(':licenseValid', $licenseValid, PDO::PARAM_STR);
            $stmt8->bindParam(':teamID', $myteamid, PDO::PARAM_INT);

            $result8 = $stmt8->execute();

        }
        catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }               
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    } else if (strcmp ($res, "INVALID") == 0) {
        // IPN invalid, log for manual investigation
        //echo "The response from IPN was: <b>" .$res ."</b>";

        $date = date('Y-m-d H:i:s');
        $date .= " EET";

        //$sql = "INSERT INTO payments (team_TeamID, time, payer, amount, payment_currency, payment_date, debug) VALUES (" . $myteamid . ", '" . $date . "', " . $myuserid . ", '" . $payment_amount . "', '" . $payment_currency . "', '" . $payment_date . "', '" . $res . "')";
        //$result = mysql_query($sql);

        try {
    
            $sql11 = "INSERT INTO payments (team_TeamID, time, payer, amount, payment_currency, payment_date, debug) 
                    VALUES (:teamID, :date, :myuserid, :payment, :currency, :paymentDate, :result)";
        
            $stmt11 = $dbh->prepare($sql11);
            $stmt11->bindParam(':teamID', $myteamid, PDO::PARAM_INT);
            $stmt11->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt11->bindParam(':myuserid', $myuserid, PDO::PARAM_INT);
            $stmt11->bindParam(':payment', $payment_amount, PDO::PARAM_STR);
            $stmt11->bindParam(':currency', $payment_currency, PDO::PARAM_STR);
            $stmt11->bindParam(':paymentDate', $payment_date, PDO::PARAM_STR);
            $stmt11->bindParam(':result', $res, PDO::PARAM_STR);

            $result = $stmt11->execute();

        }
        catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
        }

    } 
       
    $dbh = null;

    header("HTTP/1.1 200 OK");
 
?>

