<?php
function send_email ($sender_name, $from, $to, $message, $subject) {
   $from = "FROM:".$from;
   mail ($to, $subject, $message, $from);
}
?>

