<?php

    session_start();

    //$playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];
    //$ad=$_SESSION['myAdmin'];

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	  {
	  die('Could not connect: ' . mysql_error());
	  }

	mysql_select_db("areyouin", $con);

    $sql = "SELECT * FROM comments WHERE team_teamID = " . $teamid . "";

    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    //echo "PHP comment: " . $row['comment'] . ""; 
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
                <h4>Testing chat document...</h4>
                <!--<h4>Comment:</h4>-->
                <a id="chattest" href="#">Chat</a>

                <?php
                    echo "<h4>PHP Comment: " .  $row['comment'] . "</h4>";
                ?>

                <!--<div id="output"></div>-->
                  
            <!--</div>-->

        </article>
        
    </body>
</html>
