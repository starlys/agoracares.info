<?php require_once "./lib/doc_info.php"; 

/*allows user to start a phone shift, or to report some additional time worked.
can be called by the proc routine (on data errors) or by the select user form 
so store user num from select form as a session variable
*/
?>

    
<?php 

if (!$_SESSION['admin_active']) redirect ('index.php');
if (isset ($_POST['selected_user']))$_SESSION['selected_user'] = $_POST['selected_user'];
$user_qry = "select * from user where user_num ='".$_SESSION['selected_user']."'";
$user_res = mysqli_query($db, $user_qry);
if (!$user_res) db_error ('form admin user 1', $user_qry);
$user_info = mysqli_fetch_array($user_res);

$activity_qry = "select * from activity_types";
$activity_res = mysqli_query($db, $activity_qry);
if (!$activity_res) db_error('page history 5',$activity_qry);

while ($activity_info = mysqli_fetch_array($activity_res)) {
	$activity[$activity_info['type']] = $activity_info['description'];									  
}


$approved_qry = "select * from time_log where user_num = '".$_SESSION['selected_user']."' and entry_status = '1' 
                 order by start_timestamp DESC";
$approved_res = mysqli_query($db, $approved_qry);
if (!$approved_res) db_error ('page history 1',$approved_qry);


?>	

<form method="POST" action="proc_adjust_user.php">
<h2>Update Time Records For <?php echo $user_info['first_name']." ".$user_info['last_name'];?></h2>  
<?php
        if (isset ($_SESSION['error_text'])) {
	        echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
	        $_SESSION['error_text'] = "";
        }


?>  
<h3>Add Service Hours</h3>
    <p>Use this form to add any additional hours the volunteer has worked.  For most options, you should also add a note explaining what you did. You can use minus (-) hours to reduce the overall hours for the user.</p>
<p>	</p>
<table>
<tr><td>Service Hours:</td><td> <input type=text name=hours size=2></td></tr>
<tr><td>Effective date (mm/dd/yy):</td><td> <input type=text name=display_date size=8></td></tr>
<tr><td>Activity:</td><td> <select name=activity size=1>";
<?php
foreach ($activity  as $activity_type => $activity_desc) {
	echo "<option value ='".$activity_type."'>".$activity_desc."</option>";
}
?>


</select></td></tr>
<tr><td>Notes:</td><td><input type=text name=note size=60></td></tr>
</table>
<p><input type=submit name=extra_hours value ='Add Service Hours'></p>
<p>&nbsp;</p>
<h3>Reject Invalid Shifts</h3>
<p>In the list below, mark any phone shifts that were entered in error, or recorded but not worked. The record will not actually be deleted, but the status will be changed to 'rejected'. Include a note for the volunteer so that they know why the item was rejected.</p>
<table border=1>
<tr><th>Recorded/<br>Work Date</th><th>Activity</th><th>Time<br>(hr:min)</th><th>Reject</th><th>Notes</th></tr>

<?php 

while ($approved_item = mysqli_fetch_array($approved_res)) {
	$key= $_SESSION['selected_user'].".".$approved_item['start_timestamp'];
	$recorded = date ('m/d/y',$approved_item['start_timestamp']);
	$current_activity = $activity[$approved_item['activity_type']];
	$total_time_mins = $approved_item['time_in_mins'];
	$approved_time_mins = $total_time_mins%60;	
	$total_time_mins = $total_time_mins - $approved_time_mins;
	$approved_time_hrs = $total_time_mins/60;
	if ($recorded == $approved_item['display_date']) $work_date = "";
	else $work_date = $approved_item['display_date'];
	echo "<tr><td>".$recorded."<br>".$work_date."</td><td>&nbsp;".$current_activity."</td><td>&nbsp;".
	$approved_time_hrs." : ".$approved_time_mins."</td>
	<td align=center><input type = checkbox name = del[".$key."] value = 1></td>
	<td><input type = text name = notes[$key] size= 30 value='".$approved_item['notes']."'></td></tr>";								
}

?>
</table>
<p>&nbsp;</p>
<p><input type=submit name=reject_hours value ='Reject Marked Hours'></p>

</form>

<?php require_once "./lib/page_foot.php";?>