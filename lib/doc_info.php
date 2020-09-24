<?php
session_start ();
require_once './lib/opendb.php';
require_once './lib/check_ip.php';
require_once './lib/email_error.php';
require_once './lib/redirect.php';
require_once "./lib/date_convert.php";
require_once "./lib/email_admins.php";

if (!isset ($_SESSION['current_user_num'])) $_SESSION['current_user_num'] = 0;
if (!isset ($_SESSION['shift_start_time'])) $_SESSION['shift_start_time'] = 0;
$_SESSION['current_script'] = $_SERVER["SCRIPT_NAME"];
$last_slash = strrpos($_SESSION['current_script'],'/');
$_SESSION['current_script']=substr($_SESSION['current_script'],$last_slash+1);

check_ip_in_center ($_SERVER['REMOTE_ADDR']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Agora Volunteer Portal" />
	<meta name="keywords" content="agora, timekeeping, portal" />
	<meta name="author" content="Danny Lee" />
	<link rel="stylesheet" type="text/css" href="tables.css" />
	<link rel="stylesheet" type="text/css" href="origo.css" />
	<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Dosis'  />
	<title>Agora Volunteer Portal</title>
</head>

<body class="light blue smaller freestyle01">
<div id="layout">
 

  
	<div class="row">
		<div class="col c12 aligncenter">
			<img src="images/logo.jpg" width="960" height="175" alt="" />
		</div>
	</div>
    
    <?php require_once "./lib/left_menu.php";?>	

		<div class="col c10">