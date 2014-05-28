<?php
    session_start();

    $comment=$_GET["comment"];
    
    date_default_timezone_set('UTC');

    //include 'ChromePhp.php';        
    //ChromePhp::log("starting chat...");

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	    {
	    die('Could not connect: ' . mysql_error());
	    }

	mysql_select_db("areyouin", $con);

    $date = new DateTime();
    //$date->modify("-1 hour");
    $date->modify("+3 hour"); //Todo, timezones must be checked

    $sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES ('" . $comment . "','" . $playerid . "','" . $teamid . "','" . $date->format("Y-n-j H:i:s") . "')";
    //ChromePhp::log('Update: ' . $sql3);
    $result3 = mysql_query($sql3);

    mysql_close($con);  
?>

