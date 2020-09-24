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
if (!$_SESSION['admin_active']) redirect ('index.php');

$_SESSION['error_text'] = "";
$change_pwd_requested = 0;
//save entries for re-display

if (!isset($_POST['max_length'])) $_POST['max_length'] = "";
if (!isset($_POST['min_length'])) $_POST['min_length'] = "";
if (!isset($_POST['warn_length'])) $_POST['warn_length'] = "";
if (!isset($_POST['admin1'])) $_POST['admin1'] = "";
if (!isset($_POST['admin2'])) $_POST['admin2'] = "";
if (!isset($_POST['admin3'])) $_POST['admin3'] = "";
if (!isset($_POST['emergency'])) $_POST['emergency'] = "";
if (!isset($_POST['information'])) $_POST['information'] = "";
if (!isset($_POST['shift'])) $_POST['shift'] = "";
if (!isset($_POST['emergency_2'])) $_POST['emergency_2'] = "";
if (!isset($_POST['information_2'])) $_POST['information_2'] = "";
if (!isset($_POST['shift_2'])) $_POST['shift_2'] = "";
$_SESSION['err_max'] = $_POST['max_length'];
$_SESSION['err_warn'] = $_POST['warn_length'];
$_SESSION['err_admin1'] = $_POST['admin1'];
$_SESSION['err_admin2'] = $_POST['admin2'];
$_SESSION['err_admin3'] = $_POST['admin3'];
$_SESSION['err_page1'] = $_POST['emergency'];
$_SESSION['err_page2'] = $_POST['information'];
$_SESSION['err_page3'] = $_POST['shift'];
$_SESSION['err_page1_2'] = $_POST['emergency_2'];
$_SESSION['err_page2_2'] = $_POST['information_2'];
$_SESSION['err_page3_2'] = $_POST['shift_2'];


//check max and warn
if (!is_numeric($_POST['max_length'])) $_SESSION['error_text'] .= "Maximum sessions must be set to a numeric value<br>";
if (!is_numeric($_POST['min_length'])) $_SESSION['error_text'] .= "Minimum sessions must be set to a numeric value<br>";
if ($_POST['min_length'] < 5) $_SESSION['error_text'] .= "Minimum sessions must be at least five minutes<br>";
if (!is_numeric($_POST['warn_length'])) $_SESSION['error_text'] .= "Warning sessions must be set to a numeric value<br>";
if ($_POST['max_length'] < $_POST['warn_length']) $_SESSION['error_text'] .= "Warning minutes must be less than or equal to Max minutes<br>";
if ($_POST['max_length'] < $_POST['min_length']) $_SESSION['error_text'] .= "Minimum minutes must be less than or equal to Max minutes<br>";

//validate email addresses
if (strlen ($_POST['admin1'])== 0 && strlen ($_POST['admin2']) == 0 && strlen ($_POST['admin3']) == 0) 
      $_SESSION['error_text'] .= "At least one admin email is required<br>";

if (strlen ($_POST['admin1']) > 0 && (!valid_email($_POST['admin1']))) $_SESSION['error_text'] .= "Admin email 1: invalid email format<br>";
if (strlen ($_POST['admin2']) > 0 && (!valid_email($_POST['admin2']))) $_SESSION['error_text'] .= "Admin email 2: invalid email format<br>";
if (strlen ($_POST['admin3']) > 0 && (!valid_email($_POST['admin3']))) $_SESSION['error_text'] .= "Admin email 3: invalid email format<br>";

//if (!valid_email($_POST['emergency'])) $_SESSION['error_text'] .= "Pager emergency (1): invalid email format<br>";
//if (!valid_email($_POST['information'])) $_SESSION['error_text'] .= "Pager help requests (1): invalid email format<br>";
//if (!valid_email($_POST['shift'])) $_SESSION['error_text'] .= "Pager shift messages(1): invalid email format<br>";

//if (strlen ($_POST['emergency_2']) > 0 && (!valid_email($_POST['emergency_2']))) $_SESSION['error_text'] .= "Pager emergency (2): invalid email format<br>";
//if (strlen ($_POST['information_2']) > 0 && (!valid_email($_POST['information_2']))) $_SESSION['error_text'] .= "Pager help requests (2): invalid email format<br>";
//if (strlen ($_POST['shift_2']) > 0 && (!valid_email($_POST['shift_2']))) $_SESSION['error_text'] .= "Pager shift messages(2): invalid email format<br>";




//end validation
if (strlen ($_SESSION['error_text']) > 1) {
    redirect("form_settings.php"); 
	}

$update_qry = "update settings set 
                max_session_mins = '".$_POST['max_length']."', 
                min_session_mins = '".$_POST['min_length']."', 
                long_session_warning = '".$_POST['warn_length']."', 
                email_admin_1 = '".$_POST['admin1']."', 
                email_admin_2 = '".$_POST['admin2']."', 
                email_admin_3 = '".$_POST['admin3']."', 
                page_email_emergencies = '".$_POST['emergency']."', 
                page_email_information = '".$_POST['information']."', 
                page_email_shift = '".$_POST['shift']."',
                page_email_emergencies_2 = '".$_POST['emergency_2']."', 
                page_email_information_2 = '".$_POST['information_2']."', 
                page_email_shift_2 = '".$_POST['shift_2']."'";


$update_res = mysqli_query($db, $update_qry);
if (!$update_res)  db_error ($update_qry, "proc settings 2"); 


  unset ($_SESSION['err_max']);
unset ($_SESSION['err_min']);
unset ($_SESSION['err_warn']);
unset ($_SESSION['err_admin1']);
unset ($_SESSION['err_admin2']);
unset ($_SESSION['err_admin3']);
unset ($_SESSION['err_page1']);
unset ($_SESSION['err_page2']);
unset ($_SESSION['err_page3']);
unset ($_SESSION['err_page1_2']);
unset ($_SESSION['err_page2_2']);
unset ($_SESSION['err_page2_3']);
  redirect ("./page_settings_thanks.php");

?>


