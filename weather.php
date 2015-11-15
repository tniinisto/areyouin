<?php
       include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );      
       session_start();

       $con = mysql_connect($dbhost, $dbuser, $dbpass);
	    if (!$con)
	      {
	      die('Could not connect: ' . mysql_error());
	      }

	    mysql_select_db("areyouin", $con);

        //Weather info///////////////////////////////////////////////////////////////////        
        $sql_weather = "select distinct name, position from location l, team t where l.teamID = " . $_SESSION['myteamid'] . " and t.showWeather = 1 and l.teamID = t.teamID";
        $result_weather = mysql_query($sql_weather);
	
        while($row_weather = mysql_fetch_array($result_weather)) {

            $latlon = explode(", ", $row_weather['position']);

            //echo "<aside id='" . $row_weather['name'] . "' class='sidebar'>";
                //echo "<article id=\"event_article_id\" class='event_article clearfix'>";
                    echo "<div style='width=100%;'>";
                        echo "<iframe 
            	            id='forecast_embed'
	                        type='text/html'
	                        frameborder='0'
	                        height='245'
	                        width='100%'
	                        src='http://forecast.io/embed/#lat=" . str_replace(' ', '', $latlon[0]) . "&lon=" . str_replace(' ', '', $latlon[1]) . "&name=" . $row_weather['name'] . "&color=#00aaff&font=Georgia&units=si'>         
                        </iframe>";
                    echo "</div>";
                //echo "</article>";
            //echo "</aside>";            
	    }
        /////////////////////////////////////////////////////////////////////////////////

        mysql_close($con);
?>

