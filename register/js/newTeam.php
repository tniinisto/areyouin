<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    include( $_SERVER['DOCUMENT_ROOT'] . 'mail_ayi.php' );

    session_start();

    //if($_SESSION['ChromeLog']) {
    //    require_once  $_SERVER['DOCUMENT_ROOT'] . 'ChromePhp.php';
    //    ChromePhp::log('newTeam.php start');
    //}


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
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

        $playerid = 0;
        //Get the playerID by mail address////////////////////////////////////////////////           
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
        
            //Calculate offset to UTC//////////////////////
            date_default_timezone_set( "UTC" );    
            $daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open($_GET['timezone']), new DateTime() ); 
            $offset = round($daylight_savings_offset_in_seconds/3600); //Hours
            
            $sql3 = "INSERT INTO team (teamname, timezone, utcoffset, maxplayers, inuse) VALUES (:teamname, :timezone, :utcoffset, 20, 1)";

            if($_SESSION['ChromeLog']) { ChromePhp::log('Create new team: ' . $sql3); }
            
            $stmt3 = $dbh->prepare($sql3);
            $stmt3->bindParam(':teamname', $_GET['teamname'], PDO::PARAM_STR);
            $stmt3->bindParam(':timezone', $_GET['timezone'], PDO::PARAM_STR);
            $stmt3->bindParam(':utcoffset', $offset, PDO::PARAM_INT);
            // $stmt3->bindParam(':maxplayers', 20, PDO::PARAM_INT);
            // $stmt3->bindParam(':inuse', 1, PDO::PARAM_INT);        
            
            $result3 = $stmt3->execute();


            // //Get the team////////////////////////////////////////////////
            // $teamid = 0;           
            // $sql4 = "SELECT teamID from team WHERE teamname like :teamname and timezone like :timezone";

            // if($_SESSION['ChromeLog']) { ChromePhp::log('select team: ' . $sql4); }
            
            // $stmt4 = $dbh->prepare($sql4);
            // $stmt4->bindParam(':teamname', $_GET['teamname'], PDO::PARAM_STR);
            // $stmt4->bindParam(':timezone', $_GET['timezone'], PDO::PARAM_STR);
            
            // $result4 = $stmt4->execute();   

            // $row4;         
            // while($row4 = $stmt4->fetch()) {
            //     //print_r($row);
            //     $teamid= $row4['teamID'];
            // }        


        // //Add player to the team//////////////////////////////////////////////////////////
        // $sql5 = "INSERT INTO playerteam (Players_playerID, Team_teamID, teamAdmin, registrar) VALUES (:playerid, :teamid , 0, 1)";

        // if($_SESSION['ChromeLog']) { ChromePhp::log('Add registrar for playerteam: ' . $sql5); }
        
        // $stmt5 = $dbh->prepare($sql5);
        
        // $stmt5->bindParam(':playerid', $playerid, PDO::PARAM_INT);
        // $stmt5->bindParam(':teamID', $teamid, PDO::PARAM_INT);
        
        // $result5 = $stmt5->execute();                        



        // //Send the mail for totally new user//////////////////////////////////////////////
        // if($_GET['playerid'] == 0) {                
        //     //Mail content, totally new user
        //     $newuser_mail = array(        
        //         'subject' => "R'YouIN new team info",                 
        //         'content' => "
                
        //           <html>             	

        //             <div style='background: black;'>
        //                 <img style='padding: 5px;' src='https://r-youin.com/images/r2.png' align='middle' alt='RYouIN' height='42' width='42'>
        //                 <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> Your login information</font>
        //             </div>

        //             <br>

        //             <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Your team <span style='color:blue'>" . $_GET['teamname'] . "</span> has been created!</font>
                    
        //             <br>
        //             <br>

        //             <ul style='list-style-type:disc'>
        //             <font size='3' face='Trebuchet MS'>                                       		
	    //                 <li><span style='font-weight: bold;'>User ID: </span><span style='color:blue'> " . $_GET['mail'] . "    </span></li>
        //                 <li><span style='font-weight: bold;'>Password: </span><span style='color:blue'> " . $password . "</span></li>
	    //                 </font>
        //             </ul>                                

        //             <br>

        //             <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Please remember to change your own password from the Profile section after login!</font>
                    
        //             <br>
        //             <br>

        //             <div style='text-align: center; background: black; padding: 15px;'>
        //             <font size='4' face='Trebuchet MS' style='color: white;'>			
        //                 Login at <a href='https://r-youin.com/' style='color: white;'>R'YouIN</a>!
        //             </font>
        //             </div>

        //         </html>",
        //     );


        //     sendMail($_GET['mail'], $mail_user, $mail_key, $newuser_mail);  
        // }

        // //Send the mail existing user//////////////////////////////////////////////
        // if($_GET['playerid'] != 0) {

        //     //Mail content, totally new user
        //     $newuser_mail = array(        
        //         'subject' => "R'YouIN new team info",                 
        //         'content' => "
                
        //           <html>             	

        //             <div style='background: black;'>
        //                 <img style='padding: 5px;' src='https://r-youin.com/images/r2.png' align='middle' alt='RYouIN' height='42' width='42'>
        //                 <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> New team information</font>
        //             </div>

        //             <br>

        //             <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Your team <span style='color:blue'>" . $_GET['teamname'] . "</span> has been created!</font>
                    
        //             <br>
        //             <br>

        //             <ul style='list-style-type:disc'>
        //             <font size='3' face='Trebuchet MS'>                                       		
	    //                 <li><span style='font-weight: bold;'>User ID: </span><span style='color:blue'> " . $_GET['mail'] . "    </span></li>
        //                 <li><span style='font-weight: bold;'>Your password for R'YouIN is the same as before</li>
	    //                 </font>
        //             </ul>                                

        //             <br>

        //             <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Please remember to change your own password from the Profile section after login!</font>
                    
        //             <br>
        //             <br>

        //             <div style='text-align: center; background: black; padding: 15px;'>
        //             <font size='4' face='Trebuchet MS' style='color: white;'>			
        //                 Login at <a href='https://r-youin.com/' style='color: white;'>R'YouIN</a>!
        //             </font>
        //             </div>

        //         </html>",
        //     );
                            
        //     sendMail($_GET['mail'], $mail_user, $mail_key, $newuser_mail);  
        // }  

        // $dbh = null;

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
?>


