<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('uniqueMail.php, start');
    }
        

    $mail=$_GET["mail"];

    $sql = "SELECT count(mail) as mailcount from players WHERE mail like '" . $mail . "'";
	$result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    echo "Count: " . $row['mailcount'];


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
