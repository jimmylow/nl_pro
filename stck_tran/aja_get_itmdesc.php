<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['itmcod']) || !$method = $_GET['itmcod']) exit;
	$vitmcode = $_GET['itmcod'];
        
	$sql = "select main_code from rawmat_subcode ";
    $sql .= " where rm_code ='".$vitmcode."'";
    $sql_result = mysql_query($sql);
    $row1 = mysql_fetch_array($sql_result);
    $mainitmcode = $row1['main_code'];
 
    $sq12 = "select description, uom, category from rawmat_master ";
    $sq12 .= " where rm_code ='".$mainitmcode."'";
    $sql_result2 = mysql_query($sq12);
    $row2 = mysql_fetch_array($sql_result2);
    
    $sq13 = "select mark, cut, spread, bundle from cat_master ";
    $sq13 .= " where cat_code ='".$row2['category']."'";
    $sql_result3 = mysql_query($sq13);
    $row3 = mysql_fetch_array($sql_result3);

    $row_array['desc'] = htmlentities($row2['description']);
	$row_array['uom'] = $row2['uom'];
	$row_array['mark'] = $row3['mark'];
	$row_array['cut'] = $row3['cut'];
	$row_array['spread'] = $row3['spread'];
	$row_array['bundle'] = $row3['bundle'];
    	
    echo json_encode($row_array);
    
?>