<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    include 'mail_ayi.php';

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('newTeamUser.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    $password = '';
          
    try {
        $result = 0;

        if($_GET['totallyNew'] == 0) { //Create new player, if the player is not already in another team

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


        //Get the playerID////////////////////////////////////////////////////////////////           
        $sql2 = "SELECT playerID from players WHERE mail like :mail";

        if($_SESSION['ChromeLog']) { ChromePhp::log('select inserted player: ' . $sql2); }
        
        $stmt2 = $dbh->prepare($sql2);
        $stmt2->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        
        $result2 = $stmt2->execute();   
        $row2;
        $playerid = 0;
        while($row2 = $stmt2->fetch()) {
            //print_r($row);
            $playerid = $row2['playerID'];
        }                     


        //Add player to the team//////////////////////////////////////////////////////////
        $sql1 = "INSERT INTO playerteam (Players_playerID, Team_teamID, teamAdmin) VALUES (" . $playerid . "," . $_SESSION['myteamid'] . ", 0)";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add player for playerteam: ' . $sql1); }
        
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':teamID', $_GET['teamid'], PDO::PARAM_INT);
        $stmt1->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        
        $result1 = $stmt1->execute();                        

        //Send mail
        $newuser_mail = array(        
                'subject' => "R'YouIN user login information",                 
                'content' => "
                
                  <html>             	

                    <div style='background: black;'>
                        <img style='padding: 5px;' src='https://r-youin.com/images/r2.png' align='middle' alt='RYouIN' height='42' width='42'>
                        <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> Your login information</font>
                    </div>

                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Welcome as a new R'YouIN user!</font>
                    
                    <br>
                    <br>

                    <ul style='list-style-type:disc'>
                    <font size='3' face='Trebuchet MS'>                                       		
	                    <li><span style='font-weight: bold;'>User ID: </span><span style='color:blue'> " . $_GET['mail'] . "    </span></li>
                        <li><span style='font-weight: bold;'>Password: </span><span style='color:blue'> " . $password . "</span></li>
	                    </font>
                    </ul>                                

                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Please remember to change your own password from the Profile section after login!</font>
                    
                    <br>
                    <br>

                    <div style='text-align: center; background: black; padding: 15px;'>
                    <font size='4' face='Trebuchet MS' style='color: white;'>			
                        Login at <a href='https://r-youin.com/' style='color: white;'>R'YouIN</a>!
                    </font>
                    </div>

                </html>",
            );

            //Send the mail for totally new user
            //if($_GET['totallyNew'] == 0) {
                
                sendMail($_GET['mail'], $mail_user, $mail_key, $newuser_mail);  

            //} 

        $dbh = null;

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


