<?php
    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('mail_ayi.php, start');
    }

    $user=$_POST['mail_user'];
    $pass=$_POST['mail_pass'];
    if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php, $user: ', $user, ' @pass: ', $pass); }

    //SendGrid, Web API//////////////////////////////////////////////////////////    
    $url = 'http://api.sendgrid.com/';

    $params = array(
        'api_user' => $user,
        'api_key' => $pass,
        'to' => 'tniinisto@gmail.com',
        'subject' => 'testing from AreYouIN',
        'html' => 'testing html body html',
        'text' => 'testing text body txt',
        'from' => 'tniinisto@gmail.com',
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

    // obtain response
    $response = curl_exec($session);
    curl_close($session);

    // print everything out
    if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php, $response: ', $response); }

    print_r($response);



    //SendGrid's PHP Library - https://github.com/sendgrid/sendgrid-php///////////////////////////////
    //require_once 'sendgrid/sendgrid-php.php';

    //$sendgrid = new SendGrid($mail_user, $mail_key);
    //$email    = new SendGrid\Email();

    //$email->addTo("tniinisto@gmail.com")
    //      ->setFrom("areyouin@areyouin.org")
    //      ->setSubject("Sending with SendGrid is Fun")
    //      ->setHtml("and easy to do anywhere, even with PHP");

    //$sendgrid->send($email);

?>
