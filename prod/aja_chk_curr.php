<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['currcd']) || !$method = $_GET['currcd']) exit; 
	$currcdcd=$_GET['currcd'];

    if ($currcdcd <> "") {

      $var_sql = " SELECT count(*) as cnt from currency_master ";
      $var_sql .= " WHERE currency_code = '$currcdcd'";
      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Currency Code Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Currency Code Is Valid</font>";
      } 
    }  
?> 