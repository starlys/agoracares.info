<?php require_once "./lib/doc_info.php"; 

/*
allows user to start a phone shift, or to report some additional time worked.
This variable $_SESSION['in_center'] is set if the IP address is in the center IP list
*/

if (!$_SESSION['current_user_num']) redirect ('index.php');

//get an array of activities
$activity_qry = "select * from activity_types where type > '1' order by description";
$activity_res = mysqli_query($db, $activity_qry);
if (!$activity_res) db_error ('time.php','$activity_qry');

?>	

<form method="POST" action="proc_time.php">
<?php 
if ($_SESSION['in_center'] && ($_SESSION['shift_start_time'] == 0) ) {  //user in center, but not on shift
    echo "<h2>Start Shift</h2>
    <p>Click the button to start recording time for your shift. At the end of your shift, return to this page to record the end of your shift, or just click the LOGOUT button at the bottom of any page. If you do not END your shift, you will not get ANY service hours credit! </p>
<p><input type=submit name=start_shift value ='START PHONE SHIFT'></p>";
}
    
if ($_SESSION['in_center'] && ($_SESSION['shift_start_time'] <> 0) ) {  //user in center, AND on shift
    echo "<h2>End Shift</h2>
    <p>Click the button to record the end of your shift. Your shift time will be added to your volunteer balance. ";
	echo "Enter the number of people left on shift - if no-one, enter zero.</p>";
	
	if (isset ($_SESSION['error_text'])) {
		echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
		$_SESSION['error_text'] = "";
	}

    echo "<p>&nbsp;	</p>
    <p><strong>How many people will be left on shift?&nbsp;&nbsp;<input type=text name='still_on' size=2></strong></p>
    <p><input type=submit name=end_shift value ='END PHONE SHIFT'></p>";
}



echo "
<p>&nbsp;<br>&nbsp;<br>&nbsp;<br></p>
<h2>Add Service Hours</h2>
    <p>Use this form to add any additional hours you have worked. If the correct type of activity is not listed, choose OTHER. For most options, you should also add a note explaining what you did (e.g. 'tabled at welcome back days'). These hours will NOT be added to your balance automatically; a staff member will have to approve them. Contact a staff member if your service hour credits do not appear within a week.</p>
<p>	</p>
<table>
<tr><td>Hours claimed:</td><td> <input type=text name=hours size=2></td></tr>
<tr><td>Date worked (dd/mm/yy):</td><td> <input type=text name=display_date size=8></td></tr>
<tr><td>Activity:</td><td> <select name=activity size=1>";

while ($activity_info = mysqli_fetch_array ($activity_res)) {
	echo "<option value ='".$activity_info['type']."'>".$activity_info['description']."</option>";
}

echo "<option value='999'>OTHER (add note)</option>
</select></td></tr>
<tr><td>Notes to staff:</td><td> <input type=text name=note size=60></td></tr>
</table>
<p><input type=submit name=extra_hours value ='ADD EXTRA HOURS'></p>";


    

?>
</form>
    
<?php require_once "./lib/page_foot.php";?>