<?php 
require_once './lib/opendb.php';
require_once './lib/redirect.php';
require_once './lib/check_ip.php';
require_once './lib/email_error.php';
require_once "./lib/date_convert.php";
require_once "./lib/email_admins.php";


if (!isset ($_SESSION['current_user_num'])) $_SESSION['current_user_num'] = 0;
if (!isset ($_SESSION['shift_start_time'])) $_SESSION['shift_start_time'] = 0;
$_SESSION['current_script'] = $_SERVER["SCRIPT_NAME"];
$last_slash = strrpos($_SESSION['current_script'],'/');
$_SESSION['current_script']=substr($_SESSION['current_script'],$last_slash+1);
check_ip_in_center ($_SERVER['REMOTE_ADDR']);


date_default_timezone_set('America/Denver');
ini_set("precision", 2);
$_SESSION['err_start'] = $_POST['start'];
$_SESSION['err_end'] = $_POST['end'];
$_SESSION['error_text'] = "";

if (!$_SESSION['admin_active']) {
//	redirect ('index.php');
}


//validate dates. Both are optional. If entered, they must be valid format

if (isset ($_POST['start']) && (strlen($_POST['start'])) ) {
    $start_timestamp = DateInputToTimestamp ($_POST['start']);
	if (!$start_timestamp) $_SESSION['error_text'] .= "Invalid start date for shift coverage report.<br>";

}
else {
	  $today = date ('m/d/y', time());
	  $today_timestamp = strtotime ($today);
	  $week_in_secs = 7*24*60*60;
	  $start_timestamp = $today_timestamp - $week_in_secs;
}
if (isset ($_POST['end']) && ($_POST['end'] <> "")) {
    $end_timestamp = DateInputToTimestamp ($_POST['end']);
	if (!$start_timestamp) $_SESSION['error_text'] .= "Invalid end date for shift coverage report.<br>";
}
else {  
	  $end_timestamp = time ();
}

if ($end_timestamp <= $start_timestamp) $_SESSION['error_text'] .= "Invalid date range for shift coverage report.<br>";


//end validation
if ($_SESSION['error_text'] <> "") redirect ("page_reports.php");


$shift_qry = "select * from time_log where start_timestamp > '".$start_timestamp."' 
									    and   start_timestamp < '".$end_timestamp."'
										and activity_type < '11'
										and entry_status = '1' 
                 order by start_timestamp";

$shift_res = mysqli_query($db, $shift_qry);
if (!$shift_res) db_error ('page single user 2',$shift_qry);




ob_clean();
header('Content-type: application/csv');
header("Content-Disposition: attachment; filename=shift_report.csv");
header("Cache-control: private"); //use this to open files directly

$sep = "\",\"";

$data = "\"First\",\"Last\",\"Date\",\"Start\",\"End\",\"Duration (hrs)\"\r\n ";
echo $data;
$data = "\"".''.$sep.''.$sep.''.$sep.''.$sep.''.$sep.''."\"\r\n";
echo $data;
while ($shift_item = mysqli_fetch_array ($shift_res)) {
	$start_date = date ('m/d/y',$shift_item['start_timestamp']);
	$start_time = date ('h:i A',$shift_item['start_timestamp']);
	$total_time_mins = $shift_item['time_in_mins'];
	$shift_time_hrs = $total_time_mins/ 60.00;
	$end_time = $shift_item['start_timestamp'] + ($shift_item['time_in_mins']*60);
	$end_time_display = date ('h:i A',$end_time);
	
    $user_qry = "select * from user where user_num ='".$shift_item['user_num']."'";
    $user_res = mysqli_query($db, $user_qry);
    if (!$user_res) db_error ('page single user 1',$user_qry);
    $user_info = mysqli_fetch_array ($user_res);
	
	$data = "\"".$user_info['last_name'].$sep.$user_info['first_name'].$sep.$start_date.$sep.$start_time.$sep.$end_time_display.$sep.$shift_time_hrs."\"\n";

	 
	 echo $data; 
	 }


exit();

?>




