<?php 
require_once "./lib/doc_info.php"; 

//if the footer button is clicked, and there is no phone shift active, just go to the logout process.
if (!$_SESSION['admin_active']) {
	redirect ('index.php');
}

$settings_qry = "select * from settings";
$settings_res = mysqli_query($db, $settings_qry);
if (!$settings_res) db_error ("page active session 1",$settings_qry);
$settings_info = mysqli_fetch_array($settings_res);

$max_seconds = $settings_info["max_session_mins"] * 60;
$earliest_time = time() - $max_seconds;
$session_qry = "select * from session_log, user 
                where session_log.user_num = user.user_num and session_log.start_timestamp > '".$earliest_time."' 
				order by first_name, last_name";
$session_res = mysqli_query($db, $session_qry);
if (!$session_res) db_error ('page active session 2',$session_qry);



?>
<h2>Active Phone Shift Sessions</h2>
<table border=1>
<?php
echo "<tr><th>Name</th><th>Session start</th><th>Length</th></tr>";
while ($session_item = mysqli_fetch_array ($session_res)) {
	$name = $session_item['first_name']." ".$session_item['last_name'];
	$session_start = date ('m/d/y g:i a', $session_item['start_timestamp'] );	
	$total_time_secs = time () - $session_item['start_timestamp'];
	$total_time_mins = round ($total_time_secs/60);
	$minutes = $total_time_mins%60; 	
	$total_hours = ($total_time_mins - $minutes) /60;
	$display_time = $total_hours." : ".$minutes;
	echo "<tr><td>".$name."</td><td>".$session_start."</td><td>".$display_time."</td></tr>";								  
}

?>
</table>


<?php require_once "./lib/page_foot.php";?>

