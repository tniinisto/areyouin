<?php

echo "<html lang='en()'>";
echo "<head>";
echo "<meta charset='utf-8'>";

echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";

echo "<title>R'YouIN</title>";

echo "<link href='style.css' rel='stylesheet' type='text/css'>";
echo "<link href='media-queries.css' rel='stylesheet' type='text/css'>";

echo "<script type='text/javascript' src='main.js'> </script>";
echo "<script src='js/jquery-2.0.0.min.js'></script>";
echo "<script type='text/javascript' src='js/spin.min.js'></script>";            

echo "</head>";

echo "<body>";

echo "<article id='license_expried_article' class='clearfix'>";

    echo "<div id='license_content_id' class=''>";
        
        echo "<h1 style='text-align: center;'>License expired</h1>";

        ob_start(); // begin collecting output
        include 'payment/onlineCurrency.php';
        $result = ob_get_clean(); // retrieve output from myfile.php, stop buffering
        echo $result;

    echo "</div>";

echo "</article>";    



echo "</body>";
echo "</html>";

?>