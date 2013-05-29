<?php

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con)or die("cannot select DB");
	
	$playeramount=$_POST['playeramount'];	  
	  
	// To protect MySQL injection
	$playeramount = stripslashes($playeramount);

	//$sql="SELECT * FROM players WHERE playerID = '".$q."'";
	//$sql= "UPDATE eventplayer SET areyouin = '" . $areyouin . "' WHERE EventPlayerID = '".$eventplayerid."'";
	
	//echo $sql;

	//$result = mysql_query($sql);
	
	echo "insert_event.php, playeamount: " . $playeramount;
	
	mysql_close($con);

?>
