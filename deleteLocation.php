<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('deleteLocation.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  
    try {
        $result = 0;

            //delete location
            $sql = "DELETE FROM location WHERE locationID = :locationID";

            if($_SESSION['ChromeLog']) { ChromePhp::log('deleteLocation: ' . $sql); }          

            $stmt = $dbh->prepare($sql);             
            $stmt->bindParam(':locationID', $_GET['locationid'], PDO::PARAM_INT);
        
            $result = $stmt->execute();
            
            if($_SESSION['ChromeLog']) { ChromePhp::log('deleteLocation result: ' . $result); }

        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>

