<?php

include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

$teamid=$_POST['teamid'];

$sql = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = :teamid order by c.publishTime desc";

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

    echo '{"items":'. json_encode($comment_info) .'}'; 
     
}
catch(PDOException $e) {
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}

?>
