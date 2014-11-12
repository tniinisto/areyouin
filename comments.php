<?php

    session_start();
    
    date_default_timezone_set('UTC');

    //include 'ChromePhp.php';        
    //ChromePhp::log("starting chat...");

	$teamid=$_SESSION['myteamid'];

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
                    echo "<td width=\"80px\" height=\"auto\" align=\"center\"><img class=\"seenchat\" src=\"images/" . $row['photourl'] . "\">
                    <br><text class=\"chatname\" style=\"color: white;\">" . $row['name'] . "</text></td>";
                    echo "<td width=\"500px\" height=\"auto\"><text class=\"commentArea1\">" . $published->format("j.n.Y H:i") . "</text>
                    <textarea maxlength=\"500\" readonly class=\"commentArea2\" id=\"area" . $i ."\">" . $row['comment'] . "</textarea></td>";
                echo "</tr>";

                $i++;
            }
            else {
                break;
            }
        }

    echo "</table>";
 
    mysql_close($con);       
                  
?>
