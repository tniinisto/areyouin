<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('newLocation.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  
    try {
        $result = 0;

            //Insert new location
            $sql = "INSERT INTO location (position, name, teamID, showWeather) VALUES (:position, :name, :teamID, :weather);";

            if($_SESSION['ChromeLog']) { ChromePhp::log('newLocation: ' . $sql); }          

            $stmt = $dbh->prepare($sql);
            
            $pos = $_GET['lat'] . ", " . $_GET['lon'];
            
            $stmt->bindParam(':position', $pos, PDO::PARAM_STR);
            $stmt->bindParam(':name', $_GET['name'], PDO::PARAM_STR);
            $stmt->bindParam(':teamID', $_GET['teamid'], PDO::PARAM_INT);
            $stmt->bindParam(':weather', $_GET['weather'], PDO::PARAM_INT);
        
            $result = $stmt->execute();
            
            if($_SESSION['ChromeLog']) { ChromePhp::log('New location result: ' . $result); }

        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>


