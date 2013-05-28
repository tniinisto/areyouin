<?php
	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	$sql="SELECT playerID, name, photourl FROM players";

	$result = mysql_query($sql);
	echo "<article id=\"admin_content_article\" class=\"clearfix\">";
	echo "<h1>Enter new game</h1>";
	echo "<form id=\"eventform\">";
	echo "<h2>Set Time</h2>";
	echo "<label>Game start:</label>";
	echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required></input>";
	echo "<label>Game end:</label>";
	echo "<input type=\"datetime-local\" id=\"gamesend_id\" name=\"gamesend\" required></input>";

	$row_index = 1; 
	echo "<table border='1' id='insertplayers' class=\"atable2\">"; 
	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td class=\"col1\">" . $row['playerID'] . "</td>";
		echo "<td class=\"col2\">" . $row['playerID'] . "</td>";
		echo "<td class=\"col3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
		echo "<td class=\"col4\">" . $row['name'] . "</td>";
		echo "<td class=\"col5\"><input class=\"cb\" type=\"checkbox\" id=\"row" . $row_index . "\"></td>";
		echo "</tr>";
		
		$row_index = $row_index + 1;
	}
	echo "</table>";
	
	echo "<input type=\"submit\" value=\"Create Game\" id=\"submitgame\"></input>"; 
	echo "</form>";
	echo "</article>";
	
	mysql_close($con);
?>
