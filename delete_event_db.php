<?php
   include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    //include 'ChromePhp.php';
    //ChromePhp::log('Hello console!');

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
            
    $eventid=$_POST['update_eventid'];
    $teamid=$_POST['update_teamid'];
    
?>


