<?php  

        require_once('ImageManipulator.php');
        $player;

        session_start();

        //$call = 0;
	    //if ($_POST['Uploader']) // form has been submitted
	    //{ 
     //       uploadPhoto();

    	//    //if(uploadPhoto() == -5) {
     //    //       ChromePhp::log("Upload -5");
     //    //       $call = -5;
    	//    //}
     //   } 

        //include 'ChromePhp.php';        
        //ChromePhp::log("players_insert, admin:", $ad);
        
        //echo "<html>";
        //echo "<body>";
        
        //Article///////////////////////////////////////////////////////////////////////////
        echo "<article id=\"profile_content_article\" class=\"clearfix \">";
                    
        //Navigation///////////////////////////////////////////////////////////////////////////
        echo "<nav>";
			echo "<ul id=\"profile-nav\" class=\"clearfix\" onClick=\"profileClick()\">";
				echo "<li id=\"link_profile_profile\" class=\"current2\"><a href=\"#\">Player</a></li>";
                echo "<li id=\"link_profile_chart\"' onClick='drawChart();'><a href=\"#\">Chart</a></li>";
			echo "</ul>";
		echo "</nav>";
        //Navigation///////////////////////////////////////////////////////////////////////////

        //Profile tab
        echoProfile();
        
        //Chart tab
        echoChart();

        echo "</article>";
        //Article///////////////////////////////////////////////////////////////////////////

        //JS Show notification if image size is too big/////////////////////////////////////////////////////////////////////
        //if($call == -5) {
        //    echo "<script src=\"http://code.jquery.com/jquery-2.0.0.min.js\"></script>";
        //    echo "<script type=\"text/javascript\">";
        //    //echo "jQuery(document).ready(function () {";
        //        
        //            //echo "alert(\"PHP JS run...\");";
        //            echo "var x=document.getElementById(\"ImageSizeId\");";
        //            //echo "alert(x.innerHTML);";
        //            echo "alert(x.className);";
        //            //echo "var myClassName=\" noshow\";";
        //            //echo "x.className=x.className.replace(\"myClassName\",\"\");"; 
        //            echo "$(\"#ImageSizeId\").removeClass(\"noshow\");";
        //            echo "alert(x.className);";
        //            
        //     //echo "});";                        

        //    echo "</script>";
        //}
        //JS Show notification if image size is too big/////////////////////////////////////////////////////////////////////

        echo "</body>";
        echo "</html>";

        //Profile content///////////////////////////////////////////////////////////////////   
        function echoProfile() {
            include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

            $playerid=$_SESSION['myplayerid'];
	        
            $con = mysql_connect($dbhost, $dbuser, $dbpass);

	        if (!$con)
	        {
    	        die('Could not connect here: ' . mysql_error());
	        }

	        mysql_select_db("areyouin", $con);

            $sql = "SELECT * FROM players WHERE playerID = " . $playerid . "";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);

            $player = new Player($row['playerID'], $row['name'], $row['mail'], $row['mobile'], $row['photourl'], $row['notify']);


            echo "<div id=\"profile_profile_content_id\">";
                echo "<iframe name='frame_player' style='display: none;'></iframe>";
                echo "<br />";
                echo "<fieldset id='playerdata' style='padding-left: 5px; padding-bottom: 5px; margin-top: -15px;'>";
                    //echo "<br />";
                    echo "<legend style='text-align: left; color: black;'>";
                        echo "<div id=\"output\" style='padding-top: 35px;'>";
                            echo "<img width=\"50\" height=\"50\"\" src=\"images/" . $player->photourl . "\">";
                        echo "</div>";
                    echo "</legend>";
                    
                    echo "</legend>";
                        //echo "PlayerID: " . $player->playerID . "</br>";
                        echo "<h5 id='profile_playerName' style='margin-top: 10px;'> Name: " . $player->name . "</h5>";
                        echo "<h5 id='profile_playerEmail'>Email: " . $player->email . "</h5>";
                        echo "<h5 id='profile_playerPhone'>Phone: " . $player->phone . "</h5>";                        
                        if($player->notify == '0') 
                            echo "<h5 id='profile_playerNotify'>Mail notifications: OFF</h5>";
                        else
                            echo "<h5 id='profile_playerNotify'>Mail notifications: ON</h5>";


                        echo "<br />";
            mysql_close($con);
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

                            echo "<form id='player_edit' name='player_edit' method='post' action='updatePlayer.php' target='frame_player' onsubmit='refreshPlayerInfo();'>";

                                echo "<p style='margin: 0px; padding-top: 10px;'>";
                                echo "<label for='player_name' style='display: inline-block; width: 60px; text-align: right;'>User ID:&nbsp</label>";                    
                                echo "<input type='text' id='dialog_player_name' name='player_name' value='" . $player->name ."' required style='margin-bottom: 15px; background: grey; width: 190px;' readonly></input>";
                                echo "</p>";

                                echo "<p style='margin: 0px'>";
                                echo "<label for='player_email' style='display: inline-block; width: 60px; text-align: right;'>Email:&nbsp</label>";
                                echo "<input type='text' id='dialog_player_email' name='player_email' value='" . $player->email ."' required style='margin-bottom: 15px; width: 190px;'></input>";
                                echo "</p>";

                                echo "<p style='margin: 0px'>";
                                echo "<label for='player_phone' style='display: inline-block; width: 60px; text-align: right;'>Phone:&nbsp</label>";
                                echo "<input type='text' id='dialog_player_phone' name='player_phone' value='" . $player->phone ."' required style='margin-bottom: 15px; width: 190px;'></input>";
                                echo "</p>";


                        echo "<h5 id='dialog_player_notify'>Mail notifications:</h5>";
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

                                echo "<div class='buttonHolder'>";
                                    echo "<input type=\"submit\" value=\"Save\" name=\"savebutton\" id=\"savebutton\" class='dialog_button'>";
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
            echo "<legend style='text-align: left; color: black;'><h4>Change password</h4></legend>";
                echo "</br>";
                echo "<a href='#openModalPassword' class='myButton'>Change</a>";
                //echo "</br>";

                    //Modal dialog for password change/////////////////////////////////////////////////////////
                    echo "<div id='openModalPassword' class='modalDialog'>";
	                    echo "<div>";
		                    echo "<a id='closer' href='#close' title='Close' class='close'>X</a>";

                            echo "<form id='player_edit' name='player_edit' method='post' action='' target='frame_player>";
                            
                                echo "<p style='text-align: center; weight: bold;'>";
                                echo "Type your password twice";                        
                                echo "</p>";

                                echo "<p style='margin: 0px; padding-top: 10px;'>";
                                echo "<label for='player_name' style='display: inline-block; width: 60px; text-align: right;'>Password:&nbsp</label>";                    
                                echo "<input type='text' id='dialog_password1' name='dialog_password1' value='' required style='margin-bottom: 15px; width: 190px;'></input>";
                                echo "</p>";

                                echo "<p style='margin: 0px'>";
                                echo "<label for='player_email' style='display: inline-block; width: 60px; text-align: right;'>Password:&nbsp</label>";
                                echo "<input type='text' id='dialog_password2' name='dialog_password2' value='' required style='margin-bottom: 15px; width: 190px;'></input>";
                                echo "</p>";

                                echo "<div class='buttonHolder'>";
                                    echo "<input type=\"submit\" value=\"Save\" name=\"savebutton\" id=\"savebutton\" class='dialog_button'>";
                                echo "</div>";

		                    echo "</form>";
                    //Modal dialog for password change/////////////////////////////////////////////////////////
            
            echo"</fieldset>";
            //Password change////////////////////////////////////////////////////////////////////////////////////////////////
                
            //Photo upload////////////////////////////////////////////////////////////////////////////////////////////////    
            echo "<div class=\"nomobile\">";                
                echo "<br />";
                echo "<fieldset id='playerdata' style='padding: 5px;'>";
                echo "<legend style='text-align: left; color: black;'><h4>Upload new photo (Max size 2MB)</h4></legend>";
                    echo "</br>";
                    //echo "<form action=\"" . $_SERVER[PHP_SELF] . "\" method=\"post\" enctype=\"multipart/form-data\" id=\"MyUploadForm\" target=\"frame\">";
                    echo "<form action=\"processupload.php\" method=\"post\" enctype=\"multipart/form-data\" id=\"MyUploadForm\" target=\"frame\">";
                    echo "<input name=\"FileInput\" id=\"FileInput\" type=\"file\"/>";
                    echo "<br>";
                    echo "<br>";
                    echo "<input class=\"myButton\" type=\"submit\"  id=\"submit-btn\" value=\"Upload\" name=\"Uploader\"/>";
                    echo "</form>";
                    //echo "</br>";
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

            function Player($playerID, $name, $mail, $phone, $photourl, $notify) {
                $this->playerID = $playerID;
                $this->name = $name;
                $this->email = $mail;
                $this->phone = $phone;
                $this->photourl = $photourl;
                $this->notify = $notify;
            }

        }

?>

