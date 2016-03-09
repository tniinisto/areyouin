<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('updateAdminStatus.php, start');
    }
    
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");

    //Ajax url parameter
     $playerID=$_GET["playerID"];

    //Admin switch
    $admin = 0;
    if($_GET['admin'] == '') //OFF
        $admin = 0;
    else
        $admin = 1;
          
    $sql = "UPDATE playerteam SET teamAdmin = '". $admin . "' WHERE player_playerID = '" . $playerID . "' AND teamID = '" . $_SESSION['myteamid'] . "';";
    $result = mysql_query($sql);
    
    mysql_close($con);
?>


