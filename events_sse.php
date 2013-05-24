<?php
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');

	//$time = date('r');
	//echo "data: The server time is: {$time}\n\n";

	/*function notify()
	{
		echo "data: event data changed\n\n";
		flush();
	}*/

	function notifyEventUpdate() {
	  echo "data: event data changed\n\n". PHP_EOL;
	  echo PHP_EOL;
	  ob_flush();
	  flush();
	}

?> 
