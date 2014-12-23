<?php
    session_start();
    
    date_default_timezone_set('UTC');

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
	    $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
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

                    echo "<tr class=\"chatrow\">";

                        echo "<td valign=\"top\"><img class=\"seenchat\" src=\"images/" . $row['photourl'] . "\">
                        <div class=\"chatname\">" . $row['name'] . "</div>
                        </td>";
                    
                        echo "<td height=\"auto\">
                        <div class=\"commentArea1\">" . $published->format("j.n.Y H:i") . "</div>
                        <div class=\"commentArea2\">" . $row['comment'] . "</div></td>";
                    
                    echo "</tr>";

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
