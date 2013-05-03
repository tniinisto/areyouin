<?php

	$q=$_GET["q"];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	//$sql="SELECT * FROM players WHERE playerID = '".$q."'";
	$sql="SELECT * FROM players";
	
	echo $sql;
	echo "<br />";
	echo "<br />";

	$result = mysql_query($sql);
  
	echo "<table border='1' id='atable'> 
	<tr>
	<th>PlayerID</th>
	<th>Firstname</th>
	<th>Mobile</th>
	<th>Email</th>
	<th>Photo</th>
	</tr>";

	while($row = mysql_fetch_array($result))
	{
		echo "<tr>";
		echo "<td>" . $row['playerID'] . "</td>";
		echo "<td>" . $row['name'] . "</td>";
		echo "<td>" . $row['mobile'] . "</td>";
		echo "<td>" . $row['email'] . "</td>";
		echo "header('"'Content-type: image/png'"');";
		echo "<td>" . $row['photo'] . "</td>";
		echo "</tr>";
	}
	echo "</table>";

	mysql_close($con);
  
	//echo "<b> Working </b>";

  ?>
  