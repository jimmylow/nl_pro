<?php 

  include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
	$productcode=$_GET['productcode'];
	$jobcode=$_GET['jobcode'];
	$vid=$_GET['vid'];

  $query="SELECT prod_jobrate from pro_jobmodel where prod_jobid = '$jobcode' and prod_code = '$productcode'";
				
	$prod_jobrate = 0;
	$sql_result2 = mysql_query($query) or die("Cant Get Total Received Qty ".mysql_error());;
	$row2 = mysql_fetch_array($sql_result2);
	$prod_jobrate = $row2[0];


	echo '<input class="inputtxt" value = "'.$prod_jobrate .'" name="jobrate[]" id ="jobrate'.$vid.'" type="text" style="width: 50px;">';

?>
