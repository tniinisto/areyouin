<?php

$amount=$_GET["amount"];
$from=$_GET["from"];
$to=$_GET["to"];

function convertCurrency($amount, $from, $to) {
    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
    $data = file_get_contents($url);
    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
    //return 'Euros set:' . $amount . ' and result in ' . $to . ': ' .  round($converted, 2);
    return  round($converted, 2) . ',' .  $to;
}

//Usage
echo convertCurrency(7, "EUR", "USD");
echo "<br>";
echo convertCurrency(7, "EUR", "GBP");



?>


