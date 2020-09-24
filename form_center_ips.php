<?php 
require_once "./lib/doc_info.php"; 

if (!$_SESSION['admin_active']) redirect ('index.php');

$ip_qry = "select * from center_ip order by ip_address";
$ip_res = mysqli_query($db, $ip_qry);
if (!$ip_res) db_error ('form center ip 1',$ip_qry);

$this_ip_qry = "select * from center_ip where ip_address = '".$SESSION."'";
?>


<?php 


//if the footer button is clicked, and there is no phone shift active, just go to the logout process.


?>
<h2>Maintain Center Computer Authorizations</h2>
<p>From here you can add or remove PCs from the list of authorized computers. An authorized computer is allowed to record time for phone shifts and extra hours in the database. </p>
<p>The automatic ADD function is designed for UNM's network. It should only be used with computers that are connected BY A WIRE to the UNM network. <strong>DO NOT add a computer that is on a wireless network;</strong> this can result in authorizing additional (and unwanted) computers to use the time-keeping functions!</p>

<form method="POST" action="proc_center_ips.php">

<h4 class='error'>IP address for this machine: <?php echo $_SERVER['REMOTE_ADDR'];?></h4>
<h3 >Automatic Add/Delete</h3>

<?php

if (!$_SESSION['in_center']) {
  echo "<p><strong>To add current machine to list, supply a description (e.g. 'machine 1')</strong></p>";
  echo "<input type=text size = 30 name = auto_description>";
  echo "<p><input type=submit name = auto_add_ip value = 'Authorize This machine for Time Recording'></p>";
  }
else {

  echo "<p><input type=submit name = auto_del_ip value = 'Remove Authorization From This Machine'></p>";
  }
?>

<p>&nbsp;</p>

<h3>Manual Add</h3>
<?php   if (isset ($_SESSION['error_text'])) {
	        echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
	        $_SESSION['error_text'] = "";
        }
?>
<table >
<tr><td>Enter IP address (e.g. 35.168.2.121):</td><td><input type=text size = 15 value = '
<?php if (isset ($_POST['err_ip'])) echo $_POST['err_ip'];?>
' name='man_add_ip' /></td></tr>
<tr><td>Add a description (e.g. computer 1):</td><td><input type=text size = 20 name='man_add_description' /></td></tr>
</table>
<input type=submit name='manual_add' value = 'Approve This IP Address' />

<p>&nbsp;</p>
<h2>Currently Approved Computer IP Addresses</h2>
<table border = 1 cellspacing = 3>
<tr><th>IP Address</th><th>Description</th><th>Delete</th></tr>
<?php
while ($ip_info = mysqli_fetch_array ($ip_res) ) {
	echo "<tr><td>".$ip_info['ip_address']."</td><td>".$ip_info['description']."</td><td align=center>
	<input type=checkbox name = del[".$ip_info['ip_address']."] value = 1>
	</td></tr>";
	
}

?>
</table>
<p>&nbsp;</p>
<input type=submit name='delete_items' value = 'Delete Checked Items' />
</form>


<?php require_once "./lib/page_foot.php";?>

