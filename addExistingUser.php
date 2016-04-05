<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('addExistingUser.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
    try {

        //Add existing player to the team//////////////////////////////////////////////////////////
        $sql1 = "INSERT INTO playerteam (Players_playerID, Team_teamID, teamAdmin) VALUES ( :playerid ," . $_SESSION['myteamid'] . ", 0)";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add player for playerteam: ' . $sql1); }

        $stmt1 = $dbh->prepare($sql1);    
        $stmt1->bindParam(':playerID', $_GET['playerid'], PDO::PARAM_INT);
  
        $result1 = $stmt1->execute();                        

        echo $result1;

        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>


