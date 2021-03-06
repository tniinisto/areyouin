<?php  
        //include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
        require_once('ImageManipulator.php');
        $player;

        session_start();

        //include 'ChromePhp.php';        
        //ChromePhp::log("players_insert, admin:", $ad);
                
        //Article///////////////////////////////////////////////////////////////////////////
        echo "<article id=\"profile_content_article\" class=\"clearfix \">";
                    
        //Navigation///////////////////////////////////////////////////////////////////////////
        echo "<nav>";
			echo "<ul id=\"profile-nav\" class=\"clearfix\" onClick=\"profileClick()\">";
				echo "<li id=\"link_profile_profile\" class=\"current2\"><a href=\"#\">Your information</a></li>";
                echo "<li id=\"link_profile_chart\"' onClick='drawChart();'><a href=\"#\">Activity</a></li>";
			echo "</ul>";
		echo "</nav>";
        //Navigation///////////////////////////////////////////////////////////////////////////

        //Profile tab
        echoProfile();
        
        //Chart tab
        echoChart();

        echo "</article>";
        //Article///////////////////////////////////////////////////////////////////////////

        //echo "</body>";
        //echo "</html>";

        //Profile content///////////////////////////////////////////////////////////////////   
        function echoProfile() {
            include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
            $playerid=$_SESSION['myplayerid'];
	        
            // $con = mysql_connect($dbhost, $dbuser, $dbpass);

	        // if (!$con)
	        // {
    	    //     die('Could not connect here: ' . mysql_error());
	        // }

	        // mysql_select_db($dbname, $con);

            // $sql = "SELECT * FROM players WHERE playerID = " . $playerid . "";
            // $result = mysql_query($sql);
            // $row = mysql_fetch_array($result);

        //PDO - UTF-8
        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);	
	    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Get current users info
        // PDO. utf-8 //////////////////////////////////////////////////        
        $sql1 ="SELECT * FROM players WHERE playerID = :playerid";
        $stmt1 = $dbh->prepare($sql1);
        $stmt1->bindParam(':playerid', $playerid, PDO::PARAM_INT);

        $result1 = $stmt1->execute();

        $row;
        while($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {    

            $player = new Player($row['playerID'], $row['name'], $row['mail'], $row['mobile'], $row['photourl'], $row['notify'], $row['firstname'], $row['lastname']);

            echo "<div id=\"profile_profile_content_id\">";
                echo "<iframe name='frame_player' style='display: none;'></iframe>";
                echo "<br />";
                echo "<fieldset id='playerdata' style='padding-left: 5px; padding-bottom: 5px; margin-top: -15px;'>";
                    //echo "<br />";
                    echo "<legend style='text-align: left; color: black;'>";
                        echo "<div id='output' style='padding-top: 35px;'>";
                            echo "<img width=\"50\" height=\"50\"\" src=\"images/" . $player->photourl . "\">";
                        echo "</div>";
                    echo "</legend>";
                    
                        //echo "PlayerID: " . $player->playerID . "<br>";
                        echo "<h5 id='profile_playerName' style='margin-top: 10px;'> Nickname: " . $player->name . "</h5>";
                        echo "<h5 id='profile_playerFirstname' style='margin-top: 10px;'> Firstname: " . $player->firstname . "</h5>";
                        echo "<h5 id='profile_playerLastname' style='margin-top: 10px;'> Lastname: " . $player->lastname . "</h5>";
                        echo "<h5 id='profile_playerEmail'>Email / UserID: " . $player->email . "</h5>";
                        echo "<h5 id='profile_playerPhone'>Phone: " . $player->phone . "</h5>";                        
                        if($player->notify == '0') 
                            echo "<h5 id='profile_playerNotify'>Mail notifications: OFF</h5>";
                        else
                            echo "<h5 id='profile_playerNotify'>Mail notifications: ON</h5>";


                        echo "<br />";
            //mysql_close($con);
            $dbh = null;
        }
        
?>
            <a href="#openModal" class="myButton">Edit information</a>
            
            <!--<form action="#openModal">
                <input type="submit" value="Edit information">
            </form>-->

                    <!--Modal dialog for player information editing/////////////////////////////////////////////////////////-->
                    <div id="openModal" class="modalDialog">
	                    <div>
		                    <a id="closer" href="#close" title="Close" class="close">X</a>
                    <?php

                            echo "<form autocomplete='off' id='player_edit' name='player_edit' method='get' target='frame_player' onsubmit='UpdatePlayer();'>";

                                //echo "<p style='margin: 5px;'>";
                                //echo "<label style='display: block; text-align: center; weight: bold; width: 110%; font-size: 125%;'>Edit your information</label>";
                                //echo "</p>";

                                //Mail & UserID
                                echo "<div id='profile_mail' style='text-align: center; margin: auto; display: inline-block; width: 100%; padding-top: 5px;'>";
                                    echo "<label style='display: block; text-align: center; font-weight: bold; width: 100%; font-size: 100%; color:red;'>Email / UserID:</label>";
                                    echo "<label style='display: block; text-align: center; font-weight: bold; width: 100%; font-size: 70%; color:black;'>Notice! This is also your login ID!</label>";                                    
                                    echo "<p style='margin: 0px'>";
                                    echo "<input type='text' id='dialog_player_email' name='player_email' value='" . $player->email ."' required
                                           style='margin-bottom: 15px; width: 210px;' onblur='validateEmail(this.value);'></input>";
                                    echo "</p>";
                                echo "</div>";

                                //Nickname
                                echo "<p style='margin: 0px; padding-top: 0px;'>";
                                echo "<label for='player_name' style='display: inline-block; width: 60px; text-align: right; color:black;'>Nickname:&nbsp</label>";                    
                                echo "<input type='text' id='dialog_player_name' name='player_name' value='" . $player->name ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                echo "</p>";
                                
                                //Fullname
                                echo "<p style='margin: 0px; padding-top: 0px; margin-top: -5px;'>";
                                echo "<label for='player_firstname' style='display: inline-block; width: 60px; text-align: right; color:black;'>Firstname:&nbsp</label>";                    
                                echo "<input type='text' id='dialog_player_firstname' name='player_firstname' value='" . $player->firstname ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                echo "</p>";

                                //Lastname
                                echo "<p style='margin: 0px; padding-top: 0px; margin-top: -5px;'>";
                                echo "<label for='player_lastname' style='display: inline-block; width: 60px; text-align: right; color:black;'>Lastname:&nbsp</label>";                    
                                echo "<input type='text' id='dialog_player_lastname' name='player_lastname' value='" . $player->lastname ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                echo "</p>";

                                //Phone
                                echo "<p style='margin: 0px; padding-top: 0px; padding-bottom: 2px; margin-top: -5px;'>";
                                echo "<label for='player_phone' style='display: inline-block; width: 60px; text-align: right; color:black;'>Phone:&nbsp</label>";
                                echo "<input type='text' id='dialog_player_phone' name='player_phone' value='" . $player->phone ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                echo "</p>";


                                echo "<h5 id='dialog_player_notify' style='color:black; font-weight: normal;'>Mail notifications:</h5>";
                                    if( $player->notify == '1') {
                                        echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
						                    echo "<input type='checkbox' name='notifyswitch' class=\"onoffswitch-checkbox\" id='dialog_notify_switch' checked>";					            
                                            echo "<label class=\"onoffswitch-label\" for='dialog_notify_switch' onClick=''>";
                                                echo "<div class=\"notifyswitch-inner\"></div>";
						                        echo "<div class=\"onoffswitch-switch\"></div>";
						                    echo "</label>";
                                        echo "</div>";
                                    } else {
                                        echo "<div class=\"onoffswitch notifyswitch\" style='display: inline-block;'>";
						                    echo "<input type='checkbox' name='notifyswitch' class=\"onoffswitch-checkbox\" id='dialog_notify_switch'>";
                                            echo "<label class=\"onoffswitch-label\" for='dialog_notify_switch' onClick=''>";
                                                echo "<div class=\"notifyswitch-inner\"></div>";
						                        echo "<div class=\"onoffswitch-switch\"></div>";
						                    echo "</label>";
                                        echo "</div>";                            
                                    }
                                echo "</h5>";

                                echo "<div class='buttonHolder' style='padding-top: 2px;'>";
                                    echo "<input type='submit' value='Save' name='savebutton' id='savebutton' class='myButton'>";
                                echo "</div>";
		                    echo "</form>";
                    ?>
                    <!--/Modal dialog for player information editing/////////////////////////////////////////////////////////-->

	                    </div>
                    </div>
                </fieldset>                
            <br />

<?php
    
            //Password change////////////////////////////////////////////////////////////////////////////////////////////////                    
            echo "<fieldset id='playerdata' style='padding: 5px;'>";
            echo "<legend style='text-align: left; color: black;'><h4>Password change</h4></legend>";
                echo "<br>";
                echo "<p id='password_info_id' class='noshow' style='text-align: center; color:red; font-weight: bold;'>Your password is changed</p>";
                echo "<a href='#openModalPassword' class='myButton' onclick='initPassForm();'>Change password</a>";
                //echo "<br>";

                    //Modal dialog for password change/////////////////////////////////////////////////////////
                    echo "<div id='openModalPassword' class='modalDialog'>";
	                    echo "<div>";
		                    echo "<a id='closer1' href='#close' title='Close' class='close'>X</a>";

                            echo "<form id='pass_edit' name='pass_edit' method='post' action='updatePassword.php' target='frame_player' onsubmit='refreshPassword();'>";
                            
                                echo "<p style='margin: 10px;'>";
                                echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%; color: black;'>Type your new password twice</label>";                      
                                echo "</p>";
                                echo "<p style='margin: 0px;'>";
                                echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 100%; color: black;'>Length 5-10 characters</label>";                      
                                echo "</p>";

                                echo "<p style='margin: 0px; padding-top: 10px;'>";
                                echo "<label for='player_name' style='display: inline-block; width: 60px; text-align: right; color: black;'>Password:&nbsp</label>";                    
                                echo "<input type='text' id='dialog_password1' name='password1' value='' pattern='.{5,10}' minlength='5' maxlength='10' required style='margin-bottom: 15px; width: 190px;' onfocusout='check_pass()'></input>";
                                echo "</p>";

                                echo "<p style='margin: 0px'>";
                                echo "<label for='player_email' style='display: inline-block; width: 60px; text-align: right; color: black;'>Password:&nbsp</label>";
                                echo "<input type='text' id='dialog_password2' name='password2' value='' pattern='.{5,10}' minlength='5' maxlength='10' required style='margin-bottom: 15px; width: 190px;' onfocusout='check_pass()'></input>";
                                echo "</p>";

                                echo "<div class='buttonHolder'>";
                                    echo "<input type=\"submit\" value=\"Save\" name=\"savebutton\" id=\"savebutton\" class='myButton'>";
                                echo "</div>";

		                    echo "</form>";
	                    echo "</div>";
                    echo "</div>";
                    //Modal dialog for password change/////////////////////////////////////////////////////////

            echo"</fieldset>";
            //Password change////////////////////////////////////////////////////////////////////////////////////////////////
                
            //Photo upload////////////////////////////////////////////////////////////////////////////////////////////////    
            echo "<div class=\"nomobile\">";                
                echo "<br />";
                echo "<fieldset id='playerdata' style='padding: 5px;'>";
                echo "<legend style='text-align: left; color: black;'><h4>Upload new photo (Max size 2MB)</h4></legend>";
                    echo "<br>";
                        echo "<div id='toobig' style='padding-top: 35px;'>";
                            
                        echo "</div>";                    
                    echo "<form action=\"processupload.php\" method=\"post\" enctype=\"multipart/form-data\" id=\"MyUploadForm\" target=\"frame\">";
                    echo "<input name=\"FileInput\" id=\"FileInput\" type=\"file\"/>";
                    echo "<br>";
                    echo "<br>";
                    echo "<input class=\"myButton\" type=\"submit\"  id=\"submit-btn\" value=\"Upload\" name=\"Uploader\"/>";
                    echo "</form>";
                    //echo "<br>";
                    echo "<div id=\"progressbox\">";
                        echo "<div id=\"progressbar\"></div >";
                        echo "<div id=\"statustxt\">0%</div>";
                    echo "</div>";
                echo"</fieldset>";

            echo "</div>";
            //Photo upload////////////////////////////////////////////////////////////////////////////////////////////////
                //echo "<h4 name=\"ImageSize\" id=\"ImageSizeId\" class=\"noshow\">Your image is too big!</h4>";
                //echo "<div id=\"output\"></div>";                
            
        echo "</div>"; //profile_profile_content_id
        }


        //Chart//////////////////////////////////////////////////////////////////////////////
        function echoChart() {
            echo "<div id='profile_chart_content_id' class='noshow'></div>";
        }
        //Chart//////////////////////////////////////////////////////////////////////////////


        //Team content////////////////////////////////////////////////////////////////////
        //function echoTeam() {
        //    echo "<div id=\"profile_team_content_id\" class=\"noshow\">";
        //        echo "<h1>Team</h1>";
        //    echo "</div>";            
        //}



        class Player {
            var $playerID;
            var $photourl;
            var $name;
            var $email;
            var $phone;
            var $notify;
            var $firstname;
            var $lastname;

            function Player($playerID, $name, $mail, $phone, $photourl, $notify, $firstname, $lastname) {
                $this->playerID = $playerID;
                $this->name = $name;
                $this->email = $mail;
                $this->phone = $phone;
                $this->photourl = $photourl;
                $this->notify = $notify;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
            }

        }

?>

