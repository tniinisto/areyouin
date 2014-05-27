<?php

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        //$time = date('r');
        echo "event: ayi\n";
        //echo "data: The server time is: {$time}\n\n";
        echo "data: e\n\n";
        sleep(10);
        
        /*function sendMsg()
        {
                echo "event: ayi\n";
                echo "data: event data changed\n\n";
                //echo PHP_EOL;
                ob_flush();
                flush();
        }*/
        
        /*while(1) {
                echo "event: ayi\n";
                echo "data: fetch events";
                //echo PHP_EOL;
                ob_flush();
                flush();        
                //sleep(60);
        }*/
        
        /*while (1) {
          // Every second, sent a "ping" event.
           
          echo "event: ping\n";
          $curDate = date(DATE_ISO8601);
          echo 'data: {"time": "' . $curDate . '"}';
          echo "\n\n";
           
          // Send a simple message at random intervals.
           
          $counter--;
           
          if (!$counter) {
                echo 'data: This is a message at time ' . $curDate . "\n\n";
                $counter = rand(1, 10);
          }
           
          ob_flush();
          flush();
          sleep(1);
        }*/        
        
?> 
