<?php

	$q=$_GET["q"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	$sql="SELECT * FROM players";
	/*$sql = "select l.name as location, e.startTime, e.endTime, p.name, v.areyouin from events e, eventtype t, location l, players p,  eventplayer v
			where t.eventTypeID = e.EventType_eventTypeID and
			l.locationID = e.Location_locationID and
			p.playerID = v.Players_playerID and v.Events_eventID = e.eventID;"
	*/
	echo $sql;
	//echo "<br />";
	//echo "<br />";

	/*$result = mysql_query($sql);
  
	echo "<table border='1' id='atable'> 
	<tr>
	<th>PlayerID</th>
	<th>Firstname</th>
	<th>Mobile</th>
	<th>Email</th>
	<th>Photo</th>
	</tr>";

	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>" . $row['playerID'] . "</td>";
		echo "<td>" . $row['name'] . "</td>";
		echo "<td>" . $row['mobile'] . "</td>";
		echo "<td>" . $row['mail'] . "</td>";
		echo "<td> <img src=\"images/" . $row['photourl'] . "\"></td>";
		echo "</tr>";
	}
	echo "</table>";*/

	mysql_close($con);

  ?>
  