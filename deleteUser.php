<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('deleteUser.php start');
    }


    //$playerId=$_GET['playerID'];

  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
    try {

        //Update the removed player teamID to nullTeam, teamId = 0 for current team, player can be in multiple teams
        $sql = "UPDATE playerteam SET Team_teamID = '0' WHERE Players_playerID =  :playerID AND Team_teamid = :teamID";

        if($_SESSION['ChromeLog']) { ChromePhp::log('delete player: ' . $sql); }
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':playerID', $_GET['playerID'], PDO::PARAM_INT);
        $stmt->bindParam(':teamID', $_SESSION['myteamid'], PDO::PARAM_INT);
        $stmt->execute();


        //Count TeamMemberCount for the player
        $sql2 = "select count(players_playerid) as TeamMemberCount from playerteam where players_playerid = :playerID";

        $stmt2 = $dbh->prepare($sql2);
        $stmt2->bindParam(':playerID', $_GET['playerID'], PDO::PARAM_INT);
        $stmt2->execute();
        $teamCount = 0;
        while($row = $stmt2->fetch()) {
            //print_r($row);
            $teamCount = $row['TeamMemberCount'];
        }

        //Get the max "nulled" mail value
        $sql3 = "SELECT MAX(mail + 0) as maxi FROM players;";

        $stmt3 = $dbh->prepare($sql3);
        $stmt3->execute();
        $row3 = $stmt3->fetch();
        $max_mail = $row3['maxi'];
        $max_mail++;
        

        //If player is only in one team -> Clear email value, so same email can be used when making a new player for the same mail in future
        if($teamCount < 2) {
            $sql1 = "UPDATE players SET mail = '" . $max_mail . "' WHERE playerID =  :playerID";
            if($_SESSION['ChromeLog']) { ChromePhp::log('delete player: ' . $sql1); }
        
            $stmt1 = $dbh->prepare($sql1);
            $stmt1->bindParam(':playerId', $_GET['playerID'], PDO::PARAM_INT);   
            $stmt1->execute();
        }
        
        if($_SESSION['ChromeLog']) { ChromePhp::log('deleteUser.php: ', $sql1); }

        $dbh = null;
    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }

    //header("location:index.html");    
    
?>