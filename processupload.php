<?php
    require_once('ImageManipulator.php');

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
	    if ($_FILES["FileInput"]["size"] > 5242880) {
		    ChromePhp::log("processupload too big...");
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

	    //if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
	    //{
        //       ChromePhp::log("processupload move file...");

		   // die('Success! File Uploaded.');
        //                   
	    //}else{
        //       ChromePhp::log("processupload error uploading...");

		   // die('error uploading File!');
	    //}

        if(1) {

            $newNamePrefix = time() . '_';
            $manipulator = new ImageManipulator($_FILES['FileInput']['tmp_name']);
            // resizing to 60x60
            $newImage = $manipulator->resample(60, 60);
            // saving file to uploads folder
            $manipulator->save('images/' . $newNamePrefix . $_FILES['FileInput']['name']);
            echo 'Done ...';

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

?>