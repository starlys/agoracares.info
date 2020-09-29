<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twilio test</title>
</head>
<body>
<?php

//DEBUG - can add these lines to proc_sms_staff to see errors
ini_set('display_startup_errors', true);
error_reporting(E_ALL);
ini_set('display_errors', true);

echo "Sending<br>";

$twilio_number = "+15052073240"; //from
$number = "+15059089426"; //to

require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
$account_sid = '';
$auth_token = '';

$client = new Client($account_sid, $auth_token);
$result = $client->messages->create(
    $number,
    array(
        'from' => $twilio_number,
        'body' => 'Suicide in progress (test)'.time()
    )
);
echo "Sent:";
echo $result->sid;

echo "<br>Done<br>";
?>
</body>
</html>