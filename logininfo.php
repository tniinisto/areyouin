<?php

	$p=$_GET["p"];
	//$t=$_GET["t"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT p.name, t.teamName FROM players p, team t where p.playerID = ".$p." and t.teamID = ".$t;
	$sql="SELECT p.name, t.teamName FROM players p, team t where p.playerID = '".$p."'";
	
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
  
	echo $p;
    echo $sql;
	//echo "<font color=\"#ccc\">Welcome: </font>" . $row[name] . " <font color=\"#ccc\">Team: </font>" . $row[teamName]);

	mysql_close($con);

  ?>