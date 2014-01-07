<?php
    session_start();

    //include 'ChromePhp.php';
    //$password = $_SESSION['mypassword'];        
    //ChromePhp::log("MD5:", $password);
	
    //Old implementation with URL paramaters
    //$teamid=$_GET["teamid"];
	//$playerid=$_GET["playerid"];

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];
    $ad=$_SESSION['myAdmin'];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

	$sql = "SELECT v.Events_eventID, l.name as location,l.position as pos, e.startTime, e.endTime, p.playerid, p.name, p.photourl, v.eventplayerid, v.areyouin, v.seen, m.teamID, m.teamName, a.teamAdmin FROM events e, eventtype t, location l, players p,  eventplayer v, team m, playerteam a WHERE t.eventTypeID = e.EventType_eventTypeID and l.locationID = e.Location_locationID and p.playerID = v.Players_playerID and v.Events_eventID = e.eventID and a.Players_playerID = p.playerID and a.Team_teamID = m.teamID and m.teamID = '" . $teamid  . "' and e.endTime > now() order by e.startTime asc, v.Events_eventID asc, v.areyouin desc";
	 	
	$result = mysql_query($sql);

	//$sql3 = "SELECT v.Events_eventID, l.name as location,l.position as pos, e.startTime, e.endTime, p.playerid, p.name, p.photourl, v.eventplayerid, v.areyouin, v.seen, m.teamID, m.teamName, a.teamAdmin FROM events e, eventtype t, location l, players p,  eventplayer v, team m, playerteam a WHERE t.eventTypeID = e.EventType_eventTypeID and l.locationID = e.Location_locationID and p.playerID = v.Players_playerID and v.Events_eventID = e.eventID and a.Players_playerID = p.playerID and a.Team_teamID = m.teamID and m.teamID = '" . $teamid  . "' and e.endTime > now() and p.playerid = '" . $playerid . "'order by e.startTime asc, v.Events_eventID asc, v.areyouin desc";
	// 	
	//$result3 = mysql_query($sql3);
 //   $row3 = mysql_fetch_array($result3);
	
	//Go through events & players
	$event_check = 0; //Check when the event changes
	$row_index = 1; //Unique naming for switches
	while($row = mysql_fetch_array($result))
	{
		
        
        //Check when the event changes, then echo the event basic information
		if($row['Events_eventID'] != $event_check)
		{		
			if($event_check != 0) {
				echo "</div>";
                echo "</article>";
		    }

			$event_check = $row['Events_eventID'];

            $sql3 = "SELECT eventplayerid, areyouin, seen, photourl, name FROM eventplayer, players WHERE Players_playerID = playerID and Players_playerID = " . $playerid . " and Events_eventID = " . $event_check . "";
	        $result3 = mysql_query($sql3);
            $row3 = mysql_fetch_array($result3);
			
			echo "<article id=\"event_article_id\" class=\"clearfix\">";
            echo "<img id=\"update_event\" onClick=\"updateEvent(" . $event_check . ")\" width=\"40\" height=\"40\" src=\"images\edit.png\" style=\"cursor: pointer;\"></img>";


            //Top table
            echo "<table border='0' class=\"lastrow\">";
				echo "<tr style=\"cursor: pointer;\">";
					//echo "<th style=\"text-align:right;\" onClick=\"showPlayers(" . $event_check . ")\">Click for others >>></th>";
                    echo "<th>&nbsp</th>";
				echo "</tr>";
			echo "</table>";
			
            //Invited players
            $sql4 = "SELECT count(*) as player_amount FROM eventplayer WHERE Events_eventID = " . $row['Events_eventID'] . "";
            $result4 = mysql_query($sql4);
            $row4 = mysql_fetch_array($result4);

            //Players IN
            $sql5 = "SELECT count(*) as players_in FROM eventplayer WHERE Events_eventID = " . $row['Events_eventID'] . " and areyouin = 1";
            $result5 = mysql_query($sql5);
            $row5 = mysql_fetch_array($result5);

            //Summary row & expand
            echo "<table border='0' class=\"atable\">";
				echo "<tr style=\"cursor: pointer;\">";
					//echo "<th>&nbsp</th>";
                    //echo "<th>&nbsp&nbsp&nbsp</th>";
                    echo "<th id=\"id_summary\" style=\"text-align: center;\" onClick=\"showPlayers(" . $event_check . ")\">Players IN: " . $row5['players_in'] . " / " . $row4['player_amount'] . "</th>";
                    //echo "<th>&nbsp&nbsp&nbsp</th>";
                    //echo "<th style=\"text-align: right;\" onClick=\"showPlayers(" . $event_check . ")\"&nbsp&nbsp&nbsp</th>";
                    //echo "<th style=\"text-align:center;\"><img width=\"40\" height=\"10\" src=\"images\edit.png\"  onClick=\"showPlayers(" . $event_check . ")\"></th>";
                    //echo "<th>Col2</th>";
				echo "</tr>";
			echo "</table>";            
            
            echo "<table border='0' class=\"atable\">";			    
            	echo "<tr>";
					echo "<th> Games @&nbsp <a href=\"https://maps.google.fi/maps?q=" . $row[pos] . "\"&npsp target=\"_blank\">" . $row['location'] . "</a></th>";
				echo "</tr>";
			echo "</table>";
			
			echo "<table border='0' class=\"atable\">";
                    //$day2 used when event lasts multiple days
                    $day2 = "";
                    if(!(substr($row['startTime'], 8, 2) == substr($row['endTime'], 8, 2)))
                        $day2 = date("j. \of l", mktime(0, 0, 0, substr($row['endTime'], 5, 2), substr($row['endTime'], 8, 2), substr($row['endTime'], 0, 4)));

				echo "<tr>";
				    //$day = date("l jS \of F Y", mktime(0, 0, 0, substr($row['startTime'], 5, 2), substr($row['startTime'], 8, 2), substr($row['startTime'], 0, 4)));
                    $day = date("l j.n.Y", mktime(0, 0, 0, substr($row['startTime'], 5, 2), substr($row['startTime'], 8, 2), substr($row['startTime'], 0, 4)));
					$res1 = substr($row['startTime'], 11, 5);
					$res2 = substr($row['endTime'], 11, 5);
					echo "<th>On " . $day . "</th>";
				echo "</tr>";
			echo "</table>";
			
			//Empty table as divider between event data & players
			/*echo "<table border='0' class=\"atable\" visibility=\"hidden\">";
				echo "<tr>";
					echo "<th></th>";
				echo "</tr>";
			echo "</table>";*/
			
            //Echo event update if admin rights
       //     if($ad==1) {
			    //echo "<table border='0' class=\"atable\">";
				   // echo "<tr>";
					  //  echo "<th>From " . $res1 . " to " . $day2 . " " . $res2 . "</th>";
					  //  //<a href="javascript:update_event()"></a>)
				   // echo "</tr>";
			    //echo "</table>";
       //     }
       //     else {
                echo "<table border='0' class=\"atable\">";
				    echo "<tr>";
					    echo "<th>From " . $res1 . " to " . $day2 . " " . $res2 . "</th>";
				    echo "</tr>";
			    echo "</table>";
                //echo "<img id=\"update_event2\" onClick=\"updateEvent(" . $event_check . ")\" width=\"40\" height=\"40\" src=\"images\sort_down.png\" style=\"cursor: pointer;\"></img>";
       //     }

            //Current player's row
            echo "<table border='0' class=\"atable\">";
		        //echo "<th>";
                    echo "<tr class=\"row_player\">";				
				    echo "<th class=\"col11\">" . $row3['eventplayerid'] . "</th>";
				    echo "<th class=\"col21\">" . $row3['playerid'] . "</th>";
				    if($row3['seen'] == 1)
					    echo "<th class=\"col31\"><img class=\"seen\" width=\"40\" height=\"40\" src=\"images/" . $row3['photourl'] . "\"></th>";
				    else
					    echo "<th class=\"col31\"><img class=\"unseen\" width=\"40\" height=\"40\" src=\"images/" . $row3['photourl'] . "\"></th>";
				    echo "<th class=\"col41\">" . $row3['name'] . "</th>";
				
				    //Show on/off switch only for the user
				    //if($playerid != $row3['playerid']) {
					   // if($row3['areyouin'] == 0)
						  //  echo "<td class=\"col5\">OUT</td>";
					   // else
						  //  echo "<td class=\"col5\">IN</td>";					
				    //}
				    {
					    if($row3['areyouin'] == 0) {
						    echo "<th class=\"col51\">";
							    echo "<div class=\"onoffswitch\">";
								    echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch" . $row_index . "\" checked>";
								    echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '1')\">";
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
								    echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row3['eventplayerid'] . ", '0')\">";
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
				    }
				    //echo "<td class=\"col6\">" . $row['seen'] . "</td>";
                    //echo "</th>";
			    echo "</tr>";
		    echo "</table>";

   //         //Invited players
   //         $sql4 = "SELECT count(*) as player_amount FROM eventplayer WHERE Events_eventID = " . $event_check . "";
   //         $result4 = mysql_query($sql4);
   //         $row4 = mysql_fetch_array($result4);

   //         //Players IN
   //         $sql5 = "SELECT count(*) as players_in FROM eventplayer WHERE Events_eventID = " . $event_check . " and areyouin = 1";
   //         $result5 = mysql_query($sql5);
   //         $row5 = mysql_fetch_array($result5);

   //         //Summary row & expand
   //         echo "<table border='0' class=\"lastrow\">";
			//	echo "<tr style=\"cursor: pointer;\">";
			//		//echo "<th>&nbsp</th>";
   //                 echo "<th>&nbsp&nbsp&nbsp</th>";
   //                 echo "<th id=\"id_summary\" style=\"text-align: center;\" onClick=\"showPlayers(" . $event_check . ")\">Players " . $row5['players_in'] . " / " . $row4['player_amount'] . "</th>";
   //                 echo "<th>&nbsp&nbsp&nbsp</th>";
   //                 //echo "<th style=\"text-align: right;\" onClick=\"showPlayers(" . $event_check . ")\"&nbsp&nbsp&nbsp</th>";
   //                 //echo "<th style=\"text-align:center;\"><img width=\"40\" height=\"10\" src=\"images\edit.png\"  onClick=\"showPlayers(" . $event_check . ")\"></th>";
   //                 //echo "<th>Col2</th>";
			//	echo "</tr>";
			//echo "</table>";

            //Bottom table
            echo "<table border='0' class=\"lastrow2\">";
				echo "<tr style=\"cursor: pointer;\">";
					//echo "<th style=\"text-align:right;\" onClick=\"showPlayers(" . $event_check . ")\">Click for others >>></th>";
                    echo "<th>&nbsp</th>";
				echo "</tr>";
			echo "</table>";
            
            
            //Open first event in full
            if($row_index == 1)
                echo "<div id=\"id_playersfull_" . $event_check . "\">";
            else
                echo "<div id=\"id_playersfull_" . $event_check . "\" class=\"noshow\">";
		}

		//Echo players for the event
		if($playerid != $row['playerid']) {
            echo "<table border='0' class=\"atable2\">";
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
				    //else {
					   // if($row['areyouin'] == 0) {
						  //  echo "<td class=\"col5\">";
							 //   echo "<div class=\"onoffswitch\">";
								//    echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch" . $row_index . "\" checked>";
								//    echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row['eventplayerid'] . ", '1')\">";
								//    echo "<div class=\"onoffswitch-inner\"></div>";
								//    echo "<div class=\"onoffswitch-switch\"></div>";
								//    echo "</label>";
							 //   echo "</div>";
						  //  echo "</td>";
						
//						  //  //Update the seen status
						  //  if($row['seen'] == 0) {
							 //   $sql2= "UPDATE eventplayer SET seen = '1' WHERE EventPlayerID = " . $row['eventplayerid'] . "";
							 //   $result2 = mysql_query($sql2);
						  //  }	
					   // }
					   // else {
						  //  echo "<td class=\"col5\">";
							 //   echo "<div class=\"onoffswitch\">";
								//    echo "<input type=\"checkbox\" name=\"onoffswitch\" class=\"onoffswitch-checkbox\" id=\"myonoffswitch" . $row_index . "\">";
								//    echo "<label class=\"onoffswitch-label\" for=\"myonoffswitch" . $row_index . "\" onClick=\"updateAYI(" . $row['eventplayerid'] . ", '0')\">";
								//    echo "<div class=\"onoffswitch-inner\"></div>";
								//    echo "<div class=\"onoffswitch-switch\"></div>";
								//    echo "</label>";
							 //   echo "</div>";
						  //  echo "</td>";
					
	//					  //  //Update the seen status
						  //  if($row['seen'] == 0) {
							 //   $sql2= "UPDATE eventplayer SET seen = '1' WHERE EventPlayerID = " . $row['eventplayerid'] . "";
							 //   $result2 = mysql_query($sql2);
						  //  }					
					   // }	
				    //}
				    //echo "<td class=\"col6\">" . $row['seen'] . "</td>";
			    echo "</tr>";
		    echo "</table>";
        }
		
		$row_index = $row_index + 1;
	}	
	
	mysql_close($con);
	
?>
  
