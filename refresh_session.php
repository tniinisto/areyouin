<?php
  session_start();

  if (isset($_SESSION['ayiloginName'])) { //if you have more session-vars that are needed for login, also check if they are set and refresh them as well
    $_SESSION['myusername'] = $_SESSION['myusername'];
    $_SESSION['mypassword'] = $_SESSION['mypassword'];
    $_SESSION['myplayerid'] = $_SESSION['myplayerid'];
    $_SESSION['myteamid'] = $_SESSION['myteamid'];
    $_SESSION['myAdmin'] = $_SESSION['myAdmin'];
  }

?>
