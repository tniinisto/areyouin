<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();
    
    //date_default_timezone_set('Europe/Helsinki');

    //include 'ChromePhp.php';        
    //ChromePhp::log("starting chat...");

	$teamid=$_SESSION['myteamid'];

    ////Check session expiration & logged_in status
    //if(!isset($_SESSION['logged_in'])) {
    //    //ChromePhp::log("Session expired, \$_SESSION['logged_in']=", $_SESSION['logged_in']);
    //    ob_end_clean();
    //    header("location:default.html");
    //}
    //else if($_SESSION['logged_in'] == TRUE) {
	    
        $con = mysql_connect($dbhost, $dbuser, $dbpass);
	    if (!$con)
	        {
	        die('Could not connect: ' . mysql_error());
	        }

	    mysql_select_db("areyouin", $con);

        getComments($teamid);

        function getComments($p_teamid) {                                
            //$sql = "SELECT * FROM comments WHERE team_teamID = " . $p_teamid . "";
            $sql = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = " . $p_teamid . " order by c.publishTime desc";
        
            //ChromePhp::log("sql: ", $sql);

            $GLOBALS['commentsresult'] = mysql_query($sql);
            //$GLOBALS['row'] = mysql_fetch_array($result);
            //ChromePhp::log("select: ",  $GLOBALS['row']['comment']);
        }

        echo "<table id=\"comments_table\" class=\"atable\" border=\"0\">";
                    
            $limit=30;
            $i=0;

            while($row = mysql_fetch_array($GLOBALS['commentsresult'])) {
                if($i < $limit) {                        
                    $published = new DateTime($row['publishTime']);

                    //Save the newest comment's datetime to session
                    if($i == 0) {
                        $_SESSION['mylastmsg'] = $published;
                    }
                        
                    echo "<tr class=\"chatrow\">";

                        echo "<td valign=\"top\">";
                                  echo "<div>";
                                    echo "<div class='chat-list-left'>";
                                        echo "<img class='comment-image' src='images/" . $row['photourl'] . "'>";
                                        echo "<br />";
                                        echo "<div class='comment-name'>" . $row['name'] . "</div>";
                                    echo "</div>";
                                    echo "<br />";
                                    echo "<div class='chat-list-right'>";
                                        echo "<div class='comment-time'>" . $published->format("D j.n.Y H:i") . "</div>";         
                                        echo "<div class='comment-text'>" . $row['comment'] . "</div>";
                                    echo "</div>";
                                echo "</div>";
                        echo "</td>";

                    echo "</tr>";

                    //Marks the newest unformatted comment datetime to div
                    if($i == 0) {
                        echo "<div id='latestMsg'>" . $published . "</div>";
                    }

                    $i++;
                }
                else {
                    break;
                }
            }

        echo "</table>";
 
        mysql_close($con);

        //ob_end_flush;    
    //}
                      
?>
