
<?php 
        session_start();

        //echo "<!DOCTYPE HTML>";
        //echo "<html>";

        //echo "<head>";

        echo "<script type=\"text/javascript\">";
        echo "$(document).ready(function() {"; 
        echo "var options = { ";
        echo "target:   '#output',   // target element(s) to be updated with server response";
        echo "beforeSubmit:  beforeSubmit,  // pre-submit callback";
        echo "success:       afterSuccess,  // post-submit callback"; 
        echo "uploadProgress: OnProgress, //upload progress callback"; 
        echo "resetForm: true        // reset the form after successful submit"; 
        echo "};"; 

        echo "$('#MyUploadForm').submit(function() {"; 
        echo "$(this).ajaxSubmit(options);";  			
        echo "// always return false to prevent standard browser submit and page navigation";
        echo "return false;"; 
        echo "});"; 

        echo "//function after succesful file upload (when server response)";
        echo "function afterSuccess()";
        echo "{";
        echo "$('#submit-btn').show(); //hide submit button";
        echo "$('#loading-img').hide(); //hide submit button";
        echo "$('#progressbox').delay( 1000 ).fadeOut(); //hide progress bar";
        echo "}";

        echo "//function to check file size before uploading.";
        echo "function beforeSubmit(){";
        echo "//check whether browser fully supports all File API";
        echo "if (window.File && window.FileReader && window.FileList && window.Blob)";
        echo "{";
        echo "if( !$('#FileInput').val()) //check empty input filed";
        echo "{";
        echo "$(\"#output\").html(\"Are you kidding me?\");";
        echo "return false";
        echo "}\";}";

        echo "var fsize = $('#FileInput')[0].files[0].size;";
        echo "var ftype = $('#FileInput')[0].files[0].type;";

        echo "//allow file types"; 
        echo "switch(ftype)";
        echo "{";
        echo "case 'image/png':"; 
        echo "case 'image/gif':"; 
        echo "case 'image/jpeg':"; 
        echo "case 'image/pjpeg':";
        echo "case 'text/plain':";
        echo "case 'text/html':";
        echo "case 'application/x-zip-compressed':";
        echo "case 'application/pdf':";
        echo "case 'application/msword':";
        echo "case 'application/vnd.ms-excel':";
        echo "case 'video/mp4':";
        echo "break;";
        echo "default:";
        echo "$(\"#output\").html(\"<b>\"+ftype+\"</b> Unsupported file type!\");";
        echo "return false";
        echo "}";		

        echo "if(fsize>5242880)";
        echo "{";
        echo "$(\"#output\").html(\"<b>\"+bytesToSize(fsize) +\"</b> Too big file! <br />File is too big, it should be less than 5 MB.\");";
        echo "return false";
        echo "}";
	
        echo "$('#submit-btn').hide(); //hide submit button";
        echo "$('#loading-img').show(); //hide submit button";
        echo "$(\"#output\").html(\"\");";  
        echo "}";
        echo "else";
        echo "{";
        echo "//Output error to older unsupported browsers that doesn't support HTML5 File API";
        echo "$(\"#output\").html(\"Please upgrade your browser, because your current browser lacks some new features we need!\");";
        echo "return false;";
        echo "}";
        echo "}";

        echo "//progress bar function";
        echo "function OnProgress(event, position, total, percentComplete)";
        echo "{";
        echo "//Progress bar";
        echo "$('#progressbox').show();";
        echo "$('#progressbar').width(percentComplete + '%') //update progressbar percent complete";
        echo "$('#statustxt').html(percentComplete + '%'); //update status text";
        echo "if(percentComplete>50)";
        echo "{";
        echo "$('#statustxt').css('color','#000'); //change status text to white after 50%";
        echo "}";
        echo "}";

        echo "//function to format bites bit.ly/19yoIPO";
        echo "function bytesToSize(bytes) {";
        echo "var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];";
        echo "if (bytes == 0) return '0 Bytes';";
        echo "var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));";
        echo "return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];";
        echo "}";
        echo "});"; 

        echo "</script>";
        //echo "</head>";

        //echo "<body>";

  //      //if ($_POST['uploader']) 
  //      //{ 
  //      //    // form has been submitted 
  //      //    uploadPhoto();
  //      //    //header('location:index.html?uploadAvatar=1');
  //      //    header('Location: ' . $_SERVER['HTTP_REFERER']);
  //      //} 
  //     
  //      //ini_set('upload_max_filesize', '10M');        

  //      //include 'ChromePhp.php';        
  //      //ChromePhp::log("players_insert, admin:", $ad);


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

        echo "<iframe name=\"frame\" style=\"display: none;\"></iframe>";

        //echo "</body>";
        //echo "</html>";

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


                echo "<h3>Ajax File Uploader</h3>";
                echo "<form action=\"processupload.php\" method=\"post\" enctype=\"multipart/form-data\" id=\"MyUploadForm\" target=\"frame\">";
                echo "<input name=\"FileInput\" id=\"FileInput\" type=\"file\" />";
                echo "<input type=\"submit\"  id=\"submit-btn\" value=\"Upload\" />";
                echo "<img src=\"images/ajax-loader.gif\" id=\"loading-img\" style=\"display:none;\" alt=\"Please Wait\"/>";
                echo "</form>";
                //echo "<div id=\"progressbox\" ><div id=\"progressbar\"></div ><div id=\"statustxt\">0%</div></div>";
                //echo "<div id=\"output\"></div>";

  //              //echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"upload.php\">";
  //              //echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"" . $_SERVER[PHP_SELF] ."\">";
  //              //    echo "<div class=\"row\">";
  //              //        echo "<label for=\"fileToUpload\">Select a File to Upload</label><br />";
  //              //        echo "<input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\" />";
  //              //    echo "</div>";
  //              //    echo "<div class=\"row\">";
  //              //        echo "<input type=\"submit\" value=\"Upload\" name=\"uploader\"/>";
  //              //    echo "</div>";
  //              //echo "</form>";

            echo "</div>";
        }

        //Team content////////////////////////////////////////////////////////////////////
        function echoTeam() {
            echo "<div id=\"profile_team_content_id\" class=\"noshow\">";
                echo "<h1>Team</h1>";
            echo "</div>";            
        }

  //      //Upload/////////////////////////////////////////////////////////////////////////
  //      //function uploadPhoto()
  //      //{   
  //      //    if ($_FILES['fileToUpload']['error'] > 0) {
  //      //        //echo "Error: " . $_FILES['fileToUpload']['error'] . "<br />";
  //      //    } else {
  //      //        // array of valid extensions
  //      //        $validExtensions = array('.jpg', '.JPG', '.jpeg', '.gif', '.png');
  //      //        // get extension of the uploaded file
  //      //        $fileExtension = strrchr($_FILES['fileToUpload']['name'], ".");
  //      //        // check if file Extension is on the list of allowed ones
  //      //        if (in_array($fileExtension, $validExtensions)) {
  //      //            // we are renaming the file so we can upload files with the same name
  //      //            // we simply put current timestamp in fron of the file name
  //      //            $newName = time() . '_' . $_FILES['fileToUpload']['name'];
  //      //            $destination = 'images/' . $newName;
  //      //            if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $destination)) {
  //      //                //echo 'File ' .$newName. ' succesfully copied';
  //      //            }
  //      //        } else {
  //      //            //echo 'You must upload an image...';
  //      //        }
  //      //    }
  //      //}

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


