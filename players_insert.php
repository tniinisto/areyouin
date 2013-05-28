<?php
	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');

	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	$sql="SELECT playerID, name, photourl FROM players";

	$result = mysql_query($sql);
  	
	$row_index = 1; 
	echo "<table border='0' id='insertplayers' class=\"atable2\">"; 
	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td class=\"col1\">playerID " . $row['playerID'] . "</td>";
		echo "<td class=\"icol1\"> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
		echo "<td class=\"icol2\">" . $row['name'] . "</td>";
		echo "<td class=\"icol3\"> <input type=\"checkbox\" id=\"row" . $row_index . "\"<\input></td>";
		echo "</tr>";
		
		$row_index = $row_index + 1;
	}
	echo "</table>";	
	
	mysql_close($con);
?>
