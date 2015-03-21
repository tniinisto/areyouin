<?php
    session_start();

    $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
            
    //$player_name=$_POST['player_name']; //Now this is the user name
    $player_email=$_POST['player_email'];
    $player_phone=$_POST['player_phone'];

    $sql = "UPDATE players SET mail = \"" . $player_email ."\", mobile = \"" . $player_phone . "\" WHERE playerID = " . $_SESSION['myplayerid'] . "";
    
    $result = mysql_query($sql);

    mysql_close($con);
?>


