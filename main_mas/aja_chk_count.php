<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['countcd']) || !$method = $_GET['countcd']) exit; 
	$countcd=mysql_real_escape_string($_GET['countcd']);

    if ($countcd <> "") {

      $var_sql = " SELECT count(*) as cnt from country_master ";
      $var_sql .= " WHERE country_code = '$countcd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
   
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Country Code Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Country Code Is Valid</font>";
      } 
    }  
?> 