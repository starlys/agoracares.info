<?php session_start ();
/* Change log

*/
require_once './lib/opendb.php';
require_once './lib/email_error.php';
require_once './lib/email_admins.php';
require_once './lib/redirect.php';
require_once './lib/safe_sql.php';
if (!$_SESSION['admin_active']) redirect ('index.php');

$_SESSION['err_ip'] = $_POST['man_add_ip'];
$_SESSION['error_text'] = "";
//process auto requests

$this_ip = $_SERVER['REMOTE_ADDR'];
if (isset($_POST['auto_add_ip'])){
    $insert_qry = "insert into center_ip (ip_address, description) values ('".$this_ip."','".safe_sql($_POST['auto_description'])."')
	               ON DUPLICATE KEY UPDATE description = '".safe_sql($_POST['auto_description'])."'";
	$insert_res = mysqli_query($db, $insert_qry);
	if (!$insert_res) db_error ('proc center ip 1', $insert_qry);
}

if (isset($_POST['auto_del_ip'])){
    $del_qry = "delete from center_ip where ip_address = '".$this_ip."'";
	$del_res = mysqli_query($db, $del_qry);
	if (!$del_res) db_error ('proc center ip 1', $del_qry);
}

//process manual add
if (isset($_POST['manual_add'])){
	//validate IP address
	$ip_parts = explode ('.',$_POST['man_add_ip']);
	if (count($ip_parts) <> 4) $_SESSION['error_text'] = "Invalid IP address - please re-enter<br>";
	if (!is_numeric ($ip_parts[0]) || !is_numeric ($ip_parts[1]) || !is_numeric ($ip_parts[2]) || !is_numeric ($ip_parts[3]) )
	   $_SESSION['error_text'] = "Invalid IP address - please re-enter<br>";
	   
	if ($_SESSION['error_text'] <> "") redirect ('form_center_ips.php');
	
    $insert_qry = "insert into center_ip (ip_address, description) values ('".$_POST['man_add_ip']."','".safe_sql($_POST['man_add_description'])."') ON DUPLICATE KEY UPDATE description = '".safe_sql($_POST['man_add_description'])."'";
	$insert_res = mysqli_query($db, $insert_qry);
	if (!$insert_res) db_error ('proc center ip 2', $insert_qry);
}
	
//process delete requests
if (isset($_POST['delete_items'])){
	foreach ($_POST['del'] as $ip => $value) {
		$del_qry = "delete from center_ip where ip_address = '".$ip."'";
		$del_res = mysqli_query($db, $del_qry);
	    if (!$del_res) db_error ('proc center ip 3', $del_qry);
	}
}

  unset ($_SESSION['err_ip']);
  redirect ("./page_ip_thanks.php");

?>


