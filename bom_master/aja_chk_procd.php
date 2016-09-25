<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");	
		
    if(!isset($_GET['prosiz']) || !$method = $_GET['prosiz']) exit;
    if(!isset($_GET['procol']) || !$method = $_GET['procol']) exit; 
    if(!isset($_GET['cdnum']) || !$method = $_GET['cdnum']) exit;
    if(!isset($_GET['pre']) || !$method = $_GET['pre']) exit;  
     
	$bprosiz  = $_GET['prosiz'];
	$bprocol = $_GET['procol'];
	$bcdnum = $_GET['cdnum'];
	$bppre = $_GET['pre'];

    if ($bprosiz <> "" || $bprocol <> "" || $bcdnum <> "" || $bppre <> "") 
    {

       $var_sql = " SELECT count(*) as cnt from pro_cd_master ";
   	   $var_sql .= " WHERE prod_size = '$bprosiz'";
   	   $var_sql .= " And   prod_col = '$bprocol'      And pronumcode = '$bcdnum'";
   	   $var_sql .= " And   prod_catpre ='$bppre'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
     
	   echo "1";
	  }else {
	  
        echo "0";
      } 
    }  
?> 