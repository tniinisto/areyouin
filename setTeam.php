<?php
        // No cache
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");

        session_start();

        $teamid=$_POST['teamselect'];
        $_SESSION['myteamid'] = $teamid;

        header('Location:login_success.php');
  
?>
