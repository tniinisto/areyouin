
<?php
        require_once('ImageManipulator.php');
        
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
                
                echo "<div id=\"output\">";
                echo "<img width=\"50\" height=\"50\"\" class=\"seen\" src=\"images/" . $player->photourl . "\">";
                echo "</div>";
                
                //echo "<div id=\"output\"  class=\"nomobile\">";
                echo "<div class=\"nomobile\">";
                    echo "</br>";
                    echo "</br>";
                    echo "</br>";

                    //FORM/////////////////////////////////////////
                    echo "<h4>Upload new photo (Max size 2MB)</h4>";
                    echo "</br>";
                    //echo "<form action=\"" . $_SERVER[PHP_SELF] . "\" method=\"post\" enctype=\"multipart/form-data\" id=\"MyUploadForm\" target=\"frame\">";
                    echo "<form action=\"processupload.php\" method=\"post\" enctype=\"multipart/form-data\" id=\"MyUploadForm\" target=\"frame\">";
                    echo "<input name=\"FileInput\" id=\"FileInput\" type=\"file\" />";
                    echo "<input type=\"submit\"  id=\"submit-btn\" value=\"Upload\" name=\"Uploader\"/>";
                    echo "</form>";
                    echo "</br>";
                    echo "<div id=\"progressbox\">";
                        echo "<div id=\"progressbar\"></div >";
                        echo "<div id=\"statustxt\">0%</div>";
                    echo "</div>";
                echo "</div>";
                //echo "<h4 name=\"ImageSize\" id=\"ImageSizeId\" class=\"noshow\">Your image is too big!</h4>";

                //echo "<div id=\"output\"></div>";

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

?>

