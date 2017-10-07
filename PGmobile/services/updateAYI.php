<?php

include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );


$eventplayerid=$_POST['eventplayerid'];
$ayi=$_POST['ayi'];

$playerid=$_POST['playerid'];
$teamid=$_POST['teamid'];

$timezone=$_POST['timezone'];
date_default_timezone_set($timezone);

try {
    //PDO means "PHP Data Objects"
    //dbh meand "Database handle"
    //STH means "Statement Handle"

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE eventplayer SET areyouin = :ayi WHERE eventplayerid = :eventplayerid";		   
    $stmt = $dbh->prepare($sql);
    
    $stmt->bindParam(':ayi', $ayi, PDO::PARAM_INT);
    $stmt->bindParam(':eventplayerid', $eventplayerid, PDO::PARAM_INT);
    $result = $stmt->execute();


    //Update last event update time
    $updateDateTime = date("Y-n-j H:i:s");

    $sql2 = "UPDATE playerteam SET lastEventUpdate = :updatetime where Team_teamID = :teamid and players_playerId = :playerid ;";
    $stmt2 = $dbh->prepare($sql2);
    $stmt2->bindParam(':updatetime', $updateDateTime, PDO::PARAM_STR);
    $stmt2->bindParam(':teamid', $teamid, PDO::PARAM_INT);
    $stmt2->bindParam(':playerid', $playerid, PDO::PARAM_INT);
    $result2 = $stmt2->execute();

	$dbh = null;

    echo '{"items":'. json_encode('200') .'}'; 
     
}

catch(PDOException $e) {
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    
}

?>
