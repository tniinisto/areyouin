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

        //Get playerID for mail address////////////////////////////////////////////////////////////
        $sql = "SELECT playerID from players WHERE mail like ' :mail '";
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);        
        $stmt->execute();                        

        $row = $stmt->fetch();
        $playerid = $row['playerID'];

        //Add existing player to the team//////////////////////////////////////////////////////////
        $sql1 = "INSERT INTO playerteam (Players_playerID, Team_teamID, teamAdmin) VALUES (" . $playerid . "," . $_SESSION['myteamid'] . ", 0)";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add player for playerteam: ' . $sql1); }
        
        $stmt1 = $dbh->prepare($sql1);      
        $result1 = $stmt1->execute();                        

        echo 

        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>


