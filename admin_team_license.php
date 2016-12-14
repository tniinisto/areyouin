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

                //echo "<div style='text-align: center'><label>24% VAT is added to prices on payment. Tax is separated on the receipt.</label> </div>";
?>

