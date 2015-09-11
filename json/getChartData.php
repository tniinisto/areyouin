<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

    $sql_team_events = "SELECT YEAR(events.startTime) as year, MONTH(events.startTime) as month, count(events.eventID) games FROM `areyouin`.`events`
                        where Team_teamID = " . $teamid . "
                        GROUP BY YEAR(events.startTime), MONTH(events.startTime);";

    //SELECT year, month, games_set, participated
    //FROM

    //(SELECT eventID, YEAR(e1.startTime) as year, MONTH(e1.startTime) as month, count(e1.eventID) 'games_set'
    //FROM `areyouin`.`events` e1
    //where Team_teamID = 1
    //GROUP BY YEAR(e1.startTime), MONTH(e1.startTime)) t1
    //left join

    //(SELECT eventID, YEAR(e2.startTime) as year2, MONTH(e2.startTime) as month2, count(ep.eventPlayerID) as participated
    //FROM eventplayer ep, events e2
    //where ep.Players_playerID = 1 and ep.areyouin = 1 and e2.eventID = ep.Events_eventID
    //GROUP BY YEAR(e2.startTime), MONTH(e2.startTime)) t2

    //on t1.eventID = t2.eventID;


    
    try {
        //PDO means "PHP Data Objects"
        //dbh meand "Database handle"
        //STH means "Statement Handle"

	    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

	    //Team's events///////////////////////////////////////////////////////////////////////
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $stmt = $dbh->query($sql_team_events);  
	    //$result = $stmt->fetchAll(PDO::FETCH_OBJ);

        $data_table = array();
        $data_table[] = array("Month", "Your Games", "Games set");
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data_table[] = $row;
            }
        }

        echo '{"items":'. json_encode($data_table) .'}'; 

	    $dbh = null;
        //Team's events///////////////////////////////////////////////////////////////////////


        //Player's events/////////////////////////////////////////////////////////////////////
        
        //Player's events/////////////////////////////////////////////////////////////////////



    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    

?>
