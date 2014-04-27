<?php

    session_start();
    
    //include 'ChromePhp.php';        
    //ChromePhp::log("starting chat...");

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

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
        $sql = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = " . $p_teamid . " order by c.publishTime desc";
        
        //ChromePhp::log("sql: ", $sql);

        $GLOBALS['chatresult'] = mysql_query($sql);
        //$GLOBALS['row'] = mysql_fetch_array($result);
        //ChromePhp::log("select: ",  $GLOBALS['row']['comment']);
    }


    function sendComment($playerid, $teamid) {
        //ChromePhp::log($_POST['comment_input']);
        
        $date = new DateTime();
        $date->modify("-1 hour");

        $sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES ('" . $_POST['comment_input'] . "','" . $playerid . "','" . $teamid . "','" . $date->format("Y-n-j H:i:s") . "')";
        //ChromePhp::log('Update: ' . $sql3);
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
            <nav>

                <ul id="chat-nav" class="clearfix">
                    <li id="chat_link">
                        <a href="#"></a>
                    </li>
                </ul>
                </br>

            </nav>        

            <div class="scrollit">
                <table id="comments_table" border="0" width="100%">
                    <?php
                    while($row = mysql_fetch_array($GLOBALS['chatresult'])) {
                        $published = new DateTime($row['publishTime']);

                        echo "<tr class=\"chatrow\">";
                            //echo "<td width=\"150px\" height=\"50px\"><textarea class=\"commentArea1\"> Tupu &#10 24.4.2014 &#10 20:20 </textarea></td>";
                            echo "<td width=\"80px\" align=\"center\"><img width=\"50\" height=\"50\"\" class=\"seen\" src=\"images/" . $row['photourl'] . "\"><br><text style=\"color: white;\">" . $row['name'] . "</text></td>";
                            echo "<td width=\"500px\" height=\"60px\"><textarea class=\"commentArea1\">" . $published->format("j.n.Y H:i") . "</textarea><textarea class=\"commentArea2\">" . $row['comment'] . "</textarea></td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>

            </br>

            <?php
                //$date = new DateTime();
                //$date->modify("-1 hour");
                //echo "<h4>PHP Comment: " .  $row['comment'] . " :: " . $date->format("Y-n-j H:i:s") . " </h4>";
                
                echo "<form onsubmit=\"addRow()\" id=\"chatform\" name=\"chatform\" method=\"post\" action=\"". $_SERVER[PHP_SELF] ."\" target=\"frame_chat\">";
                    echo "<label for=\"comment_input\">Comment: </label>";
                    echo "</br>";
			        //echo "<input type=\"text\" id=\"comment_input\" name=\"comment_input\" placeholder=\"\" required>";
                    echo "<textarea maxlength=\"500\" id=\"comment_input\" name=\"comment_input\" placeholder=\"\" required></textarea>";
                    echo "</br>";
                    echo "<input type=\"submit\" value=\"Send\" name=\"sendbutton\" id=\"sendbutton\">";
		        echo "</form>";
            ?>
                  
        </article>

        <iframe name="frame_chat" style="display: none;"></iframe>
                
    </body>
</html>
