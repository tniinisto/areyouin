<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

    //$sql_team_events = "SELECT YEAR(events.startTime) as year, MONTH(events.startTime) as month, count(events.eventID) games FROM `areyouin`.`events`
    //                    where Team_teamID = " . $teamid . "
    //                    GROUP BY YEAR(events.startTime), MONTH(events.startTime);";

    
    //$sql_team_events = 
    //"SELECT CONCAT(year, '/' ,month) as month, participated, games
    //FROM
    //(SELECT eventID, YEAR(e1.startTime) as year, MONTH(e1.startTime) as month, count(e1.eventID) as games
    //FROM `areyouin`.`events` e1
    //where Team_teamID = '" . $teamid . "'
    //GROUP BY YEAR(e1.startTime), MONTH(e1.startTime)) t1
    //left join
    //(SELECT eventID, YEAR(e2.startTime) as year2, MONTH(e2.startTime) as month2, count(ep.eventPlayerID) as participated
    //FROM eventplayer ep, events e2
    //where ep.Players_playerID = '" . $playerid. "' and ep.areyouin = 1 and e2.eventID = ep.Events_eventID
    //GROUP BY YEAR(e2.startTime), MONTH(e2.startTime)) t2
    //on t1.eventID = t2.eventID;";
    //

    $sql_team_events = 
    "select CONCAT(YEAR(e.startTime), '/', MONTH(e.endTime)) as month, count(ep.eventPlayerID) as participated, count(t2.eventID) as games
    from areyouin.events e, areyouin.eventplayer ep,
    (SELECT e1.eventID, count(e1.eventID) as games
    FROM `areyouin`.`events` e1
    where e1.Team_teamID = '" . $teamid . "') t2
    where e.eventID = ep.Events_eventID and e.Team_teamID = '" . $teamid . "' and ep.Players_playerID = '" . $playerid. "' and ep.areyouin = 1
    group by month;";

    try {
        //PDO means "PHP Data Objects"
        //dbh meand "Database handle"
        //STH means "Statement Handle"

	    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $stmt = $dbh->query($sql_team_events);  
	    //$result = $stmt->fetchAll(PDO::FETCH_OBJ);

        $data_table = array();
        //$data_table[] = array('month'=>'Month', 'participated'=>'Your games', 'games'=>'Games set', );
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data_table[] = $row;
            }
        }


        //echo '{"items":'. json_encode($data_table) .'}'; 
        //echo '{' . json_encode($data_table) .'}';
        echo json_encode($data_table);

	    $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    

?>
