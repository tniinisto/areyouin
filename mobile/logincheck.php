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
    // if($_SESSION['ChromeLog'] == TRUE) {

    //     $included_files = get_included_files();
    //     foreach ($included_files as $filename) {
    //         if(strpos($filename,'ChromePhp') !== false)
    //             $_SESSION['ChromeLog'] = TRUE;
    //     }

    // }
    ////////////////////////////////////////////////////

    // $con = mysql_connect($dbhost, $dbuser, $dbpass);
	// if (!$con)
	//   {
	//   die('Could not connect: ' . mysql_error());
	//   }

	// mysql_select_db($dbname, $con)or die("cannot select DB");
   
    //More sustainable db connection
    $con = 0;
    if($con == 0){
        $i=0;

        while($con == 0 && $i!=3){
            $con = mysql_connect($dbhost, $dbuser, $dbpass, true);
            mysql_select_db($dbname, $con);
            
            sleep(1);
            $i++;
        }

        if($con == 0){
            //Connection error, back to login with message...
            header('Location:default.html'); 
        } else
            mysql_select_db($dbname, $con);       
    }

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

	$sql="SELECT x.count, p.playerID, p.name, t.teamID, t.teamName, t.timezone, t.utcOffset, m.teamAdmin, m.registrar, m.lastMsg, r.licensevalid
    FROM players p, playerteam m, team t, registration r, 
        
        (SELECT count(*) as count
        FROM players p, playerteam m, team t, registration r
        WHERE (name = '$myusername' OR mail = '$myusername') and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid and t.teamid <> 0 and r.team_teamid = t.teamid) as x

    WHERE (name = '$myusername' OR mail = '$myusername') and password = '$mymd5' and p.playerID = m.Players_playerID and m.Team_teamID = t.teamid and t.teamid <> 0 and r.team_teamid = t.teamid
    ORDER BY t.teamID";

    $count = 0;
  	$result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $count =  $row['count'];

	if($count>=1){

        //For session expiration checking
        $_SESSION['logged_in'] = TRUE;

        $_SESSION['myusername'] = $myusername;        
        $_SESSION['mypassword'] = md5($mypassword);
        $_SESSION['myplayerid'] = $row['playerID'];
        $_SESSION['myteamid'] = $row['teamID'];
        $_SESSION['myteamname'] = $row['teamName'];
        $_SESSION['myAdmin'] = $row['teamAdmin'];
        $_SESSION['myRegistrar'] = $row['registrar'];
        $_SESSION['mytimezone'] = $row['timezone'];
        $_SESSION['myoffset'] = $row['utcOffset'];
        $_SESSION['mylicense'] = $row['licensevalid'];

        //User belogns to multiple teams
        if($count > 1) {

            echo "<html lang=\"en()\">";
            echo "<head>";
            echo "<meta charset=\"utf-8\">";

            echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, user-scalable=0\">";

            echo "<title>R'YouIN</title>";

            echo "<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
            echo "<link href=\"media-queries.css\" rel=\"stylesheet\" type=\"text/css\">";

            echo "<script type=\"text/javascript\" src=\"main.js\"> </script>";
            echo "<script src=\"js/jquery-2.0.0.min.js\"></script>";
            echo "<script type='text/javascript' src='js/spin.min.js'></script>";            

            echo "</head>";

            echo "<body>";
                echo "<div id='pagewrap'>";
                    echo "<div id='loginwrapper'>";
			            echo "<div>";
                            echo "<h1 id='loginsite-logo' style='margin-top: 10px;'>R'YouIN</h1>";
                        echo "</div>";
                        //echo "<br />";
                        echo "<div id='spinnerteamlogin_id' class='spin'></div>";
                        echo "<br />";
                        //echo "<br />";
                        echo "<fieldset id=\"loginfailfs\">";
                            echo "<h2 style='margin: 5px 0 .5em;'>Select your Team</h2>";
                            
                            echo "<form id=\"teamform\" method=\"post\" action=\"setTeam.php\">";                                
                                echo "<select id=\"team_select\" name=\"teamselect\" form=\"teamform\">";                                
                                    mysql_data_seek($result, 0);                            
                                    while($row = mysql_fetch_array($result)){
                                            echo "<option value='" . $row['teamID'] . " | " . $row['teamName'] . "'>" . $row['teamName'] . "</option>";                               
                                    }
                                echo "</select>";
                                echo "<br />";

                                echo "<input class='myButton' type='submit' value='Select' id='submit_team' onClick='startLoginSpinner();'></input>";
                                
                            echo "</form>";                            
                            echo "<h1></h1>";
                        echo "</fieldset>";
                    echo "</div>";
                echo "</div>";

                echo "<script  type='text/javascript'>";
                    echo "var spinnerTeamlogin;";

                    echo "var opts = {
                       lines: 15 // The number of lines to draw
                        , length: 2 // The length of each line
                        , width: 4 // The line thickness
                        , radius: 10 // The radius of the inner circle
                        , scale: 1 // Scales overall size of the spinner
                        , corners: 1 // Corner roundness (0..1)
                        , color: '#fff' // #rgb or #rrggbb or array of colors
                        , opacity: 0.25 // Opacity of the lines
                        , rotate: 0 // The rotation offset
                        , direction: 1 // 1: clockwise, -1: counterclockwise
                        , speed: 1 // Rounds per second
                        , trail: 60 // Afterglow percentage
                        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
                        , zIndex: 2e9 // The z-index (defaults to 2000000000)
                        , className: 'spinner' // The CSS class to assign to the spinner
                        , top: '100px;' // Top position relative to parent
                        , left: '50%' // Left position relative to parent
                        , shadow: false // Whether to render a shadow
                        , hwaccel: false // Whether to use hardware acceleration
                        , position: 'fixed' // Element positioning
                    };";

                    echo "spinnerTeamlogin = new Spinner(opts);";

                    echo "function startLoginSpinner() {";
                        echo "var target = document.getElementById('spinnerteamlogin_id');";
                        echo "spinnerTeamlogin.spin(target);";
                    echo "}";
                echo "</script>";

            echo "</body>";
        echo "</html>";
            
        } 
        else {
            
            //Check license status/////////////////////////////////
            
            //UTC//
            date_default_timezone_set("UTC");

            $licenseValid = new DateTime($_SESSION['mylicense']);
            //$licenseValid = $licenseValid->format('Ymd');
            $currentDate = new DateTime('now');
            //$currentDate = $currentDate->format('Ymd');

            if($currentDate->format('Ymd') > $licenseValid->format('Ymd')) {
                header('Location:../licenseExpired.php');    
                // echo "Now: " . $currentDate;
                // echo "License: " . $licenseValid;    
            }
            else
                header('Location:login_success.php');
        }
                    
	}
	else { //Login failed

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
                        echo "<br>";
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
