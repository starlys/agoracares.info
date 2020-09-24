<?php 
require_once "./lib/doc_info.php"; 

//if the footer button is clicked, and there is no phone shift active, just go to the logout process.
if (!$_SESSION['current_user_num']) {
	redirect ('index.php');
}

$approved_qry = "select * from time_log where user_num = '".$_SESSION['current_user_num']."' and entry_status = '1' 
                 order by start_timestamp DESC";
$approved_res = mysqli_query($db, $approved_qry);
if (!$approved_res) db_error ('page history 1',$approved_qry);

$total_qry = "select SUM(time_in_mins) from time_log where user_num = '".$_SESSION['current_user_num']."' and entry_status = '1'";
$total_res = mysqli_query($db, $total_qry);
if (!$total_res) db_error ('page history 2',$total_qry);
$total_info = mysqli_fetch_array ($total_res);
$total_mins = $total_info['SUM(time_in_mins)']; 
$approved_mins = ($total_mins%60);
$total_mins = $total_mins - $approved_mins;
$approved_hrs = $total_mins/60;

$pending_qry = "select * from time_log where user_num = '".$_SESSION['current_user_num']."' and entry_status = '0' 
                 order by start_timestamp DESC";
$pending_res = mysqli_query($db, $pending_qry);
if (!$pending_res) db_error ('page history 3',$pending_qry);

$total_qry = "select SUM(time_in_mins) from time_log where user_num = '".$_SESSION['current_user_num']."' and entry_status = '0'";
$total_res = mysqli_query($db, $total_qry);
if (!$total_res) db_error ('page history 2',$total_qry);
$total_info = mysqli_fetch_array ($total_res);
$total_mins = $total_info['SUM(time_in_mins)']; 
$pending_mins = ($total_mins%60);
$total_mins = $total_mins - $pending_mins;
$pending_hrs = $total_mins/60;

$rejected_qry = "select * from time_log where user_num = '".$_SESSION['current_user_num']."' and entry_status = '2' 
                 order by start_timestamp DESC";
$rejected_res = mysqli_query($db, $rejected_qry);
if (!$rejected_res) db_error ('page history 4',$rejected_qry);
$rejected_items = mysqli_num_rows ($rejected_res);

$total_qry = "select SUM(time_in_mins) from time_log where user_num = '".$_SESSION['current_user_num']."' and entry_status = '2'";
$total_res = mysqli_query($db, $total_qry);
if (!$total_res) db_error ('page history 2',$total_qry);
$total_info = mysqli_fetch_array ($total_res);
$total_mins = $total_info['SUM(time_in_mins)']; 
$rejected_mins = ($total_mins%60);
$total_mins = $total_mins - $rejected_mins;
$rejected_hrs = $total_mins/60;

$activity_qry = "select * from activity_types";
$activity_res = mysqli_query($db, $activity_qry);
if (!$activity_res) db_error('page history 5',$activity_qry);

while ($activity_info = mysqli_fetch_array($activity_res)) {
	$activity[$activity_info['type']] = $activity_info['description'];									  
}
$activity[999] = "Other";

?>
<h2>Time Report</h2>
<table >
<tr><td><strong>Total Approved:</td><td> <?php echo  $approved_hrs." hours ".$approved_mins." minutes";?></strong></td></tr>
<tr><td><strong>Total Pending:</td><td> <?php echo  $pending_hrs." hours ".$pending_mins." minutes";?></strong></td></tr>
<?php if ($rejected_items) {
	echo "<tr><td><strong>Total Rejected:</td><td>".$rejected_hrs." hours ".$rejected_mins." minutes</strong></td></tr>";
} ?>
</table>
<p>&nbsp;<br />&nbsp;<br /></p>
<h3>Pending Items</h3>
<table border=1>
<?php
echo "<tr><th>Recorded</th><th>Work Date</th><th>Activity</th><th>Time<br>(hr:min)</th><th>Notes</th></tr>";
while ($pending_item = mysqli_fetch_array ($pending_res)) {
	$recorded = date ('m/d/y h:i a',$pending_item['start_timestamp']);
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
echo "<tr><th>Recorded</th><th>Work Date</th><th>Activity</th><th>Time<br>(hr:min)</th><th>Notes</th></tr>";
while ($approved_item = mysqli_fetch_array ($approved_res)) {
	$recorded = date ('m/d/y h:i a',$approved_item['start_timestamp']);
	$current_activity = $activity[$approved_item['activity_type']];
	$total_time_mins = $approved_item['time_in_mins'];
	$approved_time_mins = $total_time_mins%60;	
	$total_time_mins = $total_time_mins - $approved_time_mins;
	$approved_time_hrs = $total_time_mins/60;
	echo "<tr><td>".$recorded."</td><td>".$approved_item['display_date']."</td><td>".$current_activity."</td><td>".
	$approved_time_hrs." : ".$approved_time_mins."</td><td>".$approved_item['notes']."</td></tr>";								  
}

?>
</table>
<p>&nbsp;<br />&nbsp;<br /></p>
<h3>Rejected Items</h3>
<table border=1>
<?php
echo "<tr><th>Recorded</th><th>Work Date</th><th>Activity</th><th>Time<br>(hr:min)</th><th>Notes</th></tr>";
while ($rejected_item = mysqli_fetch_array ($rejected_res)) {
	$recorded = date ('m/d/y h:i a',$rejected_item['start_timestamp']);
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

