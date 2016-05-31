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


    //Verify mail uniqueness, before update is allowed, check if it is already used by the user, so then it is ok.    
    $sql2 = "SELECT playerID from players WHERE mail =  '" . mysql_real_escape_string($player_email) ."'";
    $result2 = mysql_query($sql2);
    $row2 = mysql_fetch_array($result2);
    $num_rows = mysql_num_rows($result2);

    //If mail already belongs to the user or is new one then it is ok to update information
    if($num_rows == 0 || $row2['playerID'] == $_SESSION['myplayerid']) {

        $sql = "UPDATE players SET mail = '" . mysql_real_escape_string($player_email) ."', mobile = '" .       mysql_real_escape_string($player_phone) . "', notify = '" . $player_notify . "',
                name = '" . mysql_real_escape_string($player_name) . "', firstname = '" . mysql_real_escape_string($player_firstname) . "', lastname = '" . mysql_real_escape_string($player_lastname) . "'
                WHERE playerID = " . $_SESSION['myplayerid'] . ";";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Update player: ' . $sql); }
        
        $result = mysql_query($sql);

        if($_SESSION['ChromeLog']) { ChromePhp::log('Duplicate mail address, mysql_errno: ' . mysql_errno()); }
    
        //duplicate key, duplicate mail address
        if( mysql_errno() == 1062) {
           // Duplicate key
           echo (mysql_affected_rows() > 0 ) ? 1 : "error: 1062, " . $player_email;
        }

    }
    else { //Mail already exists in R'YouIN for another user, don't allow update!!!
        echo "911, mail already in use!";
    }

    mysql_close($con);

?>
