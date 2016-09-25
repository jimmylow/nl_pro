<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT * FROM rawmat_subcode WHERE rm_code REGEXP '^$param' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
    	$sql = "select uom, category, remark from rawmat_master  ";
        $sql .= " where rm_code ='".htmlentities($row['main_code'])."'";
        $sql_result = mysql_query($sql);
        $row1 = mysql_fetch_array($sql_result);
        $catcd = $row1[1];
        
        $sql = "select mark, spread, cut, bundle from cat_master ";
        $sql .= " where cat_code ='".$catcd."'";
        $sql_result = mysql_query($sql);
        $row3 = mysql_fetch_array($sql_result);
             
        $sql = "select colour_desc from colour_master ";
        $sql .= " where colour_code ='".$row['colour']."'";
        $sql_result = mysql_query($sql);
        $row2 = mysql_fetch_array($sql_result);

        $row_array['rm_code'] = $row['rm_code'];
        
        if ($row2[0] == ""){
        	$row_array['colour'] = " ";
        }else{	
        	$row_array['colour'] = $row2[0];
        }
        if ($row['size'] == ""){
          	$row_array['size'] = " ";
        }else{
        	$row_array['size'] = $row['size'];
        }
        if ($row['description'] == ""){	
        	 $row_array['remark'] = " ";
		}else{
        	$row_array['remark'] = stripslashes(mysql_real_escape_string($row['description']));
        }
        
        $sql2 = "select sum(totalqty) from rawmat_tran ";
        $sql2 .= " where item_code ='".mysql_real_escape_string($row['rm_code'])."' ";
        $sql2 .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";

        $sql_result2 = mysql_query($sql2);
        $row2 = mysql_fetch_array($sql_result2);        
        if ($row2[0] == "" or $row2[0] == null){ 
          $row2[0]  = 0.00;
        }
        $onhnd = $row2[0];
        
        $sql4 = "select sum(bookqty-sumrelqty) from booktab02 ";
        $sql4 .= " where bookitm ='".htmlentities($row['rm_code'])."' ";
        $sql4 .= " and compflg = 'N'";
        $sql_result4 = mysql_query($sql4);
        $row4 = mysql_fetch_array($sql_result4);        
        if ($row4[0] == "" or $row4[0] == null){ 
          $row4[0]  = 0.00;
        }
        $currbk = $row4[0];
        $row_array['avail'] = number_format(($onhnd - $currbk),"2",".","");
        
        if ($row1[0] == " "){
        	$row_array['uom'] = " ";
        }else{	
        	$row_array['uom'] = $row1[0];
        }
        
        ///cost price-------------------------------------------------
		$sqlv1  = "select cost_price";
		$sqlv1 .= " from rawmat_subcode";
		$sqlv1 .= " where rm_code ='".htmlentities($row['rm_code'])."'";
		$sql_resultv1 = mysql_query($sqlv1);
		$rowv1 = mysql_fetch_array($sql_resultv1);
		if ($rowv1[0] == "" or $rowv1[0] == null){ 
			$rowv1[0]  = 0.00;
		}
		$oavgcst = $rowv1[0];
		//-------------------------------------------------------------------------
        	
        $row_array['mark'] = $row3[0];
        $row_array['spread'] = $row3[1];
        $row_array['cut'] = $row3[2];
        $row_array['bundle'] = $row3[3];
        $row_array['cost'] = $oavgcst;
        
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
