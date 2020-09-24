<?php function available_user_name ($user_name) {
global $db;
require_once 'opendb.php';
require_once 'email_error.php';
//set name to all lower case

$user_name = strtolower ($user_name);

//check that only letters and numbers are used
$regex =  '/^[0-9a-z]+$/' ; 
$valid_user_name = (preg_match($regex, $user_name));

if ($valid_user_name) {
	//if user name is already in the DB, return an error message and value 0, otherwise return value 1.
	$user_qry = "select * from user where user_name = '".safe_sql($user_name)."' LIMIT 1";
	$user_res = mysqli_query($db, $user_qry);
	if (!$user_res) db_error ('available_user_name',$user_qry);
	if ( mysqli_num_rows ($user_res) ) { 
		$_SESSION['error_text'] .= "User name can only contain letters and/or numbers<br>";
		return 0; 
	} else { 
		return 1; 
	}	
}
else {
	$_SESSION['error_text'] .= "User name can only contain letters and/or numbers<br>";
	return false;
}





}
?>