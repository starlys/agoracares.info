<?php

//$db = mysqli_connect("localhost", "dantec2_adminag", "password", "dantec2_agora");

//live database: agoracar_portal
//test database: agoracar_test
$db = mysqli_connect("localhost", "agoracar_webagnt", "password", "agoracar_portal");

//NOTE ON PASSWORDS: Since the repo is stored in a public location, be careful not to include the password in this file
//when checking in changes!

if (!$db)
  {
    echo "Error: Could not connect to database.  Please try again later.";
    exit;
  }
?>