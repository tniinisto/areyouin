<?php
        session_start();
        
        //$teamid=1;
        $teamid=$_SESSION['myteamid'];
        $ad=$_SESSION['myAdmin'];

        //include 'ChromePhp.php';        
        //ChromePhp::log("players_insert, admin:", $ad);

        if($ad==1) //Execute only for admin status
        {
            $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');

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
                    echo "</br>";
                    echo "<input type=\"submit\" value=\"Create Game\" id=\"submitgame\"></input>"; 
                    echo "</form>";
                echo "</div>";
                //New game///////////////////////////////////////////////////////////////////////////


                //Team page///////////////////////////////////////////////////////////////////////////
                echo "<div id=\"#team_content_id\">";
                    
                    echo "<h1>Testing team info</h1>";


                    //echo "<div id=\"testdiv\" class=\"scrollit\">";
                    echo "<div id=\"testdiv\" class=\"scrollit\" style=\"height: 200px; overflow-x: hidden; overflow-y: scroll; webkit-overflow-scrolling: touch;\">";
                      
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 1</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 2</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 3</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 4</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 5</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 6</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 7</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 8</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 9</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 21</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 22</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 23</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 24</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 25</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 26</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 27</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 28</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 29</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 291</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 292</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 293</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 294</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 295</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 296</a>";
                        echo "</br>";
                        echo "<a href=\"\" style=\"display: inline;\">item .......................................... 297</a>";
                        echo "</br>";

                    echo "</div>";

                echo "</div>";
                //Team page///////////////////////////////////////////////////////////////////////////


            echo "</article>";
            //Article///////////////////////////////////////////////////////////////////////////
        
            mysql_close($con);
        }
?>