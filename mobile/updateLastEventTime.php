<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();

    date_default_timezone_set($_SESSION['mytimezone']);

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
            
    $teamid=$_SESSION['myteamid'];
    $playerid=$_SESSION['myplayerid'];

    $updateDateTime = date("Y-n-j H:i:s");
    $sql = "UPDATE playerteam SET lastEventUpdate = '" . $updateDateTime .  "' where Team_teamID = " . $teamid . " and players_playerId = " . $playerid . " ;";
    
    $result = mysql_query($sql);
    //echo $result;

    mysql_close($con);

?>



