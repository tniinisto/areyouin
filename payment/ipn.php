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
    $validate = 'cmd=_notify‐validate';
    $req = '';
    foreach ($_POST as $key => $value) {
        $value = urlencode(stripslashes($value));
        $req .= "&$key=$value";
    }
    $req .= $validate;

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
         $date = date('Y-m-d H:i:s');
         $sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 7.77)";
         $result = mysql_query($sql);

    } else {

        fputs ($fp, $header . $req);
        while (!feof($fp)) {
            
            $res = fgets ($fp, 1024);

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
    
    mysql_close($con);


?>

