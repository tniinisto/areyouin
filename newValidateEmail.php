<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('newValidateEmail.php start');
    }


    //$mail=$_GET['mail'];

  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
    try {

        //Validate the email, does it already exist, is user already in the team. If already in another team, show name and ask if this should be insterted for the team

        $sql = "";

        if($_SESSION['ChromeLog']) { ChromePhp::log('newValidateEmail player: ' . $sql); }
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':playerID', $_GET['playerID'], PDO::PARAM_INT);
        $stmt->bindParam(':teamID', $_SESSION['myteamid'], PDO::PARAM_INT);
        $stmt->execute();

        $dbh = null;
    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>
