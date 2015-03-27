<?php
    session_start();

    $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
            
    //$player_name=$_POST['player_name']; //Currently this is the user name, do not allow editing
    $player_email=$_POST['player_email'];
    $player_phone=$_POST['player_phone'];
    $player_notify=$_POST['notifyswitch'];

    if($player_notify == 'on')
        $player_notify = 1;
    else    
        $player_notify = 0;

    $sql = "UPDATE players SET mail = '" . $player_email ."', mobile = '" . $player_phone . "', notify = '" . $player_notify . "' WHERE playerID = " . $_SESSION['myplayerid'] . ";";
    
    $result = mysql_query($sql);

    mysql_close($con);

    //header("location:player_profile.php");
?>
