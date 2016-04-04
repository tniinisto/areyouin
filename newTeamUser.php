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

        if($_GET['totallyNew'] == 0) { //Create new player, if the player is not already in another team

            //Insert new player
            $photourl = '/images/player7.png';
            $sql = "INSERT INTO players (name, mail, firstname, lastname, photourl, password) VALUES (:nick, :mail, :first, :last,'" . $photourl ."', 'ca412a4244ea7f113cfeb6c10992f66a')";

            if($_SESSION['ChromeLog']) { ChromePhp::log('newTeamUser: ' . $sql); }
        
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':teamID', $_GET['teamid'], PDO::PARAM_INT);
            $stmt->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
            $stmt->bindParam(':nick', $_GET['nickname'], PDO::PARAM_STR);
            $stmt->bindParam(':first', $_GET['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(':last', $_GET['lastname'], PDO::PARAM_STR);
        
            $result = $stmt->execute();                        
        }

        //Get the playerID////////////////////////////////////////////////////////////////           

        $sql2 = "SELECT playerID from players WHERE mail like :mail";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add player for team: ' . $sql); }
        
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        
        $result2 = $stmt2->execute();   
        $row2;
        $playerid = 0;
        while($row2 = $stmt2->fetch()) {
            //print_r($row);
            $playerid = $row2['playerID'];
        }                     

        //Create password


        //Add player to the team//////////////////////////////////////////////////////////

        $sql1 = "INSERT INTO playerteam () VALUES (" . $playerid . "," . $_SESSION['myteamid'] . ", 0)";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add player for team: ' . $sql); }
        
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':teamID', $_GET['teamid'], PDO::PARAM_INT);
        $stmt1->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        
        $result1 = $stmt1->execute();                        

        //Send mail



        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>


