<?php
        $eventid=$_GET["eventid"];
        
        //echo "update_event.php called eventid=" . $eventid;

        $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
        if (!$con)
          {
          die('Could not connect: ' . mysql_error());
          }

        mysql_select_db("areyouin", $con)or die("cannot select DB");

        echo "<article id=\"event_content_article\" class=\"clearfix \">";
        echo "<h1>Update game</h1>";
        echo "<form id=\"eventupdateform\" method=\"post\" action=\"update_event_db.php\">";
        echo "<label><h2>Game start:</h2></label>";
        echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required value=\"" . date(('Y-m-d H:i'), strtotime('+10 hours')) . "\"></input>";
        echo "<label><h2>Game end:</h2></label>";
        echo "<input type=\"datetime-local\" id=\"gameend_id\" name=\"gamesend\" required value=\"" . date(('Y-m-d H:i'), strtotime('+12 hours')) . "\"></input>";

        echo "<h2>Pick players:</h2>";
        $row_index = 1; 
        /*echo "<table border='0' id='insertplayers' class=\"atable2\">"; 
        
        while($row = mysql_fetch_array($result))
        {
                echo "<tr>";
                echo "<td class=\"col1\"><input type=\"number\" name=\"playeramount\" value=\"" . $row_count . "\"></input></td>";
                echo "<td class=\"col2\"><input type=\"number\" name=\"playerid" . $row_index . "\" value=\"" . $row['playerID'] . "\"></input></td>";
                echo "<td class=\"col3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
                echo "<td class=\"col4\">" . $row['name'] . "</td>";
                echo "<td class=\"col5\">";
                        echo "<div class=\"onoffswitch\">";
                                echo "<input type=\"checkbox\" name=\"ooswitch" . $row_index . "\" class=\"onoffswitch-checkbox\" id=\"myonoff" . $row_index . "\" checked>";
                                echo "<label class=\"onoffswitch-label\" for=\"myonoff" . $row_index . "\">";
                                echo "<div class=\"onoffswitch-inner\"></div>";
                                echo "<div class=\"onoffswitch-switch\"></div>";
                                echo "</label>";
                        echo "</div>";
                echo "</td>";
                echo "</tr>";
                
                $row_index = $row_index + 1;
        }
        echo "</table>";*/
        echo "</br>";
        echo "</br>";
        //echo "<input type=\"submit\" value=\"Update Game\" id=\"submitgame\"></input>"; 
        echo "</form>";

        echo "<form id=\"back\" method=\"post\" action=\"event_list.php\">";
            echo "<input type=\"submit\" value=\"Back\" id=\"button1\"></input>";
        echo "</form>";  

        echo "</article>";


        //Update the database
        function update_event_db()
        {
                echo "update_event.php called eventid=" . $eventid;
        }

        
        //Post variables
        /*$playeramount=$_POST['playeramount'];
        $gamestart=$_POST['gamestart'];
        $gamesend=$_POST['gamesend'];

        //Array containing [playerID, checkbox]
        $players = array(); 
        $idpost = '';
        $oopost = '';
        for ($i=1; $i<=$playeramount; $i++)
        {
                $players[$i] = array();
                $idpost = "playerid" . $i;
                $oopost = "ooswitch" . $i;
                
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
        
        //Insert event to events
        $sql = "INSERT INTO events (Location_locationID, EventType_eventTypeID, startTime, endTime, Team_teamID) VALUES ('1', '1', '" . $gamestart. "', '" . $gamesend . "', '1')";
        //echo $sql;
        //echo "</br>";
        $result = mysql_query($sql);
        
        if(!$result)
        {
                $url = htmlspecialchars($_SERVER['HTTP_REFERER']);        
                echo "<a href='$url'><h1>Something went wrong, check the start&end dates format!</h1></a>";
                mysql_close($con);
                exit;
        }*/
        
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
        
        //Sending email notification for the plyaers
        /*$to      = "tniinisto@gmail.com";
        $subject = "RYouIN";
        $message = "New game set";
        $headers = "From: webmaster@areyouin.net" . "\r\n" .
                "Reply-To: webmaster@areyouin.net" . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

        mail($to, $subject, $message, $headers);*/

        
        mysql_close($con);
?>
