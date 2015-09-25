<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    ////////////////////////////////////////////////////////
    //Uncomment to enable ChromePhp-logging
    //include 'ChromePhp.php';
    ////////////////////////////////////////////////////////

    //ini_set('default_charset', 'UTF-8');

    session_start();

    //For PHP LOGGING enable/disable////////////////////////
    $_SESSION['ChromeLog'] = FALSE;
    $included_files = get_included_files();
    foreach ($included_files as $filename) {
        if(strpos($filename,'ChromePhp') !== false)
            $_SESSION['ChromeLog'] = TRUE;
    }
    ////////////////////////////////////////////////////

    if($_SESSION['ChromeLog']) { ChromePhp::log('logincheck.php, start'); }

	
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
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
	$sql="SELECT p.playerID, p.name, t.teamID, t.teamName, t.timezone, t.utcOffset, m.teamAdmin
    FROM areyouin.players p, playerteam m, team t
    WHERE (name = '$myusername' OR mail = '$myusername') and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid
    ORDER BY t.teamID";

	$result=mysql_query($sql);

	// Mysql_num_row is counting table row
	$count=mysql_num_rows($result);

    if($_SESSION['ChromeLog']) { ChromePhp::log('logincheck.php, $count: ', $count); }

	if($count>=1){

        //For session expiration checking
        $_SESSION['logged_in'] = TRUE;

		// Register $myusername, $mypassword and redirect to file "index.html"
		//session_register("myusername");
		//session_register("mypassword");
		$row = mysql_fetch_array($result);
		
        if($_SESSION['ChromeLog']) { ChromePhp::log('logincheck.php, mysql_fetch_array()'); }

		//header("location:index.html?userid=" . $row[playerID] . "&username=$myusername&teamid=" . $row[teamID] . "&teamname=" . $row[teamName]);
		//header("location:index.html?p=" . $row[playerID] . "&t=" . $row[teamID]);

        $_SESSION['myusername'] = $myusername;        
        if($_SESSION['ChromeLog']) { ChromePhp::log('logincheck.php, $_SESSION[\'myusername\']: ', $_SESSION['myusername']); }
        $_SESSION['mypassword'] = md5($mypassword);
        $_SESSION['myplayerid'] = $row['playerID'];
        $_SESSION['myteamid'] = $row['teamID'];
        $_SESSION['myAdmin'] = $row['teamAdmin'];
        $_SESSION['mytimezone'] = $row['timezone'];
        $_SESSION['myoffset'] = $row['utcOffset'];

        if($_SESSION['ChromeLog']) { ChromePhp::log('logincheck.php, $playerid: ', $row['playerID']); }

        //ChromePhp::log("logincheck.php, logged_in:", $_SESSION['logged_in']);

        //User belogns to multiple teams
        if($count > 1) {
            echo "<html lang=\"en()\">";
            echo "<head>";
            echo "<meta charset=\"utf-8\">";

            echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";

            echo "<title>R'YouIN</title>";

            echo "<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
            echo "<link href=\"media-queries.css\" rel=\"stylesheet\" type=\"text/css\">";

            echo "<script type=\"text/javascript\" src=\"main.js\"> </script>";
            echo "<script src=\"js/jquery-2.0.0.min.js\"></script>";

            //echo "<script type=\"text/javascript\">";
            //    echo "function goIndex() {";                    
            //        echo "var e = document.getElementById('team_select');";
            //        echo "var teamID = e.options[e.selectedIndex].value;";
            //        echo "alert('teamid: ' + teamID);";                
            //    echo "}";
            //echo "</script>";

            echo "</head>";

            echo "<body>";
                echo "<div id=\"pagewrap\">";

                    echo "<div id=\"loginwrapper\">";

			            echo "<h1 id=\"loginsite-logo\">R'YouIN</h1>";
                        echo "<br />";

                        echo "<fieldset id=\"loginfailfs\">";
                            echo "<h2 style='margin: 5px 0 .5em;'>Select your Team</h2>";
                            
                            echo "<form id=\"teamform\" method=\"post\" action=\"setTeam.php\">";                                
                                echo "<select id=\"team_select\" name=\"teamselect\" form=\"teamform\">";                                
                                    mysql_data_seek($result, 0);                            
                                    while($row = mysql_fetch_array($result)){
                                            echo "<option value=\"" . $row['teamID'] . "\">" . $row['teamName'] . "</option>";                               
                                    }
                                echo "</select>";
                                echo "<br />";
                                echo "<input class='linkButton' type='submit' value='Login' id='submit_team' class='myButton'></input>";
                                //echo "<a href='#' onclick='this.submit();'>Login</a>";
                            echo "</form>";                            
                            echo "<h1></h1>";
                        echo "</fieldset>";
                    echo "</div>";
                echo "</div>";

                //echo "<iframe name=\"frame_team\" style=\"display: none;\"></iframe>";

            echo "</body>";
            echo "</html>";
            
        } 
        else {
            header('Location:login_success.php');
        }
            
        mysql_close($con);

        
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
        echo "<script src=\"js/jquery-2.0.0.min.js\"></script>";

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