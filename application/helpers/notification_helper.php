<?php
defined('BASEPATH') OR exit('No direct script access allowed');
		//This function is used to check the validation of the input fields
		function checkRequired($array){
				$requried = "";
			foreach($array as $key => $value) {
				if ($value==''){
					$requried .= $key.',';
				}
			}
			if($requried!=""){
				return rtrim($requried,',');
			}
			else{
				return false;
			}
		}


	//This function is used to send the notification on Android device
		function andiPush($token,$message,$section){

			$token = $token;
			$message = addslashes($message);
			//$badge = $badge;

			// Replace with the real server API key from Google APIs
			$apiKey = "AAAAshm-Uc0:APA91bH9ad4VAdTdOUHPwxKoZx9ZVXWbBN_bUhnhcS9cqgXYB187T03HoIlbjdGMk8jXDez7Ox0V_tDDYOHLuhlxB3oj8iFk6ryDItqwAAtrDO_RhtpRfrMPh-HsmgqxjfifylOyh4gW";

			// Set POST variables
			//$url = 'https://android.googleapis.com/gcm/send';
			$url = 'https://fcm.googleapis.com/fcm/send';

			$fields = array(
			'registration_ids'  => array($token),
			'data'              => array( "message" => $message,'badge'=>'1','section'=>$section)
			);
			//,'joinGroupId'=>$joinGroupLastInserId
			//print_r($fields);exit;
			$headers = array(
			'Authorization: key=' . $apiKey,
			'Content-Type: application/json'
			);

			// Open connection
			$ch = curl_init();
			// Set the url, number of POST vars, POST data
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
			// Execute post
			$result = curl_exec($ch);
			// Close connection
			curl_close($ch);
			return $res = json_decode($result,true);
			//print_R($res);
			//exit;
			/*return $var['success'];*/
	}
	//This function is used to send the push notification
	function applePush($token,$message,$badge=0,$section){
			$deviceToken = $token;
			// Put your private key's passphrase here:
			$passphrase = '123';

			// Put your alert message here:
			$message = $message;

			////////////////////////////////////////////////////////////////////////////////

			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', './certificate/ck.pem');
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

			// Open a connection to the APNS server
			// $fp = stream_socket_client(
			// 'ssl://gateway.sandbox.push.apple.com:2195', $err,
			// $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr,60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			// if (!$fp)
			// exit("Failed to connect: $err $errstr" . PHP_EOL);

			// echo 'Connected to APNS' . PHP_EOL;

			// Create the payload body
			$body['aps'] = array(
			'alert' => $message,
			'sound' => 'default',
			'badge'=>$badge,
			'section'=>$section,
			'content-available'=>'1'
			);

			// Encode the payload as JSON
			$payload = json_encode($body);

			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));
			// Close the connection to the server
			//fclose($fp);

			if (!$result)
			return false;
			//echo 'Message not delivered' . PHP_EOL;
			else
			return true;
			//echo 'Message successfully delivered' . PHP_EOL;
		fclose($fp);
			}
			##############
function bannerCategory($code){
	$http_status_codes = array(
    101 => 'For You',
    102 => 'Latest',
     103 => 'Top',
    1 => 'Health',
    2 => 'Business',
    3 => 'Mindfulness',
    4 => 'Religion',
    5 => 'Fitness',
    6 => 'Money',
    7 => 'Love',
    8 => 'Relationship',
    9 => 'Investing',
    10 => 'Mindset',
    11 => '',
);
	return $http_status_codes[$code];
}

## http status code

