<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['custcdg']) || !$method = $_GET['custcdg']) exit; 
	$custcd=$_GET['custcdg'];

    if ($custcd <> "") {

      $var_sql = " SELECT count(*) as cnt from customer_master ";
      $var_sql .= " WHERE customer_code = '$suppcd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
	   echo "<font color=red> This Customer Code Has Been Use</font>";
	  }else {
       echo "<font color=green>This Customer Code Is Valid</font>";
      } 
    }  
?> 