<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('updateLocation.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  
    try {
        $result = 0;

            //Insert new location
            $sql = "UPDATE location SET name = :name, showWeather = :weather WHERE locationID = :locationID";

            if($_SESSION['ChromeLog']) { ChromePhp::log('updateLocation: ' . $sql); }          

            $stmt = $dbh->prepare($sql);
             
            $stmt->bindParam(':name', $_GET['name'], PDO::PARAM_STR);
            $stmt->bindParam(':locationID', $_GET['locationid'], PDO::PARAM_INT);
            $stmt->bindParam(':weather', $_GET['weather'], PDO::PARAM_INT);
        
            $result = $stmt->execute();
            
            if($_SESSION['ChromeLog']) { ChromePhp::log('updateLocation result: ' . $result); }

        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>

