<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['sordno']) || !$method = $_GET['sordno']) exit; 
    if(!isset($_GET['buyercd']) || !$method = $_GET['buyercd']) exit; 
	$sordnop = $_GET['sordno'];
	$sordbuy = $_GET['buyercd'];

    if ($sordnop <> "") {

      $var_sql = " SELECT count(*) as cnt from salesentry ";
      $var_sql .= " WHERE sordnobuyer = '$sordnop'  And sbuycd = '$sordbuy'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
	   echo "<font color=red>This Buyer Order No Is Use For This Buyer Code</font>";
	  }else {
	  
        echo "<font color=green>This Buyer Order No Is Valid</font>";
      } 
    }  
?> 