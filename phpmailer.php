<?php
require 'PHPMailerAutoload.php';
$mail = new PHPMailer;
$mail->setFrom('webmaster@agoracares.info', 'webmaster');
$mail->addAddress('abqdan@gmail.com', 'client');
$mail->Subject  = 'First PHPMailer Message';
$mail->Body     = 'Hi! This is my first e-mail sent through PHPMailer.';
if(!$mail->send()) {
  echo 'Message was not sent.';
  echo 'Mailer error: ' . $mail->ErrorInfo;
} else {
  echo 'Message has been sent.';
}