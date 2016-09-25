<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");	
		
    if(!isset($_GET['propre']) || !$method = $_GET['propre']) exit;
    if(!isset($_GET['codenu']) || !$method = $_GET['codenu']) exit; 
    if(!isset($_GET['proty']) || !$method = $_GET['proty']) exit; 
     
	$bpropre = $_GET['propre'];
	$btype = $_GET['proty'];
	$bcodenu = $_GET['codenu'];
	
	$bcodenu  = substr($bcodenu , 0, 4); 
	
    if ($bpropre <> "" ||  $bcodenu <> "" || $btype <> "") 
    {

       $var_sql = " SELECT count(*) as cnt from pro_cat_master ";
   	   $var_sql .= " WHERE pro_buy_cd = '$bpropre' and pro_type_cd = '$btype'";
   	   $var_sql .= " And   '$bcodenu' between pro_cat_frnum and pro_cat_tonum";
       $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
       $res_id = mysql_fetch_object($query_id);
   
       if ($res_id->cnt > 0 ) {
	   	echo "0";
	   }else {
        echo "1";
       } 
    }  
?> 