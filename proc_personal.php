<?php session_start ();
/* Change log

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
$_SESSION['error_text'] = "";
$change_pwd_requested = 0;
//save entries for re-display

$_SESSION['err_user_name'] = $_POST['user_name'];
$_SESSION['err_first'] = $_POST['first'];
$_SESSION['err_last'] = $_POST['last'];
$_SESSION['err_email'] = $_POST['email'];
$salt ="ag";
$regex =  '/^[a-zA-z]+$/' ; 
//check first and last name contain at least one character
if (!isset ($_POST['first']) || !isset ($_POST['last']) )  {
	 $_SESSION['error_text'] .= "First and last name must be entered<br>";
	 $name_error = 1;
     }
else {
	 if (strlen ($_POST['first']) < 1 || strlen ($_POST['last']) < 1) {
		$_SESSION['error_text'] .= "Invalid entry for name<br>";
	    }
     else {
		  if (!preg_match($regex, $_POST['first'])) {
			 $_SESSION['error_text'] .= "First name contains invalid characters<br>";
		     }
          else {
		       if (!preg_match($regex, $_POST['last'])) {
			      $_SESSION['error_text'] .= "Last name contains invalid characters<br>";
		          }
	           }
     }
}


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
	if ($email_user['user_num'] <> $_SESSION['current_user_num']) {
	   $_SESSION['error_text'] .= "Email is currently in use by someone else - please re-enter<br>";
	}
}

//if entered, check password is valid for content/length, and check if the two entries are the same.

if (strlen($_POST['newpwd']) || strlen($_POST['oldpwd']) || strlen($_POST['checkpwd']) ) {
	$change_pwd_requested = 1;
	if (!valid_password ($_POST['newpwd'])) {
		$_SESSION['error_text'] .= "Password contains invalid characters<br>";
	}
	if (strlen ($_POST['newpwd']) < 6) {
		$_SESSION['error_text'] .= "Password must be at least six characters<br>";
	}
	if ($_POST['newpwd'] == $_POST['checkpwd']){} else {
		$_SESSION['error_text'] .= "New passwords do not match - please reenter<br>";
		}
	
	//check existing password is correct
	$user_qry = "select * from user where user_num = '".$_SESSION['current_user_num']."'";
	$user_res = mysqli_query($db, $user_qry);
	if (!$user_res) db_error ('proc_create_user 2',$user_qry);
	$user_info = mysqli_fetch_array ($user_res);
	$existing_pwd = crypt(safe_sql($_POST['oldpwd']),$salt);
	
	if ($existing_pwd <> $user_info['pwd']) {
		$_SESSION['error_text'] .= "Current password entered is incorrect - please re-enter<br>";
	}
}
//end validation
if (strlen ($_SESSION['error_text']) > 1) {
    redirect("form_personal.php"); 
	}

$_POST['first'] = trim (safe_sql($_POST['first']));
$_POST['last'] = trim (safe_sql($_POST['last']));
$_POST['email'] = trim (safe_sql ($_POST['email']));

$pwd = crypt(safe_sql($_POST['newpwd']),$salt);

$update_qry = "update user set first_name = '".$_POST['first']."', last_name = '".$_POST['last']."', email = '".$_POST['email']."' where user_num = '".$_SESSION['current_user_num']."'";

$update_res = mysqli_query($db, $update_qry);
if (!$update_res)  db_error ($update_qry, "proc personal 2"); 

//update password if requested
if ($change_pwd_requested) {
	$update_qry = "update user set pwd = '".$pwd."' where user_num = '".$_SESSION['current_user_num']."'";

	$update_res = mysqli_query($db, $update_qry);
	if (!$update_res)  db_error ($update_qry, "proc personal 3"); 
}
$message = 
	"PLEASE DO NOT REPLY TO THIS MESSAGE: IT DOESN'T GO TO A HUMAN - NO-ONE WILL READ IT!\n\n".
	"Just letting you know that someone updated your Agora profile today. If that was you, that's great! If it wasn't you, ".
	"please contact an Agora staff member as soon as possible. ".
	"Thanks again for all you do!\nThe Agora Staff.";
	$to = stripslashes ($_POST['email']);
	$sender_email = "webmaster@agoracares.org";
	$sender_name = "Agora Administrator";
	$subject = "Your Agora account was updated";
	
	send_email ($sender_name, $sender_email, $to, $message, $subject);

  unset ($_SESSION['err_first']);
  unset ($_SESSION['err_last']);
  unset ($_SESSION['err_email']);
  redirect ("./page_update_thanks.php");

?>


