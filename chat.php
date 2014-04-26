<?php

    session_start();
    
    include 'ChromePhp.php';        
    //ChromePhp::log("starting chat...");

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

    //$GLOBALS['row'] = 'initial';
    //$row = "initial";

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	    {
	    die('Could not connect: ' . mysql_error());
	    }

	mysql_select_db("areyouin", $con);


    if(isset($_POST['sendbutton'])) {
       sendComment($playerid, $teamid);
    } 
    else {
        getComments($teamid);
    }


    function getComments($p_teamid) {
                                
        //$sql = "SELECT * FROM comments WHERE team_teamID = " . $p_teamid . "";
        $sql = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = " . $p_teamid . "";
        
        
        //ChromePhp::log("sql: ", $sql);

        $GLOBALS['chatresult'] = mysql_query($sql);
        //$GLOBALS['row'] = mysql_fetch_array($result);
        //ChromePhp::log("select: ",  $GLOBALS['row']['comment']);
    }


    function sendComment($playerid, $teamid) {

        ChromePhp::log($_POST['comment']);
        
        $date = new DateTime();
        $date->modify("-1 hour");

        $sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES ('" . $_POST['comment'] . "','" . $playerid . "','" . $teamid . "','" . $date->format("Y-n-j H:i:s") . "')";
        ChromePhp::log('Update: ' . $sql3);
        $result3 = mysql_query($sql3);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>

        <article id="chat_content_article" class="clearfix">
        
            <!--<table border="1">
                <tbody>
                    <tr>
                        <td colspan="2">-->
                    <div class="scrollit">
                        <table border="0">
                            <?php
                            while($row = mysql_fetch_array($GLOBALS['chatresult'])) {
                                $published = new DateTime($row['publishTime']);

                                echo "<tr class=\"chatrow\">";
                                    //echo "<td width=\"150px\" height=\"50px\"><textarea class=\"commentArea1\"> Tupu &#10 24.4.2014 &#10 20:20 </textarea></td>";
                                    echo "<td width=\"80px\" align=\"center\"><img width=\"50\" height=\"50\"\" class=\"seen\" src=\"images/" . $row['photourl'] . "\"><text style=\"color: white;\">" . $row['name'] . "</text></td>";
                                    echo "<td width=\"500px\" height=\"60px\"><textarea class=\"commentArea1\">" . $published->format("j.n.Y H:i") . "</textarea><textarea class=\"commentArea2\">" . $row['comment'] . "</textarea></td>";
                                echo "</tr>";
                            }
                            ?>
                       </table>
                    </div>
                            <!--</td>
                    </tr>
                </tbody>
            </table>-->


            <?php
                //$date = new DateTime();
                //$date->modify("-1 hour");
                //echo "<h4>PHP Comment: " .  $row['comment'] . " :: " . $date->format("Y-n-j H:i:s") . " </h4>";
                
                echo "<form id=\"chatform\" name=\"chatform\" method=\"post\" action=\"". $_SERVER[PHP_SELF] ."\" target=\"frame_chat\">";
                    echo "<label for=\"comment\">Text: </label>";
			        echo "<input type=\"text\" id=\"login\" name=\"comment\" placeholder=\"\" required>";
                    echo "<input type=\"submit\" value=\"Send\" name=\"sendbutton\" id=\"sendbutton\">";
		        echo "</form>";
            ?>
                  
        </article>

        <iframe name="frame_chat" style="display: none;"></iframe>
        
    </body>
</html>
