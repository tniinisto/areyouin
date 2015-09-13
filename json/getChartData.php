<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

    //$sql_team_events = "SELECT YEAR(events.startTime) as year, MONTH(events.startTime) as month, count(events.eventID) games FROM `areyouin`.`events`
    //                    where Team_teamID = " . $teamid . "
    //                    GROUP BY YEAR(events.startTime), MONTH(events.startTime);";

    
    $sql_team_events = 
    "SELECT CONCAT(year, '/' ,month) as month, participated, games
    FROM
    (SELECT eventID, YEAR(e1.startTime) as year, MONTH(e1.startTime) as month, count(e1.eventID) as games
    FROM `areyouin`.`events` e1
    where Team_teamID = '" . $teamid . "'
    GROUP BY YEAR(e1.startTime), MONTH(e1.startTime)) t1
    left join
    (SELECT eventID, YEAR(e2.startTime) as year2, MONTH(e2.startTime) as month2, count(ep.eventPlayerID) as participated
    FROM eventplayer ep, events e2
    where ep.Players_playerID = '" . $playerid. "' and ep.areyouin = 1 and e2.eventID = ep.Events_eventID
    GROUP BY YEAR(e2.startTime), MONTH(e2.startTime)) t2
    on t1.eventID = t2.eventID;";
    
    try {
        //PDO means "PHP Data Objects"
        //dbh meand "Database handle"
        //STH means "Statement Handle"

	    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $stmt = $dbh->query($sql_team_events);  
	    //$result = $stmt->fetchAll(PDO::FETCH_OBJ);

        $data_table = array();
        $data_table[] = array('month'=>'Month', 'participated'=>'Your games', 'games'=>'Games set', );
        if ($stmt->execute()) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $data_table[] = $row;
            }
        }


$col1=array();
$col1["id"]="";
$col1["label"]="Topping";
$col1["pattern"]="";
$col1["type"]="string";
//print_r($col1);
$col2=array();
$col2["id"]="";
$col2["label"]="Slices";
$col2["pattern"]="";
$col2["type"]="number";
//print_r($col2);
$cols = array($col1,$col2);
//print_r($cols);
 
$cell0["v"]="Mushrooms";
$cell0["f"]=null;
$cell1["v"]=3;
$cell1["f"]=null;
$row0["c"]=array($cell0,$cell1);
 
$cell0["v"]="Onion";
$cell1["v"]=1;
$row1["c"]=array($cell0,$cell1);
 
$cell0["v"]="Olives";
$cell1["v"]=1;
$row2["c"]=array($cell0,$cell1);
 
$cell0["v"]="Zucchini";
$cell1["v"]=1;
$row3["c"]=array($cell0,$cell1);
 
$cell0["v"]="Pepperoni";
$cell1["v"]=2;
$row4["c"]=array($cell0,$cell1);
 
//$rows=array($row0,$row1,$row2,$row3,$row4);
$rows=array();
array_push($rows,$row0);
array_push($rows,$row1);
array_push($rows,$row2);
array_push($rows,$row3);
array_push($rows,$row4);
 
 
//print_r($rows);
 
$data_table=array("cols"=>$cols,"rows"=>$rows);
//print_r($data);









        echo json_encode($data);
        //echo '{"items":'. json_encode($data_table) .'}'; 

	    $dbh = null;




    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    

?>
