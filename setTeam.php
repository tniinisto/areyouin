<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('setTeam.php start');
    }

    // No cache
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");

    //Set team info to session from select POST, get teamid and teamname
    $teaminfo = explode("|", $_POST['teamselect']);
    $_SESSION['myteamid'] = $teaminfo[0];
    $_SESSION['myteamname'] = $teaminfo[1];


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {

        //Check & set team admin status for user////////////////////////////////////////////////////////////////
        $sql1 = "SELECT teamAdmin FROM playerteam, team where Team_teamID = :teamid AND Players_playerID = :player";

        if($_SESSION['ChromeLog']) { ChromePhp::log('set admin status: ' . $sql1); }
        
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':player', $_SESSION['myplayerid'], PDO::PARAM_INT);
        $stmt1->bindParam(':teamid', $_SESSION['myteamid'], PDO::PARAM_INT);
        $stmt1->execute();
        $row1 = $stmt1->fetch();

        //Set chosen teams admin status  to session
        $_SESSION['myAdmin'] = $row1['teamAdmin'];



        //Set rest of team specific session variables/////////////////////////////////////////////////////////////
        $sql2 = "SELECT teamID, teamName, timezone, utcOffset, registrar, lastMsg
                FROM playerteam m, team t
                WHERE m.Team_teamID = t.teamid AND teamID = :teamid;";


        if($_SESSION['ChromeLog']) { ChromePhp::log('set admin status: ' . $sql2); }
        
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->bindParam(':teamid', $_SESSION['myteamid'], PDO::PARAM_INT);
        $stmt2->execute();
        $row2 = $stmt2->fetch();        

        $_SESSION['myteamid'] = $row2['teamID'];
        $_SESSION['myteamname'] = $row2['teamName'];
        $_SESSION['myRegistrar'] = $row2['registrar'];
        $_SESSION['mytimezone'] = $row2['timezone'];
        $_SESSION['myoffset'] = $row2['utcOffset'];        

        header('Location:login_success.php');
    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
  
?>
