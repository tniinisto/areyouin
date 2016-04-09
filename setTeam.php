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

    //Set team to session
    $teamid=$_POST['teamselect'];
    $_SESSION['myteamid'] = $teamid;

    //Check & set team admin status for user
    $sql1 = "SELECT teamAdmin FROM playerteam where Team_teamID = :teamid AND Players_playerID = :player";

    if($_SESSION['ChromeLog']) { ChromePhp::log('set admin status: ' . $sql1); }
        
    $stmt1 = $dbh->prepare($sql1);
    $stmt1->bindParam(':player', $_SESSION['myplayerid'], PDO::PARAM_INT);
    $stmt1->bindParam(':teamid', $_SESSION['myteamid'], PDO::PARAM_INT);
    $stmt1->execute();
    $row1 = $stmt1->fetch();

    //Set chosen teams admin status  to session
    $_SESSION['myAdmin'] = $row1['teamAdmin'];


    header('Location:login_success.php');
  
?>
