
<?php 
        session_start();

        //if ($_POST['uploader']) 
        //{ 
        //    // form has been submitted 
        //    uploadPhoto();
        //    //header('location:index.html?uploadAvatar=1');
        //    header('Location: ' . $_SERVER['HTTP_REFERER']);
        //} 
       
        //ini_set('upload_max_filesize', '10M');        

        //include 'ChromePhp.php';        
        //ChromePhp::log("players_insert, admin:", $ad);


        //Article///////////////////////////////////////////////////////////////////////////
        echo "<article id=\"profile_content_article\" class=\"clearfix \">";
        
            
        //Navigation///////////////////////////////////////////////////////////////////////////
        echo "<nav>";
			echo "<ul id=\"profile-nav\" class=\"clearfix\" onClick=\"profileClick()\">";
				echo "<li id=\"link_profile_profile\" class=\"current\"><a href=\"#\">Player</a></li>";
                echo "<li id=\"link_profile_team\"><a href=\"#\">Team</a></li>";
			echo "</ul>";
		echo "</nav>";
        //Navigation///////////////////////////////////////////////////////////////////////////

        //Profile tab
        echoProfile();
        
        //Team tab
        echoTeam();

        echo "</article>";
        //Article///////////////////////////////////////////////////////////////////////////


        //Profile content///////////////////////////////////////////////////////////////////   
        function echoProfile() {

            $playerid=$_SESSION['myplayerid'];

	        $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
	        if (!$con)
	          {
	          die('Could not connect: ' . mysql_error());
	          }

	        mysql_select_db("areyouin", $con);

            $sql = "SELECT * FROM players WHERE playerID = " . $playerid . "";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);


            $player = new Player($row[playerID], $row[name], $row[photourl]);


            echo "<div id=\"profile_profile_content_id\">";
                //echo "<h1>Player</h1>";
                //echo "</br>";
                //echo "PlayerID: " . $player->playerID . "</br>";
                echo "<h2>Name: " . $player->name . "</h2>";
                echo "<h2>Picture: <img width=\"50\" height=\"50\"\" src=\"images/" . $player->photourl . "\"></h2>";

                //echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"upload.php\">";
                //echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"" . $_SERVER[PHP_SELF] ."\">";
                //    echo "<div class=\"row\">";
                //        echo "<label for=\"fileToUpload\">Select a File to Upload</label><br />";
                //        echo "<input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\" />";
                //    echo "</div>";
                //    echo "<div class=\"row\">";
                //        echo "<input type=\"submit\" value=\"Upload\" name=\"uploader\"/>";
                //    echo "</div>";
                //echo "</form>";

            echo "</div>";
        }

        //Team content////////////////////////////////////////////////////////////////////
        function echoTeam() {
            echo "<div id=\"profile_team_content_id\" class=\"noshow\">";
                echo "<h1>Team</h1>";
            echo "</div>";            
        }

        //Upload/////////////////////////////////////////////////////////////////////////
        //function uploadPhoto()
        //{   
        //    if ($_FILES['fileToUpload']['error'] > 0) {
        //        //echo "Error: " . $_FILES['fileToUpload']['error'] . "<br />";
        //    } else {
        //        // array of valid extensions
        //        $validExtensions = array('.jpg', '.JPG', '.jpeg', '.gif', '.png');
        //        // get extension of the uploaded file
        //        $fileExtension = strrchr($_FILES['fileToUpload']['name'], ".");
        //        // check if file Extension is on the list of allowed ones
        //        if (in_array($fileExtension, $validExtensions)) {
        //            // we are renaming the file so we can upload files with the same name
        //            // we simply put current timestamp in fron of the file name
        //            $newName = time() . '_' . $_FILES['fileToUpload']['name'];
        //            $destination = 'images/' . $newName;
        //            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destination)) {
        //                //echo 'File ' .$newName. ' succesfully copied';
        //            }
        //        } else {
        //            //echo 'You must upload an image...';
        //        }
        //    }
        //}

        class Player {
            var $playerID;
            var $photourl;
            var $name;

            function Player($playerID, $name, $photourl) {
                $this->playerID = $playerID;
                $this->name = $name;
                $this->photourl = $photourl;
            }

        }

?>


