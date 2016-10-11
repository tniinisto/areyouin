<?php

class PaypalIPN
{
    private $use_sandbox = false;
    private $use_local_certs = true;
    /*
     * PayPal IPN postback endpoints
     */
    const VERIFY_URI = 'https://ipnpb.paypal.com/cgi-bin/webscr';
    const SANDBOX_VERIFY_URI = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
    /*
     * Possible responses from PayPal after the request is issued.
     */
    const VALID = 'VERIFIED';
    const INVALID = 'INVALID';
    /**
     * Sets the IPN verification to sandbox mode (for use when testing,
     * should not be enabled in production).
     * @return void
     */
    public function useSandbox()
    {
        $this->use_sandbox = true;
    }
    /**
     * Determine endpoint to post the verification data to.
     * @return string
     */
    public function getPaypalUri()
    {
        if ($this->use_sandbox) {
            return self::SANDBOX_VERIFY_URI;
        } else {
            return self::VERIFY_URI;
        }
    }
    /**
     * Verification Function
     * Sends the incoming post data back to paypal using the cURL library.
     *
     * @return bool
     * @throws Exception
     */
    public function verifyIPN()
    {
        if ( ! count($_POST)) {
            throw new Exception("Missing POST Data");
        }
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = [];
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                // Since we do not want the plus in the datetime string to be encoded to a space, we manually encode it.
                if ($keyval[0] === 'payment_date') {
                    if (substr_count($keyval[1], '+') === 1) {
                        $keyval[1] = str_replace('+', '%2B', $keyval[1]);
                    }
                }
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }
        // Build the body of the verification post request, adding the _notify-validate command.
        $req = 'cmd=_notify-validate';
        $get_magic_quotes_exists = false;
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
        // Post the data back to paypal, using curl. Throw exceptions if errors occur.
        $ch = curl_init($this->getPaypalUri());
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSLVERSION, 6);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        // This is often required if the server is missing a global cert bundle, or is using an outdated one.
        if ($this->use_local_certs) {
            curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . "/cert/cacert.pem");
        }
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);
        $res = curl_exec($ch);
        $info = curl_getinfo($ch);
        $http_code = $info['http_code'];
        if ($http_code != 200) {
            throw new Exception("PayPal responded with http code $http_code");
        }
        if ( ! ($res)) {
            $errno = curl_errno($ch);
            $errstr = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL error: [$errno] $errstr");
        }
        curl_close($ch);
        // Check if paypal verfifes the IPN data, and if so, return true.
        // if ($res == self::VALID) {
        //     return true;
        // } else {
        //     return false;
        // }

if ($res == self::VALID) {
  $item_name = $_POST['item_name'];
  $item_number = $_POST['item_number'];
  $payment_status = $_POST['payment_status'];
  $payment_amount = $_POST['mc_gross'];
  $payment_currency = $_POST['mc_currency'];
  $txn_id = $_POST['txn_id'];
  $receiver_email = $_POST['receiver_email'];
  $payer_email = $_POST['payer_email'];
  // IPN message values depend upon the type of notification sent.
  // To loop through the &_POST array and print the NV pairs to the screen:
  foreach($_POST as $key => $value) {
    echo $key . " = " . $value . "<br>";
  }
  
} else {
  // IPN invalid, log for manual investigation
  echo "The response from IPN was: <b>" .$res ."</b>";
}

    }
}





    // include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    // session_start();

    // echo "IPN - Instant Payment Notification service";
    // echo "<br>";

    // $con = mysql_connect($dbhost, $dbuser, $dbpass);
	// if (!$con)
	//   {
	//   die('Could not connect: ' . mysql_error());
	//   }

	// mysql_select_db("areyouin", $con)or die("cannot select DB");

	// $sql="SELECT p.playerID, p.name, t.teamID, t.teamName, t.timezone, t.utcOffset, m.teamAdmin, m.registrar, m.lastMsg
    // FROM areyouin.players p, playerteam m, team t
    // WHERE (name = '$myusername' OR mail = '$myusername') and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid and t.teamid <> 0
    // ORDER BY t.teamID";

	// $result=mysql_query($sql);

    // // read the post from PayPal system and add 'cmd'
    // $req = 'cmd=_notify‐validate';
    // foreach ($_POST as $key => $value) {
    //     $value = urlencode(stripslashes($value));
    //     $req .= "&$key=$value";
    // }

    // // post back to PayPal system to validate
    // $header = "POST /cgi‐bin/webscr HTTP/1.0\r\n";
    // $header .= "Content‐Type: application/x‐www‐form‐urlencoded\r\n";
    // $header .= "Content‐Length: " . strlen($req) . "\r\n\r\n";

    // const VERIFY_URI = 'https://ipnpb.paypal.com/cgi-bin/webscr';
    // const SANDBOX_VERIFY_URI = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';

    // //$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
    // $fp = fsockopen (SANDBOX_VERIFY_URI, 443, $errno, $errstr, 30);

    // if (!$fp) {

    //     // HTTP ERROR

    // } else {

    //     fputs ($fp, $header . $req);
    //     while (!feof($fp)) {
    //         $res = fgets ($fp, 1024);
    //         if (strcmp ($res, "VERIFIED") == 0) {
    //             // PAYMENT VALIDATED & VERIFIED!
    //             echo "Payment validated!";
    //         }
    //         else if (strcmp ($res, "INVALID") == 0) {
    //             // PAYMENT INVALID & INVESTIGATE MANUALY!
    //             echo "Payment invalid!";
    //     }
    // }

    // fclose ($fp);
    // }

    // mysql_close($con);

?>

