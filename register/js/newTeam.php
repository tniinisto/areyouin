<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    include( $_SERVER['DOCUMENT_ROOT'] . '/mail_ayi.php' );

    session_start();

    //if($_SESSION['ChromeLog']) {
    //    require_once  $_SERVER['DOCUMENT_ROOT'] . 'ChromePhp.php';
    //    ChromePhp::log('newTeam.php start');
    //}


  	//$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);		
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    $password = '';
          
    try {
        $result = 0;

        if($_GET['playerid'] == 0) { //Create totally new player

            //Create random password
            $password = randomPassword();
        
            //Insert new player
            $photourl = 'default_avatar.jpg';
            $sql = "INSERT INTO players (name, mail, firstname, lastname, photourl, password) VALUES (:nick, :mail, :first, :last,'" . $photourl ."', '" . md5($password) ."')";

            if($_SESSION['ChromeLog']) { ChromePhp::log('newTeamUser: ' . $sql); }
        
            $stmt = $dbh->prepare($sql);
            //$stmt->bindParam(':teamID', $_GET['teamid'], PDO::PARAM_INT);
            $stmt->bindParam(':nick', $_GET['nickname'], PDO::PARAM_STR);
            $stmt->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
            $stmt->bindParam(':first', $_GET['firstname'], PDO::PARAM_STR);
            $stmt->bindParam(':last', $_GET['lastname'], PDO::PARAM_STR);
        
            $result = $stmt->execute();
            
            if($_SESSION['ChromeLog']) { ChromePhp::log('New player result: ' . $result); }
        } 


        //Get the playerID by mail address////////////////////////////////////////////////
        $playerid = 0;           
        $sql2 = "SELECT playerID from players WHERE mail = :mail";

        if($_SESSION['ChromeLog']) { ChromePhp::log('select inserted player: ' . $sql2); }
        
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        
        $result2 = $stmt2->execute();   
        $row2;

        while($row2 = $stmt2->fetch()) {
            //print_r($row);
            $playerid = $row2['playerID'];
        }
        

        //Create team/////////////////////////////////////////////////////////////////////
        
        //Select max teamid and make the new team's id++
        $teamid_max = 0;
        $sql6 = "SELECT max(teamID) as team_max from team";

        if($_SESSION['ChromeLog']) { ChromePhp::log('select inserted player: ' . $sql6); }
    
        $stmt6 = $dbh->prepare($sql6);
        $result6 = $stmt6->execute();   

        $row6;
        while($row6 = $stmt6->fetch()) {
            //print_r($row);
            $teamid_max = $row6['team_max'];
        }
        
        $teamid_max++; //The new team's ID

        //Calculate offset to UTC//////////////////////
        //$timezone_all = $_GET['continent'] . "/" . $_GET['country'];

        $timezone=$_GET["timezone"]; 
        date_default_timezone_set( "UTC" );    
        $daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open($timezone), new DateTime() ); 
        $offset = round($daylight_savings_offset_in_seconds/3600); //Hours
        
        //$sql3 = "INSERT INTO team (teamid, teamname, utcoffset, maxplayers, inuse) VALUES (:teamid, :teamname, :utcoffset, :maxplayers, :inuse)";
        $sql3 = "INSERT INTO team (teamid, teamname, timezone, utcoffset) VALUES (:teamid, :teamname, :timezone, :utcoffset)";
        //$sql3 = "INSERT INTO team (teamid, teamname) VALUES (:teamid, :teamname)"; //Works!

        if($_SESSION['ChromeLog']) { ChromePhp::log('Create new team: ' . $sql3); }

        $stmt3 = $dbh->prepare($sql3);
        $stmt3->bindParam(':teamid', $teamid_max, PDO::PARAM_INT);
        $stmt3->bindParam(':teamname', $_GET['teamname'], PDO::PARAM_STR);
        $stmt3->bindParam(':timezone', $timezone, PDO::PARAM_STR);
        $stmt3->bindParam(':utcoffset', $offset, PDO::PARAM_INT);
        //$stmt3->bindParam(':maxplayers', 20, PDO::PARAM_INT); //DB default
        //$stmt3->bindParam(':inuse', '1', PDO::PARAM_INT); //DB default       
        
        $result3 = $stmt3->execute();


        //Add user as registrar to the playerteam//////////////////////////////////////////////////////////
        $sql5 = "INSERT INTO playerteam (players_playerID, team_teamID, registrar) VALUES (:playerid, :teamid, :registrar)";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add registrar for playerteam: ' . $sql5); }
        
        $stmt5 = $dbh->prepare($sql5);

        $registrar = 1;        
        $stmt5->bindParam(':playerid', $playerid, PDO::PARAM_INT);
        $stmt5->bindParam(':teamid', $teamid_max, PDO::PARAM_INT);
        $stmt5->bindParam(':registrar', $registrar, PDO::PARAM_INT);
        
        $result5 = $stmt5->execute();                        

        //Add first chat comment to team////////////////////////////////////////////////////////////
        date_default_timezone_set($timezone);
        $insertDate = date("Y-n-j H:i:s");

        $sql7 = "INSERT INTO comments (comment, Players_playerID, Team_teamID, publishTime) VALUES (:comment, :playerid, :teamid, :insertdate)";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add registrar for playerteam: ' . $sql7); }
        
        $stmt7 = $dbh->prepare($sql7);

        $ryouin_player = 0;
        $comment = "Welcome to use R'YouIN! This is an automatic message from the team's registration. You can start by adding your teammates from the Admin - Users navigation and after that create a New event!";
        $stmt7->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt7->bindParam(':playerid', $ryouin_player, PDO::PARAM_INT);
        $stmt7->bindParam(':teamid', $teamid_max, PDO::PARAM_INT);
        $stmt7->bindParam(':insertdate', $insertDate, PDO::PARAM_STR);

        $result7 = $stmt7->execute();


        //Insert team data to registration table, use UTC for datetime information//////////////////
        date_default_timezone_set("UTC");
        $registrationDate = date("Y-n-j H:i:s");

        $start = new DateTimeImmutable($registrationDate);
        //$valid = $start->modify('+14 day');
        $valid = $start->modify('+30000 day');
        $valid = $valid->format('Y-m-d H:i:s');

        $sql8 = "INSERT INTO registration (registered, players_playerid, team_teamid, licensevalid) VALUES (:registered, :playerid, :teamid, :licensevalid)";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add registrar for playerteam: ' . $sql7); }
        
        $stmt8 = $dbh->prepare($sql8);

        $stmt8->bindParam(':registered', $registrationDate, PDO::PARAM_STR);
        $stmt8->bindParam(':playerid', $playerid, PDO::PARAM_INT);
        $stmt8->bindParam(':teamid', $teamid_max, PDO::PARAM_INT);
        $stmt8->bindParam(':licensevalid', $valid, PDO::PARAM_STR);

        $result8 = $stmt8->execute();          
        

        // //Send the mail for totally new user/////////////////////////////////////////////////////
        if($_GET['playerid'] == 0) {                
            //Mail content, totally new user
            $newuser_mail = array(        
                'subject' => "R'YouIN team created",                 
                'content' => "
                
                  <html>             	

                    <div style='background: black;'>
                        <img style='padding: 5px;' src='https://areyouin.azurewebsites.net/images/r2.png' align='middle' alt='RYouIN' height='42' width='42'>
                        <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> New team information</font>
                    </div>

                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Your team <span style='color:blue'>" . $_GET['teamname'] . "</span> has been created!</font>
                    
                    <br>
                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Your login information</font>
                    <ul style='list-style-type:disc'>
                    <font size='3' face='Trebuchet MS'>                                       		
	                    <li><span style='font-weight: bold;'>User ID: </span><span style='color:blue'> " . $_GET['mail'] . "    </span></li>
                        <li><span style='font-weight: bold;'>Password: </span><span style='color:blue'> " . $password . "</span></li>
	                    </font>
                    </ul>                                

                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Add users from the application and start setting events! Please remember to change your own password from the Profile section after login!</font>
                    
                    <br>
                    <br>

                    <div style='text-align: center; background: black; padding: 15px;'>
                    <font size='4' face='Trebuchet MS' style='color: white;'>			
                        Login at <a href='https://areyouin.azurewebsites.net/default.html' style='color: white;'>R'YouIN</a>!
                    </font>
                    </div>

                </html>",
            );


            sendMail($_GET['mail'], $mail_user, $mail_key, $newuser_mail);  
        }

        //Send the mail existing user//////////////////////////////////////////////
        if($_GET['playerid'] != 0) {

            //Mail content, totally new user
            $newuser_mail = array(        
                'subject' => "R'YouIN team created",                 
                'content' => "
                
                  <html>             	

                    <div style='background: black;'>
                        <img style='padding: 5px;' src='https://areyouin.azurewebsites.net/images/r2.png' align='middle' alt='RYouIN' height='42' width='42'>
                        <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> New team information</font>
                    </div>

                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Your team <span style='color:blue'>" . $_GET['teamname'] . "</span> has been created!</font>
                    
                    <br>
                    <br>

                    <ul style='list-style-type:disc'>
                    <font size='3' face='Trebuchet MS'>                                       		
	                    <li><span style='font-weight: bold;'>User ID: </span><span style='color:blue'> " . $_GET['mail'] . "    </span></li>
                        <li><span style='font-weight: bold;'>Your password stays the same</li>
	                    </font>
                    </ul>                                

                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Add users from the application and start setting events!</font>
                    
                    <br>
                    <br>

                    <div style='text-align: center; background: black; padding: 15px;'>
                    <font size='4' face='Trebuchet MS' style='color: white;'>			
                        Login at <a href='https://areyouin.azurewebsites.net/default.html' style='color: white;'>R'YouIN</a>!
                    </font>
                    </div>

                </html>",
            );
                            
            sendMail($_GET['mail'], $mail_user, $mail_key, $newuser_mail);  
        }  

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    

    //Random password
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 6; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    $dbh = null;

?>


