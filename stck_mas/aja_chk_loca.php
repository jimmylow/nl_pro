<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");	
	
    if(!isset($_GET['lotcd']) || !$method = $_GET['lotcd']) exit; 
	$locacd=$_GET['lotcd'];
	

    if (!empty($locacd)) 
    {
      $var_sql = " SELECT count(*) as cnt from stk_location ";
      $var_sql .= " WHERE loca_code = '$locacd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) { 
	   echo "1";
	  }else {
        echo "0";
      } 
    }
?> 