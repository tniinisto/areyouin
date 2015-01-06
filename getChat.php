<?php
    session_start();
    date_default_timezone_set('UTC');
        
    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('getChat.php, start');
    }
        
    $teamid=$_SESSION['myteamid'];

    //if($_SESSION['ChromeLog']) {
    //    ChromePhp::log('timestamp: ', $_GET['timestamp']);
    //}

    $lastmodif = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
<<<<<<< HEAD
    //$lastmodif =  stripslashes($lastmodif);

    //$param = json_decode($lastmodif);
    //if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $param: ', $param->{'timestamp'}); }
    

=======
>>>>>>> parent of 3365c4f... finally, working in azure not local
    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $timestamp time: ', $_GET['timestamp']); }

    //$time = strtotime($lastmodif);
    //$lastmodif = date("m/d/y g:i A", $time);
    //if($lastmodif != 0) {
    //    $lastmodif = date("Y-m-d H:i:s",strtotime($lastmodif));
    //}
    
    //if($lastmodif != "") {
    //    $date = new DateTime($lastmodif);
    //if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif: ', $lastmodif); }
    //}
    //$date2 = 0;

    //if($lastmodif != "") {
    //    //$date2 = date_create($lastmodif);
    //    //$formatted = date_format($lastmodif, 'Y-m-d H:i:s');
    //    //if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif format: ', date_format($lastmodif, 'Y-m-d H:i:s')); }
    //    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif format: ', date_format($lastmodif, 'Y-m-d H:i:s')); }
    //}    

	$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	if (!$con)
	{
	    die('Could not connect: ' . mysql_error());
    }

	mysql_select_db("areyouin", $con);
    $sql = "select max(commentID) as commentID from comments where Team_teamID = " . $teamid . ";";
	$result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, sql commentID: ', $row['commentID']); }
    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif commentID: ', $lastmodif); }

    $currentmodif = $row['commentID'];
    $str_currentmodif = "\"" . $currentmodif . "\"";

    while($str_currentmodif == $lastmodif) {
        if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, while loop'); }
        usleep(30000);
        clearstatChcache();

        //mysql_free_result($result);
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $currentmodif = $row['commentID'];
        $str_currentmodif = "\"" . $currentmodif . "\"";
    }

    //while((date_format($currentmodif, 'Y-m-d H:i:s') <= date_format($lastmodif, 'Y-m-d H:i:s')) && $lastmodif != 0) {
    //if($lastmodif != 0) {
    //    $d1 = new DateTime($currentmodif);
    //    $d2 = new DateTime($lastmodif);        

    //    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, d1: ', $d1); }
    //    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, d2: ', $d2); }

    //    while($d1 <= $d2) {
    //        //if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, start to sleep... '); }
    //        usleep(30000);
    //        clearstatChcache();
    //        //if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, woke up... '); }

    //        //mysql_free_result($result);
    //        $result = mysql_query($sql);
    //        $row = mysql_fetch_array($result);
    //        $currentmodif = $row['time'];
    //        $d1 = new DateTime($currentmodif);
    //    }
    //}
    //else {
    //    mysql_free_result($result);
    //    $result = mysql_query($sql);
    //    $row = mysql_fetch_array($result);
    //    $currentmodif = $row['time'];        
    //}

    $response = array();
    //$response['msg'] = "test response...";
    $response['timestamp'] = $currentmodif;
    echo json_encode($response);

    mysql_close($con);

?>
