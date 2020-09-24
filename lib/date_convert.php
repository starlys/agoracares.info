<?php 

//FUNCTION: DateInputToTimestamp 
//takes a string in the form mm-dd-yy, or mm/dd/yy and validates it. 
//Allows for single m or d component; and yyyy, yy, or y year.
//if input is invalid, returns a zero value, otherwise returns Unix timestamp.
//only appropriate for dates greater than start of Unix epoch

function DateInputToTimestamp ($datein) { 
$input_timestamp = "0";
str_replace (" ","0",$datein); //remove any spaces in string

if ($datein == "") return $input_timestamp;	

$regs = explode ("/", $datein);
if (count($regs) <> 3) {
    $regs = explode ("-", $datein);
	if (count($regs) <> 3) return $input_timestamp;
} //end if

//so regs[0] = month, regs[1] = day, regs[2] = year

if (is_numeric ($regs[0]) && is_numeric ($regs[1]) && is_numeric ($regs[0])) { //valid numbers
$regs[0] = (int)$regs[0];	
$regs[1] = (int)$regs[1];	
$regs[2] = (int)$regs[2];	    

if ($regs[2] < 100) $regs[2] = $regs[2] + 2000; //allows for entry of year as 2 digits
if ( checkdate((int)$regs[0], (int)$regs[1], (int)$regs[2]) ) {

    if ($regs[1] < 10) $regs[1] = "0".$regs[1];	
    if ($regs[0] < 10) $regs[0] = "0".$regs[0];	
	$input_timestamp = mktime (0,0,0,$regs[0],$regs[1],$regs[2]);
	}
}	
return $input_timestamp;
    
}//end function


?>