<?php


function sendMail($emailTo, $mail_user, $mail_key, $eventInfo) {

    include( $_SERVER['DOCUMENT_ROOT'] . '/config/config.php' );

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('mail_ayi.php, start');
    }
        
  
    ////SendGrid, Web API//////////////////////////////////////////////////////////    
    $url = 'https://api.sendgrid.com/';

    $params = array(
        'api_user' => $mail_user,
        'api_key' => $mail_key,
        //'x-smtpapi' => json_encode($json_string),
        //'to' => json_encode(array('tniinisto@gmail.com', 'tuomasniinisto@hotmail.com')),
        //'to' => 'tniinisto@gmail.com',
        'to' => $emailTo,
        'subject' => $eventInfo[subject],
        'html' => $eventInfo[content],
        'text' => 'Testing text body txt',
        'from' => 'RYouIN@ryouin.co',
    );

    $request = $url.'api/mail.send.json';
    if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php, $request: ', $request); }


    // Generate curl request
    $session = curl_init($request);

    // Tell curl to use HTTP POST
    curl_setopt ($session, CURLOPT_POST, true);

    // Tell curl that this is the body of the POST
    curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    // Set curl to avoid certificate errors
    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, FALSE);

    // obtain response
    $response = curl_exec($session);

    if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php, $response: ', $response, ' ,cURL errno: ', curl_errno($session), ' ,error: ', curl_error($session) ); }
    //$log = 'mail_ayi.php, $response: ' . $response . ' ,cURL errno: ' . curl_errno($session) . ' ,error: ' . curl_error($session);
    //if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php, $response: ', $log ); }

    curl_close($session);

    // print everything out
    print_r($response);

}

//function getEventInformation($eventID) {
//    
//    //select * from areyouin.events
//    //inner join areyouin.team on team.teamID = events.Team_teamID
//    //inner join areyouin.location on location.locationID = events.Location_locationID
//    //where events.eventID = 2282
//    //order by events.startTime desc;

//    $eventInfoArray = array(        
//        'subject' => "Subject field",
//        'content' => "<html><p>Checkout the game from <a href='http://areyouin.azurewebsites.net/'>AreYouIN</a></p></html>",
//    );

//    return $eventInfoArray;
//}

?>
