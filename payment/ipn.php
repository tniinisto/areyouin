<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con)or die("cannot select DB");




    // read the post from PayPal system and add 'cmd'
    $req = 'cmd=_notify‐validate';
    foreach ($_POST as $key => $value) {
    $value = urlencode(stripslashes($value));
    $req .= "&$key=$value";
    }

    // post back to PayPal system to validate
    $header = "POST /cgi‐bin/webscr HTTP/1.0\r\n";
    $header .= "Content‐Type: application/x‐www‐form‐urlencoded\r\n";
    $header .= "Content‐Length: " . strlen($req) . "\r\n\r\n";

    //$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
    $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

    if (!$fp) {

        // HTTP ERROR

    } else {

        fputs ($fp, $header . $req);
        while (!feof($fp)) {
            
            $res = fgets ($fp, 1024);

            if (strcmp ($res, "VERIFIED") == 0) {
                
                // PAYMENT VALIDATED & VERIFIED!
                $date = date('Y-m-d H:i:s');
                
                //$sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 7.77)";
                $sql = "INSERT INTO payments (team_TeamID) VALUES (9)";
                $result = mysql_query($sql);
            }
            else if (strcmp ($res, "INVALID") == 0) {
                // PAYMENT INVALID & INVESTIGATE MANUALY!

                $sql = "INSERT INTO payments (team_TeamID) VALUES (99)";
                $result = mysql_query($sql);
            }
        }
    
    fclose ($fp);
    }
    
    mysql_close($con);

?>
