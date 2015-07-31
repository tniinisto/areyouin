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

        //SITE SPECIFIC
        if (strpos($_SERVER['HTTP_HOST'], 'dev-') !== false) { //Dev
            header('Location:http://dev-areyouin.azurewebsites.net/default.html');  
        } else
        if (strpos($_SERVER['HTTP_HOST'], 'm-') !== false) { //Mobile
            header('Location:http://m-areyouin.azurewebsites.net/default.html');  
        } else 
        if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) { //Localhost
            header('Location:http://localhost:18502/');  
        } else { //Production
            header('Location:http://areyouin.azurewebsites.net/default.html');  
        }


    }
    else {
        if($_SESSION['ChromeLog']) { ChromePhp::log('login_success.php, session_is_registered'); }

        //SITE SPECIFIC
        if (strpos($_SERVER['HTTP_HOST'], 'dev-') !== false) { //Dev
            header('Location:http://dev-areyouin.azurewebsites.net/index.html');  
        } else
        if (strpos($_SERVER['HTTP_HOST'], 'm-') !== false) { //Mobile
            header('Location:http://m-areyouin.azurewebsites.net/index.html');
        } else 
        if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) { //Localhost
            header('Location:http://localhost:18502/');  
        } else { //Production
            header('Location:http://areyouin.azurewebsites.net/default.html');  
        }
    }

?>
