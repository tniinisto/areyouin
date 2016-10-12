<?php

require ("PaypalIPN.php");
include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

$con = mysql_connect($dbhost, $dbuser, $dbpass);
if (!$con)
    {
    die('Could not connect: ' . mysql_error());
    }

mysql_select_db("areyouin", $con)or die("cannot select DB");

//This test works!
//$sql = "INSERT INTO payments (team_TeamID) VALUES (3)";
//$result = mysql_query($sql);

use PaypalIPN;

$ipn = new PayPalIPN();
// Use the sandbox endpoint during testing.
$ipn->useSandbox();
$verified = $ipn->verifyIPN();

if ($verified) {
    /*
     * Process IPN
     * A list of variables is available here:
     * https://developer.paypal.com/webapps/developer/docs/classic/ipn/integration-guide/IPNandPDTVariables/
     */


    //$date = date('Y-m-d H:i:s');
    //$sql = "INSERT INTO payments (team_TeamID, time, payer, amount) VALUES (1, '" . $date . "', 1, 7.77)";
    $sql = "INSERT INTO payments (team_TeamID) VALUES (1)";
    $result = mysql_query($sql);


} else {

    $sql = "INSERT INTO payments (team_TeamID) VALUES (2)";
    $result = mysql_query($sql);

}

mysql_close($con);

// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.

header("HTTP/1.1 200 OK");
