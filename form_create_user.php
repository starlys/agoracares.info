<?php require_once "./lib/doc_info.php"; 

if (!isset($_SESSION['err_user_name'])) $_SESSION['err_user_name'] = "";
if (!isset($_SESSION['err_first'])) $_SESSION['err_first'] = "";
if (!isset($_SESSION['err_last'])) $_SESSION['err_last'] = "";
if (!isset($_SESSION['err_email'])) $_SESSION['err_email'] = "";


?>


	<img id="frontphoto" src="images/logo.jpg" width="760" height="175" alt="" />


    <h2>Create an Account</h2>
    <p>Fill out the form on this page to create your Agora account. You will immediately be able to start recording
    your shift hours. An email will be sent to staff informing them that you have created this account. They will then add any hours you worked before you started using this system.</p>
    
<?php  if (isset ($_SESSION['error_text'])) {
	      echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
	      $_SESSION['error_text'] = "";
}
?>	 

<form method="POST" action="proc_create_user.php">
		
  
		<table border="0" cellspacing="2"  cellpadding="2" align="justify" >

        <tr>
        <td >Choose a user name</td>
          <td > <input type="text" name="user_name" size="20" maxlength="20" value= '<?php echo $_SESSION['err_user_name'];?>'> 
          (Only numbers and letters allowed)</td>       
        </tr>
        <tr>
          <td >Choose a password</td>
          <td> <input type="password" name="pwd" size="20" maxlength="20"> 
          (At least six characters) </td>
        </tr>
        <tr>
          <td >Re-enter password</td>
          <td > <input type="password" name="pwd_check" size="20" maxlength="20"></td>
        </tr>
        <tr>
          <td >First Name</td>
          <td ><input type="text" name="first" size="20" maxlength="30"value= '<?php echo $_SESSION['err_first'];?>'> </td>
        </tr>
        <tr>
          <td >Last Name</td>
          <td ><input type="text" name="last" size="20" maxlength="30"value= '<?php echo $_SESSION['err_last'];?>'> </td>
        </tr>
        <tr>
          <td >Email Address</td>
          <td ><input type="text" name="email" size="30" maxlength="40"value= '<?php echo $_SESSION['err_email'];?>'> </td>
        </tr>
      </table>
      <p><input type="submit" value="Create Account" name="B1"></p></form></td>
    




<?php require_once "./lib/page_foot.php";?>