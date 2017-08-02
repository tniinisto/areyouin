<?php      
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    
    session_start();
    
    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('insertComment.php, start');
    }
        
    $comment=$_GET["comment"];
    
    date_default_timezone_set($_SESSION['mytimezone']);

    $playerid=$_SESSION['myplayerid'];
	$teamid=$_SESSION['myteamid'];

	
    // $con = mysql_connect($dbhost, $dbuser, $dbpass);
	// if (!$con)
	//     {
	//     die('Could not connect: ' . mysql_error());
	//     }

	// mysql_select_db($dbname, $con);

    //PDO - UTF-8
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);	
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //Insert new comment to comments table
    $insertDate = date("Y-n-j H:i:s");
    //$sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES (\"" . mysql_real_escape_string($comment) . "\",'" . $playerid . "','" . $teamid . "','" . $insertDate . "')";

    $sql3 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES (:comment, :playerid, :teamID, ':insertDate')";
    $stmt3 = $dbh->prepare($sql3);
    $stmt3->bindParam(':comment', $comment, PDO::PARAM_STR);
    $stmt3->bindParam(':playerid', $playerid, PDO::PARAM_INT);
    $stmt3->bindParam(':teamID', $teamid, PDO::PARAM_INT);
    $stmt3->bindParam(':insertDate', $insertDate, PDO::PARAM_STR);
    $result3 = $stmt3->execute();

    if($_SESSION['ChromeLog']) { ChromePhp::log('Insert comment: ' . $sql3); }
    
    //Update the seen date for the commenter to playerteam table
    $sql = "UPDATE playerteam SET lastMsg = ':insertdate' WHERE Players_playerID = :playerid AND Team_teamID = :teamID";            
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':insertdate', $insertDate, PDO::PARAM_STR);
    $stmt->bindParam(':playerid', $playerid, PDO::PARAM_INT);
    $stmt->bindParam(':teamID', $teamid, PDO::PARAM_INT);
    $result = $stmt->execute();

?>
