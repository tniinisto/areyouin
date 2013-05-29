<?php

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con)or die("cannot select DB");
	
	//Post variables
	$playeramount=$_POST['playeramount'];
	$gamestart=$_POST['gamestart'];
	$gamesend=$_POST['gamesend'];

	$p2layers1=$_POST['playerid1']; 
	$p2layers2=$_POST['ooswitch1']; 
	
	$players = array(); //(playerID, checkbox)
	$idpost = '';
	$oopost = '';
	for ($i=1; $i<=$playeramount; $i++)
	{
		$idpost = "'playerid" . $i . "'";
		$oopost = "'ooswitch" . $i . "'";
	
		$players[i][1] = "test" . $i; //$_POST[$idpost];
		$players[i][2] = "2test" . $i; //$_POST[$oopost];

		$players[i][1] = stripslashes($players[i][1]);
		$players[i][2] = stripslashes($players[i][2]);
	}

	// To protect MySQL injection
	$playeramount = stripslashes($playeramount);
	$gamestart = stripslashes($gamestart);
	$gamesend = stripslashes($gamesend);

	$p2layers1 = stripslashes($p2layers1);
	$p2layers2 = stripslashes($p2layers2);

	//$sql="SELECT * FROM players WHERE playerID = '".$q."'";
	//$sql= "UPDATE eventplayer SET areyouin = '" . $areyouin . "' WHERE EventPlayerID = '".$eventplayerid."'";
	
	//echo $sql;
	//$result = mysql_query($sql);
	
	echo "insert_event.php, playeamount: " . $playeramount . " start: " . $gamestart . " end: " . $gamesend;
	echo "</br>";
	echo "</br>";
	echo "playerID: " . $p2layers1 . " checkbox value: " . $p2layers2;
	echo "</br>";
	
	for ($j=1; $j<=$playeramount; $j++)
	{
		echo "playerID: " . $players[j][1] . " checkbox value: " . $players[j][2] . "";
		echo "</br>";
	}
	
	mysql_close($con);

?>
