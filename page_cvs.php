<?php 
require_once "./lib/doc_info.php"; 

$_SESSION['err_start'] = $_POST['start'];
$_SESSION['err_end'] = $_POST['end'];
$_SESSION['error_text'] = "";

if (!$_SESSION['admin_active']) {
	redirect ('index.php');
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


$shift_qry = "select * from time_log where start_timestamp >= '".$start_timestamp."' 
									    and   start_timestamp =< '".$end_timestamp."'
										and activity_type < '11'
										and entry_status = '1' 
                 order by start_timestamp";

$shift_res = mysqli_query($db, $shift_qry);
if (!$shift_res) db_error ('page single user 2',$shift_qry);

$total_qry = "select SUM(time_in_mins) from time_log where start_timestamp > '".$start_timestamp."' 
									    and   start_timestamp < '".$end_timestamp."'
										and activity_type = '10'
										and entry_status = '1'" ;
$total_res = mysqli_query($db, $total_qry);
if (!$total_res) db_error ('page singe user 2a',$total_qry);
$total_info = mysqli_fetch_array ($total_res);
$total_mins = $total_info['SUM(time_in_mins)']; 
$shift_mins = ($total_mins%60);
$total_mins = $total_mins - $shift_mins;
$shift_hrs = $total_mins/60;



date_default_timezone_set('America/Denver');
/*

while ($shift_item = mysqli_fetch_array ($shift_res)) {
//echo "<br>".$shift_item['start_timestamp']."<br>";	
    $start_date = date ('D: m/d/y',$shift_item['start_timestamp']);
	$start_time = date ('h:i a',$shift_item['start_timestamp']);
	$total_time_mins = $shift_item['time_in_mins'];
	$shift_time_mins = $total_time_mins%60;	
	$total_time_mins = $total_time_mins - $shift_time_mins;
	$shift_time_hrs = $total_time_mins/60;
	$end_time = $shift_item['start_timestamp'] + ($shift_item['time_in_mins']*60);
	$end_time_display = date ('h:i a',$end_time);
	
    $user_qry = "select * from user where user_num ='".$shift_item['user_num']."'";
    $user_res = mysqli_query($db, $user_qry);
    if (!$user_res) db_error ('page single user 1',$user_qry);
    $user_info = mysqli_fetch_array ($user_res);
	$current_date = $start_date;
	if ($first) {		
		$previous_date = $start_date;
		$first = 0;
	}
	else {
		  if ($current_date <> $previous_date) {
			  echo "</table><p></p><table border = 1><tr><th>Date</th><th>Start</th><th>End</th><th>Shift Time</th><th>Volunteer</th></tr>";
			  $previous_date = $current_date;
		  }
	}
	
	echo "<tr><td>".$start_date."</td><td>".$start_time."</td><td>".$end_time_display."</td><td>".$shift_time_hrs." : ".
	      $shift_time_mins."</td><td>".$user_info['first_name']." ".$user_info['last_name']."</td></tr>";
}

?>
</table>
*/
header('Content-type: application/csv');
header("Content-Disposition: attachment; filename=shift_report.csv");
header("Cache-control: private"); //use this to open files directly

$sep = "\",\"";

$data = "First,\"Last\",\"Date\",\"Start\",\"Duration\"\r\n ";
echo $data;

while ($shift_item = mysqli_fetch_array ($shift_res)) {
	$start_date = date ('D: m/d/y',$shift_item['start_timestamp']);
	$start_time = date ('h:i a',$shift_item['start_timestamp']);
	$total_time_mins = $shift_item['time_in_mins'];
	$shift_time_mins = $total_time_mins%60;	
	$total_time_mins = $total_time_mins - $shift_time_mins;
	$shift_time_hrs = $total_time_mins/60;
	$end_time = $shift_item['start_timestamp'] + ($shift_item['time_in_mins']*60);
	$end_time_display = date ('h:i a',$end_time);
	
    $user_qry = "select * from user where user_num ='".$shift_item['user_num']."'";
    $user_res = mysqli_query($db, $user_qry);
    if (!$user_res) db_error ('page single user 1',$user_qry);
    $user_info = mysqli_fetch_array ($user_res);
	
	$data = "\"".$user_info['last_name'].$sep.$user_info['first_name'].$sep.$start_date.$sep.$shift_time_hrs."\"\r\n";

	 
	 echo $data; 
	 }




?>




