<?php

    session_start();
    
    date_default_timezone_set('UTC');

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

    //Get current users info
    $sql5 = "SELECT name, photourl FROM players WHERE playerID = " . $playerid . "";
	$result5 = mysql_query($sql5);
    $GLOBALS['MYPLAYER'] = mysql_fetch_array($result5);

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
        //$date->modify("-1 hour");
        $date->modify("+3 hour"); //Todo, timezones must be checked

        $sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES ('" . $_POST['comment_input'] . "','" . $playerid . "','" . $teamid . "','" . $date->format("Y-n-j H:i:s") . "')";
        //ChromePhp::log('Update: ' . $sql3);
        $result3 = mysql_query($sql3);

        //echo '<script type="text/javascript">';
        //    echo 'clearComment();';
        //echo '</script>';

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
            <!--<nav>
                <ul id="chat-nav" class="clearfix">
                    <li id="chat_link">
                        <a href="#"></a>
                    </li>
                </ul>
                </br>

            </nav>-->        

            <div id="chatdiv" class="scrollit" style="webkit-overflow-scrolling: touch;">
                <!--<p style="display:none;">Just to enable webkit-overflow-scrolling: touch</p>-->
                <table id="comments_table" class="atable" border="0" style="display: inline;">
                    <?php
                        
                    $limit=30;
                    $i=0;

                    while($row = mysql_fetch_array($GLOBALS['chatresult'])) {
                        if($i < $limit) {                        
                            $published = new DateTime($row['publishTime']);

                            echo "<tr class=\"chatrow\">";
                                echo "<td width=\"80px\" height=\"auto\" align=\"center\"><img class=\"seenchat\" src=\"images/" . $row['photourl'] . "\"><br><text class=\"chatname\" style=\"color: white;\">" . $row['name'] . "</text></td>";
                                echo "<td width=\"500px\" height=\"auto\"><text class=\"commentArea1\">" . $published->format("j.n.Y H:i") . "</text><textarea maxlength=\"500\" readonly class=\"commentArea2\" id=\"area" . $i ."\">" . $row['comment'] . "</textarea></td>";
                            echo "</tr>";

                            $i++;
                        }
                        else {
                            break;
                        }

                    }
                    ?>
                </table>
            </div>

            </br>

            <?php
                //$date = new DateTime();
                //$date->modify("-1 hour");
                //echo "<h4>PHP Comment: " .  $row['comment'] . " :: " . $date->format("Y-n-j H:i:s") . " </h4>";

                //echo "<form onsubmit=\"addRow('" . $GLOBALS['MYPLAYER']['photourl'] . "', '" . $GLOBALS['MYPLAYER']['name'] . "')\" id=\"chatform\" name=\"chatform\" method=\"post\" action=\"". $_SERVER[PHP_SELF] ."\" target=\"frame_chat\">";
                //echo "<form onsubmit=\"addRow('" . $GLOBALS['MYPLAYER']['photourl'] . "', '" . $GLOBALS['MYPLAYER']['name'] . "')\" id=\"chatform\" name=\"chatform\" method=\"post\" action=\"insertComment.php\" target=\"frame_chat\">";
                echo "<form onsubmit=\"addRow('" . $GLOBALS['MYPLAYER']['photourl'] . "', '" . $GLOBALS['MYPLAYER']['name'] . "')\" id=\"chatform\" name=\"chatform\" method=\"post\" target=\"frame_chat\">";
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


        <script type="text/javascript">
            //Clear chat input
            //function clearComment() {
            //    alert("jepa");
            //    //document.getElementById("comment_input").value = "";
            //}

            //$('#chatform').submit(function (e) {
            //    alert("come on...");
            //    e.preventDefault(); // don't submit multiple times
            //    this.submit(); // use the native submit method of the form element
            //    $('#comment_input').val(''); // blank the input
            //});
        </script>                
    </body>
</html>
