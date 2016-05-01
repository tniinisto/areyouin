<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('updatupdateNewEventLocations.php start');
    }

    $teamid=$_SESSION['myteamid'];

  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                  
    try {
        $result = 0;

            //Update new event's location list
            $sql="SELECT locationID, name FROM location WHERE teamID = '" . $teamid . "'";

            if($_SESSION['ChromeLog']) { ChromePhp::log('updatupdateNewEventLocations: ' . $sql); }          

            $stmt = $dbh->prepare($sql);    
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
            
            if($_SESSION['ChromeLog']) { ChromePhp::log('updateNewEventLocations result: ' . $result); }


            echo "<div id='locationWrapper'>";
                echo "<label><h2>Event location:</h2></label>";

                echo "<select id=\"location_select\" name=\"location\" form=\"eventform\">";
                    
                //Default location when no locations entered to RYouIN
                echo "<option value='0'></option>";
                //Team's locations
                //while($row2 = mysql_fetch_array($result2))
                foreach($data as $row)
	            {  
                    echo "<option value=\"" . $row['locationID'] . "\">" . $row['name'] . "</option>";
                }
                echo "</select>";
            echo "</div>";

        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>

