<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT * FROM rawmat_subcode WHERE rm_code REGEXP '^$param' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
    	$sql = "select uom, category, description from rawmat_master  ";
        $sql .= " where rm_code ='".$row['main_code']."'";
        $sql_result = mysql_query($sql);
        $row1 = mysql_fetch_array($sql_result);
        $catcd = $row1[1];
        
        $sql = "select sum(totalqty) from rawmat_tran ";
        $sql .= " where item_code ='".$row['rm_code']."' and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        $sql_result = mysql_query($sql);
        $row3 = mysql_fetch_array($sql_result);
        
        //$sql = "select sum(totalqty) from rawmat_tran ";
        //$sql .= " where item_code ='".$row['rm_code']."' and tran_type in ('ISS')";
        //$sql_result = mysql_query($sql);
        //$row4 = mysql_fetch_array($sql_result);
             
        $sql = "select colour_desc from colour_master ";
        $sql .= " where colour_code ='".$row['colour']."'";
        $sql_result = mysql_query($sql);
        $row2 = mysql_fetch_array($sql_result);

        $row_array['rm_code'] = $row['rm_code'];
        
        if ($row3[0] == "" or $row3[0] == null){ //receive, adjustment, issue -->issue trx will hv -ve value in rawmat_tran
          $row3[0]  = 0;
        }
        
        //if ($row4[0] == "" or $row4[0] == null){ //issue out
        //  $row4[0]  = 0;
        //}
        
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
        
        
       
        $row_array['uom'] = $row1[0];
        $row_array['mark'] = $row3[0];// - $row4[0];
        
        $sql = "select description from rawmat_subcode  ";
        $sql .= " where rm_code ='".$row['rm_code']."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
		if ($row[0] == ""){	
        	 $row_array['remark'] = " ";
		}else{
        	$row_array['remark'] =  stripslashes(mysql_real_escape_string($row[0]));
        }
        
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
