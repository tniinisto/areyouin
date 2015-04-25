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
    //$timezone=$_POST['timezone_select'];

    //Ajax url parameter
     $timezone=$_GET["timezone"];

    //Calculate offset to UTC//////////////////////
    date_default_timezone_set( "UTC" );    
    $daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open($timezone), new DateTime() ); 
    $offset = round($daylight_savings_offset_in_seconds/3600); //Hours
    ///////////////////////////////////////////////

    //Save to session
    $_SESSION['mytimezone'] = $timezone;
    $_SESSION['myoffset'] = $offset;
          
    $sql = "UPDATE team SET timezone = '". $timezone . "', utcOffset = '" . $offset . "' WHERE teamID = '" . $_SESSION['myteamid'] . "';";
    $result = mysql_query($sql);
    
    mysql_close($con);
?>


