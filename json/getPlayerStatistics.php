<?php
include 'config.php';

session_start();

//if($_SESSION['ChromeLog']) {
//    require_once 'ChromePhp.php';
//    ChromePhp::log('getPlayerStatistics.php, start');
//}
        
$team = $_SESSION['myteamid'];

$sql = "select p.name name, p.photourl photourl, count(ep.Events_eventID) games from players p
inner join eventplayer ep on p.playerID = ep.Players_playerID
inner join events e on ep.Events_eventID = e.eventID
inner join team t on e.Team_teamID = t.teamID
where t.teamID = '" . $team . "' and ep.areyouin = 1 and e.endTime < now()
group by p.name
order by games desc;";


try {
    //PDO means "PHP Data Objects"
    //dbh meand "Database handle"
    //STH means "Statement Handle"

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$playerstats = $stmt->fetchAll(PDO::FETCH_OBJ);

	$dbh = null;

	echo '{"items":'. json_encode($playerstats) .'}'; 
}
catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}

?>
