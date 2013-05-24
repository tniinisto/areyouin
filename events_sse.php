<?php
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	//$time = date('r');
	//echo "data: The server time is: {$time}\n\n";
	
	//function sendMsg()
	//{
		echo "data: AYI event data changed\n\n";
		ob_flush();
		flush();
	//}
	
?> 
