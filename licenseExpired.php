<?php

echo "<html lang='en()'>";
echo "<head>";
echo "<meta charset='utf-8'>";

//echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0', user-scalable=0 />";

echo "<title>R'YouIN</title>";

echo "<link href='style.css' rel='stylesheet' type='text/css'>";
echo "<link href='media-queries.css' rel='stylesheet' type='text/css'>";

echo "<script type='text/javascript' src='main.js'> </script>";
echo "<script src='js/jquery-2.0.0.min.js'></script>";
echo "<script type='text/javascript' src='js/spin.min.js'></script>";            

echo "</head>";

echo "<body>";

echo "<article id='license_expried_article' class='clearfix license_exp'>";

    echo "<div id='license_content_id' class=''>";
        
        echo "<h1 style='text-align: center;'>License expired</h1>";

        echo "<p style='text-align: center; font-size: initial; font-weight: bold; margin-bottom: 40px;'>Your R'YouIN usage license has expired. You can purhcase a new license and continue usage immediately after the payment.</p>";
        
        echo "<div id='wrapper_login' style='text-align: center'>";  
            echo "<div style='display: inline-block; text-align: center; text-decoration: underline;'>";
                echo "<a style='color: #0167cd; font-size: large; font-weight: bold;' href='default.php'>Back to login</a>";
            echo "</div>";
        echo "</div>";

        ob_start(); // begin collecting output
        include 'payment/onlineCurrency.php';
        $result = ob_get_clean(); // retrieve output from myfile.php, stop buffering
        echo $result;

    echo "</div>";

echo "</article>";    



echo "</body>";
echo "</html>";

?>