<?php session_start(); 
  require_once ("./lib/opendb.php");
  require_once ("./lib/email_error.php");
  require_once ("./lib/redirect.php");
  require_once ("./lib/email_admins.php");
  require_once ("./lib/send_email.php");
  require_once ("./lib/safe_sql.php");

  $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];  
  $_SESSION['error_text'] = "";
  $_SESSION['display_name'] = "";
  $_SESSION['admin_active'] = 0;
  $_SESSION['staff_active'] = 0;
  $_SESSION['user_active'] = 0;
  $_SESSION['current_user_num'] = 0;
  $_SESSION['current_user_name'] = safe_sql($_POST['user_name']);
  
  $user_qry = "select * from user where enabled = '1' and user_name = '".$_SESSION['current_user_name']."' LIMIT 1";
  $user_res = mysqli_query($db, $user_qry);
  if (!$user_res) db_error ('proc_login 1', $user_qry);
  $user_exists = mysqli_num_rows ($user_res) > 0;
  if (!$user_exists) { 
    $_SESSION['error_text'] = "User name not known - please reenter<br>";
    redirect ("./".$_POST['form_page']);
  } 
  
  $user_info = mysqli_fetch_array($user_res);
  
  $session_qry = "select * from session_log where user_num = '".$user_info['user_num']."' LIMIT 1";
  $session_res = mysqli_query($db, $session_qry);
  if (!$session_res) db_error ("proc login 2", $session_qry);
  $session_active = mysqli_num_rows ($session_res);
  if ($session_active) {
	 $session_info = mysqli_fetch_array ($session_res);
	 $_SESSION['shift_start_time'] = $session_info['start_timestamp'];
  
	 //check if old session needs to be terminated
	 $login_time = time ();
	 $settings_qry = "select * from settings";
	 $settings_res = mysqli_query($db, $settings_qry);
	 if (!$settings_res) db_error ('proc login 3', $settings_qry);
	 $settings_val = mysqli_fetch_array ($settings_res);
	 $session_period_secs = $login_time - $_SESSION['shift_start_time'];
	 $session_remainder = $session_period_secs%3600;
	 $session_hours = ($session_period_secs - $session_remainder)/3600;
	 $session_minutes =  round (($session_remainder/60), 0);
	 $session_total_minutes =  round (($session_period_secs/60), 0);

	 if ($session_active && ($session_total_minutes > $settings_val['max_session_mins']) )  { 

	 //need to cancel the session, and send a message to admins
		 $_SESSION['shift_start_time'] = 0;	 
		 //send admins message
		 $admin_message = "User session deleted - exceeded maximum login time.\n\n";
		 $admin_message .= "User: ".$user_info['first_name']." ".$user_info['last_name']."\n\n";
		 $admin_message .= "Original start time: ". date ('g:h a j M, Y',$session_info['start_timestamp'])."\n\n";
		 $admin_message .= "Length when terminated: ".$session_hours." hrs ".$session_minutes. " mins";
		 $admin_subject = "INFORMATION: Session deleted: over maximum length";
		 email_admins ($admin_message, $admin_subject);
		 //send user message
		 $user_message = "A previous phone shift session was cancelled when you logged in to your Agora profile. ";
		 $user_message .= "The session had exceeded the maximum time for a shift. Your session was ".$session_hours." hrs ";  
		 $user_message .=  $session_minutes. " mins long when it was terminated. You were NOT given credit for this session. ";
		 $user_message .= "If you feel this was in error, please contact a staff member for assistance. \n\n The Agora Staff";
		 $user_subject = "WARNING - your Agora session was deleted - no credit given";
		 
		 $session_update = "delete from session_log where user_num = '".$user_info['user_num']."'";

		 $session_res = mysqli_query($db, $session_update);
		 if (!$session_res) db_error ("proc login 4", $session_update);
		 send_email ("Agora Admin", "admin@agoracares.org", $user_info['email'], $user_message, $user_subject);
  }
}
  //at this point we've check that the max logins hasn't been exceeded; now see if this is a valid login.
  $salt ="ag";
  $pwd = crypt($_POST['pwd'],$salt);
  if ($pwd == $user_info["pwd"]) 
  { //login successful, check permissions
	  if ($user_info['admin']) $_SESSION['admin_active'] = 1;
	  if ($user_info['staff']) $_SESSION['staff_active'] = 1;
	  $_SESSION['user_active'] = 1;
	  $_SESSION['current_user_num'] = $user_info['user_num'];
	  $_SESSION['display_name'] = $user_info['first_name']." ".$user_info['last_name'];
	  
      $user_update_qry = "update user set last_login_time ='".time()."',last_ip='".$_SESSION['user_ip']."' 
	                      where dancernum = '".$_SESSION['current_user_num']."'";	
	  $user_update_res = mysqli_query($db, $user_update_qry);	  
	  redirect ("./".$_POST['form_page']);	
  }
  else //login failed, display error .
  { 
      $_SESSION['error_text'] = "Invalid username or password entered.";
	  redirect ("./".$_POST['form_page']);	
  }

?>


