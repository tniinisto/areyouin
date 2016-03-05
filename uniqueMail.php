<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('uniqueMail.php, start');
    }

    $mail=$_GET["mail"];
    
    $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	
    if (!$con)
    {
	    die('Could not connect: ' . mysql_error());
	}

    mysql_select_db("areyouin", $con);    


    $sql = "SELECT count(mail) as mailcount from players WHERE mail like '" . $mail . "'";
	$result = mysql_query($sql);
    $row = mysql_fetch_array($result);


    if($_SESSION['ChromeLog']) { ChromePhp::log('mailcount: ' . $row['mailcount']); }
    
    echo $row['mailcount'];



    //try {
    //    //PDO means "PHP Data Objects"
    //    //dbh meand "Database handle"
    //    //STH means "Statement Handle"

    //	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

    //	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //	$stmt = $dbh->query($sql);  
    //	$mailcount = $stmt->fetchAll(PDO::FETCH_OBJ);

    //	$dbh = null;

    //    //if($_SESSION['ChromeLog']) { ChromePhp::log("mailcount: " . $mailcount[0]); }

    //	echo '{"items":'. json_encode($mailcount) .'}'; 
    //}
    //catch(PDOException $e) {
    //	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    //}

?>
