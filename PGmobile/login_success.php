<?php     
    session_start();

    // Check if session is not registered, redirect back to main page. 
    // Put this code in first line of web page. 

    //if(!$_SESSION['myusername']){

        //if($_SESSION['ChromeLog']) { ChromePhp::log('login_success.php, not session_is_registered'); }

        //header("location:default.php");
    //}
    //else {
        //if($_SESSION['ChromeLog']) { ChromePhp::log('login_success.php, session_is_registered'); }

        //header("location:index.html");

        include($_SERVER['DOCUMENT_ROOT']."/pgmobile/index.html");

        //$index = file_get_contents('index.html');
        //echo $index;

        // ob_start(); // begin collecting output
        // include 'index.html';
        // $result = ob_get_clean(); // retrieve output from myfile.php, stop buffering
        // echo $result;       <html>

        // echo "<head>";
        // echo "    <script type='text/javascript'>";
        // echo "        window.location.href = 'file:///index.html';";
        // echo "    </script>";
        // echo "</head>";
        // echo "<body>";
        // echo "</body>";
        // echo "</html>";

    }

?>
