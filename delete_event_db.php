<?php
   include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
   include 'mail_ayi.php';

    //include 'ChromePhp.php';
    //ChromePhp::log('Hello console!');

    //Mail sending///////////////////////////////////////////////////////////////////////////////
    $con = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");    

    //SendGrid
    $mailId=$mail_user;
    $mailPass=$mail_key;

   //Get event info to sendMail function parameter
    $sql_eventInfo = "select * from areyouin.events
                inner join areyouin.team on team.teamID = events.Team_teamID
                inner join areyouin.location on location.locationID = events.Location_locationID
                where events.eventID = " . $_POST['delete_eventid'] . ";";
    
    $r = mysql_query($sql_eventInfo);
    $eventInfo = mysql_fetch_array($r);

    //Start- and End-time formating
    $time = strtotime($eventInfo['startTime']);
    $starttime = date("D j.n.Y H:i", $time);
    $time = strtotime($eventInfo['endTime']);
    $endtime = date("D j.n.Y H:i", $time);

    $eventInfoArray = array(        
        'subject' => "Event cancelled for " . $eventInfo['teamName'] . "",                 
        'content' => "<html>             	

                        <div style='background: black;'>
                            <img style='padding: 5px;' src='https://r-youin.com/images/r2.png' align='middle' alt='AreYouIN' height='42' width='42'>
                            <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> Event cancelled</font>
                        </div>

                        <br>

                        <ul style='list-style-type:disc'>
                        <font size='3' face='Trebuchet MS'>                                       		
                        <li><span style='font-weight: bold;'>Team: </span>" . $eventInfo['teamName'] . "</li>
                            <li><span style='font-weight: bold;'>Location: </span><a href='https://maps.google.fi/maps?q=
                            " . $eventInfo['position'] . "&hl=en&sll=" . $eventInfo['position'] . "&sspn=0.002108,0.004367&t=h&z=16' target='_blank'>" . $eventInfo['name'] . "</a></li>
                            <li><span style='font-weight: bold;'>Starting: </span><span style='color:blue'> " . $starttime . "    </span></li>
                            <li><span style='font-weight: bold;'>Ending: </span><span style='color:blue'> " . $endtime . "</span></li>
                            </font>
                        </ul>                                

                        <br>

                        <div style='text-align: center; background: black; padding: 15px;'>
                        <font size='4' face='Trebuchet MS' style='color: white;'>			
                            Check events at <a href='https://r-youin.com/' style='color: white;'>R'YouIN</a>!
                        </font>
                        </div>

                    </html>",
    );    


    //Get emails where players notify setting is 1(true)
    $sql_mail = "select mail, notify FROM eventplayer, players WHERE Events_eventID = " . $_POST['delete_eventid'] . " and Players_playerID = playerID;";

    $result_mail = mysql_query($sql_mail);

    while($row_mail = mysql_fetch_array($result_mail)) {
        if($row_mail['notify'] == 1 && $row_mail['mail'] != '') { //If notity setting is true and player has email in profile
                        
            sendMail($row_mail['mail'], $mailId, $mailPass, $eventInfoArray);

        }
    }

    mysql_close($con);


    //Delete event & players from db/////////////////////////////////////////////////////////////////////////
  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
    try {    
        $sql1 = "DELETE FROM eventplayer WHERE Events_eventID = :eventId";
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':eventId', $_POST['delete_eventid'], PDO::PARAM_INT);   
        $stmt1->execute();

        $sql2 = "DELETE FROM events WHERE eventID = :eventId";
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->bindParam(':eventId', $_POST['delete_eventid'], PDO::PARAM_INT);   
        $stmt2->execute();

        $dbh = null;
    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }

    header("location:index.html");    
    
?>


