<?php
	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	$sql="SELECT p.playerID, p.name, p.photourl FROM players p, team t where t.teamID = '1'";
	
	$result = mysql_query($sql);
	$row_count = mysql_num_rows($result);

	echo "<article id=\"admin_content_article\" class=\"clearfix\">";
	echo "<h1>Enter new game</h1>";
	echo "<form id=\"eventform\" method=\"post\" action=\"insert_event.php\">";
	echo "<h2>Set Time</h2>";
	echo "<label><h2>Game start:</h2></label>";
	echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required></input>";
	echo "<label><h2>Game end:</h2></label>";
	echo "<input type=\"datetime-local\" id=\"gamesend_id\" name=\"gamesend\" required></input>";

	$row_index = 1; 
	echo "<table border='0' id='insertplayers' class=\"atable2\">"; 
	
	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td class=\"col1\"><input type=\"number\" name=\"playeramount\" value=\"" . $row_count . "\"></input></td>";
		echo "<td class=\"col2\"><input type=\"number\" name=\"playerid" . $row_index . "\" value=\"" . $row['playerID'] . "\"></input></td>";
		echo "<td class=\"col3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
		echo "<td class=\"col4\">" . $row['name'] . "</td>";
		echo "<td class=\"col5\">";
			echo "<div class=\"onoffswitch\">";
				echo "<input type=\"checkbox\" name=\"ooswitch" . $row_index . "\" class=\"onoffswitch-checkbox\" id=\"myonoff" . $row_index . "\" checked>";
				echo "<label class=\"onoffswitch-label\" for=\"myonoff" . $row_index . "\">";
				echo "<div class=\"onoffswitch-inner\"></div>";
				echo "<div class=\"onoffswitch-switch\"></div>";
				echo "</label>";
			echo "</div>";
		echo "</td>";
		echo "</tr>";
		
		$row_index = $row_index + 1;
	}
	echo "</table>";
	echo "</br>";
	echo "</br>";
	echo "<input type=\"submit\" value=\"Create Game\" id=\"submitgame\"></input>"; 
	echo "</form>";
	echo "</article>";
	
	mysql_close($con);
?>
