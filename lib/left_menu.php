<?php //if the user is logged in, then their user_num is set in $_SESSION['current_user_num'] 

if (!isset ($_SESSION['admin_active'])) $_SESSION['admin_active']=0;

?>

	<div class="row">
		<div class="col c2 alignleft">
			<ul class="menu">

        <li><a <?php if ($_SESSION['current_script']== 'index.php') echo 'class="current" ';?> href="index.php">Start</a></li>
        <?php 
        if ($_SESSION['current_user_num']) { //logged in
		    echo "<li><a href='#'>My Account</a></li>";
				echo '<li><a ';
				if ($_SESSION['current_script']== 'form_personal.php') {echo 'class=current ';}
				echo " href='form_personal.php'>Personal Info</a></li>";
				echo '<li><a ';
				if ($_SESSION['current_script']== 'page_history.php') {echo 'class=current ';}
				echo " href='page_history.php'>Time History</a></li>";
			
        }
		
        if ($_SESSION['current_user_num'] && $_SESSION['in_center'] ) { //logged in AND at the center
			echo '<li><a ';
			if ($_SESSION['current_script']== 'time.php') {echo 'class="current" ';}
			echo ' href="time.php">Time Recording</a></li>';
		}
        if ($_SESSION['in_center'] ) { //at the center
			echo '<li><a ';
			if ($_SESSION['current_script']== 'page_sms_staff.php') {echo 'class="current" ';}
			echo ' href="page.php">Page Staff</a></li>';
        }
		
        if ($_SESSION['admin_active'] ) { //at the center
		    echo "<li><a href='#' >Administration</a><ul class='subpages'>";
				echo '<li><a ';
				if ($_SESSION['current_script']== 'page_active_sessions.php' ) {echo 'class="current" ';}
				echo ' href="page_active_sessions.php">Active Sessions</a></li>';
				echo '<li><a ';
				if ($_SESSION['current_script']== 'form_adjust_select_user.php' || 
				    $_SESSION['current_script']== 'form_adjust_user.php') {echo 'class="current" ';}
				echo ' href="form_adjust_select_user.php">Adjust Hours</a></li>';
				echo '<li><a ';
				if ($_SESSION['current_script']== 'form_approvals.php') {echo 'class="current" ';}
				echo ' href="form_approvals.php">Approve Hours</a></li>';
				echo '<li><a ';
				if ($_SESSION['current_script']== 'form_center_ips.php') {echo 'class="current" ';}
				echo ' href="form_center_ips.php">Center PCs</a></li>';
				echo '<li><a ';
				if ($_SESSION['current_script']== 'page_reports.php' ||
					$_SESSION['current_script']== 'page_summary.php') {echo 'class="current" ';}
				echo ' href="page_reports.php">Reports</a></li>';
				echo '<li><a ';
				if ($_SESSION['current_script']== 'form_settings.php') {echo 'class="current" ';}
				echo ' href="form_settings.php">Settings</a></li>';
				echo '<li><a ';
				if ($_SESSION['current_script']== 'form_admin_select_user.php' ||
					$_SESSION['current_script']== 'form_admin_user.php') {echo 'class="current" ';}
				echo ' href="form_admin_select_user.php">Update User</a></li><br>';
			
        }
		
		
		
		
		
		
		
        ?>
        </ul><ul class="menu"><li><b><a href="#" >Resources</a></b>
    
            <ul class='subpages'>
                <li><a href="http://www.icarol.com" target="_blank">iCarol</a></li>
                <li><a href="http://www.refersoftware.com/uwcnm/" target="_blank">United Way</a></li>
                
                
            </ul>
    
        </li>
    </ul>
    

</div>
