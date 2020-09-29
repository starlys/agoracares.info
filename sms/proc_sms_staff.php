<?php session_start ();
require_once '../lib/opendb.php';
require_once '../lib/email_error.php';
require_once '../lib/redirect.php';
/*
Two types of page:

$_POST['suicide']   is set: page (email) emergency staff contact
$_POST['help']  is set: page (email) information staff contact

Check type of page
*/

//DEBUG
//ini_set('display_startup_errors', true);
//error_reporting(E_ALL);
//ini_set('display_errors', true);

// Your Account SID and Auth Token from twilio.com/console
$account_sid = '';
$auth_token = '';

// A Twilio number you own with SMS capabilities
$twilio_number = "+15052073240";

if (isset($_POST['suicide_text']) && strlen($_POST['suicide_text']) > 1) $text = $_POST['suicide_text'];
else 
    if (isset($_POST['help_text']) && strlen($_POST['help_text']) > 1) $text = $_POST['help_text'];
	else $text = "No additional information";
	
//get pager numbers
$admin_qry = "select * from settings";
$admin_res = mysqli_query($db, $admin_qry);
if (!$admin_res) db_error ('sms_admins', $admin_qry);
$settings=mysqli_fetch_array ($admin_res);

/*print_r($settings);
echo "emergency1 ".$settings['page_email_emergencies']."<br>";
echo "emergency2 ".$settings['page_email_emergencies_2']."<br>";
echo "help1 ".$settings['page_email_information']."<br>";
echo "help2 ".$settings['page_email_information_2']."<br>";
*/

$atpos = strpos ($settings['page_email_emergencies'],'@'); 
if ($atpos) {
	$settings['page_email_emergencies'] = substr($settings['page_email_emergencies'],0,$atpos);
//	echo "<br>E1 ".$settings['page_email_emergencies']; 
}
$atpos = strpos ($settings['page_email_emergencies_2'],'@'); 
if ($atpos) {
	$settings['page_email_emergencies_2'] = substr($settings['page_email_emergencies_2'],0,$atpos);
//	echo "<br>E2 ".$settings['page_email_emergencies_2']; 
}
$atpos = strpos ($settings['page_email_information'],'@'); 
if ($atpos) {
	$settings['page_email_information'] = substr($settings['page_email_information'],0,$atpos);
//	echo "<br>H1 ".$settings['page_email_information']; 
}
$atpos = strpos ($settings['page_email_information_2'],'@'); 
if ($atpos) {
	$settings['page_email_information_2'] = substr($settings['page_email_information_2'],0,$atpos);
//	echo "<br>H1 ".$settings['page_email_information_2']; 
}
	

if ($_POST['suicide']) {
	$page_type = 1;
	$message = $_SESSION['display_name']. ": AGORA: EMERGENCY HELP NEEDED: ";
	if (isset($_POST['suicide_text'])) $message .= $text;
	$pager_1 = "+1".$settings['page_email_emergencies'];
    $pager_2 = "+1".$settings['page_email_emergencies_2'];
}

if ($_POST['help']) {
	$page_type=2;
	$message = $_SESSION['display_name']. ": AGORA: HELP NEEDED: ";
	if (isset($_POST['help_text'])) $message .= $text;
	$pager_1 = "+1".$settings['page_email_information'];
    $pager_2 = "+1".$settings['page_email_information_2'];
}

require __DIR__ . '/vendor/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
use Twilio\Rest\Client;

$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    $pager_1,
    array(
        'from' => $twilio_number,
        'body' => $message
    )
);
if (strlen($pager_2) > 9) { //check the second number is valid
$client = new Client($account_sid, $auth_token);
$client->messages->create(
    // Where to send a text message (your cell phone?)
    $pager_2,
    array(
        'from' => $twilio_number,
        'body' => $message
    )
);
}

redirect ('../page_help_sent.php');
?>