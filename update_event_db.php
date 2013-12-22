<?php
    
    $eventid=$_POST['update_eventid'];
    $teamid=$_POST['update_teamid'];
    
    //echo "update_event_db() called, eventid=" . $eventid;

    //Post variables
    $playeramount=$_POST['playeramount'];
    $gamestart=$_POST['gamestart'];
    $gamesend=$_POST['gamesend'];

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
            $gamesend = str_replace("T", " ", $gamesend);
            $gamesend = $gamesend . ":00";
    }
    //iPhone  -> 2013-07-27 17:30:00
    if(stripos($gamestart,"T") && strlen($gamestart) > 17)
    {
            $gamestart = str_replace("T", " ", $gamestart);
            $gamesend = str_replace("T", " ", $gamesend);
            //$gamestart = DateTime::createFromFormat('Y-m-d H:i:s',$gamestart)->format('Y-m-d H:i');        
            //$gamestart = $gamestart . ":00";
            //$gamesend = DateTime::createFromFormat('d.m.Y H.i',$gamesend)->format('Y-m-d H:i');        
            //$gamesend = $gamesend . ":00";
    }
    //echo $gamestart;
        
    //Update game's basic information//////////////////////////////////////////////////////////////


    //Update players, deed to check whether EventPlayer row should be inserted or deleted//////////
    for ($k=1; $k<=$playeramount; $k++)
    {
            if($players[$k][2] == '') { //Player is selected for the event, check if insert is needed


            }
            else { //Player is not selected for the event, check if delete is needed
                
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

?>

