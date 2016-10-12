<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    //session_start();

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }
	mysql_select_db("areyouin", $con)or die("cannot select DB");

    //Test db insert, works!
    // $date = date('Y-m-d H:i:s');
    // $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 7.77)";
    // $result = mysql_query($sql);

    // read the post from PayPal system and add 'cmd_notify‐validate' to the end
    $req = 'cmd=_notify‐validate';
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
    }

    // post back to PayPal system to validate
    $header = "POST /cgi‐bin/webscr HTTP/1.0\r\n";
    $header .= "Content‐Type: application/x‐www‐form‐urlencoded\r\n";
    $header .= "Content‐Length: " . strlen($req) . "\r\n\r\n";

    //Production
    //$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

    //Sandbox
    $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

    if (!$fp) {

         // HTTP ERROR      

    } else {

        fputs ($fp, $header . $req);

        while (!feof($fp)) {
            
            //$res = fgets ($fp, 1024);
            $res = stream_get_contents($fp, 1024);

            //Test, this comes through
            // $date = date('Y-m-d H:i:s');
            // $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 1.11)";
            // $result = mysql_query($sql);

            if (strcmp ($res, "VERIFIED") == 0) {    
                // PAYMENT VALIDATED & VERIFIED!

                $date = date('Y-m-d H:i:s');
                $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 7.77)";
                $result = mysql_query($sql);
            }
        
            if (strcmp ($res, "INVALID") == 0) {
                // PAYMENT INVALID & INVESTIGATE MANUALY!

                $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 9.99)";
                $result = mysql_query($sql);
            }
        }
    
    fclose ($fp);
    }
    
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
    // //$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
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
    //     $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 7.77)";
    //     $result = mysql_query($sql);
    // }
    // if ( ! ($res)) {
    //     $errno = curl_errno($ch);
    //     $errstr = curl_error($ch);
    //     curl_close($ch);
    //     throw new Exception("cURL error: [$errno] $errstr");
    //     $date = date('Y-m-d H:i:s');
    //     $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 9.99)";
    //     $result = mysql_query($sql);        
    // }
    // curl_close($ch);

    mysql_close($con);

?>

