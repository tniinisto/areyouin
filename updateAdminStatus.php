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
    $admin = $_GET['admin'];
          
    $sql = "UPDATE playerteam SET teamAdmin = '" . $admin . "' WHERE Players_playerID = '" . $playerID . "' AND Team_teamID = '" . $_SESSION['myteamid'] . "';";
    if($_SESSION['ChromeLog']) {ChromePhp::log('updateAdminStatus sql: ', $sql);}
    $result = mysql_query($sql);
    
    mysql_close($con);
?>


