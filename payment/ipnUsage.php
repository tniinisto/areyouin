<?php require("ipn.php");

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
     echo "verified test";
}
// Reply with an empty 200 response to indicate to paypal the IPN was received correctly.
//echo "not verified test";

header("HTTP/1.1 200 OK");


?>