<?php

	$q=$_GET["q"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT * FROM players";
	$sql = "select l.name as location, e.startTime, e.endTime, p.name, v.areyouin from events e, eventtype t, location l, players p,  eventplayer v where t.eventTypeID = e.EventType_eventTypeID and l.locationID = e.Location_locationID and p.playerID = v.Players_playerID and v.Events_eventID = e.eventID";
	 	
	$result = mysql_query($sql);

	//Table header
	echo "<table border='1' id='atable'>
	<tr>
	<th>Location</th>
	<th>Start time</th>
	<th>End time</th>
	</tr>";
	
	//Table for event basic information
	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>" . $row['location'] . "</td>";
		echo "<td>" . $row['startTime'] . "</td>";
		echo "<td>" . $row['endTime'] . "</td>";
		echo "</tr>";
		break;
	}

	//Table for event participants
	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>" . $row['name'] . "</td>";
		if($row['areyouin'] == 0)
			echo "<td>I'm OUT</td>";
		else
			echo "<td>I'm IN</td>";
		echo "</tr>";
	}
	
	echo "</table>";
	
	mysql_close($con);

  ?>
  