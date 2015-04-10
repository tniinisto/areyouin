<?php


function sendMail($emailTo, $mail_user, $mail_key) {

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
        'subject' => 'AreYouIN, New game',
        'html' => '<html><p>Checkout the game from <a href="http://areyouin.azurewebsites.net/">AreYouIN</a></p></html>',
        'text' => 'Testing text body txt',
        'from' => 'AreYouIN@Puonti',
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

?>
