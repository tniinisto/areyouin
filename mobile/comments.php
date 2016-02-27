<?php
    session_start();
    
    date_default_timezone_set('Europe/Helsinki');

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('comments.php, start');
    }

	$teamid=$_SESSION['myteamid'];

    ////Check session expiration & logged_in status
    //if(!isset($_SESSION['logged_in'])) {
    //    //ChromePhp::log("Session expired, \$_SESSION['logged_in']=", $_SESSION['logged_in']);
    //    ob_end_clean();
    //    header("location:default.html");
    //}
    //else if($_SESSION['logged_in'] == TRUE) {
	    $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	    if (!$con)
	        {
	        die('Could not connect: ' . mysql_error());
	        }

	    mysql_select_db("areyouin", $con);

        //Get current users lastseen datetime & update to session
        $sql5 = "SELECT pt.lastMsg as lastMsg FROM players, playerteam pt WHERE playerID = " . $playerid . " AND pt.Team_teamID = " . $teamid . " AND playerID = pt.Players_playerID";
	    $result5 = mysql_query($sql5);
        $row5 = mysql_fetch_array($result5);
        $_SESSION['mylastmsg'] = $row5['lastMsg'];

    if($_SESSION['ChromeLog']) { ChromePhp::log('Latest seen msg time: ' . $row5['lastMsg']); }

        getComments($teamid);

        function getComments($p_teamid) {                                
            //$sql = "SELECT * FROM comments WHERE team_teamID = " . $p_teamid . "";
            $sql = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = " . $p_teamid . " order by c.publishTime desc";
        
            //ChromePhp::log("sql: ", $sql);

            $GLOBALS['commentsresult'] = mysql_query($sql);
            //$GLOBALS['row'] = mysql_fetch_array($result);
            //ChromePhp::log("select: ",  $GLOBALS['row']['comment']);
        }

        $lastmsgdatetime;

        echo "<table id=\"comments_table\" class=\"atable\" border=\"0\">";
                    
            $limit=30;
            $i=0;

            while($row = mysql_fetch_array($GLOBALS['commentsresult'])) {
                if($i < $limit) {                        
                    $published = new DateTime($row['publishTime']);

                    //Save the newest comment's datetime to session
                    if($i == 0) {
                        $lastmsgdatetime = $row['publishTime'];
                    }

                    echo "<tr class=\"chatrow\">";

                        echo "<td valign=\"top\">";
                                  echo "<div>";
                                    echo "<div class='chat-list-left'>";
                                        echo "<img width='50' height='50' src='http://areyouin.azurewebsites.net/images/" . $row['photourl'] . "'>";
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
