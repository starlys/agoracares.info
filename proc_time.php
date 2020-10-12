<?php session_start ();
/*
Two types of page:

$_POST['start_shift']   is set: add a record to the session_log							
$_POST['end_shift']     is set: call logoff with appropriate options 
$_POST['extra_hours']   is set: add an extra credit record to time_log
*/
$qry = 0;
require_once './lib/opendb.php';
require_once './lib/email_admins.php';
require_once './lib/email_error.php';
require_once './lib/redirect.php';
require_once './lib/send_email.php';
require_once './lib/safe_sql.php';

if (!$_SESSION['current_user_num']) db_error ("No user logged in - proc_time.php; unable to proceed", $qry); //shouldn't happen - just in case.

//transfer to logout process if this is the end of a shift
if (isset ($_POST['end_shift'])) {
	if (isset($_POST['still_on']) && is_numeric($_POST['still_on']) ) {
		 $_SESSION['still_on'] = $_POST['still_on']; 
		 redirect ('proc_logout.php');
	}
    else {
		$_SESSION['error_text'] = "The 'left on shift' entry is required (enter zero if no-one will be on shift)";
		redirect ('time.php');
	}
	
}

//start of shift - add session record. No record for this user should exist at this point. If there is one, die.


if (isset ($_POST['start_shift'])) {
	$session_qry = "select * from session_log where user_num ='".$_SESSION['current_user_num']."'";
	$session_res = mysqli_query($db, $session_qry);
	if(!$session_res) db_error ('proc_time.php 1',$session_qry);
	$session_active = mysqli_num_rows($session_res);
	if ($session_active) db_error ('Already active session in proc_time.php - terminating',$qry);
	//OK - no active session; go ahead and set one up
	$_SESSION['shift_start_time'] = time();
	$add_qry = "insert into session_log (user_num, start_timestamp, activity_type) values
	           ('".$_SESSION['current_user_num']."','".$_SESSION['shift_start_time']."','10')";
	$add_res = mysqli_query($db, $add_qry);
	if (!$add_res) db_error ("proc_time 2",$add_qry);
	$message = $_SESSION['display_name']." started shift. ";
	$subject = "NOTICE: ".$_SESSION['display_name']." started shift.";
	email_admins ($message, $subject, '4');
	redirect ('page_shift_started.php');
}

if (isset ($_POST['extra_hours'])) {
	$time_claimed = $_POST['hours'] * 60;
	$display_date = $_POST['display_date'];
	
	if (strlen ($display_date) < 6) $display_date = date ('m/d/y',time());
	$time_qry = "insert into time_log (user_num, entered_by, start_timestamp, display_date, time_in_mins, reason_code,
										entry_time, activity_type, entry_status, notes)
					values
					('".$_SESSION['current_user_num']."',
					'".$_SESSION['current_user_num']."',		
					'".time()."',			
					'".$display_date."',
					'".$time_claimed."',
					'0',
					'".time()."',		
					'".$_POST['activity']."',				
					'0',
					'".safe_sql($_POST['note'])."')";	

	$time_res = mysqli_query($db, $time_qry);
	if (!$time_res) db_error ('proc time 3',$time_res);
	
	$message = $_SESSION['display_name']." has recorded extra hours. You must approve these hours before they will be added to the total for this volunteer.";
	$subject = "NOTICE: ".$_SESSION['display_name']." has recorded extra hours.";
	email_admins ($message, $subject, '1');
	redirect ('page_extra_hours.php');
}
?>


