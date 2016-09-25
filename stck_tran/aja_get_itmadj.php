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
        	
	if ($itmcodedesc == ""){
		$row_array['desc']  = "";
	}else{	
    	$row_array['desc']  = $itmcodedesc;
	}

	$row_array['uom'] = $row2['uom'];
	$row_array['bal'] = number_format($onhnd,"2",".","");
    	
    echo json_encode($row_array);
?>