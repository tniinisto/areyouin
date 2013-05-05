 <?php

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	$sql="SELECT name, photourl FROM players";
	
	echo $sql;
	echo "<br />";
	//echo "<br />";
	
	echo "<ul id=\"players_short\">"
	echo	"<li><a href=\"#\">Fiona2</a></li>"
	echo "</ul>"

	//$result = mysql_query($sql);
  	
	//echo "<ul id='players_short'>"
	/*while($row = mysql_fetch_array($result))
	{
		echo "<li> <img src=\"images/" . $row['photourl'] . "\" ". .$row['name'] . "></li>";
		//echo "<li>" .$row['name'] . "</li>";
	}*/
	//echo "</ul>";

	mysql_close($con);

  ?>