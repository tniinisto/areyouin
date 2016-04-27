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
            $sql="SELECT p.playerID, p.name, p.mobile, p.mail, p.photourl, p.notify, p.firstname, p.lastname, pt.teamAdmin, t.maxPlayers
            FROM players p, playerteam pt, team t WHERE t.teamID = '" . $teamid . "' AND pt.team_teamID = '" . $teamid . "' AND pt.players_playerID = p.playerID";
        
            $result = mysql_query($sql);
            $row_count = mysql_num_rows($result);

            //Article///////////////////////////////////////////////////////////////////////////
            echo "<article id='admin_content_article' class='clearfix'>";
            
            //Navigation///////////////////////////////////////////////////////////////////////////
            echo "<nav>";
			    echo "<ul id='admin-nav' class='clearfix' onClick='adminClick()'>";
				    echo "<li id='link_admingame' class='current2' onclick='showInsertPlayers();'><a href='#'>New event</a></li>";
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
                                    echo "<td id='p_usercount" . $row_count . "' class=\"pcol1\"><input type=\"number\" name=\"playeramount\" value=\"" . $row_count . "\"></input></td>";
                                    echo "<td id='p_playerid" . $row['playerID'] . "' class=\"pcol2\"><input type=\"number\" name=\"playerid" . $row_index . "\" value=\"" . $row['playerID'] . "\"></input></td>";
                                    echo "<td id='p_photo" . $row['playerID'] . "' class=\"pcol3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
                                    echo "<td id='p_name" . $row['playerID'] . "' class=\"pcol4\">" . $row['name'] . "</td>";
                                    echo "<td id='p_switch" . $row['playerID'] . "' class=\"pcol5\">";
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
                                        echo "<td id='p_usercount" . $row_count . "' class=\"pcol1\"><input type=\"number\" name=\"playeramount\" value=\"" . $row_count . "\"></input></td>";
                                        echo "<td id='p_playerid" . $row['playerID'] . "' class=\"pcol2\"><input type=\"number\" name=\"playerid" . $row_index . "\" value=\"" . $row['playerID'] . "\"></input></td>";
                                        echo "<td id='p_photo" . $row['playerID'] . "' class=\"pcol3\"> <img width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
                                        echo "<td id='p_name" . $row['playerID'] . "' class=\"pcol4\">" . $row['name'] . "</td>";
                                        echo "<td id='p_switch" . $row['playerID'] . "' class=\"pcol5\">";
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
                            echo "<label><h3 style='text-align: center;'>Set timezone:</h3></label>";                    
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

                    //Locations section//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    
                    echo "<fieldset id='location_set' style='padding:4px;'>";
                        echo "<legend style='text-align: left;'><h2>Locations</h2></legend>";

                         //Locations list sql
                        $sql_location="SELECT * FROM location WHERE teamID = '" . $teamid . "'";        
                        $result_location = mysql_query($sql_location);

                        echo "<div id='locations_list' class='scrollit2'>";

                            //echo "<h2>Test location header...</h3>";

                            echo "<table border='0' class='usertable' id='locations_table'>";
                        
                                mysql_data_seek($result, 0);
                                $index_locations = 1000;

                                while($row_locations = mysql_fetch_array($result_location))
                                {

                                                                
                                        echo "<tr>";

                                          echo "<td>";
                                            echo "<div class='edit-listinfo'>";                                                                                                

                                                //Location name
                                                echo "<div style='font-weight: bold; overflow: hidden; text-overflow: ellipsis;'>";
                                                    echo "" . $row_locations[name] . "";
                                                echo "</div>";

                                                //Position
                                                echo "" . $row_locations[position] . "";

                                                //Call javacript to add markers on Map
                                                echo "<script> placeMarker(". $row_locations[position] . "); </script>";

                                            echo "</div>";
                                          echo "</td>";

                                          $position = $row_locations[position];
                                          list($lat, $lon) = split('[,]', $position);

                                          echo "<td id='showlocation'". $index_locations .">
                                            <a href='#'><img id='showLocation' style='padding-right: 15px;' width='40' height='40' src='images/maps_icon.jpg'
                                            onclick='placeMarker(". $lat . "," . $lon . ")'></img></a>
                                          </td>";

                                          //echo "<input type='button' class='' value='Show on map' onclick='placeMarker(". $row_locations[position] . ")'/>";

                                          echo "<td id='editrow'". $index_locations .">
                                            <a href='#openModalEdit". $index_locations . "'><img id='editLocation' width='40' height='40' src='images/edit.png'></img></a>
                                          </td>";
                                        
                                          //echo "<td style='display: none;'> teamID: " . $row_locations[teamID] . "</td>";
                                        
                                        echo "</tr>";
               
                                        //Modal dialog for location information editing///////////////
                                        echo "<div id='openModalEdit". $index_locations . "' class='modalDialog'>";
	                                        echo "<div>";
		                                        echo "<a id='closer_edit". $index_locations . "' href='#' title='Close' class='close'>X</a>";


                                                echo "<form id='player_edit". $index_locations . "' name='player_edit". $index_locations . "' method='post' action='' target='frame_player' onsubmit='refreshPlayerInfo();'>";

                                                    echo "<p style='margin: 10px;'>";
                                                    echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%;'>Location</label>";
                                                    echo "</p>";

                                                    echo "<p style='margin: 0px; padding-top: 10px;'>";
                                                    
                                                        echo "<label for='location_name". $index_locations . "' style='display: inline-block; width: 60px; text-align: right;'>Name:&nbsp</label>";                   
                                                        echo "<input type='text' id='dialog_location_name". $index_locations . "' name='location_name". $index_locations . "' value='". $row_locations[name] . "'
                                                               style='margin-bottom: 15px; width: 170px;'></input>";
                                                    
                                                        echo "<label for='location_pos". $index_locations . "' style='display: inline-block; width: 60px; text-align: right;'>Position:&nbsp</label>";   
                                                        echo "<input type='text' id='dialog_location_pos". $index_locations . "' name='location_pos". $index_locations . "' value='". $row_locations[position] . "'
                                                               style='margin-bottom: 15px; width: 170px;' readonly></input>";
                                                    
                                                    echo "</p>";

                                                    //Show weather for location
                                                    echo "<h5 id='dialog_location_weather". $index . "' class='dialog_player_notify'>Weather: </h5>";
                                                    
                                                    echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
						                                echo "<input type='checkbox' name='weatherswitch' class=\"onoffswitch-checkbox\" id='dialog_weather_switch". $index_locations . "'>";					            
                                                        echo "<label class=\"onoffswitch-label\" for='dialog_weather_switch". $index_locations . "' onClick=''>";
                                                            echo "<div class=\"notifyswitch-inner\"></div>";
						                                    echo "<div class=\"onoffswitch-switch\"></div>";
						                                echo "</label>";

                                                    echo "</div>";

                                                    //Buttons                                               
                                                    echo "<div class='buttonHolder' style='margin-top:15px;'>";

                                                        echo "<input type='button' class='dialog_button' style='float: left; margin-left: 30px;' value='Save'
                                                        onclick=''/>";

                                                        echo "<input type='button' class='dialog_button' style='color: red; float: rigth;' value='Delete'
                                                        onclick=''/>";

                                                    echo "</div>";

		                                        echo "</form>";
                                            echo "</div>";
                                        echo "</div>";
                                        //Modal dialog for location information editing//////////////////               
                            
                                        $index_locations++;
                                }

                            echo "</table>";

                        echo "</div>"; //Scrollit, end of locations list//////////////////////////////
                        
                        echo "</br>";

                            //Modal dialog for new location information///////////////
                            echo "<div id='openModalEditNewLocation' class='modalDialog'>";
	                            echo "<div>";
		                            echo "<a id='closer_newLocation' href='#' title='Close' class='close'>X</a>";

                                    echo "<form id='new_location_form' name='newLocationForm' method='post' action='' target='frame_player' onsubmit=''>";

                                        echo "<p style='margin: 10px;'>";
                                        echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%;'>Set new location</label>";
                                        echo "</p>";

                                        echo "<p style='margin: 0px; padding-top: 10px;'>";
                                                    
                                            echo "<label for='location_name_new' style='display: inline-block; width: 60px; text-align: right;'>Name:&nbsp</label>";                   
                                            echo "<input type='text' id='dialog_location_name_new' name='location_name_new' value=''
                                                    style='margin-bottom: 15px; width: 170px; required'></input>";
                                                    
                                            echo "<label for='location_pos_new' style='display: inline-block; width: 60px; text-align: right;'>Position:&nbsp</label>";   
                                            echo "<input type='text' id='dialog_location_pos_new' name='location_pos_new' value=''
                                                    style='margin-bottom: 15px; width: 170px;' readonly></input>";
                                                    
                                        echo "</p>";

                                        //Show weather for location
                                        echo "<h5 id='dialog_location_weather_new' class='dialog_player_notify'>Weather: </h5>";
                                                    
                                        echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
						                    echo "<input type='checkbox' name='weatherswitch' class=\"onoffswitch-checkbox\" id='dialog_weather_switch_new'>";					            
                                            echo "<label class=\"onoffswitch-label\" for='dialog_weather_switch_new' onClick=''>";
                                                echo "<div class=\"notifyswitch-inner\"></div>";
						                        echo "<div class=\"onoffswitch-switch\"></div>";
						                    echo "</label>";

                                        echo "</div>";

                                        //Buttons                                               
                                        echo "<div class='buttonHolder' style='margin-top:15px;'>";

                                            echo "<input type='button' class='dialog_button' style='float: center;' value='Save'
                                            onclick='addNewLocation(" . json_encode('location_pos_new.value') . ", location_name_new.value, " . $teamid . ", \"dialog_weather_switch_new\")'/>";

                                        echo "</div>";

		                            echo "</form>";
                                echo "</div>";
                            echo "</div>";
                        //Modal dialog for new location information///////////////
                        
                        echo "<div id='Location_map' style='height: 400px;'></div>";

                        //echo "<form id='locationform' method='get' target='frame_local' onsubmit=''";
                        //    $timezone_identifiers = DateTimeZone::listIdentifiers();
                        //    echo "<label><h3 style='text-align: center;'>Location form</h3></label>";                    
                        //    //echo "<select id='timezone_select' name='timezone_select' form='timezones' onchange=showTimezone(this.value)>";

                        //    echo "<input type='submit' class='myButton' value='Save' id='submit_locstion'></input>";                                         
                        //echo "</form>";
                   echo "</fieldset>";

                    //Location section//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                echo "</div>";
                //Settings page///////////////////////////////////////////////////////////////////////////


                //Members/Users page///////////////////////////////////////////////////////////////////////////
                echo "<div id='member_content_id' class='noshow'>";
                    
                    //echo "<h2>User managing stuff new, delete ok, edit ok...</h2>";

                    mysql_data_seek($result, 0);
                    $row = mysql_fetch_array($result);
                    echo "<h2>Users: " . $row_count . " / " . $row[maxPlayers] . "</h2>";

                    echo "<br>";

                    //Add new user
                    if($row_count < $row[maxPlayers]) {
                        echo "<a href='#openModal_new' id='addUserButton' class='myButton' style='float: left;'>Add new user</a>";
                    }

                    echo "<br>";
                    echo "<br>";

                            //Modal dialog for new user/////////////////////////////////////////////////////////
                            echo "<div id='openModal_new' class='modalDialog'>";
	                            echo "<div>";

		                            echo "<a id='closer' href='#' title='Close' class='close' onclick='resetModalUserDialog()'>X</a>";

                                    echo "<form id='player_new' name='player_new' method='get' target='frame_player'>";

                                        //User already in the team, give notification
                                        echo "<p style='margin: 0px; padding-top: 0px;' class='noshow' id='p_existing_user_dialog'>";
                                        echo "<br>";
                                        echo "<label style='display: block; text-align: center; margin-left:-10px; weight: bold; width: 110%; font-size: 125%;'>User is already a team member!</label>";
                                        echo "</p>";

                                        //PlayerID, hidden
                                        echo "<p style='margin: 0px; padding-top: 0px;' class='noshow' id='p_new_dialog_playerid'>";
                                        echo "<input type='text' id='new_dialog_playerid' name='player_playerid' value='' style='margin-bottom: 15px; width: 180px;'></input>";
                                        echo "</p>";

                                        //Mail & UserID
                                        echo "<div id='player_new_mail' style='text-align: center; margin: auto; display: inline-block; width: 100%; padding-top: 5px;'>";
                                            echo "<label id='new_dialog_mail_text' class='mailclass'>Enter user's email</label>";
                                            echo "<br>";
                                            echo "<p style='margin: 0px'>";
                                            echo "<input type='text' id='dialog_player_new_email' name='player_new_email' value='" . $player->email ."' required
                                                   style='margin-bottom: 15px; width: 210px;' onblur='validateEmail(this.value);'></input>";                                            
                                        echo "</div>";

                                        //Header
                                        echo "<p style='margin: 0px; padding-top: 0px;' class='noshow' id='p_dialog_player_new_header'>";
                                        echo "<label style='display: block; text-align: center; font-weight: bold; width: 100%; font-size: 125%;'>Enter user's info</label>";
                                        echo "</p>";
                                        echo "<br>";

                                        //Nickname
                                        echo "<p style='margin: 0px; padding-top: 0px;' class='noshow' id='p_dialog_player_new_name'>";
                                        echo "<label for='player_new_name' style='display: inline-block; width: 60px; text-align: right; color:black;'>Nickname:&nbsp</label>";                    
                                        echo "<input type='text' id='dialog_player_new_name' name='player_new_name' value='" . $player->name ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                        echo "</p>";
                                
                                        //Fullname
                                        echo "<p style='margin: 0px; padding-top: 0px; margin-top: -5px;' class='noshow' id='p_dialog_player_new_firstname'>";
                                        echo "<label for='player_new_firstname' style='display: inline-block; width: 60px; text-align: right; color:black;'>Firstname:&nbsp</label>";                    
                                        echo "<input type='text' id='dialog_player_new_firstname' name='player_new_firstname' value='" . $player->firstname ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                        echo "</p>";

                                        //Lastname
                                        echo "<p style='margin: 0px; padding-top: 0px; margin-top: -5px;' class='noshow' id='p_dialog_player_new_lastname'>";
                                        echo "<label for='player_new_lastname' style='display: inline-block; width: 60px; text-align: right; color:black;'>Lastname:&nbsp</label>";                    
                                        echo "<input type='text' id='dialog_player_new_lastname' name='player_new_lastname' value='" . $player->lastname ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                        echo "</p>";

                                        //Phone
                                        //echo "<p style='margin: 0px; padding-top: 0px; padding-bottom: 2px; margin-top: -5px;' class='noshow'>";
                                        //echo "<label for='player_new_phone' style='display: inline-block; width: 60px; text-align: right; color:black;'>Phone:&nbsp</label>";
                                        //echo "<input type='text' id='dialog_player_new_phone' name='player_new_phone' value='" . $player->phone ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                        //echo "</p>";


                                  //      echo "<h5 id='dialog_player_new_notify' class='noshow' style='color:black; font-weight: normal;'>Mail notifications:</h5>";
                                  //          if( $player->notify == '1') {
                                  //              echo "<div class='onoffswitch notifyswitch noshow' style='display: inline-block;'>";
						                            //echo "<input type='checkbox' name='notifyswitch' class='onoffswitch-checkbox noshow' id='dialog_player_new_notify_switch' checked>";					            
                                  //                  echo "<label class='onoffswitch-label noshow' for='dialog_player_new_notify_switch' onClick=''>";
                                  //                      echo "<div class=\"notifyswitch-inner\"></div>";
						                            //    echo "<div class=\"onoffswitch-switch\"></div>";
						                            //echo "</label>";
                                  //              echo "</div>";
                                  //          } else {
                                  //              echo "<div class='onoffswitch notifyswitch noshow' style='display: inline-block;'>";
						                            //echo "<input type='checkbox' name='notifyswitch' class='onoffswitch-checkbox noshow' id='dialog_player_new_notify_switch'>";
                                  //                  echo "<label class='onoffswitch-label noshow' for='dialog_player_new_notify_switch' onClick=''>";
                                  //                      echo "<div class=\"notifyswitch-inner\"></div>";
						                            //    echo "<div class=\"onoffswitch-switch\"></div>";
						                            //echo "</label>";
                                  //              echo "</div>";                            
                                  //          }
                                  //      echo "</h5>";

                                        echo "<div class='buttonHolder' style='padding-top: 2px;'>";

                                            //Insert the player for the team, send mail for the new user
                                            echo "<input type='button' value='Save' name='player_new_savebutton' id='player_new_savebutton' class='dialog_button noshow'
                                                   onclick='addTeamUser(" . $teamid . ", player_new_email.value, player_new_name.value, player_new_firstname.value, player_new_lastname.value)'>";

                                            //Validate the email entered, does email already exist, is user already in the team. If already in another team, show name and ask if this should be insterted for the team
                                            echo "<input type='button' value='Validate' name='player_new_validatebutton' id='player_new_validatebutton' class='dialog_button' style='text-align: center;'    
                                                   onclick='newValidateEmail(player_new_email.value)'>";

                                            //Add existing RYouIN user
                                            echo "<input type='button' value='Add user' name='player_new_add' id='player_new_add_button' class='dialog_button noshow' style='text-align: center;'    
                                                   onclick='addExistingUser(player_playerid.value, " . $teamid . ")'>";

                                        echo "</div>";
		                            echo "</form>";
                                echo "</div>";
                            echo "</div>";
                            //Modal dialog for new user/////////////////////////////////////////////////////////

                    echo "<br>";
                    
                    echo "<div id='users_list' class='scrollit2'>";

                        echo "<table border='0' id='users_table' class='usertable'>";
                        
                            mysql_data_seek($result, 0);
                            $index = 1;
                            while($row = mysql_fetch_array($result))
                            {

                                    $player = new PlayerEdit($row['playerID'], $row['name'], $row['mobile'], $row['mail'], $row['photourl'], $row['notify'], $row['firstname'], $row['lastname'], $row['teamAdmin']);
                                                                
                                    echo "<tr>";

                                        //Image & Nickname
                                        echo "<td>";
                                            echo "<div class='chat-list-left' style='width: 50px;'>";
                                                echo "<img width='40' height='40' src='images/" . $player->photourl . "'>";
                                                echo "<br />";
                                                echo "<div class='comment-name' style='color: #474747; text-align: left; padding-left: 2px; font-weight: bold; width: 55px;'>" . $player->name . "</div>";
                                                 echo "<div id='player_name" . $index . "' class='noshow'>".$player->name."</div>";
                                            echo "</div>";
                                        echo "</td>";                  

                                        //Firstname Lastname, mobile, mail, teamAdmin
                                        echo "<td>";
                                            echo "<div class='edit-listinfo'>";

                                                if($player->teamAdmin == 1)
                                                    echo "<div style='font-weight: bold;'>Team Admin</div>";
                                                echo "<div id='player_admin" . $index . "' class='noshow'>".$player->teamAdmin."</div>";

                                                echo "" . $player->firstname . " " . $player->lastname . "";
                                                echo "<div id='player_firstname" . $index . "' class='noshow'>".$player->firstname."</div>";
                                                echo "<div id='playerlastname" . $index . "' class='noshow'>".$player->lastname."</div>";
                                                echo "<br />";
                                                echo "" . $player->mobile . "";
                                                echo "<div id='player_modile" . $index . "' class='noshow'>".$player->mobile."</div>";
                                                echo "<br />";
                                                echo "" . $player->mail . "";
                                                echo "<div id='player_mail" . $index . "' class='noshow'>".$player->mail."</div>";

                                            echo "</div>";
                                        echo "</td>";                  

                                        //echo "<td class=''> <a href='#openModalEdit' class='myButton'>Edit</a></td>";
                                        echo "<td id='editrow'". $index ."><a href='#openModalEdit". $index . "'><img id='editPlayer' width='40' height='40' src='images/edit.png'></img></a></td>"; 
                                        
                                        echo "<td style='display: none;'> ID: " . $player->playerID . "</td>";
                                        
                                    echo "</tr>";
               
                                    //Modal dialog for player information editing///////////////
                                    echo "<div id='openModalEdit". $index . "' class='modalDialog'>";
	                                    echo "<div>";
		                                    echo "<a id='closer_edit". $index . "' href='#' title='Close' class='close'>X</a>";


                                            echo "<form id='player_edit". $index . "' name='player_edit". $index . "' method='post' action='' target='frame_player' onsubmit='refreshPlayerInfo();'>";

                                                echo "<p style='margin: 10px;'>";
                                                echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%;'>Edit user status</label>";
                                                echo "</p>";

                                                echo "<p style='margin: 0px; padding-top: 10px;'>";
                                                echo "<label for='player_name". $index . "' style='display: inline-block; width: 60px; text-align: right;'>Name:&nbsp</label>";                    
                                                echo "<input type='text' id='dialog_player_name". $index . "' name='player_name". $index . "' value='". $player->firstname . " " . $player->lastname ."' required style='margin-bottom: 15px; width: 170px;' readonly></input>";
                                                echo "</p>";

                                                //echo "<p style='margin: 0px'>";
                                                //echo "<label for='player_email". $index . "' style='display: inline-block; width: 60px; text-align: right;'>Email:&nbsp</label>";
                                                //echo "<input type='text' id='dialog_player_email". $index . "' name='player_email". $index . "' value='". $player->mail ."' required style='margin-bottom: 15px; width: 190px;'></input>";
                                                //echo "</p>";

                                                //echo "<p style='margin: 0px'>";
                                                //echo "<label for='player_phone". $index . "' style='display: inline-block; width: 60px; text-align: right;'>Phone:&nbsp</label>";
                                                //echo "<input type='text' id='dialog_player_phone". $index . "' name='player_phone". $index . "' value='" . $player->mobile . "' required style='margin-bottom: 15px; width: 190px;'></input>";
                                                //echo "</p>";


                                                echo "<h5 id='dialog_player_notify". $index . "' class='dialog_player_notify'>Team Admin: </h5>";
                                                    if( $player->teamAdmin == 1) {
                                                        echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
						                                    echo "<input type='checkbox' name='adminswitch' class=\"onoffswitch-checkbox\" id='dialog_admin_switch". $index . "' checked>";					            
                                                            echo "<label class=\"onoffswitch-label\" for='dialog_admin_switch". $index . "' onClick=''>";
                                                                echo "<div class=\"notifyswitch-inner\"></div>";
						                                        echo "<div class=\"onoffswitch-switch\"></div>";
						                                    echo "</label>";
                                                        echo "</div>";
                                                    } else {
                                                        echo "<div class=\"onoffswitch notifyswitch\" style='display: inline-block;'>";
						                                    echo "<input type='checkbox' name='adminswitch' class=\"onoffswitch-checkbox\" id='dialog_admin_switch". $index . "'>";
                                                            echo "<label class=\"onoffswitch-label\" for='dialog_admin_switch". $index . "' onClick=''>";
                                                                echo "<div class=\"notifyswitch-inner\"></div>";
						                                        echo "<div class=\"onoffswitch-switch\"></div>";
						                                    echo "</label>";
                                                        echo "</div>";                            
                                                    }
                                                echo "</h5>";

                                                //echo "<div class='buttonHolder'>";
                                                //    echo "<input type='submit' value='Save' name='savebutton' id='savebutton_edit' class='dialog_button'>";
                                                //echo "</div>";
                                                
                                                echo "<div class='buttonHolder' style='margin-top:15px;'>";

                                                    echo "<input type='button' class='dialog_button' style='float: left; margin-left: 30px;' value='Save'
                                                        onclick='updateAdminStatus(" . $player->playerID . ", \"dialog_admin_switch". $index . "\");'/>";

                                                    echo "<input type='button' class='dialog_button' style='color: red; float: rigth;' value='Delete'
                                                    onclick='confirmDelete(" . $player->playerID . ");'/>";

                                                echo "</div>";

		                                    echo "</form>";

                                    //Modal dialog for player information editing//////////////////               
                            
                                    $index++;
                            }

                        echo "</table>";

                    echo "</div>"; //Scrollit

                echo "</div>"; //Member content
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
            var $teamAdmin;

            function PlayerEdit($playerID, $name, $mobile, $mail, $photourl, $notify, $firstname, $lastname, $teamAdmin) {
                $this->playerID = $playerID;
                $this->name = $name;
                $this->mail = $mail;
                $this->mobile = $mobile;
                $this->photourl = $photourl;
                $this->notify = $notify;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
                $this->teamAdmin = $teamAdmin;
            }

        }

?>