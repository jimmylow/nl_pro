<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");		
		
    if(!isset($_GET['typecd']) || !$method = $_GET['typecd']) exit; 
	$typcd=$_GET['typecd'];

    if ($typcd <> "") {

      $var_sql = " SELECT count(*) as cnt from protype_master ";
      $var_sql .= " WHERE type_code = '$typcd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
   
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Type Code Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Type Code Is Valid</font>";
      } 
    }  
?> 