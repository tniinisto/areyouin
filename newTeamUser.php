<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('newTeamUser.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
    try {
        $result = 0;

        if($_GET['totallyNew'] > 0) { //Create new player, if the player is not already in another team

            //Insert new player
            $sql = "INSERT INTO players (name, mail, firstname, lastname, photourl) VALUES ()";

            if($_SESSION['ChromeLog']) { ChromePhp::log('newTeamUser: ' . $sql); }
        
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':teamID', $_GET['teamid'], PDO::PARAM_INT);
            $stmt->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
            $stmt->bindParam(':nick', $_GET['nickname'], PDO::PARAM_STR);
            $stmt->bindParam(':first', $_GET['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(':last', $_GET['lastname'], PDO::PARAM_STR);
        
            $result = $stmt->execute();                        
        }

        //Add player to the team
        $sql1 = "INSERT INTO playerteam () VALUES ()";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add player for team: ' . $sql); }
        
        $stmt1 = $dbh->prepare($sql);
        $stmt1->bindParam(':teamID', $_GET['teamid'], PDO::PARAM_INT);
        $stmt1->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        
        $result1 = $stmt1->execute();                        



        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>


