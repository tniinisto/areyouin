<?php
        session_start();

        $teamid=$_POST['teamselect'];
        $_SESSION['myteamid'] = $teamid;

        header("location:login_success.php");
  
?>
