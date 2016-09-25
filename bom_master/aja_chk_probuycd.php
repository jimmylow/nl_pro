<?php
    include("../Setting/Configifx.php");
    include("../Setting/Connection.php");
    		
    if(!isset($_GET['probuycd']) || !$method = $_GET['probuycd']) exit;
    if(!isset($_GET['pre']) || !$method = $_GET['pre']) exit;  
	
	$probcd = $_GET['probuycd'];
	$pre = $_GET['pre'];

    if ($probcd <> "") {

      $var_sql  = " SELECT count(*) as cnt from pro_buy_master ";
      $var_sql .= "  WHERE pro_buy_code = '$probcd'";
      $var_sql .= "  And pro_buy_pre = '$pre'";
      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
   
      if ($res_id->cnt > 0 ) {
     	echo 1;
	  }else {
	  	echo 0;
      } 
    }  
?> 