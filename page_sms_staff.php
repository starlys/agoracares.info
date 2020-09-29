<?php require_once "./lib/doc_info.php"; ?>


<?php 


if (!$_SESSION['in_center']) redirect ('index.php');


?>	

<form method="POST" action="./sms/proc_sms_staff.php">
    <h2>Page CRISIS SUPPORT TEAM - Emergency Help Needed</h2>
    <p>Optional: text to crisis support team
      <input type=text name=suicide_text size = 60 /></p>
    <p><input style="height: 100px; width: 250px; font-weight: bold;   color:white; background-color:#F00;" type = submit name=suicide value ='EMERGENCY HELP NEEDED' /></p>
    <p>&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br /></p>
    <h2>Page CRISIS SUPPORT TEAM - Need help</h2>
    
    <p>Optional: text to crisis support team
      <input type=text name=help_text size = 60 /></p>
    <p><input style="height: 50px; width: 250px; font-weight: bold;   color:black; background-color:#FC3" type = submit name=help value ='REQUEST HELP' /></p>
    
</form>
    




<?php require_once "./lib/page_foot.php";?>