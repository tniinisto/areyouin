<?php

    session_start();

    $ad=$_SESSION['myAdmin'];
    $registrar = $_SESSION['myRegistrar'];  

    if($ad==1 || $registrar==1 )
        //echo "<li id=\"linkadmin\"><a href=\"#\">Admin</a></li>";
        echo "<a href=\"#\">Admin</a>";
    //else
    //    echo "<li id=\"linkadmin\" style=\"display: none\"><a href=\"#\">Admin</a></li>";  


?>
