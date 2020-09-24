<?php session_start ();
/* Change log

*/
require_once './lib/opendb.php';
require_once './lib/valid_email.php';
require_once './lib/email_error.php';
require_once './lib/date_convert.php';
require_once './lib/redirect.php';
require_once './lib/safe_sql.php';
if (!$_SESSION['admin_active']) redirect ('index.php');

$_SESSION['error_text'] = "";

$_SESSION['err_email'] = $_POST['email'];
$_SESSION['err_joined'] = $_POST['date_joined'];
$_SESSION['err_enabled'] = $_POST['enabled'];
$_SESSION['err_status'] = $_POST['status'];
$_SESSION['err_notes'] = $_POST['notes'];
$_SESSION['err_admin'] = $_POST['admin'];


//check email address is valid format
	 
if (!valid_email($_POST['email'])) {
	$_SESSION['error_text'] .= "Email address is not valid. Please re-enter<br>";
}

//check if email is already in use by someone else

$email_qry = "select * from user where email = '".safe_sql($_POST['email'])."' LIMIT 1";
$email_res = mysqli_query($db, $email_qry);
if (!$email_res) db_error ('proc_create_user 1',$email_qry);
if ( mysqli_num_rows ($email_res) ) { 
	$email_user = mysqli_fetch_array ($email_res);
	if ($email_user['user_num'] <> $_SESSION['selected_user']) {
	   $_SESSION['error_text'] .= "Email is currently in use by someone else - please re-enter [".$email_user['user_num'].",".$_SESSION['current_user_num']."]<br>";
	}
}

//check joined date is valid format

if (!DateInputToTimestamp ($_POST['date_joined']))
	$_SESSION['error_text'] .= "Join date is invalid. Use dd/mm/yy format.<br>";
//end validation
if (strlen ($_SESSION['error_text']) > 1) {
    redirect("form_admin_user.php"); 
	}

//update user record. Only update date_status_updated if it has changed.
$user_qry = "select * from user where user_num = '".$_SESSION['selected_user']."'";
$user_res = mysqli_query($db, $user_qry);
if (!$user_res) db_error ("proc admin user 1",$user_qry);
$user_info = mysqli_fetch_array($user_res);
if ($user_info['status_id'] == $_POST['status']) $date_status_updated = $user_info['date_status_updated'];
else $date_status_updated = time();

$update_qry = "update user set 
                  email = '".$_POST['email']."',
				  date_joined = '".DateInputToTimestamp ($_POST['date_joined'])."',
				  enabled = '".$_POST['enabled']."',
				  status_id = '".$_POST['status']."',
				  admin = '".$_POST['admin']."',
				  date_status_updated = '".$date_status_updated."',
				  notes = '".safe_sql($_POST['notes'])."'
		      where user_num = '".$_SESSION['selected_user']."'";

$update_res = mysqli_query($db, $update_qry);

if (!$update_res) db_error ("proc admin user 2", $update_res);

unset ($_SESSION['selected_user']);
unset ($_SESSION['err_email']);
unset ($_SESSION['err_joined']);
unset ($_SESSION['err_enabled']);
unset ($_SESSION['err_status']);
unset ($_SESSION['err_notes']);
unset ($_SESSION['admin']);

redirect ("./page_user_admin_thanks.php");

?>


