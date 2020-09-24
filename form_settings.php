<?php 
require_once "./lib/doc_info.php"; 

if (!$_SESSION['admin_active']) redirect ('index.php');
$settings_qry = "select * from settings";
$settings_res = mysqli_query($db, $settings_qry);
if (!$settings_res) db_error ('form settings 1',$settings_qry);
$settings_val = mysqli_fetch_array($settings_res);

if (isset ($_SESSION['err_max'] )) $max_length = $_SESSION['err_max']; else $max_length=$settings_val['max_session_mins'];
if (isset ($_SESSION['err_min'] )) $min_length = $_SESSION['err_min']; else $min_length=$settings_val['min_session_mins'];
if (isset  ($_SESSION['err_warn'] )) $warn = $_SESSION['err_warn']; else $warn=$settings_val['long_session_warning'];

if (isset  ($_SESSION['err_admin1'])) $admin1 = $_SESSION['err_admin1']; else $admin1=$settings_val['email_admin_1'];
if (isset  ($_SESSION['err_admin2'])) $admin2 = $_SESSION['err_admin2']; else $admin2=$settings_val['email_admin_2'];
if (isset  ($_SESSION['err_admin3'] )) $admin3 = $_SESSION['err_admin3']; else $admin3=$settings_val['email_admin_3'];

if (isset  ($_SESSION['err_page1'] )) $page1 = $_SESSION['err_page1']; else $page1=$settings_val['page_email_emergencies'];
if (isset  ($_SESSION['err_page2'] )) $page2 = $_SESSION['err_page2']; else $page2=$settings_val['page_email_information'];
if (isset  ($_SESSION['err_page3'] )) $page3 = $_SESSION['err_page3']; else $page3=$settings_val['page_email_shift'];

if (isset  ($_SESSION['err_page1_2'] )) $page1_2 = $_SESSION['err_page1_2']; else $page1_2=$settings_val['page_email_emergencies_2'];
if (isset  ($_SESSION['err_page2_2'] )) $page2_2 = $_SESSION['err_page2_2']; else $page2_2=$settings_val['page_email_information_2'];
if (isset  ($_SESSION['err_page3_2'] )) $page3_2 = $_SESSION['err_page3_2']; else $page3_2=$settings_val['page_email_shift_2'];


?>

    <h2>Administration: System Settings</h2>
    <p>&nbsp;</p>

<?php    
if (isset ($_SESSION['error_text'])) {
	      echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
	      $_SESSION['error_text'] = "";
}
?>	 


<form method="POST" action="proc_settings.php">
		
  
<table border="0" cellspacing="2"  cellpadding="2" align="justify" >

        <tr>
        <td >Maximum Session Length (in mins):</td>
          <td > <input type="text" name="max_length" size="3"  value= '<?php echo $max_length;?>'> </td>       
        </tr>
        <td >Minimum Session Length (in mins):</td>
          <td > <input type="text" name="min_length" size="3"  value= '<?php echo $min_length;?>'> </td>       
        </tr>
        <tr>
          <td >Warn on sessions longer than (mins):</td>
          <td> <input type='text' name ="warn_length"  size="3" value= '<?php echo $warn;?>'>  </td>
        </tr>
        <tr>
          <td >Admin email 1</td>
          <td > <input type="text" name="admin1" size="40" maxlength="60" value= '<?php echo $admin1;?>'></td>
        </tr>
        <tr>
          <td >Admin email 2</td>
          <td > <input type="text" name="admin2" size="40" maxlength="60" value= '<?php echo $admin2;?>'></td>
        </tr>
        <tr>
          <td >Admin email 3</td>
          <td > <input type="text" name="admin3" size="40" maxlength="60" value= '<?php echo $admin3;?>'></td>
        </tr>
        <tr>
          <td >Pager: Email for emergencies (1)</td>
          <td ><input type="text" name="emergency" size="40" maxlength="60" value= '<?php echo $page1;?>'> </td>
        </tr>
        <tr>
          <td >Pager: Email for emergencies (2)</td>
          <td ><input type="text" name="emergency_2" size="40" maxlength="60" value= '<?php echo $page1_2;?>'> </td>
        </tr>
        <tr>
          <td >Pager: Email for help requests (1)</td>
          <td ><input type="text" name="information" size="40" maxlength="60" value= '<?php echo $page2;?>'> </td>
        </tr>
        <tr>
          <td >Pager: Email for help requests (2)</td>
          <td ><input type="text" name="information_2" size="40" maxlength="60" value= '<?php echo $page2_2;?>'> </td>
        </tr>
        <tr>
          <td >Pager: Email for shift messages (1)</td>
          <td ><input type="text" name="shift" size="40" maxlength="60" value= '<?php echo $page3;?>'> </td>
        </tr>
        <tr>
          <td >Pager: Email for shift messages (2)</td>
          <td ><input type="text" name="shift_2" size="40" maxlength="60" value= '<?php echo $page3_2;?>'> </td>
        </tr>
</table>     
<p>&nbsp;</p>
<p><input type="submit" value="Update Settings" name="B1"></p></form>
    




<?php 



require_once "./lib/page_foot.php";?>