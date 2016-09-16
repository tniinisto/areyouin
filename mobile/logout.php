<?php
    // session_start();
    
    // //if($_SESSION['ChromeLog']) {
    // //    require_once 'ChromePhp.php';
    // //    ChromePhp::log('logout.php, start...');
    // //}

    // //ChromePhp::log("logout.php start, logged_in:", $_SESSION['logged_in']);
	
    // setcookie(session_name(), '', 100);
    // session_unset();
    // session_destroy();
    // $_SESSION = array();

    // //ChromePhp::log("logout.php end, logged_in:", $_SESSION['logged_in']);

    // //header("location:default.html");

    session_start();
    
    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('logout.php, start...');
    }

    //ChromePhp::log("logout.php start, logged_in:", $_SESSION['logged_in']);

    $_SESSION['mypassword'] = '';
    unset($_SESSION['mypassword']);
    $_SESSION['myplayerid'] = 0;
    unset($_SESSION['myplayerid']);
    $_SESSION['myteamid'] = 0;
    unset($_SESSION['myteamid']);
    $_SESSION['myteamname'] = '';
    unset($_SESSION['myteamname']);
    $_SESSION['myAdmin'] = 0;
    unset($_SESSION['myAdmin']);
    $_SESSION['mytimezone'] ='';
    unset($_SESSION['mytimezone']);
    $_SESSION['myoffset'] = 0;
    unset($_SESSION['myoffset']);

    //setcookie(session_name(), '', 100);
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_unset();
    session_destroy();
    
    //ChromePhp::log("logout.php end, logged_in:", $_SESSION['logged_in']);

    //header("location:default.html");

?>
    


