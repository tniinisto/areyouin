<?php


//$time = date('r');
//echo "data: The server time is: {$time}\n\n";

function notify()
{
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	echo "data: event data changed\n\n";
	flush();
}

?> 
