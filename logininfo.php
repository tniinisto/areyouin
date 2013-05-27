<?php

	$pl=$_GET["p"];
	$te=$_GET["t"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	$sql="SELECT p.name, t.teamName FROM players p, team t where p.playerID = " . $pl . " and t.teamID = " . $te;
	//$sql="SELECT p.name, t.teamName, r.teamadmin FROM players p, team t, playerteam r where p.playerID = " . $pl . " and t.teamID = " . $te . " and r.players_playerid = " . $pl;
	
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
  
    //echo $sql;
	echo "<font color=\"#ccc\">Welcome: </font>" . $row[name] . " <font color=\"#ccc\">Team: </font>" . $row[teamName]; //. "<style display=\"visible\">" . $row[teamadmin] . "</style>";
	//echo "Welcome: " . $row[name] . " Team: </font>" . $row[teamname];
	
	mysql_close($con);

  ?>