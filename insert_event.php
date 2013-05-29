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

	$players1=$_POST['playerid1']; 
	$players2=$_POST['ooswitch1']; 
	
	/*$players = array(); //(playerID, checkbox)
	for ($i=1; $i<=$playeramount; $i++)
	{
		//echo "The number is " . $i . "<br>";
		//$players[i][1] = $_POST["'playerid" . $i ."'"];
		//$players[i][2] = $_POST["'onoffswitch" . $i ."'"];
		$players[i][1] = $_POST['playerid1'];
		$players[i][2] = $_POST['ooswitch1'];
	}*/

	// To protect MySQL injection
	$playeramount = stripslashes($playeramount);
	$gamestart = stripslashes($gamestart);
	$gamesend = stripslashes($gamesend);

	//$sql="SELECT * FROM players WHERE playerID = '".$q."'";
	//$sql= "UPDATE eventplayer SET areyouin = '" . $areyouin . "' WHERE EventPlayerID = '".$eventplayerid."'";
	
	//echo $sql;
	//$result = mysql_query($sql);
	
	echo "insert_event.php, playeamount: " . $playeramount . " start: " . $gamestart . " end: " . $gamesend;
	echo "</br>";
	echo "playerID: " . $players1 . " checkbox value: " . $players2;
	
	/*for ($j=1; $j<=$playeramount; $j++)
	{
		echo "playerID: " . $players[j][1] . " checkbox value: " . $players[j][2] . "";
		echo "</br>";
	}*/
	
	mysql_close($con);

?>
