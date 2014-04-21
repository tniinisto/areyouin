<?php

    session_start();
    
    include 'ChromePhp.php';        
    ChromePhp::log("starting chat...");

    //$playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

    $GLOBALS['row'] = 'initial';
    //$row = "initial";

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	    {
	    die('Could not connect: ' . mysql_error());
	    }

	mysql_select_db("areyouin", $con);


    if(isset($_POST['sendbutton'])) {
       sendComment();
    } 
    else {
        getComments($teamid);

        //$sql = "SELECT * FROM comments WHERE team_teamID = " . $teamid . "";

        //$result = mysql_query($sql);
        //$row = mysql_fetch_array($result);
    }


    function getComments($p_teamid) {
                                
        $sql = "SELECT * FROM comments WHERE team_teamID = " . $p_teamid . "";
        ChromePhp::log("sql: ", $sql);

        $result = mysql_query($sql);
        $GLOBALS['row'] = mysql_fetch_array($result);
        ChromePhp::log("select: ",  $GLOBALS['row']['comment']);
    }


    function sendComment() {

        ChromePhp::log($_POST['comment']);

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
            
            <!--<div id="chat_content_id">-->        
                <!--<h4>Testing chat document...</h4>
                <h4>Comment:</h4>
                <a id="chattest" href="#">Chat</a>-->
            <!--</div>-->

            <?php
                echo "<h4>PHP Comment: " .  $row['comment'] . " - " . Date("j.n.Y H:i:s") . " </h4>";
                
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
