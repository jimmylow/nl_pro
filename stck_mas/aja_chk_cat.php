<?php
    include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
   	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
    if(!isset($_GET['catcd']) || !$method = $_GET['catcd']) exit; 
	$catrcd=$_GET['catcd'];

    if ($catrcd <> "") {

      $var_sql = " SELECT count(*) as cnt from cat_master ";
      $var_sql .= " WHERE cat_code = '$catrcd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Categoy Code Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Categoy Code Is Valid</font>";
      } 
    }  
?> 