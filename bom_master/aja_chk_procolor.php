<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
		
    if(!isset($_GET['clrcd']) || !$method = $_GET['clrcd']) exit; 
	$colourcd=mysql_real_escape_string($_GET['clrcd']);

    if ($colourcd <> "") {

      $var_sql = " SELECT count(*) as cnt from pro_clr_master ";
      $var_sql .= " WHERE clr_code = '$colourcd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red> This Product Colour Code Has Been Created</font>";
	  }else {
	  
        echo "<font color=green>This Product Colour Code Is Valid</font>";
      } 
    }  
?> 