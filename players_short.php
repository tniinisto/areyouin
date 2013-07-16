<?php
	$teamid=$_GET["teamid"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT name, photourl FROM players";
	$sql="SELECT p.name, p.photourl FROM players p, playerteam t where t.team_teamid ='" . $teamid . "' and p.playerid = t.players_playerid";
	
	$result = mysql_query($sql);
  	
	echo "<table border='0' id='players_short'>"; 
	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
		echo "<td>" . $row['name'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";	
	
	mysql_close($con);
?>
