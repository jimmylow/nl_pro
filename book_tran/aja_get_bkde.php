<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['itmcod']) || !$method = $_GET['itmcod']) exit;
	if(!isset($_GET['ref']) || !$method = $_GET['ref']) exit;
	
	$vitmcode = htmlentities($_GET['itmcod']);
	$vref     = $_GET['ref'];
        
	$sql = "select itmdesc, (bookqty - sumrelqty) from booktab02 ";
    $sql .= " where bookitm ='".$vitmcode."' and bookno = '$vref '";
    $sql_result = mysql_query($sql);
    $row1 = mysql_fetch_array($sql_result);
    $itmcodedesc = $row1[0];
    $qtybook = $row1[1];
    if (empty($qtybook)){$qtybook = 0;}

    $row_array['desc'] = $itmcodedesc;
	$row_array['qty'] = $qtybook;

    echo json_encode($row_array);
    
?>