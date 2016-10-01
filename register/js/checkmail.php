<?php

    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    //Ajax url parameter
    $mail=$_GET["mail"];

    //Sql connection
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
    {
        die('Could not connect here: ' . mysql_error());
    }
    mysql_select_db("areyouin", $con);

    $sql = "select count(playerid) as count, firstname, lastname, name
            from players
            where mail = '" . $mail . "';";
           
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    echo "Amount is: " + $row['count'];


    // try {
    //     //PDO means "PHP Data Objects"
    //     //dbh meand "Database handle"
    //     //STH means "Statement Handle"

    //     $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	

    //     $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     $stmt = $dbh->query($sql);  
    //     $playerinfo = $stmt->fetchAll(PDO::FETCH_OBJ);

    //     // foreach($playerinfo as $row) {
    //     //     echo "playercount is: " + $row['count'];
    //     // }

    //     echo "moro";

    //     $dbh = null;
    //     //echo '{"items":'. json_encode($playerinfo) .'}'; 
    // }
    // catch(PDOException $e) {
    //     echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    // }    

?>