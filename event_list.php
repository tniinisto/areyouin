<?php

	$teamid=$_GET["teamid"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT * FROM players";
	$sql = "select v.Events_eventID, l.name as location,l.position as pos, e.startTime, e.endTime, p.playerid, p.name, p.photourl, v.eventplayerid, v.areyouin, m.teamID, m.teamName, a.teamAdmin from events e, eventtype t, location l, players p,  eventplayer v, team m, playerteam a where t.eventTypeID = e.EventType_eventTypeID and l.locationID = e.Location_locationID and p.playerID = v.Players_playerID and v.Events_eventID = e.eventID and a.Players_playerID = p.playerID and a.Team_teamID = m.teamID and m.teamID = '" . $teamid  . "' order by e.startTime asc, v.Events_eventID asc";
	 	
	$result = mysql_query($sql);
	
	//Go through events & players
	$event_check = 0;
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
					echo "<th>From " . $res1 . " to " . $res2 . "</th>";
				echo "</tr>";
			echo "</table>";
			
		}

		//Echo players for the event
		echo "<table border='0' class=\"atable2\">";
			echo "<tr>";
				echo "<td class=\"col1\">" . $row['eventplayerid'] . "</td>";
				echo "<td class=\"col2\">" . $row['playerid'] . "</td>";
				echo "<td class=\"col3\"><img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
				echo "<td class=\"col4\">" . $row['name'] . "</td>";
				/*if($row['areyouin'] == 0)
					echo "<td class=\"col5\">OUT</td>";
				else
					echo "<td class=\"col5\">IN</td>";
				*/

				if($row['areyouin'] == 0) {
					echo "<td class=\"col5\">";
						echo "<div class=\"onoffswitch\">";
							echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch\ checked">";
							echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch\">";
							echo "<div class=\"onoffswitch-inner\"></div>";
							echo "<div class=\"onoffswitch-switch\"></div>";
							echo "</label>";
						echo "</div>";
					echo "</td>";
				}
				else {
					echo "<td class=\"col5\">";
						echo "<div class=\"onoffswitch\">";
							echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch\">";
							echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch\">";
							echo "<div class=\"onoffswitch-inner\"></div>";
							echo "<div class=\"onoffswitch-switch\"></div>";
							echo "</label>";
						echo "</div>";
					echo "</td>";
				}					
			echo "</tr>";
		echo "</table>";		
	}	
	
	/*
	//Content header
	$row = mysql_fetch_array($result);
	echo "<h1 onClick=\"getEvents()\">Games set for " . $row['teamName'] . "</h1>";

	//sql cursor move function, to index 0
	mysql_data_seek($result , 0);	

	//Go through events & players
	$event_check = 0;
	while($row = mysql_fetch_array($result))
	{
		//Check when the event changes, then echo the event basic information
		if($row['Events_eventID'] != $event_check)
		{		
			$event_check = $row['Events_eventID'];	
			
			echo "<table border='0' id='atable'>";
				echo "<tr>";
					echo "<th> Games at:" . $row['location'] . " </th>";
					echo "<th> Starting:" . $row['startTime'] . " </th>";
					echo "<th> Ending:" . $row['endTime'] . " </th>";
				echo "</tr>";
			echo "</table>";
		}

		//Echo players for the event
		echo "<table border='0' id='atable2'>";
			echo "<tr>";
				echo "<td> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
				echo "<td>" . $row['name'] . "</td>";
				if($row['areyouin'] == 0)
					echo "<td>I'm OUT</td>";
				else
					echo "<td>I'm IN</td>";
			echo "</tr>";
		echo "</table>";		
	}*/
	
	
	//Table header
	/*echo "<table border='0' id='atable'>
	<tr>
	<th>Location</th>
	<th>Start time</th>
	<th>End time</th>
	</tr>
	</table>";

	//sql cursor move function, to index 0
	mysql_data_seek($result , 0);	
	
	//Go through events & players
	$event_check = 0;
	while($row = mysql_fetch_array($result))
	{
		//Check when the event changes, then echo the event basic information
		if($row['Events_eventID'] != $event_check)
		{		
			$event_check = $row['Events_eventID'];	
			
			echo "<table border='1' id='atable2'>";
			echo "<tr>";
			echo "<td>" . $row['location'] . "</td>";
			echo "<td>" . $row['startTime'] . "</td>";
			echo "<td>" . $row['endTime'] . "</td>";
			echo "</tr>";
			echo "</table>";
		}

		//Echo players for the event
		echo "<table border='1' id='atable3'>";
		echo "<tr>";
		echo "<td> <img width=\"20\" height=\"20\" src=\"images/" . $row['photourl'] . "\"></td>";
		echo "<td>" . $row['name'] . "</td>";
		if($row['areyouin'] == 0)
			echo "<td>I'm OUT</td>";
		else
			echo "<td>I'm IN</td>";
		echo "</tr>";
		echo "</table>";		
	}*/
	
	mysql_close($con);
	
?>
  