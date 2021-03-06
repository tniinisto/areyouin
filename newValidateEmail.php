<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('newValidateEmail.php start');
    }


    //$mail=$_GET['mail'];

  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
    try {

        //Validate the email, does it already exist, is user already in the team. If already in another team, show name and ask if this should be insterted for the team

        //Get users teamIDs///////////////////////////////////////////////////////////////////
        $sql1 = "SELECT count(t.teamID) as teamcount from players p
                inner join playerteam pt on p.playerID = pt.Players_playerID
                inner join team t on t.teamID = pt.Team_teamID
                where p.mail like :mail and t.teamID = :teamid";

        if($_SESSION['ChromeLog']) { ChromePhp::log('newValidateEmail users teams: ' . $sql1); }
        
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        $stmt1->bindParam(':teamid', $_SESSION['myteamid'], PDO::PARAM_INT);
        $stmt1->execute();
        $row1 = $stmt1->fetch();
        
        $teamid_count = 0;
        $teamid_count = $row1['teamcount'];

        //Check mail address, return count of matching addresses/////////////////////////////
        $sql = "SELECT mail, count(mail) as mailcount, t.teamName, t.teamID from players p
                inner join playerteam pt on p.playerID = pt.Players_playerID
                inner join team t on t.teamID = pt.Team_teamID
                where p.mail like :mail";

        if($_SESSION['ChromeLog']) { ChromePhp::log('newValidateEmail mailcount: ' . $sql); }
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        $stmt->execute();

        $row;
        $mailCount = 0;
        while($row = $stmt->fetch()) {
            //Return mailcount, teamid        
            $mailCount = $row['mailcount'] . "," . $teamid_count;
        }

        echo $mailCount;

        $dbh = null;
    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>
