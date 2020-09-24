<?php require_once "./lib/doc_info.php"; ?>

    <h2>Reset Password</h2>
    <p>Enter your email address below. We will email you a temporary password. Login with that password, and then change it to something you can remember using the Account page.</p>
    
<?php  if (isset ($_SESSION['error_text'])) {
	      echo "<p>".$_SESSION['error_text']."</p>"; 
	      $_SESSION['error_text'] = "";
}
?>	 

<form method="POST" action="proc_reset_user.php">
		
  
		<table border="0" cellspacing="2"  cellpadding="2" align="justify" >

        <tr>
          <td >Email Address</td>
          <td ><input type="text" name="email" size="30" maxlength="40"></td>
        </tr>
      </table><p>&nbsp;</p>
      <p><input type="submit" value="Reset Password" name="B1"></p></form></td>
    


<?php require_once "./lib/page_foot.php";?>