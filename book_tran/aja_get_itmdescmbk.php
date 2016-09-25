<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['itm']) || !$method = $_GET['itm']) exit;
	$vitmcode = $_GET['itm'];
	
	$sql5 = "select count(*) from rawmat_subcode ";
    $sql5 .= " where rm_code ='".$vitmcode."'";
    $sql_result5 = mysql_query($sql5);
    $row5 = mysql_fetch_array($sql_result5);
    $cntcd = $row5[0];
        
	$sql = "select main_code, description from rawmat_subcode ";
    $sql .= " where rm_code ='".$vitmcode."'";
    $sql_result = mysql_query($sql);
    $row1 = mysql_fetch_array($sql_result);
    $mainitmcode = $row1['main_code'];
    $itmcodedesc = $row1['description'];
 
    $sq12 = "select uom from rawmat_master ";
    $sq12 .= " where rm_code ='".$mainitmcode."'";
    $sql_result2 = mysql_query($sq12);
    $row2 = mysql_fetch_array($sql_result2);
    
	$sql4 = "select sum(totalqty) from rawmat_tran ";
    $sql4 .= " where item_code ='".$vitmcode."' ";
    $sql4 .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
    $sql_result4 = mysql_query($sql4);
    $row4 = mysql_fetch_array($sql_result4);        
    if ($row4[0] == "" or $row4[0] == null){ 
          $row4[0]  = 0.00;
    }
    $onhnd = $row4[0];
        
    $sql3 = "select sum(bookqty-sumrelqty) from booktab02 ";
    $sql3 .= " where bookitm ='".$vitmcode."' ";
    $sql3 .= " and compflg = 'N'";
    $sql_result3 = mysql_query($sql3);
    $row3 = mysql_fetch_array($sql_result3);        
    if ($row3[0] == "" or $row3[0] == null){ 
        $row3[0]  = 0.00;
    }
    $currbk = $row3[0];
    $abvil = ($onhnd - $currbk);
	
	if ($itmcodedesc == ""){
		$row_array['desc']  = "";
	}else{	
    	$row_array['desc']  = $itmcodedesc;
	}
	$row_array['uom']   = $row2['uom'];
	$row_array['avail'] = number_format($abvil,"2",".","");
	$row_array['cdcnt'] = $cntcd;
    	
    echo json_encode($row_array);
    
?>