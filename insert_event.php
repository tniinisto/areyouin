<?php
        include 'mail_ayi.php';
        include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
        
        session_start();

        if($_SESSION['ChromeLog']) {
            require_once 'ChromePhp.php';
            ChromePhp::log('insert_event.php start');
        }

        
        $con = mysql_connect($dbhost, $dbuser, $dbpass);
        if (!$con)
          {
          die('Could not connect: ' . mysql_error());
          }

        mysql_select_db("areyouin", $con)or die("cannot select DB");

        $teamid=$_SESSION['myteamid'];
        
        //Post variables
        $playeramount=$_POST['playeramount'];
        $gamestart=$_POST['gamestart'];
        $gamesend=$_POST['gamesend'];
        $locationId=$_POST['location'];

        //SendGrid
        $mailId=$mail_user;
        $mailPass=$mail_key;

         //Event mail switch
        if($_POST['mailswitch'] == '') //OFF
            $mail_event = 0;
        else
            $mail_event = 1;

        //Private event switch
        if($_POST['privateswitch'] == '') //OFF
            $private_event = 0;
        else
            $private_event = 1;

        //Select all players switch
        $ooswitch_all = $_POST['ooswitch_all'];

        //Array containing [playerID, checkbox]
        $players = array(); 
        $idpost = '';
        $oopost = '';
        for ($i=1; $i<=$playeramount; $i++)
        {
                $players[$i] = array();
                $idpost = "playerid" . $i;
                $oopost = "ooswitch" . $i;
                
                $players[$i][1] = $_POST[$idpost];
                $players[$i][2] = $_POST[$oopost];

                $players[$i][1] = stripslashes($players[$i][1]);
                $players[$i][2] = stripslashes($players[$i][2]);
        }

        // To protect MySQL injection
        $playeramount = stripslashes($playeramount);
        $gamestart = stripslashes($gamestart);
        //echo $gamestart;
        $gamesend = stripslashes($gamesend);

        //Handle date format from 2013-05-29T01:01 -> 2013-07-27 17:30:00
        if(stripos($gamestart,"T") && strlen($gamestart) < 17)
        {
                $gamestart = str_replace("T", " ", $gamestart);
                $gamestart = $gamestart . ":00";
        }
        if(stripos($gamesend,"T") && strlen($gamesend) < 17)
        {
                $gamesend = str_replace("T", " ", $gamesend);
                $gamesend = $gamesend . ":00";
        }

        //iPhone has seconds -> 2013-07-27 17:30:00
        if(stripos($gamestart,"T") && strlen($gamestart) > 17)
        {
                $gamestart = str_replace("T", " ", $gamestart);
                //$gamestart = DateTime::createFromFormat('Y-m-d H:i:s',$gamestart)->format('Y-m-d H:i');        
                //$gamestart = $gamestart . ":00";
                //$gamesend = DateTime::createFromFormat('d.m.Y H.i',$gamesend)->format('Y-m-d H:i');        
                //$gamesend = $gamesend . ":00";
        }
        if(stripos($gamesend,"T") && strlen($gamesend) > 17)
        {
                $gamesend = str_replace("T", " ", $gamesend);
                //$gamestart = DateTime::createFromFormat('Y-m-d H:i:s',$gamestart)->format('Y-m-d H:i');        
                //$gamestart = $gamestart . ":00";
                //$gamesend = DateTime::createFromFormat('d.m.Y H.i',$gamesend)->format('Y-m-d H:i');        
                //$gamesend = $gamesend . ":00";
        }
        //echo $gamestart;
        
        //Insert event to events
        //$sql = "INSERT INTO events (Location_locationID, EventType_eventTypeID, startTime, endTime, Team_teamID) VALUES ('1', '1', '" . $gamestart. "', '" . $gamesend . "', '1')";
        //$sql = "INSERT INTO events (Location_locationID, EventType_eventTypeID, startTime, endTime, Team_teamID) VALUES (" . $locationId . ", '1', '" . $gamestart. "', '" . $gamesend . "', '1')";
        $sql = "INSERT INTO events (Location_locationID, EventType_eventTypeID, startTime, endTime, Team_teamID, private)
                VALUES (" . $locationId . ", '1', '" . $gamestart. "', '" . $gamesend . "', '" . $teamid . "', '" . $private_event . "')";
        
        //echo $sql;
        //echo "</br>";
        $result = mysql_query($sql);
        
        if(!$result)
        {
                $url = htmlspecialchars($_SERVER['HTTP_REFERER']);        
                echo "<a href='$url'><h1>Something went wrong, check the start&end dates format!</h1></a>";
                mysql_close($con);
                exit;
        }
        
        //Get the id for the inserted event
        //$sql2 = "SELECT MAX(eventID) as eventID FROM events";
        //echo $sql2;
        //echo "</br>";
        //$result2 = mysql_query($sql2);
        //$row = mysql_fetch_array($result2);        
        
        //Insert players which are selected into the event
        $eid = mysql_insert_id(); //Get the just created event id
        for ($k=1; $k<=$playeramount; $k++)
        {
            if($ooswitch_all == '') //If all players switch is on -> add all players to event
            {
                    $sql3 = "INSERT INTO eventplayer (Players_playerID, Events_eventID, areyouin) VALUES ('" . $players[$k][1] . "', '" .  $eid . "', '0');";
                    $result3 = mysql_query($sql3);                    
            }
            else
            {
                if($players[$k][2] == '') //Chech if single player is selected
                {
                    $sql3 = "INSERT INTO eventplayer (Players_playerID, Events_eventID, areyouin) VALUES ('" . $players[$k][1] . "', '" .  $eid . "', '0');";
                    $result3 = mysql_query($sql3);
                }
            }
        }
        
   
        
        //$sql3 = "INSERT INTO eventplayer (Players_playerID, Events_eventID, areyouin) VALUES ('" . $players[1][1] . "', '" . $row[eventID] . "', '0');";
        //echo $sql3;
        //echo "</br>";
        //$result3 = mysql_query($sql3);
        
        //echo $result;        
        /*echo "insert_event.php, playeamount: " . $playeramount . " start: " . $gamestart . " end: " . $gamesend;
        echo "</br>";*/
        
        /*for ($j=1; $j<=$playeramount; $j++)
        {
                echo "playerID: " . $players[$j][1] . " checkbox value: " . $players[$j][2] . "";
                echo "</br>";
        }*/
        
        //echo "<h1>Your game was inserted, click the browser back button...</h1>";

        //Success
        //$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
        //echo "<a href='$url'><h1>Your game was inserted succesfully, click here for R'YouIN!</h1></a>";
        //echo "</br>";
        
        //if(isset($_SERVER['HTTP_REFERER'])) {
                //$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
                //$url = htmlentities($_SERVER['HTTP_REFERER']);
                //echo $_SERVER['HTTP_REFERER'];
                //header("Location: " . $url);
                //header("Location:" . $url);
        //}
        //else
        //{
                //$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
                //echo "<a href='$url'><h1>Your game was inserted succesfully, click here for R'YouIN!</h1></a>";
                //echo "</br>";
        //}


        //Get players emails which to notify, depending on the notify field's value///////////////////////////////////////

        if($mailId != '' && $mailPass != '' && $mail_event==1) { //Check if mail credentials are set
                  
            $playerIdSqlList=''; //PlayerIDs for the sql where statement
            $loopFirst = 1;
            for ($m=1; $m<=$playeramount; $m++)
            {
                if($ooswitch_all == '') //If all players switch is on, add all
                {
                    if($m == 1)
                        $playerIdSqlList = $players[$m][1];
                    else
                        $playerIdSqlList = $playerIdSqlList . ', ' . $players[$m][1];
                }
                else
                {
                    if($players[$m][2] == '') //Check if single player is selected
                    {
                        if($loopFirst == 1) {
                            $loopFirst = 0;
                            $playerIdSqlList = $players[$m][1];
                        }
                        else
                            $playerIdSqlList = $playerIdSqlList . ', ' . $players[$m][1];
                    }
                }
            }

            //Get event info to sendMail function parameter
            $sql_eventInfo = "select * from areyouin.events
                        inner join areyouin.team on team.teamID = events.Team_teamID
                        inner join areyouin.location on location.locationID = events.Location_locationID
                        where events.eventID = " . $eid . ";";
            
            $r = mysql_query($sql_eventInfo);
            $eventInfo = mysql_fetch_array($r);

            //Start- and End-time formating
            $time = strtotime($eventInfo['startTime']);
            $starttime = date("d.m.Y H:i", $time);
            $time = strtotime($eventInfo['endTime']);
            $endtime = date("d.m.Y H:i", $time);

            $eventInfoArray = array(        
                'subject' => "New event for " . $eventInfo['teamName'] . "",                 
                'content' => "<html>             	
                                 <div style='background: black;'>
                                      <div style='display: inline-block; float: right; padding-right: 5px;'>
                                        <font style='color: white;  ' size='2' face='Trebuchet MS'>". date("d-m-Y") ."</font>
                                      </div>

                                      <img style='padding: 4px;' src='https://r-youin.com/images/r2.png' align='middle' alt='AreYouIN' height='42' width='42'>

                                      <div style='display: inline-block;'>  
                                        <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'>New event</font>
                                      </div>

                                      <br>
                                 </div>

                                <ul style='list-style-type:disc'>
                                <font size='3' face='Trebuchet MS'>                                       		
                                <li><span style='font-weight: bold;'>Team: </span>" . $eventInfo['teamName'] . "</li>
	                              <li><span style='font-weight: bold;'>Location: </span><a href='https://maps.google.fi/maps?q=
                                " . $eventInfo['position'] . "&hl=en&sll=" . $eventInfo['position'] . "&sspn=0.002108,0.004367&t=h&z=16' target='_blank'>" . $eventInfo['name'] . "</a></li>
                                   <li><span style='font-weight: bold;'>Starting:&nbsp</span><span style='color:blue'> " . $starttime . "    </span></li>
                                   <li><span style='font-weight: bold;'>Ending:&nbsp</span><span style='color:blue'> " . $endtime . "</span></li>
	                              </font>
                                </ul>                                

                                <div style='text-align: center; background: black; padding: 10px;'>
                                <font size='4' face='Trebuchet MS' style='color: white;'>			
                                    Roll in at <a href='https://r-youin.com/' style='color: white;'>R'YouIN</a>!
                                </font>
                                </div>

                            </html>",
            );
            
            //try {
            //    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	           // $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	           // $stmt = $dbh->query($sql_eventInfo);  
	           // $eventinfo = $stmt->fetchAll(PDO::FETCH_OBJ);
	           // $dbh = null;

            //    $teamName = '';
            //    foreach($eventinfo as $row) {
            //        $teamName = $row['teamName'];    
            //    }

            //    $eventInfoArray = array(        
            //        'subject' => "team: " . $teamName . "",
            //        //'subject' => "teST:",                                      
            //        'content' => "<html><p>Checkout the game from <a href='http://areyouin.azurewebsites.net/'>AreYouIN</a></p></html>",
            //    );
            //}
            //catch(PDOException $e) {
	           // echo '{"error":{"text":'. $e->getMessage() .'}}'; 
            //}    
            ///////////////////////////////////////////////////

            //Get emails where players notify setting is 1(true)
            $sql_mail = "SELECT mail, notify FROM players where playerID IN (" . $playerIdSqlList . ");";
            if($_SESSION['ChromeLog']) { ChromePhp::log('insert_event.php, $sql_mail: ', $sql_mail); }

            $result_mail = mysql_query($sql_mail);

            while($row_mail = mysql_fetch_array($result_mail)) {
                if($row_mail['notify'] == 1 && $row_mail['mail'] != '') { //If notity setting is true and player has email in profile
                    if($_SESSION['ChromeLog']) { ChromePhp::log('insert_event.php, sendMail() mail address: ', $row_mail['mail']); }            
                    sendMail($row_mail['mail'], $mailId, $mailPass, $eventInfoArray);   
                }
            }

        }
        //PlayerMails///////////////////////////////////////////////////////////////////////////////////////////

        mysql_close($con);

        if($result3)
        {
            header("location:index.html");    
        } 

?>
