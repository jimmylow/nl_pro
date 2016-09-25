<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['itmcod']) || !$method = $_GET['itmcod']) exit;
	$vitmcode = $_GET['itmcod'];
	       
	$sql = "select productcode, productqty from sew_entry ";
    $sql .= " where ticketno ='".$vitmcode."'";
    $sql_result = mysql_query($sql);
    $row1 = mysql_fetch_array($sql_result);
    $productcode = $row1['productcode'];
    $productqty = $row1['productqty'];
    //$productqty = stripslashes(mysql_real_escape_string($row1['productqty']));



	$row_array['productqty'] = $productqty;
	$row_array['productcode'] = $productcode;
    	
   echo json_encode($row_array);
?>