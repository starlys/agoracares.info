<?php require_once "./lib/doc_info.php"; ?>


<h1>Now signing up shifts for...</h1>
<p>&nbsp;</p>
<p>&nbsp;</p><table>
<?php 

if (!$_SESSION['admin_active']) {
	redirect ('index.php');
}
//assume we're going forward
$_SESSION['first_row_this_page'] = $_SESSION['last_row_displayed']+1;
if (!isset ($_SESSION['previous_first_row'])) $_SESSION['previous_first_row'] = $_SESSION['first_row_this_page'];

if (isset($_POST['previous_page'])) {
	       $num_displayed = $i + 1;
		   $remainder = $num_displayed%$_SESSION['rows_per_page'];
		   $rows_last_page = $_SESSION['rows_per_page'] - $remainder;
           $_SESSION['first_row_this_page'] = $_SESSION['last_row_displayed'] - $_SESSION['rows_per_page'] - $rows_last_page ;
		   if ($_SESSION['first_row_this_page'] < 0) $_SESSION['first_row_this_page']=0;
}
	$page_limit = $_SESSION['first_row_this_page']+$_SESSION['rows_per_page'];

for ($i=$_SESSION['first_row_this_page']; $i<$page_limit; $i++) {
	
		if (isset($_SESSION['meeting_name'][$i])) {
		    echo "<tr height = 80><td width= 350>&nbsp;</td><td><h1>".$_SESSION['meeting_name'][$i].
			" (".$_SESSION['meeting_hours'][$i].")</h1></td></tr>";
		}
}

$i--;
?>
</table>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']?>">
<?php 
if ($_SESSION['last_row_displayed'] >= 0) {
     echo "<input style='height: 50px; width: 100px; font-weight: bold;   color:white; background-color:#039;' type=submit name = 'previous_page' value = ' < '>";
}

if ( $i <= $_SESSION['last_meeting_row'] ) {
     echo "<input style='height: 50px; width: 100px; font-weight: bold;   color:white; background-color:#039;' type=submit name = 'next_page' value = ' > '>";
}

	
$_SESSION['last_row_displayed'] = $i;
?>
</form>
<?php require_once "./lib/page_foot.php";?>




