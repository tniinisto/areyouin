<?php      
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    
    session_start();
    
    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('insertComment.php, start');
    }
        
    $comment=$_GET["comment"];
    
    date_default_timezone_set($_SESSION['mytimezone']);

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

	
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	    {
	    die('Could not connect: ' . mysql_error());
	    }

	mysql_select_db("areyouin", $con);

    //Insert new comment to comments table
    $insertDate = date("Y-n-j H:i:s");
    $sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES (\"" . mysql_real_escape_string($comment) . "\",'" . $playerid . "','" . $teamid . "','" . $insertDate . "')";
        
    if($_SESSION['ChromeLog']) { ChromePhp::log('Insert comment: ' . $sql3); }
    
    $result3 = mysql_query($sql3);

    
    //Update the seen date for the commenter to playerteam table
    $sql = "UPDATE playerteam SET lastMsg = '" . $insertDate . "' WHERE Players_playerID = '" . $playerid . "' AND Team_teamID = '" . $teamid . "'";            
    $result = mysql_query($sql);


    mysql_close($con);  
?>
