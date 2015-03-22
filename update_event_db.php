<?php
    //include 'ChromePhp.php';
    //ChromePhp::log('Hello console!');

    $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
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
    $sql3 = "UPDATE events SET Location_locationID = \"" . $locationID . "\", startTime = \"" . $gamestart ."\", endTime = \"" . $gamesend . "\" WHERE eventID = " . $eventid . ";";
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
    

    //Get the id for the inserted event
    //$sql2 = "SELECT MAX(eventID) as eventID FROM events";
    //echo $sql2;
    //echo "</br>";
    //$result2 = mysql_query($sql2);
    //$row = mysql_fetch_array($result2);        
        
    //Insert players which are selected into the event
    /*$eid = mysql_insert_id(); //Get the just created event id
    for ($k=1; $k<=$playeramount; $k++)
    {
            if($players[$k][2] == '')
            {
                    $sql3 = "INSERT INTO eventplayer (Players_playerID, Events_eventID, areyouin) VALUES ('" . $players[$k][1] . "', '" .  $eid . "', '0');";
                    $result3 = mysql_query($sql3);
            }
    }*/        
        
    //$sql3 = "INSERT INTO eventplayer (Players_playerID, Events_eventID, areyouin) VALUES ('" . $players[1][1] . "', '" . $row[eventID] . "', '0');";
    //echo $sql3;
    //echo "</br>";
    //$result3 = mysql_query($sql3);
        
    //echo $result;        
    /*echo "insert_event.php, playeamount: " . $playeramount . " start: " . $gamestart . " end: " . $gamesend;
    echo "</br>";*/
        
    /*for ($j=1; $j<=$playeramount; $j++)
    {
            echo "playerID: " . $players[$j][1] . " checkbox value: " . $players[$j][2] . "";
            echo "</br>";
    }*/
        
    //echo "<h1>Your game was inserted, click the browser back button...</h1>";

    //Success
    /*$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
    echo "<a href='$url'><h1>Your game was inserted succesfully, click here for R'YouIN!</h1></a>";
    echo "</br>";*/
        
    //Sending email notification for the players
    /*$to      = "tniinisto@gmail.com";
    $subject = "RYouIN";
    $message = "New game set";
    $headers = "From: webmaster@areyouin.net" . "\r\n" .
            "Reply-To: webmaster@areyouin.net" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();

    mail($to, $subject, $message, $headers);*/

    mysql_close($con);

    //if($result2 && $result3)
    {
        header("location:index.html");    
    }  

?>

