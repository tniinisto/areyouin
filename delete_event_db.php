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
            
    //$eventid=$_POST['delete_eventid'];
    
    $sql1 = "DELETE FROM eventplayer WHERE Events_eventID =  :eventId";
    $stmt = $pdo->prepare($sql1);
    $stmt->bindParam(':eventId', $_POST['delete_eventid'], PDO::PARAM_INT);   
    $stmt->execute();

    $sql2 = "DELETE FROM events WHERE eventID =  :eventId";
    $stmt = $pdo->prepare($sql2);
    $stmt->bindParam(':eventId', $_POST['delete_eventid'], PDO::PARAM_INT);   
    $stmt->execute();

    header("location:index.html");    
    

?>


