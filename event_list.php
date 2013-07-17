<?php

	$teamid=$_GET["teamid"];
	$playerid=$_GET["playerid"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT * FROM players";
	$sql = "select v.Events_eventID, l.name as location,l.position as pos, e.startTime, e.endTime, p.playerid, p.name, p.photourl, v.eventplayerid, v.areyouin, v.seen, m.teamID, m.teamName, a.teamAdmin from events e, eventtype t, location l, players p,  eventplayer v, team m, playerteam a where t.eventTypeID = e.EventType_eventTypeID and l.locationID = e.Location_locationID and p.playerID = v.Players_playerID and v.Events_eventID = e.eventID and a.Players_playerID = p.playerID and a.Team_teamID = m.teamID and m.teamID = '" . $teamid  . "' and e.endTime > now() order by e.startTime asc, v.Events_eventID asc, v.areyouin desc";
	 	
	$result = mysql_query($sql);
	
	//Go through events & players
	$event_check = 0; //Check when the event changes
	$row_index = 1; //Unique naming for swithces
	while($row = mysql_fetch_array($result))
	{
		//Check when the event changes, then echo the event basic information
		if($row['Events_eventID'] != $event_check)
		{		
			if($event_check != 0)
				echo "</article>";
				
			$event_check = $row['Events_eventID'];	
			
			echo "<article class=\"clearfix\">";			
			echo "<table border='0' class=\"atable\">";
				echo "<tr>";
					echo "<th> Games @&nbsp <a href=\"https://maps.google.fi/maps?q=" . $row[pos] . "\"&npsp target=\"_blank\">" . $row['location'] . "</a></th>";
				echo "</tr>";
			echo "</table>";
			
			echo "<table border='0' class=\"atable\">";
				echo "<tr>";
				    $day = date("l jS \of F Y", mktime(0, 0, 0, substr($row['startTime'], 5, 2), substr($row['startTime'], 8, 2), substr($row['startTime'], 0, 4)));		
					$res1 = substr($row['startTime'], 11, 5);
					$res2 = substr($row['endTime'], 11, 5);
					echo "<th>On " . $day . "</th>";
				echo "</tr>";
			echo "</table>";
			
			//Empty table as divider between event data & players
			/*echo "<table border='0' class=\"atable\" visibility=\"hidden\">";
				echo "<tr>";
					echo "<th></th>";
				echo "</tr>";
			echo "</table>";*/
			
			echo "<table border='0' class=\"atable\">";
				echo "<tr>";
					echo "<th>From " . $res1 . " to " . $res2 . "<img id=\"update_event\" width=\"30\" height=\"30\" src=\"images\edit.png\" style=\"float: right;z-index: 1;\" onClick=\"update_event($event_check)\"></img></th>";
				echo "</tr>";
			echo "</table>";
		}

		//Echo players for the event
		echo "<table border='0' class=\"atable2\">";
				echo "<tr>";				
				echo "<td class=\"col1\">" . $row['eventplayerid'] . "</td>";
				echo "<td class=\"col2\">" . $row['playerid'] . "</td>";
				if($row['seen'] == 1)
					echo "<td class=\"col3\"><img class=\"seen\" width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
				else
					echo "<td class=\"col3\"><img class=\"unseen\" width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
				echo "<td class=\"col4\">" . $row['name'] . "</td>";
				
				//Show on/off switch only for the user
				if($playerid != $row['playerid']) {
					if($row['areyouin'] == 0)
						echo "<td class=\"col5\">OUT</td>";
					else
						echo "<td class=\"col5\">IN</td>";					
				}
				else {
					if($row['areyouin'] == 0) {
						echo "<td class=\"col5\">";
							echo "<div class=\"onoffswitch\">";
								echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch" . $row_index . "\" checked>";
								echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row['eventplayerid'] . ", '1')\">";
								echo "<div class=\"onoffswitch-inner\"></div>";
								echo "<div class=\"onoffswitch-switch\"></div>";
								echo "</label>";
							echo "</div>";
						echo "</td>";
						
						//Update the seen status
						if($row['seen'] == 0) {
							$sql2= "UPDATE eventplayer SET seen = '1' WHERE EventPlayerID = " . $row['eventplayerid'] . "";
							$result2 = mysql_query($sql2);
						}	
					}
					else {
						echo "<td class=\"col5\">";
							echo "<div class=\"onoffswitch\">";
								echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch" . $row_index . "\">";
								echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row['eventplayerid'] . ", '0')\">";
								echo "<div class=\"onoffswitch-inner\"></div>";
								echo "<div class=\"onoffswitch-switch\"></div>";
								echo "</label>";
							echo "</div>";
						echo "</td>";
					
						//Update the seen status
						if($row['seen'] == 0) {
							$sql2= "UPDATE eventplayer SET seen = '1' WHERE EventPlayerID = " . $row['eventplayerid'] . "";
							$result2 = mysql_query($sql2);
						}					
					}	
				}
				//echo "<td class=\"col6\">" . $row['seen'] . "</td>";
			echo "</tr>";
		echo "</table>";
		
		$row_index = $row_index + 1;
	}	
	
	mysql_close($con);
	
?>
  