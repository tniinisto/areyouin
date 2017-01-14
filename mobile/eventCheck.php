<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();
        
    date_default_timezone_set($_SESSION['mytimezone']);

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('eventCheck.php, start');
    }

    // How often to poll, in microseconds (1,000,000 μs equals 1 s)
    //define('MESSAGE_POLL_MICROSECONDS', 5000000); //5 seconds sleep in while loop
    define('MESSAGE_POLL_MICROSECONDS', 20000000); //20 seconds sleep in while loop
    
    // How long to keep the Long Poll open, in seconds
    define('MESSAGE_TIMEOUT_SECONDS', 30); //30s

    // Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
    define('MESSAGE_TIMEOUT_SECONDS_BUFFER', 5);
        
    $teamid=$_SESSION['myteamid'];
    $playerid=$_SESSION['myplayerid'];

    if($_SESSION['ChromeLog']) { ChromePhp::log('player: ', $playerid); }
    if($_SESSION['ChromeLog']) { ChromePhp::log('team: ', $teamid); }

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

	mysql_select_db($dbname, $con);
    //$sql = "select lastEventUpdate from playerteam where Team_teamID = " . $teamid . " and players_playerId = " . $playerid . " ;";
    $sql = "select players_playerid, max(lastEventUpdate) as last from playerteam where Team_teamID = " . $teamid . ";";
	$result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    $sql1 = "select players_playerid from playerteam where lastEventUpdate = '" . $row[last] . "';";
    $result1 = mysql_query($sql1);
    $row1 = mysql_fetch_array($result1);

    if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, sql time: ', $row['latest']); }
    if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, lastmodif: ', $lastmodif); }

    $currentmodif = $row['last'];
    $playeredited = $row1['players_playerid'];

    $timeout = 1;
    $db_time = new DateTime(trim($currentmodif)); //From database
    $param_time = new DateTime(trim($lastmodif)); //From parameter

    if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, db_time: ', $db_time); }
    if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, param_time: ', $param_time); }

    // Counter to manually keep track of time elapsed (PHP's set_time_limit() is unrealiable while sleeping)
    $counter = MESSAGE_TIMEOUT_SECONDS;

    while($db_time <= $param_time && $counter > 0) {
                        
            //sleep
            usleep(MESSAGE_POLL_MICROSECONDS);
                        
            if($_SESSION['ChromeLog']) { ChromePhp::log('eventCheck.php, woke up... '); }

            clearstatcache();

            mysql_free_result($result);
            $sql = "select players_playerid, max(lastEventUpdate) as last from playerteam where Team_teamID = " . $teamid . ";";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
            $currentmodif = $row['last'];
            $db_time = new DateTime(trim($currentmodif));

            mysql_free_result($result1);
            $sql1 = "select players_playerid from playerteam where lastEventUpdate = '" . $row[last] . "';";
            $result1 = mysql_query($sql1);
            $row1 = mysql_fetch_array($result1);
            $playeredited = $row1['players_playerid'];

            $db_time = new DateTime(trim($currentmodif));
            $param_time = new DateTime(trim($lastmodif));
            if($db_time > $param_time) {
                $timeout = 0;
            }

            //Decrement seconds from counter (the interval was set in μs, see above)
            $counter -= MESSAGE_POLL_MICROSECONDS / 1000000;
    }

    if($counter > 0) {
        $timeout = 0;
    }

    $response = array();
    $response['timestamp'] = $currentmodif;
    $response['timeout'] = $timeout;
    $response['playerid'] = $playeredited;
    echo json_encode($response);

    mysql_close($con);

?>
