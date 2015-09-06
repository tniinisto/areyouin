<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

    $sql_team_events = "SELECT YEAR(events.startTime) as year, MONTH(events.startTime) as month, count(events.eventID) games FROM `areyouin`.`events`
                        where Team_teamID = " . $teamid . "
                        GROUP BY YEAR(events.startTime), MONTH(events.startTime);";

    
    try {
        //PDO means "PHP Data Objects"
        //dbh meand "Database handle"
        //STH means "Statement Handle"

	    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $stmt = $dbh->query($sql_team_events);  
	    //$result = $stmt->fetchAll(PDO::FETCH_OBJ);

        $chart_data_table = array();
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $chart_data_table[] = $row;
            }
        }

        echo '{"items":'. json_encode($chart_data_table) .'}'; 

	    $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    

?>
