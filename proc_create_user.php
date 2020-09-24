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

//check two passwords are equal

if ($_POST['pwd'] == $_POST['pwd_check']){} else {
	$_SESSION['error_text'] .= "Passwords do not match - please reenter<br>";
	}

//check user name is valid and available

if (!available_user_name ($_POST['user_name'])) $error = 1;

//check email address is valid format
	 
if (!valid_email($_POST['email'])) {
	$_SESSION['error_text'] .= "Email address is not valid. Please re-enter<br>";
}

//check if email is already in use

$email_qry = "select * from user where email = '".safe_sql($_POST['email'])."' LIMIT 1";
$email_res = mysqli_query($db, $email_qry);
if (!$email_res) db_error ('proc_create_user 1',$email_qry);
if ( mysqli_num_rows ($email_res) ) { 
	$_SESSION['error_text'] .= "Email is already associated with a user - please re-enter<br>";
}

//check password is valid for content/length, and check if the two entries are the same.
if (!valid_password ($_POST['pwd'])) {
	$_SESSION['error_text'] .= "Password contains invalid characters<br>";
}

if (strlen ($_POST['pwd']) < 6) {
	$_SESSION['error_text'] .= "Password must be at least six characters<br>";
}

//end validation
if (strlen ($_SESSION['error_text']) > 1) {
    redirect("form_create_user.php"); 
	}

$_POST['first'] = trim (safe_sql($_POST['first']));
$_POST['last'] = trim (safe_sql($_POST['last']));


$pwd = crypt(safe_sql($_POST['pwd']),$salt);

$query = "insert into user (user_name, pwd, first_name, last_name, email, 
							  enabled, status_id, notes, new_user, admin, staff, create_time, initial_ip, last_ip, 
							  date_joined, date_status_updated, last_login_time) 

  values
  ('".safe_sql($_POST['user_name'])."',
  '".$pwd."', 
  '".safe_sql($_POST['first'])."', 
  '".safe_sql($_POST['last'])."', 
  '".safe_sql($_POST['email'])."', 
  '1',  
  '1',
  '',
  '1',
  '0',
  '0',
  '".time()."', 
  '".$_SERVER['REMOTE_ADDR']."', 
  '".$_SERVER['REMOTE_ADDR']."', 0, 0, 0
  )";

  $result = mysqli_query($db, $query);
  if (!$result)  db_error ($query, "proc create user 2"); 

$message = "A new account has been created for:\n\n".$_POST['first']." ".$_POST['last'];
$subject = "New account created";	
email_admins ($message, $subject);
	
  $message = 
	"PLEASE DO NOT REPLY TO THIS MESSAGE: IT DOESN'T GO TO A HUMAN - NO-ONE WILL READ IT!\n\n".
	"Thank you for creating your Agora account. Staff have been notified that you created an account. ".
	"If you have additional hours that you completed before creating your account, a staff member will add them to your ".
	"information in the next few days. Please be patient! You can see your hours in the Time History section of the website.\n\n".
	"You can now log your shift times online when in the center. (The TIME page can only be accessed from a center computer.)\n\n".
    "If you need help using the Agora Portal, please contact a member of staff.\n\n".
	"Thanks again for all you do!\nThe Agora Staff.";
	$to = stripslashes ($_POST['email']);
	$sender_email = "webmaster@agoracares.org";
	$sender_name = "Agora Administrator";
	$subject = "Your new Agora account";
	
	send_email ($sender_name, $sender_email, $to, $message, $subject);

  unset ($_SESSION['err_user_name']);
  unset ($_SESSION['err_first']);
  unset ($_SESSION['err_last']);
  unset ($_SESSION['err_email']);
  redirect ("./page_register_thanks.php");

?>


