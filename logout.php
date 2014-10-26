<?php
    include 'ChromePhp.php';

    session_start();
    
    if($_SESSION['ChromeLog']) {
        ChromePhp::log('logout.php, start');
    }

    ChromePhp::log("logout.php start, logged_in:", $_SESSION['logged_in']);
	
    session_unset(); 	
    session_destroy();

    ChromePhp::log("logout.php end, logged_in:", $_SESSION['logged_in']);

    //header("location:default.html");

?>
