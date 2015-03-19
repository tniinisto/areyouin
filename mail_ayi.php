<?php
    include 'json/config.php';

    session_start();

    if($_SESSION['ChromeLog']) {
        require_once 'ChromePhp.php';
        ChromePhp::log('mail_ayi.php, start');
    }

    $user=$_POST['mail_user'];
    $pass=$_POST['mail_pass'];
    if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php, $user: ', $user, ' @pass: ', $pass); }

    ////SendGrid, Web API//////////////////////////////////////////////////////////    
    $url = 'https://api.sendgrid.com/';

    $params = array(
        'api_user' => $mail_user,
        'api_key' => $mail_key,
        'to' => 'tniinisto@gmail.com',
        'subject' => 'testing from AreYouIN',
        'html' => '<html><p>Testing html <b>body</b></p></html>',
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





//$apiUrl = "https://sendgrid.com/api/mail.send.json";///////////////////////////////////////////////
// 
//$dateTime = date('Y/m/d h:i:s');
// 
//$sendGridParams = array(
//	'api_user' => $user
//	,'api_key' => $pass
//	,'to' => 'CustomerEmail@gmail.com'
//	,'toname' => 'Customer Name'
//	,'subject' => 'Test - ' . $dateTime 
//	,'html' => 'This is a <B>test!</B> <BR> Sent: '. $dateTime
//	,'from' => 'DoNotRepl@YourCompanyWebsite.com'
//	,'fromname' => 'Your Company'
////optional add'l email headers
//	//,'headers' => json_encode(array('X-Accept-Language'=>'en')) 
//);
// 
//$query = http_build_query($sendGridParams);
// 
//$curl = curl_init();
// 
//curl_setopt_array($curl, array(
//	CURLOPT_URL => $apiUrl . '?' . $query
//	,CURLOPT_RETURNTRANSFER => true
//	,CURLOPT_SSL_VERIFYPEER => true
//	,CURLOPT_SSL_VERIFYHOST => 2
////download latest CA bundle from http://curl.haxx.se/docs/caextract.html
//	,CURLOPT_CAINFO => dirname(__FILE__) . '\cacert.pem' 
//));
// 
//if(FALSE === $curlResponse = curl_exec($curl)){
//    if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php FALSE, $response: ', $curlResponse); }
//	die("API call failed! cURL error " . curl_errno($curl) . " " . curl_error($curl));
//}
//curl_close($curl);
// 
//if(NULL === $decodedResponse = json_decode($curlResponse,true)){
//    if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php NULL, $response: ', $curlResponse); }
//	die("Error decoding API response, raw text: " . $curlResponse);
//}
// 
//if( $decodedResponse['message'] === "success"){
//    if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php SUCCESS, $response: ', $curlResponse); }
//	echo "E-Mail Sent!";
//}else{
//	if($_SESSION['ChromeLog']) { ChromePhp::log('mail_ayi.php ERRORS, $response: ', $curlResponse); }
//    echo "API Returned errors! <BR>";
//	if(is_array($decodedResponse['errors'])){
//		foreach($decodedResponse['errors'] as $error){
//			echo $error , "<BR>";
//		}
//	}
//}






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
