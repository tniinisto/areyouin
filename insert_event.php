<?php
        include 'mail_ayi.php';

        session_start();

        if($_SESSION['ChromeLog']) {
            require_once 'ChromePhp.php';
            ChromePhp::log('insert_event.php start');
        }

        $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
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
        $sql = "INSERT INTO events (Location_locationID, EventType_eventTypeID, startTime, endTime, Team_teamID) VALUES (" . $locationId . ", '1', '" . $gamestart. "', '" . $gamesend . "', '" . $teamid . "')";
        
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

        //Get emails where players notify setting is 1(true)
        $sql_mail = "SELECT mail, notify FROM players where playerID IN (" . $playerIdSqlList . ");";
        if($_SESSION['ChromeLog']) { ChromePhp::log('insert_event.php, $sql_mail: ', $sql_mail); }
        //echo "alert(" . $sql_mail . ");";

        $result_mail = mysql_query($sql_mail);
        //$mailAddresses = "array("; //Mail addressses string array in comma separated list

        while($row_mail = mysql_fetch_array($result_mail)) {
            if($row_mail['notify'] == 1) {
                if($_SESSION['ChromeLog']) { ChromePhp::log('insert_event.php, sendMail() mail address: ', $row_mail['mail']); }            
                sendMail($row_mail['mail']);    
            }
        }
    
        //PlayerMails///////////////////////////////////////////////////////////////////////////////////////////

        mysql_close($con);

        if($result3)
        {
            header("location:index.html");    
        } 

?>
