<?php
   include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    //include 'ChromePhp.php';
    //ChromePhp::log('Hello console!');

  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
    try {    
        $sql1 = "DELETE FROM eventplayer WHERE Events_eventID = :eventId";
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':eventId', $_POST['delete_eventid'], PDO::PARAM_INT);   
        $stmt1->execute();

        $sql2 = "DELETE FROM events WHERE eventID = :eventId";
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->bindParam(':eventId', $_POST['delete_eventid'], PDO::PARAM_INT);   
        $stmt2->execute();

        $dbh = null;
    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }

    header("location:index.html");    
    
?>


