<?php
    session_start();
    date_default_timezone_set('Europe/Helsinki');
        
    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('getChat.php, start');
    }

// How often to poll, in microseconds (1,000,000 μs equals 1 s)
define('MESSAGE_POLL_MICROSECONDS', 15000000);

// How long to keep the Long Poll open, in seconds
define('MESSAGE_TIMEOUT_SECONDS', 30);

// Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);
        
    $teamid=$_SESSION['myteamid'];

// Close the session prematurely to avoid usleep() from locking other requests
session_write_close();

// Automatically die after timeout (plus buffer)
set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);

    //if($_SESSION['ChromeLog']) {
    //    ChromePhp::log('timestamp: ', $_GET['timestamp']);
    //}

    $lastmodif = isset($_GET['timestamp']) ? json_decode($_GET['timestamp']) : 0;
    //$lastmodif = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
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
    $sql = "select max(publishTime) as time from comments where Team_teamID = " . $teamid . ";";
	$result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, sql time: ', $row['time']); }

    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, lastmodif: ', $lastmodif); }

    $currentmodif = $row['time'];
    
    $timeout = 1;

    //while((date_format($currentmodif, 'Y-m-d H:i:s') <= date_format($lastmodif, 'Y-m-d H:i:s')) && $lastmodif != 0) {
    if($lastmodif != NULL) {
        $d1 = new DateTime($currentmodif);
        $d2 = new DateTime($lastmodif);        

        if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, d1: ', $d1); }
        if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, d2: ', $d2); }

// Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
$counter = MESSAGE_TIMEOUT_SECONDS;

        while($d1 <= $d2 && $counter > 0) {
            //if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, start to sleep... '); }
                        
            //sleep(15);
usleep(MESSAGE_POLL_MICROSECONDS);
                        
            if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, woke up... '); }

            clearstatcache();
            mysql_free_result($result);
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $currentmodif = $row['time'];
            $d1 = new DateTime($currentmodif);

            if($d1 > $d2) {
                $timeout = 0;
            }

// Decrement seconds from counter (the interval was set in μs, see above)
$counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
        }
    }
    else {
        mysql_free_result($result);
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $currentmodif = $row['time'];
        $timeout = 0;     
    }

    $response = array();
    //$response['msg'] = "test response...";
    $response['timestamp'] = $currentmodif;
    $response['timeout'] = $timeout;

    echo json_encode($response);

    mysql_close($con);

?>
