<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	//if(!isset($_GET['itmcod']) || !$method = $_GET['itmcod']) exit;
	//if(!isset($_GET['ref']) || !$method = $_GET['ref']) exit;
	//if(!isset($_GET['refd']) || !$method = $_GET['refd']) exit;
	
	$vitmcode = htmlentities($_GET['itmcod']);
	$vref     = $_GET['ref'];
	$vrefd    = $_GET['refd'];
        
	$sql = "select description from rawmat_subcode ";
    $sql .= " where rm_code ='".$vitmcode."'";
    $sql_result = mysql_query($sql);
    $row1 = mysql_fetch_array($sql_result);
    $itmcodedesc = $row1['description'];

    switch ($vrefd)
	{
		case "0":
			$var_sql  = " SELECT sum(totalqty) from rawmat_opening_tran ";
    		$var_sql .= " WHERE rm_opening_id = '$vref'";
    		$var_sql .= " AND   item_code = '$vitmcode'";
			break;
		case "1":
			$var_sql  = " SELECT sum(totalqty) from rawmat_receive_tran ";
    		$var_sql .= " WHERE rm_receive_id = '$vref'";
    		$var_sql .= " AND   item_code = '$vitmcode'";
			break;
  		case "2":
  			$var_sql = " SELECT sum(y.totalqty) from rawmat_receive_tran y, rawmat_receive x ";
    		$var_sql .= " WHERE x.rm_receive_id = y.rm_receive_id";
    		$var_sql .= " AND   x.invno = '$vref'";
    		$var_sql .= " AND   y.item_code = '$vitmcode'";
  			break;	
		case "3":
			$var_sql = " SELECT sum(totalqty) from rawmat_receive_tran ";
    		$var_sql .= " WHERE refno = '$vref'";
    		$var_sql .= " AND   item_code = '$vitmcode'";
  			break;	
		default:
 			$var_sql  = "";
	} 
	if ($var_sql != ""){
		$sql_resultq = mysql_query($var_sql);
    	$rowq = mysql_fetch_array($sql_resultq);
    	$itmqty = $rowq[0];
    }else{
    	$itmqty = 0;
    }	

    $row_array['desc'] = $itmcodedesc;
	$row_array['qty'] = $itmqty;
	$row_array['sql'] = $var_sql;
	
    echo json_encode($row_array);
    
?>