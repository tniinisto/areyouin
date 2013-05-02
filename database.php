<?php

	$q=$_GET["q"];

	$con = mysql_connect('localhost', 'root', 'trinity');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("ruin", $con);

	$sql="SELECT * FROM players WHERE playerID = '".$q."'";
	
	echo $sql;
	echo "<br />";
	echo "<br />";

	$result = mysql_query($sql);
  
  echo "<table border='1'>
  <tr>
  <th>PlayerID</th>
  <th>Firstname</th>
  <th>Mobile</th>
  <th>Email</th>
  </tr>";

  while($row = mysql_fetch_array($result))
    {
    echo "<tr>";
		echo "<td>" . $row['playerID'] . "</td>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . $row['mobile'] . "</td>";
    echo "<td>" . $row['email'] . "</td>";
    echo "</tr>";
    }
  echo "</table>";

	mysql_close($con);
  
  //echo "<b> Working </b>";

  ?>
  