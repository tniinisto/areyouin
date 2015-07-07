<?php     
    // No cache
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('login_success.php, start...');
    }

    // Check if session is not registered, redirect back to default page. 
    if(!$_SESSION['myusername']){

        if($_SESSION['ChromeLog']) { ChromePhp::log('login_success.php, not session_is_registered'); }

        header('Location:http://areyouin.azurewebsites.net/default.html/');
    }
    else {
        if($_SESSION['ChromeLog']) { ChromePhp::log('login_success.php, session_is_registered'); }

        header('Location:http://areyouin.azurewebsites.net/index.html/');
    }

?>
