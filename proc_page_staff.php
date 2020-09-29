<?php session_start ();
/*
Two types of page:

$_POST['suicide']   is set: page (email) emergency staff contact
								
$_POST['help']  is set: page (email) information staff contact
*/

require_once './lib/opendb.php';
require_once './lib/email_admins.php';
require_once './lib/email_error.php';
require_once './lib/redirect.php';
require_once './lib/send_email.php';
require_once './lib/safe_sql.php';

if (isset($_POST['suicide_text']) && strlen($_POST['suicide_text']) > 1) $text = $_POST['suicide_text'];
else 
    if (isset($_POST['help_text']) && strlen($_POST['help_text']) > 1) $text = $_POST['help_text'];
	else $text = "No additional information";

$settings_qry = "select * from settings";
$settings_res = mysqli_query($db, $settings_qry);
if (!$settings_res) db_error ('proc page staff 1',$settings_qry);

if ($_POST['suicide']) {
	$page_type = 1;
	$message = $_SESSION['display_name']. ": EMERGENCY HELP NEEDED: ";
	if (isset($_POST['suicide_text'])) $message .= $text;
	$subject = "EMERGENCY HELP NEEDED";
	email_admins ($message, $subject, '2');
	redirect ('page_emergency_sent.php');
}
if ($_POST['help']) {
	$page_type=2;
	$message = $_SESSION['display_name']. ": Requesting Help: ";
	if (isset($_POST['help_text'])) $message .= $text;
	$subject = "Help request: ".$_SESSION['display_name'] ;
	//echo $message."<br".$subject; die;
	email_admins ($message, $subject, '3');
	redirect ('page_help_sent.php');
}



?>


