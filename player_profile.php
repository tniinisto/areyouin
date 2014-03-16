<?php
        session_start();
        
        //$teamid=1;
        $teamid=$_SESSION['myteamid'];
        $ad=$_SESSION['myAdmin'];

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


    
        function echoProfile() {
            echo "<div id=\"profile_profile_content_id\">";
                echo "<h1>Player</h1>";
            echo "</div>";
        }


        function echoTeam() {
            echo "<div id=\"profile_team_content_id\" class=\"noshow\">";
                echo "<h1>Team</h1>";
            echo "</div>";            
        }

?>