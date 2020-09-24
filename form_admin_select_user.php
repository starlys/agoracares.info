<?php require_once "./lib/doc_info.php"; 

if (!$_SESSION['admin_active']) redirect ('index.php');

//get an array of activities
$user_qry = "select * from user order by first_name";
$user_res = mysqli_query($db, $user_qry);
if (!$user_res) db_error ('form_select_user.php',$user_qry);

?>	
	
<form method="POST" action="form_admin_user.php">


<h2>Select Volunteer</h2>  
<p>This section allows you to change various information about the user, including their email address, current status, and join date. It also allows you to disable a user's access to the portal's protected pages. Select the volunteer record you would like to update, then click EDIT.</p>

<p><select name=selected_user size = 1 >
<?php 
   while ($user_info = mysqli_fetch_array($user_res) ) {
	   echo "<option value='".$user_info['user_num']."'>".$user_info['first_name']." ".$user_info['last_name']."</option>";
   }
?>

</select>

&nbsp;&nbsp;<input type=submit name='edit' value='Edit' /></p>
</form>
	

<?php require_once "./lib/page_foot.php";?>