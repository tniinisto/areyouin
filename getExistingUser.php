<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('getExistingUser.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
    try {

        //Get playerID for mail address////////////////////////////////////////////////////////////
        $sql = "SELECT playerID, firstname, lastname from players WHERE mail like :mail";
        
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);        
        $stmt->execute();

        $row = $stmt->fetch();
        $result = $row['playerID'] . "," . $row['firstname'] . "," . $row['lastname'];

        echo $result;

        $dbh = null;

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>


