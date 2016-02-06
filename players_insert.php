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
            $sql="SELECT p.playerID, p.name, p.mobile, p.mail, p.photourl, p.notify, p.firstname, p.lastname
            FROM players p, playerteam pt, team t WHERE t.teamID = '" . $teamid . "' AND pt.team_teamID = '" . $teamid . "' AND pt.players_playerID = p.playerID";
        
            $result = mysql_query($sql);
            $row_count = mysql_num_rows($result);

            //Article///////////////////////////////////////////////////////////////////////////
            echo "<article id='admin_content_article' class='clearfix'>";
            
            //Navigation///////////////////////////////////////////////////////////////////////////
            echo "<nav>";
			    echo "<ul id='admin-nav' class='clearfix' onClick='adminClick()'>";
				    echo "<li id='link_admingame' class='current2'><a href='#'>New event</a></li>";
                    echo "<li id='link_adminmembers'><a href='#'>Users</a></li>";
                    echo "<li id='link_adminsettings' onClick='setTimeout(function(){ initializeMap(); }, 100);'><a href='#'>Settings</a></li>";
			    echo "</ul>";
		    echo "</nav>";
            //Navigation///////////////////////////////////////////////////////////////////////////


                //New game///////////////////////////////////////////////////////////////////////////
                echo "<div id=\"newgame_id\">";
                    echo "<h1>Enter new event</h1>";
                    
                    echo "<form id=\"eventform\" method=\"post\" action=\"insert_event.php\">";
                    
                    //Location///////////////////////////////////////////
                    echo "<label><h2>Event location:</h2></label>";
                    $sql2="SELECT locationID, name FROM location WHERE teamID = '" . $teamid . "'";
                    $result2 = mysql_query($sql2);

                    echo "<select id=\"location_select\" name=\"location\" form=\"eventform\">";
                    
                    //Default location when no locations entered to RYouIN
                    echo "<option value='0'></option>";
                    //Team's locations
                    while($row2 = mysql_fetch_array($result2))
	                {  
                        echo "<option value=\"" . $row2['locationID'] . "\">" . $row2['name'] . "</option>";
                    }
                    echo "</select>";
                    //Location///////////////////////////////////////////

                    //echo "<h2>Set Time</h2>";
                    echo "<label><h2>Start time:</h2></label>";
                    echo "<input type=\"datetime-local\" id=\"gamestart_id\" name=\"gamestart\" required value=\"" . date(('Y-m-d H:00'), strtotime('-1 hours')) . "\" onchange=\"game_start()\"
                    required></input>";
                    
                    echo "<label><h4 id='gametime_notify' class='noshow' style='color: red;'> * Game start time must be before the end time...</h4></label>";
                    echo "<label><h2>End time:</h2></label>";
                    echo "<input type=\"datetime-local\" id=\"gameend_id\" name=\"gamesend\" required value=\"" . date(('Y-m-d H:00'), strtotime('-1 hours')) . "\" onchange=\"game_end()\"
                    required></input>";


                    echo "<label><h2>Pick participants:</h2></label>";
                    echo "<h4>Select all: ";
                        echo "<div class=\"onoffswitch\" style=\"display: inline-block; vertical-align: middle;\">";
                            echo "<input type=\"checkbox\" name=\"ooswitch_all\" class=\"onoffswitch-checkbox\" id=\"myonoff_all\" checked>";
                            echo "<label class=\"onoffswitch-label\" for=\"myonoff_all\">";
                            echo "<div class=\"onoffswitch-inner\"></div>";
                            echo "<div class=\"onoffswitch-switch\"></div>";
                            echo "</label>";
                        echo "</div>
                    </h4>";

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

                    //Sendmail credentials
                        echo "<h2>Email notification:</h2>";
                        echo "<div class=\"onoffswitch notifyswitch\" style='display: inline-block;'>";
						    echo "<input type='checkbox' name='mailswitch' class=\"onoffswitch-checkbox\" id='event_mail_switch'>";
                            echo "<label class=\"onoffswitch-label\" for='event_mail_switch' onClick=''>";
                                echo "<div class=\"notifyswitch-inner\"></div>";
						        echo "<div class=\"onoffswitch-switch\"></div>";
						    echo "</label>";
                        echo "</div>";  
                    
                    echo "</br>";
                        
                        //Public/Private event switch
                        echo "<h2>Private event:</h2>";
                        echo "<div class=\"onoffswitch notifyswitch\" style='display: inline-block;'>";
						    echo "<input type='checkbox' name='privateswitch' class=\"onoffswitch-checkbox\" id='event_private_switch'>";
                            echo "<label class=\"onoffswitch-label\" for='event_private_switch' onClick=''>";
                                echo "<div class=\"notifyswitch-inner\"></div>";
						        echo "<div class=\"onoffswitch-switch\"></div>";
						    echo "</label>";
                        echo "</div>";                    

                    echo "</br>";
                    echo "</br>";
                    echo "</br>";

                    //Background for button on form
                    //echo "<div style='background: linear-gradient(-90deg, #121111, #474747); margin: 5px; padding: 10px;'>";
                    echo "<div style='background: #b9b9b9; margin: 5px; padding: 10px;'>";                    
                        echo "<input class=\"myButton\" type=\"submit\" value=\"Create Game\" id=\"submitgame1\"></input>"; 
                    echo "</div>";

                    echo "</form>";
                echo "</div>";
                //New game///////////////////////////////////////////////////////////////////////////


                //Settings page///////////////////////////////////////////////////////////////////////////

                $sql_team="SELECT timezone, utcOffset FROM team WHERE teamID = '" . $teamid . "'";
                $res_team = mysql_query($sql_team);
                $row_team = mysql_fetch_array($res_team);


                echo "<div id=\"team_content_id\" class=\"noshow\">";
                    
                    //Timezone section///////////////////////////////////////////////////////////////////////////
                    echo "<br>";
                    echo "<fieldset id='timezone_set'>";
                        echo "<legend style='text-align: left;'><h2>Timezone</h2></legend>";
                        
                        echo "<div style='background-color: #b9b9b9; margin: 5px;'>";
                            echo "<h3 id='team_timezone' style='text-align: center;'>Current timezone:</h3>";
                            echo "<h4 id='team_timezone_value' style='text-align: center; margin-top: 0px;'>" . $row_team['timezone'] . "</h4>";
                        echo "</div>";

                        //echo "<form id='timezones' method='post' action='update_team.php' target='frame_local' onsubmit=\"showTimezone('Timezone set to:' + timezone_select.value)\"";
                        echo "<form id='timezones' method='get' target='frame_local' onsubmit='updateTimezone(timezone_select.value)'";
                            $timezone_identifiers = DateTimeZone::listIdentifiers();
                            echo "<label><h3 style='text-align: center;'>Choose team's timezone:</h3></label>";                    
                            //echo "<select id='timezone_select' name='timezone_select' form='timezones' onchange=showTimezone(this.value)>";
                            
                            echo "<div align='center'>";
                            echo "<select id='timezone_select' name='timezone_select' form='timezones' style='text-align: center;'>";
                                for ($i=0; $i < sizeof($timezone_identifiers); $i++) {
	                                echo "<option value=\"" . $timezone_identifiers[$i] . "\">" . $timezone_identifiers[$i] . "</option>";
                                }
                            echo "</select>";
                            echo "</div>";

                            //echo "<input type='text' name='timezone_offset' id='timezone_offset' value=''></input>";
                            echo "<input type='submit' class='myButton' value='Save' id='submit_timezone'></input>";                                         
                        echo "</form>";
                        echo "<br>";
                        echo "<p><span id='txtZone'></span></p>";                
                    echo "</fieldset>";
                    
                    //Timezone section///////////////////////////////////////////////////////////////////////////

                    echo "<br>";

                    //Location section///////////////////////////////////////////////////////////////////////////
                    echo "<fieldset id='location_set' style='padding:4px;'>";
                        echo "<legend style='text-align: left;'><h2>Location</h2></legend>";

                        echo "<div id='Location_map' style='height: 400px;'></div>";

                        //echo "<form id='locationform' method='get' target='frame_local' onsubmit=''";
                        //    $timezone_identifiers = DateTimeZone::listIdentifiers();
                        //    echo "<label><h3 style='text-align: center;'>Location form</h3></label>";                    
                        //    //echo "<select id='timezone_select' name='timezone_select' form='timezones' onchange=showTimezone(this.value)>";

                        //    echo "<input type='submit' class='myButton' value='Save' id='submit_locstion'></input>";                                         
                        //echo "</form>";
                   echo "</fieldset>";

                    //Location section///////////////////////////////////////////////////////////////////////////

                echo "</div>";
                //Settings page///////////////////////////////////////////////////////////////////////////


                //Members/Users page///////////////////////////////////////////////////////////////////////////
                echo "<div id='member_content_id' class='noshow'>";
                
                    echo "<br><h2>User managing stuff new, delete, edit...</h2>";
                        
                        echo "<table border='0' id='usertable' class='usertable'>";
                        
                            mysql_data_seek($result, 0);
                            while($row = mysql_fetch_array($result))
                            {

                                    $player = new PlayerEdit($row['playerID'], $row['name'], $row['mobile'], $row['mail'], $row['photourl'], $row['notify'], $row['firstname'], $row['lastname']);
                                                                
                                    echo "<tr>";

                                        //Image & Nickname
                                        echo "<td>";
                                            echo "<div class='chat-list-left' style='width: 50px;'>";
                                                echo "<img width='40' height='40' src='images/" . $player->photourl . "'>";
                                                echo "<br />";
                                                echo "<div class='comment-name' style='color: #474747; text-align: left;'>" . $player->name . "</div>";
                                            echo "</div>";
                                        echo "</td>";                  

                                        //Firstname Lastname, mobile, mail
                                        echo "<td>";
                                            echo "<div>";
                                                echo "" . $player->firstname . " " . $player->lastname . "";
                                                echo "<br />";
                                                echo "" . $player->mobile . "";
                                                echo "<br />";
                                                echo "" . $player->mail . "";
                                            echo "</div>";
                                        echo "</td>";                  

                                        //echo "<td class=''> <a href='#openModalEdit' class='myButton'>Edit</a></td>";
                                        echo "<td><a href='#openModalEdit'><img id='editPlayer' width='40' height='40' src='images/edit.png'></img></a></td>"; 
                                        
                                        echo "<td class=''> ID: " . $player->playerID . "</td>";
                                        
                                    echo "</tr>";
                                
                            }

                        echo "</table>";


                    //Modal dialog for player information editing///////////////
                    echo "<div id='openModalEdit' class='modalDialog'>";
	                    echo "<div>";
		                    echo "<a id='closer_edit' href='#close' title='Close' class='close'>X</a>";


                            echo "<form id='player_edit' name='player_edit' method='post' action='updatePlayer.php' target='frame_player' onsubmit='refreshPlayerInfo();'>";

                                echo "<p style='margin: 10px;'>";
                                echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%;'>Edit your information</label>";
                                echo "</p>";

                                echo "<p style='margin: 0px; padding-top: 10px;'>";
                                echo "<label for='player_name' style='display: inline-block; width: 60px; text-align: right;'>User ID:&nbsp</label>";                    
                                echo "<input type='text' id='dialog_player_name' name='player_name' value='name' required style='margin-bottom: 15px; background: grey; width: 190px;' readonly></input>";
                                echo "</p>";

                                echo "<p style='margin: 0px'>";
                                echo "<label for='player_email' style='display: inline-block; width: 60px; text-align: right;'>Email:&nbsp</label>";
                                echo "<input type='text' id='dialog_player_email' name='player_email' value='email' required style='margin-bottom: 15px; width: 190px;'></input>";
                                echo "</p>";

                                echo "<p style='margin: 0px'>";
                                echo "<label for='player_phone' style='display: inline-block; width: 60px; text-align: right;'>Phone:&nbsp</label>";
                                echo "<input type='text' id='dialog_player_phone' name='player_phone' value='phone' required style='margin-bottom: 15px; width: 190px;'></input>";
                                echo "</p>";


                          //      echo "<h5 id='dialog_player_notify'>Mail notifications:</h5>";
                          //          if( $player->notify == '1') {
                          //              echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
						                    //echo "<input type='checkbox' name='notifyswitch' class=\"onoffswitch-checkbox\" id='dialog_notify_switch' checked>";					            
                          //                  echo "<label class=\"onoffswitch-label\" for='dialog_notify_switch' onClick=''>";
                          //                      echo "<div class=\"notifyswitch-inner\"></div>";
						                    //    echo "<div class=\"onoffswitch-switch\"></div>";
						                    //echo "</label>";
                          //              echo "</div>";
                          //          } else {
                          //              echo "<div class=\"onoffswitch notifyswitch\" style='display: inline-block;'>";
						                    //echo "<input type='checkbox' name='notifyswitch' class=\"onoffswitch-checkbox\" id='dialog_notify_switch'>";
                          //                  echo "<label class=\"onoffswitch-label\" for='dialog_notify_switch' onClick=''>";
                          //                      echo "<div class=\"notifyswitch-inner\"></div>";
						                    //    echo "<div class=\"onoffswitch-switch\"></div>";
						                    //echo "</label>";
                          //              echo "</div>";                            
                          //          }
                          //      echo "</h5>";

                                echo "<div class='buttonHolder'>";
                                    echo "<input type='submit' value='Save' name='savebutton' id='savebutton_edit' class='dialog_button'>";
                                echo "</div>";
		                    echo "</form>";

                    //Modal dialog for player information editing//////////////////



                echo "</div>";
                //Members page///////////////////////////////////////////////////////////////////////////



            echo "</article>";
            //Article///////////////////////////////////////////////////////////////////////////
        
            echo "<iframe name=\"frame_local\" style='display: none'></iframe>";

            mysql_close($con);

        } //Admin



        class PlayerEdit {
            var $playerID;
            var $name;
            var $mobile;
            var $mail;
            var $photourl;
            var $notify;
            var $firstname;
            var $lastname;

            function PlayerEdit($playerID, $name, $mobile, $mail, $photourl, $notify, $firstname, $lastname) {
                $this->playerID = $playerID;
                $this->name = $name;
                $this->mail = $mail;
                $this->mobile = $mobile;
                $this->photourl = $photourl;
                $this->notify = $notify;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
            }

        }

?>