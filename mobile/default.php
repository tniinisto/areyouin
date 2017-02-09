<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">

  <meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0">
  <meta http-equiv="expires" content="-1">
  <meta http-equiv="pragma" content="no-cache">

<!-- disable iPhone inital scale -->
<!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta name="author" content="Tuomas Niinistö">

<!--Title-->
<title>R'YouIN</title>

<!-- Scripts main, jquery-->
<script type="text/javascript" charset="utf-8" src="main.js"> </script>
<!--<script src="http://code.jquery.com/jquery-2.0.0.min.js"></script>-->

<!--<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>-->
<script src="js/jquery-1.11.1.min.js"></script>

<script type="text/javascript" src="../js/spin.min.js"></script>

<!-- main css -->
<link href="style.css" rel="stylesheet" type="text/css">

<!-- media queries css -->
<link href="media-queries.css" rel="stylesheet" type="text/css">

<!--Toaster-->
<link href="../js/toastr.min.css" rel="stylesheet"/>
<script src="../js/toastr.min.js"></script>
<script src="../js/jquery.cookie.js"></script>

<!-- html5.js for IE less than 9 -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- css3-mediaqueries.js for IE less than 9 -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->

<!--<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-50815012-1', 'areyouin.azurewebsites.net');
  ga('send', 'pageview');
</script>-->

<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">

</head>

<body>

    <!--Check the php wakeness-->
    <?php 
    
        include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

        //session_start();

        $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        try {
            $result = 0;

            $sql2 = "SELECT teamName from team WHERE teamID = 0";
            
            $stmt2 = $dbh->prepare($sql2);        
            $result2 = $stmt2->execute();   
            $row2;

            while($row2 = $stmt2->fetch()) {
                //print_r($row);
                $result = $row2['teamName'];
            }
        }
        catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}'; 
            header('Location:default.php'); 
        }

        if($result == 0)
            header('Location:default.php');

     ?>


<div id="pagewrap">

    <div id="loginwrapper">

		<div>
        <h1 id="loginsite-logo" style="margin-top: 10px;">R'YouIN <img alt="mobile" width="30" height="30" src="images/icon-mobile.png" align="top"></h1>            			
        </div>

        <div id="spinnerlogin_id" class="spin"></div>

        <div>
		    <form id="loginform" name="loginform" method="post" action="logincheck.php">
            
                <fieldset id="loginfs">
                    <legend style="text-align: left; color: white;">Login</legend>
                    <!--<font color="white" size="3">-->
			    
                    <label style="color: white">User ID</label>
                    <input type="text" id="login" name="ayiloginName" placeholder="" required>
			                        
                    <label style="color: white">Password</label>
                    <input type="password" id="password" name="ayipassword" placeholder="" required>
            
                    <!--<br>-->
                    <div style="padding-top: 5px;"></div>
                    <input type="submit" value="Login" name="loginbutton" id="loginbutton" class='myButton' onClick="startLoginSpinner();">
                    
                    <div id="checklogin" class="noshow">
                        <br>
                        <label style="color: white">Check username and password</label>
                        <br>
                    </div>

	            </fieldset>
		    </form>

            <br>
            <br>
                <a style="color: white; text-decoration: underline;" href='#openModalPassword' id='passwordForgot'>Forgot my password</a>

        </div>
    </div>
</div>


<!--//Modal dialog for new password change///////////////-->
<div id='openModalPassword' class='modalDialog'>
	<div>
		<a id='closer_password' href='' title='Close' class='close'>X</a>

        <form id='password_forgot_form' name='passwordForm' method='post' action='' target='frame_player' onsubmit=''>

            <p style='margin: 10px;'>
                <label style='display: block; text-align: center; weight: bold; width: 100%; font-size: 125%; color: black;'>Enter your email address</label>
            </p>

            <p style='margin: 0px; padding-top: 10px;'>
                                                    
                <label for='pasword_send' style='display: inline-block; width: 60px; text-align: right; color: black; margin-left: -10px;'>Email:&nbsp</label>
                <input type='text' id='forgot_password' name='password_forgotten' value=''
                        style='margin-bottom: 15px; width: 160px; required'></input>
                </p>

                <p style="padding-left: 10px; color: black;">Email containing your new password will be sent to you in a moment.</p>

            <!--Button-->                                               
            <div class='buttonHolder' style='margin-top:15px;'>

                <input type='button' class='myButton' style='float: center;' value='Send' onclick="forgotPassword(password_forgotten.value);">

            </div>

		</form>
    </div>
