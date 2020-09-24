<?php require_once "./lib/doc_info.php"; 

//allows user to start a phone shift, or to report some additional time worked.
?>

    
<?php 

if (!$_SESSION['current_user_num']) redirect ('index.php');

//get an array of activities
$user_qry = "select * from user where user_num = '".$_SESSION['current_user_num']."'";
$user_res = mysqli_query($db, $user_qry);
if (!$user_res) db_error ('form_personal.php','$user_qry');
$user_info = mysqli_fetch_array($user_res);

$display_first = $user_info['first_name'];
$display_last = $user_info['last_name'];
$display_email = $user_info['email'];

if (isset($_SESSION['err_first']) and strlen($_SESSION['err_first']) > 1) $display_first = $_SESSION['err_first'];
if (isset($_SESSION['err_last']) and strlen($_SESSION['err_last']) > 1) $display_last = $_SESSION['err_last'];
if (isset($_SESSION['err_email']) and strlen($_SESSION['err_email']) > 1) $display_email = $_SESSION['err_email'];
?>	

<form method="POST" action="proc_personal.php">
<h2>Update Personal Information</h2>  
<?php
        if (isset ($_SESSION['error_text'])) {
	        echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
	        $_SESSION['error_text'] = "";
        }
?>  
<table>
<tr><td>First Name:</td><td><input type=text size=30 name='first' value='<?php echo $display_first;?>'  /></td></tr>
<tr><td>Last Name:</td><td><input type=text size=30 name='last' value='<?php echo  $display_last;?>'  /></td></tr>
<tr><td>Email:</td><td><input type=text size=30 name='email' value='<?php echo $display_email;?>'  /></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td colspan=2><h3>Change password</h3></td></tr>
<tr><td>Current Password:</td><td><input type=password name='oldpwd' size=20 /></td></tr>
<tr><td>New Password:</td><td><input type=password name='newpwd' size=20  /></td></tr>
<tr><td>Re-enter New Password:</td><td><input type=password name='checkpwd' size=20 /></td></tr>
</table>
<input type=submit name='change' value='Change Details' />
</form>


<?php require_once "./lib/page_foot.php";?>