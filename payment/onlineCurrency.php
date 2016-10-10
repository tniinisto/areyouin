<?php

function convertCurrency($amount, $from, $to){
    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
    $data = file_get_contents($url);
    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
    return 'Euros set:' . $amount . ' and result in ' . $to . ': ' .  round($converted, 2);
}

//Usage
echo convertCurrency(7, "EUR", "USD");
//EUR, USD, GBP

?>


