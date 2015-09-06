<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];


  
    //   $con = mysql_connect($dbhost, $dbuser, $dbpass);
	//if (!$con)
	//    {
	//    die('Could not connect: ' . mysql_error());
	//    }

    //   mysql_select_db("areyouin", $con);

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

	    $dbh = null;


    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    



 
?>
