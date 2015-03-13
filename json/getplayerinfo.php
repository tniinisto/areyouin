<?php
include 'config.php';

session_start();

if($_SESSION['ChromeLog']) {
    require_once 'ChromePhp.php';
    ChromePhp::log('getplayerinfo.php, start');
}
        
$pl=$_SESSION['myplayerid'];

//$sql =	"SELECT playerID, name, photourl
//    	FROM areyouin.players
//        WHERE playerID = '" . $pl . "'";

$sql = "SELECT p.playerID, p.name, p.photourl, t.teamID, t.teamName, m.teamAdmin
        from areyouin.players p
        inner join areyouin.playerteam m on p.playerID = m.Players_playerID
        inner join areyouin.team t on m.Team_teamID = t.teamid
	    where p.playerID = '" . $pl . "'";
try {
    //PDO means "PHP Data Objects"
    //dbh meand "Database handle"
    //STH means "Statement Handle"

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$playerinfo = $stmt->fetchAll(PDO::FETCH_OBJ);

	$dbh = null;

	echo '{"items":'. json_encode($playerinfo) .'}'; 
}
catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}

?>