<?php

                /////////////////////////////////////////////////////////////////////////////////////////////
                //License page///////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////////////////////
                echo "<div id='license_content_id' class=''>";
                    
                    //echo "<h2>License</h2>";

                    ob_start(); // begin collecting output
                    include 'payment/onlineCurrency.php';
                    $result = ob_get_clean(); // retrieve output from myfile.php, stop buffering
                    echo $result;

                echo "</div>";

                echo "<div><<label>24% VAT is added to prices on payment. Separated on the receipt.</label> </div>";
?>

