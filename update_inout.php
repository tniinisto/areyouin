<?php

	$eventplayerid=$_GET["event"];
	$areyouin=$_GET["ayi"];	

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT * FROM players WHERE playerID = '".$q."'";
	$sql= "UPDATE eventplayer SET areyouin = '" . $areyouin . "' WHERE EventPlayerID = '".$eventplayerid."'";
	
	//echo $sql;

	$result = mysql_query($sql);

	echo $result;

	mysql_close($con);

  ?>