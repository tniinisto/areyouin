<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

//session_start();
        
// $pl=$_SESSION['myplayerid'];
// $team=$_SESSION['myteamid'];

$teamid=$_POST['teamid'];
//$userid=$_POST['userid'];

$timezone=$_POST['timezone'];
date_default_timezone_set($timezone);

$sql = "SELECT x.rowcount, y.eventcount, e.private, ep.Events_eventID, l.name as location, l.position as pos, e.startTime, e.endTime, p.playerid, p.name,
p.photourl, ep.EventPlayerID, ep.areyouin, ep.seen, t.teamID, t.teamName, pt.teamAdmin
FROM
(SELECT count(*) as rowcount
 FROM events ex, team t, eventplayer ep
 where ep.Events_eventID = ex.eventID and t.teamID = :teamid and ex.startTime > now()) as x,
(SELECT count(*) as eventcount
 FROM events ex, team t
 where t.teamID = :teamid and ex.startTime > now()) as y,
events e
inner join location l on l.locationID = e.Location_locationID
inner join eventplayer ep on ep.Events_eventID = e.eventID
inner join players p on ep.Players_playerID = p.playerID
inner join playerteam pt on pt.Players_playerID = p.playerID
inner join team t on t.teamID = pt.Team_teamID
where t.teamID = :teamid and e.Team_teamID = t.teamID
and e.startTime > now()
order by e.startTime asc, ep.Events_eventID asc, ep.areyouin desc, ep.seen desc;";		


try {
    //PDO means "PHP Data Objects"
    //dbh meand "Database handle"
    //STH means "Statement Handle"

    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);		
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':teamid',  $teamid, PDO::PARAM_INT);        
    $stmt->execute();
    $event_info = $stmt->fetchAll(PDO::FETCH_OBJ);

    $dbh = null;

    echo '{"items":'. json_encode($event_info) .'}'; 
     
}
catch(PDOException $e) {
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}



?>