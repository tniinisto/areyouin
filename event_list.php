<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    //include 'ChromePhp.php';
    //$password = $_SESSION['mypassword'];        
    //ChromePhp::log("MD5:", $password);
	
    //Old implementation with URL paramaters
    //$teamid=$_GET["teamid"];
	//$playerid=$_GET["playerid"];

    //Check session expiration & logged_in status OLD PART
    //if(!isset($_SESSION['logged_in'])) {
    //    //ChromePhp::log("Session expired, \$_SESSION['logged_in']=", $_SESSION['logged_in']);
    //    header("location:default.html");
    //}
    //else

    if($_SESSION['logged_in'] == TRUE) { //Session on and user logged in -> list events ///////////////////////////////////////
    
        $playerid=$_SESSION['myplayerid'];
	    $teamid=$_SESSION['myteamid'];
        $ad=$_SESSION['myAdmin'];

        $con = mysql_connect($dbhost, $dbuser, $dbpass);
	    if (!$con)
	      {
	      die('Could not connect: ' . mysql_error());
	      }

	    mysql_select_db("areyouin", $con);

	    //$sql = 
     //   "SELECT v.Events_eventID, l.name as location,l.position as pos, e.startTime, e.endTime, p.playerid, p.name, p.photourl, v.eventplayerid, v.areyouin, v.seen, m.teamID, m.teamName, a.teamAdmin
     //   FROM events e, eventtype t, location l, players p,  eventplayer v, team m, playerteam a
     //   WHERE t.eventTypeID = e.EventType_eventTypeID and l.locationID = e.Location_locationID and p.playerID = v.Players_playerID and v.Events_eventID = e.eventID and a.Players_playerID = p.playerID
     //   and a.Team_teamID = //m.teamID        and m.teamID = '" . $teamid  . "' and e.endTime > now() order by e.startTime asc, v.Events_eventID asc, v.areyouin desc, v.seen desc";
	 	
        //if($_SESSION['myoffset'] >= 0) {
        //    $time_condition = "(e.endTime - INTERVAL " . $_SESSION['myoffset'] . " HOUR)";
        //} else {
        //    $time_condition = "(e.endTime - INTERVAL " . $_SESSION['myoffset'] . " HOUR)";
        //}

                
        $sql = 
        "SELECT ep.Events_eventID, l.name as location, l.position as pos, e.startTime, e.endTime, p.playerid, p.name,
        p.photourl, ep.EventPlayerID, ep.areyouin, ep.seen, t.teamID, t.teamName, pt.teamAdmin
        FROM events e
        inner join location l on l.locationID = e.Location_locationID
        inner join eventplayer ep on ep.Events_eventID = e.eventID
        inner join players p on ep.Players_playerID = p.playerID
        inner join playerteam pt on pt.Players_playerID = p.playerID
        inner join team t on t.teamID = pt.Team_teamID
        where t.teamID = '" . $teamid  . "' and e.Team_teamID = t.teamID
        and (e.endTime - INTERVAL " . $_SESSION['myoffset'] . " HOUR) > now()
        order by e.startTime asc, ep.Events_eventID asc, ep.areyouin desc, ep.seen desc;";

	    $result = mysql_query($sql);
	
	    //Go through events & players
	    $event_check = 0; //Check when the event changes
	    $row_index = 1; //Unique naming for switches
	    while($row = mysql_fetch_array($result))
	    {
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
			
			    echo "<article id=\"event_article_id\" class=\"clearfix\">";
            
                echo "<div class=\"divtable\">&nbsp"; //Background for the header part
            
                //Admin's event update button
                if($ad==1)
                    echo "<img id=\"update_event\" onClick=\"updateEvent(" . $event_check . ")\" width=\"40\" height=\"40\" src=\"images/edit.png\" style=\"cursor: pointer;\"></img>";
                else
                    echo "<img id=\"update_event\" width=\"40\" height=\"40\" src=\"images/edit.png\" style=\"visibility:hidden;\"></img>"; 

                //Top table for rounded corners
       //         echo "<table class=\"lastrow\">";
			    //	echo "<tr style=\"cursor: pointer;\">";
			    //		//echo "<th style=\"text-align:right;\" onClick=\"showPlayers(" . $event_check . ")\">Click for others >>></th>";
       //                 echo "<th>&nbsp</th>";
			    //	echo "</tr>";
			    //echo "</table>";
			
                //Event summary info, Invited players
                $sql4 = "SELECT count(*) as player_amount FROM eventplayer WHERE Events_eventID = " . $row['Events_eventID'] . "";
                $result4 = mysql_query($sql4);
                $row4 = mysql_fetch_array($result4);

                //Event summary info, Players IN
                $sql5 = "SELECT count(*) as players_in FROM eventplayer WHERE Events_eventID = " . $row['Events_eventID'] . " and areyouin = 1";
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
                            echo "<th id=\"id_summary" . $event_check . "\" style=\"text-align: center; color: #ffd800;\" onClick=\"showPlayers(" . $event_check . ")\">
                            Players IN: " . $row5['players_in'] . " / " . $row4['player_amount'] . "</th>";
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
					    echo "<th> Games @&nbsp <a href=\"https://maps.google.fi/maps?q=" . $row['pos'] . "\"&npsp target=\"_blank\">" . $row['location'] . "</a></th>";
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
					            echo "<th class=\"col31\"><img class=\"seen\" width=\"40\" height=\"40\" src=\"images/" . $row3['photourl'] . "\"></th>";
				            else
					            echo "<th class=\"col31\"><img class=\"unseensummary\" width=\"40\" height=\"40\" src=\"images/" . $row3['photourl'] . "\"></th>";
				            echo "<th class=\"col41\">" . $row3['name'] . "</th>";
								        
					        if($row3['areyouin'] == 0) {
						        echo "<th class=\"col51\">";
							        echo "<div class=\"onoffswitch\">";
								        echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch" . $row_index . "\" checked>";
								        //echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '1')\">";
                                        echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '1', '". $event_check . "', '". $row_index . "')\">";
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
								        echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch" . $row_index . "\">";
								        //echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '0')\">";
                                        echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '0', '". $event_check . "',  '". $row_index . "')\">";
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
					        echo "<td class=\"col3\"><img class=\"seen\" width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
				        else
					        echo "<td class=\"col3\"><img class=\"unseen\" width=\"40\" height=\"40\" src=\"images/" . $row['photourl'] . "\"></td>";
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
		
		    $row_index = $row_index + 1;
	    }	
	
        //Close the last event's article
        if($event_check != 0) {
            echo "</article>";
        }

        //Display no event scheduled info if there are no games
        if($event_check == 0) {
            echo "<article id=\"event_article_id\" class=\"clearfix\">";
                echo "<div>";

                    echo "<h3 style=\"text-align: center;\">No games currently scheduled...</h3>";
                    //echo "<h3 style=\"text-align: center;\">Kenttä paketissa kauden 2014 osalta, kiitokset peleistä!</h3>";

                echo "</div>";
            echo "</article>";

        }

        //Weather info///////////////////////////////////////////////////////////////////
        $sql_weather = "select distinct name, position from location where teamID = " . $teamid . "";
        $result_weather = mysql_query($sql_weather);
	
        while($row_weather = mysql_fetch_array($result_weather)) {

            $lonlat = explode(", ", $row_weather['position']);

            echo "<article id=\"event_article_id\" class=\"clearfix\">";
                echo "<div>";
                    echo "<iframe 
            	        id='forecast_embed'
	                    type='text/html'
	                    frameborder='0'
	                    height='245'
	                    width='100%'
	                    src='http://forecast.io/embed/#lat=" . str_replace(' ', '', $lonlat[0]) . "&lon=" . str_replace(' ', '', $lonlat[1]) . "&name=" . $row_weather['name'] . "&color=#00aaff&font=Georgia&units=si'>                       
                    </iframe>";
                echo "</div>";
            echo "</article>";
	    }
        /////////////////////////////////////////////////////////////////////////////////
        
        mysql_close($con);
    } //END OF event_list

    //Returns the minParticipants count for the event (Depends on the sport)
    function minParticipantsCount($ev) {

        	$sql10 =
            "select * from areyouin.events e
            inner join areyouin.eventtype t on t.eventTypeID = e.EventType_eventTypeID
            where eventID = ". $ev . ";";	 	

	        $result10 = mysql_query($sql10);
            $row10 = mysql_fetch_array($result10);

            return $row10['minParticipants'];
    }
	
?>
  
