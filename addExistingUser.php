<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    include 'mail_ayi.php';

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('addExistingUser.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
    try {

        //Add existing player to the team//////////////////////////////////////////////////////////
        $sql1 = "INSERT INTO playerteam (Players_playerID, Team_teamID, teamAdmin) VALUES ( :playerid ," . $_SESSION['myteamid'] . ", 0)";

        if($_SESSION['ChromeLog']) { ChromePhp::log('Add player for playerteam: ' . $sql1); }

        $stmt1 = $dbh->prepare($sql1);    
        $stmt1->bindParam(':playerid', $_GET['playerid'], PDO::PARAM_INT);
  
        $result1 = $stmt1->execute();                        

        echo $result1;

        $dbh = null;

        //Send mail
        $existinguser_mail = array(        
                'subject' => "R'YouIN user login information",                 
                'content' => "
                
                  <html>             	

                    <div style='background: black;'>
                        <img style='padding: 5px;' src='https://r-youin.com/images/r2.png' align='middle' alt='RYouIN' height='42' width='42'>
                        <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> Your login information</font>
                    </div>

                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>You have been added to a new team: " . $_SESSION['myteamname'] . "</font>
                    
                    <br>
                    <br>

                    <ul style='list-style-type:disc'>
                    <font size='3' face='Trebuchet MS'>                                       		
	                    <li><span style='font-weight: bold;'>You can use same login credentials as before for User ID: </span><span style='color:blue'> " . $_GET['mail'] . "    </span></li>                        
	                    </font>
                    </ul>                                
                   
                    <br>

                    <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Team selection dialog is presented when you login!</font>

                    <br>
                    <br>

                    <div style='text-align: center; background: black; padding: 15px;'>
                    <font size='4' face='Trebuchet MS' style='color: white;'>			
                        Login at <a href='https://r-youin.com/' style='color: white;'>R'YouIN</a>!
                    </font>
                    </div>

                </html>",
            );

            //Send mail for existing user that has been added to another team
            sendMail($_GET['mail'], $mail_user, $mail_key, $existinguser_mail);  
           

    }
    catch(PDOException $e) {
	    echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
    
?>


