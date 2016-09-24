<?php

    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );
            
    $timezone_identifiers = DateTimeZone::listIdentifiers();
    echo "<label>Select your timezone:</label>";                    
    //echo "<select id='timezone_select' name='timezone_select' form='timezones' onchange=showTimezone(this.value)>";
    
    echo "<div align='center'>";
    echo "<select id='timezone_select' name='timezone_select' form='timezones' style='text-align: center;'>";
        for ($i=0; $i < sizeof($timezone_identifiers); $i++) {
            echo "<option value=\"" . $timezone_identifiers[$i] . "\">" . $timezone_identifiers[$i] . "</option>";
        }
    echo "</select>";
    echo "</div>";

?>