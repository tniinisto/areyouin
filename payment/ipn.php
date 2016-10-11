<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    echo "IPN - Instant Payment Notification service";

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con)or die("cannot select DB");

	$sql="SELECT p.playerID, p.name, t.teamID, t.teamName, t.timezone, t.utcOffset, m.teamAdmin, m.registrar, m.lastMsg
    FROM areyouin.players p, playerteam m, team t
    WHERE (name = '$myusername' OR mail = '$myusername') and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid and t.teamid <> 0
    ORDER BY t.teamID";

	$result=mysql_query($sql);

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

    const VERIFY_URI = 'https://ipnpb.paypal.com/cgi-bin/webscr';
    const SANDBOX_VERIFY_URI = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

    //$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
    $fp = fsockopen (SANDBOX_VERIFY_URI, 443, $errno, $errstr, 30);

    if (!$fp) {

        // HTTP ERROR

    } else {

        fputs ($fp, $header . $req);
        while (!feof($fp)) {
            $res = fgets ($fp, 1024);
            if (strcmp ($res, "VERIFIED") == 0) {
            // PAYMENT VALIDATED & VERIFIED!
                alert('IPN verified');
            }
            else if (strcmp ($res, "INVALID") == 0) {
            
            // PAYMENT INVALID & INVESTIGATE MANUALY!
                alert('IPN invalid');
        }
    }

    fclose ($fp);
    }

    mysql_close($con);

?>