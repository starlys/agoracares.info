<?php
function valid_email ($email) { 

  $email = trim (strtolower ($email));
  $regex =  '/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i' ; 
  return (preg_match($regex, $email)) ;
}
?>