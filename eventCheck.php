<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();
        
    date_default_timezone_set($_SESSION['mytimezone']);

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('eventCheck.php, start');
    }

    // How often to poll, in microseconds (1,000,000 μs equals 1 s)
    define('MESSAGE_POLL_MICROSECONDS', 15000000); //15 seconds

    // How long to keep the Long Poll open, in seconds
    define('MESSAGE_TIMEOUT_SECONDS', 60);

    // Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
    define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);
        
    $teamid=$_SESSION['myteamid'];
    $playerid==$_SESSION['myplayerid'];

    // Close the session prematurely to avoid usleep() from locking other requests
    session_write_close();

    // Automatically die after timeout (plus buffer)
    set_time_limit(MESSAGE_TIMEOUT_SECONDS+MESSAGE_TIMEOUT_SECONDS_BUFFER);

    $lastmodif = isset($_GET['timestamp']) ? json_decode($_GET['timestamp']) : 0;

    //if($lastmodif != "") {
    //    //$date2 = date_create($lastmodif);
    //    //$formatted = date_format($lastmodif, 'Y-m-d H:i:s');
    //    //if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif format: ', date_format($lastmodif, 'Y-m-d H:i:s')); }
    //    if($_SESSION['ChromeLog']) { ChromePhp::log('getChat.php, $lastmodif format: ', date_format($lastmodif, 'Y-m-d H:i:s')); }
    //}    

    $con = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$con)
	{
	    die('Could not connect: ' . mysql_error());
    }

	mysql_select_db("areyouin", $con);
    $sql = "select lastEventUpdate from playerteam where Team_teamID = " . $teamid . " and players_playerId = " . $playerid . " ;";
	$result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, sql time: ', $row['time']); }
    if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, lastmodif: ', $lastmodif); }

    $currentmodif = $row['lastEventUpdate'];
    
    $timeout = 1;
    $d1 = new DateTime($currentmodif);
    $d2 = new DateTime($lastmodif); 

    if($d1 <= $d2) {

        if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, d1: ', $d1); }
        if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, d2: ', $d2); }

        // Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
        $counter = MESSAGE_TIMEOUT_SECONDS;

        while($d1 <= $d2 && $counter > 0) {
                        
            //sleep
            usleep(MESSAGE_POLL_MICROSECONDS);
                        
            if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, woke up... '); }

            clearstatcache();

            mysql_free_result($result);
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $currentmodif = $row['lastEventUpdate'];
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

