<?php
    include("../Setting/Configifx.php");
    include("../Setting/Connection.php");
    		
    if(!isset($_GET['jobidf']) || !$method = $_GET['jobidf']) exit; 
	$jobidfi = $_GET['jobidf'];

    if ($jobidfi <> "") {

      $var_sql = " SELECT count(*) as cnt from jobfile_master ";
      $var_sql .= " WHERE jobfile_id = '$jobidfi'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
   
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Job File ID Has Been Use</font>";
	  }else {
	  
        echo "<font color=green>This Job File ID Code Is Valid</font>";
      } 
    }  
?> 