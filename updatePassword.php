<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    session_start();

    
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");
            
    //$player_name=$_POST['player_name']; //Currently this is the user name, do not allow editing
    $player_password=$_POST['password1'];

    $sql = "UPDATE players SET password = '" . md5($player_password) . "' WHERE playerID = " . $_SESSION['myplayerid'] . ";";
    
    $result = mysql_query($sql);
    //echo $result;

    mysql_close($con);

?>



