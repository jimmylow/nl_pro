<?php
    include("../Setting/Configifx.php");
    include("../Setting/Connection.php");
    		
    if(!isset($_GET['de']) || !$method = $_GET['de']) exit; 
	$dep = $_GET['de'];

    if ($dep <> "") {

      $var_sql = " SELECT count(*) as cnt from wor_detmas ";
      $var_sql .= " WHERE workid = '$dep'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
   
      if ($res_id->cnt > 0 ) {
	    echo "1";
	  }else {
        echo "0";
      } 
    }  
?> 