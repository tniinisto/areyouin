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
	 
	//Table for event basic information
	$result1 = mysql_query($sql);
	
	//echo "<table border='0' id='atable'> 
	echo "
		<tr>
		<th>Location</th>
		<th>Start time</th>
		<th>End time</th>
		</tr>";
		while($row = mysql_fetch_array($result1))
		{
			echo "<tr>";
			echo "<td>" . $row['location'] . "</td>";
			echo "<td>" . $row['startTime'] . "</td>";
			echo "<td>" . $row['endTime'] . "</td>";
			echo "</tr>";
			break;
		}
	//echo "</table>";

	//Table for event participants
	$result2 = mysql_query($sql);
	
	//echo "<table border='0' id='atable'> 
	echo "
		<tr>
		<th>Player</th>
		<th>A'youIN</th>
		</tr>";	
		while($row = mysql_fetch_array($result2))
		{
			echo "<tr>";
			echo "<td>" . $row['name'] . "</td>";
			if($row['areyouin'] == 0)
				echo "<td>I'm OUT</td>";
			else
				echo "<td>I'm IN</td>";
			echo "</tr>";
		}
	//echo "</table>";
	
	mysql_close($con);

  ?>
  