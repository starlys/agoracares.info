<?php 
require_once "./lib/doc_info.php"; 


?>
<form method="POST" action="proc_approvals.php">
<?php 


//if the footer button is clicked, and there is no phone shift active, just go to the logout process.
if (!$_SESSION['admin_active']) {
	redirect ('index.php');
}

$pending_qry = "select * from time_log where entry_status = '0' order by start_timestamp";
$pending_res = mysqli_query($db, $pending_qry);
if (!$pending_res) db_error ('form approval 1',$pending_qry);

$total_qry = "select SUM(time_in_mins) from time_log where user_num = '".$_SESSION['current_user_num']."' and entry_status = '0'";
$total_res = mysqli_query($db, $total_qry);
if (!$total_res) db_error ('page history 2',$total_qry);
$total_info = mysqli_fetch_array ($total_res);
$total_mins = $total_info['SUM(time_in_mins)']; 
$pending_mins = ($total_mins%60);
$total_mins = $total_mins - $pending_mins;
$pending_hrs = $total_mins/60;

$activity_qry = "select * from activity_types";
$activity_res = mysqli_query($db, $activity_qry);
if (!$activity_res) db_error('form approval 2',$activity_qry);

while ($activity_info = mysqli_fetch_array($activity_res)) {
	$activity[$activity_info['type']] = $activity_info['description'];									  
}
$activity[999] = "Other";

?>
<h2>Time Record Approval</h2>
<p>For each item, you can select No Change (N), Approved (A), or Rejected (R). If you select Rejected, add a note so that the volunteer will know why. This explanation will be displayed to the user when they look at their time history.</p>

<p>&nbsp;</p>

<h3>Pending Items</h3>
<table width=750px border=1 cellpadding=1>
<?php
echo "<tr><th>Recorded/<br>Work Date</th><th>Name</th><th>Activity</th><th>Time<br>(hr:min)</th><th>Action</th><th>Notes</th></tr>";
while ($pending_item = mysqli_fetch_array ($pending_res)) {
	$key = $pending_item['user_num'].".".$pending_item['start_timestamp'];
	
	$name_qry = "select * from user where user_num = '".$pending_item['user_num']."'";
	$name_res = mysqli_query($db, $name_qry);
	if (!$name_res) db_error('form approvals 3', $name_res);
	$name_info = mysqli_fetch_array($name_res);
	$display_name =  $name_info['first_name']."<br>".$name_info['last_name'];
	
	$recorded = date ('m/d/y',$pending_item['start_timestamp']);
	$current_activity = $activity[$pending_item['activity_type']];	
	$total_time_mins = $pending_item['time_in_mins'];
	$pending_time_mins = ($total_time_mins%60);	
	$total_time_mins = $total_time_mins - $pending_time_mins;
	$pending_time_hrs = $total_time_mins/60;
	echo "<tr valign='top'><td>".$recorded."<br>".$pending_item['display_date']."</td><td>".$display_name."</td><td>".$current_activity."</td><td>".
	$pending_time_hrs." : ".$pending_time_mins."</td>
	<td>&nbsp;N&nbsp;<input type=radio name = action[".$key."] value=0 checked><br>&nbsp;A&nbsp;<input type=radio name = action[".$key."] value=1 ><br>&nbsp;R&nbsp;<input type=radio name = action[".$key."] value=2 ></td>
	<td><input type=text size=30 name = note[".$key."] value ='".$pending_item['notes']."'</td></tr>";								  
}

?>

</table>
<p>&nbsp;</p>
<p><input type=submit name='approve' value ='Update Items' /></p>
</form>

<?php require_once "./lib/page_foot.php";?>

