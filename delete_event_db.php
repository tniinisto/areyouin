<?php
   include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    //include 'ChromePhp.php';
    //ChromePhp::log('Hello console!');

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
            
    try {    
        $sql1 = "DELETE FROM eventplayer WHERE Events_eventID = :eventId";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->bindParam(':eventId', $_POST['delete_eventid'], PDO::PARAM_INT);   
        $stmt1->execute();

        $sql2 = "DELETE FROM events WHERE eventID = :eventId";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(':eventId', $_POST['delete_eventid'], PDO::PARAM_INT);   
        $stmt2->execute();
    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }

    header("location:index.html");    
    
?>


