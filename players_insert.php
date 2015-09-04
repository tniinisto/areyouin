<?php
        include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

        session_start();
        
        //$teamid=1;
        $teamid=$_SESSION['myteamid'];
        $ad=$_SESSION['myAdmin'];

        //include 'ChromePhp.php';        
        //ChromePhp::log("players_insert, admin:", $ad);

        if($ad==1) //Execute only for admin status
        {
            
            $con = mysql_connect($dbhost, $dbuser, $dbpass);

            if (!$con)
              {
              die('Could not connect: ' . mysql_error());
              }

            mysql_select_db("areyouin", $con);

            //$sql="SELECT p.playerID, p.name, p.photourl FROM players p, team t where t.teamID = '1'";
            $sql="SELECT p.playerID, p.name, p.photourl FROM players p, playerteam pt, team t WHERE t.teamID = '" . $teamid . "' AND pt.team_teamID = '" . $teamid . "' AND pt.players_playerID = p.playerID";
        
            $result = mysql_query($sql);
            $row_count = mysql_num_rows($result);

            //Article///////////////////////////////////////////////////////////////////////////
            echo "<article id=\"admin_content_article\" class=\"clearfix \">";
            
            //Navigation///////////////////////////////////////////////////////////////////////////
            echo "<nav>";
			    echo "<ul id=\"admin-nav\" class=\"clearfix\" onClick=\"adminClick()\">";
				    echo "<li id=\"link_admingame\" class=\"current2\"><a href=\"#\">New game</a></li>";
                    echo "<li id=\"link_adminteam\"><a href=\"#\">Team</a></li>";
			    echo "</ul>";
		    echo "</nav>";
            //Navigation///////////////////////////////////////////////////////////////////////////

                //New game///////////////////////////////////////////////////////////////////////////
                echo "<div id=\"newgame_id\">";
                    echo "<h1>Enter new game</h1>";
                    
                    echo "<form id=\"eventform\" method=\"post\" action=\"insert_event.php\">";
                    
                    //Location///////////////////////////////////////////
                    echo "<label><h2>Game location:</h2></label>";
                    $sql2="SELECT locationID, name FROM location WHERE teamID = '" . $teamid . "'";
                    $result2 = mysql_query($sql2);

                    echo "<select id=\"location_select\" name=\"location\" form=\"eventform\">";
                    while($row2 = mysql_fetch_array($result2))
	                {  
                        echo "<option value=\"" . $row2['locationID'] . "\">" . $row2['name'] . "</option>";
                    }
                    echo "</select>";
                    //Location///////////////////////////////////////////

                    //echo "<h2>Set Time</h2>";
                    echo "<label><h2>Game start:</h2></label>";
                    echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required value=\"" . date(('Y-m-d H:00'), strtotime('-1 hours')) . "\" onchange=\"game_start()\"
                    required></input>";
                    
                    echo "<label><h4 id='gametime_notify' class='noshow' style='color: red;'> * Game start time must be before the end time...</h4></label>";
                    echo "<label><h2>Game end:</h2></label>";
                    echo "<input type=\"datetime-local\" id=\"gameend_id\" name=\"gamesend\" required value=\"" . date(('Y-m-d H:00'), strtotime('-1 hours')) . "\" onchange=\"game_end()\"
                    required></input>";

                    //OnOff switch to selecte all
                    //echo "<div>";
                    echo "<h2 id=\"pickall\">Pick players: <span style=\"float: right;\">Select all: ";
      
                    echo "<div class=\"onoffswitch\" style=\"display: inline-block; vertical-align: middle;\">";
                        echo "<input type=\"checkbox\" name=\"ooswitch_all\" class=\"onoffswitch-checkbox\" id=\"myonoff_all\" checked>";
                        echo "<label class=\"onoffswitch-label\" for=\"myonoff_all\">";
                        echo "<div class=\"onoffswitch-inner\"></div>";
                        echo "<div class=\"onoffswitch-switch\"></div>";
                        echo "</label>";
                    echo "</div></span></h2>";

                    echo "</br>";
                    echo "</br>";

                    $row_index = 1; 
                    echo "<table border='0' id='insertplayers' class=\"atable2\">"; 
        
                    //$second = 0;
        
                    while($row = mysql_fetch_array($result))
                    {
                            //echo "<div style=\"display: inline-block;\">";
                    
                                echo "<tr>";
                                echo "<td class=\"pcol1\"><input type=\"number\" name=\"playeramount\" value=\"" . $row_count . "\"></input></td>";
                                echo "<td class=\"pcol2\"><input type=\"number\" name=\"playerid" . $row_index . "\" value=\"" . $row['playerID'] . "\"></input></td>";
                                echo "<td class=\"pcol3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
                                echo "<td class=\"pcol4\">" . $row['name'] . "</td>";
                                echo "<td class=\"pcol5\">";
                                        echo "<div class=\"onoffswitch\">";
                                                echo "<input type=\"checkbox\" name=\"ooswitch" . $row_index . "\" class=\"onoffswitch-checkbox\" id=\"myonoff" . $row_index . "\" checked>";
                                                echo "<label class=\"onoffswitch-label\" for=\"myonoff" . $row_index . "\">";
                                                echo "<div class=\"onoffswitch-inner\"></div>";
                                                echo "<div class=\"onoffswitch-switch\"></div>";
                                                echo "</label>";
                                        echo "</div>";
                                echo "</td>";
                    
                                if($row = mysql_fetch_array($result))
                                {
                                    $row_index = $row_index + 1;
                                    echo "<td style=\"width: 20px;\"></td>";
                                    echo "<td class=\"pcol1\"><input type=\"number\" name=\"playeramount\" value=\"" . $row_count . "\"></input></td>";
                                    echo "<td class=\"pcol2\"><input type=\"number\" name=\"playerid" . $row_index . "\" value=\"" . $row['playerID'] . "\"></input></td>";
                                    echo "<td class=\"pcol3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
                                    echo "<td class=\"pcol4\">" . $row['name'] . "</td>";
                                    echo "<td class=\"pcol5\">";
                                            echo "<div class=\"onoffswitch\">";
                                                    echo "<input type=\"checkbox\" name=\"ooswitch" . $row_index . "\" class=\"onoffswitch-checkbox\" id=\"myonoff" . $row_index . "\" checked>";
                                                    echo "<label class=\"onoffswitch-label\" for=\"myonoff" . $row_index . "\">";
                                                    echo "<div class=\"onoffswitch-inner\"></div>";
                                                    echo "<div class=\"onoffswitch-switch\"></div>";
                                                    echo "</label>";
                                            echo "</div>";
                                    echo "</td>";
                                }                                      
                                echo "</tr>";
                
                            //echo "</div>";

                            $row_index = $row_index + 1;
                    }
                    echo "</table>";
                    echo "</br>";

                        //Input credentials to send mail from the event
                        echo "</br>";
                        echo "<h2>Email notify credentials:</h2>";
                        echo "<label><h3>Mail userid:</h3></label>";                        
                        echo "<input type=\"text\" name=\"mail_user\" value=\"\"></input>";
                        echo "<label><h3>Mail password: </h3></label>";                        
                        echo "<input type=\"text\" name=\"mail_pass\" value=\"\"></input>";
                        echo "</br>";

                    //    echo "</br>";
                    //    echo "<input type=\"submit\" value=\"Send mail\" id=\"submitmail\"></input>"; 
                    echo "</br>";
                        
                        //Public/Private event switch
                        echo "<h2>Private game:</h2>";
                        echo "<div class=\"onoffswitch notifyswitch\" style='display: inline-block;'>";
						    echo "<input type='checkbox' name='privateswitch' class=\"onoffswitch-checkbox\" id='event_private_switch'>";
                            echo "<label class=\"onoffswitch-label\" for='event_private_switch' onClick=''>";
                                echo "<div class=\"notifyswitch-inner\"></div>";
						        echo "<div class=\"onoffswitch-switch\"></div>";
						    echo "</label>";
                        echo "</div>";                    

                    echo "</br>";
                    echo "</br>";
                    echo "<input class=\"myButton\" type=\"submit\" value=\"Create Game\" id=\"submitgame1\"></input>"; 
                    echo "</form>";
                echo "</div>";
                //New game///////////////////////////////////////////////////////////////////////////


                //Team page///////////////////////////////////////////////////////////////////////////

                $sql_team="SELECT timezone, utcOffset FROM team WHERE teamID = '" . $teamid . "'";
                $res_team = mysql_query($sql_team);
                $row_team = mysql_fetch_array($res_team);


                echo "<div id=\"team_content_id\" class=\"noshow\">";
                    
                    echo "<fieldset id='timezone_set'>";
                    echo "<legend style='text-align: left;'><h2>Timezone</h2></legend>";
                        echo "<h3>Team's current timezone is: " . $row_team['timezone']. "</h3>";
                        echo "<h3>Offset to UTC is: " . $row_team['utcOffset'] . "</h3>";

                        //echo "<form id='timezones' method='post' action='update_team.php' target='frame_local' onsubmit=\"showTimezone('Timezone set to:' + timezone_select.value)\"";
                        echo "<form id='timezones' method='get' target='frame_local' onsubmit=\"updateTimezone(timezone_select.value)\"";
                            $timezone_identifiers = DateTimeZone::listIdentifiers();
                            echo "<label><h3>Choose team's timezone:</h3></label>";                    
                            //echo "<select id='timezone_select' name='timezone_select' form='timezones' onchange=showTimezone(this.value)>";
                            echo "<select id='timezone_select' name='timezone_select' form='timezones'>";
                                for ($i=0; $i < sizeof($timezone_identifiers); $i++) {
	                                echo "<option value=\"" . $timezone_identifiers[$i] . "\">" . $timezone_identifiers[$i] . "</option>";
                                }
                            echo "</select>";
                            //echo "<input type='text' name='timezone_offset' id='timezone_offset' value=''></input>";
                            echo "<input type='submit' value='Save' id='submit_timezone'></input>";                                         
                        echo "</form>";
                        echo "<br>";
                        echo "<p><span id='txtZone'></span></p>";
                
                    echo "</fieldset>";

                    //TIMEZONE OFFSET///////////////////////
                    // Don't know where the server is or how its clock is set, so default to UTC
                    //date_default_timezone_set( "UTC" );

                    //// The client is in England where daylight savings may be in effect
                    //$daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open( 'Europe/Helsinki' ), new DateTime() );

                    //echo "</br>";
                    //echo "Time offset Helsinki to UTC is " . round($daylight_savings_offset_in_seconds/3600) . " hours";
                    //////////////////////////////////////


                echo "</div>";
                //Team page///////////////////////////////////////////////////////////////////////////


            echo "</article>";
            //Article///////////////////////////////////////////////////////////////////////////
        
            echo "<iframe name=\"frame_local\" style='display: none'></iframe>";

            mysql_close($con);
        }
?>