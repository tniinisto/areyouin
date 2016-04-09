<?php

    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();
        
    $teamid=$_SESSION['myteamid'];
           
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


    $row_index = 1; 
    echo "<table border='0' id='insertplayers' class=\"atable2\" class='noshow'>"; 
        
    while($row = mysql_fetch_array($result))
    {
                    
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

            $row_index = $row_index + 1;
    }
    echo "</table>";

?>