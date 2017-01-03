<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('setTeam.php start');
    }

    // No cache
    // header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    // header("Cache-Control: no-cache");
    // header("Pragma: no-cache");

    //Set team info to session from select POST, get teamid and teamname
    $teaminfo = explode("|", $_POST['teamselect']);
    $_SESSION['myteamid'] = $teaminfo[0];
    $_SESSION['myteamname'] = $teaminfo[1];


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {

        //Set player & team specific session variables/////////////////////////////////////////////////////////////
        $sql2 = "SELECT teamAdmin, teamID, teamName, timezone, utcOffset, registrar, lastMsg, licenseValid
                FROM playerteam m, team t, registration r
                WHERE m.Team_teamID = t.teamid AND teamID = :teamid AND m.Players_playerID = :player AND r.team_teamid = t.teamid;";

        if($_SESSION['ChromeLog']) { ChromePhp::log('set admin status: ' . $sql2); }
        
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->bindParam(':teamid', $_SESSION['myteamid'], PDO::PARAM_INT);
        $stmt2->bindParam(':player', $_SESSION['myplayerid'], PDO::PARAM_INT);
        $stmt2->execute();
        $row2 = $stmt2->fetch();        

        $_SESSION['myAdmin'] = $row2['teamAdmin'];
        $_SESSION['myteamid'] = $row2['teamID'];
        $_SESSION['myteamname'] = $row2['teamName'];
        $_SESSION['myRegistrar'] = $row2['registrar'];
        $_SESSION['mytimezone'] = $row2['timezone'];
        $_SESSION['myoffset'] = $row2['utcOffset'];
        $_SESSION['mylicense'] = $row2['licenseValid'];

        //Check license status/////////////////////////////////
        
        //UTC//
        date_default_timezone_set("UTC");
    
        $licenseValid = new DateTime($_SESSION['mylicense']);
        //$licenseValid = $licenseValid->format('Ymd');
        $currentDate = new DateTime('now');
        //$currentDate = $currentDate->format('Ymd');

        if($currentDate->format('Ymd') > $licenseValid->format('Ymd')) {
            header('Location:../licenseExpired.php');    
            // echo "Now: " . $currentDate;
            // echo "License: " . $licenseValid;    
        }
        else
            header('Location:login_success.php');  

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  
    $dbh = null;
    
?>
