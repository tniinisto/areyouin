<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    //Maximum number of events listed at once
    define('MAX_NRO_EVENTS', 3);

    //More events parameter & session//////////////////////
    $moreevents=$_GET["more"];    
    if($moreevents == 0) {
        $_SESSION['more_clicks'] = 0;
    }
    else {
        $_SESSION['more_clicks'] = $moreevents;
    }
    //More events parameter & session//////////////////////

    if($_SESSION['logged_in'] == TRUE) { //Session on and user logged in -> list events ///////////////////////////////////////
    
        $playerid=$_SESSION['myplayerid'];
	    $teamid=$_SESSION['myteamid'];
        $ad=$_SESSION['myAdmin'];
        $registrar=$_SESSION['myRegistrar'];

        $con = mysql_connect($dbhost, $dbuser, $dbpass);
	    if (!$con)
	      {
	      die('Could not connect: ' . mysql_error());
	      }

	    mysql_select_db($dbname, $con);
        $offset = $moreevents * MAX_NRO_EVENTS;

        //Count timezone offset to support daylight savings
        $timezone=$_SESSION['mytimezone']; 

        $daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open($timezone), new DateTime() ); 
        $team_offset = round($daylight_savings_offset_in_seconds/3600); //Hours        

        $dst = 0;
        if (date('I', time()))
        {
            //echo 'We’re in DST!';
            $dst = 1;
        }
        else
        {
            //echo 'We’re not in DST!';
            $dst = 0;
        }        
               
        //Display notification for admins & registrar on the license payment, 3 days before///////////////////////////////////
        if( ($moreevents == 0) && ($_SESSION['myAdmin'] == 1 || $_SESSION['myRegistrar'] == 1) ) {         

            $licenseValid = new DateTime($_SESSION['mylicense']);            
            $licenseValid = $licenseValid->modify('-3 day');

            $currentDate = new DateTime('now');            

            $interval = $currentDate->diff(new DateTime($_SESSION['mylicense']))->days + 1;

            if($licenseValid < $currentDate) {
                echo "<article id='event_article_licens_id' class='event_article clearfix'>";
                    echo "<div>";
                        echo "<h3 style='text-align: center; color: #313131;'>Team's license expires in " . $interval .  " days. Purchase new license through the Admin - License menu.</h3>";
                    echo "</div>";
                echo "</article>";
            }
        }
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //Get events in set limit
        $sql_events = "SELECT SQL_CALC_FOUND_ROWS e.eventID, e.startTime FROM events e
                       where e.Team_teamID = '" . $teamid  . "' and (e.endTime - INTERVAL " . $team_offset . " HOUR) > now()
                       order by e.startTIme asc
                       LIMIT " . MAX_NRO_EVENTS . " OFFSET " . $offset . ";";
        $rows_events = mysql_query($sql_events);        
        $eventrow = 0;
        $eventIDs = 0;
        $eventrow = mysql_fetch_array($rows_events);
        $eventIDs = $eventrow['eventID'];
	    while($eventrow = mysql_fetch_array($rows_events)) {
            $eventIDs .= ", " . $eventrow['eventID'];
        }

        //Get total event amount
        $sql_total = "SELECT FOUND_ROWS() AS total;";
        $rows_total = mysql_query($sql_total);
        $total = mysql_fetch_array($rows_total);
        $totalrows = $total['total'];                        

        //Check DST
        if($dst == 0) {
            console.warn('DST off'); 
            //Get events with players
            $sql = 
            "SELECT e.private, ep.Events_eventID, l.name as location, l.position as pos, e.startTime, e.endTime, p.playerid, p.name,
            p.photourl, ep.EventPlayerID, ep.areyouin, ep.seen, t.teamID, t.teamName, pt.teamAdmin
            FROM events e
            inner join location l on l.locationID = e.Location_locationID
            inner join eventplayer ep on ep.Events_eventID = e.eventID
            inner join players p on ep.Players_playerID = p.playerID
            inner join playerteam pt on pt.Players_playerID = p.playerID
            inner join team t on t.teamID = pt.Team_teamID
            where t.teamID = '" . $teamid  . "' and e.Team_teamID = t.teamID
            and (e.endTime - INTERVAL " . $_SESSION['myoffset'] . " HOUR) > now()
            and ep.Events_eventID IN (". $eventIDs .")
            order by e.startTime asc, ep.Events_eventID asc, ep.areyouin desc, ep.seen desc;";
        } else { //DST valid
            console.warn('DST on'); 
            //Get events with players
            $sql = 
            "SELECT e.private, ep.Events_eventID, l.name as location, l.position as pos, e.startTime, e.endTime, p.playerid, p.name,
            p.photourl, ep.EventPlayerID, ep.areyouin, ep.seen, t.teamID, t.teamName, pt.teamAdmin
            FROM events e
            inner join location l on l.locationID = e.Location_locationID
            inner join eventplayer ep on ep.Events_eventID = e.eventID
            inner join players p on ep.Players_playerID = p.playerID
            inner join playerteam pt on pt.Players_playerID = p.playerID
            inner join team t on t.teamID = pt.Team_teamID
            where t.teamID = '" . $teamid  . "' and e.Team_teamID = t.teamID
            and (e.endTime - INTERVAL " . ($_SESSION['myoffset'] + 1) . " HOUR) > now()
            and ep.Events_eventID IN (". $eventIDs .")
            order by e.startTime asc, ep.Events_eventID asc, ep.areyouin desc, ep.seen desc;";            
        }

	    //Go through events & players
        $result = mysql_query($sql);
	    $event_check = 0; //Check when the event changes
	    $row_index = 1 + $moreevents; //Unique naming for switches
        $private = 0; //Private event        
	    while($row = mysql_fetch_array($result))
	    {
            //Check private event showing
            $private = $row['private'];

            $sql_private = "SELECT players_playerid, Events_eventID FROM eventplayer, events WHERE Events_eventID = eventID and Players_playerID = " . $playerid . " and Events_eventID = " . $row['Events_eventID'] . "";
	        $result_private = mysql_query($sql_private);
            $row_private = mysql_fetch_array($result_private);

            //Private event and player selected for game -> show event, public event -> show event, admin and registrar sees everything
            if(($private == 1 && $row_private) || ($private == 0) || ($ad == 1) || ($registrar == 1) ) {
            
                //Check when the event changes, then echo the event basic information/////////////////////////////////////
		        if($row['Events_eventID'] != $event_check)
		        {		
			        if($event_check != 0) {
                        echo "</div>";
                        echo "</article>";
		            }

			        $event_check = $row['Events_eventID'];

                    //Get current users information
                    $sql3 = "SELECT eventplayerid, players_playerid, areyouin, seen, photourl, name FROM eventplayer, players WHERE Players_playerID = playerID and Players_playerID = " . $playerid . " and Events_eventID = " . $event_check . "";
	                $result3 = mysql_query($sql3);
                    $row3 = mysql_fetch_array($result3);

                    //Unique event id
                    $eventID_unique = 'event_article_' . $row_index;
			        echo "<article id='". $eventID_unique ."' style='padding: 1px 5px 18px 5px;' class='event_article clearfix'>";
                
                    echo "<div class=\"divtable\">&nbsp"; //Background for the header part
            
                    //Admin & Registrar event update button - DONT SHOW EVENT UPDATE BUTTON ON MOBILE VIEW
                    // if(($ad==1) || ($registrar == 1))
                    //     echo "<img id=\"update_event\" onClick=\"eventFetchOff(); updateEvent(" . $event_check . ");\" width=\"40\" height=\"40\" src=\"images/edit.png\" style=\"cursor: pointer;\"></img>";
                    // else
                    //     echo "<img id=\"update_event\" width=\"40\" height=\"40\" src=\"images/edit.png\" style=\"visibility:hidden;\"></img>"; 
			
                    //Event summary info, Invited players
                    $sql4 = "SELECT count(*) as player_amount FROM eventplayer WHERE Events_eventID = " . $row['Events_eventID'] . " AND
                             Players_playerID IN (SELECT Players_playerID FROM playerteam WHERE Team_teamID = " . $teamid . ")";
                    $result4 = mysql_query($sql4);
                    $row4 = mysql_fetch_array($result4);

                    //Event summary info, Players IN
                    $sql5 = "SELECT count(*) as players_in FROM eventplayer WHERE Events_eventID = " . $row['Events_eventID'] . " AND areyouin = 1 AND
                             Players_playerID IN (SELECT Players_playerID FROM playerteam WHERE Team_teamID = " . $teamid . ")";
                    $result5 = mysql_query($sql5);
                    $row5 = mysql_fetch_array($result5);

                    //Get needed participant count
                    //$minimum = minParticipantsCount($event_check);

                    //Event summary row & expand
                    echo "<table class=\"atable_summary\">";
				        echo "<tr style=\"cursor: pointer;\">";
                            //Set the summary text color depending on checked players//////////////////////////

                            //Still more than 1 player needed, yellow
                            //if($minimum - $row5['players_in'] > 1) {
                                echo "<th id=\"id_summary" . $event_check . "\" style=\"text-align: center; color: #CEB425;\" onClick=\"showPlayers(" . $event_check . ")\">
                                Event status: " . $row5['players_in'] . " / " . $row4['player_amount'] . "</th>";
                            //}
                            //else {
                            //    //Only one player missing, pink
                            //    if($minimum - $row5['players_in'] == 1) {
                            //        echo "<th id=\"id_summary" . $event_check . "\" style=\"text-align: center; color: #ff006e;\" onClick=\"showPlayers(" . $event_check . ")\">
                            //        Players IN: " . $row5['players_in'] . " / " . $row4['player_amount'] . "</th>";
                            //    }
                            //    //Enough players, green
                            //    else { 
                            //        echo "<th id=\"id_summary" . $event_check . "\" style=\"text-align: center; color: #00ff21;\" onClick=\"showPlayers(" . $event_check . ")\">
                            //        Players IN: " . $row5['players_in'] . " / " . $row4['player_amount'] . "</th>";                            
                            //    }
                            //}
				        echo "</tr>";
			        echo "</table>";            
            
                    //Event location information
                    echo "<table class=\"atable\">";			    
            	        echo "<tr>";
					        echo "<th> Event @&nbsp";
                                
                                if($row['location'] != 'No location set')
                                    echo "<a href=\"https://maps.google.fi/maps?q=" . $row['pos'] . "\" target='_blank'>" . $row['location'] . "</a>&nbsp";
                                else
                                    echo "" . $row['location'] . "";

                                //Dont show location icon for "No location"
                                if($row['location'] != 'No location set') {
                                    echo "<a style='text-decoration: none;' href=\"https://maps.google.fi/maps?q=" . $row['pos'] . "\" target='_blank'>
                                        <img style='vertical-align: middle;' src='../images/GoogleMapsIcon.png' alt='maps' height='18' width='18' style='padding-top: 0px;'>
                                    </a>";
                                }

                            echo "</th>";
				        echo "</tr>";
			        echo "</table>";
			
                    //Event date&time information
			        echo "<table class=\"atable\">";
                            //$day2 used when event lasts multiple days
                            $day2 = "";
                            if(!(substr($row['startTime'], 8, 2) == substr($row['endTime'], 8, 2)))
                                $day2 = date("l j.", mktime(0, 0, 0, substr($row['endTime'], 5, 2), substr($row['endTime'], 8, 2), substr($row['endTime'], 0, 4)));

				        echo "<tr>";
				            //$day = date("l jS \of F Y", mktime(0, 0, 0, substr($row['startTime'], 5, 2), substr($row['startTime'], 8, 2), substr($row['startTime'], 0, 4)));
                            $day = date("l j.n.Y", mktime(0, 0, 0, substr($row['startTime'], 5, 2), substr($row['startTime'], 8, 2), substr($row['startTime'], 0, 4)));
					        $res1 = substr($row['startTime'], 11, 5);
					        $res2 = substr($row['endTime'], 11, 5);
					        echo "<th>On " . $day . "</th>";
				        echo "</tr>";
			        echo "</table>";
			
                    echo "<table class=\"atable\">";
				        echo "<tr>";
					        echo "<th>From " . $res1 . " to " . $day2 . " " . $res2 . "</th>";
				        echo "</tr>";
			        echo "</table>";

                    //Current player's row, if the player is selected to the event
                    if($row3['players_playerid'] == $playerid) {
                        echo "<table class=\"atable\">";
		                    //echo "<th>";
                                echo "<tr class=\"row_player\">";				
				                echo "<th class=\"col11\">" . $row3['eventplayerid'] . "</th>";
				                //echo "<th class=\"col21\">" . $row3['playerid'] . "</th>";
                                echo "<th class=\"col21\">" . $playerid . "</th>";
				                if($row3['seen'] == 1)
					                echo "<th class=\"col31\"><img class=\"seen\" width=\"40\" height=\"40\" src=\"../images/" . $row3['photourl'] . "\"></th>";
				                else
					                echo "<th class=\"col31\"><img class=\"unseensummary\" width=\"40\" height=\"40\" src=\"../images/" . $row3['photourl'] . "\"></th>";
				                echo "<th class=\"col41\">" . $row3['name'] . "</th>";
								        
					            if($row3['areyouin'] == 0) {
						            echo "<th class=\"col51\">";
							            echo "<div class=\"onoffswitch\">";
								            echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id='myonoffswitch" . $row_index . "" . $moreevents . "' checked>";
								            //echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '1')\">";
                                            echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "" . $moreevents . "\"
                                            onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '1', '". $event_check . "', '" . $row_index . "" . $moreevents . "')\">";
                                            
                                            echo "<div class=\"onoffswitch-inner\"></div>";
								            echo "<div class=\"onoffswitch-switch\"></div>";
								            echo "</label>";
							            echo "</div>";
						            echo "</th>";
						
						            //Update the seen status
						            if($row3['seen'] == 0) {
							            $sql2= "UPDATE eventplayer SET seen = '1' WHERE EventPlayerID = " . $row3['eventplayerid'] . "";
							            $result2 = mysql_query($sql2);
						            }	
					            }
					            else {
						            echo "<th class=\"col51\">";
							            echo "<div class=\"onoffswitch\">";
								            echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id='myonoffswitch" . $row_index . "" . $moreevents . "'>";
								            //echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '0')\">";
                                            echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "" . $moreevents . "\" 
                                            onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '0', '". $event_check . "',  '" . $row_index . "" . $moreevents . "')\">";
                                            
                                            echo "<div class=\"onoffswitch-inner\"></div>";
								            echo "<div class=\"onoffswitch-switch\"></div>";
								            echo "</label>";
							            echo "</div>";
						            echo "</th>";
					
						            //Update the seen status
						            if($row3['seen'] == 0) {
							            $sql2= "UPDATE eventplayer SET seen = '1' WHERE EventPlayerID = " . $row3['eventplayerid'] . "";
							            $result2 = mysql_query($sql2);
						            }					
					            }	

			                echo "</tr>";
		                echo "</table>";
                    }
            
                    //Bottom table for rounded corners
                    echo "<table class=\"lastrow2\">";
				        echo "<tr style=\"cursor: pointer;\">";
					        //echo "<th style=\"text-align:right;\" onClick=\"showPlayers(" . $event_check . ")\">Click for others >>></th>";
                            echo "<th>&nbsp</th>";
				        echo "</tr>";
			        echo "</table>";

                    echo "</div>"; //divtable (background for the header part)
                        
                    //Open first event in full
                    //if($row_index == 1)
                    //    echo "<div id=\"id_playersfull_" . $event_check . "\">";
                    //else
                        echo "<div id=\"id_playersfull_" . $event_check . "\" class=\"box visuallynoshow noshow\">";
		        }

		        //Echo players for the event////////////////////////////////////////////////////
		        if($playerid != $row['playerid']) {
                    echo "<table class=\"atable2\">";
				            echo "<tr>";				
				            echo "<td class=\"col1\">" . $row['eventplayerid'] . "</td>";
				            echo "<td class=\"col2\">" . $row['playerid'] . "</td>";
				            if($row['seen'] == 1)
					            echo "<td class=\"col3\"><img class=\"seen\" width=\"40\" height=\"40\" src=\"../images/" . $row['photourl'] . "\"></td>";
				            else
					            echo "<td class=\"col3\"><img class=\"unseen\" width=\"40\" height=\"40\" src=\"../images/" . $row['photourl'] . "\"></td>";
				            echo "<td class=\"col4\">" . $row['name'] . "</td>";
				
				            //Show on/off switch only for the user
				            if($playerid != $row['playerid']) {
					            if($row['areyouin'] == 0)
						            echo "<td class=\"col5\">OUT</td>";
					            else
						            echo "<td class=\"col5\">IN</td>";					
				            }

			            echo "</tr>";
		            echo "</table>";
                }
		    
            }

		    $row_index = $row_index + 1;
	    }	
	
        //Close the last event's article
        if($event_check != 0) {
            echo "</article>";
        }

        //Display no event scheduled info if there are no events
        if($event_check == 0) {
            echo "<article id='event_article_id' class='event_article clearfix'>";
                echo "<div>";

                    echo "<h3 style=\"text-align: center;\">No events currently scheduled...</h3>";
                    //echo "<h3 style=\"text-align: center;\">Kenttä paketissa kauden 2014 osalta, kiitokset peleistä!</h3>";

                echo "</div>";
            echo "</article>";
        }

        //More events info///////////////////////////////////////////////////////////////////        
        if($totalrows > (($moreevents + 1) * MAX_NRO_EVENTS)) {
            
            $eventsleft = $totalrows - (($moreevents + 1) * MAX_NRO_EVENTS);  

            $call = $_SESSION['more_clicks'] + 1;
            echo "<div id='more_events_content". $call ."' class='ajax_loader'>";
                //echo "<article id='event_article_id' style='background-color: #6a6a6a; width: 99%;' class='clearfix'>";
                echo "<article id='event_article_id' class='event_article clearfix'>";
                    echo "<div class='divtable'>&nbsp";
                        //echo "<h3 style=\"text-align: center;\">Total " . $totalrows . "</h3>";
                        //echo "<h3 style=\"text-align: center;\">MAX_NRO_EVENTS " . MAX_NRO_EVENTS . "</h3>";
                        echo "<table class='atable_summary'>";
				            echo "<tr style='cursor: pointer;'>";
                                echo "<th style='padding-bottom: 10px; text-align: center; color: #ffd800; text-decoration: underline;' onclick='getEvents(" . $call . ")'>
                                <a href='#' style='color: #CEB425;'  onclick='getEvents(" . $call . ")'>More events available</a></th>";        
                                //<a href='#' style='color: #ffd800;'  onclick='getEvents(" . $call . ")'>" . $eventsleft . " more events available</a></th>";        
				            echo "</tr>";
    		            echo "</table>";  
                        echo "<table class='lastrow2'>";
				            echo "<tr>";
                                echo "<th style='border-top-left-radius: 0px; border-top-right-radius: 0px; border-bottom-right-radius: 6px; border-bottom-left-radius: 6px;'>&nbsp</th>";
				            echo "</tr>";
    		            echo "</table>";  
                    echo "</div>";
                echo "</article>";
            echo "</div>";

        }
        //More events info///////////////////////////////////////////////////////////////////        

        //Weather info///////////////////////////////////////////////////////////////////        
        
        //Find weather only for the first set, not for more event
        if($moreevents == 0) {
        
            $sql_weather = "select distinct name, position from location l, team t where l.teamID = " . $teamid . " and l.showWeather = 1 and l.teamID = t.teamID";
            $result_weather = mysql_query($sql_weather);
	
            while($row_weather = mysql_fetch_array($result_weather)) {
                $latlon = explode(", ", $row_weather['position']);
            
                echo "<article id=\"event_article_id\" class='event_article clearfix'>";
                    echo "<div style='width=100%; overflow: hidden;'>";
                        echo "<iframe 
            	            id='forecast_embed'
	                        type='text/html'
	                        frameborder='0'
	                        height='245'
	                        width='100%'
	                        src='https://forecast.io/embed/#lat=" . str_replace(' ', '', $latlon[0]) . "&lon=" . str_replace(' ', '', $latlon[1]) . "&name=" . $row_weather['name'] . "&color=#00aaff&font=Georgia&units=si'>         
                        </iframe>";
                    echo "</div>";
                echo "</article>";         	    
            }
        
        }
        /////////////////////////////////////////////////////////////////////////////////
        
        mysql_close($con);
    } //END OF event_list

    //Returns the minParticipants count for the event (Depends on the sport)
    function minParticipantsCount($ev) {

        	$sql10 =
            "select * from events e
            inner join eventtype t on t.eventTypeID = e.EventType_eventTypeID
            where eventID = ". $ev . ";";	 	

	        $result10 = mysql_query($sql10);
            $row10 = mysql_fetch_array($result10);

            return $row10['minParticipants'];
    }
	
?>
  
