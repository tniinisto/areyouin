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

	//Array containing [playerID, checkbox]
	$players = array(); 
	$idpost = '';
	$oopost = '';
	for ($i=1; $i<=$playeramount; $i++)
	{
		$players[$i] = array();
		$idpost = "playerid" . $i;
		$oopost = "ooswitch" . $i;
		
		$players[$i][1] = $_POST[$idpost];
		$players[$i][2] = $_POST[$oopost];

		$players[$i][1] = stripslashes($players[$i][1]);
		$players[$i][2] = stripslashes($players[$i][2]);
	}

	// To protect MySQL injection
	$playeramount = stripslashes($playeramount);
	$gamestart = stripslashes($gamestart);
	$gamesend = stripslashes($gamesend);

	
	//Handle date format from 2013-05-29T01:01 -> 2013-07-27 17:30:00
	$gamestart = str_replace("T", " ", $gamestart);
	$gamestart = $gamestart . ":00";
	$gamesend = str_replace("T", " ", $gamesend);
	$gamesend = $gamesend . ":00";	
	//echo $gamestart;
	
	$sql = "INSERT INTO events (Location_locationID, EventType_eventTypeID, startTime, endTime, Team_teamID') VALUES ('1', '1', '" . $gamestart. "', '" . $gamesend . "', '1')";
	//INSERT INTO events (Location_locationID, EventType_eventTypeID, startTime, endTime, Team_teamID) VALUES ('1', '1', '2013-08-01 15:00:00', '2013-07-08 17:00:00', '1')
	echo $sql;
	
	$result = mysql_query($sql);
	echo $result;
	
	/*echo "insert_event.php, playeamount: " . $playeramount . " start: " . $gamestart . " end: " . $gamesend;
	echo "</br>";
	
	for ($j=1; $j<=$playeramount; $j++)
	{
		echo "playerID: " . $players[$j][1] . " checkbox value: " . $players[$j][2] . "";
		echo "</br>";
	}*/
	
	mysql_close($con);

?>
