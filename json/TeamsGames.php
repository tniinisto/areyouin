<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

session_start();

//if($_SESSION['ChromeLog']) {
//    require_once 'ChromePhp.php';
//    ChromePhp::log('getPlayerStatistics.php, start');
//}
        
$team = $_SESSION['myteamid'];

$sql = "select count(eventID) gamecount from events, team
where events.Team_teamID = team.teamID and team.teamID = '" . $team . "' and events.endTime < now();";

try {
    //PDO means "PHP Data Objects"
    //dbh meand "Database handle"
    //STH means "Statement Handle"

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$allplayedgames = $stmt->fetchAll(PDO::FETCH_OBJ);

	$dbh = null;

	echo '{"items":'. json_encode($allplayedgames) .'}'; 
}
catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}

?>

