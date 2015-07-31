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

    $sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES (\"" . mysql_real_escape_string($comment) . "\",'" . $playerid . "','" . $teamid . "','" . date("Y-n-j H:i:s") . "')";
        
    if($_SESSION['ChromeLog']) { ChromePhp::log('Insert comment: ' . $sql3); }
    
    $result3 = mysql_query($sql3);

    mysql_close($con);  
?>
