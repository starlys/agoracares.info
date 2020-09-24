<?php session_start ();
/* Check each row in the table. for action = 0 (N) ignore. For action = 1 (A) approve record and email the user telling them their extra hours have been improved. For action = 2 (Reject) update the record, and email the user to let them know.
*/
require_once './lib/opendb.php';
require_once './lib/valid_email.php';
require_once './lib/valid_user.php';
require_once './lib/valid_password.php';
require_once './lib/email_error.php';
require_once './lib/email_admins.php';
require_once './lib/redirect.php';
require_once './lib/send_email.php';
require_once './lib/safe_sql.php';
if (!$_SESSION['admin_active']) redirect ('index.php');


//The key for each entry is in the form <user_num>.<start_timestamp>
foreach ($_POST['note'] as $key => $note_text) {
	$note[$key] = $note_text;
}

foreach ($_POST['action'] as $key => $action_code) {
    
	if ($action_code <> 0) {
		$record_key = explode ('.',$key);
		$user_num = $record_key[0];
        $user_qry = "select * from user where user_num = '".$user_num."'";
		$user_res = mysqli_query($db, $user_qry);
		if (!$user_res) db_error ('proc approvals 1',$user_qry);
		$user_info = mysqli_fetch_array ($user_res);
		
		$start_timestamp = $record_key[1];
		$update_qry = "update time_log set entry_status = '".$action_code."', notes ='".$note[$key].
	                  "' where user_num = '".$user_num."' and start_timestamp = '".$start_timestamp."'";
		$update_res = mysqli_query($db, $update_qry);
		if (!$update_res) db_error ('proc approvals 2',$update_qry);
		
		if ($action_code == 1) {
			$message .= "This is notification that an Agoran staff member has approved a pending item in your time log. You can log in to the Agora portal to view this item (under Time History).\n\nThanks for everything you do!\nThe Agora Staff";
		}
		else {
			$message .= "This is notification that an Agoran staff member has rejected a pending item in your time log. You can log in to the Agora portal to view this item (under Time History).\n\nPlease let us know if you think this was done in error.\nThe Agora Staff";
		$to = stripslashes ($user_info['email']);
		$sender_email = "webmaster@agoracares.org";
		$sender_name = "Agora Administrator";
		$subject = "Agora: Your pending time record has been updated";
		
		send_email ($sender_name, $sender_email, $to, $message, $subject);

		}
	}
}

redirect ("./page_approvals_thanks.php");

?>


