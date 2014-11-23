<?php
    //For some reason logincheck does not print to console???
    include 'ChromePhp.php';

    //ini_set('default_charset', 'UTF-8');

    session_start();

    //For LOGGING enable/disable//
    //$_SESSION['ChromeLog'] = FALSE;
    //if(!@include("ChromePhp.php"))
        $_SESSION['ChromeLog'] = TRUE;
    //////////////////////////////

    if($_SESSION['ChromeLog']) {
        ChromePhp::log('logincheck.php, start');
    }

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con)or die("cannot select DB");
   

    //For session expiration checking
    $_SESSION['logged_in'] = FALSE;

	// username and password sent from form
	$myusername=$_POST['ayiloginName'];
	$mypassword=$_POST['ayipassword'];

	// To protect MySQL injection
	$myusername = stripslashes($myusername);
	$mypassword = stripslashes($mypassword);
	$myusername = mysql_real_escape_string($myusername);
	$mypassword = mysql_real_escape_string($mypassword);

    $mymd5 = md5($mypassword);

	//$sql="SELECT * FROM players WHERE name='$myusername' and password='$mymd5'";
	$sql="SELECT p.playerID, p.name, t.teamID, t.teamName, m.teamAdmin
    FROM areyouin.players p, playerteam m, team t
    WHERE name = '$myusername' and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid
    ORDER BY t.teamID";

	$result=mysql_query($sql);

	// Mysql_num_row is counting table row
	$count=mysql_num_rows($result);

    if($_SESSION['ChromeLog']) {
        ChromePhp::log('logincheck.php, $count: ', $count);
    }

	if($count>=1){

        //For session expiration checking
        $_SESSION['logged_in'] = TRUE;

		// Register $myusername, $mypassword and redirect to file "index.html"
		//session_register("myusername");
		//session_register("mypassword");
		$row = mysql_fetch_array($result);
		
        if($_SESSION['ChromeLog']) {
            ChromePhp::log('logincheck.php, mysql_fetch_array()');
        }

		//header("location:index.html?userid=" . $row[playerID] . "&username=$myusername&teamid=" . $row[teamID] . "&teamname=" . $row[teamName]);
		//header("location:index.html?p=" . $row[playerID] . "&t=" . $row[teamID]);

        //session_register("myusername");
        if($_SESSION['ChromeLog']) {
            ChromePhp::log('logincheck.php, session_register()');
        }

        $_SESSION['myusername'] = $myusername;
        if($_SESSION['ChromeLog']) {
            ChromePhp::log('logincheck.php, $_SESSION[\'myusername\']: ', $_SESSION['myusername']);
        }

        $_SESSION['mypassword'] = md5($mypassword);
        $_SESSION['myplayerid'] = $row['playerID'];
        $_SESSION['myteamid'] = $row['teamID'];
        $_SESSION['myAdmin'] = $row['teamAdmin'];

        if($_SESSION['ChromeLog']) {
            ChromePhp::log('logincheck.php, $playerid: ', $row['playerID']);
        }

        //ChromePhp::log("logincheck.php, logged_in:", $_SESSION['logged_in']);

        mysql_close($con);

        header("location:login_success.php");
	}
	else { //Login failed

        //header("location:default.html");

        echo "<html lang=\"en()\">";
        echo "<head>";
        echo "<meta charset=\"utf-8\">";

        echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";

        echo "<title>R'YouIN</title>";

        echo "<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
        echo "<link href=\"media-queries.css\" rel=\"stylesheet\" type=\"text/css\">";

        echo "<script type=\"text/javascript\" src=\"main.js\"> </script>";
        echo "<script src=\"http://code.jquery.com/jquery-2.0.0.min.js\"></script>";

        echo "</head>";

        echo "<body>";
            echo "<div id=\"pagewrap\">";

                echo "<div id=\"loginwrapper\">";

			        echo "<h1 id=\"loginsite-logo\">R'YouIN</h1>";

                    echo "<fieldset id=\"loginfailfs\">";
                        echo "<h1>Login failed</h1>";
                        echo "<h2>Check your username & password</h2>";
                        echo "</br>";
                        echo "<a href='default.html'>Back to login</a>";
                        echo "<h1></h1>";
                    echo "</fieldset>";
                echo "</div>";
            echo "</div>";

        echo "</body>";
        echo "</html>";
    
    }

    mysql_close($con);

?>