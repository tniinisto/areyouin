<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('updatePlayer.php start');
    }
    
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
            
    $player_name=$_POST['player_name'];
    $player_email=$_POST['player_email'];
    $player_phone=$_POST['player_phone'];
    $player_notify=$_POST['notifyswitch'];
    $player_firstname=$_POST['player_firstname'];
    $player_lastname=$_POST['player_lastname'];

    if($player_notify == 'on')
        $player_notify = 1;
    else    
        $player_notify = 0;

    $sql = "UPDATE players SET mail = '" . $player_email ."', mobile = '" . $player_phone . "', notify = '" . $player_notify . "',
            name = '" . $player_name . "', firstname = '" . $player_firstname . "', lastname = '" . $player_lastname . "'
            WHERE playerID = " . $_SESSION['myplayerid'] . ";";


    if($_SESSION['ChromeLog']) { ChromePhp::log('Update player: ' . $sql); }
        
    $result = mysql_query($sql);

    if($_SESSION['ChromeLog']) { ChromePhp::log('Duplicate mail address, mysql_errno: ' . mysql_errno()); }
    
    //duplicate key, duplicate mail address
    if( mysql_errno() == 1062) {
       // Duplicate key
       echo (mysql_affected_rows() > 0 ) ? "Update success" : "Update failed, duplicate mail: " . $player_email;
    }


    mysql_close($con);

?>