function http_code_message($code){
	$http_status_codes = array(
    100 => 'Informational: Continue',
    101 => 'Informational: Switching Protocols',
    102 => 'Informational: Processing',
    200 => 'Successful: OK',
    201 => 'Successful: Created',
    202 => 'Successful: Accepted',
    203 => 'Successful: Non-Authoritative Information',
    204 => 'Successful: No Content',
    205 => 'Successful: Reset Content',
    206 => 'Successful: Partial Content',
    207 => 'Successful: Multi-Status',
    208 => 'Successful: Already Reported',
    226 => 'Successful: IM Used',
    300 => 'Redirection: Multiple Choices',
    301 => 'Redirection: Moved Permanently',
    302 => 'Redirection: Found',
    303 => 'Redirection: See Other',
    304 => 'Redirection: Not Modified',
    305 => 'Redirection: Use Proxy',
    306 => 'Redirection: Switch Proxy',
    307 => 'Redirection: Temporary Redirect',
    308 => 'Redirection: Permanent Redirect',
    400 => 'Client Error: Bad Request',
    401 => 'Unauthorized user.',
    402 => 'Unauthorized user.',
    403 => 'Client Error: Forbidden',
    404 => 'Client Error: Not Found',
    405 => 'Client Error: Method Not Allowed',
    406 => 'Client Error: Not Acceptable',
    407 => 'Client Error: Proxy Authentication Required',
    408 => 'Client Error: Request Timeout',
    409 => 'Client Error: Conflict',
    410 => 'Client Error: Gone',
    411 => 'Client Error: Length Required',
    412 => 'Client Error: Precondition Failed',
    413 => 'Client Error: Request Entity Too Large',
    414 => 'Client Error: Request-URI Too Long',
    415 => 'Client Error: Unsupported Media Type',
    416 => 'Client Error: Requested Range Not Satisfiable',
    417 => 'Client Error: Expectation Failed',
    418 => 'Client Error: I\'m a teapot',
    419 => 'Client Error: Authentication Timeout',
    420 => 'Client Error: Enhance Your Calm',
    420 => 'Client Error: Method Failure',
    422 => 'Client Error: Unprocessable Entity',
    423 => 'Client Error: Locked',
    424 => 'Client Error: Failed Dependency',
    424 => 'Client Error: Method Failure',
    425 => 'Client Error: Unordered Collection',
    426 => 'Client Error: Upgrade Required',
    428 => 'Client Error: Precondition Required',
    429 => 'Client Error: Too Many Requests',
    431 => 'Client Error: Request Header Fields Too Large',
    444 => 'Client Error: No Response',
    449 => 'Client Error: Retry With',
    450 => 'Client Error: Blocked by Windows Parental Controls',
    451 => 'Client Error: Redirect',
    451 => 'Client Error: Unavailable For Legal Reasons',
    494 => 'Client Error: Request Header Too Large',
    495 => 'Client Error: Cert Error',
    496 => 'Client Error: No Cert',
    497 => 'Client Error: HTTP to HTTPS',
    499 => 'Client Error: Client Closed Request',
    500 => 'Server Error: Internal Server Error',
    501 => 'Server Error: Not Implemented',
    502 => 'Server Error: Bad Gateway',
    503 => 'Server Error: Service Unavailable',
    504 => 'Server Error: Gateway Timeout',
    505 => 'Server Error: HTTP Version Not Supported',
    506 => 'Server Error: Variant Also Negotiates',
    507 => 'Server Error: Insufficient Storage',
    508 => 'Server Error: Loop Detected',
    509 => 'Server Error: Bandwidth Limit Exceeded',
    510 => 'Server Error: Not Extended',
    511 => 'Server Error: Network Authentication Required',
    598 => 'Server Error: Network read timeout error',
    599 => 'Server Error: Network connect timeout error',
);
	return $http_status_codes[$code];
}
## Get any data of any table
function getAnything($table,$cond=array(),$whatever){
	$CI =& get_instance();
	$CI->db->select($whatever)->from($table);
	if(count($cond)){
		$CI->db->where($cond);
	}
	$check	=	$CI->db->get()->result_array();
	//echo $CI->db->last_query();exit;
	if($check){
		return $check;
	}else{
		return [];
	}
}

?>