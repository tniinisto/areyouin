<?php

    session_start();
    
    // How often to poll, in microseconds (1,000,000 μs equals 1 s)
    define('MESSAGE_POLL_MICROSECONDS', 5000000);

    // How long to keep the Long Poll open, in seconds
    define('MESSAGE_TIMEOUT_SECONDS', 60);
 
    // Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
    $counter = MESSAGE_TIMEOUT_SECONDS;
    
    $playerID = 0;
    $playerID = $_SESSION['myplayerid'];

    while($playerID > 0 && $counter > 0) {
                        
        usleep(MESSAGE_POLL_MICROSECONDS);

        $playerID = $_SESSION['myplayerid'];
                        
        // Decrement seconds from counter (the interval was set in μs, see above)
        $counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
    }
    
    if($playerID > 0) {
        echo "1"; //Session active
    }
    else {
        echo "0"; //Session expired
    } 

?>
