
</div>
	<div id="footer" class="row">
		<div class="col c12 aligncenter">

    <?php if ((!$_SESSION['current_user_num'])) {
		  echo "<p><span>&copy; 2009 - ".date ('Y',time())." <a href='#'>Agora Crisis Center, NM</a></span><br /></p>";
		  }
		  else {
			    echo "<form method='POST' action='form_logout.php' >
                     <table width = 100%>
                     <tr><td>&copy; 2009 - ".date ('Y',time())." <a href='#'>Agora Crisis Center, NM</a></td>
                         <td align=right>User: ".$_SESSION['display_name'];
						 if ($_SESSION['shift_start_time']) echo " on shift since: ".date('g:i a',$_SESSION['shift_start_time']);
						 echo "&nbsp;<input type=submit name=logout value = 'Log Out' /></td>
                     </tr>
                     </table>
                     </form>";
		       }
	?>
	</div>
<?php /*Template design by <a href="http://andreasviklund.com/">Andreas Viklund</a>*/?>
</div>
</body>
</html>