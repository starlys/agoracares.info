<?php
function valid_password ($pwd) { 

  $pwd = trim (strtolower ($pwd));
  $regex =  '/^([*+!.#$¦\'\\%\/0-9a-zA-Z^_`?~:-]+)$/i' ; 
  return (preg_match($regex, $pwd)) ;
}
?>