<?php
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );  
    
        /*include 'events_sse.php';
        //Call the SSE function
        sendMsg();*/

        $eventplayerid=$_GET["event"];
        $areyouin=$_GET["ayi"];        

        //$con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
        $con = mysql_connect($dbhost, $dbuser, $dbpass);
        if (!$con)
          {
          die('Could not connect: ' . mysql_error());
          }

        mysql_select_db($dbname, $con);

        //$sql="SELECT * FROM players WHERE playerID = '".$q."'";
        $sql= "UPDATE eventplayer SET areyouin = '" . $areyouin . "' WHERE EventPlayerID = '".$eventplayerid."'";
        
        //echo $sql;

        $result = mysql_query($sql);
        
        echo $result;
        
        mysql_close($con);

?>