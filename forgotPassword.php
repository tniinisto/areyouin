<?php
    
    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
    include 'mail_ayi.php';

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('forgotPassword.php start');
    }


  	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    $password = '';
          
    try {
        $result = 0;

            //Create random password
            $password = randomPassword();
        
            //Insert new password
            $sql = "UPDATE players SET password = '" . md5($password) . "' WHERE mail LIKE ':mail';";

            if($_SESSION['ChromeLog']) { ChromePhp::log('forgotPassword: ' . $sql); }
        
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':mail', $_GET['mail'], PDO::PARAM_STR);
        
            $result = $stmt->execute();
            
            if($_SESSION['ChromeLog']) { ChromePhp::log('forgotPassword result: ' . $result); }

            echo $result;

        $dbh = null;

        if($result == 1) {
            //Send mail
            $password_mail = array(        
                    'subject' => "R'YouIN new password",                 
                    'content' => "
                
                      <html>             	

                        <div style='background: black;'>
                            <img style='padding: 5px;' src='https://r-youin.com/images/r2.png' align='middle' alt='RYouIN' height='42' width='42'>
                            <font style='color: white; padding-left: 5px;' size='4' face='Trebuchet MS'> Your login information</font>
                        </div>

                        <br>

                        <font style='color: black; padding-left: 5px;' size='3' face='Trebuchet MS'>Your password has been renewed.</font>
                    
                        <br>
                        <br>

                        <ul style='list-style-type:disc'>
                            <font size='3' face='Trebuchet MS'>                                       		
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

            sendMail($_GET['mail'], $mail_user, $mail_key, $password_mail);  
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
?>


