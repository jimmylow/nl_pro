<?php 

  include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
	$sel=$_GET['sel'];
?>
	<form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 796px;">

<?php
	if ($sel == '1')
	{
	echo'
		<table style="width: 807px; height: 102px;">
				  <tr>
					<td style="width: 6px"></td>
					<td style="width: 172px" class="tdlabel">From Worker</td>
					<td style="width: 19px">:</td> 
					<td style="width: 274px">
						<select name="frbat" id="frbat" style="width: 250px" >'; ?>
						<?php
							$sql = "select workid, workname from wor_detmas where status= 'A' order by workid";
							$sql_result = mysql_query($sql);
							   
							if(mysql_num_rows($sql_result)) 
							{
								while($row = mysql_fetch_assoc($sql_result)) 
								{ 
									$workid = htmlentities($row['workid']);
									$workname = htmlentities($row['workname']);
									echo '<option value="'.$workid.'">'.$workid. '-'. $workname. '</option>';
								} 
							} 
						?>
						<?php echo'				   
						</select>
					</td>
					<td></td>
					<td style="width: 159px" class="tdlabel">To Worker</td>
					<td style="width: 19px">:</td> 
					 <td style="width: 304px">
						<select name="tobat" id="tobat" style="width: 250px" >'; ?>
						<?php
							$sql = "select workid, workname from wor_detmas where status= 'A' order by workid";
							$sql_result = mysql_query($sql);
							   
							if(mysql_num_rows($sql_result)) 
							{
								while($row = mysql_fetch_assoc($sql_result)) 
								{ 
									$workid = htmlentities($row['workid']);
									$workname = htmlentities($row['workname']);
									echo '<option value="'.$workid.'">'.$workid. '-'. $workname. '</option>';
								} 
							} 
						?>
						<?php echo'				   
						</select>
					</td>
				  </tr>
				   <tr>
				   <td colspan="8" align="center">
				   </td>
				  </tr>
				   <tr><td style="width: 6px"></td></tr>
				</table>';
	}
	if ($sel == '2')
	{
	echo'
		<table style="width: 807px; height: 102px;">
				  <tr>
					<td style="width: 6px"></td>
					<td style="width: 172px" class="tdlabel">From Ticket No.</td>
					<td style="width: 19px">:</td> 
					<td style="width: 274px">
						<select name="frbat" id="frbat" style="width: 250px" >'; ?>
						<?php
							$sql = "select ticketno from sew_entry";
							$sql_result = mysql_query($sql);
							   
							if(mysql_num_rows($sql_result)) 
							{
								while($row = mysql_fetch_assoc($sql_result)) 
								{ 
									$ticketno = htmlentities($row['ticketno']);
									echo '<option value="'.$ticketno.'">'.$ticketno. '</option>';
								} 
							} 
						echo'				   
						</select>
					</td>
					<td></td>
					<td style="width: 159px" class="tdlabel">To Ticket No.</td>
					<td style="width: 19px">:</td> 
					 <td style="width: 304px">
						<select name="tobat" id="tobat" style="width: 250px" >'; ?>
						<?php
							$sql = "select ticketno from sew_entry";
							$sql_result = mysql_query($sql);
							   
							if(mysql_num_rows($sql_result)) 
							{
								while($row = mysql_fetch_assoc($sql_result)) 
								{ 
									$ticketno = htmlentities($row['ticketno']);
									echo '<option value="'.$ticketno.'">'.$ticketno. '</option>';
								} 
							} 
						 echo'				   
						</select>
					</td>
				  </tr>
				   <tr>
				   <td colspan="8" align="center">
				   </td>
				  </tr>
				   <tr><td style="width: 6px"></td></tr>
				</table>';
				
	}
	if ($sel == '3')
	{
	echo'
		<table style="width: 807px; height: 102px;">
				  <tr>
					<td style="width: 6px"></td>
					<td style="width: 172px" class="tdlabel">From Ticket No.</td>
					<td style="width: 19px">:</td> 
					<td style="width: 274px">
						<select name="frbat" id="frbat" style="width: 250px" >'; ?>
						<?php
							$sql = "select prod_code from pro_cd_master where actvty = 'A' order by prod_code ";
							$sql_result = mysql_query($sql);
							   
							if(mysql_num_rows($sql_result)) 
							{
								while($row = mysql_fetch_assoc($sql_result)) 
								{ 
									$prod_code = htmlentities($row['prod_code']);
									echo '<option value="'.$prod_code.'">'.$prod_code. '</option>';
								} 
							} 
						echo'				   
						</select>
					</td>
					<td></td>
					<td style="width: 159px" class="tdlabel">To Ticket No.</td>
					<td style="width: 19px">:</td> 
					 <td style="width: 304px">
						<select name="tobat" id="tobat" style="width: 250px" >'; ?>
						<?php
							$sql = "select prod_code from pro_cd_master where actvty = 'A' order by prod_code ";
							$sql_result = mysql_query($sql);
							   
							if(mysql_num_rows($sql_result)) 
							{
								while($row = mysql_fetch_assoc($sql_result)) 
								{ 
									$prod_code = htmlentities($row['prod_code']);
									echo '<option value="'.$prod_code.'">'.$prod_code. '</option>';
								} 
							} 
						echo'				   
						</select>
					</td>
				  </tr>
				   <tr>
				   <td colspan="8" align="center">
				   </td>
				  </tr>
				   <tr><td style="width: 6px"></td></tr>
				</table>';
				
	}
	if ($sel == '0')
	{
		echo "<font color=red><strong></strong> Please Select Option to print </strong></font>";
	}	
	
	if ($sel <> '0')
	{
		echo '<tr> <td colspan="8" align="center">'; 

		$var_accvie = 1;
		include("../Setting/btnprint.php");
	}
		echo'</td>
		</tr>
		</form>';   
?>