</div>
<!--//Modal dialog for new password change///////////////-->

<script type="text/javascript">
    function loginFailed() {
        $("#checklogin").removeClass("noshow");
    }
</script>

<script  type="text/javascript">
    //Cookie
    $(document).ready(function() {

        //Clear session, local storage and php session data
        localStorage.clear();
        sessionStorage.clear();
        $.get("logout.php");        

        if (!$.cookie('noShowWelcome')) {

                //Toaster/////////////////////////////
                toastr.options = {
                  "closeButton": true,
                  "debug": false,
                  "newestOnTop": false,
                  "progressBar": false,
                  "positionClass": "toast-bottom-center",
                  "preventDuplicates": true,
                  "onclick": null,
                  "showDuration": "300",
                  "hideDuration": "1000",
                  "timeOut": "5000",
                  "extendedTimeOut": "3000",
                  "showEasing": "swing",
                  "hideEasing": "linear",
                  "showMethod": "fadeIn",
                  "hideMethod": "fadeOut"
                }

                toastr.info("R'YouIN application uses cookies. Please make sure that cookies are enabled on your browser. Thank you!", "Cookies")
                //Toaster/////////////////////////////

                //Set cookie
                //$.cookie('noShowWelcome', true);
                $.cookie('noShowWelcome', true, { expires: 30, path: '/', secure: true});
        }
    });
</script>

<script  type="text/javascript">
    var spinnerlogin;

    var opts = {
        lines: 15 // The number of lines to draw
        , length: 2 // The length of each line
        , width: 4 // The line thickness
        , radius: 10 // The radius of the inner circle
        , scale: 1 // Scales overall size of the spinner
        , corners: 1 // Corner roundness (0..1)
        , color: '#fff' // #rgb or #rrggbb or array of colors
        , opacity: 0.25 // Opacity of the lines
        , rotate: 0 // The rotation offset
        , direction: 1 // 1: clockwise, -1: counterclockwise
        , speed: 1 // Rounds per second
        , trail: 60 // Afterglow percentage
        , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
        , zIndex: 2e9 // The z-index (defaults to 2000000000)
        , className: 'spinner' // The CSS class to assign to the spinner
        , top: '100px;' // Top position relative to parent
        , left: '50%' // Left position relative to parent
        , shadow: false // Whether to render a shadow
        , hwaccel: false // Whether to use hardware acceleration
        , position: 'fixed' // Element positioning
    }

    //var target = document.getElementById('spinnerlogin_id');
    spinnerlogin = new Spinner(opts);

    function startLoginSpinner() {
        var target = document.getElementById('spinnerlogin_id');
        spinnerlogin.spin(target);
        
        //$("#loginchekSpinner").addClass("login_signal");
    }

    function stopSpinner() {
        spinnerlogin.stop();
        //$("#loginchekSpinner").removeClass("login_signal");
    }

    //Forgotten password/////////////////////////////////////////////////////
    function forgotPassword(mail) {

        if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else {// code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                stopSpinner();

                //Close modal dialog
                window.location.replace('#');

                if (xmlhttp.responseText.indexOf('1') > -1)
                    alert("Your password has been changed, check your email!");
                else
                    alert("R'YouIN did not find your mail from the system! Check the mail you entered and try again.");

            }
        }

        startLoginSpinner();

        var variables = "mail=" + mail;
        xmlhttp.open("GET", "forgotPassword.php?" + variables, true);
        xmlhttp.send();

    }

</script>

</body>
</html>