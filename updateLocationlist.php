<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('updateLocationlist.php start');
    }


    //$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	//$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);                

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	    {
	    die('Could not connect: ' . mysql_error());
	    }

	mysql_select_db("areyouin", $con);

    try {

            //Select locations
            $sql = "SELECT * FROM areyouin.location WHERE teamID =  " . $_SESSION['myteamid'] . ";";
            $result = mysql_query($sql);

            if($_SESSION['ChromeLog']) { ChromePhp::log('updateLocationlist: ' . $sql); }
        
            //$stmt = $dbh->prepare($sql);
            //$stmt = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
            //$stmt->bindParam(':teamid',  $_SESSION['myteamid'], PDO::PARAM_INT);        
            //$stmt->execute();
            ////$data = $stmt->fetchAll();
            
            echo "<table border='0' class='usertable' id='locations_table'>";                     
            $index_locations = 1000;
            
            //foreach($data as $row_locations) {            
            //while($row_locations = $stmt->fetch(PDO::FETCH_ASSOC))

            while($row = mysql_fetch_array($result))
	        {
                echo "<tr>";

                    echo "<td>";
                    echo "<div class='edit-listinfo'>";                                                                                                

                    if($_SESSION['ChromeLog']) { ChromePhp::log('updateLocationlist loop, locationID: ' . $row_locations[locationID]); }

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


                        echo "<form id='player_edit". $index_locations . "' name='player_edit". $index_locations . "' method='post' action='' target='frame_player' onsubmit='refreshPlayerInfo();'>";

                            echo "<p style='margin: 10px;'>";
                            echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%;'>Edit location</label>";
                            echo "</p>";

                            //LocationID
                            echo "<p>";
                            echo "<input type='text' id='dialog_location_id". $index_locations . "' name='location_id". $index_locations . "' value='". $row_locations[locationID] . "'
                                    style='display:none;'></input>";
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


                            //Buttons                                               
                            echo "<div class='buttonHolder' style='margin-top:15px;'>";

                                //Save   
                                echo "<input type='button' class='dialog_button' style='float: left; margin-left: 30px;' value='Save'
                                onclick='updateLocation(" . $index_locations . ")'></input>";
                                                                                                                                                                    
                                //Delete
                                echo "<input type='button' class='dialog_button' style='color: red; float: rigth;' value='Delete'
                                onclick=''></input>";

                            echo "</div>";

		                echo "</form>";
                    echo "</div>";
                echo "</div>";
                //Modal dialog for location information editing//////////////////               
                            
                $index_locations++;
        }

        echo "</table>";



        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>


