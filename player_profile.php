
<?php
        require_once('ImageManipulator.php');
        
        session_start();

        //$call = 0;
	    if ($_POST['Uploader']) // form has been submitted
	    { 
            uploadPhoto();

    	    //if(uploadPhoto() == -5) {
         //       ChromePhp::log("Upload -5");
         //       $call = -5;
    	    //}
        } 

        //include 'ChromePhp.php';        
        //ChromePhp::log("players_insert, admin:", $ad);
        
        echo "<html>";
        echo "<body>";
        
        //Article///////////////////////////////////////////////////////////////////////////
        echo "<article id=\"profile_content_article\" class=\"clearfix \">";
                    
        //Navigation///////////////////////////////////////////////////////////////////////////
        echo "<nav>";
			echo "<ul id=\"profile-nav\" class=\"clearfix\" onClick=\"profileClick()\">";
				echo "<li id=\"link_profile_profile\" class=\"current2\"><a href=\"#\">Player</a></li>";
                //echo "<li id=\"link_profile_team\"><a href=\"#\">Team</a></li>";
			echo "</ul>";
		echo "</nav>";
        //Navigation///////////////////////////////////////////////////////////////////////////

        //Profile tab
        echoProfile();
        
        //Team tab
        echoTeam();

        echo "</article>";
        //Article///////////////////////////////////////////////////////////////////////////

        echo "<iframe name=\"frame\" style=\"display: none;\"></iframe>";

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
                //echo "PlayerID: " . $player->playerID . "</br>";
                echo "<h4>Name: " . $player->name . "</h4>";
                echo "<h4>Picture</h4>";
                echo "<img width=\"50\" height=\"50\"\" class=\"seen\" src=\"images/" . $player->photourl . "\">";

                echo "</br>";
                echo "</br>";
                echo "</br>";

                //FORM/////////////////////////////////////////
                echo "<h4>Upload new photo (Max size 2MB)</h4>";
                echo "</br>";
                echo "<form action=\"" . $_SERVER[PHP_SELF] . "\" method=\"post\" enctype=\"multipart/form-data\" id=\"MyUploadForm\" target=\"frame\">";
                echo "<input name=\"FileInput\" id=\"FileInput\" type=\"file\" />";
                echo "<input type=\"submit\"  id=\"submit-btn\" value=\"Upload\" name=\"Uploader\"/>";
                echo "</form>";
                
                //echo "<h4 name=\"ImageSize\" id=\"ImageSizeId\" class=\"noshow\">Your image is too big!</h4>";

            echo "</div>";
        }

        //Team content////////////////////////////////////////////////////////////////////
        function echoTeam() {
            echo "<div id=\"profile_team_content_id\" class=\"noshow\">";
                echo "<h1>Team</h1>";
            echo "</div>";            
        }

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

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function uploadPhoto() {
            
            //require_once('ImageManipulator.php');

            include 'ChromePhp.php';        
            //ChromePhp::log("players_insert, admin:", $ad);
            ChromePhp::log("processupload starts...");

            if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK)
            {
	            ChromePhp::log("processupload 1...");

                ############ Edit settings ##############
	            $UploadDirectory	= 'images/'; //specify upload directory ends with / (slash)
	            ##########################################
	
	            /*
	            Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
	            Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
	            and set them adequately, also check "post_max_size".
	            */
	
	            //check if this is an ajax request
	            //if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		           // ChromePhp::log("processupload ajax...");
             //       die();
	            //}
	
	
	            //Is file size is less than allowed size.
	            if ($_FILES["FileInput"]["size"] > 2000000) {
		            ChromePhp::log("processupload too big...");
                    //return -5;                    
                    die("File size is too big!");
	            }
	
	            //allowed file type Server side check
	            switch(strtolower($_FILES['FileInput']['type']))
		            {
			            //allowed file types
                        case 'image/png': 
			            case 'image/gif': 
			            case 'image/jpeg': 
			            case 'image/pjpeg':
			            case 'text/plain':
			            case 'text/html': //html file
			            case 'application/x-zip-compressed':
			            case 'application/pdf':
			            case 'application/msword':
			            case 'application/vnd.ms-excel':
			            case 'video/mp4':
                        case 'image/JPG': 
				            break;
			            default:
				            die('Unsupported File!'); //output error
	            }
	
	            $File_Name          = strtolower($_FILES['FileInput']['name']);
	            $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
	            $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
	            $NewFileName 		= $Random_Number.$File_Ext; //new file name

                //Save file to folder///////////////////////////////////////////////
                if(1) {

                    $newNamePrefix = time() . '_';
                    $manipulator = new ImageManipulator($_FILES['FileInput']['tmp_name']);
                    // resizing to 60x60
                    $newImage = $manipulator->resample(50, 50);
                    // saving file to uploads folder
                    $manipulator->save('images/' . $newNamePrefix . $_FILES['FileInput']['name']);
                    echo 'Done ...';

                    //Update database////////////////////////////////////////////////////////////////////////
                    //echo "<script type=\"text/javascript\" src=\"main.js\"> </script>";
                    //echo "<script type=\"text/javascript\">";
                    //echo "alert(\"jepase\");";
                    //echo "updatePlayerPhoto(" . $newNamePrefix . $_FILES['FileInput']['name'] . ");";
                    //echo "</script>";
          
   $playerid=$_SESSION['myplayerid'];

   $con = mysql_connect('eu-cdbr-azure-north-a.cloudapp.net', 'bd3d44ed2e1c4a', '8ffac735');
    if (!$con)
        {
        die('Could not connect: ' . mysql_error());
        }

    mysql_select_db("areyouin", $con)or die("cannot select DB");


    $sql = "UPDATE players SET photourl = \"" . $newNamePrefix . $_FILES['FileInput']['name'] . "\" WHERE playerID = " . $playerid . "";
    //ChromePhp::log('Update: ' . $sql3);
    $result = mysql_query($sql);

                } 
                else {
                    echo 'You must upload an image...';
                }
                }
            else
            {
	            ChromePhp::log("processupload die...");
                die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
            }

        }

?>

