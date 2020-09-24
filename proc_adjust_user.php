<?php session_start ();

require_once './lib/opendb.php';
require_once './lib/valid_email.php';
require_once './lib/email_error.php';
require_once './lib/send_email.php';
require_once './lib/date_convert.php';
require_once './lib/redirect.php';
require_once './lib/safe_sql.php';
if (!$_SESSION['admin_active']) redirect ('index.php');

$_SESSION['error_text'] = "";

  $user_qry = "select * from user where user_num = '".$_SESSION['selected_user']."' LIMIT 1";

  $user_res = mysqli_query($db, $user_qry);
  if (!$user_res) db_error ('proc_adjust 1', $user_qry);
  $user_info = mysqli_fetch_array ($user_res);

//process extra hours

if (isset ($_POST['extra_hours'])) {
	    $time_claimed = $_POST['hours'] * 60;
		$display_date = $_POST['display_date'];
		
		if (strlen ($display_date) < 6) $display_date = date ('m/d/y',time());
		$time_qry = "insert into time_log (user_num, entered_by, start_timestamp, display_date, time_in_mins, reason_code,
										   entry_time, activity_type, entry_status, notes)
		             values
					 ('".$_SESSION['selected_user']."',
					  '".$_SESSION['current_user_num']."',		
					  '".time()."',			
					  '".$display_date."',
					  '".$time_claimed."',
					  '0',
					  '".time()."',		
					  '".$_POST['activity']."',				
					  '1',
					  '".safe_sql($_POST['note'])."')";	

		$time_res = mysqli_query($db, $time_qry);
		if (!$time_res) db_error ('proc time 3',$time_res);
}

//Process reject requests for approved items

//The key for each entry is in the form <user_num>.<start_timestamp>
if (isset ($_POST['reject_hours'])) {
foreach ($_POST['notes'] as $key => $note_text) {
	$note[$key] = $note_text;
}

foreach ($_POST['del'] as $key => $action_code) {
    
	if ($action_code <> 0) {
		$record_key = explode ('.',$key);
		
		$start_timestamp = $record_key[1];
		$update_qry = "update time_log set entry_status = '2', notes ='".safe_sql($note[$key]).
	                  "' where user_num = '".$_SESSION['selected_user']."' and start_timestamp = '".$start_timestamp."'";
		$update_res = mysqli_query($db, $update_qry);
		if (!$update_res) db_error ('proc approvals 2',$update_qry);
		
        $message .= "This is notification that an Agoran staff member has rejected a phone shift item in your time log. You can log in to the Agora portal to view this item (under Time History).\n\nPlease let us know if you think this was done in error.\nThe Agora Staff";
		$to = stripslashes ($user_info['email']);
		$sender_email = "webmaster@agoracares.org";
		$sender_name = "Agora Administrator";
		$subject = "Agora: Your phone shift time record has been updated by staff";

		send_email ($sender_name, $sender_email, $to, $message, $subject);

		}
}
}

redirect ("./page_user_adjust_thanks.php");

?>


