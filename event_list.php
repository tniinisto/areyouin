<?php

	$q=$_GET["q"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT * FROM players";
	$sql = "select v.Events_eventID, l.name as location, e.startTime, e.endTime, p.name, v.areyouin from events e, eventtype t, location l, players p,  eventplayer v where t.eventTypeID = e.EventType_eventTypeID and l.locationID = e.Location_locationID and p.playerID = v.Players_playerID and v.Events_eventID = e.eventID order by e.startTime asc, v.Events_eventID asc";
	 	
	$result = mysql_query($sql);

	//Table header
	echo "<table border='1' id='atable'>
	<tr>
	<th>Location</th>
	<th>Start time</th>
	<th>End time</th>
	</tr>
	</table>";
	
	//Go through the first event & players
	$event_check = 0;
	while($row = mysql_fetch_array($result))
	{
		//Check when the event changes, then echo the event basic information
		if($row['Events_eventID'] != $event_check)
		{
			$event_check = $row['Events_eventID'];
			
			echo "<table border='1' id='atable2'>"
			echo "<tr>";
			echo "<td>" . $row['location'] . "</td>";
			echo "<td>" . $row['startTime'] . "</td>";
			echo "<td>" . $row['endTime'] . "</td>";
			echo "</tr>";
			echo "</table>";
		}

		//Echo players for the event
		echo "<table border='1' id='atable3'>"
		echo "<tr>";
		echo "<td>" . $row['name'] . "</td>";
		if($row['areyouin'] == 0)
			echo "<td>I'm OUT</td>";
		else
			echo "<td>I'm IN</td>";
		echo "</tr>";
		echo "</table>";			
	}

	//sql cursor move function
	//mysql_data_seek($result , 0);
	
	mysql_close($con);
	
  ?>
  