<?php

include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );


$eventplayerid=$_POST['eventplayerid'];


try {
    //PDO means "PHP Data Objects"
    //dbh meand "Database handle"
    //STH means "Statement Handle"

	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE eventplayer SET seen = '1' WHERE eventplayerid = :eventplayerid";		   
    $stmt = $dbh->prepare($sql);
    
    $stmt->bindParam(':eventplayerid', $eventplayerid, PDO::PARAM_INT);
    $result = $stmt->execute();

	$dbh = null;

    echo '{"items":'. json_encode('200') .'}'; 
     
}

catch(PDOException $e) {
    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    
}

?>
