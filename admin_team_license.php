<?php

                /////////////////////////////////////////////////////////////////////////////////////////////
                //License page///////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////////////////////
                echo "<div id='license_content_id' class='noshow'>";
                    
                    //echo "<h2>License</h2>";

                    ob_start(); // begin collecting output
                    include 'payment/onlineCurrency.php';
                    $result = ob_get_clean(); // retrieve output from myfile.php, stop buffering
                    echo $result;

                echo "</div>";

?>

