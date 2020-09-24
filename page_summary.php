<?php 
require_once "./lib/doc_info.php"; 

if (!$_SESSION['admin_active']) {
	redirect ('index.php');
}

//if the user has NO hours booked, there won't be any records in this query for them. Need to add those to the 
//temp table using another query.
if (isset($_POST['selected_status']) && ($_POST['selected_status']) > 0) {
	$summary_qry = "select * from time_log, user where time_log.entry_status='1' and status_id = '".$_POST['selected_status']."'
	               and user.user_num = time_log.user_num";
}
else {
	$summary_qry = "select * from time_log, user where time_log.entry_status='1' and user.user_num = time_log.user_num";
}

$summary_res = mysqli_query($db, $summary_qry);
if (!$summary_res) db_error ('summary 1',$summary_qry);


//create temporary table of totals by name
$clear_qry = "delete from temp_time";
$clear_res = mysqli_query($db, $clear_qry);
if (!$clear_res) db_error ('summary 1b',$clear_qry);

while ( $summary_info = mysqli_fetch_array($summary_res)) {
	 if (!isset($total[$summary_info['user_num']]['mins'])) $total[$summary_info['user_num']]['mins'] = 0;
	 if (!isset($total[$summary_info['user_num']]['shift_mins'])) $total[$summary_info['user_num']]['shift_mins'] = 0;
	 if (!isset($total[$summary_info['user_num']]['nonshift_mins'])) $total[$summary_info['user_num']]['nonshift_mins'] = 0;
	 $total[$summary_info['user_num']]['user_num'] = $summary_info['user_num'];
	 $total[$summary_info['user_num']]['first_name'] = $summary_info['first_name'];
	 $total[$summary_info['user_num']]['last_name'] = $summary_info['last_name'];
	 $total[$summary_info['user_num']]['email'] = $summary_info['email'];
	 $total[$summary_info['user_num']]['date_joined'] = $summary_info['date_joined'];
	 $total[$summary_info['user_num']]['status'] = $summary_info['status_id'];
	 $total[$summary_info['user_num']]['mins'] = $total[$summary_info['user_num']]['mins'] + $summary_info['time_in_mins'];
	 
	 if ($summary_info['activity_type'] < 11) {
			$total[$summary_info['user_num']]['shift_mins'] = $total[$summary_info['user_num']]['shift_mins'] +
			$summary_info['time_in_mins'];
	 }
	 else   {
		    
	 		$total[$summary_info['user_num']]['nonshift_mins'] = $total[$summary_info['user_num']]['nonshift_mins'] +
			$summary_info['time_in_mins'];
	 }
	 
}

