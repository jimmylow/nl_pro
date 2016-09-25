<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
    if(!isset($_GET['procd']) || !$method = $_GET['procd']) exit; 
	$procode=$_GET['procd'];

    if ($procode <> "") {

      $var_sql = " SELECT count(*) as cnt from pro_cd_master";
      $var_sql .= " WHERE prod_code = '$procode'";
      $result=mysql_query($var_sql);
      $row = mysql_fetch_array($result);
	  if ($row[0] == 0){
    	echo "0";
      }else{
        echo "1";
      }    	
    }  
?> 