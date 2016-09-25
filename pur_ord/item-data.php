<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT * FROM rawmat_subcode WHERE rm_code REGEXP '^$param' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
    	$sql = "select uom, description from rawmat_master  ";
        $sql .= " where rm_code ='".$row['main_code']."'";
        $sql_result = mysql_query($sql); 
        $row1 = mysql_fetch_array($sql_result);
        
        $row_array['rm_code'] = $row['rm_code'];
        
        $row_array['uom'] = $row1[0];
        if (empty($row['description'])) { $row_array['desc'] = ""; } else { $row_array['desc'] = htmlspecialchars_decode($row['description']); }
        
        array_push( $return_arr, $row_array );  
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
