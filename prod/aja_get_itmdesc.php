<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
 
	if(!isset($_GET['itmcod']) || !$method = $_GET['itmcod']) exit;
	$vitmcode = $_GET['itmcod'];
	       
	$sql = "select main_code, description from rawmat_subcode ";
    $sql .= " where rm_code ='".$vitmcode."'";
    $sql_result = mysql_query($sql);
    $row1 = mysql_fetch_array($sql_result);
    $mainitmcode = $row1['main_code'];
    $itmcodedesc = stripslashes(mysql_real_escape_string($row1['description']));

    $sq12 = "select uom, category from rawmat_master ";
    $sq12 .= " where rm_code ='".$mainitmcode."'";
    $sql_result2 = mysql_query($sq12);
    $row2 = mysql_fetch_array($sql_result2);
    
    $sq15 = "select mark, cut, spread, bundle from cat_master ";
    $sq15 .= " where cat_code ='".$row2['category']."'";
    $sql_result5 = mysql_query($sq15);
    $row5 = mysql_fetch_array($sql_result5);
    if ($row5['mark'] == "" or $row5['mark'] == null){ 
          $row5['mark']  = 0.00;
    }
    $mark = $row5['mark'];
	if ($row5['cut'] == "" or $row5['cut'] == null){ 
          $row5['cut']  = 0.00;
    }
    $cut = $row5['cut'];
	if ($row5['spread'] == "" or $row5['spread'] == null){ 
          $row5['spread']  = 0.00;
    }
    $spread = $row5['spread'];
	if ($row5['bundle'] == "" or $row5['bundle'] == null){ 
          $row5['bundle']  = 0.00;
    }
    $bundle = $row5['bundle'];
    
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
	
	///cost price-------------------------------------------------
	$sqlv1  = "select cost_price";
	$sqlv1 .= " from rawmat_subcode";
	$sqlv1 .= " where rm_code ='".$vitmcode."'";
	$sql_resultv1 = mysql_query($sqlv1);
	$rowv1 = mysql_fetch_array($sql_resultv1);
	if ($rowv1[0] == "" or $rowv1[0] == null){ 
		$rowv1[0]  = 0.00;
	}
	if  ($rowv1[0] == 0){
		$rowv1[0] = "";
	}	
	$oavgcst = $rowv1[0];
	//-------------------------------------------------------------------------


	$row_array['uom'] = $row2['uom'];
	$row_array['avail'] = number_format($abvil,"2",".","");
	$row_array['mark'] = $mark;
	$row_array['cut'] = $cut;
	$row_array['spread'] = $spread;
	$row_array['bundle'] = $bundle;
	$row_array['cost'] = $oavgcst;
    	
   echo json_encode($row_array);
?>