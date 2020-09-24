<?php
function sms_admins ($number, $message) {

require __DIR__ . 'vendor/autoload.php';
use \sms\vendor\twilio\sdk\Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$account_sid = 'ACbb76c0488e1d11843db66e0642d534dc';
$auth_token = '6ede6d2b8eab3fbdd53433a6ca68f78d';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with SMS capabilities
$twilio_number = "+15052073240";

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    '+15053073491',
    array(
        'from' => $twilio_number,
        'body' => 'Suicide in progress (test)'.time()
    )
);
echo "Sent";
}
?>
