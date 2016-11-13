<?php
       include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

        session_start();
        
        //$teamid=1;
        $teamid=$_SESSION['myteamid'];
        $ad=$_SESSION['myAdmin'];
        $registrar=$_SESSION['myRegistrar'];

        //include 'ChromePhp.php';        
        //ChromePhp::log("players_insert, admin:", $ad);

    // if($_SESSION['adminSettingsPage'] == 0) { //Load page only once per session
            
    //     $_SESSION['adminSettingsPage'] = 1;

        if(($ad==1) || ($registrar==1) ) //Execute only for admin and registrar status
        {
            
            $con = mysql_connect($dbhost, $dbuser, $dbpass);

            if (!$con)
            {
              die('Could not connect: ' . mysql_error());
            }

            mysql_select_db("areyouin", $con);

            // $sql="SELECT p.playerID, p.name, p.mobile, p.mail, p.photourl, p.notify, p.firstname, p.lastname, pt.teamAdmin, pt.registrar, t.maxPlayers
            // FROM players p, playerteam pt, team t WHERE t.teamID = '" . $teamid . "' AND pt.team_teamID = '" . $teamid . "' AND pt.players_playerID = p.playerID
            // order by pt.registrar desc, pt.teamAdmin desc";
        
            // $result = mysql_query($sql);
            // $row_count = mysql_num_rows($result);


            /////////////////////////////////////////////////////////////////////////////////////////////
            //Settings page///////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////
            $sql_team="SELECT timezone, utcOffset FROM team WHERE teamID = '" . $teamid . "'";
            $res_team = mysql_query($sql_team);
            $row_team = mysql_fetch_array($res_team);


            echo "<div id=\"team_content_id\" class=''>";
                
                /////////////////////////////////////////////////////////////////////////////////////////////
                //Timezone section///////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////////////////////
                echo "<br>";
                echo "<fieldset id='timezone_set'>";
                    echo "<legend style='text-align: left;'><h2>Timezone</h2></legend>";
                    
                    echo "<div style='background-color: #b9b9b9; margin: 5px;'>";
                        echo "<h3 id='team_timezone' style='text-align: center;'>Current timezone:</h3>";
                        echo "<h4 id='team_timezone_value' style='text-align: center; margin-top: 0px;'>" . $row_team['timezone'] . "</h4>";
                    echo "</div>";

                    //echo "<form id='timezones' method='post' action='update_team.php' target='frame_local' onsubmit=\"showTimezone('Timezone set to:' + timezone_select.value)\"";
                    echo "<form user-scalable='0' id='timezones' method='get' target='frame_local' onsubmit='updateTimezone(timezone_select.value)'";
                        $timezone_identifiers = DateTimeZone::listIdentifiers();
                        echo "<label><h3 style='text-align: center;'>Set new timezone:</h3></label>";                    
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
                /////////////////////////////////////////////////////////////////////////////////////////////

                echo "<br>";

                /////////////////////////////////////////////////////////////////////////////////////////////
                //Locations section//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////////////////////

                echo "<fieldset id='location_set' style='padding:4px;'>";
                    echo "<legend style='text-align: left;'><h2>Locations</h2></legend>";

                        //Locations list sql
                    $sql_location="SELECT * FROM location WHERE teamID = '" . $teamid . "'";        
                    $result_location = mysql_query($sql_location);

                    echo "<div id='locations_list' class='scrollit2'>";

                        //echo "<h2>Test location header...</h3>";

                        echo "<table border='0' class='usertable' id='locations_table'>";
                    
                            //mysql_data_seek($result, 0);
                            $index_locations = 1000;

                            while($row_locations = mysql_fetch_array($result_location))
                            {

                                                            
                                    echo "<tr>";

                                        echo "<td>";
                                        echo "<div class='edit-listinfo'>";                                                                                                

                                            //locationID
                                            echo "<div style='display: none;'>";
                                                echo "" . $row_locations[locationID] . "";
                                            echo "</div>";

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


                                            echo "<form user-scalable='0' id='player_edit". $index_locations . "' name='player_edit". $index_locations . "' method='post' action='' target='frame_player' onsubmit='refreshPlayerInfo();'>";

                                                echo "<p style='margin: 10px;'>";
                                                echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%; color: black;'>Edit location</label>";
                                                echo "</p>";

                                                //LocationID
                                                echo "<p>";
                                                echo "<input type='text' id='dialog_location_id". $index_locations . "' name='location_id". $index_locations . "' value='". $row_locations[locationID] . "'
                                                        style='display:none;'></input>";
                                                echo "</p>";

                                                echo "<p style='margin: 0px; padding-top: 10px;'>";
                                                
                                                    echo "<label for='location_name". $index_locations . "' style='display: inline-block; width: 60px; text-align: right; color: black;'>Name:&nbsp</label>";                   
                                                    echo "<input type='text' id='dialog_location_name". $index_locations . "' name='location_name". $index_locations . "' value='". $row_locations[name] . "'
                                                            style='margin-bottom: 15px; width: 170px;'></input>";
                                                
                                                    echo "<label for='location_pos". $index_locations . "' style='display: inline-block; width: 60px; text-align: right; color: black;'>Position:&nbsp</label>";   
                                                    echo "<input type='text' id='dialog_location_pos". $index_locations . "' name='location_pos". $index_locations . "' value='". $row_locations[position] . "'
                                                            style='margin-bottom: 15px; width: 170px;' readonly></input>";
                                                
                                                echo "</p>";

                                                //Show weather for location
                                                echo "<h5 id='dialog_location_weather". $index_locations . "' class='dialog_player_notify' style='color: black;'>Weather: </h5>";
                                                
                                                if($row_locations[showWeather] == 0) {

                                                    echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
                                                    echo "<input type='checkbox' name='weatherswitch". $index_locations . "' class='onoffswitch-checkbox'
                                                            id='dialog_weather_switch". $index_locations . "'></input>";

                                                        echo "<label class=\"onoffswitch-label\" for='dialog_weather_switch". $index_locations . "' onClick=''>";
                                                            echo "<div class=\"notifyswitch-inner\"></div>";
                                                            echo "<div class=\"onoffswitch-switch\"></div>";
                                                        echo "</label>";
                                                    echo "</div>";

                                                } else {

                                                    echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
                                                    echo "<input type='checkbox' name='weatherswitch". $index_locations . "' class='onoffswitch-checkbox'
                                                            id='dialog_weather_switch". $index_locations . "' checked></input>";

                                                        echo "<label class=\"onoffswitch-label\" for='dialog_weather_switch". $index_locations . "' onClick=''>";
                                                            echo "<div class=\"notifyswitch-inner\"></div>";
                                                            echo "<div class=\"onoffswitch-switch\"></div>";
                                                        echo "</label>";
                                                    echo "</div>";                                                        

                                                }

                                                echo "<p id='weather_text". $index_locations . "' class='dialog_player_notify' style='color: black;'>Weather is shown in the events section.</p>";

                                                //Buttons                                               
                                                echo "<div class='buttonHolder' style='margin-top:15px;'>";

                                                    //Save   
                                                    echo "<input type='button' class='myButton' style='float: left; margin-left: 30px;' value='Save'
                                                    onclick='updateLocation(" . $index_locations . ")'></input>";
                                                                                                                                                                
                                                    //Delete
                                                    echo "<input type='button' class='myButton' style='color: red; float: rigth;' value='Delete'
                                                    onclick='deleteLocation(" . $row_locations[locationID] . ")'></input>";

                                                echo "</div>";

                                            echo "</form>";
                                        echo "</div>";
                                    echo "</div>";
                                    //Modal dialog for location information editing//////////////////               
                        
                                    $index_locations++;
                            }

                        echo "</table>";

                    echo "</div>"; //Scrollit, end of locations list//////////////////////////////
                    
                    echo "<br>";

                        //Modal dialog for new location information///////////////
                        echo "<div id='openModalEditNewLocation' class='modalDialog'>";
                            echo "<div>";
                                echo "<a id='closer_newLocation' href='#' title='Close' class='close'>X</a>";

                                echo "<form user-scalable='0' id='new_location_form' name='newLocationForm' method='post' action='' target='frame_player' onsubmit=''>";

                                    echo "<p style='margin: 10px;'>";
                                    echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%; color: black;'>Set new location</label>";
                                    echo "</p>";

                                    echo "<p style='margin: 0px; padding-top: 10px;'>";
                                                
                                        echo "<label for='location_name_new' style='display: inline-block; width: 60px; text-align: right; color: black;'>Name:&nbsp</label>";                   
                                        echo "<input type='text' id='dialog_location_name_new' name='location_name_new' value=''
                                                style='margin-bottom: 15px; width: 170px; required'></input>";
                                                
                                        echo "<label for='location_pos_new' style='display: inline-block; width: 60px; text-align: right; color: black;'>Position:&nbsp</label>";   
                                        echo "<input type='text' id='dialog_location_pos_new' name='location_pos_new' value=''
                                                style='margin-bottom: 15px; width: 170px;' readonly></input>";
                                                
                                    echo "</p>";

                                    //Show weather for location
                                    echo "<h5 id='dialog_location_weather_new' class='dialog_player_notify' style='color: black;'>Weather: </h5>";
                                                
                                    echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
                                        echo "<input type='checkbox' name='weatherswitch' class=\"onoffswitch-checkbox\" id='dialog_weather_switch_new'>";					            
                                        echo "<label class=\"onoffswitch-label\" for='dialog_weather_switch_new' onClick=''>";
                                            echo "<div class=\"notifyswitch-inner\"></div>";
                                            echo "<div class=\"onoffswitch-switch\"></div>";
                                        echo "</label>";

                                    echo "</div>";

                                    echo "<p id='weather_text_new' class='dialog_player_notify'>Weather is shown in the events section.</p>";

                                    //Buttons                                               
                                    echo "<div class='buttonHolder' style='margin-top:15px;'>";

                                        echo "<input type='button' class='myButton' style='float: center;' value='Save'
                                        onclick='addNewLocation(location_pos_new.value, location_name_new.value, " . $teamid . ", \"dialog_weather_switch_new\")'/>";

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
                /////////////////////////////////////////////////////////////////////////////////////////////

            echo "</div>";
            //Settings page///////////////////////////////////////////////////////////////////////////
            /////////////////////////////////////////////////////////////////////////////////////////////


        } //Admin

    //} //Page loaded in session


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
            var $registrar;

            function PlayerEdit($playerID, $name, $mobile, $mail, $photourl, $notify, $firstname, $lastname, $teamAdmin, $registrar) {
                $this->playerID = $playerID;
                $this->name = $name;
                $this->mail = $mail;
                $this->mobile = $mobile;
                $this->photourl = $photourl;
                $this->notify = $notify;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
                $this->teamAdmin = $teamAdmin;
                $this->registrar = $registrar;
            }

        }

?>
