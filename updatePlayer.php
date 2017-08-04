<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('updatePlayer.php start');
    }

    // $con = mysql_connect($dbhost, $dbuser, $dbpass);
    // if (!$con)
    //     {
    //     die('Could not connect: ' . mysql_error());
    //     }

    // mysql_select_db($dbname, $con)or die("cannot select DB");

    $player_name=$_GET['player_name'];
    $player_email=$_GET['player_email'];
    $player_phone=$_GET['player_phone'];
    $player_notify=$_GET['notifyswitch'];
    $player_firstname=$_GET['player_firstname'];
    $player_lastname=$_GET['player_lastname'];

    
    if($player_notify == 'on')
        $player_notify = 1;
    else    
        $player_notify = 0;

    $playerid = $_SESSION['myplayerid'];

    //PDO - UTF-8
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);	
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Verify mail uniqueness, before update is allowed, check if it is already used by the user, so then it is ok.    
    // $sql2 = "SELECT playerID from players WHERE mail =  '" . mysql_real_escape_string($player_email) ."'";
    // $result2 = mysql_query($sql2);
    // $row2 = mysql_fetch_array($result2);
    // $num_rows = mysql_num_rows($result2);

        // PDO. utf-8 //////////////////////////////////////////////////        
        $sql1 = "SELECT playerID from players WHERE mail = :mail";
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':mail', $player_email, PDO::PARAM_STR);
        $result1 = $stmt1->execute();
        
        $num_rows = $stmt1->rowCount();
        $row2 = $stmt1->fetch(PDO::FETCH_ASSOC);


    //If mail already belongs to the user or is new one then it is ok to update information
    if($num_rows == 0 || $row2['playerID'] == $_SESSION['myplayerid']) {

if($player_notify == 1)        
    $sql2 = "UPDATE players SET mail = :mail, mobile = :phone, notify = '1', name = :name, firstname = :firstname, lastname = :lastname WHERE playerid = :playerid";
else
    $sql2 = "UPDATE players SET mail = :mail, mobile = :phone, notify = '0', name = :name, firstname = :firstname, lastname = :lastname WHERE playerid = :playerid";    

$stmt2 = $dbh->prepare($sql2);

$stmt2->bindParam(':mail', $player_email, PDO::PARAM_STR);
$stmt2->bindParam(':phone', $player_phone, PDO::PARAM_STR);
//$stmt2->bindParam(':pnotify', $player_notify, PDO::PARAM_INT);
$stmt2->bindParam(':name', $player_name, PDO::PARAM_STR);
$stmt2->bindParam(':firstname', $player_firstname, PDO::PARAM_STR);
$stmt2->bindParam(':lastname', $player_lastname, PDO::PARAM_STR);
$stmt2->bindParam(':playerid', $playerid, PDO::PARAM_INT);
$result2 = $stmt2->execute();


    //     if($_SESSION['ChromeLog']) { ChromePhp::log('Duplicate mail address, mysql_errno: ' . mysql_errno()); }
    //     //duplicate key, duplicate mail address
    //     // if( mysql_errno() == 1062) {
    //     //    // Duplicate key
    //     //    echo (mysql_affected_rows() > 0 ) ? 1 : "error: 1062, " . $player_email;
    //     // }

    }
    //Mail already exists in R'YouIN for another user, don't allow update!!!
    else if($row2['playerID'] != $_SESSION['myplayerid']) {
        echo "911, mail already in use!";
    }

    //mysql_close($con);
    $dbh = null;

?>
