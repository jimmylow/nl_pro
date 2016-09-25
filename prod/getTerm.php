<?php
	include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);

	$supp_code=$_GET['supp_code'];
	$frdate=$_GET['frdate'];
	$todate=$_GET['todate'];

	$frdate= date('Y-m-d', strtotime($frdate));		
	$todate= date('Y-m-d', strtotime($todate));	



	
	//test here//
	$sql = "SELECT terms, term_desc from supplier_master, term_master ";
	$sql.= "  WHERE terms = term_code ";
	$sql.= " AND supplier_code = '". $supp_code."'";
     $result = mysql_query($sql) or die ("Error : ".mysql_error());
	//echo $sql;
	//test here//
     $data = mysql_fetch_object($result); 
    
     $terms = $data->terms;     
     $term_desc = $data->term_desc;    
  
    echo $terms."^".$term_desc;       
   
	mysql_close($db_link);



?> 