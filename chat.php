<?php

    session_start();
    
    include 'ChromePhp.php';        
    //ChromePhp::log("starting chat...");

    $playerid=$_SESSION['myplayerid'];
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
       sendComment($playerid, $teamid);
    } 
    else {
        getComments($teamid);
    }


    function getComments($p_teamid) {
                                
        $sql = "SELECT * FROM comments WHERE team_teamID = " . $p_teamid . "";
        //ChromePhp::log("sql: ", $sql);

        $result = mysql_query($sql);
        $GLOBALS['row'] = mysql_fetch_array($result);
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
            
            <!--<div id="chat_content_id">-->        
                <!--<h4>Testing chat document...</h4>
                <h4>Comment:</h4>
                <a id="chattest" href="#">Chat</a>-->
            <!--</div>-->

<table border="1">
    <thead>
        <tr>
            <th width="200">Month</th>
            <th>Savings</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>Sum</td>
            <td>$180</td>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td colspan="2">
        <div class="scrollit">
            <table border="1">
                <tr>
                    <td width="500" height="50"><textarea class="commentArea">Comment...</textarea></td>
                    <td width="200">$100</td>
                </tr>
                <tr>
                    <td>February</td>
                    <td>$80</td>
                </tr>
                <tr>
                    <td>January</td>
                    <td>$100</td>
                </tr>
                <tr>
                    <td>February</td>
                    <td>$80</td>
                </tr>
                <tr>
                    <td>January</td>
                    <td>$100</td>
                </tr>
            </table>
        </div>
                </td>
        </tr>
    </tbody>
</table>

            <?php
                $date = new DateTime();
                $date->modify("-1 hour");
                echo "<h4>PHP Comment: " .  $row['comment'] . " :: " . $date->format("Y-n-j H:i:s") . " </h4>";
                
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
