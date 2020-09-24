<?php function db_error ($qry, $module) {
global $db;
$from = "From:webmaster@agoracares.info";
$subject ="AGORA - DB ERROR REPORT";
$timenow = date ("d M Y H:i:s T", time());
$to ="abqdan@gmail.com";
$message.= "USER : ".$_SESSION['current_user_name']."\n\n";
$message.= "QUERY : ".$qry."\n\n";
$message.= "CALL #: ".$module."\n\n";
$message.= "SCRIPT: ".$_SERVER['PHP_SELF']."\n\n";
$message.= "ERROR:". mysqli_error($db)."\n\n";
$message.= "TIME  : ".$timenow ;
mail ($to, $subject, $message, $from, "-f webmaster@agoracares.org");


echo "Sorry - a database error occurred. Your request was not completed. A staff member has been informed<br>";
echo "Errors are normally fixed within 24 hours - please try your request again tomorrow.<br>&nbsp;<br>";    	
echo "If you would like further information please contact any staff member at the center.<br>&nbsp;<br>"; 

echo "<a href='index.php'>Return to Agora home page</a>"; 
}
?>	