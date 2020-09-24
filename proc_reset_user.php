<?php session_start ();
/* Change log

*/
require_once './lib/opendb.php';
require_once './lib/valid_email.php';
require_once './lib/email_error.php';
require_once './lib/redirect.php';
require_once './lib/send_email.php';
require_once './lib/safe_sql.php';
$_SESSION['error_text'] = "";

//save entries for re-display


$_SESSION['err_email'] = $_POST['email'];
$salt ="ag";
$regex =  '/^[a-zA-z]+$/' ; 

//check email address is valid format
	 
if (!valid_email($_POST['email'])) {
	$_SESSION['error_text'] .= "Email address is not valid. Please re-enter<br>";
}

//check if email is already in use

$email_qry = "select * from user where email = '".safe_sql($_POST['email'])."' LIMIT 1";
$email_res = mysqli_query($db, $email_qry);
if (!$email_res) db_error ('proc_reset_user 1',$email_qry);
if ( !mysqli_num_rows ($email_res) ) { 
	$_SESSION['error_text'] .= "Email is not registered - please re-enter<br>";
}
$user_info = mysqli_fetch_array($email_res);
//end validation
if (strlen ($_SESSION['error_text']) > 1) {
    redirect("form_create_user.php"); 
	}

//create temporary password

  /* create random password */
  $length=8;
  $all=explode(" ", 
   "a b c d e f g h i j k l m n o p q r s t u v w x y z "
  ."A B C D E F G H I J K L M N O P Q R S T U V W X Y Z " 
  ."0 1 2 3 4 5 6 7 8 9"); 
  $clear_pwd = "";
  srand((double)microtime()*1000000); 
  for($i=0; $i<$length; $i++) 
    { 
    $randy=rand(0, 61); 
    $clear_pwd.=$all[$randy]; 
    }
	

$pwd = crypt($clear_pwd,$salt);

$reset_qry = "update user set pwd = '".$pwd."' where email = '".$_POST['email']."'";
$reset_res = mysqli_query($db, $reset_qry);
if (!$reset_res) db_error ('proc reset 2',$reset_qry);

  $message = 
	"PLEASE DO NOT REPLY TO THIS MESSAGE: IT DOESN'T GO TO A HUMAN - NO-ONE WILL READ IT!\n\n".
	"Someone just sent a request to reset your Agora Portal password. If that was you great! If not, then please contact a staff member to report this situation.\n\nYour user name is : ".$user_info['user_name'].".\n\n".
	"Your password has been reset to:\n\n ".$clear_pwd."\n\n You should now go to the portal, log in, and change your password to something you can remember more easily.\n\n".
	"Thanks again for all you do!\nThe Agora Staff.";
	$to = stripslashes ($_POST['email']);
	$sender_email = "webmaster@agoracares.org";
	$sender_name = "Agora Administrator";
	$subject = "Your temporary Agora Portal password";
	//echo "SEND ".$sender_name." ".$sender_email." TO ".$to." MESSAGE ".$message. " SUBJECT ".$subject;
	send_email ($sender_name, $sender_email, $to, $message, $subject);

  unset ($_SESSION['err_email']);
  redirect ("./page_reset_thanks.php");

?>


