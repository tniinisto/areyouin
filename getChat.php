<?php
    session_start();

    include 'ChromePhp.php';
	
    if($_SESSION['ChromeLog']) {
        ChromePhp::log('getChat.php, start');
    }
        
    $teamid=$_SESSION['myteamid'];

    //if($_SESSION['ChromeLog']) {
    //    ChromePhp::log('timestamp: ', $_GET['timestamp']);
    //}

    $lastmodif = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;

    if($_SESSION['ChromeLog']) {
        ChromePhp::log('$lastmodif: ', $lastmodif);
    }

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	{
	    die('Could not connect: ' . mysql_error());
    }

	mysql_select_db("areyouin", $con);
    $sql = "select max(publishTime) as time from comments where Team_teamID = " . $teamid . ";";
	$result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    if($_SESSION['ChromeLog']) {
        ChromePhp::log('after sql, time: ', $row['time']);
    }

    $currentmodif = $row['time'];

    while($currentmodif <= $lastmodif && $lastmodif != 0) {
        usleep(30000);
        clearstatcache();

        mysql_free_result($result);
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $currentmodif = $row['time'];
    }

    $response = array();
    $response['msg'] = "test response...";
    $response['timestamp'] = $currentmodif;
    echo json_encode($response);

    mysql_close($con);

?>
