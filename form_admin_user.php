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

//get an array of status types
$status_type_qry = "select * from status_types";
$status_type_res = mysqli_query($db, $status_type_qry);
if (!$status_type_res) db_error ('form_admin_user 2.php','$status_type_qry');


?>	

<form method="POST" action="proc_admin_user.php">
<h2>Update Information For <?php echo $user_info['first_name']." ".$user_info['last_name'];?></h2>  
<?php
        if (isset ($_SESSION['error_text'])) {
	        echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
	        $_SESSION['error_text'] = "";
        }

if ($_SESSION['err_enabled'] == "") $_SESSION['err_enabled'] = $user_info['enabled'];
if ($_SESSION['err_status'] == "") $_SESSION['err_status'] = $user_info['status_id'];
if ($_SESSION['err_notes'] == "") $_SESSION['err_notes'] = $user_info['notes'];
if ($_SESSION['err_admin'] == "") $_SESSION['err_admin'] = $user_info['admin'];
?>  

<table>
<tr height="40"><td>User Name:</td><td><?php echo $user_info['user_name'];?></td></tr>
<tr height="40"><td>Email:</td><td><input type=text size=30 name='email' value='<?php echo $user_info['email'];?>'  /></td></tr>
<tr><td>Date Joined (dd/mm/yy):</td><td><input type=text size=8 name='date_joined' value='<?php echo date('m/d/y',$user_info['date_joined']);?>'  /></td></tr>
<tr height="40"><td>Enabled for access:</td><td><select name=enabled size=1>
                             <option value = 0 <?php if (!$_SESSION['err_enabled']) echo 'selected';?> >Disabled</option>
                             <option value = 1 <?php if ($_SESSION['err_enabled']) echo 'selected';?> >Enabled</option>
                         </select>
<tr height="40"><td>Administrator:</td><td><select name=admin size=1>
                             <option value = 0 <?php if (!$_SESSION['err_admin']) echo 'selected';?> >No</option>
                             <option value = 1 <?php if ($_SESSION['err_admin']) echo 'selected';?> >Yes</option>
                         </select>
</td></tr>
<tr height="40"><td>Status:</td><td><select name=status size=1>
                             <?php while ($status_type_info = mysqli_fetch_array($status_type_res)) {
								       echo "<option value = '".$status_type_info['status_id']."' ";
								       if ($_SESSION['err_status'] == $status_type_info['status_id']) {
										   echo "selected";
							           }
								       echo ">".$status_type_info['description']."</option>";
							       }
						     ?>
                             
                         </select></td></tr>
<tr height="40"><td>Date Status Changed:</td><td><?php echo date('m/d/y',$user_info['date_status_updated']);?></td></tr>
<tr height="40"><td>Notes:</td><td><textarea name = notes cols= 50 rows = 6><?php echo stripslashes ($_SESSION['err_notes']);?></textarea></td></tr>
</table>
<input type=submit name='change' value='Change Details' />
</form>

<?php require_once "./lib/page_foot.php";?>