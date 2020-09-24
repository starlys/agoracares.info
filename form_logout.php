<?php 
require_once "./lib/doc_info.php"; 

?>


<?php 

//if there is no session active, redirect
if (!isset ($_SESSION['current_user_num']) || !$_SESSION['current_user_num']) {
      echo "<h2>Not Logged In</h2><p>You are not currently logged in. To login, go to the <a href=index.php>Main Page</a></p>";
}

//if a phone shift is active, redirect to the time recording page

if (isset ($_SESSION['shift_start_time']) && $_SESSION['shift_start_time']) {
	redirect ('./time.php');
}

//if the footer button is clicked, and there is no phone shift active, just go to the logout process.

else {
		redirect ('proc_logout.php');
	}

?>			

<?php require_once "./lib/page_foot.php";?>

