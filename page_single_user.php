<?php 
require_once "./lib/doc_info.php"; 
date_default_timezone_set('America/Denver');

//if the footer button is clicked, and there is no phone shift active, just go to the logout process.
if (!$_SESSION['admin_active']) {
	redirect ('index.php');
}
if (isset($_POST['selected_user'])) $_SESSION['selected_user'] = $_POST['selected_user'];

$user_qry = "select * from user where user_num ='".$_SESSION['selected_user']."'";

$user_res = mysqli_query($db, $user_qry);
if (!$user_res) db_error ('page single user 1',$user_qry);
$user_info = mysqli_fetch_array ($user_res);

$approved_qry = "select * from time_log where user_num = '".$_SESSION['selected_user']."' and entry_status = '1' 
                 order by start_timestamp";
$approved_res = mysqli_query($db, $approved_qry);
if (!$approved_res) db_error ('page single user 2',$approved_qry);

$total_shift_qry = "select SUM(time_in_mins) from time_log where 
						user_num = '".$_SESSION['selected_user']."'". 
						" and activity_type < '11' and entry_status = '1'";
$total_shift_res = mysqli_query($db, $total_shift_qry);
if (!$total_shift_res) db_error ('page singe user 2a',$total_shift_qry);
$total_shift_info = mysqli_fetch_array ($total_shift_res);
$total_shift_mins = $total_shift_info['SUM(time_in_mins)']; 

$total_nonshift_qry = "select SUM(time_in_mins) from time_log where 
						user_num = '".$_SESSION['selected_user']."'". 
						" and activity_type = '11' and entry_status = '1'";
$total_nonshift_res = mysqli_query($db, $total_nonshift_qry);
if (!$total_nonshift_res) db_error ('page singe user 2a',$total_nonshift_qry);
$total_nonshift_info = mysqli_fetch_array ($total_nonshift_res);
$total_nonshift_mins = $total_nonshift_info['SUM(time_in_mins)'];

$total_mins = $total_nonshift_mins + $total_shift_mins;

$approved_shift_mins = ($total_shift_mins%60);
$total_shift_mins = $total_shift_mins - $approved_shift_mins;
$approved_shift_hrs = $total_shift_mins/60;

$approved_nonshift_mins = ($total_nonshift_mins%60);
$total_nonshift_mins = $total_nonshift_mins - $approved_nonshift_mins;
$approved_nonshift_hrs = $total_nonshift_mins/60;

$approved_mins = ($total_mins%60);
$total_mins = $total_mins - $approved_mins;
$approved_hrs = $total_mins/60;

$pending_qry = "select * from time_log where user_num = '".$_SESSION['selected_user']."' and entry_status = '0' 
                 order by start_timestamp";
$pending_res = mysqli_query($db, $pending_qry);
if (!$pending_res) db_error ('page single user 3',$pending_qry);

$total_qry = "select SUM(time_in_mins) from time_log where user_num = '".$_SESSION['selected_user']."' and entry_status = '0'";
$total_res = mysqli_query($db, $total_qry);
if (!$total_res) db_error ('page single user 4',$total_qry);
$total_info = mysqli_fetch_array ($total_res);
$total_mins = $total_info['SUM(time_in_mins)']; 
$pending_mins = ($total_mins%60);
$total_mins = $total_mins - $pending_mins;
$pending_hrs = $total_mins/60;

$rejected_qry = "select * from time_log where user_num = '".$_SESSION['selected_user']."' and entry_status = '2' 
                 order by start_timestamp";
$rejected_res = mysqli_query($db, $rejected_qry);
if (!$rejected_res) db_error ('page single user 5',$rejected_qry);
$rejected_items = mysqli_num_rows ($rejected_res);

$total_qry = "select SUM(time_in_mins) from time_log where user_num = '".$_SESSION['selected_user']."' and entry_status = '2'";
$total_res = mysqli_query($db, $total_qry);
if (!$total_res) db_error ('page history 2',$total_qry);
$total_info = mysqli_fetch_array ($total_res);
$total_mins = $total_info['SUM(time_in_mins)']; 
$rejected_mins = ($total_mins%60);
$total_mins = $total_mins - $rejected_mins;
$rejected_hrs = $total_mins/60;

$activity_qry = "select * from activity_types";
$activity_res = mysqli_query($db, $activity_qry);
if (!$activity_res) db_error('page single user 5a',$activity_qry);

while ($activity_info = mysqli_fetch_array($activity_res)) {
	$activity[$activity_info['type']] = $activity_info['description'];									  
}
$activity[999] = "Other";

?>

