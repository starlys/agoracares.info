<?php require_once "./lib/doc_info.php"; ?>




		<h2>Volunteer Administration and Timekeeping </h2>
        <p>&nbsp;</p>
		
        <?php if ($_SESSION['current_user_num'] && $_SESSION['in_center'])
		echo "
		<p>Remember to record the start of each phone shift. Click TIME RECORDING to start recording your shift. </p>
        <p>You must log out at the end of your shift, or <strong>YOUR HOURS WILL NOT BE ADDED TO YOUR TOTAL!</strong> (Click Log Out at the bottom of the screen, or go the TIME RECORDING page to end your shift.)</p>";

if ($_SESSION['current_user_num']) {
        echo "<h3>Emergencies / Staff Help</h3>
		<p>Select the PAGE STAFF link on the left for ways to contact staff via the pager.</p>";
}

//if the user is not logged in, add the login form; otherwise, display a logout button.   
if (!$_SESSION['current_user_num']) {
		
		echo "<h3>Log In</h3>
        <p><a href=form_create_user.php>I am new - I need to create an account</a><br />
        <a href=form_recover_pwd.php>I don't remember my password/user name</a></p>";       
           
        if (isset ($_SESSION['error_text'])) {
	        echo "<p class='error'>".$_SESSION['error_text']."</p>"; 
	        $_SESSION['error_text'] = "";
        }
        
        echo "<form method='POST' action='proc_login.php' >
		<table >
        <tr><td>User Name:</td><td><input type=text name=user_name size = 25 /></td></tr>
        <tr><td>Password:</td><td><input type=password name=pwd size = 25 /></td></tr>
		</table>
        <p><input type=submit name=login value ='Log In' /></p>
        
        <input type=hidden name='form_page' value = 'index.php' />
        </form>";
}
?>
	


<?php require_once "./lib/page_foot.php";?>