<?php
    session_start();
    
    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('logout.php, start...');
    }

    //ChromePhp::log("logout.php start, logged_in:", $_SESSION['logged_in']);

    //setcookie(session_name(), '', 100);
    $_SESSION = array();
    session_unset();
    session_destroy();
    
    //ChromePhp::log("logout.php end, logged_in:", $_SESSION['logged_in']);

    //header("location:default.html");

?>
