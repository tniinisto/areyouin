<?php
        include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

        session_start();
        
        //$teamid=1;
        $teamid=$_SESSION['myteamid'];
        $ad=$_SESSION['myAdmin'];

        if($ad==1) //Execute only for admin status
        {
            
            $con = mysql_connect($dbhost, $dbuser, $dbpass);

            if (!$con)
              {
              die('Could not connect: ' . mysql_error());
              }

            mysql_select_db("areyouin", $con);

            //$sql="SELECT p.playerID, p.name, p.photourl FROM players p, team t where t.teamID = '1'";
            $sql="SELECT p.playerID, p.name, p.mobile, p.mail, p.photourl, p.notify, p.firstname, p.lastname, pt.teamAdmin, t.maxPlayers
            FROM players p, playerteam pt, team t WHERE t.teamID = '" . $teamid . "' AND pt.team_teamID = '" . $teamid . "' AND pt.players_playerID = p.playerID";
        
            $result = mysql_query($sql);
            $row_count = mysql_num_rows($result);

               //Members/Users page///////////////////////////////////////////////////////////////////////////
                echo "<div id='member_content_id'>";
                    
                    //echo "<h2>User managing stuff new, delete ok, edit ok...</h2>";

                    mysql_data_seek($result, 0);
                    $row = mysql_fetch_array($result);
                    echo "<h2>Users: " . $row_count . " / " . $row[maxPlayers] . "</h2>";

                    echo "<br>";

                    //Add new user
                    if($row_count < $row[maxPlayers]) {
                        echo "<a href='#openModal_new' id='addUserButton' class='myButton' style='float: left;'>Add new user</a>";
                    }
                    
                    echo "<br>";
                    echo "<br>";

                            //Modal dialog for new user/////////////////////////////////////////////////////////
                            echo "<div id='openModal_new' class='modalDialog'>";
	                            echo "<div>";

		                            echo "<a id='closer' href='#close' title='Close' class='close'>X</a>";

                                    echo "<form id='player_new' name='player_new' method='get' target='frame_player' onsubmit='newPlayer();'>";

                                        //echo "<p style='margin: 5px;'>";
                                        //echo "<label style='display: block; text-align: center; weight: bold; width: 110%; font-size: 125%;'>Edit your information</label>";
                                        //echo "</p>";

                                        //Mail & UserID
                                        echo "<div id='player_new_mail' style='text-align: center; margin: auto; display: inline-block; width: 100%; padding-top: 5px;'>";
                                            echo "<label style='display: block; text-align: center; font-weight: bold; width: 100%; font-size: 125%;'>Enter user's email</label>";
                                            echo "<br>";
                                            echo "<p style='margin: 0px'>";
                                            echo "<input type='text' id='dialog_player_new_email' name='player_new_email' value='" . $player->email ."' required
                                                   style='margin-bottom: 15px; width: 210px;' onblur='validateEmail(this.value);'></input>";                                            
                                        echo "</div>";

                                        //Header
                                        echo "<p style='margin: 0px; padding-top: 0px;' class='noshow' id='p_dialog_player_new_header'>";
                                        echo "<label style='display: block; text-align: center; font-weight: bold; width: 100%; font-size: 125%;'>Enter user's info</label>";
                                        echo "</p>";
                                        echo "<br>";

                                        //Nickname
                                        echo "<p style='margin: 0px; padding-top: 0px;' class='noshow' id='p_dialog_player_new_name'>";
                                        echo "<label for='player_new_name' style='display: inline-block; width: 60px; text-align: right; color:black;'>Nickname:&nbsp</label>";                    
                                        echo "<input type='text' id='dialog_player_new_name' name='player_new_name' value='" . $player->name ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                        echo "</p>";
                                
                                        //Fullname
                                        echo "<p style='margin: 0px; padding-top: 0px; margin-top: -5px;' class='noshow' id='p_dialog_player_new_firstname'>";
                                        echo "<label for='player_new_firstname' style='display: inline-block; width: 60px; text-align: right; color:black;'>Firstname:&nbsp</label>";                    
                                        echo "<input type='text' id='dialog_player_new_firstname' name='player_new_firstname' value='" . $player->firstname ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                        echo "</p>";

                                        //Lastname
                                        echo "<p style='margin: 0px; padding-top: 0px; margin-top: -5px;' class='noshow' id='p_dialog_player_new_lastname'>";
                                        echo "<label for='player_new_lastname' style='display: inline-block; width: 60px; text-align: right; color:black;'>Lastname:&nbsp</label>";                    
                                        echo "<input type='text' id='dialog_player_new_lastname' name='player_new_lastname' value='" . $player->lastname ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                        echo "</p>";

                                        //Phone
                                        //echo "<p style='margin: 0px; padding-top: 0px; padding-bottom: 2px; margin-top: -5px;' class='noshow'>";
                                        //echo "<label for='player_new_phone' style='display: inline-block; width: 60px; text-align: right; color:black;'>Phone:&nbsp</label>";
                                        //echo "<input type='text' id='dialog_player_new_phone' name='player_new_phone' value='" . $player->phone ."' required style='margin-bottom: 15px; width: 180px;'></input>";
                                        //echo "</p>";


                                  //      echo "<h5 id='dialog_player_new_notify' class='noshow' style='color:black; font-weight: normal;'>Mail notifications:</h5>";
                                  //          if( $player->notify == '1') {
                                  //              echo "<div class='onoffswitch notifyswitch noshow' style='display: inline-block;'>";
						                            //echo "<input type='checkbox' name='notifyswitch' class='onoffswitch-checkbox noshow' id='dialog_player_new_notify_switch' checked>";					            
                                  //                  echo "<label class='onoffswitch-label noshow' for='dialog_player_new_notify_switch' onClick=''>";
                                  //                      echo "<div class=\"notifyswitch-inner\"></div>";
						                            //    echo "<div class=\"onoffswitch-switch\"></div>";
						                            //echo "</label>";
                                  //              echo "</div>";
                                  //          } else {
                                  //              echo "<div class='onoffswitch notifyswitch noshow' style='display: inline-block;'>";
						                            //echo "<input type='checkbox' name='notifyswitch' class='onoffswitch-checkbox noshow' id='dialog_player_new_notify_switch'>";
                                  //                  echo "<label class='onoffswitch-label noshow' for='dialog_player_new_notify_switch' onClick=''>";
                                  //                      echo "<div class=\"notifyswitch-inner\"></div>";
						                            //    echo "<div class=\"onoffswitch-switch\"></div>";
						                            //echo "</label>";
                                  //              echo "</div>";                            
                                  //          }
                                  //      echo "</h5>";

                                        echo "<div class='buttonHolder' style='padding-top: 2px;'>";

                                            //Insert the player for the team, send mail for the new user
                                            echo "<input type='button' value='Save' name='player_new_savebutton' id='player_new_savebutton' class='dialog_button noshow'
                                                   onclick='addTeamUser(" . $teamid . ", player_new_email.value, player_new_name.value, player_new_firstname.value, player_new_lastname.value)'>";

                                            //Validate the email entered, does email already exist, is user already in the team. If already in another team, show name and ask if this should be insterted for the team
                                            echo "<input type='button' value='Validate' name='player_new_validatebutton' id='player_new_validtebutton' class='dialog_button' style='text-align: center;'    
                                                   onclick='newValidateEmail(player_new_email.value)'>";

                                        echo "</div>";
		                            echo "</form>";
                                echo "</div>";
                            echo "</div>";
                            //Modal dialog for new user/////////////////////////////////////////////////////////

                    echo "<br>";
                    
                    echo "<div id='users_list' class='scrollit2'>";

                        echo "<table border='0' id='users_table' class='usertable'";
                        
                            mysql_data_seek($result, 0);
                            $index = 1;
                            while($row = mysql_fetch_array($result))
                            {

                                    $player = new PlayerEdit($row['playerID'], $row['name'], $row['mobile'], $row['mail'], $row['photourl'], $row['notify'], $row['firstname'], $row['lastname'], $row['teamAdmin']);
                                                                
                                    echo "<tr>";

                                        //Image & Nickname
                                        echo "<td>";
                                            echo "<div class='chat-list-left' style='width: 50px;'>";
                                                echo "<img width='40' height='40' src='images/" . $player->photourl . "'>";
                                                echo "<br />";
                                                echo "<div class='comment-name' style='color: #474747; text-align: left; padding-left: 2px; font-weight: bold; width: 55px;'>" . $player->name . "</div>";
                                                 echo "<div id='player_name" . $index . "' class='noshow'>".$player->name."</div>";
                                            echo "</div>";
                                        echo "</td>";                  

                                        //Firstname Lastname, mobile, mail, teamAdmin
                                        echo "<td>";
                                            echo "<div class='edit-listinfo'>";

                                                if($player->teamAdmin == 1)
                                                    echo "<div style='font-weight: bold;'>Team Admin</div>";
                                                echo "<div id='player_admin" . $index . "' class='noshow'>".$player->teamAdmin."</div>";

                                                echo "" . $player->firstname . " " . $player->lastname . "";
                                                echo "<div id='player_firstname" . $index . "' class='noshow'>".$player->firstname."</div>";
                                                echo "<div id='playerlastname" . $index . "' class='noshow'>".$player->lastname."</div>";
                                                echo "<br />";
                                                echo "" . $player->mobile . "";
                                                echo "<div id='player_modile" . $index . "' class='noshow'>".$player->mobile."</div>";
                                                echo "<br />";
                                                echo "" . $player->mail . "";
                                                echo "<div id='player_mail" . $index . "' class='noshow'>".$player->mail."</div>";

                                            echo "</div>";
                                        echo "</td>";                  

                                        //echo "<td class=''> <a href='#openModalEdit' class='myButton'>Edit</a></td>";
                                        echo "<td id='editrow'". $index ."><a href='#openModalEdit". $index . "'><img id='editPlayer' width='40' height='40' src='images/edit.png'></img></a></td>"; 
                                        
                                        echo "<td style='display: none;'> ID: " . $player->playerID . "</td>";
                                        
                                    echo "</tr>";
               
                                    //Modal dialog for player information editing///////////////
                                    echo "<div id='openModalEdit". $index . "' class='modalDialog'>";
	                                    echo "<div>";
		                                    echo "<a id='closer_edit". $index . "' href='#close' title='Close' class='close'>X</a>";


                                            echo "<form id='player_edit". $index . "' name='player_edit". $index . "' method='post' action='' target='frame_player' onsubmit='refreshPlayerInfo();'>";

                                                echo "<p style='margin: 10px;'>";
                                                echo "<label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%;'>Edit user status</label>";
                                                echo "</p>";

                                                echo "<p style='margin: 0px; padding-top: 10px;'>";
                                                echo "<label for='player_name". $index . "' style='display: inline-block; width: 60px; text-align: right;'>Name:&nbsp</label>";                    
                                                echo "<input type='text' id='dialog_player_name". $index . "' name='player_name". $index . "' value='". $player->firstname . " " . $player->lastname ."' required style='margin-bottom: 15px; width: 170px;' readonly></input>";
                                                echo "</p>";

                                                //echo "<p style='margin: 0px'>";
                                                //echo "<label for='player_email". $index . "' style='display: inline-block; width: 60px; text-align: right;'>Email:&nbsp</label>";
                                                //echo "<input type='text' id='dialog_player_email". $index . "' name='player_email". $index . "' value='". $player->mail ."' required style='margin-bottom: 15px; width: 190px;'></input>";
                                                //echo "</p>";

                                                //echo "<p style='margin: 0px'>";
                                                //echo "<label for='player_phone". $index . "' style='display: inline-block; width: 60px; text-align: right;'>Phone:&nbsp</label>";
                                                //echo "<input type='text' id='dialog_player_phone". $index . "' name='player_phone". $index . "' value='" . $player->mobile . "' required style='margin-bottom: 15px; width: 190px;'></input>";
                                                //echo "</p>";


                                                echo "<h5 id='dialog_player_notify". $index . "' class='dialog_player_notify'>Team Admin: </h5>";
                                                    if( $player->teamAdmin == 1) {
                                                        echo "<div class='onoffswitch notifyswitch' style='display: inline-block;'>";
						                                    echo "<input type='checkbox' name='adminswitch' class=\"onoffswitch-checkbox\" id='dialog_admin_switch". $index . "' checked>";					            
                                                            echo "<label class=\"onoffswitch-label\" for='dialog_admin_switch". $index . "' onClick=''>";
                                                                echo "<div class=\"notifyswitch-inner\"></div>";
						                                        echo "<div class=\"onoffswitch-switch\"></div>";
						                                    echo "</label>";
                                                        echo "</div>";
                                                    } else {
                                                        echo "<div class=\"onoffswitch notifyswitch\" style='display: inline-block;'>";
						                                    echo "<input type='checkbox' name='adminswitch' class=\"onoffswitch-checkbox\" id='dialog_admin_switch". $index . "'>";
                                                            echo "<label class=\"onoffswitch-label\" for='dialog_admin_switch". $index . "' onClick=''>";
                                                                echo "<div class=\"notifyswitch-inner\"></div>";
						                                        echo "<div class=\"onoffswitch-switch\"></div>";
						                                    echo "</label>";
                                                        echo "</div>";                            
                                                    }
                                                echo "</h5>";

                                                //echo "<div class='buttonHolder'>";
                                                //    echo "<input type='submit' value='Save' name='savebutton' id='savebutton_edit' class='dialog_button'>";
                                                //echo "</div>";
                                                
                                                echo "<div class='buttonHolder' style='margin-top:15px;'>";
                                                    echo "<input type='button' class='dialog_button' style='float: left; margin-left: 30px;' value='Save' onclick='updateAdminStatus(" . $player->playerID . ", \"dialog_admin_switch". $index . "\");'/>";
                                                    echo "<input type='button' class='dialog_button' style='color: red; float: rigth;' value='Delete' onclick='confirmDelete(" . $player->playerID . ");'/>";
                                                echo "</div>";

		                                    echo "</form>";

                                    //Modal dialog for player information editing//////////////////               
                            
                                    $index++;
                            }

                        echo "</table>";

                    echo "</div>"; //Scrollit

                echo "</div>"; //Member content
                //Members page///////////////////////////////////////////////////////////////////////////
        
            echo "<iframe name='frame_local' style='display: none'></iframe>";

            mysql_close($con);

        } //Admin



        class PlayerEdit {
            var $playerID;
            var $name;
            var $mobile;
            var $mail;
            var $photourl;
            var $notify;
            var $firstname;
            var $lastname;
            var $teamAdmin;

            function PlayerEdit($playerID, $name, $mobile, $mail, $photourl, $notify, $firstname, $lastname, $teamAdmin) {
                $this->playerID = $playerID;
                $this->name = $name;
                $this->mail = $mail;
                $this->mobile = $mobile;
                $this->photourl = $photourl;
                $this->notify = $notify;
                $this->firstname = $firstname;
                $this->lastname = $lastname;
                $this->teamAdmin = $teamAdmin;
            }

        }

?>