<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT * FROM rawmat_subcode WHERE rm_code REGEXP '^$param' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
        
        $row_array['rm_code'] = htmlentities($row['rm_code']);
        
        if (empty($row['description'])){ 
        	$row_array['desc'] = ""; 
        } else { 
        	$row_array['desc'] = htmlspecialchars_decode($row['description']); 
        }
        
        $sql1 = "select uom from rawmat_master  ";
        $sql1 .= " where rm_code ='".$row['main_code']."'";
        $sql_result1 = mysql_query($sql1);
        $row1 = mysql_fetch_array($sql_result1);
        
        if (empty($row1[0])){
        	$row_array['uom'] = "";
        }else{	
        	$row_array['uom'] = htmlentities($row1[0]);
        }
        
		$sql2 = "select sum(totalqty) from rawmat_tran ";
        $sql2 .= " where item_code ='".htmlentities($row['rm_code'])."' ";
        $sql2 .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";

        $sql_result2 = mysql_query($sql2);
        $row2 = mysql_fetch_array($sql_result2);        
        if ($row2[0] == "" or $row2[0] == null){ 
          $row2[0]  = 0.00;
        }
        $onhnd = $row2[0];
        
        $sql3 = "select sum(bookqty-sumrelqty) from booktab02 ";
        $sql3 .= " where bookitm ='".htmlentities($row['rm_code'])."' ";
        $sql3 .= " and compflg = 'N'";
        $sql_result3 = mysql_query($sql3);
        $row3 = mysql_fetch_array($sql_result3);        
        if ($row3[0] == "" or $row3[0] == null){ 
          $row3[0]  = 0.00;
        }
        $currbk = $row3[0];
        $row_array['avail'] = number_format(($onhnd - $currbk),"2",".","");
        
        array_push( $return_arr, $row_array );
    }
   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
