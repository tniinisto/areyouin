<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('update_team.php, start');
    }
    
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
    
    //Post variables
    $timezone=$_POST['timezone_select'];
      
    $sql = "UPDATE team SET timezone = '". $timezone . "' WHERE teamID = '" . $_SESSION['myteamid'] . "';";
    $result = mysql_query($sql);
    
    mysql_close($con);
?>


