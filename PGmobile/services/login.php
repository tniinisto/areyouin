<?php
include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

//session_start();
        
// $pl=$_SESSION['myplayerid'];
// $team=$_SESSION['myteamid'];

/*
//Test////////////////////////////////////////////////////////////////////////////////////
 $pl=1;
 $team=1;

$sql = "SELECT p.playerID, p.name, p.photourl, t.teamID, t.teamName, m.teamAdmin
        from players p
        inner join playerteam m on p.playerID = m.Players_playerID
        inner join team t on m.Team_teamID = t.teamid
	    where p.playerID = '" . $pl . "' and t.teamID = '" . $team . "'";
//Test////////////////////////////////////////////////////////////////////////////////////
*/

$name=$_POST['ayiloginname'];
$password=$_POST['ayipassword'];
$mymd5 = md5($password);

$sql = "SELECT x.count, p.playerID, p.name, p.mail, t.teamID, t.teamName, m.teamAdmin, m.registrar, m.lastMsg, r.licensevalid
    FROM players p, playerteam m, team t, registration r,         
        (SELECT count(*) as count
        FROM players p, playerteam m, team t, registration r
        WHERE (name = '$name' OR mail = '$name') and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid and t.teamid <> 0 and r.team_teamid = t.teamid) as x
    WHERE (name = '$name' OR mail = '$name') and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid and t.teamid <> 0 and r.team_teamid = t.teamid
    ORDER BY t.teamName;";		


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