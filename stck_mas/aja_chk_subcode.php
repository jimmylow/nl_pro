<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['rawmatcdg']) || !$method = $_GET['rawmatcdg']) exit; 
	$rawmatcdg=$_GET['rawmatcdg'];

    if ($rawmatcdg <> "") {

      $var_sql = " SELECT count(*) as cnt from rawmat_subcode";
      $var_sql .= " WHERE rm_code = '$rawmatcdg'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
	   echo "<font color=red> This Raw Material Sub-Code Has Been Use</font>";
	  }else {
       echo "<font color=green>This Raw Material Sub-Code Is Valid</font>";
      } 
    }  
?> 