<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT * FROM pro_cd_master WHERE prod_code REGEXP '^$param' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
        $row_array['prod_code'] = $row['prod_code'];
        
        $sql = "select colour_desc from colour_master ";
        $sql .= " where colour_code ='".$row['prod_col']."'";
        $sql_result = mysql_query($sql);
        $row2 = mysql_fetch_array($sql_result);
        
        if ($row2[0] == ""){
        	$row_array['colour'] = " ";
        }else{	
        	$row_array['colour'] = $row2[0];
        }
        if ($row['prod_size'] == ""){
          	$row_array['size'] = " ";
        }else{
        	$row_array['size'] = $row['prod_size'];
        }
        if ($row['prod_type'] == ""){	
        	 $row_array['prtype'] = " ";
		}else{
        	$row_array['prtype'] = $row['prod_type'];
        }
        $row_array['pruom'] = $row['prod_uom'];
        $row_array['prdesc'] = $row['prod_desc'];
                
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
