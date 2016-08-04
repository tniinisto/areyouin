<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    include 'mail_ayi.php';

    //include 'ChromePhp.php';
    //ChromePhp::log('Hello console!');

    //SendGrid
    $mailId=$mail_user;
    $mailPass=$mail_key;

    //Event mail switch
    if($_POST['mailswitch'] == '') //OFF
        $mail_event = 0;
    else
        $mail_event = 1;

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
            
    $eventid=$_POST['update_eventid'];
    $teamid=$_POST['update_teamid'];
    
    //echo "update_event_db() called, eventid=" . $eventid;

    //Post variables
    $playeramount=$_POST['playeramount'];
    $gamestart=$_POST['gamestart'];
    $gamesend=$_POST['gamesend'];
    $locationID=$_POST['location_select'];

    //Private event
    if($_POST['update_privateswitch'] == '') //OFF
        $private_event = 0;
    else
        $private_event = 1;

    //Array containing [playerID, checkbox]
    $players = array(); 
    $idpost = '';
    $oopost = '';
    for ($i=1; $i<=$playeramount; $i++)
    {
            $players[$i] = array();
            $idpost = "playerid" . $i; //Post variable name playerid#
            $oopost = "ooswitch" . $i; //Post variable name ooswitch#
                
            $players[$i][1] = $_POST[$idpost];
            $players[$i][2] = $_POST[$oopost];

            $players[$i][1] = stripslashes($players[$i][1]);
            $players[$i][2] = stripslashes($players[$i][2]);
    }

    // To protect MySQL injection
    $playeramount = stripslashes($playeramount);
    $gamestart = stripslashes($gamestart);
    //echo $gamestart;
    $gamesend = stripslashes($gamesend);

    //Handle date format from 2013-05-29T01:01 -> 2013-07-27 17:30:00
    if(stripos($gamestart,"T") && strlen($gamestart) < 17)
    {
            $gamestart = str_replace("T", " ", $gamestart);
            $gamestart = $gamestart . ":00";
    }
    if(stripos($gamesend,"T") && strlen($gamesend) < 17)
    {
            $gamesend = str_replace("T", " ", $gamesend);
            $gamesend = $gamesend . ":00";
    }

    //iPhone has seconds -> 2013-07-27 17:30:00
    if(stripos($gamestart,"T") && strlen($gamestart) > 17)
    {
            $gamestart = str_replace("T", " ", $gamestart);
            //$gamestart = DateTime::createFromFormat('Y-m-d H:i:s',$gamestart)->format('Y-m-d H:i');        
            //$gamestart = $gamestart . ":00";
            //$gamesend = DateTime::createFromFormat('d.m.Y H.i',$gamesend)->format('Y-m-d H:i');        
            //$gamesend = $gamesend . ":00";
    }
    if(stripos($gamesend,"T") && strlen($gamesend) > 17)
    {
            $gamesend = str_replace("T", " ", $gamesend);
            //$gamestart = DateTime::createFromFormat('Y-m-d H:i:s',$gamestart)->format('Y-m-d H:i');        
            //$gamestart = $gamestart . ":00";
            //$gamesend = DateTime::createFromFormat('d.m.Y H.i',$gamesend)->format('Y-m-d H:i');        
            //$gamesend = $gamesend . ":00";
    }
    //echo $gamestart;

    /*Eventin tiedot ja siinä jo olevat tiimin pelaajat*/
    $sql = "select e.eventID, p.playerID, p.name, e.startTime, e.endTime, l.name location from areyouin.eventplayer ep
    inner join areyouin.events e on e.eventID = ep.Events_eventID
    inner join areyouin.team t on teamID = e.Team_teamID
    inner join areyouin.players p on playerID = ep.Players_playerID
    inner join areyouin.location l on l.locationID = e.Location_locationID
    where e.eventID = " . $eventid . " and t.teamID = " . $teamid . ";";
        
    $result = mysql_query($sql);

    //Update game's basic information//////////////////////////////////////////////////////////////
    $row = mysql_fetch_array($result);
    //$sql3 = "UPDATE events SET startTime = ". $row['startTime'] .", endTime = " . $row['endTime'] . " WHERE eventID = " . $eventid . "";
    $sql3 = "UPDATE events SET private = \"" . $private_event . "\",  Location_locationID = \"" . $locationID . "\", startTime = \"" . $gamestart ."\", endTime = \"" . $gamesend . "\" WHERE eventID = " . $eventid . ";";
    //ChromePhp::log('Update: ' . $sql3);
    $result3 = mysql_query($sql3);

    //Update players, deed to check whether EventPlayer row should be inserted or deleted//////////
    for ($k=1; $k<=$playeramount; $k++)
    {
        mysql_data_seek($result, 0);
        $found = 0;

        if($players[$k][2] == '') { //Player is selected for the event. Check if insert is needed, if player was not in the event before
            while($row = mysql_fetch_array($result)) {
                if($players[$k][1] == $row['playerID']) {
                    $found = 1;    
                }
            }                   
            
            if($found == 0) {
            //Player not found, insert new row to EventPlayer
                $sql2 = "INSERT INTO eventplayer (Players_playerID, Events_eventID, areyouin) VALUES ('" . $players[$k][1] . "', '" .  $eventid . "', '0');";
                //ChromePhp::log('Insert: ' . $sql2);
                $result2 = mysql_query($sql2);                    
            }

        }
        else { //Player is not selected for the event, check if delete is needed -> player was already in the event
            while($row = mysql_fetch_array($result)) {
                if($players[$k][1] == $row['playerID']) {
                    $found = 1;    
                }
            }                   

            if($found == 1) {
            //Player was selected earlier, delete record
                $sql2 = "DELETE FROM eventplayer WHERE Players_playerID = " . $players[$k][1] . " AND Events_eventID = " . $eventid . ";";
                //ChromePhp::log('Delete: ' . $sql2);
                $result2 = mysql_query($sql2);                    
            }
                
        }
    }


    //Get players emails which to notify, depending on the notify field's value///////////////////////////////////////
    if($mailId != '' && $mailPass != '' && $mail_event==1) { //Check if mail credentials are set
                
        $playerIdSqlList=''; //PlayerIDs for the sql where statement
        $loopFirst = 1;
        for ($m=1; $m<=$playeramount; $m++)
        {
            // if($ooswitch_all == '') //If all players switch is on, add all
            // {
            //     if($m == 1)
            //         $playerIdSqlList = $players[$m][1];
            //     else
            //         $playerIdSqlList = $playerIdSqlList . ', ' . $players[$m][1];
            // }
            // else
            // {
                if($players[$m][2] == '') //Check if single player is selected
                {
                    if($loopFirst == 1) {
                        $loopFirst = 0;
                        $playerIdSqlList = $players[$m][1];
                    }
                    else
                        $playerIdSqlList = $playerIdSqlList . ', ' . $players[$m][1];
                }
            }
        }

    //Get event info to sendMail function parameter
    $sql_eventInfo = "select * from areyouin.events
                inner join areyouin.team on team.teamID = events.Team_teamID
                inner join areyouin.location on location.locationID = events.Location_locationID
                where events.eventID = " . $eventid . ";";
    
    $r = mysql_query($sql_eventInfo);
    $eventInfo = mysql_fetch_array($r);

    //Start- and End-time formating
    $time = strtotime($eventInfo['startTime']);
    $starttime = date("D j.n.Y H:i", $time);
    $time = strtotime($eventInfo['endTime']);
    $endtime = date("D j.n.Y H:i", $time);

    $eventInfoArray = array(        
        'subject' => "Event updated for " . $eventInfo['teamName'] . "",                 
        'content' => "<html>             	

                        <div style='background: black;'>
                            <img style='padding: 5px;' src='https://r-youin.com/images/r2.png' align='middle' alt='AreYouIN' height='42' width='42'>
                            <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> Event updated</font>
                        </div>

                        <br>

                        <ul style='list-style-type:disc'>
                        <font size='3' face='Trebuchet MS'>                                       		
                        <li><span style='font-weight: bold;'>Team: </span>" . $eventInfo['teamName'] . "</li>
                            <li><span style='font-weight: bold;'>Location: </span><a href='https://maps.google.fi/maps?q=
                            " . $eventInfo['position'] . "&hl=en&sll=" . $eventInfo['position'] . "&sspn=0.002108,0.004367&t=h&z=16' target='_blank'>" . $eventInfo['name'] . "</a></li>
                            <li><span style='font-weight: bold;'>Starting: </span><span style='color:blue'> " . $starttime . "    </span></li>
                            <li><span style='font-weight: bold;'>Ending: </span><span style='color:blue'> " . $endtime . "</span></li>
                            </font>
                        </ul>                                

                        <br>

                        <div style='text-align: center; background: black; padding: 15px;'>
                        <font size='4' face='Trebuchet MS' style='color: white;'>			
                            Check your status at <a href='https://r-youin.com/' style='color: white;'>R'YouIN</a>!
                        </font>
                        </div>

                    </html>",
    );


    //Get emails where players notify setting is 1(true)
    $sql_mail = "SELECT mail, notify FROM players where playerID IN (" . $playerIdSqlList . ");";
    if($_SESSION['ChromeLog']) { ChromePhp::log('insert_event.php, $sql_mail: ', $sql_mail); }

    $result_mail = mysql_query($sql_mail);

    while($row_mail = mysql_fetch_array($result_mail)) {
        if($row_mail['notify'] == 1 && $row_mail['mail'] != '') { //If notity setting is true and player has email in profile
            if($_SESSION['ChromeLog']) { ChromePhp::log('insert_event.php, sendMail() mail address: ', $row_mail['mail']); }            
            sendMail($row_mail['mail'], $mailId, $mailPass, $eventInfoArray);   
        }
    }

    mysql_close($con);

    //if($result2 && $result3)
    //{
        //header("location:index.html");
    //}

    echo "<html>";
    echo "<head>";
        //echo "<meta charset=\"utf-8\">";
        //echo "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
        echo "<title>R'YouIN</title>";
        echo "<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
        echo "<link href=\"media-queries.css\" rel=\"stylesheet\" type=\"text/css\">";
        echo "<script type=\"text/javascript\" src=\"main.js\"> </script>";
    echo "</head>";

    echo "<body>";
        echo "<div id=\"pagewrap\">";
            echo "<div data-role=\"page\" id=\"areyouin-update-page\" data-theme=\"b\" data-url=\"areyouin-update-page\">";
                echo "<div id=\"loginwrapper\">";

                    echo "<h1 id=\"loginsite-logo\">R'YouIN</h1>";

                    echo "<fieldset id=\"loginfailfs\">";
                        echo "<h1>Event updated</h1>";
                        echo "<br />";
                        echo "<a href=\"javascript:toEvents();\">Back to home</a>";
                        echo "<h1></h1>";
                    echo "</fieldset>";
                echo "</div>";
            echo "</div>";
        echo "</div>";

    echo "</body>";
    echo "</html>";      

?>

