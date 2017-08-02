<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();
    
    //date_default_timezone_set('Europe/Helsinki');

    //include 'ChromePhp.php';        
    //ChromePhp::log("starting chat...");

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

    ////Check session expiration & logged_in status
    //if(!isset($_SESSION['logged_in'])) {
    //    //ChromePhp::log("Session expired, \$_SESSION['logged_in']=", $_SESSION['logged_in']);
    //    ob_end_clean();
    //    header("location:default.php");
    //}
    //else if($_SESSION['logged_in'] == TRUE) {
	    
        // $con = mysql_connect($dbhost, $dbuser, $dbpass);
	    // if (!$con)
	    //     {
	    //     die('Could not connect: ' . mysql_error());
	    //     }

	    // mysql_select_db($dbname, $con);

        //PDO - UTF-8
        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);	
	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Get current users lastseen datetime & update to session
        // $sql5 = "SELECT pt.lastMsg as lastMsg FROM players, playerteam pt WHERE playerID = " . $playerid . " AND pt.Team_teamID = " . $teamid . " AND playerID = pt.Players_playerID";
	    // $result5 = mysql_query($sql5);
        // $row5 = mysql_fetch_array($result5);
        // $_SESSION['mylastmsg'] = $row5['lastMsg'];

        //PDO. utf-8, Get current users info///////////////////////////////////////////////////        
        $sql1 = "SELECT name, photourl, pt.lastMsg as lastMsg FROM players, playerteam pt WHERE playerID = :playerid AND pt.Team_teamID = :teamid AND playerID = pt.Players_playerID";
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':playerid', $playerid, PDO::PARAM_INT);
        $stmt1->bindParam(':teamid', $teamid, PDO::PARAM_INT);
    
        $result1 = $stmt1->execute();

        $row1;
        while($row1 = $stmt1->fetch()) {
            $GLOBALS['MYPLAYER'] = $row1;
        }

        getComments($teamid);

        function getComments($p_teamid) {                                
            //$sql = "SELECT * FROM comments WHERE team_teamID = " . $p_teamid . "";
            //$sql = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = " . $p_teamid . " order by c.publishTime desc";
                    
            //PDO//////////////////////////////////////////////////////////////////////////////
            $sql2 = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = :teamid order by c.publishTime desc";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2->bindParam(':teamid', $p_teamid, PDO::PARAM_INT);
            
            $result2 = $stmt2->execute();
   
            // $row2;
            // while($row2 = $stmt2->fetch()) {
            //     $GLOBALS['chatresult'] += $row2;
            // }

            //$GLOBALS['commentsresult'] = mysql_query($sql);
            //$GLOBALS['row'] = mysql_fetch_array($result);
            //ChromePhp::log("select: ",  $GLOBALS['row']['comment']);
        }

        $lastmsgdatetime;

        echo "<table id=\"comments_table\" class=\"atable\" border=\"0\">";
                    
            $limit=30;
            $i=0;

           while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                            if($i < $limit) {                        
                                
                                //Save the newest chat comment's datetime and update the last seen message to session
                                if($i == 0) {
                                    $lastmsgdatetime = $row['publishTime'];                                    
                                    $_SESSION['mylastmsg'] = $row1['lastMsg'];
                                }

                                // echo "<tr class=\"chatrow\">";
                                //    echo "<td width=\"80px\" height=\"auto\" align=\"center\"><img class=\"seenchat\" src=\"images/" . $row['photourl'] . "\"><br><text class=\"chatname\" style=\"color: white;\">" . $row['name'] . "</text></td>";
                                //    echo "<td width=\"500px\" height=\"auto\"><text class=\"commentArea1\">" . $published->format("j.n.Y H:i") . "</text><textarea maxlength=\"500\" readonly class=\"commentArea2\" id=\"area" . $i ."\">" . $row['comment'] . "</textarea></td>";
                                // echo "</tr>";

                                echo "<tr class=\"chatrow\">";

                                    echo "<td valign=\"top\">";
                                              echo "<div>";
                                                echo "<div class='chat-list-left'>";
                                                    echo "<img width='30' height='30' src='images/" . $row['photourl'] . "'>";
                                                    echo "<br />";
                                                    echo "<div class='comment-name'>" . $row['name'] . "</div>";
                                                echo "</div>";
                                                echo "<br />";
                                                echo "<div class='chat-list-right'>";
                                                    $published = new DateTime($row['publishTime']);       
                                                    echo "<div class='comment-time'>" . $published->format("j.n.Y H:i") . "</div>";                        
                                                    echo "<div class='comment-text'>" . $row['comment'] . "</div>";
                                                echo "</div>";
                                            echo "</div>";
                                    echo "</td>";
                    
                                echo "</tr>";

                                $i++;
                            }
                            else {
                                break;
                            }
                        }

        echo "</table>";

        
        echo "<div id='latestMsg' style='display: none;'>" . $lastmsgdatetime . "</div>"; //Latest message datetime on chat list
        echo "<div id='latestSeenMsg' style='display: none;'>" . $_SESSION['mylastmsg'] . "</div>"; //Latest message datetime user has seen

        mysql_close($con);

        //ob_end_flush;    
    //}
                      
?>
