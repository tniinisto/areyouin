<?php

// Check if session is not registered, redirect back to main page. 
// Put this code in first line of web page. 

session_start();

if(!session_is_registered(myusername)){
header("location:default.html");
}
else {
    //header("location:index.html");
    //header("location:index.html?userid=" . $row[playerID] . "&username=$myusername&teamid=" . $row[teamID] . "&teamname=" . $row[teamName]);
    echo "logged in with username:".$_SESSION[0];
    echo "<br>";
    echo "<h3> PHP List All Session Variables</h3>";
    
    foreach ($_SESSION as $key=>$val)
        echo $key." ".$val."<br/>";
    
    echo "<br>";
    //echo "user=". $_SESSION['myusername'];
}

?>

<html>
<body>
Login Successful
</body>
</html>
