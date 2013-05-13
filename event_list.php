<?php

	$q=$_GET["q"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT * FROM players";
	$sql = "select v.Events_eventID, l.name as location, e.startTime, e.endTime, p.name, p.photourl, v.areyouin, m.teamID, m.teamName, a.teamAdmin from events e, eventtype t, location l, players p,  eventplayer v, team m, playerteam a where t.eventTypeID = e.EventType_eventTypeID and l.locationID = e.Location_locationID and p.playerID = v.Players_playerID and v.Events_eventID = e.eventID and a.Players_playerID = p.playerID and a.Team_teamID = m.teamID order by e.startTime asc, v.Events_eventID asc";
	 	
	$result = mysql_query($sql);
	
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
				echo "<th>";
					echo "<td> Games at:" . $row['location'] . " </td>";
					echo "<td> Starting:" . $row['startTime'] . " </td>";
					echo "<td> Ending:" . $row['endTime'] . " </td>";
				echo "</th>";
			echo "</table>";
		}

		//Echo players for the event
		echo "<table border='1' id='atable2'>";
			echo "<tr>";
				echo "<td> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
				echo "<td>" . $row['name'] . "</td>";
				if($row['areyouin'] == 0)
					echo "<td>I'm OUT</td>";
				else
					echo "<td>I'm IN</td>";
			echo "</tr>";
		echo "</table>";		
	}
	
	
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
  