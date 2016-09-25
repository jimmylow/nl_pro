<?php 

  include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
	$jobid=$_GET['supp_code'];
	$prod_code=$_GET['prod_code'];
	//$supp_code=intval($_GET['supp_code']);

    $query="SELECT prod_jobrate from pro_jobmodel where prod_jobid = '$jobid' and prod_code = '$prod_code'";
//echo $query;
    //$result=mysql_query($query);
    
					
	$receivedqty = 0;
	$sql_result2 = mysql_query($query) or die("Cant Get Total Received Qty ".mysql_error());;
	$row2 = mysql_fetch_array($sql_result2);
	$receivedqty = $row2[0];
					
					
	echo '<input class="inputtxt" value = "'.$receivedqty.'" name="jobrate" id ="jobrate" type="text" style="width: 128px;" tabindex="7" >';

?>
