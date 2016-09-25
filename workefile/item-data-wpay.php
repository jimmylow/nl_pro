<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

    $return_arr = array();
    $param = $_GET["term"];

    $fetch = mysql_query("SELECT * FROM jobfile_master WHERE jobfile_id REGEXP '^$param' LIMIT 10");

    /* Retrieve and store in array the results of the query.*/
    while ($row = mysql_fetch_array($fetch, MYSQL_ASSOC)) {
    
		$row_array['rm_code'] = $row['jobfile_id'];
		$row_array['remark'] = $row['jobfile_desc'];
		$row_array['openingcost'] = $row['jobfile_rate'];
        


        
        array_push( $return_arr, $row_array );
    }

   
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);
?>
