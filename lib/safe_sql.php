<?php
function safe_sql ($input_string) {
global $db;

//replace common characters with equivalents
$input_string = str_replace('<','&lt;',$input_string);
$input_string = str_replace('>','&gt;',$input_string);
$input_string = str_replace('&','&amp;',$input_string);
$input_string = str_replace('%','percent',$input_string);
$input_string = str_replace('[','',$input_string);
$input_string = str_replace(']','',$input_string);
$input_string = str_replace('{','',$input_string);
$input_string = str_replace('}','',$input_string);
$input_string = str_replace('\\','',$input_string);
//remove any prior addslashes to avoid double escaping, and then escape entire string for SQL
return mysqli_real_escape_string($db, stripslashes ( $input_string) );
}

function safe_sql_text_block ($input_string) {
//replace common characters with equivalents; allow [] for picture processing
$input_string = str_replace('<','&lt;',$input_string);
$input_string = str_replace('>','&gt;',$input_string);
$input_string = str_replace('&','&amp;',$input_string);
$input_string = str_replace('=','eq.',$input_string);
$input_string = str_replace('%','percent',$input_string);
$input_string = str_replace('{','',$input_string);
$input_string = str_replace('}','',$input_string);
$input_string = str_replace("\n\n", "<br><br>", $input_string); // deal with paragraphs
$input_string = nl2br($input_string); // deal with new lines 
if (get_magic_quotes_gpc()==0); 
$input_string = str_replace('\\','',$input_string);
//remove any prior addslashes to avoid double escaping, and then escape entire string for SQL
return mysqli_real_escape_string($db, stripslashes ( $input_string) );
}
?>