<?php
function check_ip_in_center ($ip) {
global $db;

$ip_qry = "select * from center_ip where ip_address = '".$ip."' LIMIT 1";
$ip_res = mysqli_query($db, $ip_qry);
if (!$ip_res) {
    db_error ('check_ip', $ip_qry);
}
$_SESSION['in_center'] = mysqli_num_rows ($ip_res);

 $_SESSION['in_center'] = true;
}
?>