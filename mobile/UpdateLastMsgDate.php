<?php      
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    
    session_start();
    
    date_default_timezone_set($_SESSION['mytimezone']);

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('UpdateLastMagDate.php, start');
    }

    $lastmsgdate=$_GET["datetime"];

    $playerid = $_SESSION['myplayerid'];
	$teamid = $_SESSION['myteamid'];
	
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	{
    	die('Could not connect: ' . mysql_error());
	}

	mysql_select_db($dbname, $con);

    $sql3 = "UPDATE playerteam SET lastMsg = '" . $lastmsgdate  . "' WHERE Players_playerID = '" . $playerid . "' AND Team_teamID = '" . $teamid . "'";
        
    if($_SESSION['ChromeLog']) { ChromePhp::log('Update comment date: ' . $sql3); }
    
    $result3 = mysql_query($sql3);

    mysql_close($con);

?>
