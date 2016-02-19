<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();

    //date_default_timezone_set('UTC');
    
    //include 'ChromePhp.php';        
    //ChromePhp::log("starting chat...");

    $playerid=$_SESSION['myplayerid'];
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

        //Get current users info
        $sql5 = "SELECT name, photourl, pt.lastMsg as lastMsg FROM players, playerteam pt WHERE playerID = " . $playerid . " AND pt.Team_teamID = " . $teamid . " AND playerID = pt.Players_playerID";
	    $result5 = mysql_query($sql5);
        $GLOBALS['MYPLAYER'] = mysql_fetch_array($result5);


        getComments($teamid);

        function getComments($p_teamid) {                                
            //$sql = "SELECT * FROM comments WHERE team_teamID = " . $p_teamid . "";
            $sql = "SELECT c.*, p.photourl, p.name FROM comments c LEFT JOIN players p ON c.Players_playerID = p.playerID WHERE c.team_teamID = " . $p_teamid . " order by c.publishTime desc";
        
            //ChromePhp::log("sql: ", $sql);

            $GLOBALS['chatresult'] = mysql_query($sql);
            //$GLOBALS['row'] = mysql_fetch_array($result);
            //ChromePhp::log("select: ",  $GLOBALS['row']['comment']);
        }


        //function sendComment($playerid, $teamid) {
        //    //ChromePhp::log($_POST['comment_input']);
        //    
        //    $date = new DateTime();
        //    //$date->modify("-1 hour");
        //    $date->modify("+3 hour"); //Todo, timezones must be checked

        //    $sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES ('" . $_POST['comment_input'] . "','" . $playerid . "','" . $teamid . "','" . $date->format("Y-n-j H:i:s") . "')";
        //    //ChromePhp::log('Update: ' . $sql3);
        //    $result3 = mysql_query($sql3);

        //    //echo '<script type="text/javascript">';
        //    //    echo 'clearComment();';
        //    //echo '</script>';
        //}

        echo "<article id=\"chat_content_article\" class=\"clearfix\">";

            //echo "<a href=\"#openModal\" data-rel=\"dialog\" data-transition=\"pop\">Open Modal</a>";

            //echo "<div id=\"openModal\" class=\"modalDialog\">";
	           // echo "<div>";
		          //  echo "<a href=\"#close\" title=\"Close\" class=\"close\">X</a>";
		          //  
            //        echo "<h2>Modal Box</h2>";
		          //  echo "<p>This is a sample modal box that can be created using the powers of CSS3.</p>";
		          //  echo "<p>You could do a lot of things here like have a pop-up ad that shows when your website loads, or create a login/register form for users.</p>";

            //        //echo "<form onsubmit=\"addRow('" . $GLOBALS['MYPLAYER']['photourl'] . "', '" . $GLOBALS['MYPLAYER']['name'] . "')\" id=\"chatform\" name=\"chatform\" method=\"put\" target=\"frame_chat\">";
            //     //       //echo "<label for=\"comment_input\">Comment: </label>";
            //     //       //echo "</br>";
		          //     // //echo "<input type=\"text\" id=\"comment_input\" name=\"comment_input\" placeholder=\"\" required>";
            //     //       echo "<textarea maxlength=\"500\" id=\"comment_input\" name=\"comment_input\" placeholder=\"\" required></textarea>";
            //     //       echo "</br>";
            //     //       echo "<input type=\"submit\" value=\"Send\" name=\"sendbutton\" id=\"sendbutton\"  class=\"button\">";
	           //     //echo "</form>";
	           // echo "</div>";
            //echo "</div>";

            //echo "<div id=\"dialog\" title=\"Basic dialog\">";
            //echo "<p>This is the default dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>";
            //echo "</div>";

            //echo "<a href=\"modal.html\" data-role=\"button\" data-inline=\"true\" data-rel=\"dialog\" data-transition=\"pop\" data-theme=\"b\">Open dialog</a>";
            //echo "<a href=\"modal.html\" data-role=\"button\" data-inline=\"true\" data-rel=\"dialog\" data-transition=\"pop\" data-theme=\"b\">Open dialog</a>";

            //<a class="ui-btn ui-btn-inline ui-btn-corner-all ui-shadow ui-btn-up-c" data-transition="pop" data-rel="dialog" data-inline="true" data-role="button" href="dialog.html" data-theme="c">
            
            //echo "<div id='newChatImg' style='width:100%;'><img id='newChatIcon' width='40' height='40' src='images/edit.png' style='cursor: pointer; display: block; margin-left: auto; margin-right: auto;'></div>";
            echo "<img id='newChatImg' width='40' height='40' src='images/edit.png'>";

            echo "<table class='atable_summary'>";
				echo "<tr>";
                        echo "<th> </th>";
				echo "</tr>";
			echo "</table>";  

            echo "<div id='chat_new_id' class='chat_new'>";
                
                echo "<form id='chatsubmitform' onsubmit=\"addRow('" . $GLOBALS['MYPLAYER']['photourl'] . "', '" . $GLOBALS['MYPLAYER']['name'] . "')\" id=\"chatform\" name=\"chatform\" method=\"post\" target=\"frame_chat\">";
                    echo "<label for=\"comment_input\"><b>New comment</b></label>";
                    echo "</br>";
			        //echo "<input type=\"text\" id=\"comment_input\" name=\"comment_input\" placeholder=\"\" required>";
                    echo "<textarea maxlength=\"500\" id=\"comment_input\" name=\"comment_input\" placeholder=\"\" required></textarea>";
                    echo "</br>";
                    echo "<input class=\"myButton\" type=\"submit\" value=\"Send\" name=\"sendbutton\" id=\"sendbutton\"  class=\"button\">";
                echo "</form>";
            
            echo "</div>";

            //echo "</br>";

            $lastmsgdatetime = '0';

            //echo "<p><b>Comments</b></p>";
            echo "<div id=\"chatdiv\" class=\"scrollit\">";
                
                echo "<table id=\"comments_table\" class=\"atable\" border=\"0\">";
                    
                        $limit=30;
                        $i=0;

                        while($row = mysql_fetch_array($GLOBALS['chatresult'])) {
                            if($i < $limit) {                        
                                $published = new DateTime($row['publishTime']);

                                //Save the newest chat comment's datetime and update the last seen message to session
                                if($i == 0) {
                                    $lastmsgdatetime = $row['publishTime'];                                    
                                    $_SESSION['mylastmsg'] = $GLOBALS['MYPLAYER']['lastMsg'];
                                }

                                //echo "<tr class=\"chatrow\">";
                                //    echo "<td width=\"80px\" height=\"auto\" align=\"center\"><img class=\"seenchat\" src=\"images/" . $row['photourl'] . "\"><br><text class=\"chatname\" style=\"color: white;\">" . $row['name'] . "</text></td>";
                                //    echo "<td width=\"500px\" height=\"auto\"><text class=\"commentArea1\">" . $published->format("j.n.Y H:i") . "</text><textarea maxlength=\"500\" readonly class=\"commentArea2\" id=\"area" . $i ."\">" . $row['comment'] . "</textarea></td>";
                                //echo "</tr>";

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
                    

                echo "</div>";


    echo "</article>";

    echo "<iframe name=\"frame_chat\" style=\"display: none;\"></iframe>";

        //echo "</article>";

        //echo "<iframe name=\"frame_chat\" style=\"display: none;\"></iframe>";
 
        mysql_close($con);
        
        //ob_end_flush;
            
    //}
                      
?>
