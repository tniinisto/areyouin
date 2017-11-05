<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    $playerid=$_GET['playerid'];
	$teamid=$_GET['teamid'];

    $sql_team_events = 
    "select concat(YEAR(startTime), '/', MONTH(e.startTime)) as month, sum(case when ep.areyouin = 1 then 1 else 0 end) as participated, count(e.eventid) as games
    from events e
    left join
    (SELECT eventid, YEAR(e1.startTime) as year, MONTH(e1.startTime) as month1, count(e1.eventID) as events
    FROM events e1
    where Team_teamID = :teamid
    GROUP BY year, month1) t1
    on e.eventid = t1.eventID
    left join eventplayer ep
    on ep.Events_eventID = e.eventID
    where Team_teamID = :teamid and Players_playerID = :playerid
    group by YEAR(startTime), MONTH(e.startTime);";

    try {
        //PDO means "PHP Data Objects"
        //dbh meand "Database handle"
        //STH means "Statement Handle"

	    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //$stmt = $dbh->query($sql_team_events);        
        $stmt = $dbh->prepare($sql_team_events);
        $stmt->bindParam(':teamid', $teamid, PDO::PARAM_INT);
        $stmt->bindParam(':playerid', $playerid, PDO::PARAM_INT);        

        $data_table = array();
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data_table[] = $row;
            }
        }

        echo json_encode($data_table);

	    $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    

?>
