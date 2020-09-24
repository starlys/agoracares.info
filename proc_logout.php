<?php session_start ();
/*
This routine can be called in three ways:
$_SESSION['still_on'] is the number of people still on shift.
*/
require_once './lib/email_admins.php';
require_once './lib/opendb.php';
require_once './lib/email_error.php';
require_once './lib/redirect.php';
require_once './lib/send_email.php';
require_once './lib/safe_sql.php';

if (isset($_POST['still_on'])) $_SESSION['still_on'] = $_POST['still_on'];
if (!isset ($_SESSION['shift_start_time']) ) $_SESSION['shift_start_time'] = 0; //should be set, but just in case

if ($_SESSION['shift_start_time']) { //If we have a shift start time
     if (!isset ($_SESSION['still_on']) || $_SESSION['still_on']=="") {        //and still_on isn't set
		 $_SESSION['error_text'] = "The 'left on shift' entry is required (enter zero if no-one will be on shift)";
		 redirect ('time.php');
	 }
}

if ($_SESSION['shift_start_time']) { //process end of shift
//send message to staff

        $message = $_SESSION['display_name']. " finished shift. ".$_SESSION['still_on']. " still on shift.";
		$subject = $message;
		email_admins ($message, $subject, '4');

        $session_qry = "select * from session_log where user_num = '".$_SESSION['current_user_num']."'";
		$session_res = mysqli_query($db, $session_qry);
		if (!$session_res) {db_error ('proc logout 1', $session_qry);}
		$session_count = mysqli_num_rows ($session_res);
		if (!$session_count) {db_error ('proc logout 1a', $session_qry);} //if no session found, report error.
		$session_info = mysqli_fetch_array ($session_res);
		
		$settings_qry = "select * from settings";
		$settings_res = mysqli_query($db, $settings_qry);
		if (!$settings_res) db_error ('proc logout 2', $settings_qry);
		$settings_info = mysqli_fetch_array ($settings_res);
		
		$user_qry = "select * from user where user_num ='".$_SESSION['current_user_num']."'";
		$user_res = mysqli_query($db, $user_qry);
		if (!$user_res) db_error ('proc logout 3', $user_qry);
		$user_info = mysqli_fetch_array ($user_res);
		
		$current_time = time();
		
		$shift_length = $current_time - $session_info['start_timestamp'];
		$shift_remainder = $shift_length%3600;
		$shift_hours = ($shift_length - $shift_remainder)/3600;
		$shift_minutes = round (($shift_remainder/60), 0);    //used with shift hours to record time for user
		$shift_total_minutes = round (($shift_length/60), 0); //used to compare agains min/max values, and to record time_log

//Check if shift is less than minimum required length. If it is, don't give credit and email admins/user
		if ($shift_total_minutes < $settings_info['min_session_mins']) {
			$message = $_SESSION['display_name']. " ended a shift of ". $shift_minutes. " minutes. This was less than the minimum shift limit of ".$settings_info['min_session_mins']." minutes. No credit was given for this shift. The user has been informed by email.";
			$subject = $_SESSION['display_name'].": Shift shorter than minimum required. RECORD DELETED.";
			email_admins ($message, $subject, '4');
			
		    $user_message = "You recently logged out of a shift that was shorter than the minimum required - no credit was given for this shift.  The shift was only ".$shift_minutes." minutes long; the minimum shift length for credit is ".$settings_info['min_session_mins']." minutes. If this was a valid shift, please contact staff to have your shift added to your time record.\n\nThanks, and apologies in advance if this was an error on our part!\n\nThe Staff, Agora";
			$user_subject = "No credit given for last Agora shift";
			send_email ("Agora Staff", "admin@agoracares.org", $user_info['email'], $user_message, $user_subject);

		}
		
//Check if this shift exceeds the maximum length allowed. Email user and admins that this session was deleted
		if ($shift_total_minutes > $settings_info['max_session_mins']) {

			$message = $_SESSION['display_name']. " ended a shift of ". $shift_minutes. " minutes. This exceeded the maximum shift limit of ".$settings_info['max_session_mins']." minutes. No credit was given for this shift. The user has been informed by email.";
			$subject = $_SESSION['display_name'].": Max shift limted exceeded. RECORD DELETED.";
			email_admins ($message, $subject, '4');
		    $user_message = "You recently logged out of a shift that exceeded the shift length limit - no credit was given for this shift. You may have accidentally left yourself logged in from a previous shift. The shift was over ".$shift_hours." hours long. If this was a valid shift, please contact staff to have your shift added to your time record.\n\nThanks, and apologies in advance if this was an error on our part!\n\nThe Staff, Agora";
			$user_subject = "No credit given for last Agora shift";
			send_email ("Agora Staff", "admin@agoracares.org", $user_info['email'], $user_message, $user_subject);

		}

//Check if this shift exceeds the warning length. User gets credit, but a warning is sent to admins
		if (($shift_total_minutes > $settings_info['long_session_warning']) && ($shift_total_minutes < $settings_info['max_session_mins'])){

			$message = $_SESSION['display_name']. " ended a shift of ". $shift_minutes. " minutes. This exceeded the warning shift limit of ".$settings_info['long_session_warning']." minutes. The volunteer DID receive credit for this shift. This is a WARNING only.";
			$subject = $_SESSION['display_name'].": WARNING: shift limit exceeded.";
			email_admins ($message, $subject, '4');
		}
		
//credit user for valid shift if shift is between min length and max length
        if (($shift_total_minutes <= $settings_info['max_session_mins']) &&
			($shift_total_minutes >= $settings_info['min_session_mins']) ) {
		
		$time_qry = "insert into time_log (user_num, entered_by, start_timestamp, display_date, time_in_mins, reason_code,
										   entry_time, activity_type, entry_status)
		             values
					 ('".$_SESSION['current_user_num']."',
					  '".$_SESSION['current_user_num']."',		
					  '".$session_info['start_timestamp']."',		
					  '".date ('m/d/y',$session_info['start_timestamp'])."',		
					  '".$shift_total_minutes."',		
					  '0',		
					  '".time()."',		
					  '1',		
					  '1')";	
					 
		$time_res = mysqli_query($db, $time_qry);
		if (!$time_res) db_error ('proc logout 4', $time_qry);
		
		
		}		
}

//now clear the session log reocrds, and the PHP session, and redirect to Logged Off page if logoff is requested
$delete_qry = "delete from session_log where user_num = '".$_SESSION['current_user_num']."'";
$delete_res = mysqli_query($db, $delete_qry);
if (!$delete_res) db_error ('proc logout 5', $delete_qry);
$affected_rows = mysqli_affected_rows($db);
if ($_SESSION['shift_start_time'] && $affected_rows == 0) db_error ('proc logout 6',$delete_qry);

session_unset ();
session_destroy ();
redirect ('page_logged_off.php');

?>


