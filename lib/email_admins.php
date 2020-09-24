<?php
function email_admins ($message, $subject, $type=1) {
//type 1 = all admins, 2=emergencies, 3=information, 4=shift
require_once 'opendb.php';
require_once 'email_error.php';
require_once 'safe_sql.php';
$sender_email = "admin@agoracares.org";
$admin_qry = "select * from settings";
$admin_res = mysqli_query($db, $admin_qry);
if (!$admin_res) db_error ('email_admins', $admin_qry);
$settings=mysqli_fetch_array ($admin_res);

switch ($type) {
	case 1: if (strlen ($settings['email_admin_1']) > 1) {
				$to = $settings['email_admin_1'];
				//$to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			if (strlen ($settings['email_admin_2']) > 1) {
				$to = $settings['email_admin_2'];
				//$to = "agorasmspage@gmail.com"; //temporary redirection

				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			if (strlen ($settings['email_admin_3']) > 1) {
				$to = $settings['email_admin_3'];
				//$to = "agorasmspage@gmail.com"; //temporary redirection

				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			break;
			
	case 2: if (strlen ($settings['page_email_emergencies']) > 1) {
				$to = $settings['page_email_emergencies'];
				$to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			
			if (strlen ($settings['page_email_emergencies_2']) > 1) {
				$to = $settings['page_email_emergencies_2'];
				$to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			break;
	
	case 3: if (strlen ($settings['page_email_information']) > 1) {
				$to = $settings['page_email_information'];
				$to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			if (strlen ($settings['page_email_information_2']) > 1) {
				$to = $settings['page_email_information_2'];
				$to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			break;
	
	case 4: if (strlen ($settings['page_email_shift']) > 1) {
				$to = $settings['page_email_shift'];
				//$to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			if (strlen ($settings['page_email_shift_2']) > 1) {
				$to = $settings['page_email_shift_2'];
				//$to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
				}
			break;
			
	case 5:  $to = "abqdan@gmail.com";
			 $to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
			 break;
			
	default:  $to = "abqdan@gmail.com";
			  $to = "agorasmspage@gmail.com"; //temporary redirection
				mail ($to, $subject, $message, "FROM: agoraadmin@donotreply.com");
			  break;
			
}

if ($type==2 || $type==3) {
		$log_qry = "insert into paging_history (page_timestamp, page_type, user_num, message, ip_address, page_address) values
	           ('".time()."','".
				   $type."','".
				   $_SESSION['current_user_num']."','".
				   safe_sql($message)."','".
				   $_SERVER['REMOTE_ADDR']."','".
				   safe_sql($to)."'".	
				")";
		$log_res = mysqli_query($db, $log_qry);
		if (!$log_res) db_error ('email admins 2',$log_qry);
}
}
?>

