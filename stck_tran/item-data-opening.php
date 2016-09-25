<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT * FROM rawmat_subcode WHERE rm_code REGEXP '^$param' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
    	$sql = "select uom, category, remark from rawmat_master  ";
        $sql .= " where rm_code ='".$row['main_code']."'";
        $sql_result = mysql_query($sql);
        $row1 = mysql_fetch_array($sql_result);
        $catcd = $row1[1];
        
        $sql = "select sum(totalqty) from rawmat_tran ";
        $sql .= " where item_code ='".$row['rm_code']."' and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        $sql_result = mysql_query($sql);
        $row3 = mysql_fetch_array($sql_result);
        
                    
        $sql = "select colour_desc from colour_master ";
        $sql .= " where colour_code ='".$row['colour']."'";
        $sql_result = mysql_query($sql);
        $row2 = mysql_fetch_array($sql_result);

        $row_array['rm_code'] = $row['rm_code'];
        
        $sql = "select price from rawmat_price_trans ";
        $sql .= " where rm_code ='".$row['rm_code']."' and id='1' ";
        $sql_result = mysql_query($sql);
        $row4 = mysql_fetch_array($sql_result);
        
        if ($row3[0] == "" or $row3[0] == null){ //receive, adjustment, issue
          $row3[0]  = 0;
        }
        
        if ($row4[0] == "" or $row4[0] == null){ //rawmat price control 
          $row4[0]  = 0;
        }
        
        
        if ($row2[0] == ""){
        	$row_array['colour'] = " ";
        }else{	
        	$row_array['colour'] = htmlentities($row2[0]);
        }
        if ($row['size'] == ""){
          	$row_array['size'] = " ";
        }else{
        	$row_array['size'] = htmlentities($row['size']);
        }
        if ($row1[2] == ""){	
        	 $row_array['remark'] = " ";
		}else{
        	$row_array['remark'] = htmlentities($row1[2]);
        }
        $row_array['uom'] = htmlentities($row1[0]);
        
        $row_array['openingcost'] = htmlentities($row4[0]);

        
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
