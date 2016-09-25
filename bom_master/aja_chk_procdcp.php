<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");	
		
    if(!isset($_GET['procd']) || !$method = $_GET['procd']) exit;
     
	$bprocd = $_GET['procd'];

    if ($bprocd <> "") 
    {

       $var_sql = " SELECT count(*) as cnt from pro_cd_master ";
   	   $var_sql .= " WHERE prod_code = '$bprocd'";
       $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
       $res_id = mysql_fetch_object($query_id);

       if ($res_id->cnt > 0 ) 
       {
	   		echo "1";
	    }else{
        	echo "0";
      	} 
    }  
?> 