foreach ($total as $usernum => $items ) {
			 $insert_qry = "insert into temp_time (user_num, first_name, last_name, join_date, status_id, email, total_shift_mins, total_nonshift_mins, total_time_mins)
			 values
		     ('".$total[$usernum]['user_num']."','".$total[$usernum]['first_name']."','".$total[$usernum]['last_name']."','".
				 $total[$usernum]['date_joined']."','".$total[$usernum]['status']."','".
				 $total[$usernum]['email']."','".
				 $total[$usernum]['shift_mins']."','".
				 $total[$usernum]['nonshift_mins']."','".
				 $total[$usernum]['mins']."')";		
			 $insert_res = mysqli_query($db, $insert_qry);
			 if (!$insert_res) db_error ('summary 1a',$insert_qry);
}
		


$users_qry = "select * from user";
$users_res = mysqli_query($db, $users_qry);
if (!$users_res) db_error ('summary 3',$users_qry);

while ($user_info = mysqli_fetch_array ($users_res)) {
		 $check_user_qry = "select * from time_log  where user_num = '".$user_info['user_num']."'  
		 LIMIT 1";
		 $check_user_res = mysqli_query($db, $check_user_qry);
		 if (!$check_user_res) {
			 db_error ('summary 4', $check_user_qry);
		 	break;
		 }
		 $hours_booked = mysqli_num_rows ($check_user_res);
		 if (!$hours_booked) {
			$insert_qry = "insert into temp_time (user_num, first_name, last_name, join_date, status_id, email, total_time_mins, total_shift_mins, total_nonshift_mins) values
		               ('".$user_info['user_num']."','".$user_info['first_name']."','".$user_info['last_name'].
						"','".$user_info['date_joined']."','".$user_info['status_id']."','".$user_info['email']."','0','0', '0')";
		    $insert_res = mysqli_query($db, $insert_qry);
		    if (!$insert_res) db_error ('summary 2b', $insert_qry);
		 } 							   
}

//create status array
$status_qry = "select * from status_types";
$status_res = mysqli_query($db, $status_qry);
if (!$status_res) db_error('page summary 2a',$status_qry);

while ($status_info = mysqli_fetch_array($status_res)) {
	$status[$status_info['status_id']] = $status_info['description'];									  
}


?>


<h3>Summary by Volunteer</h3>
<table >
<?php
if ($_POST ['list_by'] == 1) {
	$time_qry = "select * from temp_time order by first_name, last_name, email";
	$time_res = mysqli_query($db, $time_qry);
	if (!$time_res) db_error ('summary 6a',$time_qry);
	
	echo "<tr><th>Name</th><th>Email</th><th>Join Date</th><th>Status</th><th>Shift</th><th>Non-shift</th><th>Total</th></tr>";
	while ($report_item = mysqli_fetch_array ($time_res)) {
		$display_name = $report_item['first_name']." ".$report_item['last_name'];
		$join_date = date ('m/d/y', $report_item['join_date']);
		$current_status = $status[$report_item['status_id']];	
		// Shift Time
		$remainder_shift_mins = ($report_item['total_shift_mins']%60);	
		$time_shift_hrs = ($report_item['total_shift_mins'] - $remainder_shift_mins)/60;
		$display_shift_time = $time_shift_hrs." : ".$remainder_shift_mins;
		// Total Time
		$remainder_nonshift_mins = ($report_item['total_nonshift_mins']%60);	
		$time_nonshift_hrs = ($report_item['total_nonshift_mins'] - $remainder_nonshift_mins)/60;
		$display_nonshift_time = $time_nonshift_hrs." : ".$remainder_nonshift_mins;
		// Total Time
		$remainder_mins = ($report_item['total_time_mins']%60);	
		$time_hrs = ($report_item['total_time_mins'] - $remainder_mins)/60;
		$display_time = $time_hrs." : ".$remainder_mins;

		echo "<tr><td>".$display_name."</td><td>".$report_item['email']."</td><td>".$join_date."</td><td>".
		$current_status."</td><td>".$display_shift_time."</td><td>".$display_nonshift_time."</td><td>".$display_time."</td></tr>";								  
	}
}
else {
	$time_qry = "select * from temp_time order by total_time_mins DESC, first_name, last_name, email";
	$time_res = mysqli_query($db, $time_qry);
	if (!$time_res) db_error ('summary 6',$time_qry);

echo "<tr><th>Total Time</th><th>Name</th><th>Email</th><th>Join Date</th><th>Status</th></tr>";
while ($report_item = mysqli_fetch_array ($time_res)) {
	$display_name = $report_item['first_name']." ".$report_item['last_name'];
	$join_date = date ('m/d/y', $report_item['join_date']);
	$current_status = $status[$report_item['status_id']];	
		$remainder_mins = ($report_item['total_time_mins']%60);	
		$time_hrs = ($report_item['total_time_mins'] - $remainder_mins)/60;
		$display_time = $time_hrs." : ".$remainder_mins;
	
	echo "<tr><td>".$display_time."</td><td>".$display_name."</td><td>".$report_item['email']."</td><td>".$join_date."</td><td>".
	$current_status."</td></tr>";								  
	
}

}
?>
</table>


<?php require_once "./lib/page_foot.php";?>

