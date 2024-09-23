<?php
include 'admin.php';
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;


$admin = new Admin();

// $account_sid = "ACe7fb0d1b5fa2212c4137fb8653261463";
// $auth_token = "d8f6de152838e5c1326e689debbd3ea9";

// // A Twilio number you own with SMS capabilities
// $twilio_number = "+18133363443";

// $client = new Client($account_sid, $auth_token);
// $message = $client->messages->create(
//     // Where to send a text message (your cell phone?)
//     '+639955835160',
//     array(
//         'from' => $twilio_number,
//         'body' => 'SABAY SABAY TAYONG BIBITAWW'
//     )
// );

// if($message) {
//     echo 'Sent to Phone number';
// } else {
//     echo 'Text to Number Failed';
// }



// Initialize a cURL session
$ch = curl_init();

// Set the parameters for the API request
$parameters = array(
    'apikey' => '2c26226aae5c0438695f2e851d4482e9', // Replace with your actual API key
    'number' => '9398380417',  // Replace with the recipient's number
    'message' => 'I just sent my first message with Semaphore',
    'sendername' => 'SEMAPHORE' // Replace with your registered sender name
);

// Set cURL options for the request
curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL request and get the response
$output = curl_exec($ch);

// Close the cURL session
curl_close($ch);

// Output the response from Semaphore
echo $output;

$admin->sendMonthlyPaymentNotifications();
