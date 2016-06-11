<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();

    date_default_timezone_set($_SESSION['mytimezone']);
        
    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('getChat.php, start');
    }

    // How often to poll, in microseconds (1,000,000 μs equals 1 s)
    define('MESSAGE_POLL_MICROSECONDS', 15000000);

    // How long to keep the Long Poll open, in seconds
    define('MESSAGE_TIMEOUT_SECONDS', 60);

    // Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
    define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);
        
    $teamid=$_SESSION['myteamid'];
    $playerid = 0;

    // Close the session prematurely to avoid usleep() from locking other requests
    session_write_close();

    // Automatically die after timeout (plus buffer)
    set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);

    $lastmodif = isset($_GET['timestamp']) ? json_decode($_GET['timestamp']) : 0;
  
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	{
	    die('Could not connect: ' . mysql_error());
    }

	mysql_select_db("areyouin", $con);

    $sql = "select max(publishTime) as time from comments where Team_teamID = " . $teamid . ";";
	$result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $currentmodif = $row['time'];

    $sql1 = "select Players_playerID from comments where Team_teamID = " . $teamid . " and publishTime = '" . $currentmodif . "';";
	$result1 = mysql_query($sql1);
    $row1 = mysql_fetch_array($result1);
    $playerid = $row1['Players_playerID'];

    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, sql time: ', $row['time']); }
    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, lastmodif: ', $lastmodif); }
    
    $timeout = 1;

    $d1 = new DateTime($currentmodif);
    $d2 = new DateTime($lastmodif); 

    if($d1 <= $d2) {
        //$d1 = new DateTime($currentmodif);
        //$d2 = new DateTime($lastmodif);        

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
            $sql = "select max(publishTime) as time from comments where Team_teamID = " . $teamid . ";";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $currentmodif = $row['time'];
            $d1 = new DateTime($currentmodif);

            mysql_free_result($result1);
            $sql1 = "select Players_playerID from comments where Team_teamID = " . $teamid . " and publishTime = '" . $currentmodif . "';";
            $result1 = mysql_query($sql1);
            $row1 = mysql_fetch_array($result1);
            $playerid = $row1['Players_playerID'];

            if($d1 > $d2) {
                $timeout = 0;
            }

            // Decrement seconds from counter (the interval was set in μs, see above)
            $counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
        }
    }
    else {
        mysql_free_result($result);
        $sql = "select max(publishTime) as time from comments where Team_teamID = " . $teamid . ";";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
        $currentmodif = $row['time'];

        mysql_free_result($result1);
        $sql1 = "select Players_playerID from comments where Team_teamID = " . $teamid . " and publishTime = '" . $currentmodif . "';";
        $result1 = mysql_query($sql1);
        $row1 = mysql_fetch_array($result1);
        $playerid = $row1['Players_playerID'];

        $timeout = 0;     
    }

    $response = array();
    //$response['msg'] = "test response...";
    $response['timestamp'] = $currentmodif;
    $response['timeout'] = $timeout;
    $response['player'] = $playerid;

    echo json_encode($response);
   
    mysql_close($con);

?>
