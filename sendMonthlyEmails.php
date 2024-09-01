<?php
include 'admin.php';
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;


$admin = new Admin();

$account_sid = "ACe7fb0d1b5fa2212c4137fb8653261463";
$auth_token = "d8f6de152838e5c1326e689debbd3ea9";

// A Twilio number you own with SMS capabilities
$twilio_number = "+18133363443";

$client = new Client($account_sid, $auth_token);
$message = $client->messages->create(
    // Where to send a text message (your cell phone?)
    '+639955835160',
    array(
        'from' => $twilio_number,
        'body' => 'SABAY SABAY TAYONG BIBITAWW'
    )
);

if($message) {
    echo 'Sent to Phone number';
} else {
    echo 'Text to Number Failed';
}

$admin->sendMonthlyPaymentNotifications();
