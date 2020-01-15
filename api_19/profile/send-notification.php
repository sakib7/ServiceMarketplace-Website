<?php
header('Content-Type: application/json');
require_once '../../includes/connection.php';

$rawbody = file_get_contents("php://input");
$rawobj = json_decode($rawbody);
$token = $rawobj->token;
$title = $rawobj->title;
$message = $rawobj->message;
send_notification($token,$title,$message);
function send_notification($notify_token,$notify_title, $notify_message){
	echo $notify_token."<br>".$notify_title.$notify_message."<br>";
	$url = "https://fcm.googleapis.com/fcm/send";
	$server_key = "AAAAze6fCoQ:APA91bHJlg7SDx1QRiQ9E3eFG21GIncTfPczbHlqQe9Dl_HtZEl66MSNlrSRbWM1rfe9zK0b8ddvt7wZ0EY5iHTGDtXzfrPXNJ67dxBBGuK-1UfTdh2i5jexsd_oaWVcvowDVI9pkEhF";
	$headers = array(
				'Content-Type:application/json',
				'Authorization:key='.$server_key
			);
	$fields = array(
				'to' => $notify_token,
				'data' => array('title' => $notify_title, 
										'body' => $notify_message, 
										'sound' => 'default',
										'color' => '#00aff0'
									)
			);
	$payload = json_encode($fields);

	$curl_session = curl_init();
	curl_setopt($curl_session, CURLOPT_URL, $url);
	curl_setopt($curl_session, CURLOPT_POST, true);
	curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl_session, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload );

	$result = curl_exec($curl_session);
	echo $result;
}