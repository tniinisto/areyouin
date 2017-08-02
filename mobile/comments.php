<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();
    
    date_default_timezone_set($_SESSION['mytimezone']);

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('comments.php, start');
    }

	$teamid=$_SESSION['myteamid'];
    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

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

        //getComments($teamid);

        // function getComments($p_teamid) {                                
            //$sql = "SELECT * FROM comments WHERE team_teamID = " . $p_teamid . "";
            //$sql = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = " . $p_teamid . " order by c.publishTime desc";
                    
            //PDO//////////////////////////////////////////////////////////////////////////////
            $sql2 = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = :teamid order by c.publishTime desc";
            $stmt2 = $dbh->prepare($sql2);
            $stmt2->bindParam(':teamid', $teamid, PDO::PARAM_INT);
            
            $result2 = $stmt2->execute();
   
            // $row2;
            // while($row2 = $stmt2->fetch()) {
            //     $GLOBALS['chatresult'] += $row2;
            // }

            //$GLOBALS['commentsresult'] = mysql_query($sql);
            //$GLOBALS['row'] = mysql_fetch_array($result);
            //ChromePhp::log("select: ",  $GLOBALS['row']['comment']);
        // }

        $lastmsgdatetime;

        echo "<table id=\"comments_table\" class=\"atable\" border=\"0\">";
                    
            $limit=30;
            $i=0;

           while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                if($i < $limit) {                        
                    $published = new DateTime($row['publishTime']);

                    //Save the newest comment's datetime to session
                    if($i == 0) {
                        $lastmsgdatetime = $row['publishTime'];
                        $_SESSION['mylastmsg'] = $row1['lastMsg'];
                    }

                    echo "<tr class=\"chatrow\">";

                        echo "<td valign=\"top\">";
                                  echo "<div>";
                                    echo "<div class='chat-list-left'>";
                                        echo "<img width='50' height='50' src='https://areyouin.azurewebsites.net/images/" . $row['photourl'] . "'>";
                                        echo "<br />";
                                        echo "<div class='comment-name'>" . $row['name'] . "</div>";
                                    echo "</div>";
                                    echo "<br />";
                                    echo "<div class='chat-list-right'>";
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
        echo "<div id='latestSeenMsg' style='display: none;'>" . $_SESSION['mylastmsg'] .  "</div>"; //Latest message datetime user has seen

        mysql_close($con);

        //ob_end_flush;    
    //}
                      
?>
