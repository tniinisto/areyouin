<?php
    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('getChat.php, start');
    }
        
    $teamid=$_SESSION['myteamid'];

    //if($_SESSION['ChromeLog']) {
    //    ChromePhp::log('timestamp: ', $_GET['timestamp']);
    //}

    $lastmodif = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif: ', $lastmodif); }

    //$date2 = 0;

    if($lastmodif != "") {
        //$date2 = date_create($lastmodif);
        //$formatted = date_format($lastmodif, 'Y-m-d H:i:s');
        //if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif format: ', date_format($lastmodif, 'Y-m-d H:i:s')); }
        if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif format: ', date_format($lastmodif, 'Y-m-d H:i:s')); }
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

    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, sql time: ', $row['time']); }

    $currentmodif = $row['time'];
    
    //$currentmodif = date_format($currentmodif, 'Y-m-d H:i:s');
    //$date1 = new DateTime($currentmodif);

    //while((date_format($currentmodif, 'Y-m-d H:i:s') <= date_format($lastmodif, 'Y-m-d H:i:s')) && $lastmodif != 0) {
    while(($currentmodif <= $lastmodif) && $lastmodif != 0) {
        usleep(30000);
        clearstatChcache();

        mysql_free_result($result);
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $currentmodif = $row['time'];
    }

    $response = array();
    //$response['msg'] = "test response...";
    $response['timestamp'] = $currentmodif;
    echo json_encode($response);

    mysql_close($con);

?>
