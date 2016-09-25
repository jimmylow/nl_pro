<?php
    include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
    if(!isset($_GET['rm_code']) || !$method = $_GET['rm_code']) exit; 
    $rm_code=$_GET['rm_code'];
    $supplier_code = '';
    $supp_id=intval($_GET['supp_code']);
    
    $sql = " SELECT supplier_code from supplier_master";
    $sql .= " WHERE supp_id = '$supp_id'";

    $dumsql= mysql_query($sql) or die(mysql_error());
     while($row = mysql_fetch_array($dumsql))
     {
     	$supplier_code = $row['supplier_code'];        
     }
   
    if ($rm_code <> "" && $rm_code <> "-Select Main Code-" ) {

      $var_sql = " SELECT count(*) as cnt from rawmat_price_ctrl ";
      $var_sql .= " WHERE rm_code = '$rm_code'";
      $var_sql .= " AND supplier = '$supplier_code'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);
     
      if ($res_id->cnt > 0 ) {
     
        echo "<font color=red> Price Control For This Sub Code Number & Supplier has been key-in </font>";
      }else { 
        echo "<font color=green></font>";
      } 
    }  
?> 