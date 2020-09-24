<?php 
require_once "./lib/doc_info.php"; 

?>

<?php 
$user_qry = "select * from user order by first_name";
$user_res = mysqli_query($db, $user_qry);
if (!$user_res) db_error ('page reports 1', $user_qry);

$status_qry = "select * from status_types order by description";
$status_res = mysqli_query($db, $status_qry);
if (!$status_res) db_error ('page reports 2', $status_qry);

//if the footer button is clicked, and there is no phone shift active, just go to the logout process.
if (!$_SESSION['admin_active']) {
	redirect ('index.php');
}
?>

<h2>Reports</h2>
<?php 
        if (isset ($_SESSION['error_text'])) {
	        echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
	        $_SESSION['error_text'] = "";
        }

?>
<h3>Summary - all volunteers</h3>
<p>This report lists all volunteers and their total hours. By default, all statuses are included. If you want a report from a single group, select it from the drop-down list before you run the report. You can select to list the report by number of hours (most to least) or by volunteer name. Default is by name. </p>

<p><form method='POST' action='page_summary.php' >
<select name=selected_status size = 1 ><option value ='0'>All statuses</option>
<?php 
   while ($status_info = mysqli_fetch_array($status_res) ) {
	   echo "<option value='".$status_info['status_id']."'>".$status_info['description']."</option>";
   }
?>

</select>&nbsp;&nbsp;
<select name=list_by size = 1 >
	<option value ='1' selected >List by name</option>
	<option value ='2'  >List by total hours</option>


</select>


&nbsp;&nbsp;<input type=submit name='edit' value='Volunteer Summary' /></form></p>
<p>&nbsp;</p>
<h3>Detail Report - Single Volunteer</h3>
<p>Choose a volunteer from the list to view a report of that user. The report includes approved, pending, and rejected items.</p>

<p><form method='POST' action='page_single_user.php' >
<select name=selected_user size = 1 >
<?php 
   while ($user_info = mysqli_fetch_array($user_res) ) {
	   echo "<option value='".$user_info['user_num']."'>".$user_info['first_name']." ".$user_info['last_name']."</option>";
   }
?>

</select>&nbsp;&nbsp;<input type=submit name='edit' value='Volunteer Detail' /></p></form>

<form method='POST' action='page_meeting.php' >

<p>&nbsp;</p>
<h3>Meeting Display - All Active Volunteers</h3>
<p>This list is displayed in a large font suitable for display during the semester meeting. 8 names are displayed per page. Only users with a status of active are listed; If volunteers are returning from separation or alumni status, change their status first to have them shown in this display.</p>

<p><input type=submit name='edit' value='Meeting Display' /></form></p>



<form method='POST' action='page_shift_range.php' >

<p>&nbsp;</p>
<h3>Shift Coverage By Date</h3>
<p>Display shift coverage for a range of dates. If you leave the date entries blank, the default display is the last seven days.</p>
<p>Start (mm/dd/yy)&nbsp;<input type="text" name="start" size="8" />&nbsp;&nbsp;End (mm/dd/yy)&nbsp;<input type="text" name ="end" size ="8" />

<p><input type=submit name='edit' value='Display Shift Coverage' /></form></p>

<form method='POST' action='page_csv.php' >

<p>&nbsp;</p>
<h3>Download Completed Shift Information</h3>
<p>This form will create a downloaded CSV (comma separated variable) file that you can import into Excel. Depending on the browser you are using, it will prompt you for a download location, or may store the downloaded CSV file in the 'download' directory. The file will be called 'shift_report.csv'. If you download a second one, it will be named 'shift_report(1).csv' and so on. Delete the CSV reports after use to avoid confusion! If you leave the date entries blank, the default display is the last seven days.</p>
<p>Start (mm/dd/yy)&nbsp;<input type="text" name="start" size="8" />&nbsp;&nbsp;End (mm/dd/yy)&nbsp;<input type="text" name ="end" size ="8" />

<p><input type=submit name='edit' value='Download Report' /></form></p>


<?php
unset ($_SESSION['err_start']);
unset ($_SESSION['err_end']);
?>

<?php require_once "./lib/page_foot.php";?>

