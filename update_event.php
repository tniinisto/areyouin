<?php
        //include 'ChromePhp.php';
        //ChromePhp::log('Hello console!');

        $eventid=$_GET["eventid"];
        
        session_start();
	
        //$teamid=$_GET["teamid"];
	    //$playerid=$_GET["playerid"];

        //$playerid=$_SESSION['myplayerid'];
	    $teamid=$_SESSION['myteamid'];

        //echo "update_event.php called eventid=" . $eventid;

        $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
        if (!$con)
          {
          die('Could not connect: ' . mysql_error());
          }

        mysql_select_db("areyouin", $con)or die("cannot select DB");

        /*Eventin tiedot ja siinä jo olevat tiimin pelaajat*/
        $sql = "select e.eventID, p.playerID, p.name, e.startTime, e.endTime, l.name location from areyouin.eventplayer ep
        inner join areyouin.events e on e.eventID = ep.Events_eventID
        inner join areyouin.team t on teamID = e.Team_teamID
        inner join areyouin.players p on playerID = ep.Players_playerID
        inner join areyouin.location l on l.locationID = e.Location_locationID
        where e.eventID = " . $eventid . " and t.teamID = " . $teamid . "";
        
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //ChromePhp::log($user_agent);
        
        //Datetime format for Chrome (Add "T")
        $gamestart = date(('Y-m-d H:i'), strtotime($row['startTime']));
        $gamestart[10] = "T";
        $gameend = date(('Y-m-d H:i'), strtotime($row['endTime']));
        $gameend[10] = "T";

         //Debug
        //echo($sql);
        //ChromePhp::log("startTime:");
        //ChromePhp::log($row['startTime']);
        //ChromePhp::log("endTime:");
        //ChromePhp::log($row['endTime']);
        //ChromePhp::log($gamestart);
        //$gamestart = str_replace("-", "T", $row['startTime']);
        //ChromePhp::log(date(('Y-m-d H:i'), strtotime($row['startTime'])));
        ////ChromePhp::log(date(('Y-m-d H:i'), strtotime(strtotime('+10 hours'))));
        //ChromePhp::log(date(('Y-m-d H:i'), strtotime(strtotime($gamestart))));

        //Get team's players
        //$sql="SELECT p.playerID, p.name, p.photourl FROM players p, team t WHERE t.teamID = '" . $teamid . "'";);
        //$result = mysql_query($sql);
        //$row_count = mysql_num_rows($result);

        //Get event data
        //$sql2 = "";
        //$result2 = mysql_query($sql2);

        //Form - Start & End time/////////////////////

        echo "<article id=\"admin_content_article\" class=\"clearfix \">";
        echo "<h1>Update game</h1>";
        echo "<form id=\"eventform\" method=\"post\" action=\"update_event_db.php\">";
        echo "<label><h2>Game start:</h2></label>";
        //echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required value=\"" . date(('Y-m-d H:i'), strtotime('+10 hours')) . "\"></input>";
        //echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required value=\"" . date(('Y-m-d H:i'), strtotime($row['startTime'])) . "\"></input>";
        if (preg_match('/chrome/i', $user_agent)) {
            echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required value=\"" . $gamestart . "\"></input>";
        }
        else {
            echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required value=\"" . date(('Y-m-d H:i'), strtotime($row['startTime'])) . "\"></input>";
        }

        echo "<label><h2>Game end:</h2></label>";
        //echo "<input type=\"datetime-local\" id=\"gameend_id\" name=\"gamesend\" required value=\"" . date(('Y-m-d H:i'), strtotime('+12 hours')) . "\"></input>";
        //echo "<input type=\"datetime-local\" id=\"gameend_id\" name=\"gamesend\" required value=\"" . date(('Y-m-d H:i'), strtotime($row['endTime'])) . "\"></input>";
        if (preg_match('/chrome/i', $user_agent)) {
            echo "<input type=\"datetime-local\" id=\"gameend_id\" name=\"gamesend\" required value=\"" . $gameend . "\"></input>";
        }
        else {
            echo "<input type=\"datetime-local\" id=\"gameend_id\" name=\"gamesend\" required value=\"" . date(('Y-m-d H:i'), strtotime($row['endTime'])) . "\"></input>";
        }

        echo "<input id=\"update_eventid\" name=\"update_eventid\" type=\"number\" value=\"" . $eventid .  "\" style=\"display:none;\"></label>";
        
        //Form - Players////////////////////////////////////
        echo "<h2>Pick players:</h2>";

        mysql_data_seek($result, 0); //Reset $result index position (earlier query)
        $eventplayers = array();
        $index = 0;
        while($row = mysql_fetch_array($result))
        {
            $eventplayers[$index] = $row['playerID'];
            //ChromePhp::log("Array: " . $index . ": " . $eventplayers[$index]);
            $index++;
        }

        $sql2="SELECT p.playerID, p.name, p.photourl FROM players p, team t WHERE t.teamID = '" . $teamid . "'";
        
        $result2 = mysql_query($sql2);
        $row_count = mysql_num_rows($result2);

        $row_index = 1; 
        echo "<table border='0' id='updateplayers' class=\"atable2\">"; 
        
        while($row2 = mysql_fetch_array($result2))
        {
                //echo "<div style=\"display: inline-block;\">";
                    
                    echo "<tr>";
                    echo "<td class=\"pcol1\"><input type=\"number\" name=\"playeramount\" value=\"" . $row_count . "\"></input></td>";
                    echo "<td class=\"pcol2\"><input type=\"number\" name=\"playerid" . $row_index . "\" value=\"" . $row2['playerID'] . "\"></input></td>";
                    echo "<td class=\"pcol3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row2['photourl'] . "\"></td>";
                    echo "<td class=\"pcol4\">" . $row2['name'] . "</td>";
                    echo "<td class=\"pcol5\">";
                            if(in_array($row2['playerID'], $eventplayers)) {
                                echo "<div class=\"onoffswitch\">";
                                        echo "<input type=\"checkbox\" name=\"ooswitch" . $row_index . "\" class=\"onoffswitch-checkbox\" id=\"myonoff" . $row_index . "\">";
                                        echo "<label class=\"onoffswitch-label\" for=\"myonoff" . $row_index . "\">";
                                        echo "<div class=\"onoffswitch-inner\"></div>";
                                        echo "<div class=\"onoffswitch-switch\"></div>";
                                        echo "</label>";
                                echo "</div>";
                            }
                            else {
                                echo "<div class=\"onoffswitch\">";
                                        echo "<input type=\"checkbox\" name=\"ooswitch" . $row_index . "\" class=\"onoffswitch-checkbox\" id=\"myonoff" . $row_index . "\" checked>";
                                        echo "<label class=\"onoffswitch-label\" for=\"myonoff" . $row_index . "\">";
                                        echo "<div class=\"onoffswitch-inner\"></div>";
                                        echo "<div class=\"onoffswitch-switch\"></div>";
                                        echo "</label>";
                                echo "</div>";                                
                            }
                    echo "</td>";
                    
                    if($row2 = mysql_fetch_array($result2))
                    {
                        $row_index = $row_index + 1;
                        echo "<td style=\"width: 20px;\"></td>";
                        echo "<td class=\"pcol1\"><input type=\"number\" name=\"playeramount\" value=\"" . $row_count . "\"></input></td>";
                        echo "<td class=\"pcol2\"><input type=\"number\" name=\"playerid" . $row_index . "\" value=\"" . $row2['playerID'] . "\"></input></td>";
                        echo "<td class=\"pcol3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row2['photourl'] . "\"></td>";
                        echo "<td class=\"pcol4\">" . $row2['name'] . "</td>";
                        echo "<td class=\"pcol5\">";
                            if(in_array($row2['playerID'], $eventplayers)) {
                                echo "<div class=\"onoffswitch\">";
                                        echo "<input type=\"checkbox\" name=\"ooswitch" . $row_index . "\" class=\"onoffswitch-checkbox\" id=\"myonoff" . $row_index . "\">";
                                        echo "<label class=\"onoffswitch-label\" for=\"myonoff" . $row_index . "\">";
                                        echo "<div class=\"onoffswitch-inner\"></div>";
                                        echo "<div class=\"onoffswitch-switch\"></div>";
                                        echo "</label>";
                                echo "</div>";
                            }
                            else {
                                echo "<div class=\"onoffswitch\">";
                                        echo "<input type=\"checkbox\" name=\"ooswitch" . $row_index . "\" class=\"onoffswitch-checkbox\" id=\"myonoff" . $row_index . "\" checked>";
                                        echo "<label class=\"onoffswitch-label\" for=\"myonoff" . $row_index . "\">";
                                        echo "<div class=\"onoffswitch-inner\"></div>";
                                        echo "<div class=\"onoffswitch-switch\"></div>";
                                        echo "</label>";
                                echo "</div>";                                
                            }
                        echo "</td>";
                    }                                      
                    echo "</tr>";
                
                //echo "</div>";

                $row_index = $row_index + 1;
        }
        echo "</table>";
        echo "</br>";
        echo "</br>";
        echo "<input type=\"submit\" value=\"Update Game\" id=\"submitgame\"></input>"; 
        echo "</form>";

        echo "<a href=\"javascript:getEvents();\">Back to events</a>";

        echo "</article>";
    
        mysql_close($con);
?>
