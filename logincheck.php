<?php
    
    //For some reason logincheck does not print to console???
    //include 'ChromePhp.php';
    //ChromePhp::log('Hello console!');

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con)or die("cannot select DB");

    session_start();


	// username and password sent from form
	$myusername=$_POST['ayiloginName'];
	$mypassword=$_POST['ayipassword'];

	// To protect MySQL injection
	$myusername = stripslashes($myusername);
	$mypassword = stripslashes($mypassword);
	$myusername = mysql_real_escape_string($myusername);
	$mypassword = mysql_real_escape_string($mypassword);

    $mymd5 = md5($mypassword);

	//$sql="SELECT * FROM players WHERE name='$myusername' and password='$mypassword'";
	$sql="SELECT p.playerID, p.name, t.teamID, t.teamName, m.teamAdmin
    FROM areyouin.players p, playerteam m, team t
    WHERE name = '$myusername' and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid
    ORDER BY t.teamID";

	$result=mysql_query($sql);

	// Mysql_num_row is counting table row
	$count=mysql_num_rows($result);

	if($count>=1){
		// Register $myusername, $mypassword and redirect to file "index.html"
		//session_register("myusername");
		//session_register("mypassword");
		$row = mysql_fetch_array($result);
		
		//header("location:index.html?userid=" . $row[playerID] . "&username=$myusername&teamid=" . $row[teamID] . "&teamname=" . $row[teamName]);
		//header("location:index.html?p=" . $row[playerID] . "&t=" . $row[teamID]);

        session_register("myusername");
        $_SESSION['myusername'] = $myusername;
        session_register("mypassword");
        $_SESSION['mypassword'] = md5($mypassword);

        session_register("myplayerid");
        $_SESSION['myplayerid'] = $row[playerID];
        session_register("myteamid");
        $_SESSION['myteamid'] = $row[teamID];

        $_SESSION['myAdmin'] = $row[teamAdmin];

        header("location:login_success.php");
	}
	else {
		//echo $sql;		
        echo "<h1> Wrong Username or Password </h1>";
        //header("location:default.html");
	}
	
	mysql_close($con);
    
?>



