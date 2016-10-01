<?php

    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    //Ajax url parameter
    $mail=$_GET["mail"];

    $sql = "select count(playerid) as count, firstname, lastname, name from players
            where mail = '" . $mail . "';";

    try {
        //PDO means "PHP Data Objects"
        //dbh meand "Database handle"
        //STH means "Statement Handle"

        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $dbh->query($sql);  
        $playerinfo = $stmt->fetchAll(PDO::FETCH_OBJ);


        $count = 0;
        foreach($playerinfo as $row) {
            $count = $row['count'];
        }
        echo "playercount is: " + $count;
        
        $dbh = null;
        //echo '{"items":'. json_encode($playerinfo) .'}'; 
    }
    catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }    

?>