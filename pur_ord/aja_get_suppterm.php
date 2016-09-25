<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['s']) || !$method = $_GET['s']) exit; 
	$suppno = $_GET['s'];

    if ($suppno <> "") {

      $var_sql = " SELECT terms from supplier_master";
      $var_sql .= " WHERE supplier_code = '$suppno'";
      $result=mysql_query($var_sql);
      $row = mysql_fetch_array($result);
      $suppterm = $row[0];
      
      $var_sql = " SELECT term_desc from term_master";
      $var_sql .= " WHERE term_code = '$suppterm'";
      $result=mysql_query($var_sql);
      $row = mysql_fetch_array($result);
      $termde = $row[0];
      
      echo json_encode(array("a" => "$suppterm", "b" => "$termde"));
      //echo $suppterm;   	
    }  
?> 