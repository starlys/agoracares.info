<?php 
require_once "./lib/doc_info.php"; 

//allows user to start a phone shift, or to report some additional time worked.
?>

<script>
function openUp(url) {
 newWindow = window.open(url, '', 'height=768,width=1024', false);
}
</script> 

<?php 

if (!$_SESSION['admin_active']) {
	redirect ('index.php');
}
//this routine creates two session arrays - one for the list of all users who are ACTIVE, the other a paging array giving the 
//start point for each page. It then opens a separate window maximized for the screen. This window contains forward/back buttons for paging through the list of users.

$summary_qry = "select * from time_log, user where time_log.entry_status='1' and status_id = '1'
	            and user.user_num = time_log.user_num";

$summary_res = mysqli_query($db, $summary_qry);
if (!$summary_res) db_error ('summary 1',$summary_qry);


//create session array of totals by name
$clear_qry = "delete from temp_meeting";
$clear_res = mysqli_query($db, $clear_qry);
if (!clear_res) db_error ('summary 1b',$clear_qry);

while ( $summary_info = mysqli_fetch_array($summary_res)) {
	 if (!isset($total[$summary_info['user_num']]['mins'])) $total[$summary_info['user_num']]['mins'] = 0;
	 $total[$summary_info['user_num']]['user_num'] = $summary_info['user_num'];
	 $total[$summary_info['user_num']]['first_name'] = $summary_info['first_name'];
	 $total[$summary_info['user_num']]['last_name'] = $summary_info['last_name'];
	 $total[$summary_info['user_num']]['email'] = $summary_info['email'];
	 $total[$summary_info['user_num']]['date_joined'] = $summary_info['date_joined'];
	 $total[$summary_info['user_num']]['status'] = $summary_info['status_id'];
	 $total[$summary_info['user_num']]['mins'] = $total[$summary_info['user_num']]['mins'] + $summary_info['time_in_mins'];
	 
}

foreach ($total as $usernum => $items ) {
	         $remainder = $total[$usernum]['mins']%60;
			 $hours = ($total[$usernum]['mins']-$remainder)/60;
			 $insert_qry = "insert into temp_meeting (name, hours)
			 values
		     ('".$total[$usernum]['first_name']." ".$total[$usernum]['last_name']."','".$hours."')";		
			 $insert_res = mysqli_query($db, $insert_qry);
			 if (!$insert_res) db_error ('summary 1a',$insert_qry);
}

$get_rows_qry = "select * from temp_meeting order by hours DESC, name";
$get_rows_res = mysqli_query($db, $get_rows_qry);
if (!$get_rows_res) db_error ('meeting 1b',$get_rows_qry);
$i=0;
while ($row = mysqli_fetch_array ($get_rows_res)) {
		$_SESSION['meeting_name'][$i] = $row['name'];
		$_SESSION['meeting_hours'][$i] = $row['hours'];
		$i++;
}
$i--;
$_SESSION['last_meeting_row'] = $i;
$_SESSION['last_row_displayed'] = -1; //allows for first page display
$_SESSION['rows_per_page'] = 6;
?>


<h2>Display Meeting List</h2>  
<p>Click the link below to display the meeting list. This will open in a separate window.</p>
<p><a href=javascript:openUp('page_meeting_list.php')>Display Meeting List</a></p>



<?php require_once "./lib/page_foot.php";?>



