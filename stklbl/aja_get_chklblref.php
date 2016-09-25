<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");			
      
	$optsel = $_GET['opt'];
	$selref = $_GET['ref'];
	$rtnval = "0";
	

	switch ($optsel)
	{
		case "0":
			$var_sql = " SELECT count(*) as cnt from rawmat_opening ";
    		$var_sql .= " WHERE rm_opening_id = '$selref'";
    		break;
    		
		case "1":
			$var_sql = " SELECT count(*) as cnt from rawmat_receive ";
    		$var_sql .= " WHERE rm_receive_id = '$selref'";
  			break;
  			
  		case "2":
  			$var_sql = " SELECT count(*) as cnt from rawmat_receive ";
    		$var_sql .= " WHERE invno = '$selref'";
  			break;
  			
		case "3":
			$var_sql = " SELECT count(*) as cnt from rawmat_receive ";
    		$var_sql .= " WHERE refno = '$selref'";
  			break;	
  			
		default:
 			$rtnval;
	}
    	
    $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
    $res_id = mysql_fetch_object($query_id);

    if ($res_id->cnt > 0 ) {
    	$rtnval = 1;
	}else{
		$rtnval = 0;
	}
	echo $rtnval;
?> 