<h2>Time Report For <?php echo $user_info['first_name']." ".$user_info['last_name']; ?></h2>
<table >
<tr><td><strong>Approved (shift):</td><td> <?php echo  $approved_shift_hrs." hours ".$approved_shift_mins." minutes";?></strong></td></tr>
<tr><td><strong>Approved (nonshift):</td><td> <?php echo  $approved_nonshift_hrs." hours ".$approved_nonshift_mins." minutes";?></strong></td></tr>
<tr><td><strong>Total Approved:</td><td> <?php echo  $approved_hrs." hours ".$approved_mins." minutes";?></strong></td></tr>
<tr><td><strong>Total Pending:</td><td> <?php echo  $pending_hrs." hours ".$pending_mins." minutes";?></strong></td></tr>
<?php if ($rejected_items) {
	echo "<tr><td><strong>Total Rejected:</td><td>".$rejected_hrs." hours ".$rejected_mins." minutes</strong></td></tr>";
} ?>
</table>

<p><a href=form_adjust_user.php>Adjust this volunteer's hours</a></p>

<h3>Pending Items</h3>
<table border=1>
<?php
echo "<tr><th>Recorded</th><th>Work Date</th><th>Activity</th><th>Time<br>(hr:min)</th><th>Notes</th></tr>";
while ($pending_item = mysqli_fetch_array ($pending_res)) {
	$recorded = date ('m/d/y',$pending_item['start_timestamp']);
	$current_activity = $activity[$pending_item['activity_type']];	
	$total_time_mins = $pending_item['time_in_mins'];
	$pending_time_mins = ($total_time_mins%60);	
	$total_time_mins = $total_time_mins - $pending_time_mins;
	$pending_time_hrs = $total_time_mins/60;
	echo "<tr><td>".$recorded."</td><td>".$pending_item['display_date']."</td><td>".$current_activity."</td><td>".
	$pending_time_hrs." : ".$pending_time_mins."</td><td>".$pending_item['notes']."</td></tr>";								  
}

?>
</table>
<p>&nbsp;<br />&nbsp;<br /></p>
<h3>Approved Items</h3>
<table border=1>
<?php
echo "<tr><th>Recorded</th><th>End</th><th>Work Date</th><th>Time<br>(hr:min)</th><th>Activity</th><th>Notes</th></tr>";
while ($approved_item = mysqli_fetch_array ($approved_res)) {
	$start_date = date ('m/d/y',$approved_item['start_timestamp']);
	$start_time = date ('h:i a',$approved_item['start_timestamp']);
	$current_activity = $activity[$approved_item['activity_type']];
	$total_time_mins = $approved_item['time_in_mins'];
	$approved_time_mins = $total_time_mins%60;	
	$total_time_mins = $total_time_mins - $approved_time_mins;
	$approved_time_hrs = $total_time_mins/60;
	$end_time = $approved_item['start_timestamp'] + ($approved_item['time_in_mins']*60);
	$end_time_display = date ('h:i a',$end_time);
	echo "<tr>";
	if ($approved_item['activity_type'] ==1) {
		  echo "<td>".$start_date." - ".$start_time."</td><td>".$end_time_display."</td><td>&nbsp;</td>";
	}
	else {
		  echo "<td>".$start_date."</td><td>&nbsp;</td><td>".$approved_item['display_date']."</td>";
	}
	echo "<td>".$approved_time_hrs." : ".$approved_time_mins.
	     "</td><td>".$current_activity."</td><td>".$approved_item['notes']."</td></tr>";								  
}

?>
</table>
<p>&nbsp;<br />&nbsp;<br /></p>
<h3>Rejected Items</h3>
<table border=1>
<?php
echo "<tr><th>Recorded</th><th>Work Date</th><th>Activity</th><th>Time<br>(hr:min)</th><th>Notes</th></tr>";
while ($rejected_item = mysqli_fetch_array ($rejected_res)) {
	$recorded = date ('m/d/y',$rejected_item['start_timestamp']);
	$current_activity = $activity[$rejected_item['activity_type']];
	$total_time_mins = $rejected_item['time_in_mins'];
	$rejected_time_mins = $total_time_mins%60;
	$total_time_mins = $total_time_mins - $rejected_time_mins;
	$rejected_time_hrs = $total_time_mins/60;
	echo "<tr><td>".$recorded."</td><td>".$rejected_item['display_date']."</td><td>".$current_activity."</td><td>".
	$rejected_time_hrs." : ".$rejected_time_mins."</td><td>".$rejected_item['notes']."</td></tr>";									
}

?>
</table>


<?php require_once "./lib/page_foot.php";?>

